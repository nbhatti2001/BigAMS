<?php
require_once("includes/phpincludes.php");    
$userFilter="";
if(isset($_GET['action']))
	$action=$_GET['action'];
else
	$action="freezer";
$lnkConfirm=false;




if($action=="freezer")
{
	$lblLocation="To";
	$fldLocation="to_";
	if(isset($_GET['to']))
	{
		$tome=$_GET['to'];
		if($tome=="me")
		{
			$lnkConfirm=true;
			$locationId=$_SESSION['locationId'];
			$userFilter="and transfer_main.to_='$locationId'";
		}
	}
	else
		$tome="";	
}
else	
{
	$lblLocation="From";
	$fldLocation="from_";
}
if($action !="Purchase")
{
	/*$sql="SELECT dispatch_main.*,regions.name as fromLocation, regionto.name as toLocation FROM dispatch_main
		inner join regions on dispatch_main.from_ = regions.id 
		inner join regions regionto on dispatch_main.to_ = regionto.id 
		where entry_type='$action' $userFilter
		";*/
		
		$sql ="SELECT * from transfer_main";
}
else
{
	$sql="SELECT transfer_main.* ,'' fromLocation, regionto.name as toLocation FROM dispatch_main
		inner join regions regionto on dispatch_main.to_ = regionto.id 
		where entry_type='$action' 
		";
}
$rows =getRows($sql);
require_once("includes/header.php"); 
?>

<form name="frmUser" method="post" action="">
    <div style="width:750px;">
    <div class="message"><?php if(isset($message)) { echo $message; } ?></div>
    <table border="0" cellpadding="10" cellspacing="1" width="750px" class="tblListForm">
    <tr class="listheader">
    <td>Reference #</td>
	<td>Posting Date</td>
	
	<td>Customer Code</td>
	<td>Delivery Address  </td>
	<td>Fdn</td>
	<td>Tso</td>
		<td>Rsm</td>
    <td width="60px">Actions</td>
    </tr>
    <?php
    $i=0;
    foreach($rows as $row) {
    if($i%2==0)
    $classname="evenRow";
    else
    $classname="oddRow";
    ?>
    <tr class="<?php if(isset($classname)) echo $classname;?>">
	<td><?php echo $row["reference"]; ?></td>
	<td><?php echo date("d-m-Y",strtotime($row["date"])); ?></td>
   
	
	<td><?php echo $row["ccode"]; ?></td>
	
	<td><?php echo $row["daddress"]; ?></td>
	<td><?php echo $row["fdn"]; ?></td>
	<td><?php echo $row["tso"]; ?></td>
	<td><?php echo $row["rsm"]; ?></td>
        

    <td>
	<?php if($action =='Dispatch' || $action == 'Return') { ?>
		<a href="edit_dispatch.php?action=<?=$action?>&dId=<?php echo $row["id"]; ?>" class="link"><img  class='link-icon' alt='Edit' title='Edit' src='images/edit.png' width='15px' height='15px' hspace='10' /></a>  
	<?php }elseif($action=="Purchase"){ ?>
		<a href="edit_purchase.php?action=<?=$action?>&tId=<?php echo $row["id"]; ?>" class="link"><img class='link-icon'  alt='Edit' title='Edit' src='images/edit.png' width='15px' height='15px' hspace='10' /></a>  
	<?php }else{
		if($_SESSION['userId'] == $row['user_id'] ) {
	?>
		<a href="edit_transfer.php?action=<?=$action?>&tId=<?php echo $row["id"]; ?>" class="link"><img class='link-icon'  alt='Edit' title='Edit' src='images/edit.png' width='15px' height='15px' hspace='10' /></a>  
	<?php  } }?>
	<?php if($_SESSION['userId'] == $row['user_id'] &&  false) { ?>
    <a href="delete_item.php?itemId=<?php echo $row["id"]; ?>"  class="link"><img  class='link-icon' alt='Delete' title='Delete' src='images/delete.png' width='15px' height='15px'hspace='10' /></a>
	<?php } if($action =='Dispatch' ){ ?>
			<a href="freezer.php?action=Return&freezerId=<?php echo $row["id"]; ?>" class="link"><img class='link-icon' title='Create Return' src='images/returns.png' width='15px' height='15px' hspace='10' /></a>  		
	<?php } ?>
	
	</td>
    
	</tr>
    <?php
    $i++;
    }
    ?>
    </table>
</form>
</div>