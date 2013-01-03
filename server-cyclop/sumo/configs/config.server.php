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

/**
 * If TRUE display any error.
 * Default is FALSE for security reasons
 */
define ('SUMO_VERBOSE_ERRORS', false);

/**
 * Enable session replica between nodes
 *
 * NOTE: useful if don't have a load balancer.
 *       Not running with SQLite database.
 */
define ('SUMO_SESSIONS_REPLICA', false);

/**
 * Store sessions on database
 *
 * NOTE: Not running with SQLite database.
 */
define ('SUMO_SESSIONS_DATABASE', true);

?>
