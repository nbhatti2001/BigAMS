<?php
require_once("includes/phpincludes.php");

if(isset($_POST['submit']))
{
	
	function processIt($arr)
	{
		$nArr =array();
		foreach($arr as $idx=>$vals)
		{
			if($idx != "submit" && $idx !="name")
			{
				$parts = explode("_",$idx);
				$nIdx = $parts[0];
				$nIdx2 = $parts[1];
				$nArr[$nIdx][$nIdx2] = 1;
			}
		}
		$sql="select id from forms order by id";
		$rows=getRows($sql);
		foreach($rows as $row)
		{
			$idx=$row['id'];
			if(!isset($nArr[$idx]))
				$nArr[$idx]['view']=0;	
		}
		return $nArr;
	}
	
	$arr=processIt($_POST);
	printr($arr);exit;
	$name=$_POST['name'];
	$sql="insert into rolls_main(name) values('$name')";
	$id=exeQuery($sql);
	foreach($arr as $formId=>$row)
	{
		$flds="id,form_id";
		$values="$id,$formId" ;
		foreach($row as $idx=>$val)
		{
			$flds.=",".$idx;
			$values.=",".$val;
		}
		$sql ="insert into rolls_detail($flds) values($values)";
		exeQuery($sql);
	}
}
require_once("includes/header.php");
?>
<form action="add_roll.php" method="post" >
	<table border="0" cellpadding="3" cellspacing="0" width="500px" align="center" class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="6">User Rolls </td>
    </tr>	
		<tr>
			<td width="100px">Name</td> <td colspan="5"> <input value=""  type="text" name="name" id="name" class="txtField" /> </td>
		</tr>	
	<tr class="gridheader" ><td>Control</td> <td>View</td> <td>Add</td> <td>Edit </td> <td>Delete</td><td>Print</td> </tr>
	<?php 
		$sql="select * from forms where is_active=1 order by 1 ";
		$rows= getRows($sql);
		foreach($rows as $row) {
	?>
	<tr>
		<td class="gridheader"><?=$row['name']?></td> 
		<td><input type="checkbox" name="<?=$row['id']?>_view" value="1" /> </td>
		<td><input type="checkbox" name="<?=$row['id']?>_canadd" value="1" /> </td>
		<td><input type="checkbox" name="<?=$row['id']?>_edit" value="1" /> </td>
		<td><input type="checkbox" name="<?=$row['id']?>_candelete" value="1" /> </td>
		<td><input type="checkbox" name="<?=$row['id']?>_print" value="1" /> </td>
	</tr>
		<?php } ?>
	<tr><td colspan="6" > <input type="submit" name="submit" value="Save" class="btnSubmit"> </td></tr>
	</table>
</form>




