<?php
/**
 * SUMO MODULE: Accesspoints | Delete
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_accesspoint_info($_GET['id'], 'id');

if(!sumo_verify_is_console($tab['path']) || $tab['id'] != 1)
{								
	$delete = sumo_delete_accesspoint($_GET['id']);
	
	if($delete)
		$tpl['MESSAGE:L'] = $language['AccessPointDeleted'];
	else 
		$tpl['MESSAGE:H'] = $language['AccessPointNotDeleted'];
}
else 
{
	$tpl['MESSAGE:H'] = $language['CannotDeleteThis'];
}
	
require "action.list.php";

?>