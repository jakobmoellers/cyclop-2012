<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{{FILE:copyright.tpl}}
<html>

 <head>
  <title>SUMO {{GET:SumoVersion}} - {{LANG:EraseAccount}}</title>
  <link rel='StyleSheet' href='themes/{{GET:PageTheme}}/css/login.css' type='text/css'>
  <link rel="icon" href="themes/{{GET:PageTheme}}/images/favicon.png" type="image/png" />
  <meta http-equiv='content-type' content='text/html; charset={{GET:charset}}'>
 </head>

 <body>
	<div class='login'>
		<table class='login'>
		 <tr>
		  <td colspan='3'><img src='themes/{{GET:PageTheme}}/images/login_top.png' width='412' height='72' alt='SUMO'></td>
		 </tr>
		 <tr>
		  <td class='login-sx'><img src='themes/{{GET:PageTheme}}/images/blank.gif' width='15' height='1'></td>
		  <td class='login-middle'>	
			<form name='SumoEraseAccount' method='POST' action='?sumo_action=eraseaccountconfirmed'>
			  <font class='title'>{{LANG:ConfirmEraseAccount}}</font>
			  <br><br>
			  {{LANG:ConfirmEraseAccountInfo}}<br><br>
			  <b>{{LANG:Email}}</b>:&nbsp;&nbsp;{{GET:ConfirmRegEmail}}
			  <br><br><br><br>
			  {{BUTTON:Back}}&nbsp;&nbsp;{{BUTTON:Submit}}<br><br>	  		  
			  <input type='hidden' value='{{GET:ConfirmRegEmail}}' name='reg_email'>
			 </form>      
		  </td>
		  <td class='login-dx'><img src='themes/{{GET:PageTheme}}/images/blank.gif' width='16' height='1'></td>
		 </tr>
		 <tr>
		  <td colspan='3'><img src='themes/{{GET:PageTheme}}/images/login_bottom.png' width='412' height='35' alt='http://sumoam.sourceforge.net'></td>
		 </tr>
		</table>
	</div>
	<font class='note'>{{GET:Note}}</font>

 </body>
</html>