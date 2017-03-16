<?php
require_once("includes/phpincludes.php");    
mysql_query("DELETE FROM items WHERE id='" . $_GET["itemId"] . "'");
header("Location:list_items.php");
?>