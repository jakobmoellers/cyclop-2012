<?php
/**
 * SUMO CORE 
 *
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

// Load functions datasource library
require SUMO_PATH."/libs/lib.datasource.adam.php";


// Verify if LDAP extension for Active Directory is loaded
if(!in_array('ldap', get_loaded_extensions())) sumo_dl('ldap');

// ...if not give an error
if(!in_array('ldap', get_loaded_extensions()))
	$sumo_access='LDAPMODULEERROR';
	
elseif($_SESSION['loggedin'] == false && !$sumo_verify_datasource_connection($SUMO['user']['datasource_id']))
    $sumo_access='LDAPCONNECTIONFAILED';
    
// Verify Password via Active Directory (only at login)
elseif(!$sumo_verify_datasource_authentication($SUMO['user']['datasource_id']))
	$sumo_access='PASSWORDERROR';
	
else
{
	// Verify if user already logged in and if
	// sessions is expired
	$sumo_session = sumo_get_session_info();
	$sumo_access  = empty($sumo_session['username']) ? 'LOGIN' : 'CONTINUE';
}

?>