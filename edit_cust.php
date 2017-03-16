<?php
require_once("includes/phpincludes.php");
if(count($_POST)>0) 
{
	$id=$_POST['id'];
    $accountNo=addslashes($_POST["accountno"]);
	$accountName=addslashes($_POST["account_name"]);
	$city=addslashes($_POST["city_name"]);
	$region=addslashes($_POST["region"]);
	$route=addslashes($_POST["route"]);
	$category=addslashes($_POST["category"]);
	$address=addslashes($_POST["address"]);
	$phone=addslashes($_POST["phone"]);
	//
    $values="account_no='$accountNo', account_name='$accountName',city='$city',region='$region',route='$route',
		category='$category',address='$address',phone='$phone'";
    //
    $sql="Update customers set $values where id=$id";
    $current_id=exeQuery($sql);
    if(!empty($current_id)) 
    {
        $message = "Customer Updated Successfully";
    }
}
if(isset($_GET['custId']))
	$id=$_GET['custId'];
	$sql="SELECT * FROM customers 
	
	where customers.id=$id";
$row=getRows($sql);
//
$id=$row[0]['id'];
$accNo=$row[0]['account_no'];
$accName=$row[0]['account_name'];
$city=$row[0]['city'];
$region=$row[0]['region'];
$route=$row[0]['route'];
$category=$row[0]['category'];
$address=$row[0]['address'];
$phone=$row[0]['phone'];
if(count($row)>0)
{
require_once("includes/header.php");	
?>
<form name="frmUser" method="post" action="" onsubmit="return AddCust();">
    <div style="width:500px;">
    <div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
	
        <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Update Customer</td>
        </tr>
        <tr>
        <td><label>Account # <span class="required">*</span></label></td>
        <td>
			<input type="hidden" name="id" value="<?=$id?>" />
			<input type="text" value="<?=$accNo?>" name="accountno" id="accountno" class="txtField" /></td>
        </tr>

        <tr>
        <td><label>Account Name <span class="required">*</span></label></td>
        <td><input type="text" value="<?=$accName?>" name="account_name" id="account_name" class="txtField"></td>
        </tr>
        <tr>
        <td><label>City Name <span class="required">*</span></label></td>
        <td><input type="text" value="<?=$city?>" name="city_name" id="city_name" class="txtField"></td>
        </tr>
        <tr>
        <td><label>Location <span class="required">*</span></label></td>
        <td><?=getRegions($region)?> </td>
        </tr>
        <tr>
        <td><label>Route # <span class="required">*</span></label></td>
        <td><input type="text" value="<?=$route?>" name="route" id="route" class="txtField"></td>
        </tr>

		 <tr>
        <td><label>Category <span class="required">*</span></label></td>
        <td><?=getCategories($category)?> </td>
        </tr>
        <tr>
        <td><label>Address <span class="required">*</span></label></td>
        <td><input type="text" value="<?=$address?>" name="address" id="address" class="txtField"></td>
        </tr>
        <tr>
        <td><label>Phone No. <span class="required">*</span></label></td>
        <td><input type="text" value="<?=$phone?>" name="phone" id="phone" class="txtField"></td>
        </tr>
        <tr>
			<td colspan="2">
				<input type="submit" name="submit" value="Update" class="btnSubmit">
			</td>
        </tr>
        </table>
    </div>
</form>
<?php 
}
else
{ 
	$message="Wrong customer id given";
?>
    <div style="width:500px;">
    <div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
	</div>
<?php } ?>