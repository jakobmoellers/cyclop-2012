<!--

var user	    = sumo_get_cookie('user');
var today  		= new Date();
var expiry 		= new Date(today.getTime() + 31536000000);
var refresh		= new Array();
var maximized   = new Array();
var sizeWindowX = new Array();
var sizeWindowY = new Array();
var x			= new Array();
var y			= new Array();

// Show-Hide
var timerlen 	= 5;
var slideAniLen = 250;
var timerID 	= new Array();
var startTime 	= new Array();
var obj 		= new Array();
var endHeight 	= new Array();
var moving 		= new Array();
var dir			= new Array();


// Preload images
if (document.images)
{
	var img_url = "themes/"+ sumo_theme +"/images/loading/loading";
	
	for(i=1; i<13; i++)
	{
		eval("var loading"+i+" = new Image();");
		eval("loading"+i+".src = img_url+\""+i+".png\";");
	}
}


/**
 * Parsing a script and execute <script> content
 * 
 * @param _source
 * @return
 */
function sumo_parse_script(_source)
{
	var source = _source;
	var scripts = new Array();

	// Strip out tags
	while(source.indexOf("<script") > -1 || source.indexOf("</script") > -1)
	{
		var s   = source.indexOf("<script");
		var s_e = source.indexOf(">", s);
		var e   = source.indexOf("</script", s);
		var e_e = source.indexOf(">", e);

		// Add to scripts array
		scripts.push(source.substring(s_e+1, e));
		// Strip from source
		source = source.substring(0, s) + source.substring(e_e+1);
	}

	// Loop through every script collected and eval it
	for(var i=0; i<scripts.length; i++)
	{
		try {
			eval(scripts[i]);
		}
		catch(ex) {
			// do what you want here when a script fails
		}
	}

	// Return the cleaned source
	return source;
}


/**
 * Save window settings
 */
function sumo_save_window_settings(user, name, start)
{
	var name = name.replace(".content", "");
	
	x[name] = eval('dd.elements.'+name+'.x');
	y[name] = eval('dd.elements.'+name+'.y');
	
	if(x[name] > 0 && y[name] > 0)
	{
		sumo_ajax_get_bg('services.php?service=profiler'
						+'&user='+user
						+'&module='+name
						+'&cmd=SAVE_WINDOW_SETTINGS'
						+'&s='+(parseInt(start))
						+'&x='+x[name] 
						+'&y='+y[name]);
	}
}

/**
 * Save icon settings
 */
function sumo_save_icon_settings(user, name)
{
	var name = name.replace(".content", "");
	
	x[name] = eval('dd.elements.Icon'+name+'.x');
	y[name] = eval('dd.elements.Icon'+name+'.y');
	
	if(x[name] > 0 && y[name] > 0)
	{
		sumo_ajax_get_bg('services.php?service=profiler'
						+'&user='+user
						+'&module='+name
						+'&cmd=SAVE_ICON_SETTINGS'
						+'&x='+x[name] 
						+'&y='+y[name]);
	}
}


function sumo_add_window(name)
{
	var desktop = document.getElementById('ModulesWindows');
	var newdiv  = document.createElement('div');
	var myWidth = 0, myHeight = 0;

	if(typeof( window.innerWidth ) == 'number')
	{
		//Non-IE
		myWidth  = window.innerWidth;
		myHeight = window.innerHeight;
	} else if( document.documentElement &&
	(document.documentElement.clientWidth || document.documentElement.clientHeight)) {
		//IE 6+ in 'standards compliant mode'
		myWidth  = document.documentElement.clientWidth;
		myHeight = document.documentElement.clientHeight;
	} else if(document.body && (document.body.clientWidth || document.body.clientHeight)) {
		//IE 4 compatible
		myWidth  = document.body.clientWidth;
		myHeight = document.body.clientHeight;
	}
	
	// window position	
	if(x[name] == null || y[name] == null) 
	{
		x[name] = parseInt(myWidth/2 - 350);
		y[name] = parseInt(myHeight/2 - 250);
	}
	//
	if(x[name] < 1)  x[name] = 120;
	if(y[name] < 18) y[name] = 36;
	
	newdiv.setAttribute('id', name);
	newdiv.style.opacity="0";
	newdiv.style.width="100%";
	newdiv.style.height=23;
	newdiv.style.position="absolute";
	newdiv.style.left=x[name]+"px";
	newdiv.style.top=y[name]+"px";
	
	desktop.appendChild(newdiv);
	
	ADD_DHTML(name+MAXOFFTOP+(y[name]-18)+MAXOFFLEFT+x[name]+SCROLL);
	
	// Messages module exceptions
	if(name.substring(0, 3) == 'msg' || name == 'messages' )
	{
		if(parseInt(name.substring(3, 13)) > 0)
		{
			opacity(name, 0, 100, 600);
		}
		else
		{
			document.getElementById(name).style.width="0px";
			document.getElementById(name).style.height="0px";
		}
	}
	else
	{
		eval('dd.elements.'+name+'.setOpacity(1)');
		eval('if(dd.elements.Icon'+name+'){dd.elements.Icon'+name+'.setOpacity(0.5);}');

		sumo_save_window_settings(user, name, 1);
	}
}

