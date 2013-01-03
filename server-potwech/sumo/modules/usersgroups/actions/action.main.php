<?php
/**
 * SUMO MODULE: Users & Groups | Main
 * 
 * @version    0.3.5
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tpl = array(
			 'GET:ModuleIconUsers'  => sumo_get_module_icon('users',  'list', $language['Users'],  false),
			 'GET:ModuleIconGroups' => sumo_get_module_icon('groups', 'list', $language['Groups'], false),
			 'GET:ModuleIconRelationship' => sumo_get_module_icon('relationship', 'group2users', $language['Relationship'], false)
			);

?>