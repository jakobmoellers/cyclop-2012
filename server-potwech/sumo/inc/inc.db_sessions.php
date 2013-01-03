<?php
/**
 * SUMO: Database sessions
 *
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

$options['table'] = SUMO_TABLE_SESSIONS_STORE;
	
switch($sumo_db['type'])
{
	case 'mysql':
		ADOdb_Session::config($sumo_db['type'], 
							  $sumo_db['host'], 
							  $sumo_db['user'], 
							  $sumo_db['password'], 
							  $sumo_db['name'],
							  $options);
		break;
		
	case 'postgres':
		ADOdb_Session::config($sumo_db['type'], 
							  $sumo_db['host'], 
							  $sumo_db['user'], 
							  $sumo_db['password'], 
							  $sumo_db['name'],
							  $options);
		break;
		
	case 'sqlite':
		// NOT YET SUPPORTED BY ADODB
		break;
}
			  
ADOdb_Session::Persist(false);			
ADOdb_Session::filter(new ADODB_Encrypt_MD5()); // will compress and then encrypt the record in the database
//ADODB_Session::optimize(true);  
adodb_sess_open(false,false,false);
	
?>