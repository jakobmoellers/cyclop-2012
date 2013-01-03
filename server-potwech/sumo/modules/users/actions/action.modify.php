<?php
/**
 * SUMO MODULE: Users | View
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_user_info($_GET['id'], 'id', false);

if(sumo_verify_permissions(4, $tab['group'], null, false) || sumo_verify_permissions(false, false, $tab['username'], false) || $SUMO['user']['id'] == $tab['owner_id'])
{
	// Create group string to validate group
	for($g=0; $g<count($_POST['group_name']); $g++)
	{
		$group[$g] = $_POST['group_name'][$g].":".$_POST['group_level'][$g];
	}

	$_POST['group'] = sumo_get_normalized_group(implode(";", $group));

	// If new group exist add it
	if($_POST['newgroup']) $_POST['group'] = sumo_get_normalized_group($_POST['newgroup'].";".$_POST['group']);

	// password (SUMO, Joomla)
	switch($tab['datasource_type'])
	{
		case 'MySQLUsers': 
		case 'Joomla15': 
			$pwd_verify = 'new_password2'; break;
		default:
			$pwd_verify = 'new_password'; break;
	}

	
	$data = array(	array('id',        $_GET['id'],    1),
			array('username',  $_POST['user'], 1),
			array('name',      $_POST['firstname']),
			array('name',      $_POST['lastname']),
			array('active',    $_POST['active']),
			array('email',     $_POST['email']),
			array('language',  $_POST['language']),
			array('datasource_id', $_POST['datasource_id'], 1),
			array('usergroup', $_POST['group']),
			array('ip',        $_POST['ip']),
			array('day_limit', $_POST['day_limit']),
			array($pwd_verify, array($_POST['new_password'], $_POST['renew_password'])));

	$validate = sumo_validate_data($data, true);

	// verify if current user is sumo to change administrator account
	if($_POST['user'] == 'sumo' && $SUMO['user']['user'] != 'sumo')
	{
		$validate = array(false, $language['CannotModifyAccount']);
	}


	// Verify submittedd groups with current user group
	if($validate[0])
	{
		$submitted_group_level = sumo_get_grouplevel($_POST['group']);
		$submitted_group       = sumo_get_grouplevel($_POST['group'], true);
		$available_group       = sumo_get_available_group();

		for($g=0; $g<count($submitted_group); $g++)
		{
			if(!in_array($submitted_group[$g], $available_group) && $submitted_group[$g])
			{
				$validate = array(false, sumo_get_message('GroupNotAvailable', $submitted_group[$g]));
				break;
			}

			if(!in_array('sumo', $SUMO['user']['group']) || $submitted_group[$g] == 'sumo')
			{
				if($SUMO['user']['group_level'][$submitted_group[$g]] < $submitted_group_level[$submitted_group[$g]] ||
				   $SUMO['user']['group_level'][$submitted_group[$g]] < $tab['group_level'][$submitted_group[$g]])
				   {
					$submitted_group_level[$submitted_group[$g]] = $tab['group_level'][$submitted_group[$g]];
				}

				// User can't change his group level
				if($_GET['id'] == $SUMO['user']['id'] &&
				   $submitted_group_level[$submitted_group[$g]] != $SUMO['user']['group_level'][$submitted_group[$g]])
				{
					$validate = array(false, sumo_get_message('WrongLevel', $submitted_group_level[$submitted_group[$g]]));
				}
			}
		}
	}


	if(!$validate[0])
	{
		$tpl['MESSAGE:H'] = $language['UserNotUpdated'].": ".$validate[1];
	}
	else {

		$update = sumo_update_user_data(array('id' 	  => $_GET['id'],
						'username' 	  => $_POST['user'],
						'firstname' 	  => $_POST['firstname'],
						'lastname'  	  => $_POST['lastname'],
						'active'    	  => $_POST['active'],
						'email'     	  => $_POST['email'],
						'language'        => $_POST['language'],
						'datasource_id'   => $_POST['datasource_id'],
						'usergroup' 	  => $_POST['group'],
						'ip' 		  => $_POST['ip'],
						'day_limit' 	  => $_POST['day_limit'],
						'password'  	  => $_POST['new_password']));

		if($update)
			$tpl['MESSAGE:L'] = $language['UserUpdated'];
		else 
			$tpl['MESSAGE:H'] = $language['UserNotUpdated'];
	}


	require "action.edit.php";
}
else
{
	$action_error = true;

	$tpl['MESSAGE:H'] = $language['AccessDenied'];
}

?>