function sumo_refresh_window(name, action, time, url)
{		
	refresh[name] = new Array();
	 
	if(refresh[name][action] == undefined)
	{	
		if(document.getElementById(name) != null)
		{
			refresh[name][action] = setInterval("sumo_ajax_get('"+name+".content','"+url+"&action="+action+"&decoration=false&refresh=true', true);", time);
		}
	}
}

function sumo_unrefresh_window(name)
{	
	 for (var a in refresh[name]) 
	 {				 
		 clearInterval(refresh[name][a]);
		 refresh[name][a] = false;
	 }
}

function sumo_minimize_window(name)
{
	sumo_remove_window(name);
}

function sumo_maximize_window(name)
{
	var obj = document.getElementById("window"+name);
	
	if(!maximized["window"+name])
	{
		var w = document.body.clientWidth;
		var h = parseInt(document.body.clientHeight - 25);

		sizeWindowX["window"+name] = parseInt(obj.offsetWidth);
		sizeWindowY["window"+name] = parseInt(obj.offsetHeight);

		dd.elements[name].moveTo(0, 20);

		obj.style.width  = w+"px";
		obj.style.height = h+"px";

		maximized["window"+name] = 1;
	}
	else
	{
		var w = parseInt(document.body.clientWidth / 4);
		//var h = parseInt(document.body.clientHeight / 8);
		var h = 40;

		dd.elements[name].moveTo(w, h);

		obj.style.width  = sizeWindowX["window"+name]+"px";
		obj.style.height = sizeWindowY["window"+name]+"px";

		maximized["window"+name] = 0;
	}

	resizeIframe(name);
}


function sumo_center_window(name)
{
	var obj = document.getElementById("window"+name);
	var w   = parseInt(document.body.clientWidth / 2);
	var h   = parseInt(document.body.clientHeight / 2);
	var x   = parseInt(w - (obj.offsetWidth / 2));
	var y   = parseInt(h - (obj.offsetHeight / 2));

	dd.elements[name].moveTo(x, y);
}


function resizeIframe(name)
{
	var obj = document.getElementById("iframe_"+name);
	
	if(obj != null)
	{
		obj.style.width  = "100%";
		obj.style.height = parseInt(obj.offsetHeight-50)+"px";
	}
}

function _sumo_remove_window(name)
{
	var desktop   = document.getElementById('ModulesWindows');
	var moduleWin = document.getElementById(name);
	
	desktop.removeChild(moduleWin);
}

function sumo_remove_window(name, save_settings)
{
	var desktop   = document.getElementById('ModulesWindows');
	var moduleWin = document.getElementById(name);

	if(save_settings != false) save_settings = true;
	
	if(moduleWin != null)
	{	
		opacity(name, 100, 0, 300);
		
		setTimeout('_sumo_remove_window("'+name+'")', 300);
	
		// Save window settings
		if(name.substring(0, 3) != 'msg' && save_settings == true)
		{
			// icon opacity
			eval('if(dd.elements.Icon'+name+'){dd.elements.Icon'+name+'.setOpacity(1);}');
	
			sumo_save_window_settings(user, name, 0);
		}
		
		//
		sumo_unrefresh_window(name);
	}
}


function doFocus(obj)
{
	if( obj.style.cssText == '' && (obj.type!='radio' && obj.type!='checkbox') )
	obj.style.cssText = 'border:1px solid #BB0000;';
}

function doBlur(obj)
{
	obj.style.cssText = '';
}


/**
 * Recalculate window dimension
 * 
 * @param name
 * @return
 */
function windowFocus(name)
{
	var obj  = document.getElementById(name);
	var obj2 = document.getElementById("window"+name);
	
	if(obj != null && obj2 != null)
	{
		obj.style.height = obj2.offsetHeight;
		obj.style.width  = obj2.offsetWidth;
		
		// obj2.style.zIndex = parseFloat(obj.style.zIndex);
	}
}


