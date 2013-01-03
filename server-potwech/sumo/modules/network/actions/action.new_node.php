<?php
/**
 * SUMO MODULE: Network | New Node
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tpl['GET:AddForm']     = sumo_get_form_req('', 'add_node');
$tpl['PUT:NodeName']    = "<input type='text' size='25' name='name' value='".$_POST['name']."'>";
$tpl['PUT:Host']        = "<input type='text' size='25' name='host' value='".$_POST['host']."'>";
$tpl['PUT:Port']	= "<input type='text' size='7' name='port' value='".$_POST['port']."'>";
$tpl['PUT:Protocol']    = sumo_put_node_protocol($_POST['protocol']);
$tpl['PUT:SumoPath']    = "<input type='text' size='25' name='sumo_path' value='".$_POST['sumo_path']."'>";
$tpl['PUT:Status']      = "<select name='status'>\n<option value='0'>".$language['Disable']."</option>\n<option value='1'>".$language['Enable']."</option>\n</select>";
$tpl['LINK:Add']  	= sumo_get_action_icon("", "add_node");
$tpl['LINK:Edit']  	= sumo_get_action_icon("", "edit_node");
$tpl['LINK:Remove']     = sumo_get_action_icon("", "remove_node");
$tpl['BUTTON:Back']     = "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"network.content\",\"?module=network&action=nlist&decoration=false\");'>";

?>