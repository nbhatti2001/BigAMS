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
	$type		= $_POST['action'];
	//$dor		= $_POST['returndate'];
	$fdn 		= $_POST['fdn'];
	$tso	 	= $_POST['tso'];
	$rsm	 	= $_POST['rsm'];
	$nsm	 	= $_POST['nsm'];
	$from 		= $_SESSION['locationId'];
	$userId		= $_SESSION['userId'];
	
	$custId=getCustId($customer);
	

	$flds		= "reference,date,ccode,daddress,type, fdn,tso,rsm,nsm,user_id";
	$values		= "reference='$ref',date='$cdate',ccode='$custId',daddress='$deliver',type='$type',fdn='$fdn', tso='$tso',rsm='$rsm',nsm='$nsm',user_id='$userId'";
	$sql 		= "update transfer_main set $values where id=$id";
	exeQuery($sql);
	//printr($_POST);exit;
	$message = "Freezer Updated Successfully";
	
	$sql="delete from transfer_detail where id=$id";
	exeQuery($sql);
	
		$dop		= $_POST['dop'];
		$purpose	= $_POST['purpose'];
		//printr($_POST);exit;
		
		for($cnt=1;$cnt<=10;$cnt++)
	{
			
				if($_POST['region'.$cnt]!='')
				{
					$tfrom		= $_POST['region'.$cnt];
					$ftype 		= $_POST['freezertype'.$cnt];
					$serial 	= $_POST['serialno'.$cnt];
					$qty	 	= $_POST['qty'.$cnt];
					$flds		= "id,dplacement,purpose,transferfrom,freezertype,serialno,qty";
					$values		= "'$id','$dop','$purpose','$tfrom', '$ftype','$serial','$qty'";
					$sql 		= "insert into transfer_detail($flds) values($values)";
					
					exeQuery($sql);
				//printr($_POST);exit;
				
				}
				
				else{
					
					header("Location:list_freezer.php?action=freezer",true);
					
					
					
				}
				
				
				
	}
}


if(isset($_GET['fId']))
{
	$fid=$_GET['fId'];
	
	$sql1="select transfer_main.*, customers.account_name,  customers.account_no from transfer_main
	inner join customers on customers.id = transfer_main.ccode
	where transfer_main.id=$fid";
	
	$rs=getRows($sql1);
		
	if(count($rs) > 0)
	{
	$row		= $rs[0];
	$ref 		= $row['reference'];
	$cdate 		= date("Y/m/d", strtotime($row['date']));
	$custom		= $row['account_name'];
	$deliver 	= $row['daddress'];
	$type		= $row['type'];
	//$dor		= $row['returndate'];
	$fdn 		= $row['fdn'];
	$tso	 	= $row['tso'];
	$rsm	 	= $row['rsm'];
	$nsm	 	= $row['nsm'];
	
	}
	
	
	$sql2="select * from transfer_detail where id=$fid";
	
	$rss=getRows($sql2);
	

	
	if(count($rss) > 0)
	{
				$row		= $rss[0];
				$dop		= $row['dplacement'];
				$purpose	= $row['purpose'];
				$tfrom		= $row['transferfrom'];
				$ftype 		= $row['freezertype'];
				$serial 	= $row['serialno'];
				$qty	 	= $row['qty'];
	
	}
	
	
	
}

$freezeDetail=array();
$freezeMain =array();



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
   $("#cdatee").datepicker({ dateFormat: 'dd-mm-yy' });
});


</script>

<a name="top"></a> 
<div style="margin-bottom:100px;width:1000px;">

