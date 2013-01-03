<?php
/**
 * SUMO MODULE: Groups | Modify Group
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$validate[0] = TRUE;
									
// verify group name
if($_POST['groupname']) 
{
	if(!sumo_validate_group_name($_POST['groupname'])) $validate = array(FALSE, $language['InvalidGroupName']);
}

// verify group description
if($_POST['groupdesc'] && $validate[0]) 
{
	if(!sumo_validate_group_desc($_POST['groupdesc'])) $validate = array(FALSE, $language['InvalidGroupDesc']);
}
	
if(!$validate[0]) 
{
	$tpl['MESSAGE:H'] = $language['GroupNotModified'].": ".$validate[1];
}
else
{										
	$update = sumo_update_group_data(array('id'		   => $_POST['id'],
										   'usergroup' => $_POST['groupname'],
									   	   'groupdesc' => $_POST['groupdesc'],
									   	   'oldgroup'  => $_POST['oldgroup'])
									   	   );
    if($update)
    	$tpl['MESSAGE:L'] = sumo_get_message('GroupModified', $_POST['groupname']);
    else 
    	$tpl['MESSAGE:H'] = $language['GroupNotModified'];  
}		


require "action.edit.php";

?>