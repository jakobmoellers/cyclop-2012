<?php
/**
 * SUMO: Load module configuration file
 *
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */


// Load module language
$module['file'] = array(
						'language' => SUMO_PATH.'/modules/'.$modules[$m].'/languages/lang.'.$_COOKIE['language'].'.php',
						'config'   => SUMO_PATH.'/modules/'.$modules[$m].'/module.xml'
					   );
					   
						
if(file_exists($module['file']['language'])) 
{
	require $module['file']['language'];
	
	// Create unique template library and language dictionary
	$language = array_merge($console['language'], $module['language']);
}
else 
{
	$language = $console['language'];	
}
	

$action = $_SESSION['action'] ? $_SESSION['action'] : 'main';
	
if(file_exists($module['file']['config'])) 
{ 
	$module['config'] = sumo_xmlize(file_get_contents($module['file']['config']));
}

?>