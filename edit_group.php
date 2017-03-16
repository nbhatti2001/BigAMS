<?php
require_once("includes/phpincludes.php");
if(isset($_POST['submit']))
{

	$gId=$_POST['gId'];
	$roll_id =$_POST['rolls'];
	$users =$_POST['selected_users'];
	$sql="update groups_main set roll_id = $roll_id  where id=$gId";
	exeQuery($sql);
	$sql="delete from groups_detail where id=$gId";
	exeQuery($sql);
	$rows=explode(",", $users);
	$flds="id,user_id";
	foreach($rows as $idx=>$val)
	{
		if($val!="undefined")
		{
			$values="$gId,'$val'" ;
			$sql ="insert into groups_detail($flds) values($values)";
			exeQuery($sql);
		}
	}
}
if(isset($_GET['gId']) || isset($gId))
{
	if(!isset($gId))
		$gId=$_GET['gId'];
	$sql="select groups_main.roll_id as rId,users.id,users.name as uName from groups_main 
	inner join groups_detail on groups_main.id = groups_detail.id
	inner join users on groups_detail.user_id = users.id
	inner join rolls_main on groups_main.roll_id = rolls_main.id
	where groups_main.id=$gId";
	$rows=getRows($sql);
	$rId=$rows[0]['rId'];
	
}
require_once("includes/header.php");
?>
<script>
$(document).ready(function () {   

$('#addi').click(function (e) {
                var selectedOpts = $('#users option:selected');
                if (selectedOpts.length == 0) {
                    alert("Nothing to move.");
                    e.preventDefault();
                }

                $('#sel_users').append($(selectedOpts).clone());
                $(selectedOpts).remove();
                defaultSelect();
                e.preventDefault();
            });

            $('#removei').click(function (e) {
                var selectedOpts = $('#sel_users option:selected');
                if (selectedOpts.length == 0) {
                    alert("Nothing to move.");
                    e.preventDefault();
                }

                $('#users').append($(selectedOpts).clone());
                $(selectedOpts).remove();
                defaultSelect();
                e.preventDefault();
            });
    defaultSelect = function () {
				var txt; 
                $("#sel_users option").each(function () {
                    $(this).prop("selected", true);
					txt+=","+$(this).val(); 
                });
				$("#selected_users").val(txt);
            }        
  });


</script>
<form action="edit_group.php" method="post" >
	<input type="hidden" name="gId" id="gId" value="<?=$gId?>" />
	<table border="0" cellpadding="3" cellspacing="0" width="500px" align="center" class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="6">User Rolls </td>
    </tr>	
		<tr>
			<td width="100px">Roll</td> <td colspan="5"> <?=getRolls($rId)?> </td>
		</tr>	
	<tr>
		<td>
			<select size="7" multiple="multiple" name="users" id="users" >
			<?php 
				$sql="select * from users where is_active=1 and id not in (select user_id from groups_detail where id=$gId) order by 1 ";
				$users= getRows($sql);
				foreach($users as $row) {
			?>
				<option value="<?=$row['id']?>"> <?=$row['name']?> </option>
			
			<?php } ?></select>
		</td>
		<td><input id="addi" type="button" value="add >>" />
		<input id="removei" type="button" value="Remove <<" />
		</td>
		<td>
			<select size="7" multiple="multiple" name="sel_users" id="sel_users" >
			<?php 
				foreach($rows as $row) {
			?>
				<option value="<?=$row['id']?>"> <?=$row['uName']?> </option>
			
			<?php } ?></select>		
			<input type="hidden" name="selected_users" id="selected_users" />
		</td>
	</tr>
		
	<tr><td colspan="6" > <input type="submit" name="submit" value="Save" class="btnSubmit"> </td></tr>
	</table>
</form>




