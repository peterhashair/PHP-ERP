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


if (!function_exists("ServerUnsuspendAccount")) {
	require ROOTDIR . "/includes/modulefunctions.php";
}

$result = select_query("tblhosting", "packageid", array("id" => $_POST['accountid']));
$data = mysql_fetch_array($result);
$packageid = $data['packageid'];
$result = ServerUnsuspendAccount($_POST['accountid']);

if ($result == "success") {
	$apiresults = array("result" => "success");
	return 1;
}

$apiresults = array("result" => "error", "message" => $result);
?>