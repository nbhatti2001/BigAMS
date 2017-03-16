<?php
require_once("includes/phpincludes.php");
if(isset($_POST['submit']))
{
	$tid =$_POST['tid'];
	$userId=$_SESSION['userId'];
	$sql="update dispatch_main set confirmed_by='$userId',entry_type='Confirmed' where id=$tid";
	exeQuery($sql);
	header("Location:list_transfers.php?action=Transfer&to=me",true); 
}
$tId=0;
if(isset($_GET['tid']))
	$tId=$_GET['tid'];
	$sql="select items.display_name as dname, dispatch_main.*,regions.name as toLocation, regions2.name as fromLocation,dispatch_detail.* from dispatch_main 
		inner join dispatch_detail on dispatch_main.id=dispatch_detail.id
		inner join regions on dispatch_main.to_ = regions.id
		inner join regions regions2 on dispatch_main.from_ = regions2.id
		inner join items on dispatch_detail.item_id = items.id
	where dispatch_main.id='$tId'";
	//echo $sql;
	$row=getRows($sql);
	//printr($row);exit;
	$yellow=0;
	$green=0;
	$blue=0;
	$gray=0;
	if(count($row)> 0)
	{
		
		$ref=$row[0]['reference'];
		$driver=$row[0]['driver'];
		$cdate=$row[0]['cdate'];
		$fromLocation=$row[0]['fromLocation'];
		$toLocation=$row[0]['toLocation'];
	}
	require_once("includes/header.php");
?>
<form action="transferc.php" method="post" >
	<input type="hidden" name="tid" value="<?=$tId?>" />
	<table border="0" cellpadding="5" cellspacing="0" width="500" align="center" class="tblSaveForm">
		<tr class="tableheader">
			<td colspan="4"> Receive (Transfer Shipped) </td>
		</tr>
		<tr>
			<td>Reference #</td> <td> <?=$ref?> </td>
			<td>Driver</td> <td> <?=$driver?> </td>
		
		</tr>
		<tr>
			<td>From</td> <td> <?=$fromLocation ?></td>
			<td>To</td> <td> <?=$toLocation?> </td>
		</tr>
		<tr>
			<td>Date</td> <td> <?=$cdate?> </td>
			<td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4">
				<table border="0" cellpadding="3" cellspacing="0" width="500" align="center" class="tblSaveForm">
				<?php $total=0; for($cnt=0;$cnt<count($row);$cnt++)
					{ ?>
				
					<tr>
						<td><?=$row[$cnt]['dname']?></td><td> <input readonly type="text" value="<?=$row[$cnt]['qty']?>" name="yellow" id="yellow" /> </td>
					</tr>
					<?php 
					$total+=$row[$cnt]['qty'];
					} ?>
					<tr class="table_total">
					<td >Total</td> <td><?=number_format($total ,  0 , "." ,  "," )?> </td> </tr>
					<tr>
						<td> &nbsp;</td>
						<td> <input type="submit" name="submit" value="Receive" class="btnSubmit"> </td>
					</tr>
				</table>
			</td>
		</tr>
</form>