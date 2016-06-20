<?php

if (!defined("RA"))
	die("This file cannot be accessed directly");

$reportdata["title"] = "Affiliates Overview";
$reportdata["description"] = "An overview of affiliates for the current year";

$reportdata["tableheadings"] = array('Affiliate ID','Affiliate Name','Visitors','Pending Commissions','Available to Withdraw','Withdrawn Amount','YTD Total Commissions Paid');

$result = select_query_i("tblaffiliates","tblaffiliates.id,tblaffiliates.clientid,tblaffiliates.visitors,tblaffiliates.balance,tblaffiliates.withdrawn,tblclients.firstname,tblclients.lastname,tblclients.companyname","","visitors","DESC","","tblclients ON tblclients.id=tblaffiliates.clientid");
while ($data = mysqli_fetch_array($result)) {

    $affid = $data['id'];
    $clientid = $data['clientid'];
    $visitors = $data['visitors'];
    $balance = $data['balance'];
    $withdrawn = $data['withdrawn'];
    $firstname = $data['firstname'];
    $lastname = $data['lastname'];
    $companyname = $data['companyname'];

    $name = $firstname.' '.$lastname;
    if ($companyname) $name .= ' ('.$companyname.')';

    $result2 = select_query_i("tblaffiliatespending","COUNT(*),SUM(tblaffiliatespending.amount)",array("affiliateid"=>$affid),"clearingdate","DESC","","tblaffiliatesaccounts ON tblaffiliatesaccounts.id=tblaffiliatespending.affaccid INNER JOIN tblhosting ON tblcustomerservices.id=tblaffiliatesaccounts.relid INNER JOIN tblservices ON tblservices.id=tblcustomerservices.packageid INNER JOIN tblclients ON tblclients.id=tblcustomerservices.userid");
    $data = mysqli_fetch_array($result2);
    $pendingcommissions = $data[0];
    $pendingcommissionsamount = $data[1];

    $result2 = select_query_i("tblaffiliateshistory","SUM(amount)","affiliateid=$affid AND date LIKE '".date("Y")."-%'");
    $data = mysqli_fetch_array($result2);
    $ytdtotal = $data[0];

    $currency = getCurrency($clientid);
    $pendingcommissionsamount = formatCurrency($pendingcommissionsamount);
    $ytdtotal = formatCurrency($ytdtotal);

    $reportdata["tablevalues"][] = array('<a href="affiliates.php?action=edit&id='.$affid.'">'.$affid.'</a>',$name,$visitors,$pendingcommissionsamount,$balance,$withdrawn,$ytdtotal);

}

?>