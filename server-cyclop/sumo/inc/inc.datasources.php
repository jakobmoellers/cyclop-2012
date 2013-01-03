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

switch ($SUMO['user']['datasource_type']) 
{
    case 'LDAP':       $ds = 'ldap';     break;
    case 'LDAPS':      $ds = 'ldaps';    break;
    case 'ADAM':       $ds = 'adam';     break;
    case 'MySQL':      $ds = 'mysql';    break;
    case 'MySQLUsers': $ds = 'mysql_users'; break;
    case 'Postgres':   $ds = 'postgres'; break;
    case 'Oracle':     $ds = 'oracle';   break;
    case 'Unix': 	   $ds = 'unix';     break;
    case 'GMail':	   $ds = 'gmail';	 break;
    case 'Joomla15':   $ds = 'joomla15'; break;  
    default:      
    	$ds = 'UNDEFINEDDS'; 
    	break;
}
    	

if($ds == 'UNDEFINEDDS')
    $sumo_access='UNDEFINEDDS';
else 
{	
	$ds_file = SUMO_PATH.'/inc/inc.datasource.'.$ds.'.php';
	
	if(sumo_verify_file($ds_file)) 
		require $ds_file;
	else 
		$sumo_access='UNDEFINEDDS';
}

?>