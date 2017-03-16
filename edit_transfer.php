<?php
require_once("includes/phpincludes.php");
if(isset($_POST['submit']))
{
	$tId= $_POST['id'];
	$reference =$_POST['reference'];
	$action= $_POST['action'];
	$driver =$_POST['driver'];
	$veh =$_POST['vehicle'];
	$cdate =  date("Y/m/d", strtotime($_POST['cdate']));
	if($_SESSION['isAdmin']==1 && $_POST['region']==0)
	{
		$to = $_SESSION['locationId'];
		$from=0;
		$eType='Purchase';
	}
	else
	{
		$from = $_SESSION['locationId'];
		$to	  = $_POST['region'];
		$eType='Transfer';
	}
	$userId=$_SESSION['userId'];
	$flds="reference,driver,cdate,from_,to_,vehicle,user_id,entry_type";
	$values	="reference='$reference',driver='$driver',cdate= '$cdate',from_ ='$from', to_='$to',vehicle= '$veh'";
	$sql 	= "update dispatch_main set $values where id=$tId";
	exeQuery($sql);
	//
	$sql="delete from dispatch_detail where id=$tId";
	exeQuery($sql);
	$flds="id,item_id,qty";
	$entryType="Dispatch";
	$items=$_POST['items'];
	foreach($items as $idx=>$value)
	{
		if($value > 0)
		{
			$sql="insert into dispatch_detail($flds) values('$tId', $idx, $value)";
			exeQuery($sql);
		}
	}
	if($action=="Purchase")
		header("Location:list_purchases.php?action=Purchase",true);
	else
		header("Location:list_dispatches.php?action=Transfer",true);
}
if(isset($_GET['tId']))
{
	$tId=$_GET['tId'];
	$action=$_GET['action'];
	if($action!="Purchase")
	{
		$sql="select dispatch_main.*,dispatch_detail.*, regions.name as fromName,regionto.id as toName from dispatch_main
		inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
		inner join regions on regions.id =dispatch_main.from_
		inner join regions as regionto on regionto.id =dispatch_main.to_
		where dispatch_main.id =$tId order by dispatch_detail.customer, dispatch_detail.item_id";
	}
	else
	{
		$sql="select dispatch_main.*,dispatch_detail.*, '' as fromName, '' as toName from dispatch_main
		inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
		where dispatch_main.id =$tId order by dispatch_detail.customer, dispatch_detail.item_id";
	}
	$rows= getRows($sql);
	$itemList=ProcessItemListTransfer($rows);
	//printr($rows);	
	if(count($rows) > 0)
	{
		$row		= $rows[0];
		$docNo 		= $row['doc_no'];
		$reference	= $row['reference'];
		$driver 	= $row['driver'];
		$cdate		= date("d/m/Y", strtotime($row['cdate']));
		$fromName	= $row['fromName'];
		$toName	= $row['toName'];
		$vehicle 	= $row['vehicle'];
	}
	//printr($rows);
}
require_once("includes/header.php");
?>
<script>
  $(function() {
    $("#cdate").datepicker({ dateFormat: 'dd-mm-yy' });
  });
 </script>
<div style="margin-bottom:100px;width:1000px;"> 
	 <?php if(count($rows)==0)
	{?>
	<div class="alerts" > Did not find the record to show </div>
	<?php }else { ?>

<form action="edit_transfer.php" method="post" >
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="id" value="<?=$tId?>" />
	<table border="0" cellpadding="5" cellspacing="0" width="550"  class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="4"><?php 
			if($rows[0]['entry_type']=="Purchase") 
			{
				echo "Purchase";
				$label="Purchase";
			}
			else
			{		
				echo "Transfer Shipped";
				$label="Transfer";
			}
		?></td>
    </tr>	
		<tr>
			<td>Date</td> <td> <input value="<?=$cdate?>" class="txtField" readonly type="text" name="cdate" id="cdate" /> </td>
			<td>Reference #</td><td><input value="<?=$reference?>"  class="txtField" type="text" name="reference" id="reference" /></td>
		</tr>

		<?php if($rows[0]['entry_type']=="Purchase") { ?>
			<input type="hidden" name="region" value="0" />
		<?php }else{ ?>
		<tr>
			<td>From</td> <td> <input class="txtField" type="text" readonly value="<?=$_SESSION['location'] ?>" /> </td>
			<td>To</td> <td> <?=getRegions($toName)?> </td>
		</tr>
		<?php } ?>
		<tr>
			<td>Vehicle # </td> <td> <input value="<?=$vehicle?>"  class="txtField" type="text" name="vehicle" id="vehicle" />  </td>
			<td>Driver</td> <td> <input value="<?=$driver?>"  class="txtField" type="text" name="driver" id="driver" /> </td>
		</tr>
		<tr>
			<td colspan="4">
				<table border="0" cellpadding="3" cellspacing="0" width="100%" align="center" class="tblSaveForm">
				<?php 
					$sql="select * from items where is_active=1 order by 1 ";
					$rows=getRows($sql);
					$cnt=0;
					foreach($rows as $row)
					{
						$cnt++;
						$idx=$row['id'];
						if(isset($itemList[$idx]))
							$qty=$itemList[$idx];
						else
							$qty="";
							
				?>	
					<tr>
						<td><div class="items" style="background-color:<?=$row['color']?>;" >&nbsp;&nbsp;&nbsp;</div> &nbsp;<?=$row['name']?></td><td> <input value="<?=$qty?>" class="txtField" type="text" name="items[<?=$row['id']?>]" id="items[<?=$row['id']?>]" />  </td>
					</tr>
					<?php } ?>
					<tr>
						<td> &nbsp;</td>
						<td> <input type="submit" name="submit" value="Update" class="btnSubmit"> </td>
					</tr>
				</table>
			</td>
		</tr>
</form>
<?php } ?>