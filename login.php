<?php
session_start();
if(count($_POST)>0) 
{
    require_once("includes/database.php") ; 
    $uName=mysql_real_escape_string($_POST["user_name"]);
    $password=mysql_real_escape_string($_POST["password"]);
    $sql="select users.*,regions.name as location,regions.id as locId from users 
		inner join regions on users.location =regions.id
		where users.name='$uName' and password='$password'";
    $result=mysql_query($sql);
    $rs= mysql_fetch_assoc($result);
    if(isset($rs['name']) ) 
    {
        $_SESSION['user']= $rs['display_name'];
		$_SESSION['userId']=$rs['id']; 
		$_SESSION['isSuperUser']=$rs['is_super'];
		$_SESSION['location']=$rs['location'];
		$_SESSION['locationId']=$rs['locId'];
		$_SESSION['item_group']=$rs['item_group'];
		if($rs['locId']==1)
			$_SESSION['isAdmin']=1;
		else
			$_SESSION['isAdmin']=0;
		$sql="select rolls_detail.*,forms.name from groups_main 
			inner join groups_detail on groups_main.id = groups_detail.id
			inner join rolls_main  on rolls_main.id = groups_main.roll_id
			inner join rolls_detail on rolls_main.id = rolls_detail.id
			inner join forms on rolls_detail.form_id = forms.id
			where groups_detail.user_id =".$_SESSION['userId'];
		$rows= getRows($sql);
		$_SESSION['rights'] = setUserRights($rows);
		//printr($_SESSION['rights']);
		$sql="SELECT forms.name, forms_detail.name AS dname, forms_detail.action
			FROM forms
			INNER JOIN forms_detail ON forms.id = forms_detail.idd";
		$rows=getRows($sql);
		$_SESSION['modules']  = setModules($rows);
		//printr($_SESSION['modules']);
        header("Location:home.php",true);
    }
    else
    {
        $message="Wrong user name or password";
    }
}
require_once("includes/loginheader.php");
?><div style="" class="centered">
<form name="frmUser" method="post"  action="" onsubmit="return login();">
    
    
    
        <table border="0" cellpadding="10" cellspacing="0" width="400px"   class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Enter your user name and password</td>
        </tr>
        <tr>
        <td><label>User Name <span class="required">*</span></label></td>
        <td><input type="text" name="user_name" id="user_name" class="txtField"></td>
        </tr>
        <tr>
        <td><label>Password <span class="required">*</span></label></td>
        <td><input type="password" name="password" id="password" class="txtField"></td>
        </tr>

        <td colspan="2" align="right">
		<div class="message"><?php if(isset($message)) { echo $message; } ?></div>
        <input type="submit" name="submit" value="Login" class="btnSubmit"></td>
        </tr>
        </table>
   
</form> </div>
</body></html>