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
include '../gatewayfunctions.php';
include "../orderfunctions.php";
include "../gatewayfunctions.php";
include "../cartfunctions.php";
include "../clientfunctions.php";

use RA_Gateways;
use RA_Validate;

class RA_Carts {

    private $orderfrm;
    public $data;

    public function __construct($orderfrm) {
        $this->data = "";
        $this->orderfrm = $orderfrm;
    }

    public function AddServices($pid) {

        if ($pid) {

            if (substr($pid, 0, 1) == "b") {
                redir("a=adds&bid=" . substr($pid, 1));
            }

            $templatefile = "configureserviceform";
            $productinfo = $this->orderfrm->setPid($pid);
            if (!$productinfo) {
                redir();
                $_SESSION['cart']['domainoptionspid'] = $productinfo['pid'];
                $smartyvalues['productinfo'] = $productinfo;
                $pid = $smartyvalues['pid'] = $productinfo['pid'];
                $type = $productinfo['type'];
                $des = $productinfo['description'];
            }

            if ($configoption) {
                $passedvariables['configoption'] = $configoption;
            }


            if ($customfield) {
                $passedvariables['customfield'] = $customfield;
            }

            if ($addons) {
                if (!is_array($addons)) {
                    $passedvariables['addons'] = explode(",", $addons);
                } else {
                    foreach ($addons as $k => $v) {
                        $passedvariables['addons'][] = trim($k);
                    }
                }
            }
            if (count($passedvariables)) {
                $_SESSION['cart']['passedvariables'] = $passedvariables;
            }
            $passedvariables = $_SESSION['cart']['passedvariables'];
            $prodarray = array("pid" => $pid, "billingcycle" => $passedvariables['billingcycle'], "configoptions" => $passedvariables['configoption'], "customfields" => $passedvariables['customfield'], "addons" => $passedvariables['addons'], "server" => "", "noconfig" => true);
            if (isset($passedvariables['bnum'])) {
                $prodarray['bnum'] = $passedvariables['bnum'];
            }


            if (isset($passedvariables['bitem'])) {
                $prodarray['bitem'] = $passedvariables['bitem'];
            }
            $_SESSION['cart']['products'][] = $prodarray;
            $newprodnum = count($_SESSION['cart']['products']) - 1;
//
//            if ($this->orderfrm['directpidstep1'] && !$ajax) {
//                redir("pid=" . $pid);
//                exit();
//            }

            $_SESSION['cart']['newproduct'] = true;

            if ($ajax) {
                $ajax = "&ajax=1";
            } else {
                if ($passedvariables['skipconfig']) {
                    unset($_SESSION['cart']['products'][$newprodnum]['noconfig']);
                    $_SESSION['cart']['lastconfigured'] = array("type" => "product", "i" => $newprodnum);
                    redir("a=view");
                    exit();
                }
            }

            redir("a=confservice&i=" . $newprodnum . $ajax);


            $this->data['template'] = $templatefile;
            $this->data['smarty'] = $smartyvalues;
            return $this->data;
        }
    }

