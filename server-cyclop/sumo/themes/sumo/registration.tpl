<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{{FILE:copyright.tpl}}
<html>

 <head>
  <title>SUMO Access Manager - {{LANG:RegistrationForm}}</title>
  <link rel='StyleSheet' href='{{GET:PagePath}}/themes/{{GET:PageTheme}}/css/login.css' type='text/css'>
  <link rel="icon" href="{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/favicon.png" type="image/png" />
  <meta http-equiv='content-type' content='text/html; charset={{GET:charset}}'>
  {{GET:ScriptLogin}}
 </head>

<body {{GET:OnLoad}}>

<div class='login'>
<table class='login'>
 <tr>
  <td colspan='3'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/login_top.png' width='412' height='65' alt='SUMO'></td>
 </tr>
 <tr>
  <td class='login-sx'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/blank.gif' width='15' height='1'></td>
  <td class='login-middle'>	
	<form name='SumoRegistration' method='POST' action='?sumo_action=confirmreg' 
		  onsubmit='document.SumoRegistration.reg_password.value=hex_sha1(document.SumoRegistration.reg_password.value);document.SumoRegistration.rep_reg_password.value=hex_sha1(document.SumoRegistration.rep_reg_password.value);'>
	  <font class='title'>{{LANG:RegistrationForm}}</font>
	  <br><br>
	  <div style='text-align:left;padding-left:10px'>{{LANG:RegistrationInfo}}</div>	  
	  <div style='text-align:right;padding-right:30px;padding-bottom:15px'>
	  <br><br><font class='error'>{{GET:Message}}</font><br><br>
	  <b>{{LANG:RegUser}}</b>&nbsp;&nbsp;{{PUT:RegUser}}<br><br>
	  <b>{{LANG:RegEmail}}</b>&nbsp;&nbsp;{{PUT:RegEmail}}<br><br>
	  <b>{{LANG:RegPassword}}</b>&nbsp;&nbsp;{{PUT:RegPassword}}<br><br>
	  <b>{{LANG:RegRepPassword}}</b>&nbsp;&nbsp;{{PUT:RegRepPassword}}	  
	  </div>
	  <center>{{BUTTON:Back}}&nbsp;&nbsp;{{BUTTON:Submit}}</center>
	</form>	      
  </td>
  <td class='login-dx'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/blank.gif' width='16' height='1'></td>
 </tr>
 <tr>
  <td colspan='3'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/login_bottom.png' width='412' height='30' alt='http://sumoam.sourceforge.net'></td>
 </tr>
</table>
</div>
<font class='note'>{{GET:Note}}</font>

</body>
</html>