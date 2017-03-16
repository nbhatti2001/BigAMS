<?php
require_once("includes/phpincludes.php");
if(count($_POST)>0) 
{
    $opass=addslashes($_POST["opass"]);
	$npass=addslashes($_POST["npass"]);
	$cpass=addslashes($_POST["cpass"]);
	//
	$sql="select * from users where password='$opass' and id=" . $_SESSION['userId'];
	echo $sql;
	$row=getRow($sql);
	if(count($row)>0)
	{
		$values="password='$npass'";
		$sql="update users set $values where id= ". $_SESSION['userId'];
		$current_id=exeQuery($sql);
		if(!empty($current_id)) 
		{
			$message = "Your password has been changed successfully";
		}
	}
	else
	{
		$message="Old password not matched";
	}
}
require_once("includes/header.php");
?>
<form name="frmUser" method="post" action="" onsubmit="return ChangePass();">
    <div style="width:500px;">
    <div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
        <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Change Password</td>
        </tr>
        <tr>
			<td><label>Old Password<span class="required">*</span></label></td>
			<td><input type="password" name="opass" id="opass" class="txtField"></td>
        </tr>
        <tr>
			<td><label>New Password<span class="required">*</span></label></td>
			<td><input type="password" name="npass" id="npass" class="txtField"></td>
        </tr>
        <tr>
			<td><label>Confirm Password<span class="required">*</span></label></td>
			<td><input type="password" name="cpass" id="cpass" class="txtField"></td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="submit" name="submit" value="Change Password" class="btnSubmit"></td>
        </tr>
        </table>
    </div>
</form>
