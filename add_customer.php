<?php
require_once("includes/phpincludes.php");
if(count($_POST)>0) 
{
 
    $accountNo=addslashes($_POST["accountno"]);
	$accountName=addslashes($_POST["account_name"]);
	
	$city=addslashes($_POST["city_name"]);
	$region=addslashes($_POST["region"]);
	$route=addslashes($_POST["route"]);
	$category=addslashes($_POST["category"]);
	$address=addslashes($_POST["address"]);
	$phone=addslashes($_POST["phone"]);
	
	
    $flds="account_no, account_name,city,region,route,category,address,phone";
    $values="'$accountNo','$accountName','$city','$region','$route','$category','$address','$phone'";
    $sql="INSERT INTO customers ($flds) VALUES ($values)";
    $current_id=exeQuery($sql);
    if(!empty($current_id)) 
    {
        $message = "New Customer Added Successfully";
    }
}
require_once("includes/header.php");
?>
<form name="frmUser" method="post" action="" onsubmit="return AddCust();">
    <div style="width:500px;">
    <div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
	
        <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Add New Customer</td>
        </tr>
        <tr>
        <td><label>Account # <span class="required">*</span></label></td>
        <td><input type="text" name="accountno" id="accountno" class="txtField"></td>
        </tr>

        <tr>
        <td><label>Account Name <span class="required">*</span></label></td>
        <td><input type="text" name="account_name" id="account_name" class="txtField"></td>
        </tr>
        <tr>
        <td><label>City Name <span class="required">*</span></label></td>
        <td><input type="text" name="city_name" id="city_name" class="txtField"></td>
        </tr>
        <tr>
        <td><label>Location <span class="required">*</span></label></td>
        <td><?=getRegions()?> </td>
        </tr>
        <tr>
        <td><label>Route # <span class="required">*</span></label></td>
        <td><input type="text" name="route" id="route" class="txtField"></td>
        </tr>

		 <tr>
        <td><label>Category <span class="required">*</span></label></td>
        <td><?=getCategories()?> </td>
        </tr>
        <tr>
        <td><label>Address <span class="required">*</span></label></td>
        <td><input type="text" name="address" id="address" class="txtField"></td>
        </tr>
        <tr>
        <td><label>Phone No. <span class="required">*</span></label></td>
        <td><input type="text" name="phone" id="phone" class="txtField"></td>
        </tr>


        <tr>
        <td colspan="2">
        <input type="submit" name="submit" value="Save" class="btnSubmit"></td>
        </tr>
        </table>
    </div>
</form>
