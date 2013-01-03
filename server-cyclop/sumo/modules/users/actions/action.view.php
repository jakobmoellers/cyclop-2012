<?php
/**
 * SUMO MODULE: Users | View
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_user_info($_GET['id'], 'id', FALSE);

if(sumo_verify_permissions(3, $tab['group']) || sumo_verify_permissions(FALSE, FALSE, $SUMO['user']['user']))
{			
	$datasource = sumo_get_datasource_info($tab['datasource_id'], FALSE);
	
	$tpl['GET:User']        	= $tab['username'];
	$tpl['GET:Email']       	= $tab['email'];
	$tpl['GET:DayLimit']    	= !$tab['day_limit'] ? $language['Unlimited'] : $tab['day_limit'];
	$tpl['GET:FirstName']     	= htmlspecialchars($tab['firstname'], ENT_QUOTES);
	$tpl['GET:LastName']    	= htmlspecialchars($tab['lastname'],  ENT_QUOTES);  		
	$tpl['GET:IP']          	= implode("; ", $tab['ip']);
	$tpl['GET:Language']    	= ucwords(sumo_get_string_languages($tab['language'])); 
	$tpl['GET:LastLogin']		= sumo_get_human_date($tab['last_login']);	
	$tpl['GET:AccountCreated']  	= sumo_get_human_date($tab['created']);		
	$tpl['GET:Modified']	    	= sumo_get_human_date($tab['modified']);
	$tpl['GET:UserAccessPages'] 	= sumo_get_user_accesspoints($tab['id'], TRUE);
	$tpl['GET:GroupLevel']	    	= sumo_get_user_grouplevel($tab['group_level']);
	$tpl['GET:Expire']     	 	= $tab['day_limit'] != NULL ? sumo_get_human_date($tab['day_limit'] * 86400 + $SUMO['server']['time'], FALSE) : $language['Never'];
	$tpl['GET:DataSourceType']  	= $datasource['name'] ? "<a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_datasource&id=".$datasource['id']."\");'>".$datasource['name']."</a>" : '';
	$tpl['GET:Email']       	= "<a href='mailto:".$tab['email']."' title='Send e-mail'>".$tab['email']."</a>";
	$tpl['LINK:AddUser'] 		= sumo_get_action_icon("", "new", "users.content", "?module=users&action=new&decoration=false");  
	$tpl['LINK:EditUser'] 		= sumo_get_action_icon("", "edit", "users.content", "?module=users&action=edit&id=".$tab['id']."&decoration=false");  
	
	$tpl['IMG:User']   	  	= "<img src='services.php?module=users&service=image&cmd=GET_USER&id=".$tab['id']."' alt='".$tab['username']."' class='user'>";
	$tpl['IMG:Language'] 	 	= "<img src='themes/".$SUMO['page']['theme']."/images/flags/".$tab['language'].".png' alt='".ucwords($tab['language'])."' class='flag'>";

	// Verify image support for refection effects
	if(function_exists('imagecreatefromjpeg') && function_exists('imagecreatefrompng') && function_exists('imagecreatefromgif'))
	{
	    $tpl['IMG:User'] ."<br><img src='services.php?module=users&service=image&cmd=GET_USER_REFLECTION&id=".$tab['id']."&fade=6&height=30%'>";
	}
    
    // Create sub module (to hide or show only if necessary)
    $tpl['LINK:AccountDetails']  = sumo_get_action_link('users.view', 'AccountDetails');
    $tpl['LINK:SecurityOptions'] = sumo_get_action_link('users.view', 'SecurityOptions');
       
	// Owner
	$owner = sumo_get_user_info($tab['owner_id'], 'id', FALSE);
	
	$tpl['GET:AccountCreatedBy'] = htmlspecialchars($owner['lastname']." ".$owner['firstname'], ENT_QUOTES)." ("
								 ."<a href='javascript:sumo_ajax_get(\"users.content\",\"?module=users&action=view&id=".$owner['id']."&decoration=false\");'>"
								 .$owner['user']
								 ."</a>)";
	
	// Status
	$tpl['GET:Status'] = $tab['active'] ? "<font class='status-green'>".$language['Active']."</font>" : "<font class='status-red'>".$language['Suspended']."</font>";
	
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
					'".base64_encode("<input type='submit' value='".$language['Ok']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."');\">"
					."<img src='themes/".$SUMO['page']['theme']."/images/modules/users/delete.png' vspace='4'><br>"
					.$language['Remove']
					."</a>"
					."</div>";
	}
	else
		$tpl['LINK:Remove'] = sumo_get_action_icon("users", "delete");
	
	// Pwd expiration date
	if($SUMO['config']['accounts']['password']['life'] > 0 && $datasource['type'] == 'SUMO' && $tab['pwd_updated']) 
	{
		$tpl['GET:PwdExpiration'] = sumo_get_human_date($tab['pwd_updated']+$SUMO['config']['accounts']['password']['life']*86400);
		$tpl['GET:PwdUpdated']    = sumo_get_human_date($tab['pwd_updated']);
	}
	else
	if(!$tab['pwd_updated'])
		$tpl['GET:PwdExpiration'] = $tpl['GET:PwdUpdated'] = '';
	else 
	{	
		$tpl['GET:PwdExpiration'] = $language['CannotConrolPwd'];
		$tpl['GET:PwdUpdated']    = sumo_get_human_date($tab['pwd_updated']);
	}
	
}
else 
{
	$action_error = true;
	
	$tpl['MESSAGE:H'] = $language['AccessDenied'];
}

?>