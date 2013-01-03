<!--

/**
 * Get server clock
 */
function runClock(hr, min, sec, lim, ap)
{
	sec++;	
	
	if(sec==60) { min++; sec=0; }
	if(min==60) { hr++;  min=0; }	
	if(hr==lim) { hr=0; }	
		
	if(min<="9") { var minD = "0" + min; } else { var minD = min; }
	
	document.getElementById('clock').innerHTML = hr + "<blink>:</blink>" + minD + ap; 
	
	if(document.getElementById('clock-big') != null) 
	{
		//if(hr  < 10) hr = "0"+hr;
		if(sec < 10) sec = "0"+sec;
		document.getElementById('clock-big').innerHTML = hr + ":" + minD + ":" + sec + ap; 
	}
	
	setTimeout("runClock("+hr+", "+min+", "+sec+", "+lim+", '"+ap+"')", 1000);
}

/**
 * Initialize clock
 */
function startClock() {

	<?php		
		
		switch($_GET['sumo_lang'])
		{
			case 'en':
				$h  = date("g");
				$ap = date("G") >= 12 ? "&nbsp;PM" : "&nbsp;AM";
				$l  = 13;
				break;
			
			default:
				$h  = date("G");
				$ap = "";
				$l  = 24;
				break;
		}
		
		echo "\tvar	hr  = ".$h.";\n";
		echo "\tvar	min = ".date("i").";\n";
		echo "\tvar	sec = ".intval(date("s")).";\n";
	?>
	
	setTimeout("runClock("+hr+", "+min+", "+sec+", "+<?=$l;?>+", '<?=$ap;?>')", 1000);
}

//-->