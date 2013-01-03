<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{{FILE:copyright.tpl}}
<html>

 <head>
  <title>SUMO Access Manager - {{LANG:RegistrationForm}}</title>
  <link rel='StyleSheet' href='{{GET:PagePath}}/themes/{{GET:PageTheme}}/css/login.css' type='text/css'>
  <link rel="icon" href="{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/favicon.png" type="image/png" />
  <meta http-equiv='content-type' content='text/html; charset={{GET:charset}}'>
 </head>

 <body>

	<div class='login'>
		<table class='login'>
		 <tr>
		  <td colspan='3'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/login_top.png' width='412' height='72' alt='SUMO'></td>
		 </tr>
		 <tr>
		  <td class='login-sx'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/blank.gif' width='15' height='1'></td>
          <td class='login-middle'>
			<form name='SumoRegistration' method='POST' action='?sumo_action=regconfirmed'>
			  <font class='title'>{{LANG:ConfirmRegistration}}</font>
              <br><br> 
			  <div style='text-align:left;padding-left:10px;padding-bottom:15px'>{{LANG:ConfirmRegistrationInfo}}</div>
			  <div style='text-align:left;padding-right:30px;padding-bottom:15px'>
			  {{LANG:User}}:&nbsp;&nbsp;<b>{{GET:ConfirmRegUser}}</b><br><br>
			  {{LANG:Email}}:&nbsp;&nbsp;<b>{{GET:ConfirmRegEmail}}</b><br><br>
			  </div>
			  <center>
			  {{BUTTON:Back}}&nbsp;&nbsp;{{BUTTON:Submit}}
			  </center>
			  <br><br>   			  
			  <input type='hidden' value='{{GET:ConfirmRegUser}}'  name='reg_user'>              
			  <input type='hidden' value='{{GET:ConfirmRegEmail}}' name='reg_email'>
			  <input type='hidden' value='{{GET:ConfirmLanguage}}' name='reg_language'>
			 </form>      
		  </td>
		  <td class='login-dx'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/blank.gif' width='16' height='1'></td>
		 </tr>
		 <tr>
		  <td colspan='3'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/login_bottom.png' width='412' height='35' alt='http://sumoam.sourceforge.net'></td>
		 </tr>
		</table>
	</div>
	<font class='note'>{{GET:Note}}</font>

 </body>
</html>