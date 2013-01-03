<?php
/**
 * SUMO LIBRARY: Sessions & Connections
 *
 * @version    0.3.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Console
 */


/**
 * Get formatted user name from SUMO_TABLE_USERS by user 
 */
function sumo_get_username($username='', $cache=TRUE)
{
	GLOBAL $SUMO;
	
	$query = "SELECT firstname,lastname FROM ".SUMO_TABLE_USERS." 
			  WHERE username='".$username."'";
	
	if($cache)
		$rs = $SUMO['DB']->CacheExecute(60, $query);
	else
		$rs = $SUMO['DB']->Execute($query);
		
	$tab = $rs->FetchRow();
	
	$username = sumo_get_formatted_username($tab['firstname'], $tab['lastname']);
	
	return $username;
}

?>