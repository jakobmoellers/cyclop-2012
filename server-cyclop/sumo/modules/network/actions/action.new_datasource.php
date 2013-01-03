<?php
/**
 * SUMO MODULE: Network | New Datasource
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */


$ds = sumo_get_available_datasources(true);
 
switch ($_POST['type']) 
{
	// DB
	case in_array($_POST['type'], $ds):
		$_POST['ldap_base'] = '';
		break;
		
	case in_array($type, array('LDAP', 'LDAPS', 'ADAM')):
		$_POST['db_name'] 			= '';
		$_POST['db_table'] 			= '';
		$_POST['db_field_user'] 	= '';
		$_POST['db_field_password'] = '';
		break;
	
	case 'Joomla15':
		$_POST['db_table'] 			= 'jos_users';
		$_POST['db_field_user'] 	= 'username';
		$_POST['db_field_password'] = 'password';
		break;
}

// Set default port
if($_POST['port'] == '') 
{
	switch ($_POST['type']) 
	{
		case 'MySQL': 	   $_POST['port'] = 3306; break;
		case 'MySQLUsers': $_POST['port'] = 3306; break;
		case 'Joomla15':   $_POST['port'] = 3306; break;
		case 'Postgres':   $_POST['port'] = 5432; break;
		case 'Oracle':     $_POST['port'] = 1521; break;
		case 'LDAP':       $_POST['port'] = 389;  break;
		case 'LDAPS':      $_POST['port'] = 636;  break;
		case 'ADAM':       $_POST['port'] = 389;  break;
	}
}


$form_name = ucfirst('add_datasource').ucfirst('network');

$tpl['PUT:DataSourceType']       = sumo_put_datasources_type($_POST['type'], $form_name);
$tpl['PUT:EncType']       	 = sumo_put_datasources_enctype($_POST['enctype'], $_POST['type']);
$tpl['PUT:DataSourceName']       = "<input type='text' size='35' name='name' value='".$_POST['name']."'>";
$tpl['PUT:DataSourceHost']       = "<input type='text' size='35' name='host' value='".$_POST['host']."'>";
$tpl['PUT:DataSourcePort']       = "<input type='text' size='7' name='port' value='".$_POST['port']."'>";
$tpl['PUT:DataSourceUser']       = "<input type='text' size='25' name='username' value='".$_POST['user']."'>";
$tpl['PUT:DataSourcePassword']   = "<input type='password' size='25' name='password' value='".$_POST['password']."'>";
$tpl['PUT:DataSourceRePassword'] = "<input type='password' size='25' name='re_password'>";
$tpl['PUT:DBName']     		= "<input type='text' size='25' name='db_name' value='".$_POST['db_name']."'>";
$tpl['PUT:DBTable']    		= "<input type='text' size='35' name='db_table' value='".$_POST['db_table']."'>";
$tpl['PUT:DBFieldUser']     	= "<input type='text' size='25' name='db_field_user' value='".$_POST['db_field_user']."'>";
$tpl['PUT:DBFieldPassword']  	= "<input type='text' size='25' name='db_field_password' value='".$_POST['db_field_password']."'>";
$tpl['PUT:LDAPBase']   		= "<input type='text' size='35' name='ldap_base' value='".$_POST['ldap_base']."'>";
$tpl['BUTTON:Cancel']	  	= "<input type='button' class='button-red' value='".$language["Cancel"]."' onclick='javascript:sumo_ajax_get(\"network\",\"?module=network&action=dlist\");'>";
$tpl['GET:Form']		= sumo_get_form_req('', 'add_datasource');
$tpl['LINK:Add']		= sumo_get_action_icon('', "add_datasource");
$tpl['LINK:Edit']      		= sumo_get_action_icon('', "edit_datasource");
$tpl['LINK:Remove']    		= sumo_get_action_icon('', "remove_datasource");


//$visibility['DatabaseOptions']   = $_POST['DatabaseOptions_visibility'] ? true : false;
//$visibility['LDAPOptions']       = $_POST['LDAPOptions_visibility']     ? true : false;
$visibility['DatabaseOptions']   = $_POST['DatabaseOptions_visibility'] ? 1 : 0;
$visibility['LDAPOptions']       = $_POST['LDAPOptions_visibility']     ? 1 : 0;

$tpl['LINK:DatabaseOptions'] = '<div onclick=\'javascript:ShowHideSubModule("network.add_datasource.DatabaseOptions");\'><input type="hidden" value="'.$visibility['DatabaseOptions'].'" name="DatabaseOptions_visibility"></div>'
							  .'<div style="visibility: hidden; position: absolute;" id="network.add_datasource.DatabaseOptions">';
$tpl['LINK:LDAPOptions']     = '<div onclick=\'javascript:ShowHideSubModule("network.add_datasource.LDAPOptions");\'><input type="hidden" value="'.$visibility['LDAPOptions'].'" name="LDAPOptions_visibility"></div>'
							  .'<div style="visibility: hidden; position: absolute;" id="network.add_datasource.LDAPOptions">';

//$tpl['LINK:DatabaseOptions'] = sumo_get_action_link('network.add_datasource', 'DatabaseOptions', $visibility['DatabaseOptions']);
//$tpl['LINK:LDAPOptions']     = sumo_get_action_link('network.add_datasource', 'LDAPOptions',     $visibility['LDAPOptions']);

// Bugfix
if($_POST['db_name']) $tpl['GET:WindowScripts'] = "setTimeout('ShowElement(\"network.add_datasource.DatabaseOptions\")',100);";

?>