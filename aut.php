<?php
require_once("includes/phpincludes.php");
if(isset($_GET['action']))
	$action=$_GET['action'];
else
	$action="Dispatch";


$dispatchDetail=array();
$dispatchMain =array();
$refNo="";
$dispDate="";
$dispFrom="";
$dispVeh="";
$dispDriver="";

$regionFilter="";

$types="1";
$dispId="";
$sql="select * from customers where is_active=1 $regionFilter order by id";
$customers = getRows($sql);
$custCount = count($customers);
$items = getRows("select * from items where is_active=1  and for_customer in ($types) order by 1");
$docType =($action=="Dispatch"?"DOC":"RET");

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
<form action="dispatch.php" method="post"  >
	<?=$dispId?>
	<input type="hidden" name="stock_issue" id="stock_issue"  value="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
	<div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
	<table border="0" cellpadding="5" cellspacing="0" width="1000" align="left" class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="6">Add <?=$action?></td>
    </tr>
		
		<tr>
			<td colspan="6">
				<div  id="main" style="background-color:pink;width:100%;float:left;">

					<div id="items" style="width:230px;float:left;">
						<div style="padding-left:5px;margin-bottom:5px;margin-top:2px;font-weight:900; " >Customer >></div>
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


