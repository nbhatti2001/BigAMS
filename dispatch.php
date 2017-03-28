<?php
require_once("includes/phpincludes.php");
if(isset($_GET['action']))
	$action=$_GET['action'];
else
	$action="Dispatch";
if(isset($_POST['submit']))
{
	$docNo 		= $_POST['docno'];
	$reference 	= $_POST['reference'];
	$action 	= $_POST['action'];
	$veh	 	= $_POST['veh'];
	$driver 	= $_POST['driver'];
	$cdate 		= date("Y/m/d", strtotime($_POST['cdate']));
	if($action=="Dispatch")
	{
		$from 		= $_SESSION['locationId'];
		$to_		=0;
	}
	else	
	{
		$from 		= 0;
		$to_		= $_SESSION['locationId'];
	}		
	$userId		= $_SESSION['userId'];
	if(isset($_POST['dispId']))
	{
		$id =$_POST['dispId'];
		$sql="update dispatch_main set reference='$reference', cdate='$cdate',vehicle='$veh',driver='$driver' where id='$id'";	
		exeQuery($sql);
		$sql="delete from dispatch_detail where id='$id'";
		exeQuery($sql);
	}
	else
	{
		$flds		= "doc_no,driver,vehicle, reference,cdate,from_, to_ ,user_id,entry_type";
		$values		= "'$docNo','$driver','$veh', '$reference','$cdate','$from', '$to_','$userId','$action'";
		$sql 		= "insert into dispatch_main($flds) values($values)";
		$id			= exeQuery($sql);
	}
	//
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
					$flds="id,customer, so_no, item_id, qty";
					$entryType="Dispatch";
					if($qty>0)
					{
						$sql="insert into dispatch_detail($flds) values('$id','$custId','$salesOrder','$itemId','$qty')";
						exeQuery($sql);
					}
				}
			}
		}
	}
	if($action=="Return")
		header("Location:list_dispatches.php?action=Return",true);
	else
		header("Location:dispatch.php?action=Dispatch&dispatchId=$id",true);	
}

$dispatchDetail=array();
$dispatchMain =array();
$refNo="";
$dispDate="";
$dispFrom="";
$dispVeh="";
$dispDriver="";
if(isset($_GET['dispatchId']))
{
	$dispatchId =$_GET['dispatchId'];
	$sql="select * from dispatch_main where id=$dispatchId";
	$dispatchMain =getRows($sql);
	$sql="select dispatch_detail.*,items.display_name from dispatch_detail inner join items on dispatch_detail.item_id=items.id where dispatch_detail.id=$dispatchId order by customer,items.id";
	$dispatchDetail =getRows($sql);
	$sql="select distinct concat(customers.account_name, ' / ', customers.account_no) as account_name,dispatch_detail.so_no from dispatch_detail inner join customers on dispatch_detail.customer=customers.id where dispatch_detail.id=$dispatchId order by customers.id";
	$custs =getRows($sql);
	$dispatchDetail = formatDispItems($dispatchDetail);
	//printr($dispatchMain);
}
$regionFilter="";
if(!$_SESSION['isSuperUser'])
	$regionFilter = "and region =". $_SESSION['locationId'];

$types="1";
$dispId="";
$sql="select * from customers where is_active=1 $regionFilter order by id";
$customers = getRows($sql);
$custCount = count($customers);
$items = getRows("select * from items where is_active=1  and for_customer in ($types) order by 1");
$docType =($action=="Dispatch"?"DOC":"RET");

