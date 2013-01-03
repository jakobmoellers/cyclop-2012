<?php
/**
 * SUMO MODULE: Accesspoints | View
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_accesspoint_info($_GET['id'], 'id', false);

// If id not exist				
if(!$tab['id']) 
{
	$tpl['MESSAGE:H'] = $language['AccessPointNotExist'];			
	unset($tab);
}
	
$checked['http_auth']    = $tab['http_auth']    ? " checked='checked'" : "";
$checked['filtering']    = $tab['filtering']    ? " checked='checked'" : "";
$checked['pwd_encrypt']  = $tab['pwd_encrypt']  ? " checked='checked'" : "";
$checked['change_pwd']   = $tab['change_pwd']   ? " checked='checked'" : "";
$checked['registration'] = $tab['registration'] ? " checked='checked'" : "";		
$checked['reg_group']    = $tab['registration'] ? true : false;

// Delete
if($SUMO['user']['group_level']['sumo'] > 4 && (!sumo_verify_is_console($tab['path']) || $tab['id'] != 1))
{
	$msg	= sumo_get_simple_rand_string(4, "123456789");
	$delete = "<div class='sub-module-icon' "
		 ."onmouseover='this.style.outline=\"1px solid #999999\";this.style.background=\"#FFFFFF\"' "
		 ."onmouseout='this.style.outline=\"\";this.style.background=\"\"'>"
		 ."<a href=\"javascript:"
		 ."sumo_show_message('msg$msg', '".htmlspecialchars(sumo_get_message('AreYouSureDelete', array($tab['path'], htmlspecialchars(sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']), ENT_QUOTES))))."', 
						'h', 0, 
						'".base64_encode(sumo_get_form_req('', 'delete', 'id='.$tab['id']))."',
						'".base64_encode('')."',
						'".base64_encode("<input type='button' value='".$language['Cancel']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."',
						'".base64_encode("<input type='submit' value='".$language['Ok']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."'
				);\">"
		 ."<img src='themes/".$SUMO['page']['theme']."/images/modules/accesspoints/remove.png' vspace='4'><br>"
		 .$language['Remove']
		 ."</a>"
		 ."</div>";
}
else {
	$delete = sumo_get_action_icon("", "remove");
}

$node = sumo_get_node_info($tab['node']);
    
$tpl['GET:ID']		 = $tab['id'];
$tpl['GET:RegGroup']   	 = $tab['reg_group']; 
$tpl['GET:Updated']	 = sumo_get_human_date($tab['updated']);
$tpl['GET:Created']	 = sumo_get_human_date($tab['created']);
$tpl['GET:Created']	 = sumo_get_human_date($tab['created']);
$tpl['GET:Node'] 	 = $node['name'];
$tpl['GET:Groups'] 	 = implode(", ", $tab['usergroup']);
$tpl['GET:RegGroup']     = $tab['reg_group'];								  
$tpl['GET:Theme']  	 = ucfirst($tab['theme']);
$tpl['GET:Name']   	 = sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']);
$tpl['GET:Filtering']    = "<input type='checkbox' name='filtering' ".$checked['filtering']." disabled />";
$tpl['GET:ChangePwd']    = "<input type='checkbox' name='change_pwd' ".$checked['change_pwd']." disabled />";
$tpl['GET:Registration'] = "<input type='checkbox' name='registration' ".$checked['registration']." disabled />";
$tpl['GET:Path']	 = "<input type='text' size='50' name='path' value='".$tab['path']."' disabled />";
$tpl['GET:HTTPAuth']     = "<input type='checkbox' name='http_auth' ".$checked['http_auth']." disabled />";
$tpl['GET:PwdEncrypt']   = "<input type='checkbox' name='pwd_encrypt' ".$checked['pwd_encrypt']." disabled />";
$tpl['LINK:Add']	 = sumo_verify_permissions(5, 'sumo') ? sumo_get_action_icon("", "add", "accesspoints.content", "?module=accesspoints&action=new&decoration=false") : sumo_get_action_icon("", "add");
$tpl['LINK:Edit']	 = sumo_verify_permissions(4, 'sumo') ? sumo_get_action_icon("", "edit", "accesspoints.content", "?module=accesspoints&action=edit&id=".$tab['id']."&decoration=false") : sumo_get_action_icon("", "edit");
$tpl['LINK:Remove']    	 = $delete;
$tpl['BUTTON:Back']	 = "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"accesspoints\",\"?module=accesspoints&action=list\");'>";

  
// Use REQUEST method because when delete a group on AP 
// the command came from a link
$visibility['SecurityOptions'] = $_REQUEST['SecurityOptions_visibility'] ? true : false;
$visibility['LayoutOptions']   = $_REQUEST['LayoutOptions_visibility']   ? true : false;

$tpl['LINK:SecurityOptions'] = sumo_get_action_link($form_name, 'SecurityOptions', $visibility['SecurityOptions']);
$tpl['LINK:LayoutOptions']   = sumo_get_action_link($form_name, 'LayoutOptions',   $visibility['LayoutOptions']);

// delete old refresh window and update
$tpl['GET:WindowScripts']   .= 'sumo_unrefresh_window("accesspoints");';

?>