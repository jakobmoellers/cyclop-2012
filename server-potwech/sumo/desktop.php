<?php
/**
 * SUMO CONSOLE | Desktop
 *
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Console
 */

// Can't access this file directly!
if(!defined('SUMO_PATH')) $err = 'E00001S';

// Display startup error then exit
if ($err) require 'inc/inc.startup_errors.php';


// save and exit from app.
switch($_SESSION['action'])
{
	case 'logout':
		header("Location: ".$SUMO['page']['url']."?sumo_action=logout");
		break;
}


$desktop['file']['template'] = SUMO_PATH.'/themes/'.$SUMO['page']['theme'].'/desktop.tpl';

// if template destop not exist display error then exit
if(!sumo_verify_file($desktop['file']['template']))
{
	$err = 'E00004S';
	require SUMO_PATH.'/inc/inc.startup_errors.php';
}


// Desktop
$desktop['template'] = implode('', file($desktop['file']['template']));
$desktop['settings'] = sumo_get_console_settings();

$tpl		 			   = $console['template'];
$tpl['GET:Flags'] 		   = sumo_get_flags();
$tpl['GET:ModulesWindows'] = "<!-- WINDOWS -->\n";


/**
 * Define windows for all modules
 */
$num_modules = count($modules);

for($m=0; $m<$num_modules; $m++)
{
    $m_name = $modules[$m];
    $m_icon = $SUMO['page']['web_path'].'/themes/'.$SUMO['page']['theme'].'/images/modules/'.$m_name.'/icon.desktop.png';
    $m_conf = SUMO_PATH.'/modules/'.$m_name.'/module.xml';

    // Load module config	
	$config[$m_name] = sumo_xmlize(file_get_contents($m_conf));
		
    // Verify permissions
    $_level[$m_name] = $config[$m_name]['module']['@']['level'] ? $config[$m_name]['module']['@']['level'] : 0;
	$_group[$m_name] = $config[$m_name]['module']['@']['group'] ? $config[$m_name]['module']['@']['group'] : $SUMO['user']['group'];
	$_user[$m_name]  = $config[$m_name]['module']['@']['user']  ? $config[$m_name]['module']['@']['user']  : $SUMO['user']['user'];
	
	if(sumo_verify_permissions($_level[$m_name], $_group[$m_name], $_user[$m_name]))
	{
		// Icon
		$tpl['GET:ModuleIcon'.ucfirst($m_name)] = sumo_get_module_icon($m_name, '', $console['language'][$m_name]);
		
		// Window position
	    if(isset($desktop['settings'][$m_name]['xw']))
		{
			$sm[$m_name] = $desktop['settings'][$m_name]['s'];
		   	$xw[$m_name] = $desktop['settings'][$m_name]['xw'];
		   	$yw[$m_name] = $desktop['settings'][$m_name]['yw'];
		}
		else
		{
		   	$sm[$m_name] = 0;
		   	$xw[$m_name] = isset($xws) ? $xws+10 : 100;
		 	$yw[$m_name] = isset($yws) ? $yws+23 : 30;
	
			// reset windows positions
		 	if($yw[$m_name] > 400)
		 	{
		 		$xw[$m_name] = 100;
		 		$yw[$m_name] = 30;
		 	}
		 	
		 	$xws = $xw[$m_name];
			$yws = $yw[$m_name];
		
			sumo_save_window_settings($SUMO['user']['user'],
									  $m_name,
			  						  $xw[$m_name],
			   						  $yw[$m_name],
			   						  $sm[$m_name]);
		}
	
	    $tpl['GET:ModulesWindows'] .= "<div id='".$m_name."' class='window-start' "
									 ."style='left:".$xw[$m_name]."px;top:".$yw[$m_name]."px'>"
	                                 ."</div>\n";
		// Link menu
	    $tpl['LINK:'.ucfirst($m_name).'Module'] = sumo_get_module_link($m_name, '', $console['language'][$m_name]);	    
	}
	else
	{
	  	$tpl['GET:ModuleIcon'.ucfirst($m_name)] = "<!-- Access Denied to '$m_name' module -->";
	  	$tpl['LINK:'.ucfirst($m_name).'Module'] = "<!-- Access Denied to '$m_name' menu module -->";
	}
}


// Initialize windows
$tpl['GET:ModulesWindows'] .= "<!-- -->\n\n<script type='text/javascript'>\n<!--\n\tSET_DHTML(\n";

for($m=0; $m<$num_modules; $m++)
{
	$m_name = $modules[$m];
		
    // Verify permissions
	if(sumo_verify_permissions($_level[$m_name], $_group[$m_name], $_user[$m_name]))
	{
		$tpl['GET:ModulesWindows'] .= "\t\t'".$m_name."'"
									 ."+MAXOFFTOP+".($yw[$m_name]-18)
									 ."+MAXOFFLEFT+".$xw[$m_name]
									 //."+TRANSPARENT"
									 ."+SCROLL";
		
		if($m < $num_modules-1) $tpl['GET:ModulesWindows'] .= ",\n";
	}
}

$tpl['GET:ModulesWindows'] .= "\n\t);\n"
							 ."-->\n</script>\n";
//----------------



// Reload last window opened
for($m=0; $m<$num_modules; $m++)
{
	if($desktop['settings'][$modules[$m]]['s'])
   	{
   		$m_name = $modules[$m];
   		
   		// Verify permissions
		if(sumo_verify_permissions($_level[$m_name], $_group[$m_name], $_user[$m_name]))
		{	
  			$tpl['GET:ModulesWindows'] .= sumo_get_module_start($m_name, $desktop['settings'][$m_name]['action']);
		}
  	}
}
//----------------



// Splashscreen
$tpl['GET:Splashscreen'] = sumo_get_splashscreen();

// Display Desktop
echo sumo_process_template($desktop['template'], $tpl);

?>