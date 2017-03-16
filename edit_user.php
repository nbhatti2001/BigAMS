<?php
require_once("includes/phpincludes.php"); 
if(count($_POST)>0) 
{
    $id=addslashes($_POST["id"]);
    $userName=addslashes($_POST["user_name"]);
	$password=addslashes($_POST["cpassword"]);
	if($password!="")
		$changPassword=" ,password='$password'";
    $region=addslashes($_POST["region"]);
	if(isset($_POST["isActive"]))
		$isActive=$_POST["isActive"];
	else
		$isActive=0;
    $sql="UPDATE users set name='$userName', location='$region', is_active='$isActive' $changPassword  WHERE id='$id'";    
    exeQuery($sql);
    $message = "Record Updated Successfully";
}
if(!isset($id))
    $id=$_GET["userId"];
$result = mysql_query("SELECT * FROM users WHERE id='$id'");
$row= mysql_fetch_array($result);
require_once("includes/header.php");
?>
<form name="frmUser" method="post" action="" onsubmit="return AddStudent();">
    <div style="width:500px;">
    <div class="message"><?php if(isset($message)) { echo $message; } ?></div>
        <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Edit User</td>
        </tr>
        <tr>
        <td><label>User Name <span class="required">*</span></label></td>
        <td>
            <input type="hidden" name="id" class="txtField" value="<?=$row['id']?>">
            <input type="text" name="user_name"  id="user_name" class="txtField" value="<?=$row['name']?>"></td>
        </tr>
        <tr>
        <td><label>Location <span class="required">*</span></label></td>
        <td>
			<?=getRegions($row['location'])?>
		</td>
        </tr>
		<tr>
        <td><label>Password <span class="required">*</span></label></td>
        <td><input type="password" name="cpassword"  id="cpassword" class="txtField" value="" /></td>
        </tr>
		<tr>
        <td><label>Is Active <span class="required"></span></label></td>
        <td><input value="1" type="checkbox" name="isActive"  id="isActive" class="txtField" <?=($row['is_active']==1?"checked":"")?> /></td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="submit" name="submit" value="Update" class="btnSubmit"></td>
        </tr>
        </table>
    </div>
</form>
