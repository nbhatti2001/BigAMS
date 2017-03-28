<?php
require_once("includes/phpincludes.php");
if(isset($_GET['action']))
	$action=$_GET['action'];
else
	$action="freezer";
if(isset($_POST['submit']))
{
		$id 		= $_POST['id'];
	$ref 		= $_POST['reference'];
	$cdate 		= date("Y/m/d", strtotime($_POST['cdate']));
	$customer	= $_POST['ccode'];
	$deliver 	= $_POST['daddress'];
	$fdn 		= $_POST['fdn'];
	$tso	 	= $_POST['tso'];
	$rsm	 	= $_POST['rsm'];
	$nsm	 	= $_POST['nsm'];
	$userId		= $_SESSION['userId'];
	$flds		= "reference,date,ccode,daddress,fdn,tso,rsm,nsm,user_id";
		$values		= "reference='$ref',date='$cdate',ccode='$custId',daddress='$deliver',fdn='$fdn',tso='$tso',rsm='$rsm',nsm='$nsm',user_id='$userId'";
		$sql 		= "update transfer_main set $values where id=$id";
	//echo $sql;
	exeQuery($sql);
	//
	$sql="delete from dispatch_detail where id=$id";
	exeQuery($sql);
	$itemCount = getValue("select count(*) as rcount from items where is_active=1","rcount");
	for($cnt=1;$cnt<=20;$cnt++)
	{
		if($_POST['cust'][$cnt]!='')
		{
			$customer=$_POST['cust'][$cnt];
			$salesOrder=$_POST['so'][$cnt];
			$custId=getCustId($customer);
			for($i=1;$i<=$itemCount;$i++)
			{
				if(isset($_POST['items'][$i][$cnt]) &&  $_POST['items'][$i][$cnt]!="")
				{
					$qty =$_POST['items'][$i][$cnt];
					$itemId = $_POST['item'][$i];
					$flds="id,customer,so_no, item_id,qty";
					$entryType="Dispatch";
					if($qty>0) 
					{
						$sql="insert into dispatch_detail($flds) values('$id','$custId','$salesOrder','$itemId','$qty')";
						//echo "<br /> $sql";
						exeQuery($sql);
					}
				}
			}
		}
	}
	if($action=="Return")
		header("Location:list_dispatches.php?action=Return");
	else
		header("Location:list_dispatches.php?action=Dispatch");
}
$customers = getRows("select * from customers where is_active=1");
$custCount = count($customers);
$items = getRows("select * from items where is_active=1 order by 1");
$docNo = getValue("select max(doc_no) as doc_no  from dispatch_main","doc_no");
$docNo=getDocNumber($docNo);

