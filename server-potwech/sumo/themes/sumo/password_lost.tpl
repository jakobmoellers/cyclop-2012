<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{{FILE:copyright.tpl}}
<html>

 <head>
  <title>SUMO Access Manager - {{LANG:PasswordLost}}</title>
  <link rel='StyleSheet' href='{{GET:PagePath}}/themes/{{GET:PageTheme}}/css/login.css' type='text/css'>
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
		<form name='SumoPasswordLost' method='POST' action='?sumo_action=pwdlostconfirmed'>
		  <font class='title'>{{LANG:PasswordLost}}</font><br><br>
		  {{LANG:PasswordLostInfo}}<br><br>
		  <font class='error'>{{GET:Message}}</font><br><br>
		  <b>{{LANG:RegEmail}}</b>&nbsp;&nbsp;{{PUT:RegEmail}}
		  <br><br><br><br>
		  <center>
		  {{BUTTON:Back}}&nbsp;&nbsp;{{BUTTON:Submit}}<br><br>
		  </center>	    
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