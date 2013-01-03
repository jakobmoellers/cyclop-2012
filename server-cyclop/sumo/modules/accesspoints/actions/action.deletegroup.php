<?php
/**
 * SUMO MODULE: Accesspoints | Delete group from AP
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_accesspoint_info($_GET['id'], 'id', FALSE);
			
if(sumo_verify_is_console($tab['path']) && $_GET['group'] == 'sumo')
{
	$tpl['MESSAGE:M'] = $language['CannotDeleteGroup'];	
}
else 
{
	$update = sumo_update_accesspoint_group($_GET['id'], $_GET['group']);

	if($update)
		$tpl['MESSAGE:L'] = $language['AccessPointGroupRemoved'];
	else 
		$tpl['MESSAGE:H'] = $language['AccessPointGroupNotRemoved'];
}		

require "action.edit.php";

?>