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


if (!function_exists("addClient")) {
	require ROOTDIR . "/includes/clientfunctions.php";
}


if (!function_exists("updateInvoiceTotal")) {
	require ROOTDIR . "/includes/invoicefunctions.php";
}


if (!function_exists("convertQuotetoInvoice")) {
	require ROOTDIR . "/includes/quotefunctions.php";
}

$result = select_query("tblquotes", "", array("id" => $quoteid));
$data = mysql_fetch_array($result);
$quoteid = $data['id'];

if (!$quoteid) {
	$apiresults = array("result" => "error", "message" => "Quote ID Not Found");
	return null;
}

$invoiceid = convertQuotetoInvoice($quoteid);
$apiresults = array("result" => "success", "invoiceid" => $invoiceid);
?>