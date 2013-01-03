<!--

// Background Ajax request
function sumo_ajax_get_bg(url)
{
	AjaxRequest.get(
	  {
	  	'url': url
	  }
	);
}

// Get
function sumo_ajax_get(module, url, quiet)
{
	var moduleWin = module.replace(".content", "");

	if(document.getElementById(moduleWin) == null)
	{
		sumo_add_window(moduleWin);
	}


	AjaxRequest.get(
	  {
	  	'url': url,
	  	'timeout':60000,
    	'onTimeout':function(req) {
    								if(quiet == null)
    								{
    									sumo_show_message('', 'Application timed out!<br>Module: '+moduleWin, 'h', 1);
    									document.getElementById(moduleWin).innerHTML = '';
    									document.body.style.cursor = 'default';
    								}
    							  },
	  	'onLoading':function()    {
	  								if(quiet == null)
	  								{
	  									sumo_loader_start(module);
	  								}
	  							  },
	    'onSuccess':function(req) {
	    							if(document.getElementById(module))
	    							{
	    								if(req.responseText.substring(0, 9) == '<!DOCTYPE')
	    									location.href=''; // Session ended (I hope)
	    								else
	    								if(req.responseText.substring(0, 8) == '<SCRIPT>')
	    									eval(req.responseText.substring(8, req.responseText.length));  // messages
	    								else
	    									document.getElementById(module).innerHTML = sumo_parse_script(req.responseText);
	    							}

	    							if(quiet == null)
	    							{
	    								sumo_loader_end(module);
	    							}
	    						  }
	  }
	);
}


// Post
function sumo_ajax_post(module, theform, quiet)
{	
	var moduleWin = module.replace(".content", "");
	
	if(document.getElementById(moduleWin) == null)
	{
		sumo_add_window(moduleWin);
	}
	
	var status = AjaxRequest.submit(
	    theform
	    ,{
	      'timeout':60000,
    	  'onTimeout':function(req) {
    	  							  sumo_show_message('', 'Application timed out!<br>Module: '+moduleWin, 'h', 1);
    								  document.getElementById(moduleWin).innerHTML = '';
    								  document.body.style.cursor = 'default';
    							    },
	  	  'onLoading':function()    {
	  	  							  if(quiet == null) 
	  	  							  { 
	  	  								  sumo_loader_start(module); 
	  	  							  }
	  	  							},
	      'onSuccess':function(req) {
	      							  if(req.responseText.substring(0, 9) == '<!DOCTYPE')
	    									location.href=''; // Session ended (I hope)
	    							  else
	    							  if(req.responseText.substring(0, 8) == '<SCRIPT>')
	    									eval(req.responseText.substring(8, req.responseText.length));  // messages
	    							  else
	    									document.getElementById(module).innerHTML = sumo_parse_script(req.responseText);
	    								      							  		
	      							  if(quiet == null) 
	      							  { 
	      								  sumo_loader_end(module); 
	      							  }
	      							}
	    }
  	);

  	return status;
}

//-->