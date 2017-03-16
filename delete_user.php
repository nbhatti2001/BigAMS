<?php
require_once("includes/phpincludes.php");    
mysql_query("DELETE FROM users WHERE id='" . $_GET["userId"] . "'");
header("Location:list_users.php");
?>