    public function ConfService() {

        $templatefile = "configureservice";
        $i = (int) $_REQUEST['i'];


        if (!is_array($_SESSION['cart']['products'][$i])) {
            if ($ajax) {
                exit($_LANG['invoiceserror']);
            }

            redir();
            exit();
        }

        $newproduct = $_SESSION['cart']['newproduct'];
        unset($_SESSION['cart']['newproduct']);
        $pid = $_SESSION['cart']['products'][$i]['pid'];
        $productinfo = $orderfrm->setPid($pid);

        if (!$productinfo) {
            redir();
        }

        $_SESSION['cart']['cartsummarypid'] = $productinfo['pid'];
        $pid = $productinfo['pid'];

        if ($configure) {
            global $errormessage;

            $errormessage = "";
            $result = select_query("tblservices", "type", array("id" => $pid));
            $data = mysql_fetch_array($result);
            $producttype = $data['type'];

            if ($configoption) {
                foreach ($configoption as $opid => $opid2) {
                    $result = select_query("tblproductconfigoptions", "", array("id" => $opid));
                    $data = mysql_fetch_array($result);
                    $optionname = $data['optionname'];
                    $optiontype = $data['optiontype'];
                    $qtyminimum = $data['qtyminimum'];
                    $qtymaximum = $data['qtymaximum'];

                    if ($optiontype == 4) {
                        $opid2 = (int) $opid2;

                        if ($opid2 < 0) {
                            $opid2 = 0;
                        }


                        if (($qtyminimum || $qtymaximum) && ($opid2 < $qtyminimum || $qtymaximum < $opid2)) {
                            if (strpos($optionname, "|")) {
                                $optionname = explode("|", $optionname);
                                $optionname = trim($optionname[1]);
                            }

                            $errormessage .= "<li>" . sprintf($_LANG['configoptionqtyminmax'], $optionname, $qtyminimum, $qtymaximum);
                            $opid2 = 0;
                        }
                    }

                    $configoptionsarray[sanitize($opid)] = sanitize($opid2);
                }
            }

            $addonsarray = (is_array($addons) ? array_keys($addons) : "");
            $errormessage .= bundlesValidateProductConfig($i, $billingcycle, $configoptionsarray, $addonsarray);
            $_SESSION['cart']['products'][$i]['billingcycle'] = $billingcycle;
            $_SESSION['cart']['products'][$i]['server'] = $serverarray;
            $_SESSION['cart']['products'][$i]['configoptions'] = $configoptionsarray;
            $_SESSION['cart']['products'][$i]['customfields'] = $customfield;
            $_SESSION['cart']['products'][$i]['addons'] = $addonsarray;

            if ($calctotal) {
                $i = $ra->get_req_var("i");
                $productinfo = $orderfrm->setPid($_SESSION['cart']['products'][$i]['pid']);
                $ordersummarytemp = "/templates/orderforms/" . $orderfrm->getTemplate() . "/ordersummary.tpl";


                if (file_exists(ROOTDIR . $ordersummarytemp)) {
                    $carttotals = calcCartTotals(false, true);

//               echo "<pre>",print_r($carttotals,1),"</pre>";
                    $templatevars = array("producttotals" => $carttotals['products'][$i], "carttotals" => $carttotals);
                    echo processSingleTemplate($ordersummarytemp, $templatevars);
                }

                exit();
            }


            if ((!$ajax && !$nocyclerefresh) && $previousbillingcycle != $billingcycle) {
                redir("a=confproduct&i=" . $i);
                exit();
            }

            $validate = new RA_Validate();
            $validate->validateCustomFields("product", $pid, true);
            run_validate_hook($validate, "ShoppingCartValidateProductUpdate", $_REQUEST);

            if ($validate->hasErrors()) {
                $errormessage .= $validate->getHTMLErrorOutput();
            }


            if ($errormessage) {
                if ($ajax) {
                    exit($errormessage);
                }

                $smartyvalues['errormessage'] = $errormessage;
            } else {
                unset($_SESSION['cart']['products'][$i]['noconfig']);
                $_SESSION['cart']['lastconfigured'] = array("type" => "product", "i" => $i);

                if ($ajax) {
                    exit();
                }

                redir("a=confdomains");
                exit();
            }
        }

        $billingcycle = $_SESSION['cart']['products'][$i]['billingcycle'];
        $server = $_SESSION['cart']['products'][$i]['server'];
        $customfields = $_SESSION['cart']['products'][$i]['customfields'];
        $configoptions = $_SESSION['cart']['products'][$i]['configoptions'];
        $addons = $_SESSION['cart']['products'][$i]['addons'];
        $domain = $_SESSION['cart']['products'][$i]['domain'];
        $noconfig = $_SESSION['cart']['products'][$i]['noconfig'];
        $billingcycle = $orderfrm->validateBillingCycle($billingcycle);
        $pricing = getPricingInfo($pid);
        $configurableoptions = getCartConfigOptions($pid, $configoptions, $billingcycle, "", true);
        $customfields = getCustomFields("product", $pid, "", "", "on", $customfields);

        $addonsarray = getAddons($pid, $addons);
        $recurringcycles = 0;

        if ($pricing['type'] == "recurring") {
            if (0 <= $pricing['rawpricing']['monthly']) {
                ++$recurringcycles;
            }


            if (0 <= $pricing['rawpricing']['quarterly']) {
                ++$recurringcycles;
            }


            if (0 <= $pricing['rawpricing']['semiannually']) {
                ++$recurringcycles;
            }


            if (0 <= $pricing['rawpricing']['annually']) {
                ++$recurringcycles;
            }


            if (0 <= $pricing['rawpricing']['biennially']) {
                ++$recurringcycles;
            }
        }


        if ((((($newproduct && $productinfo['type'] != "server") && ($pricing['type'] != "recurring" || $recurringcycles <= 1)) && !count($configurableoptions)) && !count($customfields)) && !count($addonsarray)) {
            unset($_SESSION['cart']['products'][$i]['noconfig']);
            $_SESSION['cart']['lastconfigured'] = array("type" => "product", "i" => $i);

            if ($ajax) {
                exit();
            }

            redir("a=confdomains");
            exit();
        }

        $serverarray = array("hostname" => $server['hostname'], "ns1prefix" => $server['ns1prefix'], "ns2prefix" => $server['ns2prefix'], "rootpw" => $server['rootpw']);
        $smartyvalues['editconfig'] = true;
        $smartyvalues['firstconfig'] = ($noconfig ? true : false);
        $smartyvalues['i'] = $i;
        $smartyvalues['productinfo'] = $productinfo;
        $smartyvalues['pricing'] = $pricing;
        $smartyvalues['billingcycle'] = $billingcycle;
        $smartyvalues['server'] = $serverarray;
        $smartyvalues['configurableoptions'] = $configurableoptions;
        $smartyvalues['addons'] = $addonsarray;
        $smartyvalues['customfields'] = $customfields;
        $smartyvalues['domain'] = $domain;
        $this->data['template'] = $templatefile;
        $this->data['smarty'] = $smartyvalues;
        return $this->data;
    }

