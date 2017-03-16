<?php
require_once("includes/checklogin.php");    
require_once("includes/database.php") ;
mysql_query("DELETE FROM customers WHERE id='" . $_GET["custId"] . "'");
header("Location:list_customers.php");
?>