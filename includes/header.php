<html>
<!doctype html>
<html lang=''>
<head>
	<meta charset='utf-8'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/menu_styles.css">
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
	<link rel="stylesheet" type="text/css" href="css/jstyles.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>	 
	<script src="js/menu_script.js"></script>
	<script src="http://www.decorplanit.com/plugin/autoNumeric-1.9.18.js"></script>
	<script type="text/javascript" src="js/validation.js"></script>
    <title>- : - AMS - : - </title>
</head>

<body>
<a name="top"></a>
<div id="main-contents" class="main-contents" >
<div id="left-panel"> &nbsp;</div>

<div id="main-body" class="main-body">

<div class="top_header" style="width:1000px;" >
<a href="home.php">
<img src="images/logo.jpg" width="85" height="80" />
</a>
<span style="position:absolute;top:40px; color:#1B1D4D;font-size:20px;font-weight:900;font-family:verdana;">&nbsp; Assets Movement System</span>

<img style="float:right; " src="images/Plastics-crates-akumplast.jpg" width="85" height="80" />

   <div class="menu_txt">
	   <div >Welcome : <?=ucwords($_SESSION['user'])?></div>
	   <div >Location : <?=$_SESSION['location']?></div>
   </div>
</div>
<div class="top_menu" id='cssmenu'>
<ul>
<?php if($_SESSION['isSuperUser']==1){ ?>
   <li class='has-sub'><a href='#'><span>Administrator</span></a>
      <ul>
	    <li class='active has-sub'><a href='#'><span>User</span></a>
		<ul>
			<li><a href='add_user.php'><span>Add User</span></a></li>
			<li><a href='list_users.php'><span>All Users</span></a></li>
		</ul>
		</li>	  
         <li class='has-sub'><a href='#'><span>Region</span></a>
            <ul>
               <li><a href='add_region.php'><span>Add Region</span></a></li>
               <li class='last'><a href='list_regions.php'><span>All Region</span></a></li>
            </ul>
         </li>
         <li class='has-sub'><a href='#'><span>City</span></a>
            <ul>
               <li><a href='add_city.php'><span>Add City</span></a></li>
               <li class='last'><a href='list_cities.php'><span>All City</span></a></li>
            </ul>
         </li>
         <li class='has-sub'><a href='#'><span>Customer</span></a>
            <ul>
               <li><a href='add_customer.php'><span>Add Customer</span></a></li>
               <li class='last'><a href='list_customers.php'><span>All Customer</span></a></li>
            </ul>
         </li>
         <li class='has-sub'><a href='#'><span>Item</span></a>
            <ul>
               <li><a href='add_item.php'><span>Add Item</span></a></li>
               <li class='last'><a href='list_items.php'><span>All Item</span></a></li>
            </ul>
         </li>
         <li class='has-sub'><a href='#'><span>Rolls</span></a>
            <ul>
               <li><a href='add_roll.php'><span>Add Roll</span></a></li>
               <li class='last'><a href='list_rolls.php'><span>All Rolls</span></a></li>
            </ul>
         </li>
         <li class='has-sub'><a href='#'><span>Groups</span></a>
            <ul>
               <li><a href='add_group.php'><span>Add Group</span></a></li>
               <li class='last'><a href='list_groups.php'><span>All Groups</span></a></li>
            </ul>
         </li>
		 
		 
      </ul>
   </li>
<?php } //if($_SESSION['locationId']!=1 || $_SESSION['isSuperUser']==1 ) { ?>
   <li class='has-sub'><a href='dispatch.php'><span>Dispatch</span></a>
		<ul>
			<li><a href='dispatch.php?action=Dispatch'><span>Add Dispatch</span></a></li>
			<li class='last'><a href='list_dispatches.php?action=Dispatch'><span>All Dispatch</span></a></li>
		</ul>
   </li>
   
    <li class='has-sub'><a href='freezer.php'><span>Freezer</span></a>
		<ul>
			<li><a href='freezer.php?action=freezer'><span>Add Freezer</span></a></li>
			<li class='last'><a href='list_freezer.php?action=freezer'><span>All Freezer</span></a></li>
			<li class='last'><a href='freezer.php?action=return'><span>Return</span></a></li>
		</ul>
   </li>
   
   <li class='has-sub'><a href='dispatch.php'><span>Return</span></a>
		<ul>
			<li><a href='dispatch.php?action=Return'><span>Add Return</span></a></li>
			<li class='last'><a href='list_dispatches.php?action=Return'><span>All Return</span></a></li>
		   
		</ul>
   </li>
   <?php //} ?>
   <li class='has-sub'><a href='transfer.php'><span>Transfer</span></a>
		<ul>
			<li><a href='transfer.php?action=Transfer&to=1'><span>Transfer Shipped</span></a></li>
			<!-- <li><a href='list_transfers.php?action=Transfer'><span>Transfered</span></a></li> -->
			<li class='last'><a href='list_transfers.php?action=Transfer&to=me'><span>Transfer In Transit</span></a></li>
			<li class='last'><a href='list_transfers.php?action=Confirmed'><span>Transfer Received</span></a></li>
			<?php if($_SESSION['isAdmin']==1 || $_SESSION['isSuperUser']==1) { ?>
			<li><a href='transfer.php?action=Transfer'><span>Purchases</span></a></li>
			<li><a href='list_purchases.php?action=Purchase'><span>All Purchases</span></a></li>
			<?php } ?>
			
		</ul>
   </li>
   
   <li class='has-sub'><a href='dispatch.php'><span>Reports</span></a>
		<ul>
		   <li><a href='locationbasestock.php'><span>Location Base Stock Balance</span></a></li>
		   <li><a href='custwisebalance.php'><span>Customer Wise Balance</span></a></li>
		   <li><a href='customers.php'><span>All Customer</span></a></li>
		   
		</ul>
   </li>
   
      <li class='has-sub'><a href='dispatch.php'><span>User</span></a>
		<ul>
		   <li><a href='logout.php'><span>Logout</span></a></li>
		   <li><a href='changep.php'><span>Change Password</span></a></li>
		   
		</ul>
   </li>
   <!-- <li class='last'><a href='logout.php'><span>Logout</span></a></li> -->

</ul>

</div>

