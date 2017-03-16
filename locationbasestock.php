<?php
require_once("includes/phpincludes.php");
$res=array();
function render($rows,$type)
{
	global $res;
	foreach($rows as $row)
	{
		if($type=='in')
			$idx=$row['toLocation'];
		else
			$idx=$row['fromLocation'];
		$idx2=$row['id'];
		if(!isset($res[$idx][$idx2][$type]))
			$res[$idx][$idx2][$type]=0;
		$res[$idx][$idx2][$type]+=$row['qty'];
		
	}
} 
$sql="select  items.id, items.name, sum(qty) as qty ,regions.name as fromLocation, regions1.name as toLocation from dispatch_main 
	inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
	left outer join regions on dispatch_main.from_ = regions.id
	left outer join regions regions1 on dispatch_main.to_ = regions1.id
	inner join items on items.id = dispatch_detail.item_id
	where dispatch_main.entry_type in ('Purchase','Return') 
	group by dispatch_detail.item_id, dispatch_main.to_
	";
$rows=getRows($sql);
//printr($rows);
render($rows,'in');
//printr($res);

//echo "<br />".date("d-m-Y H:i");
$sql="select  items.id, items.name, sum(qty) as qty ,regions.name as fromLocation, regions1.name as toLocation from dispatch_main 
	inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
	left outer join regions on dispatch_main.from_ = regions.id
	left outer join regions regions1 on dispatch_main.to_ = regions1.id
	inner join items on items.id = dispatch_detail.item_id
	where dispatch_main.entry_type in ('Confirmed') 
	group by dispatch_detail.item_id,dispatch_main.from_
	";	//'Transfer',
	
$rows=getRows($sql);
//printr($rows);
render($rows,'out');
//echo "<br />".date("d-m-Y H:i");
$sql="select  items.id, items.name, sum(qty) as qty ,regions.name as fromLocation, regions1.name as toLocation from dispatch_main 
	inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
	left outer join regions on dispatch_main.from_ = regions.id
	left outer join regions regions1 on dispatch_main.to_ = regions1.id
	inner join items on items.id = dispatch_detail.item_id
	where dispatch_main.entry_type in ('Confirmed')
	group by dispatch_detail.item_id,dispatch_main.to_, dispatch_main.from_
	";	
	
$rows=getRows($sql);
render($rows,'in');
//echo "<br />".date("d-m-Y H:i");
//render($rows,'out');
$sql="select  items.id, items.name, sum(qty) as qty ,regions.name as fromLocation, regions1.name as toLocation from dispatch_main 
	inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
	left outer join regions on dispatch_main.from_ = regions.id
	left outer join regions regions1 on dispatch_main.to_ = regions1.id
	inner join items on items.id = dispatch_detail.item_id
	where dispatch_main.entry_type in ('Dispatch')
	group by dispatch_detail.item_id
	";	
	
$rows=getRows($sql);
render($rows,'out');
//echo "<br />".date("d-m-Y H:i");
$itemFilter ="";
if(!$_SESSION['isSuperUser'])
	$itemFilter =" and for_customer in (".$_SESSION['item_group'].")";
$sql="select * from items where is_active=1 $itemFilter order by id";
$items =getRows($sql);
require_once("includes/header.php");
?>
	<table border="0" cellpadding="5" cellspacing="0" width="1000"  class="tblSaveForm">
	<tr class="tableheader"><td>Sr.No</td><td>Location</td> 
	<?php foreach($items as $item){ ?>
	<td> <?=$item['display_name']?> </td> 
	<?php } ?>
	<td> Total </td></tr>
	<?php 
	$col1Total=0;
	$col2Total=0;
	$col3Total=0;
	$gTotal=0;
	$cnt=0; 
	$arGTotal=array();
	foreach($res as $idx=>$row) 
	{ 
		$cnt++;
		$bals=array();
		foreach($items as $item)
		{
			//printr($row);exit;
			$total=0;
			$first_key = key($row);
			$itemId=$item['id'];
			$bals[]=(isset($row[$itemId]['in'])?$row[$itemId]['in']:0)-(isset($row[$itemId]['out'])?$row[$itemId]['out']:0);
		}	
		
		//printr($bal);
		//exit;
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
	<?php  } ?>
	<tr align="center" class="table_total"> <td>&nbsp;</td> <td align="left">Total </td>  
	<?php foreach($arGTotal as $total) { ?>
		<td> <?=number_format($total ,  0 , "." ,  "," )?> </td>
	<?php 
		$gTotal+=$total;
	} ?>
		<td class="table_total"> <?=number_format($gTotal ,  0 , "." ,  "," )?> </td>
	</tr>
	</table>
	
	
	<?php 
/*
			$total+=$bal1;
			$bal2=(isset($row["CRATES - GREEN"]['in'])?$row["CRATES - GREEN"]['in']:0)-(isset($row["CRATES - GREEN"]['out'])?$row["CRATES - GREEN"]['out']:0);
			$total+=$bal2;
			$bal3=(isset($row["BLUE"]['in'])?$row["BLUE"]['in']:0)-(isset($row["BLUE"]['out'])?$row["BLUE"]['out']:0);
			$total+=$bal3;
			$gTotal+= $total;
			$col1Total+=$bal1;
			$col2Total+=$bal2;
			$col3Total+=$bal3;

	*/
	?>