    public function Viewcart() {

        $templatefile = "viewcart";
        $errormessage = "";
        $gateways = new RA_Gateways();
        $availablegateways = getAvailableOrderPaymentGateways();
        $securityquestions = getSecurityQuestions();

        if (($submit || $checkout) && !$validatepromo) {
            $_SESSION['cart']['paymentmethod'] = $paymentmethod;
            $_SESSION['cart']['notes'] = $notes;

            if (!$_SESSION['uid']) {
                if ($custtype == "existing") {
                    if (!validateClientLogin($loginemail, $loginpw)) {
                        $errormessage .= "<li>" . $_LANG['loginincorrect'];
                    }
                } else {
                    $_SESSION['cart']['user'] = array("firstname" => $firstname, "lastname" => $lastname, "companyname" => $companyname, "email" => $email, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber);
                    $errormessage = checkDetailsareValid("", true, true, false);
                }
            }


            if ($contact == "new") {
                redir("a=addcontact");
                exit();
            }


            if ($contact == "addingnew") {
                $errormessage .= checkContactDetails("", false, "domaincontact");
            }


            if ($availablegateways[$paymentmethod]['type'] == "CC" && $ccinfo) {
                if ($ccinfo == "new") {
                    $errormessage .= updateCCDetails("", $cctype, $ccnumber, $cccvv, $ccexpirymonth . $ccexpiryyear, $ccstartmonth . $ccstartyear, $ccissuenum);
                }


                if (!$cccvv) {
                    $errormessage .= "<li>" . $_LANG['creditcardccvinvalid'];
                }

                $_SESSION['cartccdetail'] = encrypt(base64_encode(serialize(array($cctype, $ccnumber, $ccexpirymonth, $ccexpiryyear, $ccstartmonth, $ccstartyear, $ccissuenum, $cccvv, $nostore))));
            }

            $validate = new RA_Validate();
            run_validate_hook($validate, "ShoppingCartValidateCheckout", $_REQUEST);

            if (isset($_SESSION['uid']) && $ra->get_config("EnableTOSAccept")) {
                $validate->validate("required", "accepttos", "ordererroraccepttos");
            }


            if ($validate->hasErrors()) {
                $errormessage .= $validate->getHTMLErrorOutput();
            }

            $currency = getCurrency($_SESSION['uid'], $_SESSION['currency']);

            if ($_POST['updateonly']) {
                $errormessage = "";
            }


            if ($ajax && $errormessage) {
                exit($errormessage);
            }


            if (!$errormessage && !$_POST['updateonly']) {
                if (!$_SESSION['uid']) {
                    $userid = addClient($firstname, $lastname, $companyname, $email, $address1, $address2, $city, $state, $postcode, $country, $phonenumber, $password, $securityqid, $securityqans);
                }


                if ($contact == "addingnew") {
                    $contact = addContact($_SESSION['uid'], $domaincontactfirstname, $domaincontactlastname, $domaincontactcompanyname, $domaincontactemail, $domaincontactaddress1, $domaincontactaddress2, $domaincontactcity, $domaincontactstate, $domaincontactpostcode, $domaincontactcountry, $domaincontactphonenumber);
                }

                $_SESSION['cart']['contact'] = $contact;
                $carttotals = calcCartTotals(true);

                if ($ccinfo == "new" && !$nostore) {
                    updateCCDetails($_SESSION['uid'], $cctype, $ccnumber, $cccvv, $ccexpirymonth . $ccexpiryyear, $ccstartmonth . $ccstartyear, $ccissuenum);
                }

                $orderid = $_SESSION['orderdetails']['OrderID'];
                $fraudmodule = getActiveFraudModule();

                if ($CONFIG['SkipFraudForExisting']) {
                    $result = select_query("tblorders", "COUNT(*)", array("status" => "Active", "userid" => $_SESSION['uid']));
                    $data = mysql_fetch_array($result);

                    if ($data[0]) {
                        $fraudmodule = "";
                    }
                }

                $result = full_query("SELECT COUNT(*) FROM tblinvoices INNER JOIN tblorders ON tblorders.invoiceid=tblinvoices.id WHERE tblorders.id='" . db_escape_string($orderid) . "' AND tblinvoices.status='Paid' AND subtotal>0");
                $data = mysql_fetch_array($result);

                if ($data[0]) {
                    $fraudmodule = "";
                }


                if (!$fraudmodule) {
                    if ($ajax) {
                        exit();
                    }

                    redir("a=complete");
                    exit();
                }

                logActivity("Order ID " . $orderid . " Fraud Check Initiated");
                update_query("tblorders", array("status" => "Fraud"), array("id" => $orderid));

                if ($_SESSION['orderdetails']['Products']) {
                    foreach ($_SESSION['orderdetails']['Products'] as $productid) {
                        update_query("tblhosting", array("domainstatus" => "Fraud"), array("id" => $productid, "domainstatus" => "Pending"));
                    }
                }


                if ($_SESSION['orderdetails']['Addons']) {
                    foreach ($_SESSION['orderdetails']['Addons'] as $addonid) {
                        update_query("tblhostingaddons", array("status" => "Fraud"), array("id" => $addonid, "status" => "Pending"));
                    }
                }


                if ($_SESSION['orderdetails']['Domains']) {
                    foreach ($_SESSION['orderdetails']['Domains'] as $domainid) {
                        update_query("tbldomains", array("status" => "Fraud"), array("id" => $domainid, "status" => "Pending"));
                    }
                }

                update_query("tblinvoices", array("status" => "Cancelled"), array("id" => $_SESSION['orderdetails']['InvoiceID'], "status" => "Unpaid"));
                $results = runFraudCheck($orderid, $fraudmodule);
                $_SESSION['orderdetails']['fraudcheckresults'] = $results;

                if ($ajax) {
                    exit();
                }

                redir("a=fraudcheck");
                exit();
            }


            if (!$paymentmethod) {
                $errormessage .= "<li>No payment gateways available so order cannot proceed";
            }
        }

        $smartyvalues['errormessage'] = $errormessage;

        if (isset($_POST['qty']) && is_array($_POST['qty'])) {
            foreach ($_POST['qty'] as $i => $qty) {

                if (is_array($_SESSION['cart']['products'][$i])) {
                    $_SESSION['cart']['products'][$i]['qty'] = (int) $qty;
                    continue;
                }
            }
        }


        if ($promocode) {
            $promoerrormessage = SetPromoCode($promocode);

            if ($promoerrormessage) {
                $smartyvalues['errormessage'] = "<li>" . $promoerrormessage;
            }


            if ($paymentmethod) {
                $_SESSION['cart']['paymentmethod'] = $paymentmethod;
            }


            if ($notes) {
                $_SESSION['cart']['notes'] = $notes;
            }


            if ($firstname) {
                $_SESSION['cart']['user'] = array("firstname" => $firstname, "lastname" => $lastname, "companyname" => $companyname, "email" => $email, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postcode" => $postcode, "country" => $country, "phonenumber" => $phonenumber);
            }
        }

        $smartyvalues['promotioncode'] = $_SESSION['cart']['promo'];
        $ignorenoconfig = ($cartsummary ? true : false);
        $carttotals = calcCartTotals("", $ignorenoconfig);
        $promotype = $carttotals['promotype'];
        $promovalue = $carttotals['promovalue'];
        $promorecurring = $carttotals['promorecurring'];
        $promodescription = ($promotype == "Percentage" ? $promovalue . "%" : $promovalue);

        if ($promotype == "Price Override") {
            $promodescription .= " " . $_LANG['orderpromopriceoverride'];
        } else {
            if ($promotype == "Free Setup") {
                $promodescription = $_LANG['orderpromofreesetup'];
            }
        }

        $promodescription .= " " . $promorecurring . " " . $_LANG['orderdiscount'];
        $smartyvalues['promotiondescription'] = $promodescription;
        foreach ($carttotals as $k => $v) {
            $smartyvalues[$k] = $v;
        }

        $smartyvalues['taxenabled'] = $CONFIG['TaxEnabled'];
        $paymentmethod = $_SESSION['cart']['paymentmethod'];

        if (!$paymentmethod) {
            foreach ($availablegateways as $k => $v) {
                $paymentmethod = $k;
                break;
            }
        }

        $smartyvalues['selectedgateway'] = $paymentmethod;
        $smartyvalues['selectedgatewaytype'] = $availablegateways[$paymentmethod]['type'];
        $smartyvalues['gateways'] = $availablegateways;
        $smartyvalues['ccinfo'] = $ccinfo;
        $smartyvalues['cctype'] = $cctype;
        $smartyvalues['ccnumber'] = $ccnumber;
        $smartyvalues['ccexpirymonth'] = $ccexpirymonth;
        $smartyvalues['ccexpiryyear'] = $ccexpiryyear;
        $smartyvalues['ccstartmonth'] = $ccstartmonth;
        $smartyvalues['ccstartyear'] = $ccstartyear;
        $smartyvalues['ccissuenum'] = $ccissuenum;
        $smartyvalues['cccvv'] = $cccvv;
        $smartyvalues['acceptedcctypes'] = explode(",", $CONFIG['AcceptedCardTypes']);
        $smartyvalues['showccissuestart'] = $CONFIG['ShowCCIssueStart'];
        $smartyvalues['shownostore'] = $CONFIG['CCAllowCustomerDelete'];
        $smartyvalues['months'] = $gateways->getCCDateMonths();
        $smartyvalues['startyears'] = $gateways->getCCStartDateYears();
        $smartyvalues['expiryyears'] = $smartyvalues['years'] = $gateways->getCCExpiryDateYears();
        $cartitems = count($carttotals['products']) + count($carttotals['addons']) + count($carttotals['domains']) + count($carttotals['renewals']);

        if (!$cartitems) {
            $allowcheckout = false;
        }

        $smartyvalues['cartitems'] = $cartitems;
        $smartyvalues['checkout'] = $allowcheckout;

        if ($_SESSION['uid']) {
            $clientsdetails = getClientsDetails();
            $clientsdetails['country'] = $clientsdetails['countryname'];
            $custtype = "existing";
            $smartyvalues['loggedin'] = true;
        } else {
            $clientsdetails = $_SESSION['cart']['user'];
            $customfields = getCustomFields("client", "", "", "", "on", $customfield);
            $_SESSION['loginurlredirect'] = "cart.php?a=login";

            if (!$custtype) {
                $custtype = "new";
            }
        }

        $smartyvalues['custtype'] = $custtype;
        $smartyvalues['clientsdetails'] = $clientsdetails;
        include "includes/countries.php";

        if (!isset($country)) {
            $country = $_SESSION['cart']['user']['country'];
        }

        $smartyvalues['clientcountrydropdown'] = getCountriesDropDown($country);
        $smartyvalues['password'] = $password;
        $smartyvalues['password2'] = $password2;
        $smartyvalues['customfields'] = $customfields;
        $smartyvalues['securityquestions'] = $securityquestions;
        $smartyvalues['shownotesfield'] = $CONFIG['ShowNotesFieldonCheckout'];

        if (!$notes) {
            $notes = $_LANG['ordernotesdescription'];
        }

        $smartyvalues['notes'] = $notes;
        $smartyvalues['accepttos'] = $CONFIG['EnableTOSAccept'];
        $smartyvalues['tosurl'] = $CONFIG['TermsOfService'];

        if (count($_SESSION['cart']['domains'])) {
            $smartyvalues['domainsinorder'] = true;
        }

        $domaincontacts = array();
        $result = select_query("tblcontacts", "", array("userid" => $_SESSION['uid'], "address1" => array("sqltype" => "NEQ", "value" => "")), "firstname` ASC,`lastname", "ASC");

        while ($data = mysql_fetch_array($result)) {
            $domaincontacts[] = array("id" => $data['id'], "name" => $data['firstname'] . " " . $data['lastname']);
        }

        $smartyvalues['domaincontacts'] = $domaincontacts;
        $smartyvalues['contact'] = $contact;

        if ($contact == "addingnew") {
            $addcontact = true;
        }

        $smartyvalues['addcontact'] = $addcontact;
        $smartyvalues['domaincontact'] = array("firstname" => $domaincontactfirstname, "lastname" => $domaincontactlastname, "companyname" => $domaincontactcompanyname, "email" => $domaincontactemail, "address1" => $domaincontactaddress1, "address2" => $domaincontactaddress2, "city" => $domaincontactcity, "state" => $domaincontactstate, "postcode" => $domaincontactpostcode, "country" => $domaincontactcountry, "phonenumber" => $domaincontactphonenumber);
        $smartyvalues['domaincontactcountrydropdown'] = getCountriesDropDown($domaincontactcountry, "domaincontactcountry");
        $gatewaysoutput = array();
        foreach ($availablegateways as $module => $vals) {
            $params = getGatewayVariables($module);
            $params['amount'] = $carttotals['rawtotal'];
            $params['currency'] = $currency['code'];

            if (function_exists($module . "_orderformoutput")) {
                $gatewaysoutput[] = call_user_func($module . "_orderformoutput", $params);
                continue;
            }
        }

        $smartyvalues['gatewaysoutput'] = $gatewaysoutput;

        if ($cartsummary) {
            $ajax = "1";
            $templatefile = "cartsummary";
            $productinfo = $orderfrm->setPid($_SESSION['cart']['cartsummarypid']);
        }

        $this->data['template'] = $templatefile;
        $this->data['smarty'] = $smartyvalues;
        return $this->data;
    }

}
