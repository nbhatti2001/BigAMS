<?php
require_once("includes/header.php");
if(isset($_GET['action']))
	$action=$_GET['action'];
else
	$action="Dispatch";
if(isset($_POST['submit']))
{
	$reference =$_POST['reference'];
	$action=$_POST['action'];
	$driver =$_POST['driver'];
	$cdate =  date("Y/m/d", strtotime($_POST['cdate']));
	$from = $_SESSION['locationId'];
	$userId=$_SESSION['userId'];
	$flds="reference,driver,cdate,from_,user_id,entry_type";
	$values="'$reference','$driver','$cdate','$from','$userId','$action'";
	$sql ="insert into dispatch_main($flds) values($values)";
	$id=exeQuery($sql);
	//
	for($cnt=1;$cnt<=4;$cnt++)
	{
		$idx="customer$cnt";
		if($_POST[$idx]!='')
		{
			$customer=$_POST[$idx];
			$yellow =$_POST['yellow'.$cnt];
			$green =$_POST['green'.$cnt];
			$blue =$_POST['blue'.$cnt];
			$red =$_POST['red'.$cnt];
			$flds="id,customer, item_id,qty";
			$entryType="Dispatch";
			if($yellow!=0 ) 
			{
				$sql="insert into dispatch_detail($flds) values('$id', '$customer','1','$yellow')";
				exeQuery($sql);
			}
			if($green!=0 ) 
			{
				$sql="insert into dispatch_detail($flds) values('$id','$customer','2','$green')";
				exeQuery($sql);
			}
			if($blue!=0 ) 
			{
				$sql="insert into dispatch_detail($flds) values('$id','$customer','3','$blue')";
				exeQuery($sql);
			}
			if($red!=0 ) 
			{
				$sql="insert into dispatch_detail($flds) values('$id','$customer','4','$red')";
				exeQuery($sql);
			}
		}
	}
}
?>
<form action="dispatch.php" method="post" >
	<input type="hidden" name="action" value="<?=$action?>" />
	<table border="0" cellpadding="5" cellspacing="0" width="500" align="center" class="tblSaveForm">
	<tr class="tableheader">
        <td colspan="4">Add <?=$action?></td>
    </tr>
		<tr>
			<td>Reference #</td> <td> <input type="text" name="reference" id="reference" /> </td>
			<td>Driver</td> <td> <input type="text" name="driver" id="driver" /> </td>
		
		</tr>
		<tr>
			<td>Date</td> <td> <input type="text" name="cdate" id="cdate" /> </td>
			<td>From</td> <td> <?=$_SESSION['location'] ?> </td>
		</tr>
		<tr>
			<td colspan="4">
				<table border="0" cellpadding="3" cellspacing="0" width="500" align="center" class="tblSaveForm">
					<tr align="center"><td >Customer</td> <td> Yellow With B/Handle</td> <td>Green </td> <td> Blue </td> <td> White </td> </td>
					<tr><td><?=getCustomers('','customer1')?></td> 
						<td> <input class="txtField" type="text" name="yellow1" id="yellow1" /> </td>
						<td> <input class="txtField" type="text" name="green1" id="green1" /> </td>
						<td> <input class="txtField" type="text" name="blue1" id="blue1" /> </td>
						<td> <input class="txtField" type="text" name="red1" id="red1" /> </td>
					</tr>

					<tr><td><?=getCustomers('','customer2')?></td> 
					
						<td> <input class="txtField" type="text" name="yellow2" id="yellow2" /> </td>
						<td> <input class="txtField" type="text" name="green2" id="green2" /> </td>
						<td> <input class="txtField" type="text" name="blue2" id="blue2" /> </td>
						<td> <input class="txtField" type="text" name="red2" id="red2" /> </td>
					</tr>

					<tr><td><?=getCustomers('','customer3')?></td> 
					
						<td> <input class="txtField" type="text" name="yellow3" id="yellow3" /> </td>
						<td> <input class="txtField" type="text" name="green3" id="green3" /> </td>
						<td> <input class="txtField" type="text" name="blue3" id="blue3" /> </td>
						<td> <input class="txtField" type="text" name="red3" id="red3" /> </td>
					</tr>

					<tr><td><?=getCustomers('','customer4')?></td> 
					
						<td> <input class="txtField" type="text" name="yellow4" id="yellow4" /> </td>
						<td> <input class="txtField" type="text" name="green4" id="green4" /> </td>
						<td> <input class="txtField" type="text" name="blue4" id="blue4" /> </td>
						<td> <input class="txtField" type="text" name="red4" id="red4" /> </td>
					</tr>

					<tr><td><?=getCustomers('','customer5')?></td> 
					
						<td> <input class="txtField" type="text" name="yellow5" id="yellow5" /> </td>
						<td> <input class="txtField" type="text" name="green5" id="green5" /> </td>
						<td> <input class="txtField" type="text" name="blue5" id="blue5" /> </td>
						<td> <input class="txtField" type="text" name="red5" id="red5" /> </td>
					</tr>

					<tr><td><?=getCustomers('','customer6')?></td> 
					
						<td> <input class="txtField" type="text" name="yellow6" id="yellow6" /> </td>
						<td> <input class="txtField" type="text" name="green6" id="green6" /> </td>
						<td> <input class="txtField" type="text" name="blue6" id="blue6" /> </td>
						<td> <input class="txtField" type="text" name="red6" id="red6" /> </td>
					</tr>

					<tr><td><?=getCustomers('','customer7')?></td> 
					
						<td> <input class="txtField" type="text" name="yellow7" id="yellow7" /> </td>
						<td> <input class="txtField" type="text" name="green7" id="green7" /> </td>
						<td> <input class="txtField" type="text" name="blue7" id="blue7" /> </td>
						<td> <input class="txtField" type="text" name="red7" id="red7" /> </td>
					</tr>					
					<tr>
						<td> &nbsp;</td>
						<td> &nbsp;</td>
						<td> &nbsp;</td>
						<td> &nbsp;</td>
						<td> <input type="submit" name="submit" value="Dispatch" class="btnSubmit"> </td>
						
					</tr>
				</table>
			
			
			</td>
		
		</tr>

</form>