<?php
require_once("includes/phpincludes.php");
if(isset($_GET['action']))
	$action=$_GET['action'];
else
	$action="return";
if(isset($_POST['submit']))
{
	$ref 		= $_POST['reference'];
	$cdate 		= date("Y/m/d", strtotime($_POST['cdate']));
	$custcode	= $_POST['ccode'];
	$custname	= $_POST['cname'];
	$deliver 	= $_POST['daddress'];
	$fdn 		= $_POST['fdn'];
	$tso	 	= $_POST['tso'];
	$rsm	 	= $_POST['rsm'];
	$nsm	 	= $_POST['nsm'];
	
	if($action=="return")
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
	if(isset($_POST['freezId']))
	{
		$id =$_POST['freezId'];
		$sql="update transfer_main set reference='$ref', date='$cdate',ccode='$custcode'
		,cname='$custname', daddress='$deliver' , fdn='$fdn', tso='$tso', rsm='$rsm', nsm='$nsm' where id='$id'";	
		exeQuery($sql);
		$sql="delete from freezer_detail where id='$id'";
		exeQuery($sql);
	}
	else
	{
		$flds		= "reference,date,ccode,cname,daddress,fdn,tso,rsm,nsm";
		$values		= "'$ref','$cdate','$custcode', '$custname','$deliver','$fdn', '$tso','$rsm','$nsm'";
		$sql 		= "insert into transfer_main($flds) values($values)";
		$id			= exeQuery($sql);
	}
	
	if(isset($_POST['dop']))
	{
		$dop		= $_POST['dop'];
	
	$purpose	= $_POST['purpose'];
	$tfrom		= $_POST['transferfrom'];
	$ftype 		= $_POST['freezertype'];
	$serial 	= $_POST['serialno'];
	$qty	 	= $_POST['qty'];
	$tid	 	= $_POST['tid'];
	
		$flds		= "dplacement,purpose,transferfrom,freezertype,serialno,qty,tid";
		$values		= "'$dop','$purpose','$tfrom', '$ftype','$serial','$qty', '$tid'";
		$sql 		= "insert into transfer_detail($flds) values($values)";
		$id			= exeQuery($sql);
	}
	else
	{
		echo"Data Base Query Failed" ;
	}
	
	
	//
	/*$itemCount = getValue("select count(*) as rcount from items where is_active=1","rcount");
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
		header("Location:list_freezer.php?action=Return",true);
	else
		header("Location:freezer.php?action=Dispatch&dispatchId=$id",true);	*/
} 
 
$freezeDetail=array();
$freezeMain =array();
$refNo="";
$freezeDate="";
$ccode="";
$cname="";
$daddress="";
$fdn="";
$tso="";
$rsm="";
$nsm="";
/*if(isset($_GET['freezerId']))
{
	$freezerId =$_GET['freezerId'];
	$sql="select * from transfer_main where id=$freezerId";
	$dispatchMain =getRows($sql);
	$sql="select freezer_detail.*,items.display_name from dispatch_detail inner join items on dispatch_detail.item_id=items.id where dispatch_detail.id=$dispatchId order by customer,items.id";
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
$docType =($action=="Dispatch"?"DOC":"RET");*/
/*
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
}*/
//$stkBals = getCustWiseStkBal($_SESSION['locationId'],$types);
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
<form action="freezer.php" method="post" onsubmit="return AddDispatch();" >
	<?//=$dispId?>
	<input type="hidden" name="stock_issue" id="stock_issue"  value="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
	<div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
	<table border="0" cellpadding="5" cellspacing="0" width="1100" align="left" class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="6">Add <?=$action?></td>
    </tr>
		<tr>
			
			<td style="float:left;">Reference #</td > <td style="float:left;"> <input  type="text" name="reference" id="reference" class="txtField" /> </td>
		 <td style="float:right;">Posting Date: <input   name="cdate" id="cdatee" class="txtField" /> </td>
		</tr>
		
		<tr>
			<td colspan="6">
			
				<div  id="main" style="background-color:pink;width:100%;float:left;">
				

					<div id="items" style="width:230px;float:left;">
						<div style="padding-left:5px;margin-bottom:5px;margin-top:2px;font-weight:900; " >Customer Information</div>
						
						<tr>
						
						</div>
			
			<td style="float:left;">Customer Code</td > <td style="float:left;"> <input  type="text" name="ccode" id="ccode" class="txtField" /> </td>
		 <td style="float:right;">Customer Name: <input  type="text" name="cname" id="cname" class="txtField" /> </td>
		 
		 
		</tr>
	
		<tr> 
		
		<td style="float:left;">Delivery Address: <input style= "width:1100px;" type="text" name="daddress" id="daddress" class="txtField" /> </td>
	
		</tr>
		
	
		<tr>
	<td> <div style="background-color:pink;padding-left:5px;margin-bottom:5px;margin-top:2px;font-weight:900; " >Freezer Information</div> </td>
	</tr>
				
		<tr>
			
			<td style="float:left;">Date of Return </td > <td style="float:left;"> <input  name="dop" id="cdate" class="txtField" /> </td>
		 <td style="float:right;">Purpose :

						<select name="purpose">	
						<option value="new">New Placement</option>
						
						</select>
		</tr>		

		
		
		<?php for($cnt=1; $cnt<=10; $cnt++) { ?>
			
		<tr>		
			<td style="float:left;">Transfer to:
			
			<?=getRegions("",'region_'.$cnt,"document.getElementById('qty2').value=1")?> 
			</td >
		 <td style="float:left;">Freezer Type: 
						<select name="freezertype">
						<option value="volvo">W-Right Up</option>
												<option value="saab">Top Glass</option>
						</select> </td>
			<td style="float:left;">Serial No: <input  type="text" name="serialno" id="serialno" class="txtField" /> </td>
			<td style="float:left;">Qty: <input  type="text" name="qty" id="qty2" class="txtField" /> </td>
		 
		</tr>
			
		
		<?php }
		
		?>
	
				
			

	
	
	
	

	

		<tr>
	<td> <div style="background-color:pink;padding-left:5px;margin-bottom:5px;margin-top:2px;font-weight:900; " >Authority Information</div> </td>
	</tr>
				
		<tr>
			<tr>
			<td style="float:left;">FPN Posted By: </td > <td style="float:left;"> <input type="text" name="fdn" id="fdn" class="txtField" /> </td>
		 <td style="float:left;">TSO:  <?=getFaculty("",'tso')?> </td>

						
				
			
			</tr>
			
			
			<td style="float:left;">RSM:     <?=getFaculty("",'rsm')?></td > 
			
			
			
			
		 <td style="float:left;">NSM: 
						<?=getFaculty("",'nsm')?> </td>
			
			
		 
		</tr>
			
	




			
						
						</div>
						
						
						
					</div>	
					
						
						
					
					
					</div>
				</div>	
				

				
	
			
			
			</td>
		
		
		</tr>
		
	</table>
	
	
<div style="clear:both;"> </div>
<input type="submit" name="submit" value="<?=$action?>" class="btnSubmit">

</form>
</div>
<div style="clear:both;"> </div>

<br /><br /><br /><br />
<script>
$(function() {
	
   $("#cdatee").datepicker({ dateFormat: 'dd-mm-yy' });
   $("#cdate").datepicker({ dateFormat: 'dd-mm-yy' });
});

</script>
<?php require_once("includes/footer.php"); ?>


