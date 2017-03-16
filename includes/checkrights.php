<?php 
	if(!$_SESSION['isSuperUser'])
	{
		$fileName	=	getFileName();
		//printr($_SESSION['rights']);
		//echo $fileName;
		if(isset($_SESSION['modules'][$fileName]['module']))
		{
			$module=$_SESSION['modules'][$fileName]['module'];
			$action=$_SESSION['modules'][$fileName]['action'];
		}
		if($fileName!="home.php" && $fileName !="logout.php" && $fileName!="locationbasestock.php" && $fileName!="custwisebalance.php" && $fileName!="changep.php"  && $fileName!="customers.php")
		{
			//echo "$module][$action";exit;
			if((isset($_SESSION['rights'][$module][$action])  && $_SESSION['rights'][$module][$action] == 1))
				echo "";
			else
				header("Location:unauthorise.php",true);
		}
	}
?>