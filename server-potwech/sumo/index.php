<?php
/**
* SUMO CONSOLE
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Console
 */

// Include SUMO Core
require 'sumo.php';
require 'libs/lib.console.php';

// Common classes
require 'classes/class.sql2excel.php';

$modules = sumo_get_available_modules();

// Set locale format
setlocale(LC_ALL, sumo_get_locale($_COOKIE['language']));


// Set variables
if(!isset($_GET['module'])) $_GET['module'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';

$_SESSION['module'] = in_array($_GET['module'], $modules)        ? $_GET['module'] : false;
$_SESSION['action'] = ereg('^[_a-z0-9]{3,32}$', $_GET['action']) ? $_GET['action'] : false;
//

$console['file'] = array(
			 'language'        => SUMO_PATH.'/languages/'.$_COOKIE['language'].'/lang.console.php',
			 'language_ext'    => SUMO_PATH.'/languages/'.$_COOKIE['language'].'/lang.console.'.$SUMO['page']['theme'].'.php',
			 'template'        => SUMO_PATH.'/themes/'.$SUMO['page']['theme'].'/message.tpl',
			 'library_ext'     => SUMO_PATH.'/libs/lib.console.'.$SUMO['page']['theme'].'.php',
			 'tpl_library'     => SUMO_PATH.'/libs/lib.template.console.php',
			 'tpl_library_ext' => SUMO_PATH.'/libs/lib.template.console.'.$SUMO['page']['theme'].'.php'
			);

// Load console language & template libraries
if(sumo_verify_file($console['file']['language']))    require $console['file']['language'];
if(sumo_verify_file($console['file']['tpl_library'])) require $console['file']['tpl_library'];

// Load optional libraries
if(file_exists($console['file']['library_ext']))      require $console['file']['library_ext'];
if(file_exists($console['file']['language_ext']))     require $console['file']['language_ext'];
if(file_exists($console['file']['tpl_library_ext']))  require $console['file']['tpl_library_ext'];


if($_SESSION['module'])
	require SUMO_PATH.'/module.php';
else
	require SUMO_PATH.'/desktop.php';

?>