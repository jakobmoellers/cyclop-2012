<?php
/**
 * SUMO CORE DATASOURCE LIBRARY
 *
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

/**
 * Joomla 1.5.x datasource authentication library
 */

/**
 * Check datasource connection
 * 
 * @global resource $SUMO
 */
$sumo_verify_datasource_connection = create_function('$id=false', '
	
	if(!$_SESSION["ds_connect"][$id])  // ...to not reconnecting any time!
	{		
		$datasource = sumo_get_datasource_info($id);		
									
		if(!$datasource["port"]) $datasource["port"] = 3306;
				
		// Database connection	
		$db = ADONewConnection("mysql");
		
		$db->Connect( $datasource["host"].":".$datasource["port"], 
					  $datasource["username"], 
					  $datasource["password"], 
					  $datasource["db_name"]);
				
		$connect = $db->IsConnected() ? TRUE : FALSE;
		
		return $connect;	
	}
	else return true;	
');


/**
 * Verify password of current user
 * 
 * Return:
 * 
 * FALSE: password error
 * TRUE:  password ok
 * 
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
$sumo_verify_datasource_authentication = create_function('$id=FALSE', '
	
	GLOBAL $SUMO;
	
	if(!$_SESSION["ds_connect"][$id]) 
	{			
		$datasource = sumo_get_datasource_info($id);		
							
		if(!$datasource["port"]) $datasource["port"] = 3306;
		
		// Database connection	
		$db = ADONewConnection("mysql");
		
		$db->Connect( $datasource["host"].":".$datasource["port"], 
					  $datasource["username"], 
					  $datasource["password"], 
					  $datasource["db_name"]);
				
		if($db->IsConnected()) 
		{ 				
			$query = "SELECT `password` FROM ".$datasource["db_table"]." 
			  		  WHERE `username`=\'".$SUMO["user"]["user"]."\'";
			
			$rs = $db->Execute($query);
			
			if($rs)
				$tab = $rs->FetchRow();	
			else
				$tab[0] = FALSE;
							
			$password = explode(":", $tab[0]);
				
			// Check password			
			$_SESSION["ds_connect"][$id] = $tab[0] == md5(stripslashes($_SESSION["user"]["password"]).$password[1]).":".$password[1] ? true : false;					
		} 
		else 
		{
			sumo_write_log("W00056X", $datasource["name"], "0,1", 2);		   
		}
	}
	
	return $_SESSION["ds_connect"][$id] ? true : false;
');


/**
 * Update user password
 *
 * @param unknown_type $id
 * @param unknown_type $user
 * @return boolean
 */
$sumo_update_password = create_function('$username="", $password=""', '

	$user		= sumo_get_user_info($username);
	$datasource = sumo_get_datasource_info($user["datasource_id"]);
		
	if($user["id"] && $password)
	{
		$db = ADONewConnection("mysql");
			
		$db->Connect( $datasource["host"].":".$datasource["port"], 
					  $datasource["username"], 
					  $datasource["password"], 
					  $datasource["db_name"]);
					
		$salt = sumo_get_rand_string(32);
											
		$query = "UPDATE `".$datasource["db_table"]."` 
				  SET `password`=\'".md5(stripslashes($password).$salt).":".$salt."\'  
		  		  WHERE `username`=\'".$username."\'";

		$rs = $db->Execute($query);
			
		return true;	
	}
	return false;
');

?>