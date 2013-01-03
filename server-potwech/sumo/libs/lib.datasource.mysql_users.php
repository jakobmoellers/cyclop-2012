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
 * MySQL users datasource authentication library
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
		$db = mysql_connect( $datasource["host"].":".$datasource["port"], 
						     $datasource["username"], 
						     $datasource["password"] );

		$connect = mysql_select_db($datasource["db_name"], $db);
		
		$connect = $connect ? TRUE : FALSE;
		
		mysql_close($db);
		
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
		$db = mysql_connect( $datasource["host"].":".$datasource["port"], 
						     $datasource["username"], 
						     $datasource["password"] );

		$connect = mysql_select_db($datasource["db_name"], $db);
						
		if($connect) 
		{ 	
			$query = "SELECT COUNT(User) FROM `user` 
			  		  WHERE `User`=\'".$SUMO["user"]["user"]."\'
			  		  	AND `Password`=PASSWORD(\'".$_SESSION["user"]["password"]."\')
			  		  	AND `Host`=\'%\'";
			
			$rs = mysql_query($query);
			
			if($rs)
				$tab = mysql_fetch_row($rs);	
			else
				$tab[0] = 0;
								
			// Check password		
			$_SESSION["ds_connect"][$id] = $tab[0]==0 ? false : true;

			$SUMO["user"]["password"] = "";							
		} 
		else 
		{
			sumo_write_log("W00051X", $datasource["name"], "0,1", 2);		   
		}
		
		mysql_close($db);
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
					  "mysql");
																
		$query = "UPDATE `user` SET `Password`=PASSWORD(\'".$password."\')  
		  		  WHERE `User`=\'".$username."\'
		  		  	AND `Host`=\'%\'";

		$rs = $db->Execute($query);
			
		return true;	
	}
	return false;
');

?>