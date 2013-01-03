<?php
/**
 * SUMO MODULE: Groups | Add Group
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$validate[0] = TRUE;
				
// verify group name
if(!sumo_validate_group_name(trim($_POST['groupname']))) $validate = array(FALSE, $language['InvalidGroupName']);
// verify group description
if($_POST['groupdesc'] && $validate[0]) 
{
	if(!sumo_validate_group_desc(trim($_POST['groupdesc']))) $validate = array(FALSE, $language['InvalidGroupDesc']);
}
// verify if group already exist
if($validate[0]) 
{
	if(sumo_verify_group_exist(trim($_POST['groupname']))) $validate = array(FALSE, sumo_get_message('GroupAlreadyExist', $_POST['groupname']));
}
		
if(!$validate[0]) 
{
	$tpl['MESSAGE:H'] = $language['GroupNotAdded'].": ".$validate[1];
}
else 
{ 										
	$insert = sumo_add_group(array('usergroup' => $_POST['groupname'],
								   'groupdesc' => $_POST['groupdesc']));
		
	if($insert)
	{
		$tpl['MESSAGE:L'] 		  = sumo_get_message('GroupAdded', $_POST['groupname']);
		$tpl['GET:WindowScripts'] = "sumo_ajax_get('groups.content','?module=groups&action=list&decoration=false');";
	}
	else 
		$tpl['MESSAGE:H'] = $language['GroupNotAdded']; 
}		


$_SESSION['action'] = 'new';
	
require "action.new.php";

?>