function ResetSelect(objID)
{
	selObject = document.getElementById(objID)

	// cycle through the options collection, setting 
	// the selected attribute of each to false
	for (i=0; i<selObject.options.length; i++)
	{
		selObject.options[i].selected = false;
	}
}


function opacity(id, opacStart, opacEnd, millisec)
{
	// speed for each frame
	var speed = Math.round(millisec / 100);
	var timer = 0;
	var i = 0;
	
	// determine the direction for the blending, if start 
	// and end are the same nothing happens
	if(opacStart > opacEnd) 
	{
		for(i = opacStart; i >= opacEnd; i--)
		{
			setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
			timer++;
		}
	} 
	else if(opacStart < opacEnd) 
	{
		for(i = opacStart; i <= opacEnd; i++)
		{
			setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
			timer++;
		}
	}
}

// change the opacity for different browsers
function changeOpac(opacity, id)
{
	if(document.getElementById(id) != null)
	{
		var obj = document.getElementById(id).style;

		obj.opacity = (opacity / 100);
		obj.MozOpacity = (opacity / 100);
		obj.KhtmlOpacity = (opacity / 100);
		obj.filter = "alpha(opacity=" + opacity + ")";
	}
}


/**
 * Show - Hide
 * 
 * @param id
 * @return
 */
function HideElement(id)
{
	var obj = document.getElementsByTagName("div");

	obj[id].style.position   = 'absolute';
	obj[id].style.visibility = 'hidden';
}
function ShowElement(id)
{
	var obj = document.getElementsByTagName("div");

	obj[id].style.position   = 'static';
	obj[id].style.visibility = 'visible';
}
function ShowHideElement(id)
{
	var obj = document.getElementsByTagName("div");

	if (obj[id].style.visibility != 'visible')
		ShowElement(id);
	else
		HideElement(id);
}

function slidedown(objname)
{
    if(moving[objname])
            return;

    if(document.getElementById(objname).style.display != "none")
            return; // cannot slide down something that is already visible

    moving[objname] = true;
    dir[objname] = "down";
    startslide(objname);
}

function slideup(objname)
{
    if(moving[objname])
            return;

    if(document.getElementById(objname).style.display == "none")
            return; // cannot slide up something that is already hidden

    moving[objname] = true;
    dir[objname] = "up";
    startslide(objname);
}

function startslide(objname)
{
    obj[objname] = document.getElementById(objname);

    endHeight[objname] = parseInt(obj[objname].style.height);
    startTime[objname] = (new Date()).getTime();

    if(dir[objname] == "down"){
            obj[objname].style.height = "1px";
    }

    obj[objname].style.display = "block";

    timerID[objname] = setInterval('slidetick(\'' + objname + '\');',timerlen);
}

function slidetick(objname)
{
    var elapsed = (new Date()).getTime() - startTime[objname];

    if (elapsed > slideAniLen)
            endSlide(objname)
    else {
            var d =Math.round(elapsed / slideAniLen * endHeight[objname]);
            if(dir[objname] == "up")
                    d = endHeight[objname] - d;

            obj[objname].style.height = d + "px";
    }

    return;
}

function endSlide(objname)
{
    clearInterval(timerID[objname]);

    if(dir[objname] == "up")
            obj[objname].style.display = "none";

    obj[objname].style.height = endHeight[objname] + "px";

    delete(moving[objname]);
    delete(timerID[objname]);
    delete(startTime[objname]);
    delete(endHeight[objname]);
    delete(obj[objname]);
    delete(dir[objname]);

    return;
}

function HideSubModule(name)
{
	// div is not hidden, so slide up
    slideup(name);
    
    var id2 = name.split(".");
	var vis = id2[1]+"_visibility";

	eval("if(document."+id2[0]+"){document."+id2[0]+"."+vis+".value=0}");
}

function ShowSubModule(name)
{
	slidedown(name);
    
    var id2 = name.split(".");
	var vis = id2[1]+"_visibility";

	eval("if(document."+id2[0]+"){document."+id2[0]+"."+vis+".value=1}");
}

function ShowHideSubModule(name)
{	
	  if(document.getElementById(name).style.display == "none")
		  ShowSubModule(name);		
	  else
		  HideSubModule(name);
}


