<?php
/**
 * SUMO MODULE: Accesspoints | Edit
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

$form_name    = 'ModifyAccesspoints'; 
$is_console   = sumo_verify_is_console($tab['path']) ? true : false;
$path_console = $is_console ? $tab['path'] : sumo_get_rand_string(8); // bad solution

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


$tpl['GET:ID']		 = $tab['id'];
$tpl['GET:RegGroup']   	 = $tab['reg_group']; 
$tpl['GET:Updated']	 = sumo_get_human_date($tab['updated']);
$tpl['GET:Created']	 = sumo_get_human_date($tab['created']);
$tpl['GET:UpdateForm']   = sumo_get_form_req('', 'modify', 'id='.$tab['id']); 		
$tpl['GET:Created']	 = sumo_get_human_date($tab['created']);
$tpl['PUT:Node'] 	 = $tab['id'] > 1 ? sumo_put_node($tab['node']) : sumo_put_node($tab['node'], true);
$tpl['PUT:Groups'] 	 = sumo_put_accesspoint_group($tab['id']);
$tpl['PUT:AddGroup']     = sumo_add_accesspoint_group(sumo_get_grouplevel($tab['usergroup'], true));
$tpl['PUT:AddRegGroup']  = sumo_add_accesspoint_group($tab['reg_group'], 'reg_group', $checked['reg_group']);								  
$tpl['PUT:Theme']  	 = sumo_put_themes($tab['theme']);
$tpl['PUT:Name']   	 = sumo_put_accesspoint_name($form_name, sumo_get_accesspoint_name($tab['name']));
$tpl['PUT:Filtering']    = "<input type='checkbox' name='filtering' ".$checked['filtering'].">";
$tpl['PUT:ChangePwd']    = $is_console ? "<input type='checkbox' name='change_pwd' disabled ".$checked['change_pwd']." />" : "<input type='checkbox' name='change_pwd' ".$checked['change_pwd']." />";
$tpl['PUT:Registration'] = $is_console ? "<input type='checkbox' name='registration' disabled ".$checked['registration']." " : "<input type='checkbox' name='registration' ".$checked['registration']." "
					  ."onclick='if(document.$form_name.registration.checked==true){document.$form_name.reg_group.disabled=false;}else{document.$form_name.reg_group.disabled=true;}' />";
$tpl['PUT:Path']	 = $tab['id'] > 1 ? "<input type='text' size='50' name='path' value='".$tab['path']."' onchange='if(this.form.path.value!=\"$path_console\"){document.$form_name.filtering.disabled=false;}else{document.$form_name.filtering.disabled=true;}' />" : "<input type='hidden' name='path' value='".$tab['path']."'><input type='text' size='50' name='path2' value='".$tab['path']."' disabled>";
$tpl['PUT:HTTPAuth']     = "<input type='checkbox' name='http_auth' ".$checked['http_auth']." "
                          ."onclick='if(document.$form_name.http_auth.checked==true && document.$form_name.pwd_encrypt.disabled==false){document.$form_name.pwd_encrypt.checked=false;}' />";
$tpl['PUT:PwdEncrypt']   = "<input type='checkbox' name='pwd_encrypt' ".$checked['pwd_encrypt']." "
                          ."onclick='if(document.$form_name.pwd_encrypt.checked==true){document.$form_name.http_auth.checked=false;}' />";
$tpl['LINK:Add']	 = sumo_verify_permissions(5, 'sumo') ? sumo_get_action_icon("", "add", "accesspoints.content", "?module=accesspoints&action=new&decoration=false") : sumo_get_action_icon("", "add");
$tpl['LINK:Edit']	 = sumo_get_action_icon("", "edit");
$tpl['LINK:Remove']    	 = $delete;
$tpl['BUTTON:Back']	 = "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"accesspoints\",\"?module=accesspoints&action=view&id=".$tab['id']."\");'>";

  
// Use REQUEST method because when delete a group on AP 
// the command came from a link
$visibility['SecurityOptions'] = $_REQUEST['SecurityOptions_visibility'] ? true : false;
$visibility['LayoutOptions']   = $_REQUEST['LayoutOptions_visibility']   ? true : false;

$tpl['LINK:SecurityOptions'] = sumo_get_action_link($form_name, 'SecurityOptions', $visibility['SecurityOptions']);
$tpl['LINK:LayoutOptions']   = sumo_get_action_link($form_name, 'LayoutOptions',   $visibility['LayoutOptions']);
						  
?>