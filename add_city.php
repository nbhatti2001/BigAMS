<?php
require_once("includes/phpincludes.php");
if(count($_POST)>0) 
{
 
    $cityName=addslashes($_POST["city_name"]);
    $region=addslashes($_POST["region"]);
    $flds="name,region";
    $values="'$cityName','$region'";
    $sql="INSERT INTO Cities ($flds) VALUES ($values)";
    $current_id=exeQuery($sql);
    if(!empty($current_id)) 
    {
        $message = "New City Added Successfully";
    }
}
require_once("includes/header.php");
?>
<form name="frmUser" method="post" action="" onsubmit="return AddCity();">
    <div style="width:500px;">
    <div id="message" class="message"><?php if(isset($message)) { echo $message; } ?></div>
        <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
        <tr class="tableheader">
        <td colspan="2">Add New City</td>
        </tr>
        <tr>
        <td><label>City Name <span class="required">*</span></label></td>
        <td><input type="text" name="city_name" id="city_name" class="txtField"></td>
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
