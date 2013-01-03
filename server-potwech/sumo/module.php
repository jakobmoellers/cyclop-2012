<?php
/**
 * SUMO CONSOLE | Module
 *
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Console
 */

if(!defined('SUMO_PATH')) $err = 'E00001S';

if($err) require 'inc/inc.startup_errors.php'; // Display startup error then exit


// Control and load required libraries, templates, languages, configs and module
define('SUMO_PATH_MODULE', SUMO_PATH.'/modules/'.$_SESSION['module']);

// Load timers configuration
require SUMO_PATH.'/configs/config.timers.php';


$module['file'] = array(
						'language' => SUMO_PATH_MODULE.'/languages/lang.'.$_COOKIE['language'].'.php',
						'library'  => SUMO_PATH_MODULE.'/libraries/lib.common.php',
						'module'   => SUMO_PATH_MODULE.'/module.php',
						'config'   => SUMO_PATH_MODULE.'/module.xml'
					   );

// If exist load library functions and template lib module
if(@file_exists($module['file']['language'])) require $module['file']['language'];
if(@file_exists($module['file']['library']))  require $module['file']['library'];
if(@file_exists($module['file']['template'])) require $module['file']['template'];

// Create unique template, library and language dictionary
$language = isset($module['language']) ? array_merge($console['language'], $module['language']) : $console['language'];
$tpl	  = isset($module['template']) ? array_merge($console['template'], $module['template']) : $console['template'];

// Load module config
$module['config']['module']['@']['defaction'] = 'main';

if(sumo_verify_file($module['file']['config']))
{
	$module['config'] = sumo_xmlize(file_get_contents($module['file']['config']));
}


// Get action from user session
// if not exist try to get it from module.xml
$action = $_SESSION['action'] ? $_SESSION['action'] : $module['config']['module']['@']['defaction'];
$_SESSION['action'] = $action;


// Create Menu' tabs
$menus = $module['config']['module']['#']['menu'];

for($m=0; $m<sizeof($menus); $m++)
{
	$menu_name = $menus[$m]['@']['name'] ? $menus[$m]['@']['name'] : $action;
	$tabs 	   = $menus[$m]['#']['tab'];

	if($tabs)
	{
		for($l=0; $l<sizeof($tabs); $l++)
		{
			$menu_query  = isset($tabs[$l]['@']['query'])  ? $tabs[$l]['@']['query']  : "";
			$menu_module = isset($tabs[$l]['@']['module']) ? $tabs[$l]['@']['module'] : "";

			$menu[$menu_name][$l] = array(
										  'name'    => $language[$tabs[$l]['@']['name']],
										  'module'  => $menu_module,
										  'action'  => $tabs[$l]['@']['action'],
										  'actions' => explode(",", $tabs[$l]['@']['actions']),
										  'query'   => $menu_query
									 	 );
		}
	}
}

$tpl['GET:MenuModule'] = sumo_get_module_menu($menu[$action], $action);
//


// Get table parameters
$tables = $module['config']['module']['#']['table'];

for($l=0; $l<sizeof($tables); $l++)
{
	$name = $tables[$l]['@']['name'];

	$table['settings'][$name] = array(
									  'col'  => $tables[$l]['#']['default'][0]['#']['order'][0]['@']['col'],
									  'mode' => $tables[$l]['#']['default'][0]['#']['order'][0]['@']['mode'],
									  'rows' => $tables[$l]['#']['default'][0]['#']['rows'][0]['#']
									 );

	for($c=0; $c<sizeof($tables[$l]['#']['col']); $c++)
	{
		$visible  = 1;
		$sortable = 1;

		if(isset($tables[$l]['#']['col'][$c]['@']['visible']))
		{
			$visible = strtolower($tables[$l]['#']['col'][$c]['@']['visible'])=='false' ? 0 : 1;
		}

		if(isset($tables[$l]['#']['col'][$c]['@']['sortable']))
		{
			$sortable = strtolower($tables[$l]['#']['col'][$c]['@']['sortable'])=='false' ? 0 : 1;
		}

		$attributes = isset($tables[$l]['#']['col'][$c]['@']['attributes']) ? $tables[$l]['#']['col'][$c]['@']['attributes'] : "";

		$table['data'][$name][$c] = array(
										  'id'   	   => $tables[$l]['#']['col'][$c]['@']['id'],
										  'column' 	   => $tables[$l]['#']['col'][$c]['@']['name'],
										  'name' 	   => $language[$tables[$l]['#']['col'][$c]['@']['name']],
										  'attributes' => $attributes,
										  'visible'	   => $visible,
										  'sortable'   => $sortable
								 	 	 );
	}
}
//


