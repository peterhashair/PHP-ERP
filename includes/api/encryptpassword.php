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

$password = encrypt($_POST['password2']);
$apiresults = array("result" => "success", "password" => $password);
?>