<?php
require_once("includes/phpincludes.php");
if(isset($_POST['submit']))
{

	$roll_id=$_POST['rolls'];
	$users =$_POST['selected_users'];
	$sql="insert into groups_main(roll_id) values($roll_id)";
	$id=exeQuery($sql);
	$rows=explode(",", $users);
	$flds="id,user_id";
	foreach($rows as $idx=>$val)
	{
		if($val!="undefined")
		{
			$values="$id,'$val'" ;
			$sql ="insert into groups_detail($flds) values($values)";
			exeQuery($sql);
		}
	}
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
<form action="add_group.php" method="post" >
	<table border="0" cellpadding="3" cellspacing="0" width="500px" align="center" class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="6">User Rolls </td>
    </tr>	
		<tr>
			<td width="100px">Roll</td> <td colspan="5"> <?=getRolls()?> </td>
		</tr>	


	<tr>
		<td>
			<select size="7" multiple="multiple" name="users" id="users" >
			<?php 
				$sql="select * from users where is_active=1 order by 1 ";
				$rows= getRows($sql);
				foreach($rows as $row) {
			?>
				<option value="<?=$row['id']?>"> <?=$row['name']?> </option>
			
			<?php } ?></select>
		</td>
		<td><input id="addi" type="button" value="add >>" />
		<input id="removei" type="button" value="Remove <<" />
		</td>
		<td><select size="7" multiple="multiple" name="sel_users" id="sel_users" ></select>
			<input type="hidden" name="selected_users" id="selected_users" />
		</td>
	</tr>
		
	<tr><td colspan="6" > <input type="submit" name="submit" value="Save" class="btnSubmit"> </td></tr>
	</table>
</form>




