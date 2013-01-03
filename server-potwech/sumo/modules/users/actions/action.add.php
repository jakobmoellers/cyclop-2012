<?php
/**
 * SUMO MODULE: Users | Add
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

if(sumo_verify_permissions(5, $SUMO['user']['group']))
{
	$data = array(array('username',    	 $_POST['user'], 1),
				  array('name',      	 $_POST['firstname']),
				  array('name',      	 $_POST['lastname']),
				  array('active',    	 $_POST['active'], 1),
				  array('datasource_id', $_POST['datasource_id'], 1),
				  array('email',     	 $_POST['email']),
				  array('language',  	 $_POST['language']),
				  array('usergroup',     $_POST['newgroup'], 1),
				  array('ip',        	 $_POST['ip']),
				  array('day_limit', 	 $_POST['day_limit']),
				  array('new_password', array($_POST['new_password'], $_POST['renew_password'])));


	$validate = sumo_validate_data($data, TRUE);

	// verify if user already exist
	if(sumo_verify_user_exist($_POST['user'])) $validate = array(FALSE, sumo_get_message('W00028C', $_POST['user']));

	// verify if password is not null (for SUMO datasource)
	$ds = sumo_get_datasource_info($_POST['datasource_id']);
	if(!$_POST['new_password'] && $ds['type'] == 'SUMO') $validate = array(FALSE, $language['NoPasswordForSumoDS']);


	if($validate[0])
	{
		// Verify submittedd groups with current user group
		$available_group = sumo_get_available_group();
		$newgroup	     = explode(":", $_POST['newgroup']);

		if(!in_array($newgroup[0], $available_group))
		{
			$validate = array(FALSE, sumo_get_message('GroupNotAvailable', $newgroup[0]));
		}

		if(!in_array('sumo', $SUMO['user']['group']) || $newgroup[0] == 'sumo')
		{
			if($SUMO['user']['group_level'][$newgroup[0]] < $newgroup[1])
			{
				$validate = array(FALSE, sumo_get_message('WrongLevel', $newgroup[1]));
			}
		}
	}


	if(!$validate[0])
	{
		$tpl['MESSAGE:H'] = sumo_get_message('UserNotAdded', $_POST['user']).": ".$validate[1];
	}
	else
	{
		$insert = sumo_add_user(array('username' 	  => $_POST['user'],
									  'firstname' 	  => $_POST['firstname'],
									  'lastname'  	  => $_POST['lastname'],
									  'active' 	  	  => $_POST['active'],
									  'email' 	  	  => $_POST['email'],
									  'language'  	  => $_POST['language'],
									  'group' 	  	  => $_POST['newgroup'],
									  'ip' 		  	  => $_POST['ip'],
									  'day_limit' 	  => $_POST['day_limit'],
									  'password'  	  => $_POST['new_password'],
									  'datasource_id' => $_POST['datasource_id']));

		if($insert)
		{
			$tpl['MESSAGE:L']   = sumo_get_message('UserAdded', $_POST['user']);
			$tpl['BUTTON:Back'] = "<input type='button' class='button-red' value='".$language['Back']."' onClick='javascript:history.go(-2);'>";
			$tpl['GET:WindowScripts'] = "sumo_ajax_get('users.content','?module=users&action=list&decoration=false');";
		}
		else
			$tpl['MESSAGE:H'] = sumo_get_message('UserNotAdded', $_POST['user']);
	}
	
	
	$_SESSION['action'] = 'new';
	
	require "action.new.php";
}
else
{
	$action_error = true;

	$tpl['MESSAGE:H'] = $language['AccessDenied'];
}

?>