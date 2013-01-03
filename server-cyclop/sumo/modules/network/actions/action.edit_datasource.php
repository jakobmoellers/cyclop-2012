<?php
/**
 * SUMO MODULE: Network | Edit Datasource
 * 
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
		
// Set default port
if($_POST['port'] == '') 
{
	switch ($_POST['type']) 
	{
		case 'MySQL':      $_POST['port'] = 3306; break;
		case 'MySQLUsers': $_POST['port'] = 3306; break;
		case 'Joomla15':   $_POST['port'] = 3306; break;
		case 'Postgres':   $_POST['port'] = 5432; break;
		case 'Oracle':     $_POST['port'] = 1521; break;
		case 'LDAP':       $_POST['port'] = 389;  break;
		case 'LDAPS':      $_POST['port'] = 636;  break;
		case 'ADAM':       $_POST['port'] = 389;  break;
	}
}
	
		
$tab = sumo_get_datasource_info($_GET['id'], FALSE);
$ds  = sumo_get_available_datasources(true);

$type = $_POST['type'] ? $_POST['type'] : $tab['type'];

switch ($type) 
{
	// DB
	case in_array($type, $ds):
		$_POST['ldap_base'] = '';
		break;
		
	case in_array($type, array('LDAP', 'LDAPS', 'ADAM')):
		$_POST['db_name'] 			= '';
		$_POST['db_table'] 			= '';
		$_POST['db_field_user'] 	= '';
		$_POST['db_field_password'] = '';
		break;
	/*
	case 'Joomla15':
		$_POST['ldap_base'] = '';
		//$_POST['db_table'] 		= 'jos_users';
		$_POST['db_field_user'] 	= 'username';
		$_POST['db_field_password'] = 'password';
		break;
	*/
	case ($type == 'GMail'):
		$tab['ldap_base'] 		  = '';
		$tab['port'] 			  = ''; 
		$tab['db_name'] 		  = '';
		$tab['db_table'] 		  = '';
		$tab['db_field_user'] 	  = '';
		$tab['db_field_password'] = '';
		break;
}
			

if($_GET['id'] == 1) $tpl['MESSAGE:M'] = $language['CannotModifyDataSource'];

$form_name = ucfirst('modify_datasource').ucfirst('network');

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

$tpl['GET:Form']		 = sumo_get_form_req('', 'modify_datasource', 'id='.$tab['id']);
$tpl['PUT:DataSourceType']       = sumo_put_datasources_type($type, $form_name);
$tpl['PUT:EncType']       	 = sumo_put_datasources_enctype($tab['enctype'], $type);
$tpl['PUT:DataSourceName']       = "<input type='text' size='35' name='name' value='".$tab['name']."'>";
$tpl['PUT:DataSourceHost']       = "<input type='text' size='35' name='host' value='".$tab['host']."'>";
$tpl['PUT:DataSourcePort']       = "<input type='text' size='7' name='port' value='".$tab['port']."'>";
$tpl['PUT:DataSourceUser']       = "<input type='text' size='25' name='username' value='".$tab['username']."'>";
$tpl['PUT:DataSourcePassword']   = "<input type='password' size='25' name='password' value='".$tab['password']."'>";
$tpl['PUT:DataSourceRePassword'] = "<input type='password' size='25' name='re_password' value='".$tab['password']."'>";
$tpl['PUT:DBName']     		 = "<input type='text' size='25' name='db_name' value='".$tab['db_name']."'>";
$tpl['PUT:DBTable']    		 = "<input type='text' size='35' name='db_table' value='".$tab['db_table']."'>";
$tpl['PUT:DBFieldUser']     	 = "<input type='text' size='25' name='db_field_user' value='".$tab['db_field_user']."'>";
$tpl['PUT:DBFieldPassword']  	 = "<input type='text' size='25' name='db_field_password' value='".$tab['db_field_password']."'>";
$tpl['PUT:LDAPBase']   		 = "<input type='text' size='35' name='ldap_base' value='".$tab['ldap_base']."'>";
$tpl['BUTTON:Cancel']	  	 = "<input type='button' class='button-red' value='".$language["Cancel"]."' onclick='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_datasource&id=".$tab['id']."\");'>";
$tpl['LINK:Add']		 = sumo_verify_permissions(4, 'sumo') ? sumo_get_action_icon("", "add_datasource", "network.content", "?module=network&action=new_datasource&decoration=false") : sumo_get_action_icon("", "add_datasource");
$tpl['LINK:Edit']		 = sumo_get_action_icon("", "edit_datasource");
$tpl['LINK:Remove']    		 = (sumo_verify_permissions(4, 'sumo') && $tab['id'] > 1) ? $delete : sumo_get_action_icon("", "remove_datasource");

/*
$visibility['DatabaseOptions']   = $tab['db_name']   ? true : false;
$visibility['LDAPOptions']       = $tab['ldap_base'] ? true : false;

$tpl['LINK:DatabaseOptions']     = sumo_get_action_link('network.modify_datasource', 'DatabaseOptions', $visibility['DatabaseOptions']);
$tpl['LINK:LDAPOptions']         = sumo_get_action_link('network.modify_datasource', 'LDAPOptions',     $visibility['LDAPOptions']);
*/
$visibility['DatabaseOptions'] = $_POST['DatabaseOptions_visibility'] ? 1 : 0;
$visibility['LDAPOptions']     = $_POST['LDAPOptions_visibility']     ? 1 : 0;

$tpl['LINK:DatabaseOptions'] = '<div onclick=\'javascript:ShowHideSubModule("network.modify_datasource.DatabaseOptions");\'><input type="hidden" value="'.$visibility['DatabaseOptions'].'" name="DatabaseOptions_visibility"></div>'
							  .'<div style="visibility: hidden; position: absolute;" id="network.modify_datasource.DatabaseOptions">';
$tpl['LINK:LDAPOptions']     = '<div onclick=\'javascript:ShowHideSubModule("network.modify_datasource.LDAPOptions");\'><input type="hidden" value="'.$visibility['LDAPOptions'].'" name="LDAPOptions_visibility"></div>'
							  .'<div style="visibility: hidden; position: absolute;" id="network.modify_datasource.LDAPOptions">';
							  
// Bugfix
$tpl['GET:WindowScripts'] = $tab['db_name'] ? "setTimeout('ShowElement(\"network.modify_datasource.DatabaseOptions\")',100);" : "";
				  
?>