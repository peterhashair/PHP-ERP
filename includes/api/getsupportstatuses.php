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

$statuses = array();
$result = select_query("tblticketstatuses", "", "", "sortorder", "ASC");

while ($data = mysql_fetch_array($result)) {
	$statuses[$data['title']] = 0;
}

$apiresults = array("result" => "success", "totalresults" => count($statuses));
$where = "";

if ($deptid) {
	$where = "WHERE did='" . mysql_real_escape_string($deptid) . "'";
}

$result = full_query("SELECT status, COUNT(*) AS count FROM tbltickets " . $where . " GROUP BY status");

while ($data = mysql_fetch_array($result)) {
	$statuses[$data['status']] = $data['count'];
}

foreach ($statuses as $status => $ticketcount) {
	$apiresults['statuses']['status'][] = array("title" => $status, "count" => $ticketcount);
}

$responsetype = "xml";
?>