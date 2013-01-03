<?php
/**
 * SUMO MODULE: Users | Add
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
	
if(sumo_verify_permissions(5, $SUMO['user']['group'])) 
{		
	$tpl['PUT:User']           = "<input type='text' size='35' name='user' value='".$_POST['user']."'>";
	$tpl['PUT:FirstName']      = "<input type='text' size='35' name='firstname' value='".htmlspecialchars($_POST['firstname'], ENT_QUOTES)."'>";
	$tpl['PUT:LastName']       = "<input type='text' size='35' name='lastname' value='".htmlspecialchars($_POST['lastname'], ENT_QUOTES)."'>";
	$tpl['PUT:Email']          = "<input type='text' size='35' name='email' value='".$_POST['email']."'>";
	$tpl['PUT:DayLimit']       = "<input type='text' size='3' name='day_limit' value='".$SUMO['config']['accounts']['life']."'>";
    $tpl['PUT:NewPassword']    = "<input type='password' class='password' size='30' name='new_password' autocomplete='off'>";
	$tpl['PUT:ReNewPassword']  = "<input type='password' class='password' size='30' name='renew_password' autocomplete='off'>";
	$tpl['PUT:IP']             = "<textarea rows='2' cols='40' name='ip'>".$_POST['ip']."</textarea>";
	$tpl['PUT:AddGroupLevel']  = sumo_add_user_grouplevel('AddUsers');
	$tpl['PUT:Language']       = sumo_get_available_languages(1, 0, $_COOKIE['language']);
	$tpl['PUT:DataSourceType'] = sumo_put_datasource();                                                                
    $tpl['GET:AddForm']	  	   = sumo_get_form_req('', 
                                                   'add',
                                                   '',
                                                   'POST',
                                                   'onsubmit=\'if(document.AddUsers.new_password.value!=""){document.AddUsers.new_password.value=hex_sha1(document.AddUsers.new_password.value);'
                                                  .'document.AddUsers.renew_password.value=hex_sha1(document.AddUsers.renew_password.value);}\'');
	// Status
	$tpl['GET:Status'] = "<font class='status-green'>".$language['Active']."</font>";
	$tpl['IMG:Status'] = "<img src='themes/".$SUMO['page']['theme']."/images/modules/users/user_on.gif' align='middle'>";
	$tpl['PUT:Status'] = "<select name='active'>\n<option value='1'>".$language['Enable']."</option>\n<option value='0'>".$language['Disable']."</option>\n</select>";
						
	// Pwd expiration date
	if($SUMO['config']['accounts']['password']['life'] > 0) $tpl['GET:PwdExpiration'] = sumo_get_human_date($SUMO['server']['time'] + $SUMO['config']['accounts']['password']['life'] * 86400);
	
	$tpl['LINK:AddUser']  = sumo_get_action_icon("users", "new");  
	$tpl['LINK:EditUser'] = sumo_get_action_icon("users", "edit");  
	$tpl['LINK:Remove']   = sumo_get_action_icon("users", "delete");
}
else 
{
	$action_error = true;
	
	$tpl['MESSAGE:H'] = $language['AccessDenied'];
}

?>