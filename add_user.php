<?php
require_once("includes/phpincludes.php");
if(count($_POST)>0) 
{
 
    $userName=addslashes($_POST["user_name"]);
    $region=addslashes($_POST["region"]);
	$password=addslashes($_POST["region"]);
    $flds="name,location,password";
    $values="'$userName','$region','$password'";
    $sql="INSERT INTO users ($flds) VALUES ($values)";
    $current_id=exeQuery($sql);
    if(!empty($current_id)) 
    {
        $message = "New User Added Successfully";
    }
}
require_once("includes/header.php");
?>
<form name="frmUser" method="post" action="" onsubmit="return AddUser();">
    <div style="width:500px;">
    <div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
    
        <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Add New User</td>
        </tr>
        <tr>
        <td><label>User Name <span class="required">*</span></label></td>
        <td><input type="text" name="user_name" id="user_name" class="txtField"></td>
        </tr>
        <tr>
        <td><label>Password<span class="required">*</span></label></td>
        <td><input type="password" name="password" id="password" class="txtField"></td>
        </tr>		
        <tr>
        <td><label>Location <span class="required">*</span></label></td>
        <td>
			<?=getRegions();?>
		</td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="submit" name="submit" value="Save" class="btnSubmit"></td>
        </tr>
        </table>
    </div>
</form>
</body></html>