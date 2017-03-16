<?php
require_once("includes/phpincludes.php");    
mysql_query("DELETE FROM regions WHERE id='" . $_GET["rId"] . "'");
header("Location:list_regions.php");
?>