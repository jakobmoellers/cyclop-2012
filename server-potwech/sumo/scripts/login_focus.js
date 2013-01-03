<!--

if(document.SumoAuth!=null)
{
	document.SumoAuth.sumo_user.value="";
	document.SumoAuth.sumo_pwd.value="";
	document.SumoAuth.sumo_user.focus();
}

function doFocus(obj) 
{
   if( obj.style.cssText == '' && (obj.type!='radio' && obj.type!='checkbox') ) 
   		obj.style.cssText = 'border: 1px solid #BB0000';
}
    
function doBlur(obj) 
{
	obj.style.cssText = '';
}
	
//-->