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
 * LDAP SASL datasource authentication library
 */


/**
 * Check datasource connection
 * 
 * @global resource $SUMO
 */
$sumo_verify_datasource_connection = create_function('$id=false', '

	GLOBAL $SUMO;
	
	if(!$_SESSION["ldap_connect"][$id])  // ...to not reconnecting any time!
	{		
		$datasource = sumo_get_datasource_info($id);		
									
		if(!$datasource["port"]) $datasource["port"] = 636;
							
		// $ds is a valid link identifier for a directory server	
		$ds = ldap_connect("ldaps://".$datasource["host"], $datasource["port"]);
		$sr = ldap_search($ds, $datasource["ldap_base"], "uid=".$SUMO["user"]["user"]);
				
		// Close the connection
    	ldap_close($ds);
    
		return empty($sr) ? false : true;
	}
	else return true;
');


/**
 * Verify password of current user
 * 
 * Return:
 * 
 * FALSE: password error
 * TRUE: password ok
 * 
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
$sumo_verify_datasource_authentication = create_function('$id=FALSE', ' 

	GLOBAL $SUMO;
	
	if(!$_SESSION["ds_connect"][$id]) 
	{	
		$ldap = sumo_get_datasource_info($id);		
						
		if(!$ldap["port"]) $ldap["port"] = 389;
				
		// $ds is a valid link identifier for a directory server	
		$ds = ldap_connect($ldap["host"], $ldap["port"]);
		$dn = $ldap["ldap_base"];
				
		if($ds && $ldap["host"]) 
		{ 		    				
			// Encryption type
			switch ($SUMO["user"]["datasource_enctype"])
			{
				case "md5":   $password = md5($_SESSION["user"]["password"]);   break;
				case "crc32": $password = crc32($_SESSION["user"]["password"]); break;
				default:      $password = $_SESSION["user"]["password"];        break;
				
			}
			  
			$sr   	  = ldap_search($ds, $dn, "uid=".$SUMO["user"]["user"]); 		    
			$info 	  = ldap_get_entries($ds, $sr);
			$ldapbind = ldap_bind($ds, $info[0]["dn"], $password);
								
			// verify binding
			$_SESSION["ds_connect"][$id] = $ldapbind && $info["count"] == 1 ? true : false;
							
			// Close the connection	
			ldap_unbind($ds);
			ldap_close($ds);				
		} 
		else 
		{
			sumo_write_log("W00047X", $ldap["name"], "0,1", 2);		   
		}
	}
	
	return $_SESSION["ds_connect"][$id] ? true : false;
');

?>