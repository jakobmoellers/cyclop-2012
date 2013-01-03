<?php
/**
 * SUMO MODULE: Accesspoints | Add
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

// Create group string 
if($_POST['group']) $_POST['group'] = sumo_get_normalized_group(implode(";", $_POST['group']), TRUE);

$_POST['path'] = sumo_get_normalized_accesspoint($_POST['path']);
	
// If new group exist add it
if($_POST['newgroup']) $_POST['group'] = sumo_get_normalized_group($_POST['newgroup'].";".$_POST['group'], TRUE);


// If registration enabled require reg_group
$reg_group = $_POST['registration'] ? 1 : 0;


$data = array(array('node',      $_POST['node'], 		 1),	
			  array('name',      $_POST['name'],  		 1),			  
			  array('path',      $_POST['path'],  		 1),						  
			  array('usergroup', $_POST['group'], 		 1),
			  array('reg_group', $_POST['reg_group'], $reg_group),
			  array('boolean',   $_POST['http_auth'],    1),
			  array('boolean',   $_POST['filtering'],    1),
			  array('boolean',   $_POST['pwd_encrypt'],  1),
			  array('boolean',   $_POST['change_pwd'],   1),
			  array('boolean',   $_POST['registration'], 1),
			  array('theme',     $_POST['theme']));
			
$validate = sumo_validate_accesspoint_data($data, TRUE);
					
// verify if accesspoint already exist
if(sumo_verify_accesspoint_exist($_POST['node'], $_POST['path'])) $validate = array(FALSE, sumo_get_message('I07002C', $_POST['path']));
			
if(!$validate[0]) 
{
	$tpl['MESSAGE:H'] = $language['AccessPointNotAdded'].": ".$validate[1];
}
else 
{ 				
	$update = sumo_add_accesspoint(array('name' 		=> $_POST['name'],
										 'node' 		=> $_POST['node'],
										 'path' 		=> $_POST['path'],													 
										 'usergroup' 	=> $_POST['group'],
										 'reg_group' 	=> $_POST['reg_group'],
										 'http_auth' 	=> $_POST['http_auth'],
										 'filtering' 	=> $_POST['filtering'],
										 'pwd_encrypt'  => $_POST['pwd_encrypt'],
										 'change_pwd' 	=> $_POST['change_pwd'],
										 'registration' => $_POST['registration'],
										 'theme' 		=> $_POST['theme']));
	
	if($update)
	{
		$tpl['MESSAGE:L'] 		  = $language['AccessPointAdded'];
		$tpl['GET:WindowScripts'] = "sumo_ajax_get('accesspoints.content','?module=accesspoints&action=list&decoration=false');";
	}
	else
		$tpl['MESSAGE:H'] = $language['AccessPointNotAdded'];
}

$_SESSION['action'] = 'new';
	
require "action.new.php";
	
?>