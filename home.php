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

<div style="font-size:16px;background:url(images/DSC_0465.JPG);width:720px;height:420px;padding-left:280px;padding-top:35px;margin-top:-30px;font-weight:900;color:darkred; ">
Welcome <?=strtoupper($_SESSION['user'])?> to Crate Management system, <br />
Your location is  <?=strtoupper($_SESSION['location'])?>
</div>
<?php require_once("includes/header.php"); ?>