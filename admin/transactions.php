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
 * */
define("ADMINAREA", true);
require "../init.php";

if ($action == "edit") {
    $reqperm = "Edit Transaction";
} else {
    $reqperm = "List Transactions";
}

$aInt = new RA_Admin($reqperm);
$aInt->title = $aInt->lang("transactions", "title");
$aInt->sidebar = "billing";
$aInt->icon = "transactions";
$menuselect = "$('#menu').multilevelpushmenu('expand','Billing');";
$aInt->requiredFiles(array("gatewayfunctions", "invoicefunctions"));

if ($action == "add") {
    check_token("RA.admin.default");
    checkPermission("Add Transaction");

    if ($client) {
        $currency = 0;
    }


    if (!$invoiceids) {
        addTransaction($client, $currency, $description, $amountin, $fees, $amountout, $paymentmethod, $transid, $invoiceid, $date);

        if ($client && $addcredit) {
            if ($transid) {
                $description .= " (" . $aInt->lang("transactions", "transid") . (": " . $transid . ")");
            }

            insert_query("tblcredit", array("clientid" => $client, "date" => toMySQLDate($date), "description" => $description, "amount" => $amountin));
            update_query("tblclients", array("credit" => "+=" . $amountin), array("id" => (int) $client));
        }
    } else {
        $invoiceids = trim($invoiceids);

        if (substr($invoiceids, 0 - 1) == ",") {
            $invoiceids = substr($invoiceids, 0, 0 - 1);
        }

        $query = select_query_i("tblinvoices", "SUM(total)", "id IN (" . $invoiceids . ")");
        $data = mysqli_fetch_assoc($query);
        $invoicestotal = $data[0];
        $invoices = explode(",", $invoiceids);
        $totalleft = $amountin;
        $fees = round($fees / count($invoices), 2);
        foreach ($invoices as $invoiceid) {

            if (0 < $totalleft) {
                $result = select_query_i("tblinvoices", "total", array("id" => $invoiceid));
                $data = mysqli_fetch_array($result);
                $invoicetotal = $data[0];
                $result2 = select_query_i("tblaccounts", "SUM(amountin)", array("invoiceid" => $invoiceid));
                $data = mysqli_fetch_array($result2);
                $totalin = $data[0];
                $paymentdue = $invoicetotal - $totalin;

                if ($paymentdue < $totalleft) {
                    addInvoicePayment($invoiceid, $transid, $paymentdue, $fees, $paymentmethod, "", $date);
                    $totalleft -= $paymentdue;
                    continue;
                }

                addInvoicePayment($invoiceid, $transid, $totalleft, $fees, $paymentmethod, "", $date);
                $totalleft = 0;
                continue;
            }
        }


        if ($totalleft) {
            addInvoicePayment($invoiceid, $transid, $totalleft, $fees, $paymentmethod, "", $date);
        }
    }

    redir("added=true");
    exit();
}


if ($action == "save") {
    check_token("RA.admin.default");
    checkPermission("Edit Transaction");

    if ($client) {
        $currency = 0;
    }

    $date = toMySQLDate($date);
    update_query("tblaccounts", array("userid" => $client, "currency" => $currency, "date" => $date, "description" => $description, "amountin" => $amountin, "fees" => $fees, "amountout" => $amountout, "gateway" => $paymentmethod, "transid" => $transid, "invoiceid" => $invoiceid), array("id" => $id));
    logActivity("Modified Transaction - Transaction ID: " . $id);
    redir("saved=true");
    exit();
}


if ($action == "delete") {
    check_token("RA.admin.default");
    checkPermission("Delete Transaction");
    delete_query("tblaccounts", array("id" => $id));
    logActivity("Deleted Transaction - Transaction ID: " . $id);
    redir("deleted=true");
    exit();
}


