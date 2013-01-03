<?php
/**
 * SUMO MODULE: Users | View
 * 
 * @version    0.3.5
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_user_info($_GET['id'], 'id', false);
				
if(sumo_verify_permissions(4, $tab['group'])) 
{		
	$num_groups = count($tab['group']);
		
	if($num_groups > 1)
	{
		$update = sumo_update_user_group($_GET['id'], $_GET['group']);
		
		if($update)
			$tpl['MESSAGE:L'] = $language['UserUpdated'];
		else 
			$tpl['MESSAGE:H'] = $language['UserNotUpdated'];
	}
	else 
	{
		$validate[0] = false;
		
		$tpl['MESSAGE:M'] = $language['AtLeastOneGroup'];
	}			
	
	
	require "action.edit.php";	
}
else 
{
	$action_error = true;
	
	$tpl['MESSAGE:H'] = $language['AccessDenied'];
}

?>