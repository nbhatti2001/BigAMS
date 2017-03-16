<?php
require_once("includes/phpincludes.php");
if(count($_POST)>0) 
{
 
    $itemName=addslashes($_POST["item_name"]);
	$id=addslashes($_POST["id"]);
    $values="name='$itemName'";
    $sql="update items set $values where id=$id";
	exeQuery($sql);
    $message = "Item Updated Successfully";

}
$itemName="";
$id="";
if(isset($_GET['itemId']))
{
	$id=$_GET['itemId'];
	$sql="select * from items where id=$id";
	$rs=getRows($sql);
	$itemName=$rs[0]['name'];
}
require_once("includes/header.php");
?>
<form name="frmUser" method="post" action="" onsubmit="return AddItem();">
    <div style="width:500px;">
    <div class="message"><?php if(isset($message)) { echo $message; } ?></div>
        <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Add New Item</td>
        </tr>
        <tr>
        <td><label>Item Name <span class="required">*</span></label></td>
        <td>
			<input type="hidden" value="<?=$id?>" name="id" id="id" />
			<input type="text" value="<?=$itemName?>" name="item_name" id="item_name" class="txtField" /></td>
        </tr>
        <tr>
        <td colspan="2">
        <input type="submit" name="submit" value="Update" class="btnSubmit"></td>
        </tr>
        </table>
    </div>
</form>
