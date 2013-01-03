<!--

// Sleep for js
function sumo_sleep(delay)
{
     var start = new Date().getTime();
     while (new Date().getTime() < start + delay);
}


/**
* Generates a random number with the specified length
* 
* @param num_length
* @return
*/
function sumo_get_rand_number(num_length) 
{
	var chars = "0123456789";
	
	if(num_length < 0 || num_length == null) num_length = 8;
	
	var rand_number = '';
	
	for (var i=0; i<num_length; i++) 
	{
		var rnum = Math.floor(Math.random() * chars.length);
		rand_number += chars.substring(rnum,rnum+1);
	}
	
	return rand_number;
}

/**
 * Generates a random string with the specified length
 * 
 * @param str_length
 * @return
 */
function sumo_get_rand_string(str_length) 
{
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	
	if(str_length < 0 || str_length == null) str_length = 8;
	
	var rand_string = '';
	
	for (var i=0; i<str_length; i++) 
	{
		var rnum = Math.floor(Math.random() * chars.length);
		rand_string += chars.substring(rnum,rnum+1);
	}
	
	return rand_string;
}


/**
*  Cookies
*/
function sumo_get_cookieVal(offset) 
{
	var endstr = document.cookie.indexOf (";", offset);
	
	if (endstr == -1) { endstr = document.cookie.length; }
	
	return unescape(document.cookie.substring(offset, endstr));
}

function sumo_get_cookie(name) 
{
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;

	while (i < clen)
	{
		var j = i + alen;
		
		if (document.cookie.substring(i, j) == arg) 
		{
			return sumo_get_cookieVal(j);
		}
		
		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0) break;
	}
	return null;
}

function sumo_delete_cookie(name,path,domain)
{	
	if (sumo_get_cookie(name)) 
	{
		document.cookie = name + "=" +
			((path) ? "; path=" + path : "") +
			((domain) ? "; domain=" + domain : "") +
			"; expires=Thu, 01-Jan-70 00:00:01 GMT";
	}
}

function sumo_set_cookie(name,value,expires,path,domain,secure) 
{
	document.cookie = name + "=" + escape (value) +
		((expires) ? "; expires=" + expires.toGMTString() : "") +
		((path) ? "; path=" + path : "") +
		((domain) ? "; domain=" + domain : "") +
		((secure) ? "; secure" : "");
}

//-->