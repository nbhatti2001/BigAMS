<?php 
$conn = mysql_connect("localhost","root","");
mysql_select_db("crate",$conn);
$res=array();
function throwException($query = null)
{
    $msg = "Error Number: ".  mysql_errno()." <br /> Description:  ". mysql_error();
    //throw new Exception($msg);
    echo $msg;
}

function getRows($query_string)
{
    $rs=array();

    $queryId = mysql_query($query_string);
    if (! $queryId) 
    {
        throwException($query_string);
        return $rs;
    }
    else
    {
        while($row=mysql_fetch_assoc($queryId))
        {
            $rs[]=$row;
        }
        return $rs;
    }
    return $rs;
}
function getRow($query_string)
{
    $rs=array();
    $queryId = mysql_query($query_string);
    if (! $queryId) 
    {
        throwException($query_string);
        return $rs;
    }
    else
    {
        while($row=mysql_fetch_assoc($queryId))
        {
            $rs=$row;
        }
        return $rs;
    }
    return $rs;
}
function exeQuery($query_string)
{
    $queryId = mysql_query($query_string);
    if (! $queryId) 
    {
        throwException($query_string);
        return false;
    }
    else
    {
        if(substr($query_string,0,6)=="insert")
        {
            $current_id = mysql_insert_id();
            return $current_id;
        } 
        return true;
    }
    return false;
}
function printr($ar)
{
	echo "<pre>";
	print_r($ar);
	echo "</pre>";
}
function getRegionsssss($region="")
{
	$str= "<select name='region' >
				<option value=''>-- Select Region --</option>
				<option value='1' "  .($region=="Central"?"selected":"")  ." >Central</option>
				<option value='2' "  .($region=="South"?"selected":"") . ">South</option>
				<option value='3' "  .($region=="North"?"selected":""). ">North</option>
				<option value='4' "  .($region=="Internally"?"selected":"") . ">Internally</option>
			</select>";
	return $str;
}
function getRegions($def="")
{
	$sql="select * from regions where is_active=1 order by id";
	$rows=getRows($sql);
	$str= "\n <select id='region' name='region' >
		<option value=''>-- Select Location --</option>";
	for($cnt=0;$cnt<count($rows);$cnt++)
		$str.="\n <option value='" . $rows[$cnt]['id']  . "' "  .($def==$rows[$cnt]['id']?"selected":"")  ." >". $rows[$cnt]['name']  ."</option>";
	$str.="\n </select>";
	return $str;
}
function getCategories($def="")
{
	$sql="select * from categories order by id";
	$rows=getRows($sql);
	$str= "\n <select name='category' >
		<option value=''>-- Select Category --</option>";
	for($cnt=0;$cnt<count($rows);$cnt++)
		$str.="\n <option value='" . $rows[$cnt]['id']  . "' "  .($def==$rows[$cnt]['id']?"selected":"")  ." >". $rows[$cnt]['name']  ."</option>";
	$str.="\n </select>";
	return $str;
}
function getCustomers($def="",$name="")
{
	$sql="select * from customers order by id";
	$rows=getRows($sql);
	if($name=="")
		$name="customer";
	$str= "\n <select name='$name' >
		<option value=''>-- Select Customer --</option>";
	for($cnt=0;$cnt<count($rows);$cnt++)
		$str.="\n <option value='" . $rows[$cnt]['id']  . "' "  .($def==$rows[$cnt]['id']?"selected":"")  ." >". $rows[$cnt]['account_name']  ."</option>";
	$str.="\n </select>";
	return $str;
}
function getItems($def="",$name="")
{
	$sql="select * from items order by id";
	$rows=getRows($sql);
	if($name=="")
		$name="item";
	$str= "\n <select name='$name' >
		<option value=''>-- Select Item --</option>";
	for($cnt=0;$cnt<count($rows);$cnt++)
		$str.="\n <option value='" . $rows[$cnt]['id']  . "' "  .($def==$rows[$cnt]['id']?"selected":"")  ." >". $rows[$cnt]['name']  ."</option>";
	$str.="\n </select>";
	return $str;
}
function getDrivers($def="",$name="",$filter)
{
	$sql="select * from drivers where is_active=1 $filter order by name";
	$rows=getRows($sql);
	if($name=="")
		$name="driver";
	$str= "\n <select name='$name' >
		<option value=''>-- Select Driver --</option>";
	for($cnt=0;$cnt<count($rows);$cnt++)
		$str.="\n <option value='" . $rows[$cnt]['name']  . "' "  .($def==$rows[$cnt]['name']?"selected":"")  ." >". $rows[$cnt]['name']  ."</option>";
	$str.="\n </select>";
	return $str;
}
function getVehicles($def="",$name="",$filter)
{
	$sql="select * from vehicles where is_active=1 $filter order by name";
	$rows=getRows($sql);
	if($name=="")
		$name="veh";
	$str= "\n <select name='$name' >
		<option value=''>-- Select Vehicle --</option>";
	for($cnt=0;$cnt<count($rows);$cnt++)
		$str.="\n <option value='" . $rows[$cnt]['name']  . "' "  .($def==$rows[$cnt]['name']?"selected":"")  ." >". $rows[$cnt]['name']  ."</option>";
	$str.="\n </select>";
	return $str;
}

