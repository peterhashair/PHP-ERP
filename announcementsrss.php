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

define("CLIENTAREA", true);
require "init.php";

if ($ra->get_req_var("lcinfo")) {
	echo "<textarea cols=100 rows=4>License Key: " . $ra->get_license_key() . "
System URL: " . $CONFIG['SystemURL'] . "
System SSL URL: " . $CONFIG['SystemSSLURL'] . "</textarea>";
	exit();
}

$language = ((isset($_REQUEST['language']) && in_array($_REQUEST['language'], $ra->getValidLanguages())) ? $_REQUEST['language'] : "");
header("Content-Type: application/rss+xml; charset=" . $CONFIG['Charset']);
echo "<?xml version=\"1.0\" encoding=\"" . $CONFIG['Charset'] . "\"?>\n<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n<channel>\n<atom:link href=\"" . $CONFIG['SystemURL'] . "/announcementsrss.php\" rel=\"self\" type=\"application/rss+xml\" />\n<title><![CDATA[" . $CONFIG['CompanyName'] . "]]></title>\n<description><![CDATA[" . $CONFIG['CompanyName'] . " " . $_LANG['announcementstitle'] . " " . $_LANG['rssfeed'] . "]]></description>\n<link>" . $CONFIG['SystemURL'] . "/announcements.php</link>";
$result = select_query_i("tblannouncements", "*", array("published" => "on"), "date", "DESC");

while ($data = mysqli_fetch_array($result)) {
	$id = $data['id'];
	$date = $data['date'];
	$title = $data['title'];
	$announcement = $data['announcement'];
	$result2 = select_query_i("tblannouncements", "", array("parentid" => $id, "language" => $language));
	$data = mysqli_fetch_array($result2);

	if ($data['title']) {
		$title = $data['title'];
	}


	if ($data['announcement']) {
		$announcement = $data['announcement'];
	}

	$year = substr($date, 0, 4);
	$month = substr($date, 5, 2);
	$day = substr($date, 8, 2);
	$hours = substr($date, 11, 2);
	$minutes = substr($date, 14, 2);
	$seconds = substr($date, 17, 2);
	echo "
<item>
	<title><![CDATA[" . $title . "]]></title>
	<link>" . $CONFIG['SystemURL'] . "/announcements.php?id=" . $id . "</link>
    <guid>" . $CONFIG['SystemURL'] . "/announcements.php?id=" . $id . "</guid>
	<pubDate>" . date("r", mktime($hours, $minutes, $seconds, $month, $day, $year)) . "</pubDate>
	<description><![CDATA[" . $announcement . "]]></description>
</item>";
}

echo "
</channel>
</rss>";
?>