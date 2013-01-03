<?php
/**
 * SUMO: Database connection
 *
 * @version    0.3.5
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

switch($sumo_db['type'])
{
	case 'mysql':
		$SUMO['DB'] = ADONewConnection('mysql');
		$SUMO['DB']->Connect( $sumo_db['host'].':'.$sumo_db['port'], 
							  $sumo_db['user'], 
							  $sumo_db['password'], 
							  $sumo_db['name'] );
							 
		if(!$SUMO['DB']->IsConnected()) $err = 'E00003S';
		break;
		
	case 'postgres':
		$SUMO['DB'] = ADONewConnection('postgres');
		$SUMO['DB']->Connect( $sumo_db['host'].':'.$sumo_db['port'], 
							  $sumo_db['user'], 
							  $sumo_db['password'], 
							  $sumo_db['name'] );
							 
		if(!$SUMO['DB']->IsConnected()) $err = 'E00003S';
		break;
		
	case 'sqlite':
		$SUMO['DB'] = ADONewConnection( 'sqlite://'.urlencode($sumo_db['path']."/".$sumo_db['name']) );
		
		if(!$SUMO['DB']) $err = 'E00003S';
		break;
}

// Database debug
if(SUMO_VERBOSE_ERRORS) $SUMO['DB']->debug = true;

?>