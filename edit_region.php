<?php
require_once("includes/phpincludes.php");
if(count($_POST)>0) 
{
	$id=$_POST['id'];
    $name=addslashes($_POST["name"]);
    $category=addslashes($_POST["category"]);
    $values="name='$name',category='$category'";
    $sql="update regions set $values where id=$id";
    exeQuery($sql);
    $message = "Location Updated Successfully";
}
if(isset($_GET['rId']))
{
	$rId=$_GET['rId'];
	$sql="select * from regions where id=$rId";
	$row=getRow($sql);
}
require_once("includes/header.php");
?>
<form name="frmUser" method="post" action="" onsubmit="return AddRegion();">
    <div style="width:500px;">
    <div id="message"  class="message"><?php if(isset($message)) { echo $message; } ?></div>
    
        <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Update Region</td>
        </tr>
        <tr>
			<td><label>Name <span class="required">*</span></label></td>
			<td>
			<input type="hidden" value="<?=$row['id']?>" name="id" >
			<input type="text" value="<?=$row['name']?>" name="name" id="name" class="txtField"></td>
        </tr>
        <tr>
			<td><label>Category<span class="required">*</span></label></td>
			<td><select  name="category" id="category">
					<option value=""> -- Select Category -- </option>
					<option value="1" <?=($row['category']=="Internal"?"Selected":"")?> >Internal</option>
					<option value="2" <?=($row['category']=="External"?"Selected":"")?>>External</option>
				</select>
			</td>
        </tr>		
        <tr>
        <td colspan="2">
        <input type="submit" name="submit" value="Update" class="btnSubmit"></td>
        </tr>
        </table>
    </div>
</form>
</body></html>