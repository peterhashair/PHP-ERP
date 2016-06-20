<?php
/**
 *
 * @ RA
 *
 * 
 * 
 * 
 * 
 *
 **/

if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}


if (!function_exists("RegSaveRegistrarLock")) {
	require ROOTDIR . "/includes/registrarfunctions.php";
}


if ($domainid) {
	$where = array("id" => $domainid);
}
else {
	$where = array("domain" => $domain);
}

$result = select_query_i("tbldomains", "id,domain,registrar,registrationperiod", $where);
$data = mysqli_fetch_array($result);
$domainid = $data[0];

if (!$domainid) {
	$apiresults = array("result" => "error", "message" => "Domain Not Found");
	return false;
}

$domain = $data['domain'];
$registrar = $data['registrar'];
$regperiod = $data['registrationperiod'];
$domainparts = explode(".", $domain, 2);
$params = array();
$params['domainid'] = $domainid;
$params['sld'] = $domainparts[0];
$params['tld'] = $domainparts[1];
$params['regperiod'] = $regperiod;
$params['registrar'] = $registrar;
$params['transfertag'] = $newtag;
$values = RegReleaseDomain($params);

if ($values['error']) {
	$apiresults = array("result" => "error", "message" => "Registrar Error Message", "error" => $values['error']);
	return false;
}

$apiresults = array_merge(array("result" => "success"), $values);
?>