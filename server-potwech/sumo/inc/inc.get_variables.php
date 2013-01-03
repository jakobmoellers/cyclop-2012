<?php
/**
 * SUMO COMMON: Get HTTP Data
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Console
 */


// HTTP Auth
if(!empty($SUMO['page']['http_auth'])) 
{
	if(empty($_SERVER['PHP_AUTH_USER']))     $_SERVER['PHP_AUTH_USER'] = ''; 
	if(empty($_SERVER['PHP_AUTH_PW']))       $_SERVER['PHP_AUTH_PW']   = ''; 
	if(empty($_SESSION['user']['user']))     $_SESSION['user']['user'] = strtolower($_SERVER['PHP_AUTH_USER']);
	if(empty($_SESSION['user']['password'])) $_SESSION['user']['password'] = $_SERVER['PHP_AUTH_PW'];
}
else 
{
	if(empty($_POST['sumo_user']))   	 $_POST['sumo_user'] = ''; 
	if(empty($_POST['sumo_pwd']))    	 $_POST['sumo_pwd']  = ''; 
	if(empty($_SESSION['user']['user']))     $_SESSION['user']['user']     = strtolower($_POST['sumo_user']); 
	if(empty($_SESSION['user']['password'])) $_SESSION['user']['password'] = $_POST['sumo_pwd'];
	
	unset($_POST['sumo_user']);
	unset($_POST['sumo_pwd']);
}

if(empty($_SESSION['security_string'])) $_SESSION['security_string'] = '';

$sumo_action = empty($_GET['sumo_action']) ? NULL : strtolower($_GET['sumo_action']);

// ...to get registration parameters
$sumo_reg_data['reg_user']         = empty($_POST['reg_user'])         ? '' : strtolower($_POST['reg_user']);
$sumo_reg_data['reg_email']        = empty($_POST['reg_email'])        ? '' : strtolower($_POST['reg_email']);
$sumo_reg_data['reg_password']     = empty($_POST['reg_password'])     ? '' : $_POST['reg_password'];
$sumo_reg_data['rep_reg_password'] = empty($_POST['rep_reg_password']) ? '' : $_POST['rep_reg_password'];
$sumo_reg_data['reg_code'] 	   = empty($_GET['reg_code']) 	       ? '' : $_GET['reg_code'];
$sumo_reg_data['reg_language']     = empty($_POST['reg_language'])     ? '' : strtolower($_POST['reg_language']);

?>