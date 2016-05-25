<?php

/**
 * @ RA
 * */
define("ADMINAREA", true);
require "../init.php";
$aInt = new RA_Admin("View Products/Services");
$aInt->title = "Product/Service Custom Fields";
$aInt->sidebar = "config";
$aInt->icon = "configoptions";
$aInt->helplink = "Configurable Options";
$action = $ra->get_req_var("action");



if ($manageoptions) {
    $result = select_query("tblcurrencies", "", "", "code", "ASC");
    while ($data = mysql_fetch_array($result)) {
        $curr_id = $data['id'];
        $curr_code = $data['code'];
        $currenciesarray[$curr_id] = $curr_code;
    }
    $totalcurrencies = count($currenciesarray) * 2;

    if ($deleteconfigoption) {
        check_token("RA.admin.default");
        checkPermission("Delete Products/Services");
        delete_query("tblservicetogroup", array("gid" => $confid));
        delete_query("tblcustomfieldsgroup", array("id" => $confid));
        delete_query("tblcustomfields", array("gid" => $confid));
        redir("manageoptions=true&cid=" . $cid);
        exit();
    }
    $aInt->title = "Configurable Options";
    $result = select_query("tblcustomfieldsgroup", "", array("id" => $cid));
    $data = mysql_fetch_array($result);
    $cid = $data['id'];
    $name = $data['name'];
    ob_start();
    echo "";
    echo "<s";
    echo "cript langauge=\"JavaScript\">
function deletegroupoption(id) {
	if (confirm(\"Are you sure you want to delete this product configuration option?\")) {
		window.location='";
    echo $PHP_SELF;
    echo "?manageoptions=true&cid=";
    echo $cid;
    echo "&deleteconfigoption=true&confid='+id+'";
    echo generate_token("link");
    echo "';
	}
}
function closewindow() {
	window.opener.document.managefrm.submit();
	window.close();
}
</script>

<form method=\"post\" action=\"";
    echo $_SERVER['PHP_SELF'];
    echo "?manageoptions=true&cid=";
    echo $cid;

    if ($gid) {
        echo "&gid=" . $gid;
    }

    echo "&save=true\">

<p>Option Name: <input type=\"text\" name=\"configoptionname\" size=\"50\" value=\"";
    echo $optionname;
    echo "\" /> Option Type: ";
    echo "<s";
    echo "elect name=\"configoptiontype\"><option value=\"1\"";

    if ($optiontype == "1") {
        echo " selected";
    }

    echo ">Dropdown</option><option value=\"2\"";

    if ($optiontype == "2") {
        echo " selected";
    }

    echo ">Radio</option><option value=\"3\"";

    if ($optiontype == "3") {
        echo " selected";
    }

    echo ">Yes/No</option><option value=\"4\"";

    if ($optiontype == "4") {
        echo " selected";
    }

    echo ">Quantity</option></select>";

    if ($optiontype == "4") {
        echo "<br>Minimum Quantity Required: <input type=\"text\" name=\"qtyminimum\" size=\"6\" value=\"" . $qtyminimum . "\" /> Maximum Allowed: <input type=\"text\" name=\"qtymaximum\" size=\"6\" value=\"" . $qtymaximum . "\" /> (Leave blank for no limit)";
    }

    echo "</p>

<table width=100% align=center cellpadding=2 cellspacing=1 bgcolor=#cccccc>
<tr bgcolor=\"#efefef\" style=\"text-align:center;font-weight:bold;\"><td>Options</td><td width=70> </td><td width=70> </td><td width=70>";
    echo $aInt->lang("billingcycles", "onetime");
    echo "/<br />";
    echo $aInt->lang("billingcycles", "monthly");
    echo "</td><td width=70>Quarterly</td><td width=70>Semi-Annual</td><td width=70>Annual</td><td width=70>Biennial</td><td width=70>Triennial</td><td width=50>Order</td><td width=30>Hide</td></tr>
";
    $x = 0;
    $query = "SELECT * FROM tblserviceconfigoptionssub WHERE configid=" . (int) $cid . " ORDER BY sortorder ASC,id ASC";
    $result = full_query($query);

    while ($data = mysql_fetch_array($result)) {
        ++$x;
        $optionid = $data['id'];
        $optionname = $data['optionname'];
        $sortorder = $data['sortorder'];
        $hidden = $data['hidden'];
        echo ("<tr bgcolor=\"#ffffff\" style=\"text-align:center;\"><td rowspan=\"" . $totalcurrencies . "\"><input type=\"text\" name=\"optionname[" . $optionid . "]") . "\" value=\"" . $optionname . "\" size=\"40\">";

        if (1 < $x) {
            echo "<br><a href=\"#\" onclick=\"deletegroupoption('" . $optionid . "');return false;\"><img src=\"images/icons/delete.png\" border=\"0\">";
        }

        echo "</td>";
        $firstcurrencydone = false;
        foreach ($currenciesarray as $curr_id => $curr_code) {
            $result2 = select_query("tblpricing", "", array("type" => "configoptions", "currency" => $curr_id, "relid" => $optionid));
            $data = mysql_fetch_array($result2);
            $pricing_id = $data['id'];

            if (!$pricing_id) {
                insert_query("tblpricing", array("type" => "configoptions", "currency" => $curr_id, "relid" => $optionid));
                $result2 = select_query("tblpricing", "", array("type" => "configoptions", "currency" => $curr_id, "relid" => $optionid));
                $data = mysql_fetch_array($result2);
            }

            $val[1] = $data['msetupfee'];
            $val[2] = $data['qsetupfee'];
            $val[3] = $data['ssetupfee'];
            $val[4] = $data['asetupfee'];
            $val[5] = $data['bsetupfee'];
            $val[11] = $data['tsetupfee'];
            $val[6] = $data['monthly'];
            $val[7] = $data['quarterly'];
            $val[8] = $data['semiannually'];
            $val[9] = $data['annually'];
            $val[10] = $data['biennially'];
            $val[12] = $data['triennially'];

            if ($firstcurrencydone) {
                echo "</tr><tr bgcolor=\"#ffffff\" style=\"text-align:center;\">";
            }

            echo (((((((((((((((((("<td rowspan=\"2\" bgcolor=\"#efefef\"><b>" . $curr_code . "</b></td><td>Setup</td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[1]\" size=\"10\" value=\"" . $val['1'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[2]\" size=\"10\" value=\"" . $val['2'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[3]\" size=\"10\" value=\"" . $val['3'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[4]\" size=\"10\" value=\"" . $val['4'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[5]\" size=\"10\" value=\"" . $val['5'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[11]\" size=\"10\" value=\"" . $val['11'] . "\"></td>";

            if (!$firstcurrencydone) {
                echo (("<td rowspan=\"" . $totalcurrencies . "\"><input type=\"text\" name=\"sortorder[" . $optionid . "]") . "\" value=\"" . $sortorder . "\" style=\"width:100%;\"></td><td rowspan=\"" . $totalcurrencies . "\"><input type=\"checkbox\" name=\"hidden[" . $optionid . "]") . "\" value=\"1\"";

                if ($hidden) {
                    echo " checked";
                }

                echo " /></td>";
            }

            echo (((((((((((((((((("</tr><tr bgcolor=\"#ffffff\" style=\"text-align:center;\"><td>Pricing</td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[6]\" size=\"10\" value=\"" . $val['6'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[7]\" size=\"10\" value=\"" . $val['7'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[8]\" size=\"10\" value=\"" . $val['8'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[9]\" size=\"10\" value=\"" . $val['9'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[10]\" size=\"10\" value=\"" . $val['10'] . "\"></td><td><input type=\"text\" name=\"price[" . $curr_id . "]") . "[") . $optionid . "]") . "[12]\" size=\"10\" value=\"" . $val['12'] . "\"></td>";
            $firstcurrencydone = true;
        }

        echo "</tr>";
    }


    if (($optiontype == "1" || $optiontype == "2") || $x == "0") {
        echo "<tr bgcolor=\"#efefef\"><td colspan=\"9\"><B>Add Option:</B> <input type=\"text\" name=\"addoptionname\" size=\"60\"></td><td><input type=\"text\" name=\"addsortorder\" value=\"0\" style=\"width:100%;\"></td><td><input type=\"checkbox\" name=\"addhidden\" value=\"1\" /></td></tr>
";
    }

    echo "</table>

<p align=\"center\"><input type=\"submit\" value=\"Save Changes\" class=\"button\" /> <input type=\"button\" value=\"Close Window\" onclick=\"closewindow();\" class=\"button\" /></p>

</form>

";
    $content = ob_get_contents();
    ob_end_clean();
    $aInt->content = $content;
    $aInt->displayPopUp();
    exit();
}


if ($action == "savegroup") {
    check_token("RA.admin.default");
    checkPermission("Edit Products/Services");

    if ($id) {
        update_query("tblcustomfieldsgroup", array("name" => $name), array("id" => $id));
        $response = "saved";
    } else {
        $id = insert_query("tblcustomfieldsgroup", array("name" => $name));
        $response = "added";
    }

    update_query("tblservices", array("cpid" => 0), array("gid" => $id));

    if ($productlinks) {
        foreach ($productlinks as $pid) {
            update_query("tblservices", array("cpid" => $id), array("id" => $pid));
        }
    }

    //mail('peter@hd.net.nz','hello',print_r($fieldname));
         echo print_r($value);

   
    if ($fieldname) {
        foreach ($fieldname as $fid => $value) {
         
            update_query("tblcustomfields", array("fieldname" => $value, "fieldtype" => $fieldtype[$fid], "description" => $description[$fid], "fieldoptions" => $fieldoptions[$fid], "regexpr" => html_entity_decode($regexpr[$fid]), "adminonly" => $adminonly[$fid], "required" => $required[$fid], "showorder" => $showorder[$fid], "showinvoice" => $showinvoice[$fid], "sortorder" => $sortorder[$fid]), array("id" => $fid));
        }
    }

    if ($addfieldname) {
        insert_query("tblcustomfields", array("gid" => $id, "type" => "product", "fieldname" => $addfieldname, "fieldtype" => $addfieldtype, "description" => $adddescription, "fieldoptions" => $addfieldoptions, "regexpr" => html_entity_decode($addregexpr), "adminonly" => $addadminonly, "required" => $addrequired, "showorder" => $addshoworder, "showinvoice" => $addshowinvoice, "sortorder" => $addsortorder));
    }




    redir("action=managegroup&id=" . $id);
    exit();
}



if ($action == "duplicate") {
    check_token("RA.admin.default");
    checkPermission("Create New Products/Services");
    $result = select_query("tblcustomfieldsgroup", "", array("id" => $existinggroupid));
    $data = mysql_fetch_array($result);
    $addstr = "";
    foreach ($data as $key => $value) {

        if (is_numeric($key)) {
            if ($key == "0") {
                $value = "";
            }


            if ($key == "1") {
                $value = $newgroupname;
            }

            $addstr .= "'" . db_escape_string($value) . "',";
            continue;
        }
    }

    $addstr = substr($addstr, 0, 0 - 1);
    full_query("INSERT INTO tblcustomfieldsgroup VALUES (" . $addstr . ")");
    $newgroupid = mysql_insert_id();
    $result = select_query("tblcustomfields", "", array("gid" => $existinggroupid));

    while ($data = mysql_fetch_array($result)) {
        $configid = $data['id'];
        $addstr = "";
        foreach ($data as $key => $value) {

            if (is_numeric($key)) {
                if ($key == "0") {
                    $value = "";
                }


                if ($key == "1") {
                    $value = $newgroupid;
                }

                $addstr .= "'" . db_escape_string($value) . "',";
                continue;
            }
        }

        $addstr = substr($addstr, 0, 0 - 1);
        full_query("INSERT INTO tblcustomfieldsgroup VALUES (" . $addstr . ")");
        $newconfigid = mysql_insert_id();
        $result2 = select_query("tblcustomfields", "", array("gid" => $configid));

        while ($data = mysql_fetch_array($result2)) {
            $optionid = $data['id'];
            $addstr = "";
            foreach ($data as $key => $value) {

                if (is_numeric($key)) {
                    if ($key == "0") {
                        $value = "";
                    }


                    if ($key == "1") {
                        $value = $newconfigid;
                    }

                    $addstr .= "'" . db_escape_string($value) . "',";
                    continue;
                }
            }

            $addstr = substr($addstr, 0, 0 - 1);
            full_query("INSERT INTO tblcustomfields VALUES (" . $addstr . ")");
        }
    }

    redir("duplicated=true");
    exit();
}
if ($action == 'save') {

    check_token("RA.admin.default");
    checkPermission("Edit Products/Services");

    if ($optionname == "") {
        $optionname = array();
    }
    update_query("tblserviceconfigoptions", array("optionname" => $configoptionname, "optiontype" => $configoptiontype, "qtyminimum" => $qtyminimum, "qtymaximum" => $qtymaximum), array("id" => $cid));
    foreach ($optionname as $key => $value) {
        update_query("tblserviceconfigoptionssub", array("optionname" => $value, "sortorder" => $sortorder[$key], "hidden" => $hidden[$key]), array("id" => $key));
    }


    if ($customfieldname) {
        foreach ($customfieldname as $fid => $value) {
            update_query("tblcustomfields", array("fieldname" => $value, "fieldtype" => $fieldtype[$fid], "description" => $description[$fid], "fieldoptions" => $fieldoptions[$fid], "regexpr" => html_entity_decode($regexpr[$fid]), "adminonly" => $adminonly[$fid], "required" => $required[$fid], "showorder" => $showorder[$fid], "showinvoice" => $showinvoice[$fid], "sortorder" => $sortorder[$fid]), array("id" => $fid));
        }
    }

    if ($addfieldname) {
        insert_query("tblcustomfields", array("gid" => $id, "type" => "product", "fieldname" => $addfieldname, "fieldtype" => $addfieldtype, "description" => $adddescription, "fieldoptions" => $addfieldoptions, "regexpr" => html_entity_decode($addregexpr), "adminonly" => $addadminonly, "required" => $addrequired, "showorder" => $addshoworder, "showinvoice" => $addshowinvoice, "sortorder" => $addsortorder));
    }


    redir("manageoptions=true&cid=" . $cid);
    exit();
}

if ($action == "deleteoption") {
    check_token("RA.admin.default");
    checkPermission("Edit Products/Services");
    delete_query("tblserviceconfigoptions", array("id" => $opid));
    delete_query("tblserviceconfigoptionssub", array("configid" => $opid));
    delete_query("tblhostingconfigoptions", array("configid" => $opid));
    redir("action=managegroup&id=" . $id);
    exit();
}


if ($action == "deletegroup") {

    check_token("RA.admin.default");
    checkPermission("Delete Products/Services");
    delete_query("tblservicetogroup", array("gid" => $id));
    delete_query("tblcustomfieldsgroup", array("id" => $id));
    delete_query("tblcustomfields", array("gid" => $id));


    redir("deleted=true");
    exit();
}

ob_start();
$jscode = "function doDelete(id) {
if (confirm(\"Are you sure you want to delete this configurable option group?\")) {
window.location='" . $_SERVER['PHP_SELF'] . "?action=deletegroup&id='+id+'" . generate_token("link") . "';
}}";

if ($action == "") {
    if ($deleted) {
        infoBox("Success", "The option group has been deleted successfully!");
    }


    if ($duplicated) {
        infoBox("Success", "The option group has been duplicated successfully!");
    }

    echo $infobox;
    echo "
<p>Product/Service Custom Fields options allow you to customize the product/service field to enter customer details</p>

<p><b>Options:</b> <a href=\"";
    echo $_SERVER['PHP_SELF'];
    echo "?action=managegroup\">Create a Customer Field Group</a> | <a href=\"";
    echo $_SERVER['PHP_SELF'];
    echo "?action=duplicategroup\">Duplicate a Group</a></p>

";
    $aInt->sortableTableInit("nopagination");
    $result = select_query("tblcustomfieldsgroup", "", "", "name", "ASC");

    while ($data = mysql_fetch_array($result)) {
        $id = $data['id'];
        $name = $data['name'];
        $tabledata[] = array($name, "<a href=\"" . $_SERVER['PHP_SELF'] . ("?action=managegroup&id=" . $id . "\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit\"></a>"), "<a href=\"#\" onClick=\"doDelete('" . $id . "');return false\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete\"></a>");
    }

    echo $aInt->sortableTable(array("Name", "", ""), $tabledata);
} else {
    if ($action == "managegroup") {
        if ($id) {
            $action = "save";
            $steptitle = "Manage Group";
            $result = select_query("tblcustomfieldsgroup", "", array("id" => $id));
            $data = mysql_fetch_array($result);
            $id = $data['id'];
            $name = $data['name'];
            $productlinks = array();
            $result = select_query("tblservices", "", array("cpid" => $id));

            while ($data = mysql_fetch_array($result)) {
                $productlinks[] = $data['id'];
            }
        } else {
            $action = "savegroup";
            checkPermission("Create New Products/Services");
            $steptitle = "Create a New Group";
            $id = "";
            $productlinks = array();
        }

        $jscode = "function manageconfigoptions(id) {
    window.open('" . $_SERVER['PHP_SELF'] . "?manageoptions=true&cid='+id,'configoptions','width=900,height=500,scrollbars=yes');
}
function addconfigoption() {
    window.open('" . $_SERVER['PHP_SELF'] . "?manageoptions=true&gid=" . $id . "','configoptions','width=800,height=500,scrollbars=yes');
}
function doDelete(id,opid) {
    if (confirm(\"Are you sure you want to delete this configurable option?\")) {
        window.location='" . $_SERVER['PHP_SELF'] . "?action=deleteoption&id='+id+'&opid='+opid+'" . generate_token("link") . "';
    }
}";
        echo "
<form method=\"post\" action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "?action=savegroup&id=";
        echo $id;
        echo "\" name=\"managefrm\">

<p><b>";
        echo $steptitle;
        echo "</b></p>
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=\"15%\" class=\"fieldlabel\">Name</td><td class=\"fieldarea\"><input type=\"text\" name=\"name\" size=\"40\" value=\"";
        echo $name;
        echo "\"></td></tr>

<tr><td class=\"fieldlabel\">Assigned Products</td><td class=\"fieldarea\">";
        echo "<s";
        echo "elect name=\"productlinks[]\" size=\"8\" style=\"width:90%\" multiple>
";
        $result = select_query("tblservices", "tblservices.id,tblservices.name,tblservicegroups.name AS groupname", 'cpid=0', "groupname` ASC,`name", "ASC", "", "tblservicegroups ON tblservices.gid=tblservicegroups.id");

        while ($data = mysql_fetch_array($result)) {
            $pid = $data['id'];
            $groupname = $data['groupname'];
            $name = $data['name'];
            echo "<option value=\"" . $pid . "\"";

            if (in_array($pid, $productlinks)) {
                echo " selected";
            }

            echo ">" . $groupname . " - " . $name . "</option>";
        }

        echo "</select></td></tr>
</table>
";
        if ($id) {
            $result = select_query("tblcustomfields", "", array("type" => "product", "gid" => $id), "sortorder` ASC,`id", "ASC");
            while ($data = mysql_fetch_array($result)) {
                $fid = $data['id'];
                $fieldname = $data['fieldname'];
                $fieldtype = $data['fieldtype'];
                $description = $data['description'];
                $fieldoptions = $data['fieldoptions'];
                $regexpr = $data['regexpr'];
                $adminonly = $data['adminonly'];
                $required = $data['required'];
                $showorder = $data['showorder'];
                $showinvoice = $data['showinvoice'];
                $sortorder = $data['sortorder'];
                echo "<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=100 class=\"fieldlabel\">";
                echo $aInt->lang("customfields", "fieldname");
                echo "</td><td class=\"fieldarea\"><table width=\"98%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><input type=\"text\" name=\"customfieldname[";
                echo $fid;
                echo "]\" value=\"";
                echo $fieldname;
                echo "\" size=\"30\"></td><td align=\"right\">";
                echo $aInt->lang("customfields", "order");
                echo " <input type=\"text\" name=\"customsortorder[";
                echo $fid;
                echo "]\" value=\"";
                echo $sortorder;
                echo "\" size=\"5\"></td></tr></table></td></tr>
<tr><td class=\"fieldlabel\">";
                echo $aInt->lang("customfields", "fieldtype");
                echo "</td><td class=\"fieldarea\">";
                echo "<s";
                echo "elect name=\"customfieldtype[";
                echo $fid;
                echo "]\">
<option value=\"text\"";

                if ($fieldtype == "text") {
                    echo " selected";
                }

                echo ">";
                echo $aInt->lang("customfields", "typetextbox");
                echo "</option>
<option value=\"link\"";

                if ($fieldtype == "link") {
                    echo " selected";
                }

                echo ">";
                echo $aInt->lang("customfields", "typelink");
                echo "</option>
<option value=\"password\"";

                if ($fieldtype == "password") {
                    echo " selected";
                }

                echo ">";
                echo $aInt->lang("customfields", "typepassword");
                echo "</option>
<option value=\"dropdown\"";

                if ($fieldtype == "dropdown") {
                    echo " selected";
                }

                echo ">";
                echo $aInt->lang("customfields", "typedropdown");
                echo "</option>
<option value=\"tickbox\"";

                if ($fieldtype == "tickbox") {
                    echo " selected";
                }

                echo ">";
                echo $aInt->lang("customfields", "typetickbox");
                echo "</option>
<option value=\"textarea\"";

                if ($fieldtype == "textarea") {
                    echo " selected";
                }

                echo ">";
                echo $aInt->lang("customfields", "typetextarea");
                echo "</option>
</select></td></tr>
<tr><td class=\"fieldlabel\">";
                echo $aInt->lang("fields", "description");
                echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"customfielddesc[";
                echo $fid;
                echo "]\" value=\"";
                echo $description;
                echo "\" size=\"60\"> ";
                echo $aInt->lang("customfields", "descriptioninfo");
                echo "</td></tr>
<tr><td class=\"fieldlabel\">";
                echo $aInt->lang("customfields", "validation");
                echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"customfieldregexpr[";
                echo $fid;
                echo "]\" value=\"";
                echo $regexpr;
                echo "\" size=\"60\"> ";
                echo $aInt->lang("customfields", "validationinfo");
                echo "</td></tr>
<tr><td class=\"fieldlabel\">";
                echo $aInt->lang("customfields", "selectoptions");
                echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"customfieldoptions[";
                echo $fid;
                echo "]\" value=\"";
                echo $fieldoptions;
                echo "\" size=\"60\"> ";
                echo $aInt->lang("customfields", "selectoptionsinfo");
                echo "</td></tr>
<tr><td class=\"fieldlabel\"></td><td class=\"fieldarea\"><table width=\"98%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><input type=\"checkbox\" name=\"customadminonly[";
                echo $fid;
                echo "]\"";

                if ($adminonly == "on") {
                    echo " checked";
                }

                echo "> ";
                echo $aInt->lang("customfields", "adminonly");
                echo " <input type=\"checkbox\" name=\"customrequired[";
                echo $fid;
                echo "]\"";

                if ($required == "on") {
                    echo " checked";
                }

                echo "> ";
                echo $aInt->lang("customfields", "requiredfield");
                echo " <input type=\"checkbox\" name=\"customshoworder[";
                echo $fid;
                echo "]\"";

                if ($showorder == "on") {
                    echo " checked";
                }

                echo "> ";
                echo $aInt->lang("customfields", "orderform");
                echo " <input type=\"checkbox\" name=\"customshowinvoice[";
                echo $fid;
                echo "]\"";

                if ($showinvoice) {
                    echo " checked";
                }

                echo "> ";
                echo $aInt->lang("customfields", "showinvoice");
                echo "</td><td align=\"right\"><a href=\"#\" onClick=\"deletecustomfield('";
                echo $fid;
                echo "');return false\">";
                echo $aInt->lang("customfields", "deletefield");
                echo "</a></td></tr></table></td></tr>
</table><br>
";
            }

            echo "<b>";
            echo $aInt->lang("customfields", "addfield");
            echo "</b><br><br>
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=100 class=\"fieldlabel\">";
            echo $aInt->lang("customfields", "fieldname");
            echo "</td><td class=\"fieldarea\"><table width=\"98%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><input type=\"text\" name=\"addfieldname\" size=\"30\"></td><td align=\"right\">";
            echo $aInt->lang("customfields", "order");
            echo " <input type=\"text\" name=\"addsortorder\" size=\"5\" value=\"0\"></td></tr></table></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("customfields", "fieldtype");
            echo "</td><td class=\"fieldarea\">";
            echo "<s";
            echo "elect name=\"addfieldtype\">
<option value=\"text\">";
            echo $aInt->lang("customfields", "typetextbox");
            echo "</option>
<option value=\"link\">";
            echo $aInt->lang("customfields", "typelink");
            echo "</option>
<option value=\"password\">";
            echo $aInt->lang("customfields", "typepassword");
            echo "</option>
<option value=\"dropdown\">";
            echo $aInt->lang("customfields", "typedropdown");
            echo "</option>
<option value=\"tickbox\">";
            echo $aInt->lang("customfields", "typetickbox");
            echo "</option>
<option value=\"textarea\">";
            echo $aInt->lang("customfields", "typetextarea");
            echo "</option>
</select></td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("fields", "description");
            echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"addcustomfielddesc\" size=\"60\"> ";
            echo $aInt->lang("customfields", "descriptioninfo");
            echo "</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("customfields", "validation");
            echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"addregexpr\" size=\"60\"> ";
            echo $aInt->lang("customfields", "validationinfo");
            echo "</td></tr>
<tr><td class=\"fieldlabel\">";
            echo $aInt->lang("customfields", "selectoptions");
            echo "</td><td class=\"fieldarea\"><input type=\"text\" name=\"addfieldoptions\" size=\"60\"> ";
            echo $aInt->lang("customfields", "selectoptionsinfo");
            echo "</td></tr>
<tr><td class=\"fieldlabel\"></td><td class=\"fieldarea\"><input type=\"checkbox\" name=\"addadminonly\"> ";
            echo $aInt->lang("customfields", "adminonly");
            echo " <input type=\"checkbox\" name=\"addrequired\"> ";
            echo $aInt->lang("customfields", "requiredfield");
            echo " <input type=\"checkbox\" name=\"addshoworder\"> ";
            echo $aInt->lang("customfields", "orderform");
            echo " <input type=\"checkbox\" name=\"addshowinvoice\"> ";
            echo $aInt->lang("customfields", "showinvoice");
            echo "</td></tr>
</table>
";
        }

        echo "
<P style='clear:both' ALIGN=\"center\"><input type=\"submit\" value=\"Save Changes\" class=\"button\" /> <input type=\"button\" value=\"Back to Groups List\" onClick=\"window.location='configproductoptions.php'\" class=\"button\" /></P>

</form>

";
    } else {
        if ($action == "duplicategroup") {
            checkPermission("Create New Products/Services");
            echo "
<p><b>Duplicate Group</b></p>

<form method=\"post\" action=\"";
            echo $PHP_SELF;
            echo "?action=duplicate\">
<table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">
<tr><td width=150 class=\"fieldlabel\">Existing Group</td><td class=\"fieldarea\">";
            echo "<s";
            echo "elect name=\"existinggroupid\">";
            $result = select_query("tblcustomfieldsgroup", "", "", "id", "ASC");

            while ($data = mysql_fetch_array($result)) {
                $id = $data['id'];
                $name = $data['name'];



                echo "<option value=\"" . $id . "\">" . $name . "</option>";
            }

            echo "</select></td></tr>
<tr><td class=\"fieldlabel\">New Group Name</td><td class=\"fieldarea\"><input type=\"text\" name=\"newgroupname\" size=\"50\"></td></tr>
</table>
<P ALIGN=\"center\"><input type=\"submit\" value=\"Continue >>\" class=\"button\"></P>
</form>

";
        }
    }
}

$content = ob_get_contents();
ob_end_clean();
$aInt->content = $content;
$aInt->jquerycode = $jquerycode;
$aInt->jscode = $jscode;
$aInt->display();
?>