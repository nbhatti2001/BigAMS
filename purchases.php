<?php
require_once("includes/phpincludes.php");
if(isset($_POST['submit']))
{
	$docNo = $_POST['docno'];
	$reference =$_POST['reference'];
	$driver =$_POST['driver'];
	$veh =$_POST['vehicle']; 
	$cdate =  date("Y/m/d", strtotime($_POST['cdate']));
	if($_SESSION['isAdmin']==1 && $_POST['region']=='0')
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
	$flds="doc_no, reference,driver,cdate,from_,to_,vehicle,user_id,entry_type";
	$values	="'$docNo','$reference','$driver','$cdate',$from,$to,'$veh','$userId','$eType'";
	$sql 	= "insert into dispatch_main($flds) values($values)";
	$id  	= exeQuery($sql);
	//
	$flds="id,item_id,qty";
	$entryType="Dispatch";
	$items=$_POST['items'];
	foreach($items as $idx=>$value)
	{
		if($value > 0)
		{
			$value=str_replace(',','',$value);
			$sql="insert into dispatch_detail($flds) values('$id', $idx, $value)";
			//echo $sql;
			exeQuery($sql);
		}
	}
	if($eType=="Transfer")
		header("location:list_transfers.php?action=Transfer",true);
	else
		header("location:list_purchases.php?action=Purchase",true);
}
$docNo="";
if(isset($_GET['action']))
{ 
	$action =$_GET['action'];
	$docType=($action=="Transfer" && !isset($_GET['to']) ?"PO":"TO");
	$docNo = getValue("select max(doc_no) as doc_no  from dispatch_main where doc_no like '$docType%' ","doc_no");
	$docNo=getDocNumber($docNo,$docType);
}
	//
require_once("includes/header.php");
$items =getRows("select * from items");
?>
<script>
var itemCount=<?=count($items)?>;
  $(function() {
	$(".qty").keypress(function (e) {
		 //if the letter is not digit then display error and don't type anything
		 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			//display error message
			//$("#errmsg").html("Digits Only").show().fadeOut("slow");
				   return false;
		}
   });	
	//
   $("#cdate").datepicker({ dateFormat: 'dd-mm-yy' });
	//	
	$(document).keydown(
		function(e)
		{  
			//alert(e.keyCode);
			if (e.keyCode == 38) { 
				
				$(".qty:focus").next().focus();
			}
			if (e.keyCode == 40) {      
			
				$(".qty:focus").prev().focus();
			}
		});
	$('.qty').autoNumeric('init', {  lZero: 'deny', aSep: ',', mDec: 0 });   
	$('#gtotal').autoNumeric('init', {  lZero: 'deny', aSep: ',', mDec: 0 });  	
  });
 </script>
 <div style="width:600px;"> 
 <a name="top"></a> 
<form action="transfer.php" method="post" onsubmit="return AddTransfer();" >

	<div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
	<table border="0" cellpadding="5" cellspacing="0" width="600"  class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="4"><?php 
			if($_SESSION['isAdmin']==1 && !isset($_GET['to'])) 
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
			<td>Date</td> <td> <input class="txtField" readonly type="text" name="cdate" id="cdate" /> </td>
			<td>Reference #</td><td><input class="txtField" type="text" name="reference" id="reference" /></td>
		</tr>
		<?php if($_SESSION['isAdmin']==1 && !isset($_GET['to'])) { ?>
			<input type="hidden" name="region" value="0" />
		<?php }else{ ?>
		<tr>
			<td>From</td> <td> <input class="txtField" type="text" readonly value="<?=$_SESSION['location'] ?>" /> </td>
			<td>To</td> <td> <?=getCustomers()?> </td>
		</tr>
		<?php } ?>
		<tr>
			<td>Vehicle # </td> <td> <input class="txtField" type="text" name="vehicle" id="vehicle" />  </td>
			<td>Driver</td> <td> <input class="txtField" type="text" name="driver" id="driver" /> </td>
		</tr>
		<tr>
			<td>Document #</td> <td> <input value="<?=$docNo?>" readonly type="text" name="docno" id="docno" class="txtField" /> </td>
			<td>To </td> <td> <input class="txtField" readonly type="text" name="location" value="<?=$_SESSION['location']?>"  />  </td>
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
				?>	
					<tr>
						<td><div class="items" style="background-color:<?=$row['color']?>;" >&nbsp;&nbsp;&nbsp;</div> &nbsp;<?=$row['name']?></td><td> <input onchange="purchaseTotal();" class="txtField qty" type="text" name="items[<?=$row['id']?>]" id="items<?=$row['id']?>" />  </td>
					</tr>
					
					<?php } ?>
					<tr>
						<td class="gridheader">&nbsp;&nbsp;&nbsp;&nbsp; Total</td><td> <input class="txtField" type="text" name="gtotal" id="gtotal" />  </td>
					</tr>
					
					<tr>
						<td> &nbsp;</td>
						<td> <input type="submit" name="submit" value="<?=$label?>" class="btnSubmit"> </td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
</form>
</div>