// Get permission levels for _action_
$al = $module['config']['module']['#']['action'];
$ma  = array();

for($l=0; $l<sizeof($al); $l++)
{
	$name = $al[$l]['@']['name'];

	$al[$l]['@']['user']     = isset($al[$l]['@']['user'])     ? $al[$l]['@']['user']     : '';
	$al[$l]['@']['group']    = isset($al[$l]['@']['group'])    ? $al[$l]['@']['group']    : '';
	$al[$l]['@']['level']    = isset($al[$l]['@']['level'])    ? $al[$l]['@']['level']    : '';
	$al[$l]['@']['template'] = isset($al[$l]['@']['template']) ? $al[$l]['@']['template'] : '';
	$al[$l]['@']['maxwin']   = isset($al[$l]['@']['maxwin'])   ? $al[$l]['@']['maxwin']   : '';
	$al[$l]['@']['minwin']   = isset($al[$l]['@']['minwin'])   ? $al[$l]['@']['minwin']   : '';

	$ma[$name] = array(
						'user'     => $al[$l]['@']['user'],
						'group'    => explode(",", $al[$l]['@']['group']),
						'level'    => $al[$l]['@']['level'],
						'template' => $al[$l]['@']['template'],
						'maxwin'   => $al[$l]['@']['maxwin'],
						'minwin'   => $al[$l]['@']['minwin']
					  );
}
//


// Set template
$tpl_file = $ma[$action]['template'] ? $ma[$action]['template'] : $action;

$decoration   = true; // No window decoration if needed
$action_error = false;

$module['file']['action'] = SUMO_PATH_MODULE.'/actions/action.'.$action.'.php';

if(isset($_GET['decoration']))
{
	$decoration = $_GET['decoration'] == 'false' ? false : true;
}

// Verify if exist required action file
if(!file_exists($module['file']['action']))
{
	$tpl['MESSAGE:H'] = sumo_get_message('UnknowAction', htmlentities($action));
}
else
// Verify action permissions
if(!empty($ma[$action]['level']) && $action_error != true)
{
	$level = $ma[$action]['level'];
	$group = $ma[$action]['group'] ? $ma[$action]['group'] : $SUMO['user']['group'];
	$user  = $ma[$action]['user']  ? $ma[$action]['user']  : $SUMO['user']['user'];

	if(!sumo_verify_permissions($level, $group, $user))
	{
		$action_error = true;

		$tpl['MESSAGE:H'] = sumo_get_message('AccessDeniedDetails', array($user, $group, $level));
	}
}


if(!$action_error)
{
	// Load module file if exist
	if(file_exists($module['file']['module'])) require $module['file']['module'];
	
	// Load action file
	require $module['file']['action'];
}

// export data on file
// NOTE: no window is necessary, but not display permission error
if($action == 'export') 
{
	exit;
}


/**
 * Show Message
 */
$tpl['MESSAGE'] = ""; $l = 'l';

if($tpl['MESSAGE:H']) { $l = 'h'; $tpl['MESSAGE'] = $tpl['MESSAGE:H']; }
else
if($tpl['MESSAGE:M']) { $l = 'm'; $tpl['MESSAGE'] = $tpl['MESSAGE:M']; }
else
if($tpl['MESSAGE:L']) { $l = 'l'; $tpl['MESSAGE'] = $tpl['MESSAGE:L']; }

if($tpl['MESSAGE'])
{
	//$n = sumo_get_simple_rand_string(4, '123456789');
	$m = addslashes($tpl['MESSAGE']);
	$a = $tpl['MESSAGE:A'] == 1 || !isset($tpl['MESSAGE:A'])? 1 : 0;
	
	$tpl['MESSAGE'] = "sumo_show_message('', '$m', '$l', $a, 
										 '".base64_encode($tpl['MESSAGE:F'])."',
										 '".base64_encode($tpl['BUTTON:1'])."',
										 '".base64_encode($tpl['BUTTON:2'])."',
										 '".base64_encode($tpl['BUTTON:3'])."'
										 );";
}


if($action_error)
{
	echo "<script>"
		.$tpl['MESSAGE']
		."document.getElementById('".$_SESSION['module']."').innerHTML = '';"
		."</script>";
	exit;
}

// Show window
sumo_show_window('',
				 $language[$_SESSION['module']],
				 $tpl_file,
				 $tpl,
				 $decoration,
				 '',
				 $ma[$action]['minwin'],
				 $ma[$action]['maxwin']
				 );
?>