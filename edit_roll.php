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
	$name=$_POST['name'];
	$rId=$_POST['rId'];
	$sql="update rolls_main set name='$name' where id=$rId";
	exeQuery($sql);
	$sql="delete from rolls_detail where id=$rId";
	exeQuery($sql);
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
$rName="";
if(isset($_GET['rId']))
{
	$rId=$_GET['rId'];
	$sql="select rolls_main.name as rollName, rolls_detail.*, forms.name as formName from forms 
		left outer join rolls_detail on  rolls_detail.form_id = forms.id
		inner join rolls_main on rolls_main.id=rolls_detail.id
		where rolls_detail.id=$rId";
	$rows=getRows($sql);
	$rName=$rows[0]['rollName'];
	//printr($rows);
	
}
require_once("includes/header.php");
?>
<form action="rolls.php" method="post" >
	<input type="hidden" id="rId" name="rId" value="<?=$rId?>" />
	<table border="0" cellpadding="3" cellspacing="0" width="500px" align="center" class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="6">update User Rolls </td>
    </tr>	
		<tr>
			<td width="100px">Name</td> <td colspan="5"> <input value="<?=$rName?>"  type="text" name="name" id="name" class="txtField" /> </td>
		</tr>	
	<tr class="gridheader"><td>Control</td> <td>View</td> <td>Add</td> <td>Edit </td> <td>Delete</td><td>Print</td> </tr>
	<?php 
		foreach($rows as $row) {
	?>
	<tr>
		<td class="gridheader"><?=$row['formName']?></td> 
		<td><input type="checkbox" name="<?=$row['id']?>_view" value="1" <?=($row['view']=='1'?"checked":"")?> /> </td>
		<td><input type="checkbox" name="<?=$row['id']?>_canadd" value="1" <?=($row['canadd']=='1'?"checked":"")?> /> </td>
		<td><input type="checkbox" name="<?=$row['id']?>_edit" value="1"  <?=($row['edit']=='1'?"checked":"")?>/> </td>
		<td><input type="checkbox" name="<?=$row['id']?>_candelete" value="1" <?=($row['candelete']=='1'?"checked":"")?> /> </td>
		<td><input type="checkbox" name="<?=$row['id']?>_print" value="1" <?=($row['print']=='1'?"checked":"")?> /> </td>
	</tr>
		<?php } ?>
	<tr><td colspan="6" > <input type="submit" name="submit" value="Save" class="btnSubmit"> </td></tr>
	</table>
</form>




