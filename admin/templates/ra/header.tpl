<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$pagetitle}</title>
<link rel="stylesheet" type="text/css" href="templates/{$template}/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="templates/{$template}/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="templates/{$template}/css/main.css" />
<!-- disabled for testing -->
<link rel="stylesheet" type="text/css" href="templates/{$template}/css/oldwhmcs.css" /> 

<script type="text/javascript" src="templates/{$template}/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="templates/{$template}/js/all-min.js"></script>
<script type="text/javascript" src="templates/{$template}/bootstrap/js/bootstrap.min.js"></script>
<!-- DEMO GRAPH DATA -->
<script type="text/javascript" src="templates/{$template}/js/testdata.js"></script>
<!-- DEMO END -->
</head>
<body>
<!-- {debug} -->
<div id="wrapper">
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    <a class="navbar-brand" href="index.php"></a><img src="templates/{$template}/images/ui-logodpi.png" width="350" height="92" alt="{$pagetitle}"/></div>
  <div class="collapse navbar-collapse navbar-ex1-collapse">
 <!-- SIDEBAR -->
  {include file="$template/sidebar.tpl"}
   
    <ul class="nav navbar-nav navbar-right navbar-user">
    <li class="smalldate">{$smarty.now|date_format:"%A, %d %B %Y, %H:%M"}</li>
      <li class="dropdown messages-dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Notices <span class="badge">{$sidebarstats.orders.pending+$sidebarstats.tickets.awaitingreply+$sidebarstats.invoices.overdue}</span> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li class="dropdown-header">{$sidebarstats.orders.pending+$sidebarstats.tickets.awaitingreply+$sidebarstats.invoices.overdue} New Notices</li>
          <li class="message-preview"> <a href="orders.php?status=Pending"> <span class="avatar"><i class="fa fa-bell"></i></span> <span class="message">{$sidebarstats.orders.pending} New Orders</span> </a> </li>
          <li class="divider"></li>
          <li class="message-preview"> <a href="invoices.php?status=Overdue"> <span class="avatar"><i class="fa fa-bell"></i></span> <span class="message">{$sidebarstats.invoices.overdue} Invoices Overdue</span> </a> </li>
          <li class="divider"></li>
          <li><a href="supporttickets.php">{$_ADMINLANG.stats.ticketsawaitingreply} <span class="badge">{$sidebarstats.tickets.awaitingreply}</span></a></li>
        </ul>
      </li>
      <li class="dropdown user-dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {$admin_username}<b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="myaccount.php"><i class="fa fa-user"></i> Admin Profile</a></li>
          <li><a href="#notes"><i class="fa fa-gear"></i> My Notes</a></li>
          <li class="divider"></li>
          <li><a href="/"><i class="fa fa-gear"></i> Client Area</a></li>
          <li class="divider"></li>
          <li><a href="logout.php"><i class="fa fa-power-off"></i> Log Out</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1>{$pagetitle} <small>new extra note for page<!-- NEEDS CODING INTO $pagetitle_note --></small></h1>
                    <!-- BREAK INTO ALERT BOX -->
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        NOTICE - THIS IS AN ALERT FOR THIS PAGE - CODED ALERTS NEEDS AN INTERFACE <br>                         
                    </div>
                    <!-- ALERT BOX END -->
                </div>
            </div>
