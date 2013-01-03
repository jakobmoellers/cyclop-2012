<?php
/**
 * SUMO MODULE: Users | Edit
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_user_info($_GET['id'], 'id', false);

if(sumo_verify_permissions(4, $tab['group'], null, false) || sumo_verify_permissions(false, false, $tab['username'], false) || $SUMO['user']['id'] == $tab['owner_id'])
{				
	$tpl['PUT:NewPassword']   = $tpl['PUT:NewPassword']   ? $tpl['PUT:NewPassword']   : '';
	$tpl['PUT:ReNewPassword'] = $tpl['PUT:ReNewPassword'] ? $tpl['PUT:ReNewPassword'] : '';

	// If id not exist				
	if(!$tab['username']) $tpl['MESSAGE:H'] = sumo_get_message('W00001C', $_GET['id']);	
	
	// get data source of user 
	$datasource = sumo_get_datasource_info($tab['datasource_id']);

	$tpl['PUT:Status'] 		= "";		
	$tpl['PUT:GroupLevel']	   	= sumo_put_user_grouplevel($_GET['id']);
	$tpl['PUT:AddGroupLevel']   	= $tab['username'] == 'sumo' ? "<input type='hidden' name='newgroup' value='sumo:7'>" : sumo_add_user_grouplevel('ModifyUsers', sumo_get_grouplevel($tab['usergroup'], true));
	$tpl['BUTTON:AddGroup']		= $tab['username'] == 'sumo' ? "" : "<input type='submit' class='button' value='".$language['AddGroup']."'>";
	$tpl['PUT:DataSourceType']  	= (($tab['username'] == 'sumo' || $tab['username'] == $SUMO['user']['user']) && $SUMO['user']['id'] != $tab['owner_id']) ? "<input name='datasource_id' type='hidden' value='".$datasource['id']."'>".$datasource['name'] : sumo_put_datasource($datasource['id']);
	$tpl['GET:LastLogin']   	= sumo_get_human_date($tab['last_login']);
	$tpl['GET:PwdUpdated']  	= sumo_get_human_date($tab['pwd_updated']);
	$tpl['GET:AccountCreated']  	= sumo_get_human_date($tab['created']);
	$tpl['GET:Modified']  		= sumo_get_human_date($tab['modified']);
	$tpl['GET:UserAccessPages'] 	= sumo_get_user_accesspoints($tab['id'], true);
	$tpl['GET:UpdateForm']		= sumo_get_form_req('', 'modify', 'id='.$tab['id'], 'POST', ' enctype="multipart/form-data"');
	$tpl['GET:Expire']     	 	= $tab['day_limit'] ? sumo_get_human_date($tab['day_limit'] * 86400 + $SUMO['server']['time'], false) : $language['Never'];
	$tpl['GET:User']        	= $tab['username']."<input type='hidden' name='user' value='".$tab['username']."'>";
	$tpl['IMG:User']   	 	= "<img src='services.php?module=users&service=image&cmd=GET_USER&id=".$tab['id']."' alt='".$tab['username']."' class='user'>";	
	$tpl['IMG:Language'] 	 	= "<img src='themes/".$SUMO['page']['theme']."/images/flags/".$tab['language'].".png' alt='".ucwords(sumo_get_string_languages($tab['language']))."' class='flag' id='userflag'>";
	$tpl['LINK:AddUser'] 		= sumo_get_action_icon("users", "new", "users.content", "?module=users&action=new&decoration=false");  
	$tpl['LINK:EditUser'] 		= sumo_get_action_icon("users", "edit");  
	
	
	// Verify image support for refection effects
    if(function_exists('imagecreatefromjpeg') && function_exists('imagecreatefrompng') && function_exists('imagecreatefromgif'))
    {
    	$tpl['IMG:User'] ."<br><img src='services.php?module=users&service=image&cmd=GET_USER_REFLECTION&id=".$tab['id']."&fade=6&height=30%'>";
    }

	// Create sub module (to hide or show only if necessary)
	// Set sub module visibility 
	// NOTE: Use REQUEST method because when delete a group
	// the command came from a link
	$visibility['AccountDetails']  = $_REQUEST['AccountDetails_visibility']  ? true : false;
	$visibility['SecurityOptions'] = $_REQUEST['SecurityOptions_visibility'] ? true : false;

	$tpl['LINK:AccountDetails']  = sumo_get_action_link('ModifyUsers', 'AccountDetails',  $visibility['AccountDetails']);
	$tpl['LINK:SecurityOptions'] = sumo_get_action_link('ModifyUsers', 'SecurityOptions', $visibility['SecurityOptions']);
       
	// Owner
	$owner = sumo_get_user_info($tab['owner_id'], 'id', false);
	
	$tpl['GET:AccountCreatedBy'] = htmlspecialchars($owner['lastname']." ".$owner['firstname'], ENT_QUOTES)." ("
								 ."<a href='javascript:sumo_ajax_get(\"users.content\",\"?module=users&action=view&id=".$owner['id']."&decoration=false\");'>"
								 .$owner['user']
								 ."</a>)";
	
	// Status
	$tpl['GET:Status'] = $tab['active'] ? "<font class='status-green'>".$language['Active']."</font>" : "<font class='status-red'>".$language['Suspended']."</font>";
		
	// If it's not current user		
	// Only for sumo group 		
	if($SUMO['user']['user'] != $tab['username'] && (in_array('sumo', $SUMO['user']['group']) || $SUMO['user']['id'] == $tab['owner_id'])) 
	{
		$tpl['PUT:DayLimit'] = "<input type='text' size='5' name='day_limit' value='".$tab['day_limit']."' />";
					
		// Change status
		if($tab['active'])
			$tpl['PUT:Status'] = "<select name='active'>\n<option value='1'>".$language['Enable']."</option>\n<option value='0'>".$language['Disable']."</option>\n</select>";
		else 
			$tpl['PUT:Status'] = "<select name='active'>\n<option value='0'>".$language['Disable']."</option>\n<option value='1'>".$language['Enable']."</option>\n</select>";
	}
	else 
	{
		$tpl['PUT:DayLimit'] = $tab['day_limit'] ? $tab['day_limit'] : $language['Unlimited'];
	}
	
	
	if($SUMO['user']['user'] == $tab['username'] || in_array('sumo', $SUMO['user']['group']) || $SUMO['user']['id'] == $tab['owner_id']) 
	{
		$tpl['PUT:FirstName'] = "<input type='text' size='35' name='firstname' value='".htmlspecialchars($tab['firstname'], ENT_QUOTES)."'>";
		$tpl['PUT:LastName']  = "<input type='text' size='35' name='lastname' value='".htmlspecialchars($tab['lastname'], ENT_QUOTES)."'>";		
		$tpl['PUT:Email']     = "<input type='text' size='35' name='email' value='".$tab['email']."'>";
		$tpl['PUT:UserImage'] = "<input type='button' onclick='javascript:window.open(\"?module=users&action=editimg&id=".$tab['id']."\",\"UserImage\",\"height=230,width=300,resizable=yes,scrollbars=yes,status=0,toolbar=0,location=0\");' class='button' value='".$language['Change']."'>";
		$tpl['PUT:Language']  = sumo_get_available_languages(1, 0, $tab['language']);
	}
	else 
	{
		$tpl['PUT:FirstName'] = htmlspecialchars($tab['firstname'], ENT_QUOTES);
		$tpl['PUT:LastName']  = htmlspecialchars($tab['lastname'], ENT_QUOTES);
		$tpl['PUT:Language']  = ucfirst(sumo_get_string_languages($tab['language']));
		$tpl['PUT:Email']     = "<a href='mailto:".$tab['email']."' title='Send e-mail'>".$tab['email']."</a>";
		$tpl['PUT:UserImage'] = "";
	}
	
	// to change IP
	if( in_array('sumo', $SUMO['user']['group']) || $SUMO['user']['id'] == $tab['owner_id'] )
		$tpl['PUT:IP'] = "<textarea rows='2' cols='40' name='ip'>".implode("; ", $tab['ip'])."</textarea>";
	else 
		$tpl['PUT:IP'] = implode("; ", $tab['ip']);
	

	// Remove
	if( (in_array('sumo', $SUMO['user']['group']) || sumo_verify_permissions(7, $tab['group']) || 
		$SUMO['user']['id'] == $tab['owner_id']) && $tab['username'] != 'sumo' && $tab['username'] != $SUMO['user']['user']
		)
	{
		$msg = sumo_get_simple_rand_string(4, "123456789");
		
		$tpl['LINK:Remove'] = "<div class='sub-module-icon' "
					."onmouseover='this.style.outline=\"1px solid #999999\";this.style.background=\"#FFFFFF\"' "
					."onmouseout='this.style.outline=\"\";this.style.background=\"\"'>"
					."<a href=\"javascript:sumo_show_message('msg$msg', '".htmlspecialchars(sumo_get_message('AreYouSureDelete', array($tab['username'], htmlspecialchars($tab['firstname'], ENT_QUOTES), htmlspecialchars($tab['lastname'],  ENT_QUOTES))))."', 
							 'h', 0,
							 '".base64_encode(sumo_get_form_req('', 'delete', 'id='.$tab['id']))."',
							 '".base64_encode('')."',
							 '".base64_encode("<input type='button' value='".$language['Cancel']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."',
							 '".base64_encode("<input type='submit' value='".$language['Ok']."' onclick='javascript:sumo_remove_window(\"msg$msg\");'>")."');\" class='button'>"
					."<img src='themes/".$SUMO['page']['theme']."/images/modules/users/delete.png' vspace='4'><br>"
					.$language['Remove']
					."</a>"
					."</div>";
	}
	else
		$tpl['LINK:Remove'] = sumo_get_action_icon("users", "delete");
	
        // on submit
	$submit = 'if(document.ModifyUsers.new_password.value!="")'
		.'{document.ModifyUsers.new_password.value=hex_sha1(document.ModifyUsers.new_password.value);'
		.'document.ModifyUsers.renew_password.value=hex_sha1(document.ModifyUsers.renew_password.value);}';
	        
	// ...to change password			
	if(($SUMO['user']['user'] == $tab['username'] || $SUMO['user']['id'] == $tab['owner_id'] || $SUMO['user']['user'] == 'sumo') && 
		(in_array($datasource['type'], array('SUMO', 'MySQLUsers', 'Joomla15'))) ) 
	{
		$tpl['PUT:NewPassword']   = "<input type='password' class='password' size='30' name='new_password' autocomplete='off' onKeyPress='if(event.keyCode == 13){$submit}'>";
		$tpl['PUT:ReNewPassword'] = "<input type='password' class='password' size='30' name='renew_password' autocomplete='off' onKeyPress='if(event.keyCode == 13){$submit}'>";
		$tpl['GET:UpdateForm']	  = sumo_get_form_req('', 'modify', 'id='.$tab['id']);
	}
	        	
	// Pwd expiration date
	if($SUMO['config']['accounts']['password']['life'] > 0 && $datasource['type'] == 'SUMO' && $tab['pwd_updated']) 
		$tpl['GET:PwdExpiration'] = sumo_get_human_date($tab['pwd_updated'] + $SUMO['config']['accounts']['password']['life'] * 86400);
	elseif(!$tab['pwd_updated'])
		$tpl['GET:PwdExpiration'] = '';
	else
		$tpl['GET:PwdExpiration'] = $language['CannotConrolPwd'];
        	
		
	if($datasource['type'] == 'SUMO')
		$tpl['BUTTON:Save'] = '<input type="submit" class="button-green" value="'.$language["Save"].'" onclick=\''.$submit.'\'>'; 
	else 
		$tpl['BUTTON:Save'] = '<input type="submit" class="button-green" value="'.$language["Save"].'">'; 

}
else 
{
	$action_error = true;
	
	$tpl['MESSAGE:H'] = $language['AccessDenied'];
}         

?>