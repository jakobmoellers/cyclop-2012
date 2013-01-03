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
 * Oracle datasource authentication library
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
		
		if(!$datasource["port"]) $datasource["port"] = 1521;
				
		// Database connection	
		// Oci8 extension: Despite it is name, which implies it is only for Oracle 8i, 
		// this is the standard method for accessing databases running Oracle 8i, 9i or 10g (and later).
		$db = ADONewConnection("oci8");
		
		$tnsname = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=".$datasource["host"].")"
				  ."(PORT=".$datasource["port"]."))(CONNECT_DATA=(SID=".$datasource["db_name"].")))";
			
		$db->Connect( $tnsname, 
					  $datasource["username"], 
					  $datasource["password"]);
				
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
							
		if(!$datasource["port"]) $datasource["port"] = 1521;
		
		// Database connection	
		$db = ADONewConnection("oci8");
		
		$tnsname = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=".$datasource["host"].")"
				  ."(PORT=".$datasource["port"]."))(CONNECT_DATA=(SID=".$datasource["db_name"].")))";
			
		$db->Connect( $tnsname, 
					  $datasource["username"], 
					  $datasource["password"]);
				
		if($db->IsConnected()) 
		{ 	
			$query = "SELECT ".$datasource["db_field_password"]." FROM ".$datasource["db_table"]." 
			  		  WHERE ".$datasource["db_field_user"]."=\'".$SUMO["user"]["user"]."\'";
			
			$rs = $db->Execute($query);
			
			if($rs)
				$tab = $rs->FetchRow();	
			else
				$tab[0] = FALSE;
						
			// Check password
			$SUMO["user"]["password"] = $tab[0];
			
			$_SESSION["ds_connect"][$id] = sumo_verify_password();

			$SUMO["user"]["password"] = "";
		} 
		else 
		{
			sumo_write_log("W00053X", $datasource["name"], "0,1", 2);		   
		}
	}
	
	return $_SESSION["ds_connect"][$id] ? true : false;
');

?>