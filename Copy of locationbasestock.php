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
printr($rows);
render($rows,'in');
printr($res);
exit;
//echo "<br />".date("d-m-Y H:i");
$sql="select  items.id, items.name, sum(qty) as qty ,regions.name as fromLocation, regions1.name as toLocation from dispatch_main 
	inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
	left outer join regions on dispatch_main.from_ = regions.id
	left outer join regions regions1 on dispatch_main.to_ = regions1.id
	inner join items on items.id = dispatch_detail.item_id
	where dispatch_main.entry_type in ('Transfer','Confirmed')
	group by dispatch_detail.item_id,dispatch_main.from_
	";	
	
$rows=getRows($sql);
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
$sql="select * from items where is_active=1 order by id";
$items =getRows($sql);
require_once("includes/header.php");
?>


	<table border="0" cellpadding="5" cellspacing="0" width="800" align="center" class="tblSaveForm">
	<tr class="tableheader"><td>Sr.No</td> <td> Location </td> 
	<?php foreach($items as $item){ ?>
	<td> <?=$item['display_name'] ?> </td> 
	<?php } ?>
	<td> Total </td></tr>

	<?php 
	$col1Total=0;
	$col2Total=0;
	$col3Total=0;
	$gTotal=0;
	$cnt=0; foreach($res as $idx=>$row) { 
	//printr($row);exit;
			$cnt++;
			$total=0;
			$first_key = key($row);
			$bal1=(isset($row["YELLOW WITH BLUE HANDLE"]['in'])?$row["YELLOW WITH BLUE HANDLE"]['in']:0)-(isset($row["YELLOW WITH BLUE HANDLE"]['out'])?$row["YELLOW WITH BLUE HANDLE"]['out']:0);
			$total+=$bal1;
			$bal2=(isset($row["CRATES - GREEN"]['in'])?$row["CRATES - GREEN"]['in']:0)-(isset($row["CRATES - GREEN"]['out'])?$row["CRATES - GREEN"]['out']:0);
			$total+=$bal2;
			$bal3=(isset($row["BLUE"]['in'])?$row["BLUE"]['in']:0)-(isset($row["BLUE"]['out'])?$row["BLUE"]['out']:0);
			$total+=$bal3;
			$gTotal+= $total;
			$col1Total+=$bal1;
			$col2Total+=$bal2;
			$col3Total+=$bal3;
			?>
		<tr align="center"> 
			<td> <?=$cnt?></td> 
			<td align="left"><?=$idx?></td> 
			<td> <?=$bal1?> </td>
			<td> <?=$bal2?> </td>
			<td> <?=$bal3?> </td>
			<td> <?=$total?> </td>
		</tr>
	<?php } ?>
	<tr align="center" style="font-weight:900;" > <td>&nbsp;</td> <td align="left">Total </td>  <td><?=$col1Total?> </td>  <td> <?=$col2Total?> </td> <td><?=$col3Total?> </td>  <td> <?=$gTotal?>  </td> </tr>
	</table>