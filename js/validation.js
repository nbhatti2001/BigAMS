var counter=0;
function ChangePass()
{
	$('input').removeClass('errField');
    if($("#opass").val()=="")
    {
        $("#opass").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    }
	
    if($("#npass").val()=="")
    {
        $("#npass").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    }
    if($("#cpass").val()=="")
    {
        $("#cpass").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    }
    if($("#npass").val()!=$("#cpass").val())
    {
        $("#npass").addClass("errField");
		$("#message").html("New password is not matched");
		$("#message").show().fadeOut(5000);
        return false;
    }
	
	return true;
}

function AddTransfer()
{
	isExist=false;
	$('input').removeClass('errField');
	$('select').removeClass('errField');
    if($("#cdate").val()=="")
    {
		
        $("#cdate").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
        return false;
    }
    if($("#reference").val()=="")
    {
        $("#reference").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
        return false;
    }	
    if($("#region").val()=="")
    {
        $("#region").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
        return false;
    }
	for(cnt=1;cnt<=itemCount;cnt++)
	{
		if(!$("#items"+ cnt).val()=="")
		{
			isExist=true;
			break;
		}
	}
	//
	if(isExist==false)
	{
		$("#items1").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
		return false;
	}	
	return true;
}
function chkStockB4Save()
{
	for(cnt=1;cnt<itemCount;cnt++)
	{
		stat=checkStock("items_"+cnt);
		if(stat==false)
			return false;
	}
	return true;
}

/*
function AddFreezer()
{
	isExist=false;
	//chkStockB4Save();
	$('input').removeClass('errField');
    if($("#region").val()=="")
    {
        $("#region").addClass("errField");
		$("#message").html("Complete required fields ");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
        return false;
    } 
    if($("#ftype").val()=="")
    {
        $("#ftype").addClass("errField");
		$("#message").html("Complete required fields ");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
        return false;
    } 
    if($("#serialno").val()=="")
    {
        $("#serialno").addClass("errField");
		$("#message").html("Complete required fields ");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
        return false;
    } 
		
	return true;
}
*/

function AddDispatch()
{
	isExist=false;
	//chkStockB4Save();
	$('input').removeClass('errField');
    if($("#reference").val()=="")
    {
        $("#reference").addClass("errField");
		$("#message").html("Complete required fields 1");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
        return false;
    } 
    if($("#cdate").val()=="")
    {
        $("#cdate").addClass("errField");
		$("#message").html("Complete required fields 2");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
        return false;
    } 
    if($("#cust1").val()=="")
    {
        $("#cust1").addClass("errField");
		$("#message").html("Complete required fields 3");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
        return false;
    } 
	for(cnt=1;cnt<=itemCount;cnt++)
	{
		idx=cnt-1;
		
		if(!$("#items_"+ itemCodes[idx] +"_1").val()=="")
		{
			isExist=true;
			break;
		}
	}
	//
	if(isExist==false)
	{
		$("#items_1_1").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
		return false;
	}	
    /*if($("#stock_issue").val()=="1")
    {
        $("#stock_issue").addClass("errField");
		$("#message").html("There is some stock availability problem");
		$("#message").show().fadeOut(5000);
		location.hash = "#top" ;
        return false;
    } */	
	return true;
}
function checkStock(obj)
{
	var stkToIssue=0;
	objName = obj;
	parts=objName.split("_");
	iCode=parts[1];
	if(typeof stkBal[iCode] === 'undefined')
	{
		alert("Stock not exist, you can not dispatch it");
		return 0;
	}
	availStk=stkBal[iCode];
	for(cnt=1;cnt<20;cnt++)
	{
		if($("#items_"+iCode+"_"+cnt).val()!="")
		stkToIssue+=parseInt($("#items_"+iCode+"_"+cnt).val());
	}
	if(stkToIssue > availStk)
	{
		$("#stock_issue").val(1);
		alert("Not sufficient stock to issue");
		return false;
	}
	else
	{
		$("#stock_issue").val(0);
		return true;
	}
}
function parseIntt(s)
{
	return parseInt(s)||0;
	
}
function dispatchTotal(ctrl,pos,itemCodes)
{
	$("#gtotal"+pos).val(0);
	part=ctrl.id.split("_");
	for(cnt=1;cnt<=itemCount;cnt++)
	{
		idx=cnt-1;
		//alert("#items_"+itemCodes[idx]+"_"+pos);
		if($("#items_"+itemCodes[idx]+"_"+pos).length)
		{
			if(parseIntt($("#items_"+itemCodes[idx]+"_"+pos).val())!= 0)
			{
				total=parseIntt($("#gtotal"+pos).val()) + parseIntt($("#items_"+itemCodes[idx]+"_"+pos).val());
				$("#gtotal"+pos).val(total);
			}
		}
	}
}
function AddCity()
{
	$('input').removeClass('errField');
    if($("#name").val()=="")
    {
        $("#name").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 
    if($("#region").val()=="")
    {
        $("#region").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 
	
	return true;
}
function AddUser()
{
	$('input').removeClass('errField');
    if($("#user_name").val()=="")
    {
        $("#user_name").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 
    if($("#password").val()=="")
    {
        $("#password").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 
    if($("#region").val()=="")
    {
        $("#region").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 
	
	return true;
}
function AddRegion()
{
	$('input').removeClass('errField');
    if($("#name").val()=="")
    {
        $("#name").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 
     if($("#category").val()=="")
    {
        $("#category").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 
	
	return true;
}
function AddCust()
{
	$('input').removeClass('errField');
    if($("#accountno").val()=="")
    {
        $("#accountno").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 
    if($("#account_name").val()=="")
    {
        $("#account_name").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 
	

    if($("#city_name").val()=="")
    {
        $("#city_name").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 

	
	
    return true;
}


function AddItem()
{
	$('input').removeClass('errField');
    if($("#item_name").val()=="")
    {
        $("#item_name").addClass("errField");
		$("#message").html("Complete required fields");
		$("#message").show().fadeOut(5000);
        return false;
    } 
    return true;
}
function login()
{

    if($("#user_name").val()=="")
    {
        alert("User name is required");
        return false;
    } 
    if($("#password").val()=="")
    {
        alert("Password is required");
        return false;
    } 

                         
    return true;
    
}
function addRow()
{
	counter++;
		var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
	newTextBoxDiv.after().html('<input class="txtField" type="text" name="customer' +counter+'" id="customer' +counter+'" /> <input class="txtField" type="text" name="yellow' +counter+'" id="yellow' +counter+'" />  <input class="txtField" type="text" name="green' +counter+'" id="green' +counter+'" /><input class="txtField" type="text" name="blue' +counter+'" id="blue' +counter+'" /><input class="txtField" type="text" name="red' +counter+'" id="red' +counter+'" />');
            
	newTextBoxDiv.appendTo("#TextBoxesGroup");

}

function purchaseTotal()
{
	var gTotal=0;
	$("#gtotal").val(0);
	$(".qty").each(function() {
		 if($(this).hasClass('qty') && $(this).val()!=''){
			gTotal+=parseInt( $(this).val().replace(/\,/g,""));
		 }
	 });
	 $("#gtotal").val(gTotal);
}



