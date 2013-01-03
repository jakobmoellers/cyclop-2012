<?php
/**
 * SUMO MODULE: Security | Main
 * 
 * @version    0.2.10
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tpl = array(
			  'GET:ModuleIconSecurityLog'    => sumo_get_module_icon('security', 'last_list', $language['LogManager'], false, 'log_manager'),
			  'GET:ModuleIconSecurityBanned' => sumo_get_module_icon('security', 'banned',    $language['BannedIP'],   false, 'banned_ip'),
			  'GET:WindowScripts'	 		 => 'sumo_unrefresh_window("security")'
			 );
?>