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


if (!function_exists("RegRegisterDomain")) {
	require ROOTDIR . "/includes/registrarfunctions.php";
}


if ($domainid) {
	$result = select_query_i("tbldomains", "id", array("id" => $domainid));
}
else {
	$result = select_query_i("tbldomains", "id", array("domain" => $domain));
}

$data = mysqli_fetch_array($result);
$domainid = $data[0];

if (!$domainid) {
	$apiresults = array("result" => "error", "message" => "Domain Not Found");
	return false;
}

$params = array("domainid" => $domainid);
$values = RegRegisterDomain($params);

if ($values['error']) {
	$apiresults = array("result" => "error", "message" => "Registrar Error Message", "error" => $values['error']);
	return false;
}

$apiresults = array_merge(array("result" => "success"), $values);
?>