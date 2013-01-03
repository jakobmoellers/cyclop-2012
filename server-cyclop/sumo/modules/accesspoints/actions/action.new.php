<?php
/**
 * SUMO MODULE: Accesspoints | New
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$id  = isset($_GET['id']) ? $_GET['id'] : '';
$tab = sumo_get_accesspoint_info($id, 'id', FALSE);

$checked['http_auth']    = $tab['http_auth']    ? " checked='checked'" : "";
$checked['filtering']    = $tab['filtering']    ? " checked='checked'" : "";
$checked['pwd_encrypt']  = $tab['pwd_encrypt']  ? " checked='checked'" : "";
$checked['change_pwd']   = $tab['change_pwd']   ? " checked='checked'" : "";
$checked['registration'] = $tab['registration'] ? " checked='checked'" : "";		

$form_name = 'AddAccesspoints'; 

$tpl['GET:ID']		 = $tab['id'];
$tpl['GET:AddForm']   	 = sumo_get_form_req('', 'add', 'id='.$tab['id']);
$tpl['PUT:Node'] 	 = sumo_put_node($tab['node']);
$tpl['PUT:Theme']  	 = sumo_put_themes($tab['theme']);
$tpl['PUT:Groups'] 	 = sumo_put_accesspoint_group($tab['id']);
$tpl['PUT:AddGroup'] 	 = sumo_add_accesspoint_group();		
$tpl['PUT:AddRegGroup']  = sumo_add_accesspoint_group('', 'reg_group');
$tpl['PUT:Name']   	 = sumo_put_accesspoint_name($form_name, sumo_get_accesspoint_name($tab['name']));	
$tpl['PUT:Path']	 = "<input type='text' size='35' name='path' value='".$tab['path']."' />";
$tpl['PUT:HTTPAuth']     = "<input type='checkbox' name='http_auth' ".$checked['http_auth']." onclick='if(document.$form_name.http_auth.checked==true && document.$form_name.pwd_encrypt.disabled==false){document.$form_name.pwd_encrypt.checked=false;}' />";
$tpl['PUT:Filtering']    = "<input type='checkbox' name='filtering' ".$checked['filtering']."  />";
$tpl['PUT:PwdEncrypt']   = "<input type='checkbox' name='pwd_encrypt' ".$checked['pwd_encrypt']." onclick='if(document.$form_name.pwd_encrypt.checked==true){document.$form_name.http_auth.checked=false;}' />";
$tpl['PUT:ChangePwd']    = "<input type='checkbox' name='change_pwd' ".$checked['change_pwd']." />";
$tpl['PUT:Registration'] = "<input type='checkbox' name='registration' ".$checked['registration']." "
		    	  ."onclick='if(document.$form_name.registration.checked==true){document.$form_name.reg_group.disabled=false;}else{document.$form_name.reg_group.disabled=true;}' />";
$tpl['LINK:Add']	 = sumo_get_action_icon("", "add");
$tpl['LINK:Edit']	 = sumo_get_action_icon("", "edit");
$tpl['LINK:Remove']    	 = sumo_get_action_icon("", "remove");
$tpl['BUTTON:Back']	 = "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"accesspoints\",\"?module=accesspoints&action=list\");'>";

?>