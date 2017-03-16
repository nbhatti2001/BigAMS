<?php
require_once("includes/phpincludes.php");    
$sql="SELECT * FROM rolls_main";
$rows =getRows($sql);
require_once("includes/header.php");
?>

<form name="frmUser" method="post" action="">
    <div style="width:625px;">
    <div class="message"><?php if(isset($message)) { echo $message; } ?></div>
    <table border="0" cellpadding="10" cellspacing="1" width="625px" class="tblListForm">
    <tr class="listheader">
    <td>Name</td>
	<td>Is Active</td>
    <td width="80px">Actions</td>
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
   
    <td><?php echo $row["name"]; ?></td>
    <td><?php echo ($row["is_active"]==1?"Yes":"No"); ?></td>

    <td><a href="edit_roll.php?rId=<?php echo $row["id"]; ?>" class="link"><img alt='Edit' title='Edit' src='images/edit.png' width='15px' height='15px' hspace='10' /></a>  
    <a href="delete_roll.php?rId=<?php echo $row["id"]; ?>"  class="link"><img alt='Delete' title='Delete' src='images/delete.png' width='15px' height='15px'hspace='10' /></a></td>
    </tr>
    <?php
    $i++;
    }
    ?>
    </table>
</form>
</div>
