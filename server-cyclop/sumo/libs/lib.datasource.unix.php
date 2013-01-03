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
 * Unix/Linux datasource authentication library
 */


/**
 * Check datasource connection
 *
 * @global resource $SUMO
 */
$sumo_verify_datasource_connection = create_function('$id=false', '

	if(!$_SESSION["ds_connect"][$id])  // ...to not retry any time!
	{
		$user = $_SERVER["USER"] ? $_SERVER["USER"] : exec("ps -ef | grep \" ".getmypid()." \"| grep -v grep | awk \'{print $1}\'");

		if($user == "root" && is_readable("/etc/shadow"))
			return true;
		else
			return false;
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
		$u = exec("egrep \"^{$SUMO["user"]["user"]}:\" /etc/shadow");
		$p = explode(":", $u);

		$SUMO["user"]["password"] = $p[1];

		$_SESSION["ds_connect"][$id] = sumo_verify_password();

		$SUMO["user"]["password"] = "";
	}

	return $_SESSION["ds_connect"][$id] ? true : false;
');

?>