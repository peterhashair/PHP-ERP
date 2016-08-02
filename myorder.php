<?php

/**
 * @ RA
 * */
define("CLIENTAREA", true);
require "init.php";
require "includes/orderfunctions.php";
require "includes/whoisfunctions.php";
require "includes/configoptionsfunctions.php";
require "includes/customfieldfunctions.php";
require "includes/clientfunctions.php";
require "includes/invoicefunctions.php";
require "includes/processinvoices.php";
require "includes/gatewayfunctions.php";
require "includes/fraudfunctions.php";
require "includes/modulefunctions.php";
require "includes/ccfunctions.php";
require "includes/cartfunctions.php";
require 'includes/servicefunctions.php';
initialiseClientArea($_LANG['carttitle'], "", "<a href=\"cart.php\">" . $_LANG['carttitle'] . "</a>");
checkContactPermission("orders");
check_token();
$orderfrm = new RA_OrderForm();
$cart = new RA_Carts($orderfrm, $ra);
$a = $ra->get_req_var("a");
$gid = $ra->get_req_var("gid");
$pid = (int) $ra->get_req_var("pid");
$aid = (int) $ra->get_req_var("aid");
$ajax = $ra->get_req_var("ajax");
$sld = $ra->get_req_var("sld");
$tld = $ra->get_req_var("tld");
$description = $ra->get_req_var("description");
$step = $ra->get_req_var("step");
$signup = $ra->get_req_var("signup");
$login = $ra->get_req_var("login");

$checkout = $ra->get_req_var("checkout");
$validatepromo = $ra->get_req_var("validatepromo");
$orderfrmtpl = $ra->get_config("OrderFormTemplate");
$signup = $ra->get_req_var('signup');
$username = trim($ra->get_req_var("username"));
$password = trim($ra->get_req_var("password"));
$hash = $ra->get_req_var("hash");
$goto = $ra->get_req_var("goto");

if (!isValidforPath($orderfrmtpl)) {
    exit("Invalid Order Form Template Name");
}
$orderconf = array();
$orderfrmconfig = ROOTDIR . "/templates/orderforms/" . $orderfrmtpl . "/config.php";

//
$orderform = true;
$nowrapper = false;


if (!empty($login)) {

    $loginsuccess = $istwofa = false;
    $twofa = new RA_2FA();

    if ($twofa->isActiveClients() && isset($_SESSION['2faverifyc'])) {
        $twofa->setClientID($_SESSION['2faclientid']);

        if ($ra->get_req_var("backupcode")) {
            $success = $twofa->verifyBackupCode($ra->get_req_var("code"));
        } else {
            $success = $twofa->moduleCall("verify");
        }


        if ($success) {
            validateClientLogin(get_query_val("tblclients", "email", array("id" => $_SESSION['2faclientid'])), "", true);

            if ($_SESSION['2farememberme']) {
                wSetCookie("User", $_SESSION['uid'] . ":" . sha1($_SESSION['upw'] . $ra->get_hash()), time() + 60 * 60 * 24 * 365);
            } else {
                wDelCookie("User");
            }

            RA_Session::delete("2faclientid");
            RA_Session::delete("2farememberme");
            RA_Session::delete("2faverifyc");

            if ($ra->get_req_var("backupcode")) {
                RA_Session::set("2fabackupcodenew", true);
                $gotourl = "clientarea.php?newbackupcode=true";
                header("Location: " . $gotourl);
                exit();
            }

            $loginsuccess = true;
        } else {
            if (strpos($gotourl, "?")) {
                $gotourl .= "&";
            } else {
                $gotourl .= "?";
            }

            $gotourl .= "incorrect=true";
            header("Location: " . $gotourl);
            exit();
        }
    }


    if (!$loginsuccess) {
        if (validateClientLogin($username, $password)) {
            $loginsuccess = true;

            if ($rememberme) {
                wSetCookie("User", $_SESSION['uid'] . ":" . sha1($_SESSION['upw'] . $ra->get_hash()), time() + 60 * 60 * 24 * 365);
            } else {
                wDelCookie("User");
            }
        } else {
            if (isset($_SESSION['2faverifyc'])) {
                $istwofa = true;
            } else {
                if ($hash) {
                    $autoauthkey = "";
                    require "configuration.php";

                    if ($autoauthkey) {
                        $login_uid = $login_cid = "";

                        if ($timestamp < time() - 15 * 60 || time() < $timestamp) {
                            exit("Link expired");
                        }

                        $hashverify = sha1($email . $timestamp . $autoauthkey);

                        if ($hashverify == $hash) {
                            $result = select_query_i("tblclients", "id,password,language", array("email" => $email, "status" => array("sqltype" => "NEQ", "value" => "Closed")));
                            $data = mysqli_fetch_array($result);
                            $login_uid = $data['id'];
                            $login_pwd = $data['password'];
                            $language = $data['language'];

                            if (!$login_uid) {
                                $result = select_query_i("tblcontacts", "id,userid,password", array("email" => $email, "subaccount" => "1", "password" => array("sqltype" => "NEQ", "value" => "")));
                                $data = mysqli_fetch_array($result);
                                $login_cid = $data['id'];
                                $login_uid = $data['userid'];
                                $login_pwd = $data['password'];
                                $result = select_query_i("tblclients", "id,language", array("id" => $login_uid, "status" => array("sqltype" => "NEQ", "value" => "Closed")));
                                $data = mysqli_fetch_array($result);
                                $login_uid = $data['id'];
                                $language = $data['language'];
                            }

                            if ($login_uid) {
                                $fullhost = gethostbyaddr($remote_ip);
                                update_query("tblclients", array("lastlogin" => "now()", "ip" => $remote_ip, "host" => $fullhost), array("id" => $login_uid));
                                $_SESSION['uid'] = $login_uid;

                                if ($login_cid) {
                                    $_SESSION['cid'] = $login_cid;
                                }

                                $haship = ($CONFIG['DisableSessionIPCheck'] ? "" : $ra->get_user_ip());
                                $_SESSION['upw'] = sha1($login_uid . $login_cid . $login_pwd . $haship . substr(sha1($ra->get_hash()), 0, 20));
                                $_SESSION['tkval'] = genRandomVal();

                                if ($language) {
                                    $_SESSION['Language'] = $language;
                                }

                                run_hook("ClientLogin", array("userid" => $login_uid));
                                $loginsuccess = true;
                            }
                        }
                    }
                }
            }
        }
    }

    if (!$istwofa && !$loginsuccess) {
          $infobox = '<p class="text-danger bg-danger text-alert " id="login-error"><strong><span aria-hidden="true" class="icon icon-ban"></span> </strong><br>Login Details Incorrect. Please try again.</p>';
    }
   
 
}

$templatefile = "myorder";
if (!$templatefile) {
    redir();
    exit();
}
$smartyvalues['error'] = $infobox;
$smartyvalues['carttpl'] = $orderfrm->getTemplate();
outputClientArea($templatefile, true);
?>