<form  action="edit_freezer.php" method="post"  onsubmit="return AddFreezer();" >

	<?//=$dispId?>
	<input type="hidden" name="stock_issue" id="stock_issue"  value="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="id" value="<?=$fid?>" />
	<div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
	<table border="0" cellpadding="5" cellspacing="0" width="1000" align="left" class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="6">Add <?=$action?></td>
		
		
    </tr>
		<tr>
			
		<td style="float:left;">Reference #</td > <td style="float:left;"> <input  type="text" name="reference" value="<?=$ref?>" id="reference" class="txtField" /> </td>
	
		<td style="float:right;">Posting Date: <input  type="text" name="cdate"   value="<?=$cdate?>" id="cdate" class="txtField" /> </td>
		</tr>
		
	<tr>
		<td colspan="6">
			
			<div  id="main" style="background-color:pink;width:100%;float:left;">
				

		<tr>

			
			<?php 
								
								$custName="";
								if(isset($custs['account_name']))
									$custName=$custs['account_name'];
							?>
			
						<td style="float:left;">Customer Name</td > 
						<td style="float:left;"> 

						<input value="<?=$custName.$custom?>"   <?=$custom?>  class="txtField custs" type="text" name="ccode" id="ccode" />
				
				
				</td>
		</tr>
	
		<tr> 
		
		<td style="float:left;">Delivery Address: <input style= "width:900px;" type="text" name="daddress" value="<?=$deliver?>" id="daddress" class="txtField" /> </td>
	
		</tr>

	
		<tr>
	<td> <div style="background-color:pink;padding-left:5px;margin-bottom:5px;margin-top:2px;font-weight:900; " >Freezer Information</div> </td>
	</tr>
		
			<?php if ($_GET['action']=='freezer'){?>
		
			
		<tr>
			
			<td style="float:left;">Date of Placement </td > <td style="float:left;"> 
			<input  type="text" name="dop" value="<?php echo $dop?>" id="cdatee"  class="txtField" /> 
			
			
			
			</td>
		 <td style="float:right;">Purpose:

						<select name="purpose">	
						
						<option value="new">New</option>
						
						</select>
						</td>
		
			<?php	 }
			
			else 
			{  
		
		?>
		
	
			
			<td style="float:left;">Date of Return </td > <td style="float:left;"> 
			<input  type="text" name="returndate" value="<?echo $dor?>"  id="cdatee" class="txtField" /> 
			
			
			
			</td>
			<td style="float:right;">Purpose :

						<select name="purpose">	
						<option value="repairing">Repairing</option>
						
						</select>
						</td>
		</tr>	
		
	
	
			<?php } ?>
			
			 
<?php 



$start=1;
foreach ($rss as $row )
{



	
	?>
			



	<tr>
	
	
			<td style="float:left;">Transfer From:
				
				<?=getRegions($row['transferfrom'],'region'.$start,"document.getElementById('qty$start').value=1")?>
			</td>   
			
			 <td style="float:left;" >Freezer Type: 
					
					<select name="freezertype<?=$start?>">
									
						<option value="w-right up" <?php if($row['freezertype']=="w-right up") echo 'selected="selected"'; ?> >W-Right Up</option>
						<option value="top glass" <?php if($row['freezertype']=="top glass") echo 'selected="selected"'; ?> >Top Glass</option>
												
												
												
					</select> 
									

						</td>
						
					
						
			<td style="float:left;">Serial No: <input  type="text" name="serialno<?=$start?>" value="<?=$row['serialno']?>" id="serialno" class="txtField" required/> </td>
						
			<td style="float:left;">Qty: <input  type="text" name="qty<?=$start?>" id="qty<?=$cnt?>"  value="<?=$row['qty']?>"  class="txtshort"  /> </td>
			
		</tr>
		

			

	
		
	<?php 
	$start++;
	
	}


?>
	
<?php
	for(  $cnt=$start; $cnt<=10;  $cnt++ ) { 
		?>
	
	
	
	
		<tr>
		
			<td style="float:left;">Transfer From:
				<?=getRegions("",'region'.$cnt,"document.getElementById('qty$cnt').value=1")?>
			</td>
		
		 <td style="float:left;" >Freezer Type: 
					
									<select name="freezertype<?=$cnt?>" >
									<option value="w-right up">W-Right Up</option>
									<option value="top glass">Top Glass</option>
												
												
									</select> 
						</td>
						
					
						
			<td style="float:left;">Serial No: <input  type="text" name="serialno<?=$cnt?>"  id="serialno" class="txtField" /> </td>
						
			<td style="float:left;">Qty: <input  type="text" name="qty<?=$cnt?>" id="qty<?=$cnt?>"   class="txtshort"  /> </td>
		</tr>
	
	
	
	<?php 
	
	
	} 

	?>
	
	
		<tr>
	<td> <div style="background-color:pink;padding-left:5px;margin-bottom:5px;margin-top:2px;font-weight:900; " >Authority Information</div> </td>
	</tr>
				
		<tr>
			<tr>
			<td style="float:left;">FDN Posted By: </td > <td style="float:left;"> <input type="text" name="fdn" value="<?=$fdn ?>" id="fdn" class="txtField" /> </td>
			<td style="float:left;" >TSO: <?=getFaculty("",'tso')?>   </td>

			<td style="float:left;">RSM:<?=getFaculty("",'rsm')?></td > 
			
			<td style="float:left;">NSM: <?=getFaculty("",'nsm')?> </td>
			</tr>
		 
		</tr>
			
		</div>
		
		</td>
	</tr>
		
</table>
	
	
	<div style="clear:both;"> </div>
	<input type="submit" name="submit" value="update" class="btnSubmit">

		</form>
</div>
<div style="clear:both;"> </div>

<br /><br /><br /><br />

<?php require_once("includes/footer.php"); ?>


