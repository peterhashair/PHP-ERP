<?php
/** RA - Version 0.1 **/


if (!defined("RA")) {
	exit("This file cannot be accessed directly");
}

$apiresults = array("result" => "success");
update_query("tbladmins", array("notes" => $notes), array("id" => $_SESSION['adminid']));
?>