if(isset($dispatchMain[0]['doc_no']) && $docType =='DOC')
{
	$dispId="<input type='hidden' name='dispId' value='". $dispatchMain[0]['id'] ."' />";
	$docNo =$dispatchMain[0]['doc_no'];
	$refNo =$dispatchMain[0]['reference'];
	$dispDate = date("d-m-Y", strtotime($dispatchMain[0]['cdate']));
	$dispFrom = getValue("select name from regions where id=". $dispatchMain[0]['from_'],"name");
	$dispVeh =$dispatchMain[0]['vehicle'];
	$dispDriver =$dispatchMain[0]['driver'];
}
else
{
	$docNo = getValue("select max(doc_no) as doc_no  from dispatch_main where doc_no like '$docType%' ","doc_no");
	$docNo = getDocNumber($docNo,$docType);
}
$stkBals = getCustWiseStkBal($_SESSION['locationId'],$types);
//printr($stkBals);
require_once("includes/header.php");
?>
<script>
var stkBal = new Array(100);
var itemCodes = new Array(<?=count($items)?>);
<?php
$cnt=0;
foreach($stkBals as $iCode=>$stkBal){?>
itemCodes[<?=$cnt?>] = <?=$iCode?> ;
stkBal[<?=$iCode?>]=<?=$stkBal?>;
<?php
$cnt++;
 } ?>
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
   $("#cdate").datepicker({ dateFormat: 'dd-mm-yy' });
});
</script>
<a name="top"></a> 
<div style="margin-bottom:100px;width:1000px;">
<form action="dispatch.php" method="post" onsubmit="return AddDispatch();" >
	<?=$dispId?>
	<input type="hidden" name="stock_issue" id="stock_issue"  value="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
	<div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
	<table border="0" cellpadding="5" cellspacing="0" width="1000" align="left" class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="6">Add <?=$action?></td>
    </tr>
		<tr>
			<td>Document #</td> <td> <input value="<?=$docNo?>" readonly type="text" name="docno" id="docno" class="txtField" /> </td>
			<td>Reference #</td> <td> <input value="<?=$refNo?>" type="text" name="reference" id="reference" class="txtField" /> </td>
			<td>Date</td> <td> <input value="<?=$dispDate?>" type="text" name="cdate" id="cdate" class="txtField" /> </td>
		</tr>
		<tr>
			<td>From</td> <td> <input value="<?=$dispFrom?>" type="text" readonly class="txtField" value="<?=$_SESSION['location'] ?>" />  </td>
			<td>Vehicle #</td> <td> <?=getVehicles($dispVeh,'driver',$regionFilter)?>  </td>
			<td>Driver</td> <td> <?=getDrivers($dispDriver,'driver',$regionFilter)?>  </td>
		</tr>
		<tr>
			<td colspan="6">
				<div  id="main" style="background-color:pink;width:100%;float:left;">

					<div id="items" style="width:230px;float:left;">
						<div style="padding-left:5px;margin-bottom:5px;margin-top:2px;font-weight:900; " >Customer >></div>
						<div id="items" style="padding-left:5px;background-color:pink;border-top:1px solid; border-bottom:1px solid; height: 20px;padding-top: 4px; margin-bottom:15px;"  > Sales Invoice # >></div> 
						<?php foreach($items as $item){ ?>
							<div id="items" style="padding-left:5px;background-color:pink; border-top:1px solid; border-bottom:1px solid; height: 21.7px;padding-top: 4px;font-size:12px; "  > <?=$item['name']?> </div> 
						<?php } ?>
						<div id="items" style="padding-left:5px;background-color:pink;border-top:1px solid; border-bottom:1px solid; height: 22.4px;padding-top: 4px;font-weight:900; margin-top:12px; "  > Total </div> 
					</div>
					
					<div id="items_input"  style="width:760px;float:left;overflow: scroll;">
						<div style="width:3500px;">
							<!--  Customer Selection -->
							<?php for($cnt=1;$cnt<=20;$cnt++) { 
								$idx=$cnt-1;
								$custName="";
								if(isset($custs[$idx]['account_name']))
									$custName=$custs[$idx]['account_name'];
							?>
								<span id="custs" style="display:inline;float:left;" ><input value="<?=$custName?>" class="txtField custs" type="text" name="cust[<?=$cnt?>]" id="cust<?=$cnt?>" /> </span>
							<?php } ?>
							
							
							<?php for($cnt=1;$cnt<=20;$cnt++) { 
								$idx=$cnt-1;
								$SalesInfo="";
								if(isset($custs[$idx]['so_no']))
									$SalesInfo=$custs[$idx]['so_no'];							
							?>
								<span id="custs" style="display:inline;float:left;margin-bottom:10px;" ><input value="<?=$SalesInfo?>"   class="txtField" type="text" name="so[<?=$cnt?>]" id="so<?=$cnt?>" /> </span>
							<?php } ?>
							
							<!--  Items Qty -->
							<?php for($i=1;$i<=count($items);$i++) { ?>
								<input type="hidden" name="item[<?=$items[$i-1]['id']?>]" value="<?=$items[$i-1]['id']?>" />
								<?php for($cnt=1;$cnt<=20;$cnt++) { 
								$idx=$items[$i-1]['id'];
								$qty='';
								if(isset($dispatchDetail[$cnt][$idx]))
									$qty=$dispatchDetail[$cnt][$idx];
								?>
									<span id="custs" style="display:inline;float:left;" ><input value="<?=$qty?>" onblur="checkStock(this.id)" onchange="dispatchTotal(this,<?=$cnt?>,itemCodes);"  class="txtField qty" type="text" name="items[<?=$items[$i-1]['id']?>][<?=$cnt?>]" id="items_<?=$items[$i-1]['id']?>_<?=$cnt?>" /> </span>
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
<input type="submit" name="submit" value="<?=$action?>" class="btnSubmit">
</form>
</div>
<div style="clear:both;"> </div>

<br /><br /><br /><br />
<?php require_once("includes/footer.php"); ?>