if (!$action) {
    if ($added) {
        infoBox($aInt->lang("transactions", "transactionadded"), $aInt->lang("transactions", "transactionaddedinfo"));
    }


    if ($saved) {
        infoBox($aInt->lang("transactions", "transactionupdated"), $aInt->lang("transactions", "transactionupdatedinfo"));
    }


    if ($deleted) {
        infoBox($aInt->lang("transactions", "transactiondeleted"), $aInt->lang("transactions", "transactiondeletedinfo"));
    }

    $aInt->assign("infobox", $infobox);
    $jscode = "function doDelete(id) {
    if (confirm(\"" . $aInt->lang("transactions", "deletesure") . "\")) {
        window.location='" . $_SERVER['PHP_SELF'] . "?action=delete&id='+id+'" . generate_token("link") . "';
    }
}
";

    if (!count($_REQUEST)) {
        $within = $_REQUEST['within'] = "month";
    }


    $showoption.= "<option value=\"\">";
    $showoption.= $aInt->lang("transactions", "allactivity");
    $showoption.= "</option><option value=\"received\"";
    if ($_REQUEST['show'] == "received") {
        $showoption .=" SELECTED";
    }

    $showoption.= ">";
    $showoption.= $aInt->lang("transactions", "preceived");
    $showoption.= "</option><option value=\"sent\"";
    if ($_REQUEST['show'] == "sent") {
        $showoption.= " SELECTED";
    }

    $showoption.= ">";
    $showoption.= $aInt->lang("transactions", "psent");
    $showoption.= "</option>";

    $withinoption.= "</option><option value=\"week\"";

    if ($within == "week") {
        $withinoption.= " SELECTED";
    }

    $withinoption.= ">";
    $withinoption.= $aInt->lang("transactions", "pastweek");
    $withinoption.= "</option><option value=\"month\"";

    if ($within == "month") {
        $withinoption.= " SELECTED";
    }

    $withinoption.= ">";
    $withinoption.= $aInt->lang("transactions", "pastmonth");
    $withinoption.= "</option><option value=\"year\"";

    if ($within == "year") {
        $withinoption.= " SELECTED";
    }

    $withinoption.= ">";
    $withinoption.= $aInt->lang("transactions", "pastyear");
    $withinoption.= "</option><option>";

    if ($startdate) {
        $withinoption.= " SELECTED";
    }

    $withinoption.= ">Custom Date Range</option>";
    $date2 = getTodaysDate();

    $result = select_query_i("tblcurrencies", "", "", "code", "ASC");


    while ($data = mysqli_fetch_array($result)) {
        $currencyoption.= "<option value=\"" . $data['id'] . "\"";

        if ($data['default']) {
            $currencyoption.= " selected";
        }

        $currencyoption.= ">" . $data['code'] . "</option>";
    }


    $aInt->sortableTableInit("date", "DESC");
    $query = "";
    $where = array();

    if ($show == "received") {
        $where[] = "tblaccounts.amountin>0";
    } else {
        if ($show == "sent") {
            $where[] = "tblaccounts.amountout>0";
        }
    }


    if ($amount) {
        $where[] = "(tblaccounts.amountin='" . db_escape_string($amount) . "' OR tblaccounts.amountout='" . db_escape_string($amount) . "')";
    }


    if ($startdate) {
        $where[] = "tblaccounts.date>='" . toMySQLDate($startdate) . " 00:00:00'";
    }


    if ($enddate) {
        $where[] = "tblaccounts.date<='" . toMySQLDate($enddate) . " 23:59:59'";
    }


    if (!$startdate && !$enddate) {
        if ($within == "week") {
            $lastweek = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));
            $where[] = "tblaccounts.date>=" . $lastweek;
        } else {
            if ($within == "month") {
                $lastmonth = date("Ymd", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));
                $where[] = "tblaccounts.date>=" . $lastmonth;
            } else {
                if ($within == "year") {
                    $lastyear = date("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y") - 1));
                    $where[] = "tblaccounts.date>=" . $lastyear;
                }
            }
        }
    }


    if ($filtertransid) {
        $where[] = "tblaccounts.transid='" . db_escape_string($filtertransid) . "'";
    }


    if ($paymentmethod) {
        $where[] = "tblaccounts.gateway='" . db_escape_string($paymentmethod) . "'";
    }


    if ($filterdescription) {
        $where[] = "tblaccounts.description LIKE '%" . db_escape_string($filterdescription) . "%'";
    }


    if (count($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }

    $totals = array();
    $fullquery = "SELECT tblclients.currency,SUM(amountin),SUM(fees),SUM(amountout),SUM(amountin-fees-amountout) FROM tblaccounts,tblclients " . ($query ? $query . " AND" : "WHERE") . " tblclients.id=tblaccounts.userid GROUP BY tblclients.currency";
    $result = full_query_i($fullquery);

    while ($data = mysqli_fetch_array($result)) {
        $currency = $data['currency'];
        $totalin = $data[1];
        $totalfees = $data[2];
        $totalout = $data[3];
        $total = $data[4];
        $totals[$currency] = array("in" => $totalin, "fees" => $totalfees, "out" => $totalout, "total" => $total);
    }

    $fullquery = "SELECT currency,SUM(amountin),SUM(fees),SUM(amountout),SUM(amountin-fees-amountout) FROM tblaccounts " . ($query ? $query . " AND" : "WHERE") . " userid=0 GROUP BY currency";
    $result = full_query_i($fullquery);

    while ($data = mysqli_fetch_array($result)) {
        $currency = $data['currency'];
        $totalin = $data[1];
        $totalfees = $data[2];
        $totalout = $data[3];
        $total = $data[4];
        $totals[$currency]['in'] += $totalin;
        $totals[$currency]['fees'] += $totalfees;
        $totals[$currency]['out'] += $totalout;
        $totals[$currency]['total'] += $total;
    }

    $gatewaysarray = getGatewaysArray();
    $query .= " ORDER BY tblaccounts.date DESC,tblaccounts.id DESC";
    $result = full_query_i("SELECT COUNT(*) FROM tblaccounts" . $query);
    $data = mysqli_fetch_array($result);
    $numrows = $data[0];
    $query = "SELECT tblaccounts.*,tblclients.firstname,tblclients.lastname,tblclients.companyname,tblclients.groupid,tblclients.currency AS currencyid FROM tblaccounts LEFT JOIN tblclients ON tblclients.id=tblaccounts.userid" . $query . " LIMIT " . (int) $page * $limit . "," . (int) $limit;
    $result = full_query_i($query);

    while ($data = mysqli_fetch_array($result)) {
        $id = $data['id'];
        $userid = $data['userid'];
        $currency = $data['currency'];
        $date = $data['date'];
        $date = fromMySQLDate($date);
        $description = $data['description'];
        $amountin = $data['amountin'];
        $fees = $data['fees'];
        $amountout = $data['amountout'];
        $gateway = $data['gateway'];
        $transid = $data['transid'];
        $invoiceid = $data['invoiceid'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $companyname = $data['companyname'];
        $groupid = $data['groupid'];
        $currencyid = $data['currencyid'];
        $clientlink = ($userid ? $aInt->outputClientLink($userid, $firstname, $lastname, $companyname, $groupid) : "-");
        $currency = ($userid ? getCurrency("", $currencyid) : getCurrency("", $currency));
        $amountin = formatCurrency($amountin);
        $fees = formatCurrency($fees);
        $amountout = formatCurrency($amountout);

        if ($invoiceid != "0") {
            $description .= " (<a href=\"invoices.php?action=edit&id=" . $invoiceid . "\">#" . $invoiceid . "</a>)";
        }


        if ($transid != "") {
            $description .= "<br>Trans ID: " . $transid;
        }

        $gateway = $gatewaysarray[$gateway];
        $tabledata[] = array($clientlink, $date, $gateway, $description, $amountin, $fees, $amountout, "<a href=\"" . $PHP_SELF . "?action=edit&id=" . $id . "\"class=\"btn btn-success\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>", "<a href=\"#\" onClick=\"doDelete('" . $id . "');return false\" class=\"btn btn-danger\"><i class=\"fa fa-minus-circle\" aria-hidden=\"true\"></i></a>");
    }

    $table = $aInt->sortableTable(array($aInt->lang("fields", "clientname"), $aInt->lang("fields", "date"), $aInt->lang("fields", "paymentmethod"), $aInt->lang("fields", "description"), $aInt->lang("transactions", "amountin"), $aInt->lang("transactions", "fees"), $aInt->lang("transactions", "amountout"), "", ""), $tabledata, $tableformurl, $tableformbuttons);

    $sumtable = "";
    if (checkPermission("View Income Totals", true)) {
        $sumtable.= "
<table class=\"table\">
<tr bgcolor=\"#f4f4f4\" style=\"text-align:center;font-weight:bold;\"><td></td><td>";
        $sumtable.= $aInt->lang("transactions", "totalincome");
        $sumtable.= "</td><td>";
        $sumtable.= $aInt->lang("transactions", "totalfees");
        $sumtable.= "</td><td>";
        $sumtable.= $aInt->lang("transactions", "totalexpenditure");
        $sumtable.= "</td><td>";
        $sumtable.= $aInt->lang("transactions", "totalbalance");
        $sumtable.= "</td></tr>
";
        foreach ($totals as $currency => $values) {
            $currency = getCurrency("", $currency);
            $sumtable.= "<tr bgcolor=\"#ffffff\" style=\"text-align:center;\"><td bgcolor=\"#f4f4f4\"><b>" . $currency['code'] . "</b></td><td>" . formatCurrency($values['in']) . "</td><td>" . formatCurrency($values['fees']) . "</td><td>" . formatCurrency($values['out']) . "</td><td bgcolor=\"#f4f4f4\"><b>" . formatCurrency($values['total']) . "</b></td></tr>";
        }


        if (!count($totals)) {
            $sumtable.= "<tr bgcolor=\"#ffffff\" style=\"text-align:center;\"><td colspan=\"5\">" . $aInt->lang("transactions", "nototals") . "</td></tr>";
        }

        $sumtable.= "</table>

";
    }
    $client = $aInt->clientsDropDown($userid, "", "client", true);
    $payment = paymentMethodsSelection($aInt->lang("global", "any"));
    $aInt->assign("showoption", $showoption);
    $aInt->assign("sumtable", $sumtable);
    $aInt->assign("payment", $payment);
    $aInt->assign("today", $date2);
    $aInt->assign("withinoption", $withinoption);
    $aInt->assign("startdate", $startdate);
    $aInt->assign("enddate", $enddate);
    $aInt->assign("amount", $amount);
    $aInt->assign("transid", $transid);
    $aInt->assign("filtertransid", $filtertransid);
    $aInt->assign("description", $description);
    $aInt->assign("filterdescription", $filterdescription);
    $aInt->assign("client", $client);
    $aInt->assign("table", $table);
    $aInt->assign("currencyoption", $currencyoption);
    $aInt->assign("PHP_SELF", $PHP_SELF);
    $template = "billing/view";
} else {
    if ($action == "edit") {
        $result = select_query_i("tblaccounts", "", array("id" => $id));
        $data = mysqli_fetch_array($result);
        $id = $data['id'];
        $userid = $data['userid'];
        $date = $data['date'];
        $date = fromMySQLDate($date);
        $description = $data['description'];
        $amountin = $data['amountin'];
        $fees = $data['fees'];
        $amountout = $data['amountout'];
        $paymentmethod = $data['gateway'];
        $transid = $data['transid'];
        $invoiceid = $data['invoiceid'];

        if (!$id) {
            $aInt->gracefulExit($aInt->lang("transactions", "notfound"));
        }

        $client = $aInt->clientsDropDown($userid, "", "client", true);
        $payment = paymentMethodsSelection($aInt->lang("global", "none"));
        $aInt->assign("client", $client);
        $aInt->assign("payment", $payment);
        $aInt->assign("invoiceid", $invoiceid);
        $aInt->assign("date", $date);
        $aInt->assign("transid", $transid);
        $aInt->assign("fees", $fees);
        $aInt->assign("amountin", $amountin);
        $aInt->assign("amountout", $amountout);
        $aInt->assign("description", $description);
        $aInt->assign("PHP_SELF", $PHP_SELF);
        $template = "billing/edit";
    }
}


$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->template = $template;
$aInt->jquerycode .=$menuselect;
$aInt->display();
?>