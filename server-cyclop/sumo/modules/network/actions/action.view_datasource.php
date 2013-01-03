<?php
/**
 * SUMO MODULE: Network | View Datasource
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
 
$tab = sumo_get_datasource_info($_GET['id'], FALSE);
			  	 
$msg	= sumo_get_simple_rand_string(4, "123456789");
$delete = "<div class='sub-module-icon' "
		 ."onmouseover='this.style.outline=\"1px solid #999999\";this.style.background=\"#FFFFFF\"' "
		 ."onmouseout='this.style.outline=\"\";this.style.background=\"\"'>"
		 ."<a href=\"javascript:"
		 ."sumo_show_message('msg$msg', '".htmlspecialchars(sumo_get_message('AreYouSureDeleteDataSource', $tab['name']))."', 
							 'h', 0, 
							 '".base64_encode(sumo_get_form_req('', 'erase_datasource', 'id='.$tab['id']))."',
							 '".base64_encode('')."',
							 '".base64_encode("<input type='button' value='".$language['Cancel']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."',
							 '".base64_encode("<input type='submit' value='".$language['Ok']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."'
							);\">"
		 ."<img src='themes/".$SUMO['page']['theme']."/images/modules/network/remove_datasource.png' vspace='4'><br>"
		 .$language['Remove']
		 ."</a>"
		 ."</div>";
				 
$tpl = array(
			 'GET:Theme'		  => $SUMO['page']['theme'],
			 'GET:MenuModule'	  => $tpl['GET:MenuModule'],
			 'GET:DataSourceName'     => $tab['name'],
			 'GET:DataSourceType'     => $language[$tab['type']],
			 'GET:DataSourceHost'     => $tab['host'],
			 'GET:DataSourcePort'     => $tab['port'],
			 'GET:DataSourceUser'     => $tab['username'],
			 'GET:DataSourcePassword' => $tab['password']!='' ? '*****' : '',
   			 'GET:DBName'     	  => $tab['db_name'],
			 'GET:DBTable'    	  => $tab['db_table'],
			 'GET:DBFieldUser'        => $tab['db_field_user'],
			 'GET:DBFieldPassword'    => $tab['db_field_password'],
			 'GET:EncType'    	  => $tab['enctype'],
			 'GET:LDAPBase'   	  => $tab['ldap_base'],
			 'LINK:Add'		  => sumo_verify_permissions(4, 'sumo') ? sumo_get_action_icon("network", "add_datasource", "network.content", "?module=network&action=new_datasource&decoration=false") : sumo_get_action_icon("", "add_datasource"),
			 'LINK:Edit'    	  => (sumo_verify_permissions(4, 'sumo') && $tab['id'] > 1) ? sumo_get_action_icon("network", "edit_datasource", "network.content", "?module=network&action=edit_datasource&id=".$tab['id']."&decoration=false") : sumo_get_action_icon("", "edit_datasource"),
			 'LINK:Remove'  	  => (sumo_verify_permissions(4, 'sumo') && $tab['id'] > 1) ? $delete : sumo_get_action_icon("", "remove_datasource"),
			 'BUTTON:Back'		  => "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"network.content\",\"?module=network&action=dlist&decoration=false\");'>"
			);
?>