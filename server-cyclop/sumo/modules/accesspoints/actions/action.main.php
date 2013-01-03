<?php
/**
 * SUMO MODULE: Accesspoints | Main
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

// Template Data
$tpl = array(
        	 'GET:ModuleIconAccesspoints'      => sumo_get_module_icon('accesspoints', 'list',  $language['AccessPoints'],   false),
		 'GET:ModuleIconAccesspointsStats' => sumo_get_module_icon('accesspoints', 'stats', $language['Statistics'], 	 false, 'accesspoints_stats'),
		 // delete old refresh window
		 'GET:WindowScripts' => 'sumo_unrefresh_window("accesspoints")'
	);

?>