if(isset($_GET['dId']))
{
	$dId =$_GET['dId'];
	
	$sql="select dispatch_main.*,dispatch_detail.*, regions.name as fromName, customers.account_name,  customers.account_no from dispatch_main
	inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
	inner join regions on regions.id =dispatch_main.from_
	inner join customers on customers.id = dispatch_detail.customer
	where dispatch_main.id =$dId order by dispatch_detail.customer, dispatch_detail.item_id";
	$rows= getRows($sql);
	$itemList=ProcessItemList($rows);
	//printr($itemList);
	//
	if(count($rows) > 0)
	{
		$row		= $rows[0];
		$docNo 		= $row['doc_no'];
		$reference	= $row['reference'];
		$driver 	= $row['driver'];
		$cdate		= date("d/m/Y", strtotime($row['cdate']));
		$fromName	= $row['fromName'];
		$vehicle 	= $row['vehicle'];
	}
}
require_once("includes/header.php");
?>
<script>
var itemCount=<?=count($items)?>;
//
var searchCusts = [
<?php $cnt=1; foreach($customers as $customer) { 
	if($cnt==count($customers))
		echo '"' . $customer['account_name'] ." / " .$customer['account_no'] . '"';
	else	
		echo '"' . $customer['account_name'] ." / " .$customer['account_no'] . '",';
	$cnt++;
 } ?>
];
$(function() {
	$(".custs").autocomplete({
		  source: searchCusts
		});
		
	$(".qty").keypress(function (e) {
		 //if the letter is not digit then display error and don't type anything
		 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			//display error message
			//$("#errmsg").html("Digits Only").show().fadeOut("slow");
				   return false;
		}
   });		
   $("#cdate").datepicker({dateFormat:'dd-mm-yy'});
});
</script>
<div style="margin-bottom:100px;width:1000px;">
<?php if(count($rows)==0)
{?>
<div class="alerts" > Did not find the record to show </div>
<?php }else { ?>
<form action="edit_dispatch.php" method="post" >
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="id" value="<?=$dId?>" />
	<table border="0" cellpadding="5" cellspacing="0" width="1000" align="left" class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="4">Add <?=$action?></td>
    </tr>
		<tr>
			<td>Document #</td> <td> <input value="<?=$docNo?>" readonly type="text" name="docno" id="docno" class="txtField" /> </td>
			<td>Reference #</td> <td> <input value="<?=$reference?>" type="text" name="reference" id="reference" class="txtField" /> </td>
		</tr>
		<tr>
			<td>Date</td> <td> <input value="<?=$cdate?>" type="text" name="cdate" id="cdate" class="txtField" /> </td>
			<td>From</td> <td> <input value="<?=$fromName?>" type="text" readonly class="txtField" value="<?=$_SESSION['location'] ?>" />  </td>
		</tr>
		<tr>
			<td>Vehicle #</td> <td> <input value="<?=$vehicle?>" type="text" name="veh" id="veh" class="txtField" /> </td>
			<td>Driver</td> <td> <input value="<?=$driver?>" type="text" name="driver" id="driver" class="txtField" /> </td>
		</tr>
		<tr>
			<td colspan="4">
				<div  id="main" style="background-color:red;width:100%;float:left;">

					<div id="items" style="width:200px;background-color:brown;float:left;">
						<div style="margin-bottom:10px;" >Items</div>
						<div id="items" style="background-color:pink; border-bottom:1px solid; height: 22.4px;padding-top: 4px;"  > Sales Order # </div> 
						<?php foreach($items as $item){ ?>
							<div id="items" style="background-color:pink; border-bottom:1px solid; height: 22.4px;padding-top: 4px;"  > <?=$item['name']?> </div> 
						<?php } ?>
						<div id="items" style="background-color:pink; border-bottom:1px solid; height: 22.4px;padding-top: 4px;font-weight:900; margin-top:10px; "  > Total </div> 
					</div>
					
					<div id="items_input"  style="width:790px;background-color:yellow;float:left;overflow: scroll;">
						<div style="width:3500px;">
							<!--  Customer Selection -->
							<?php 
								$c=0;
								$cust2Show="Start";
								$cust2Trace="";
								for($cnt=1;$cnt<=20;$cnt++) { 
									
									for($c=$c;$c<count($rows);$c++)
									{
										if($cust2Trace!= $rows[$c]['account_name'] ." / " . $rows[$c]['account_no'] )
										{
											$cust2Trace = $rows[$c]['account_name'] ." / " . $rows[$c]['account_no'];
											$sOs[] = $rows[$c]['so_no'];
											$c++;
											break;
										}
									}
									if($cust2Show != "")
									{
										if($cust2Show != $cust2Trace )
											$cust2Show = $cust2Trace;
										else
											$cust2Show ="";
									}
							?>
								<span id="custs" style="display:inline;float:left;" ><input value="<?=$cust2Show?>" class="txtField custs" type="text" name="cust[<?=$cnt?>]" id="cust<?=$cnt?>" /> </span>
							<?php } ?>
							<?php for($cnt=1;$cnt<=20;$cnt++) { 
									if(isset($sOs[$cnt-1]))
										$saleOrderNo =$sOs[$cnt-1];
									else	
										$saleOrderNo="";
							?>
								<span id="custs" style="display:inline;float:left;" ><input class="txtField" value="<?=$saleOrderNo?>" type="text" name="so[<?=$cnt?>]" id="so<?=$cnt?>" /> </span>
							<?php } ?>
							
							<!--  Items Qty -->
							<?php for($i=1;$i<=count($items);$i++) { ?>
								<input type="hidden" name="item[<?=$i?>]" value="<?=$items[$i-1]['id']?>" />
								<?php for($cnt=1;$cnt<=20;$cnt++) { 
									if(isset($itemList[$cnt][$i]))
										$qty = $itemList[$cnt][$i];
									else
										$qty ="";
								?>
									<span id="custs" style="display:inline;float:left;" ><input value="<?=$qty?>" onchange="dispatchTotal(this,<?=$cnt?>);"  class="txtField qty" type="text" name="items[<?=$i?>][<?=$cnt?>]" id="items<?=$i?><?=$cnt?>" /> </span>
							<?php } }?>
							<!--  Items Grand Total -->
							<?php for($cnt=1;$cnt<=20;$cnt++) { ?>
									<span id="custs" style="display:inline;float:left;" ><input readonly class="txtField gtotal" type="text" name="gtotal[<?=$cnt?>]" id="gtotal<?=$cnt?>" /> </span>
							<?php } ?>
						</div>
					</div>
				</div>	
				

				
	<!--			<div id='TextBoxesGroup' style="display:none;">
					<div style="font-size:20px;" onclick="addRow();" >+</div>
					<div id="TextBoxDiv1">
						<input class="txtField" type="text" name="customer1" id="customer" />
						<input class="txtField" type="text" name="yellow1" id="yellow1" />
						<input class="txtField" type="text" name="green1" id="green1" />
						<input class="txtField" type="text" name="blue1" id="blue1" />
						<input class="txtField" type="text" name="red1" id="red1" />
					</div>
			</div>-->	
			
			
			</td>
		
		</tr>
	</table>
<div style="clear:both;"> </div>
<input type="submit" name="submit" value="Update" class="btnSubmit">
</form>
</div>
<div style="clear:both;"> </div>

<br /><br /><br /><br />
<?php } ?>