function getRolls($def="",$name="")
{
	$sql="select * from rolls_main order by id";
	$rows=getRows($sql);
	if($name=="")
		$name="rolls";
	$str= "\n <select name='$name' >
		<option value=''>-- Select Roll --</option>";
	for($cnt=0;$cnt<count($rows);$cnt++)
		$str.="\n <option value='" . $rows[$cnt]['id']  . "' "  .($def==$rows[$cnt]['id']?"selected":"")  ." >". $rows[$cnt]['name']  ."</option>";
	$str.="\n </select>";
	return $str;
}
function getValue($sql,$fld)
{
	$row=getRow($sql);
	if(count($row)>0)
		return $row[$fld];
	else	
		return "";
}
function getDocNumber($docNo,$action)
{
	
	if($action=="Return")
		$type="RET";
	elseif($action=="Transfer")
		$type="TO";
	else
		$type="DOC";
	
	$type=$action;
	if($docNo=="")
		return "$type.000001";
	else
	{
		$parts =explode(".",$docNo);
		$no = intval($parts[1]);
		$no++;
		return "$type." . substr("000000",1,6-strlen($no)) . $no;
	}
}
function getFileName()
{
	return basename($_SERVER['REQUEST_URI'], '?'.$_SERVER['QUERY_STRING']);
}
function setModules($rows)
{
	$modules =array();
	foreach($rows as $row)
	{
		$idx= $row['dname'];
		$modules[$idx]['module'] = $row['name'];
		$modules[$idx]['action'] = $row['action'];
	}
	return $modules;
}
function setUserRights($rights)
{
	$arr = array();
	foreach($rights as $right)
	{
		$idx=$right['name'];
		if(isset($right['view']))
			$arr[$idx]["view"] =$right['view'];
		if(isset($right['canadd']))
			$arr[$idx]["canadd"] =$right['canadd'];
		if(isset($right['edit']))
			$arr[$idx]["edit"] =$right['edit'];
		if(isset($right['candelete']))
			$arr[$idx]["candelete"] =$right['candelete'];
		if(isset($right['print']))
			$arr[$idx]["print"] =$right['print'];
	}
	return $arr;
}
function getCustId($cust)
{
	$parts=explode("/",$cust);
	$sql="select id from customers where account_no='". trim($parts[1])."'";
	$custId= getValue($sql,"id");
	return $custId;
}
function ProcessItemList($rows)
{
	$items=array();
	$cust="";
	$cnt=0;
	foreach($rows as $row)
	{
		if($cust!=$row['customer'])
		{
			$cust=$row['customer'];
			$cnt++;
		}
		$idx = $row['item_id'];
		$items[$cnt][$idx]= $row['qty'];
	}
	return $items;
}
function ProcessItemListTransfer($rows)
{
	$items=array();
	$cust="";
	$cnt=0;
	foreach($rows as $row)
	{
		if($cust!=$row['customer'])
		{
			$cust=$row['customer'];
			$cnt++;
		}
		$idx = $row['item_id'];
		$items[$idx]= $row['qty'];
	}
	return $items;
}
function renderCustWiseStkBal($rows,$eType)
{
	global $res;
	foreach($rows as $row)
	{
		$idx=$row['id'];
		$idx2=$row['id'];
		
		if(!isset($res[$idx][$eType]))
			$res[$idx][$eType]=0;
		$res[$idx][$eType]+=$row['qty'];
	}
}
function getCustWiseStkBal($location,$types)
{
	global $res;
	$sql="select items.id, items.name, sum(qty) as qty  from dispatch_main 
		inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
		inner join items on items.id = dispatch_detail.item_id
		left outer join customers on dispatch_detail.customer = customers.id
		where dispatch_main.entry_type in ('Dispatch','Confirmed') and dispatch_main.from_=$location and items.for_customer in ($types)
		group by dispatch_detail.item_id
		order by  items.id
		";
		//echo "<br/> $sql <br/>";
	$rows=getRows($sql);
	renderCustWiseStkBal($rows,'out');
	$sql="select  items.id, items.name, sum(qty) as qty  from dispatch_main 
		inner join dispatch_detail on dispatch_main.id = dispatch_detail.id
		inner join items on items.id = dispatch_detail.item_id
		left outer join customers on dispatch_detail.customer = customers.id
		where dispatch_main.entry_type in ('Purchase','Return','Confirmed') and dispatch_main.to_=$location and items.for_customer in ($types)
		group by dispatch_detail.item_id
		order by  items.id
		";
	$rows=getRows($sql);
	renderCustWiseStkBal($rows,'in');
	//printr($res);
	$res = getBalance($res);
	//printr($res);
	return $res;
}
function getBalance($rows)
{
	$stock=array();
	foreach($rows as $idx=>$row)
	{
		$stock[$idx] = (isset($row['in'])?$row['in']:0) - (isset($row['out'])?$row['out']:0);
	}
	return $stock;
}
function formatDispItems($rows)
{
	$toReturn =array();
	$cnt=0;
	$dispCustId="";
	foreach($rows as $row)
	{
		if($dispCustId != $row['customer'])
		{
			$dispCustId= $row['customer'];
			$cnt++;
		}
		$dispItemId= $row['item_id'];
		$toReturn[$cnt][$dispItemId]=$row['qty'];
		
	}
	return $toReturn;
}
?>