// Log out from application
function sumo_user_logout()
{
	// IE patch: opacity on Icons 
	var icons = document.getElementsByTagName("div");
	
	for (var i = 0; i < icons.length; i++) 
	{
		var elem = new String(icons.item(i).getAttribute("id"));
		
		if(elem.substring(0, 4) == 'Icon')
		{		
			opacity(elem, 100, 40, 500);
		}
	}
	
	document.body.style.backgroundColor = 'black';
	
	opacity('desktop', 100, 40, 500);
	
	setTimeout('location.href="?sumo_action=logout"', 2000);
}


// Reusable timer
function sumo_loader_view(numb, name)
{
	setTimeout("imgturn('"+ numb +"', '"+ name +"')", 120);
}

// Reusable image turner
function imgturn(numb, name)
{
	if (document.images && document.getElementById('loading-'+ name))
	{
		// This will loop the image
		if (numb == "12")
		{
			document.getElementById('loading-'+ name).src = eval("loading12.src");
			sumo_loader_view('1', name);
		}
		else
		{
			document.getElementById('loading-'+ name).src = eval("loading"+ numb +".src");
			sumo_loader_view(numb = ++numb, name);
		}
	}
}

function sumo_loader_end(name)
{
	document.body.style.cursor = 'default';
}

// View window loader
function sumo_loader_start(name, delay)
{
	var obj 	  = document.getElementById(name);
	var moduleWin = name.replace(".content", "");
	var paddingW  = parseInt(obj.offsetWidth / 2 - 25);
	var paddingH  = parseInt(obj.offsetHeight / 2 - 25);

	document.body.style.cursor = 'wait';
	
	dd.elements[moduleWin].maximizeZ();
	
	eval("if(dd.elements.Icon"+ moduleWin +"){dd.elements.Icon"+ moduleWin +".setOpacity(0.5);}");
	
	obj.innerHTML = obj.innerHTML
		+"<div id='loader"+moduleWin+"' "
		+"style='position: absolute;top:"+paddingH+"px;left:"+paddingW+"px;'>"
		+"<img id='loading-"+ moduleWin +"' src='themes/sumo/images/blank.gif'>"
		+"</div>";

	if(delay == null) delay = 2500;
	
	setTimeout("sumo_loader_view(11, '"+ moduleWin +"');", delay);
}


/**
	Correctly handle PNG transparency in Win IE 5.5 & 6.
	http://homepage.ntlworld.com/bobosola. Updated 18-Jan-2006.
	
	Use in <HEAD> with DEFER keyword wrapped in conditional comments:
	<!--[if lt IE 7]>
	<script defer type="text/javascript" src="pngfix.js"></script>
	<![endif]-->
*/
function PNGFix()
{
	var arVersion = navigator.appVersion.split("MSIE")
	var version = parseFloat(arVersion[1])

	if ((version >= 5.5) && (document.body.filters))
	{
		for(var i=0; i<document.images.length; i++)
		{
			var img = document.images[i]
			var imgName = img.src

			if (imgName.substring(imgName.length-3, imgName.length) == "png")
			{
				var imgID = (img.id) ? "id='" + img.id + "' " : ""
				var imgClass = (img.className) ? "class='" + img.className + "' " : ""
				var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
				var imgStyle = "display:inline-block;" + img.style.cssText

				if (img.align == "left") imgStyle = "float:left;" + imgStyle
				if (img.align == "right") imgStyle = "float:right;" + imgStyle
				if (img.align == "middle") imgStyle = "float:middle;" + imgStyle
				if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle

				var strNewHTML = "<span " + imgID + imgClass + imgTitle
				+ " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
				+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
				+ "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>"

				img.outerHTML = strNewHTML

				i = i-1
			}
		}
	}
}


function PNGFixImg(myImage)
{
	var arVersion = navigator.appVersion.split("MSIE")
	var version = parseFloat(arVersion[1])

	if ((version >= 5.5) && (version < 7) && (document.body.filters))
	{
		var imgID = (myImage.id) ? "id='" + myImage.id + "' " : ""
		var imgClass = (myImage.className) ? "class='" + myImage.className + "' " : ""
		var imgTitle = (myImage.title) ?
		"title='" + myImage.title  + "' " : "title='" + myImage.alt + "' "
		var imgStyle = "display:inline-block;" + myImage.style.cssText
		var strNewHTML = "<span " + imgID + imgClass + imgTitle
			+ " style=\"" + "width:" + myImage.width
			+ "px; height:" + myImage.height
			+ "px;" + imgStyle + ";"
			+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
			+ "(src=\'" + myImage.src + "\', sizingMethod='scale');\"></span>"
		myImage.outerHTML = strNewHTML
	}
}

//-->