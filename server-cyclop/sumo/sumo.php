<?php
/**
 * SUMO ACCESS MANAGER
 * ----------------------------------------------------------------------
 * LICENSE
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * To read the license please visit
 * http://opensource.org/licenses/gpl-license.php
 * ----------------------------------------------------------------------
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

define('SUMO_PATH', dirname(__FILE__));

// Verify startup errors
$err = FALSE;

if(preg_match("/".basename(__FILE__)."/", $_SERVER['SCRIPT_NAME'])) $err = 'E00001S';
if(ini_get('register_globals')) $err = 'E00002S';
if(version_compare(PHP_VERSION, '5.0.0', '<')) $err = 'E00005S';

// ADODB Parameters
$ADODB_CACHE_DIR      = SUMO_PATH.'/tmp/database/';
$ADODB_ERROR_LOG_TYPE = 3;
//define(ADODB_ASSOC_CASE, 0); // force lower-case 
$ADODB_LANG           = $_COOKIE['language'] ? $_COOKIE['language'] : 'en';


require SUMO_PATH.'/configs/config.sumo.php';
require SUMO_PATH.'/configs/config.server.php';
require SUMO_PATH.'/configs/config.database.php';
require SUMO_PATH.'/libs/lib.core.php';
require SUMO_PATH.'/libs/lib.registration.php';
require SUMO_PATH.'/classes/class.sendmail.php';
require SUMO_PATH.'/applications/adodb/adodb.inc.php';
require SUMO_PATH.'/inc/inc.php_modules.php';
require SUMO_PATH.'/inc/inc.setup.php';


// Specific database settings
$ADODB_COUNTRECS = false;

switch ($sumo_db['type'])
{
    case 'postgres':
        // Force to count records on PostgreSQL
        // http://phplens.com/adodb/reference.varibles.adodb_countrecs.html
	$ADODB_COUNTRECS = true;
	break;
	
    case 'sqlite':
	// some exception for SQLite database
	if(SUMO_SESSIONS_DATABASE) $err = 'E00009S';
	if(SUMO_SESSIONS_REPLICA)  $err = 'E00010S';
	break;
}

// See also configs/config.server.php
if(SUMO_VERBOSE_ERRORS)
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(0);           // comment this line only for debug
    ini_set('display_errors', 0); // do not echo any ADODB errors
}


// Verify if is first installation
if(!file_exists(SUMO_PATH.'/.installed'))
{
    sumo_create_enviroenment();
}


// Database connection
require SUMO_PATH.'/inc/inc.db_connection.php';

// Display startup error then exit
if($err) require SUMO_PATH.'/inc/inc.startup_errors.php';

// Get configuration parameters
$SUMO = array_merge( $SUMO, sumo_get_config('server') );

// Unset magic_quotes_runtime - do not change!
set_magic_quotes_runtime(0);

// Start Session
if(SUMO_SESSIONS_DATABASE)
{
    require SUMO_PATH.'/applications/adodb/session/adodb-cryptsession2.php';
    require SUMO_PATH.'/inc/inc.db_sessions.php';
}
else
{
    // preserve session data on shared hosting  ;)
    session_save_path(SUMO_PATH."/tmp/sessions/");
}

//Overwrite session timeout written in php.ini file
ini_set('session.gc_maxlifetime', $SUMO['config']['sessions']['timeout']);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 	  1);


session_name("SUMO");
session_start();

$SUMO['client'] = sumo_get_client_info();
$SUMO['server'] = sumo_get_server_info();
$SUMO['page']   = sumo_get_accesspoint_info();

$sumo_db = NULL;

// ON-LINE DEMO
//$SUMO['DB']->Execute("UPDATE ".SUMO_TABLE_USERS." SET password='89e495e7941cf9e40e6980d14a16bf023ccd4c91' WHERE username='demo'");


// Save original input data from page
if(!$SUMO['page']['filtering'])
{
    $_OLD_GET    = $_GET;
    $_OLD_POST   = $_POST;
    $_OLD_COOKIE = $_COOKIE;
}


if(!sumo_verify_is_today())
{
    sumo_update_day_limit();
    sumo_write_today();
    sumo_delete_old_users_temp();
    sumo_delete_old_sessions();
    sumo_delete_old_connections();
    sumo_delete_old_log();
    sumo_optimize_db();
    sumo_optimize_hits_counter();
}

// Auto optimize Sumo database
if(sumo_hits_count($SUMO['config']['database']['optimize_hits'], TRUE))
{
    sumo_delete_old_users_temp();
    sumo_delete_old_sessions();
    sumo_delete_old_connections();
    sumo_delete_old_log();
    sumo_optimize_db();
}


// Filter all input data
$_GET    = sumo_array_combine(array_keys($_GET),    sumo_array_filter(array_values($_GET)));
$_POST   = sumo_array_combine(array_keys($_POST),   sumo_array_filter(array_values($_POST),   'POST'));
$_COOKIE = sumo_array_combine(array_keys($_COOKIE), sumo_array_filter(array_values($_COOKIE), 'COOKIE'));

// Get variables
require SUMO_PATH.'/inc/inc.get_variables.php';

$SUMO['user']       = sumo_get_user_info();
$SUMO['connection'] = sumo_get_connection_info();

// ...to remember 'security string' after login
if(empty($SUMO['connection']['security_string']))
{
    $SUMO['connection']['security_string'] = $_SESSION['security_string'];
}


// Define and Load language,
// if exist language cookie load language set by user
require SUMO_PATH.'/inc/inc.load_language.php';

$sumo_access = NULL;

if ($sumo_action)
    $sumo_access = strtoupper($sumo_action);
// Verify if remote IP it's disabled
elseif (sumo_get_banned_ip($SUMO['client']['ip']))
    $sumo_access = 'IPDISABLED';
// Verify if node it's valid
elseif (!sumo_verify_node())
	$sumo_access = 'NODEDISABLED';
// Verify if username and password exists
elseif (!$_SESSION['user']['user'] && !$_SESSION['user']['password'])
    $sumo_access = 'NULL';
else
{
    // Verify if user exist
    if (!$SUMO['user']['user'])
        $sumo_access='USERNOTEXIST';
    // Verify if user is active
    elseif (!$SUMO['user']['active'])
        $sumo_access='USERNOTACTIVE';
    // Cannot authenticate this user on encrypted AccessPoint password
	elseif ($SUMO['user']['datasource_type'] != 'SUMO' && $SUMO['page']['pwd_encrypt'])
		$sumo_access='CANNOTAUTHENTICATE';
    // Verify Password (only at login)
    elseif ($SUMO['user']['datasource_type'] == 'SUMO' && !$_SESSION['loggedin'] && !sumo_verify_password())
        $sumo_access='PASSWORDERROR';
    // Load datasource for external authentication
    elseif ($SUMO['user']['datasource_type'] != 'SUMO')
    {
    	require SUMO_PATH.'/inc/inc.datasources.php';
    }
    // Verify IP
    elseif (!empty($SUMO['user']['ip']) && !in_array($SUMO['client']['ip'], $SUMO['user']['ip']))
    	$sumo_access='IPDENIED';
    // Verify user group
    elseif (!sumo_verify_current_group_page())
        $sumo_access='GROUPDENIED';
    elseif (sumo_verify_expired_account())
        $sumo_access='ACCOUNTEXPIRED';
    else
    {
        // Verify if user already logged in and if
        // sessions is expired
        $sumo_session = sumo_get_session_info();
	$sumo_access  = 'CONTINUE';

        if (empty($sumo_session['username']))
            $sumo_access='LOGIN';
        // Verify session time
        elseif ($sumo_session['expire'] < $SUMO['server']['time'])
        {
            $sumo_access = ($SUMO['server']['time']-$sumo_session['expire'] < 900) ? 'SESSIONENDED' : 'LOGINSE';
        }
    }
}


// Switch action
require SUMO_PATH.'/inc/inc.switch_action.php';


// Update request and create connection
if($update_req)
{
    $SUMO['connection'] = sumo_get_connection_info();
	
    if (!$SUMO['connection'])
        sumo_create_connection();
    else
    {
        // Count errors requests
        if ($SUMO['connection']['requests'] < $SUMO['config']['security']['max_login_attempts'])
        {
            sumo_update_security_string();

            //...for refresh page
            if ($_SESSION['user']['password'] && $_SESSION['user']['user']) sumo_update_request();
        }
        else
        {
            // ...too much attempts
            sumo_delete_connection();
            sumo_delete_session();
            sumo_add_banned();
        }
    }
}


// Create SSO
if($sumo_access == 'LOGIN' && SUMO_SESSIONS_REPLICA)
{
    sumo_create_session_id();
}


// Display Login or Message box
if($sumo_access != 'CONTINUE' && $sumo_access != 'LOGIN')
{
    $SUMO['connection'] = sumo_get_connection_info();

    // HTTP Basic Authentication
    if(!empty($SUMO['page']['http_auth']))
    {
    	$sumo_template  = 'message';
    	$sumo_message   = $sumo_access == 'LOGOUT' ? sumo_get_message('I00006C') : sumo_get_message('W00100C');
    	$sumo_page_name = sumo_get_accesspoint_name($SUMO['page']['name'], $SUMO['config']['server']['language']);

    	header ('WWW-Authenticate: Basic realm="'.$sumo_page_name.'"');
    	header ('HTTP/1.0 401 Unauthorized');
    	header ('status: 401 unauthorized');
    	header ('Content/Type: text/html; charset='.SUMO_CHARSET);
    }

    // Load base Template Library
    $tpl_lib     = SUMO_PATH."/libs/lib.template.login.php";
    $tpl_lib_ext = SUMO_PATH."/libs/lib.template.login.".$SUMO['page']['theme'].".php";
    $tpl_file    = SUMO_PATH."/themes/".$SUMO['page']['theme']."/".$sumo_template.".tpl";

    if(sumo_verify_file($tpl_lib))  require $tpl_lib;
    if(file_exists($tpl_lib_ext))   require $tpl_lib_ext;
    if(sumo_verify_file($tpl_file)) $tpl_data = implode('', file($tpl_file));

    // SUMO Authentication
    echo sumo_process_template($tpl_data, $tpl_array, 0, $SUMO['page']['theme']);

    exit;
}

// Return original input data
if (!$SUMO['page']['filtering'])
{
    $_GET    = $_OLD_GET;
    $_POST   = $_OLD_POST;
    $_COOKIE = $_OLD_COOKIE;
}

?>