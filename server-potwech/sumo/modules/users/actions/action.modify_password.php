<?php
/**
 * SUMO MODULE: Users | Modify password
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

if($_POST['new_password'] != $_POST['renew_password'] && $_POST['new_password'] != "")
{
	$tpl['MESSAGE:M'] = $language['PwdMismatch'];
}
else 
{
	$data = array(
		'id'		=> $SUMO['user']['id'],
		'language'	=> $SUMO['user']['language'],
		'firstname'	=> $SUMO['user']['firstname'],
		'lastname'	=> $SUMO['user']['lastname'],
		'email'		=> $SUMO['user']['email'],
		'password' 	=> sha1($_POST['new_password']),
		'datasource_id' => 1,
		'active'	=> 1
	);
	
	$update = sumo_update_user_data($data);
	
	if($update)
	{
		$tpl['MESSAGE:L'] 	  = $language['PwdUpdated'];
		$tpl['GET:WindowScripts'] = 'sumo_remove_window("users");';
	}
	else
	{ 
		$tpl['MESSAGE:H'] = $language['PwdNotUpdated'];
	}
}

$_SESSION['action'] = 'edit_password';

require "action.edit_password.php";

?>
