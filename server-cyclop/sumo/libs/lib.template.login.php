<?php

// Fix PNG images if client browser is Internet Explorer
$pngfix  = preg_match("/Internet Explorer/i", $SUMO['client']['browser']) ? "javascript:PNGFix()" : "";
$url_req = "";

// Filter URL query string
if($_SERVER['QUERY_STRING'])
{
	$_GET = sumo_array_combine(array_keys($_GET), sumo_array_filter(array_values($_GET)));
	$get_data = array_keys($_GET);
	$url_req  = "?";
	
	for($k=0; $k<count($get_data); $k++)
	{
		$url_req .= $get_data[$k]."=".$_GET[$get_data[$k]]."&";
	}
}


$tpl_array = array(

"LANG:User"			   => $sumo_lang_core['User'],
"LANG:Password"		   	   => $sumo_lang_core['Password'],
"LANG:RegistrationForm" 	   => $sumo_lang_core['RegistrationForm'],
"LANG:RegistrationInfo" 	   => $sumo_lang_core['RegistrationInfo'],
"LANG:EraseAccount"	   	   => $sumo_lang_core['EraseAccount'],
"LANG:EraseAccountInfo" 	   => $sumo_lang_core['EraseAccountInfo'],
"LANG:PasswordLost"	   	   => $sumo_lang_core['PasswordLost'],
"LANG:PasswordLostInfo" 	   => $sumo_lang_core['PasswordLostInfo'],
"LANG:ConfirmRegistration" 	   => $sumo_lang_core['ConfirmRegistration'],
"LANG:ConfirmRegistrationInfo"	   => $sumo_lang_core['ConfirmRegistrationInfo'],
"LANG:ConfirmEraseAccount" 	   => $sumo_lang_core['ConfirmEraseAccount'],
"LANG:ConfirmEraseAccountInfo" 	   => $sumo_lang_core['ConfirmEraseAccountInfo'],
"LANG:Email"		   	   => $sumo_lang_core['Email'],
"LANG:Language"   	  	   => $sumo_lang_core['Language'],

"LANG:RegUser"		  	   => "<font color='red'>*</font>&nbsp;".$sumo_lang_core['User'],
"LANG:RegEmail"		  	   => "<font color='red'>*</font>&nbsp;".$sumo_lang_core['Email'],
"LANG:RegPassword"	 	   => "<font color='red'>*</font>&nbsp;".$sumo_lang_core['Password'],
"LANG:RegRepPassword" 		   => "<font color='red'>*</font>&nbsp;".$sumo_lang_core['RepPassword'],

"LINK:Register"     		   => sumo_get_link_registration(),
"LINK:PasswordLost" 		   => sumo_get_link_pwdlost(),
"LINK:UnRegister"   		   => sumo_get_link_registration(0),

"GET:SumoVersion"      		   => SUMO_VERSION,
"GET:charset"			   => $SUMO['config']['server']['charset'],
"GET:PagePath"	  		   => $SUMO['page']['web_path'],
"GET:PageUrl"	   		   => $SUMO['page']['url'],
"GET:PageTheme"			   => $SUMO['page']['theme'],
"GET:ConfirmRegUser"  		   => $sumo_reg_data['reg_user'],
"GET:ConfirmRegEmail"  		   => $sumo_reg_data['reg_email'],
"GET:ConfirmLanguage" 		   => $sumo_reg_data['reg_language'],
"GET:PageName"		   	   => sumo_get_accesspoint_name($SUMO['page']['name'], $_COOKIE['language']),
"GET:ScriptLoginFocus" 	  	   => sumo_get_script_tag('login_focus.js'),
"GET:ScriptRegistrationFocus"  	   => sumo_get_script_tag('registration_focus.js'),
"GET:ScriptLogin"       	   => "<script language='javascript' type='text/javascript'>\n"
								 ."var sumo_theme='".$SUMO['page']['theme']."';\n"
								 ."</script>\n"
								 .sumo_get_script_tag('check_login.js')."\n"
								 .sumo_get_script_tag('sumo_common.js')."\n"
								 .sumo_get_script_tag('sumo_crypt.js')."\n"
								 .sumo_get_script_tag('sumo_gui.js')."\n",
"GET:ScriptResubmit"   		   => sumo_get_script_tag('resubmit.js'),
"GET:ScriptNoRightClick"	   => sumo_get_script_tag('no_right_click.js'),
"GET:OnLoad"			   => "onload='".$pngfix."'",
"GET:Note"			   => $sumo_lang_core["PoweredBy"]." <b>SUMO Access Manager</b> ".SUMO_VERSION."<br>&copy; Copyright 2003-".date("Y")." by <b>Basso Alberto</b><br>".$sumo_lang_core['ProjectPage']." <b><a href='http://sumoam.sourceforge.net' target='_blank'>http://sumoam.sourceforge.net</a></b>",
"GET:NoteShort"			   => $sumo_lang_core["PoweredBy"]."<br><b><a href='http://sumoam.sourceforge.net' target='_blank'>SUMO Access Manager</a></b>",
"GET:LoginForm"		   	   => "<form method='POST' name='SumoAuth' action='".$SUMO['page']['url'].$url_req."' onsubmit='check(document.SumoAuth);if((error==1)||(error==2)){return false;}else{sumo_pwd.value=hex_hmac_sha1(\"".$SUMO['connection']['security_string']."\",hex_sha1(sumo_pwd.value));}'>",
"GET:Message"	  	 	   => $sumo_message,
"GET:Redirect"			   => "<meta http-equiv='refresh' content='10; ".$SUMO['page']['url']."'>",
				 
"PUT:RegUser"	  		   => "<input type='text' size='16' name='reg_user' value='".$sumo_reg_data['reg_user']."' />"
					 ."<input type='hidden' name='reg_group' value='".$SUMO['page']['group']."' />",
"PUT:RegEmail"	   		   => "<input type='text' size='16' name='reg_email' value='".$sumo_reg_data['reg_email']."' />",
"PUT:RegPassword" 		   => "<input type='password' size='16' name='reg_password' autocomplete='off' />",
"PUT:RegRepPassword" 	  	   => "<input type='password' size='16' name='rep_reg_password' autocomplete='off' />",
"PUT:User"		   	   => "<input type='text' size='16' name='sumo_user' class='username' />",
"PUT:Password"	  		   => "<input type='password' size='16' name='sumo_pwd' class='password' autocomplete='off' />",
"PUT:LanguageLogin" 	  	   => sumo_get_available_languages(1, 1, $_COOKIE['language'], 'sumo_lang'),
"PUT:Language"   		   => sumo_get_available_languages(1),

"BUTTON:Submit"	  		   => "<input type='submit' class='button' value='".$sumo_lang_core["Ok"]."' />",
"BUTTON:BackLogin"  		   => "<form action='".$SUMO['page']['url']."' method='POST'><input type='submit' class='button' value='".$sumo_lang_core['Back']."'></form>",
"BUTTON:Back"  	   		   => "<input type='button' class='button' value='".$sumo_lang_core['Back']."' onclick='javascript:history.go(-1);' />"

);


// Disable password encryption (for LDAP server)
if(!$SUMO['page']['pwd_encrypt'] && !in_array($sumo_action, array('registration', 'regconfirmed')))
{
	$tpl_array['GET:ScriptSHA1'] = "";
	$tpl_array['GET:LoginForm']  = "<form name='SumoAuth' method='POST' action='".$SUMO['page']['url'].$url_req."' onsubmit='check(document.SumoAuth);if((error==1)||(error==2)){return false;}'>";
}



?>