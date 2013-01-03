<?php
/**
 * SUMO DATABASE CONFIGURATION
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

/**
 * Supported databases via ADODB Drivers 
 * (http://adodb.sourceforge.net/):
 * 
 * MySQL  (version > 3.2.3):
 * 
 * $sumo_db['type']     = 'mysql';
 * $sumo_db['host']     = 'localhost';
 * $sumo_db['port']     = '3306';
 * $sumo_db['name']     = '<your_db>';
 * $sumo_db['user']     = '<your_user>';
 * $sumo_db['password'] = '<your_password>';
 */
/*
$sumo_db['type']     = 'mysql';
$sumo_db['host']     = 'localhost';
$sumo_db['port']     = '3306';
$sumo_db['name']     = '<your_db>';
$sumo_db['user']     = '<your_user>';
$sumo_db['password'] = '<your_password>';
*/

/**
 * PostgreSQL  (version > 8.1):
 * 
 * $sumo_db['type']     = 'postgres';
 * $sumo_db['host']     = 'localhost';
 * $sumo_db['port']     = 5432;
 * $sumo_db['name']     = '<your_db>';
 * $sumo_db['user']     = '<your_user>';
 * $sumo_db['password'] = '<your_password>';
 */

$sumo_db['type']     = 'postgres';
$sumo_db['host']     = 'localhost';
$sumo_db['port']     = 5432;
$sumo_db['name']     = 'potwech';
$sumo_db['user']     = 'superuser';
$sumo_db['password'] = '13parcel20';



/**
 * SQLite (version 2):
 * 
 * You can use the SQLite database file:
 * sumo/install/database_sqlite.db
 *
 * WARNING: change the database directory or file name because
 * it could be retrieved directly from the web.
 * 
 * $sumo_db['type'] = 'sqlite';
 * $sumo_db['name'] = 'database_sqlite.db';
 * $sumo_db['path'] = '/var/www/sumo/install/';
 */
/*
$sumo_db['type'] = 'sqlite';
$sumo_db['name'] = '<your_db>';
$sumo_db['path'] = '<your_absolute_path_to_sqlite_database>';
*/

?>
