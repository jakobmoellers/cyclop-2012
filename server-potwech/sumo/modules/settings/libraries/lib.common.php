<?php
/**
 * SUMO LIBRARY: Settings
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Console
 */


/**
 * Validate data settings
 * See also sumo_settings_data() in libs/lib.core.php
 */
function sumo_validate_data_settings($data=array(), $message=FALSE)
{	
	$elements = count($data);
	$err 	  = FALSE;
	
	if($elements > 0) 
	{		
		for($d=0; $d<$elements; $d++) 
		{    			
			if($data[$d][2] == 1 || ($data[$d][2] == 0 && $data[$d][1])) 
			{									
				switch($data[$d][0]) 
				{                    
					case 'date_format':					
						if(!ereg("[BdDFjlLmMnrStTwWYyz:\./\\-]+", $data[$d][1]))  $err = 'W06011C';
						break;	
					
					case 'time_format':					
						if(!ereg("[aABgGhHiIOrsTU.:-]+", $data[$d][1]))  $err = 'W06010C';
						break;
					
					case 'admin_name':
						if(!preg_match('/^[a-z0-9'.SUMO_REGEXP_ALLOWED_CHARS.'\'\/\\\_\-\ ]{0,50}$/i', $data[$d][1])) $err = 'W06012C';						
						break;
					
					case 'accounts.life':
						if($data[$d][1] < 0)  $err = 'W06001C';
						break; 
						
					case 'accounts.registration.life':
						if($data[$d][1] < 1)  $err = 'W06007C';
						break; 
						
					// see also sumo_validate_data_accesspoint()
					case 'accesspoints.name':					
						$languages = sumo_get_available_languages();
						
						for($l=0; $l<count($languages); $l++)
						{
							if(!preg_match("/^[a-z0-9\-\_\.\=\&\/\\\'\ ".SUMO_REGEXP_ALLOWED_CHARS."]{5,128}$/i", $data[$d][1][$languages[$l]]))  $err = 'W00031C';
						}
						
						break;
						
					// see also sumo_validate_data_accesspoint()	
					case 'accesspoints.group':						
						if(!sumo_validate_group($data[$d][1], FALSE))  $err = 'W07002C';
						break;
						
					// see also sumo_validate_data_accesspoint()
					case 'accesspoints.theme':					
						if(!in_array($data[$d][1], sumo_get_available_themes()))  $err = 'W00033C';
						break;
					
					case 'security.banned_time':
						if($data[$d][1] < 5)  $err = 'W06002C';
						break;	
						
					case 'security.max_login_attempts':
						if($data[$d][1] < 3)  $err = 'W06004C';
						break;
					
					case 'connections.timeout':
						if($data[$d][1] < 10)  $err = 'W06005C';
						break; 
                    
					case 'sessions.timeout':
						if($data[$d][1] < 60)  $err = 'W06006C';
						break;
					
					case 'database.optimize_hits':
						if($data[$d][1] < 100)  $err = 'W06008C';
						break; 
							
					case 'logs.life':
						if($data[$d][1] < 0)  $err = 'W06003C';
						break;
                                                          
					case 'logs.file.size':
						if($data[$d][1] < 32)  $err = 'W06005C';
						break;
					
					case 'language':             
						if(!in_array($data[$d][1], sumo_get_available_languages())) $err = 'W00021C';                            
						break;	
					
					case 'email':
						if(!sumo_validate_email($data[$d][1]))  $err = 'W00007C';
						break;
										
					case 'boolean':
						if($data[$d][1] != 0 && $data[$d][1] != 1)  $err = 'W00032C';
						break;		
                                           
					default:
						$err = 'W00019C';
						break;						
				}
							
				if($err) break;
			}
		}
		
		
		if($message) 
		{
			if(!$err) return array(TRUE, '');
			else	  return array(FALSE, sumo_get_message($err)."<br>[expect:".$data[$d][0]."]");
		}
		else 
		{
			if(!$err) return TRUE;
			else	  return FALSE;
		}		
	}
	else return FALSE;	
}


/**
 * Convert seconds to H:m:i
 */
function sumo_convert_sec2hms ($sec, $padHours = false) 
{
	// holds formatted string
    $hms = "";
    
    // there are 3600 seconds in an hour, so if we
    // divide total seconds by 3600 and throw away
    // the remainder, we've got the number of hours
    $hours = intval(intval($sec) / 3600); 

    // add to $hms, with a leading 0 if asked for
    $hms .= ($padHours) 
          ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
          : $hours. ':';
     
    // dividing the total seconds by 60 will give us
    // the number of minutes, but we're interested in 
    // minutes past the hour: to get that, we need to 
    // divide by 60 again and keep the remainder
    $minutes = intval(($sec / 60) % 60); 

    // then add to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

    // seconds are simple - just divide the total
    // seconds by 60 and keep the remainder
    $seconds = intval($sec % 60); 

    // add to $hms, again with a leading 0 if needed
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    // done!
    return $hms;
}



/**
 * Add group and "registration group" to accesspoint
 * 
 * NOTE: this function is have tha same name 
 *       of a function into accesspoint module 
 * 
 * @author Alberto Basso
 */
function sumo_put_accesspoint_group($group_exist='')
{		
	$available_group = sumo_get_available_group();
	
	$list = "<select name='config[accesspoints][def_group]'>\n"
		   ."<option value='".$group_exist."'>".$group_exist."</option>\n";
	
	for($g=0; $g<count($available_group); $g++) 
	{										
		if($available_group[$g] != $group_exist) 
		{					
			$style = $available_group[$g]=='sumo' ? " style='color:#BB0000'" : "";					
			$list .= "<option value='".$available_group[$g]."'$style>".$available_group[$g]."</option>\n";
		}
	}
		   
	$list .= "</select>";
	
	return $list;
}


?>