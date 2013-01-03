<?php
/**
 * SUMO MODULE: Network | Edit Node
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_node_info($_GET['id'], 'id', FALSE);

$tpl['GET:UpdateForm']	= sumo_get_form_req('', 'modify_node', 'id='.$tab['id']);
$tpl['PUT:Protocol']	= sumo_put_node_protocol($tab['protocol']);
$tpl['PUT:NodeName']	= "<input type='text' size='25' name='name' value='".$tab['name']."'>";
$tpl['PUT:Host']	= "<input type='text' size='25' name='host' value='".$tab['host']."'>";
$tpl['PUT:Port']	= "<input type='text' size='7' name='port' value='".$tab['port']."'>";
$tpl['PUT:SumoPath']	= "<input type='text' size='25' name='sumo_path' value='".$tab['sumo_path']."'>";
$tpl['BUTTON:Back']	= "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_node&id=".$tab['id']."\");'>";
$tpl['LINK:Add']  	= sumo_verify_permissions(4, 'sumo') ? sumo_get_action_icon("network", "add_node", "network.content", "?module=network&action=new_node&decoration=false") : sumo_get_action_icon("", "add_node");
$tpl['LINK:Edit']	= sumo_get_action_icon("", "edit_node");
	    

// Change status
if($tab['active'])
{
	$tpl['GET:Status'] = "<font class='status-green'>".$language['Active']."</font>";
	$tpl['PUT:Status'] = "<select name='status'>\n<option value='1'>".$language['Enable']."</option>\n<option value='0'>".$language['Disable']."</option>\n</select>";
}
else 
{
	$tpl['GET:Status'] = "<font class='status-red'>".$language['Disabled']."</font>";
	$tpl['PUT:Status'] = "<select name='status'>\n<option value='0'>".$language['Disable']."</option>\n<option value='1'>".$language['Enable']."</option>\n</select>";
}	

// if it's current node		
if($tab['ip'] == $SUMO['server']['ip'])
{
	$tpl['MESSAGE:M']  = $language['NodeWarning'];
	$tpl['PUT:Host']   = $tab['ip']."<input type='hidden' name='host' value='".$tab['host']."'>";
	$tpl['PUT:Status'] = "";	
}


if(sumo_verify_permissions(7, 'sumo') && $tab['id'] > 1) 
{	
	$msg = sumo_get_simple_rand_string(4, "123456789");
	
	$tpl['LINK:Remove'] = "<div class='sub-module-icon' "
				."onmouseover='this.style.outline=\"1px solid #999999\";this.style.background=\"#FFFFFF\"' "
				."onmouseout='this.style.outline=\"\";this.style.background=\"\"'>"
				."<a href=\"javascript:"
				."sumo_show_message('msg$msg', '".htmlspecialchars(sumo_get_message('AreYouSureDeleteNode', $tab['name']))."', 
								 'h', 0, 
								 '".base64_encode(sumo_get_form_req('', 'erase_node', 'id='.$tab['id']))."',
								 '".base64_encode('')."',
								 '".base64_encode("<input type='button' value='".$language['Cancel']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."',
								 '".base64_encode("<input type='submit' value='".$language['Ok']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."'
								);\">"
				."<img src='themes/".$SUMO['page']['theme']."/images/modules/network/remove_node.png' vspace='4'><br>"
				.$language['Remove']
				."</a>"
				."</div>";
}
else
{
	$tpl['LINK:Remove'] = sumo_get_action_icon("", "remove_node");
}

?>