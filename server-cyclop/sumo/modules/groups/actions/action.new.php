<?php
/**
 * SUMO MODULE: Groups | New Group
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tpl['GET:Message']     = "";	
$tpl['GET:AddForm']     = sumo_get_form_req('', 'add');
$tpl['PUT:GroupName']   = "<input type='text' size='35' name='groupname' />";
$tpl['PUT:GroupDesc']   = "<input type='text' size='35' name='groupdesc' />";
$tpl['LINK:Add']        = sumo_get_action_icon("", "add");  
$tpl['LINK:Edit']       = sumo_get_action_icon("", "edit");  
$tpl['LINK:Remove']     = sumo_get_action_icon("", "delete");  
	
?>