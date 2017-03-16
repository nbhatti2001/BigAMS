<?php
require_once("includes/phpincludes.php");
if(count($_POST)>0) 
{
 
    $itemName=addslashes($_POST["item_name"]);
    $flds="name";
    $values="'$itemName'";
    $sql="INSERT INTO items ($flds) VALUES ($values)";
    $current_id=exeQuery($sql);
    if(!empty($current_id)) 
    {
        $message = "New Item Added Successfully";
    }
}
require_once("includes/header.php");
?>
<form name="frmUser" method="post" action="" onsubmit="return AddItem();">
    <div style="width:500px;">
    <div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
        <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Add New Item</td>
        </tr>
        <tr>
        <td><label>Item Name <span class="required">*</span></label></td>
        <td><input type="text" name="item_name" id="item_name" class="txtField"></td>
        </tr>
        <tr>
        <td><label>Color Code <span class="required">*</span></label></td>
        <td><input type="text" name="color" id="color" class="txtField"></td>
        </tr>
		
        <tr>
        <td colspan="2">
        <input type="submit" name="submit" value="Save" class="btnSubmit"></td>
        </tr>
        </table>
    </div>
</form>
