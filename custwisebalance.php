<?php
require_once("includes/phpincludes.php");
$res=array();
function render($rows,$eType)
{
	global $res;
	foreach($rows as $row)
	{
		$idx=$row['account_name'] . " " . $row['account_no'];
		$idx2=$row['id'];
		
		if(!isset($res[$idx][$idx2][$eType]))
			$res[$idx][$idx2][$eType]=0;
		$res[$idx][$idx2][$eType]+=$row['qty'];
	}
}
$from="";
$to="";
if($_SESSION['locationId']!=1)
{
	$myLocation=$_SESSION['locationId'];
	$from="and dispatch_main.from_=$myLocation";
	$to="and dispatch_main.to_=" . $_SESSION['locationId'] ;
}
$sql="select  customers.account_no, customers.account_name,items.id, items.name, sum(qty) as qty  from dispatch_main 
	inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
	inner join items on items.id = dispatch_detail.item_id
	inner join customers on dispatch_detail.customer = customers.id
	where dispatch_main.entry_type in ('Dispatch') $from
	group by dispatch_detail.item_id, dispatch_detail.customer
	order by customers.id, items.id
	";
	
$rows=getRows($sql);
render($rows,'out');
$sql="select   customers.account_no,  customers.account_name,items.id, items.name, sum(qty) as qty  from dispatch_main 
	inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
	inner join items on items.id = dispatch_detail.item_id
	inner join customers on dispatch_detail.customer = customers.id
	where dispatch_main.entry_type in ('Return','Confirmed') $to
	group by dispatch_detail.item_id, dispatch_detail.customer
	order by customers.id, items.id
	";
	//echo $sql;
$rows=getRows($sql);
render($rows,'in');
$sql="select * from items where is_active=1 order by id";
$items =getRows($sql);
require_once("includes/header.php");
//echo "<br />".date("d-m-Y H:i");
?>
	<table border="0" cellpadding="5" cellspacing="0" width="1000"  class="tblSaveForm">
	<tr class="tableheader" ><td>Sr.No</td> <td> Customer Name </td> 
	<?php foreach($items as $item){ ?>
	<td> <?=$item['display_name'] ?> </td> 
	<?php } ?> 
	<td> Total </td></tr>
	<?php 
	$col1Total=0;
	$col2Total=0;
	$col3Total=0;
	$gTotal=0;
	$cnt=0; 
	$arGTotal=array();
	foreach($res as $idx=>$row) { 
	//printr($row);exit;
			$cnt++;
		$bals=array();
		foreach($items as $item)
		{
			//printr($row);exit;
			$total=0;
			$first_key = key($row);
			$itemId=$item['id'];
			$bals[]=abs((isset($row[$itemId]['in'])?$row[$itemId]['in']:0)-(isset($row[$itemId]['out'])?$row[$itemId]['out']:0));
		}
			?>
		<tr class="report_data" align="center"> 
			<td> <?=$cnt?></td> 
			<td align="left"><?=$idx?></td> 
			<?php  $i=0; foreach($bals as $bal) { 
				if(!isset($arGTotal[$i]))
					$arGTotal[$i]=0;
				$arGTotal[$i]+=$bal;
				$total+=$bal;
				$i++;
				?>
			<td> <?=number_format($bal ,  0 , "." ,  "," )?> </td>
			<?php } ?>
			<td class="table_total"> <?=number_format($total ,  0 , "." ,  "," )?> </td>
		</tr>
	<?php } ?>
	<tr class="table_total" align="center"  > <td>&nbsp;</td> <td align="left">Total </td>  
		<?php foreach($arGTotal as $total) { ?>
			<td><?=number_format($total ,  0 , "." ,  "," )?>  </td>
		<?php 
		$gTotal+=$total;
		} ?>
		<td class="table_total"> <?=number_format($gTotal ,  0 , "." ,  "," )?> </td>
	</tr>
	</table>
	
	
	