<?php
/**
 * SUMO LIBRARY: Network
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Console
 */

require SUMO_PATH_MODULE.'/libraries/lib.nodes.php';
require SUMO_PATH_MODULE.'/libraries/lib.datasources.php';
require SUMO_PATH_MODULE.'/libraries/lib.local_network.php';


/**
 * Validate network data
 * 
 * Note: see also sumo_validate_data() in libs/lib.core.php
 */
function sumo_validate_data_network($data=array(), $message=FALSE)
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
					case 'id':
						// INT = 256^4-1
						if($data[$d][1] < 1 || $data[$d][1] > 4294967296)  $err = 'W00029C';
						break;

					case 'node_name':					
						if(!preg_match("/^[a-z0-9".SUMO_REGEXP_ALLOWED_CHARS."\-\_\.\=\&\/\\\'\ ]{4,50}$/i", $data[$d][1]))  $err = 'W09015C';
						break;
										
					case 'dsname':					
						if(!preg_match("/^[a-z0-9".SUMO_REGEXP_ALLOWED_CHARS."\-\_\.\=\&\/\\\'\ ]{4,128}$/i", $data[$d][1]))  $err = 'W09001C';
						break;
						
					case 'type':						
						if(!in_array($data[$d][1], sumo_get_available_datasources()))  $err = 'W09002C';
						break;
					
					case 'port':
						if($data[$d][1] < 1 || $data[$d][1] > 65535)  $err = 'W09004C';
						break;
						
					case 'protocol':
						$protocols = array('http', 'https');						
						if(!in_array($data[$d][1], $protocols))  $err = 'W09017C';
						break;
											
					case 'username':
						if(!preg_match('/^[a-z0-9]{3,32}$/i', $data[$d][1]))  $err = 'W09005C';						
						break;
						
					case 'password':
						if(!preg_match('/[a-z0-9\.\,\:\;\_\-\$\!\"\'\/\\\£\%\&\(\)\=\?\^\+\*\ '.SUMO_REGEXP_ALLOWED_CHARS.']{3,255}$/i', $data[$d][1]))  $err = 'W09006C';
						break;
					
					case 'db_name':
						if(!preg_match('/^[a-z0-9\_]{3,32}$/i', $data[$d][1]))  $err = 'W09007C';						
						break;	
					
					case 'db_table':
						if(!preg_match('/[a-z0-9\_]{3,255}$/i', $data[$d][1]))  $err = 'W09008C';
						break;	
						
					case 'enctype':
						$enctype = sumo_get_datasource_enctype();
						
						if(!in_array($data[$d][1], $enctype))  $err = 'W09018C';
						break;
						
					case 'ldap_base':
						if(!preg_match('/^[a-z0-9\.\,\:\;\_\-\=\\\/\+\*\ '.SUMO_REGEXP_ALLOWED_CHARS.']{4,255}$/i', $data[$d][1]))  $err = 'W00027C';
						break;
					
					case 'iptype':
						$type = array('L','P');						
						if(!in_array($data[$d][1], $type)) $err = 'W09010C';
						break;
					
					case 'host':
						if(!sumo_validate_ip($data[$d][1], FALSE) && !preg_match('/[a-z0-9\.\_\-]{3,128}$/i', $data[$d][1])) $err = 'W09011C';
						break;
						
					case 'hostname':
						if(!preg_match('/[a-z0-9\.\_\-]{3,128}$/i', $data[$d][1]))  $err = 'W09003C';
						break;
						
					case 'iprange':
						if(!sumo_validate_iprange($data[$d][1], FALSE)) $err = 'W09009C';
						break;
					
					case 'status':
						if($data[$d][1] != 0 && $data[$d][1] != 1) $err = 'W09012C';
						break;				
						
					case 'sumo_path':
						if(!preg_match("/^\/[a-z0-9\-\_\.\/]{1,253}\/$/i", $data[$d][1])) $err = 'W09014C';
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
			return (!$err) ? array(TRUE, '') : array(FALSE, sumo_get_message($err));
		}
		else {
			return (!$err) ? true : false;
		}		
	}
	else return false;
}

?>