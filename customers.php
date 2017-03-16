<?php
require_once("includes/phpincludes.php");
$sql="select customers.*, cities.name as city_name, regions.name as region_name from customers 
	inner join cities on customers.city = cities.id
	inner join regions on customers.region = regions.id 
 where customers.is_active=1 order by id";
$res =getRows($sql);
require_once("includes/header.php");
?>
<div>

	<table border="0" cellpadding="5" cellspacing="0" width="1000"  class="tblSaveForm">
	<tr class="tableheader"><td>Account Number</td> <td> Name </td><td>Group </td> <td> Region </td>  <td> City </td> 
	</tr>
	
	<?php 
	foreach($res as $row) 
	{?>
		<tr class="report_data" align="center"> 
			<td > <?=$row['account_no']?></td> 
			<td style="text-align:left;" > <?=$row['account_name']?></td> 
			<td style="text-align:left;"> <?=$row['cust_group']?></td> 
			<td style="text-align:left;"> <?=$row['region_name']?></td> 
			<td style="text-align:left;"> <?=$row['city_name']?></td> 
			
		</tr>
	<?php  } ?>
	
	
	</table>
	<br /><br /><br />
</div>