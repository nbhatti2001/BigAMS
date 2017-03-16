<?php
require_once("includes/phpincludes.php");
if(count($_POST)>0) 
{
 
    $name=addslashes($_POST["name"]);
    $category=addslashes($_POST["category"]);
    $flds="name,category";
    $values="'name','$category'";
    $sql="INSERT INTO regions ($flds) VALUES ($values)";
    $current_id=exeQuery($sql);
    if(!empty($current_id)) 
    {
        $message = "New Location Added Successfully";
    }
}
require_once("includes/header.php");
?>
<form name="frmUser" method="post" action="" onsubmit="return AddRegion();">
    <div style="width:500px;">
    <div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
    
        <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Add New Location</td>
        </tr>
        <tr>
			<td><label>Name <span class="required">*</span></label></td>
			<td><input type="text" name="name" id="name" class="txtField"></td>
        </tr>
        <tr>
			<td><label>Category<span class="required">*</span></label></td>
			<td><select  name="category" id="category">
					<option value=""> -- Select Category -- </option>
					<option value="1">Internal</option>
					<option value="2">External</option>
				</select>
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