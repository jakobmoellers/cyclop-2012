<?php
/**
 * SUMO SERVER CONFIGURATION
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

define ('SUMO_VERSION', '0.5.0');
define ('SUMO_UPDATED', '1254786146');

/**
 * Database tables
 */
define ('SUMO_TABLE_CONFIGS', 	  	 'sumo_configs');
define ('SUMO_TABLE_USERS', 		 'sumo_users');
define ('SUMO_TABLE_USERS_TEMP', 	 'sumo_users_temp');
define ('SUMO_TABLE_USERS_IMAGES', 	 'sumo_users_images');
define ('SUMO_TABLE_GROUPS', 		 'sumo_groups');
define ('SUMO_TABLE_ACCESSPOINTS', 	 'sumo_accesspoints');
define ('SUMO_TABLE_ACCESSPOINTS_STATS', 'sumo_accesspoints_stats');
define ('SUMO_TABLE_BANNED', 		 'sumo_banned');
define ('SUMO_TABLE_CONNECTIONS', 	 'sumo_connections');
define ('SUMO_TABLE_SESSIONS', 		 'sumo_sessions');
define ('SUMO_TABLE_SESSIONS_STORE', 	 'sumo_sessions_store');
define ('SUMO_TABLE_DATASOURCES', 	 'sumo_datasources');
define ('SUMO_TABLE_NODES',		 'sumo_nodes');
define ('SUMO_TABLE_INTRANETIP', 	 'sumo_intranet_ip');
define ('SUMO_TABLE_IPTOCOUNTRY', 	 'sumo_iptocountry');
define ('SUMO_TABLE_LOG_ACCESS', 	 'sumo_log_access');
define ('SUMO_TABLE_LOG_ERRORS', 	 'sumo_log_errors');
define ('SUMO_TABLE_LOG_SYSTEM', 	 'sumo_log_system');

// Regular expression to include some characters
define ('SUMO_REGEXP_ALLOWED_CHARS', 'àôďḟëšơßăřțňāķŝỳņĺħṗóúěéçẁċõṡøģŧșėĉśîűćęŵṫūčöèŷął'
                                    .'ųůşğļƒžẃḃåìïḋťŗäíŕêüòēñńĥĝđĵÿũŭưţýőâľẅżīãġṁōĩùįźá'
                                    .'ûþðæµĕÀÔĎḞËŠƠĂŘȚŇĀĶŜỲŅĹĦṖÓÚĚÉÇẀĊÕṠØĢŦȘĖĈŚÎŰĆĘŴṪŪČÖÈŶĄŁ'
                                    .'ŲŮŞĞĻƑŽẂḂÅÌÏḊŤŖÄÍŔÊÜÒĒÑŃĤĜĐĴŸŨŬƯŢÝŐÂĽẄŻĪÃĠṀŌĨÙĮŹÁÛÞÐÆĔ');

?>
