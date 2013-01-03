<?php
/**
 * SUMO MODULE: Users | Erase
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_user_info($_GET['id'], 'id', FALSE);
	
if(sumo_verify_permissions(5, $tab['group'])) 
{			
	$delete = sumo_delete_user($_GET['id']);
	
	require "action.list.php";
	
	if($delete)
		$tpl['MESSAGE:L'] = sumo_get_message('UserDeleted', $tab['username']);
	else 
		$tpl['MESSAGE:H'] = sumo_get_message('UserNotDeleted', $tab['username']);
}
else 
{
	$action_error = true;
	
	$tpl['MESSAGE:H'] = $language['AccessDenied'];
}

?>