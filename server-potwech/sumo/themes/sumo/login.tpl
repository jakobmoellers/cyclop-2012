<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{{FILE:copyright.tpl}}
<html>

 <head>
  <title>SUMO Access Manager - {{GET:PageName}}</title>
  <link rel='StyleSheet' href='{{GET:PagePath}}/themes/{{GET:PageTheme}}/css/login.css' type='text/css'>
  <link rel='icon' href='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/favicon.png' type='image/png'>
  <meta http-equiv='content-type' content='text/html; charset={{GET:charset}}'>
  {{GET:ScriptLogin}}
 </head>
 
 <body {{GET:OnLoad}}>

	{{GET:ScriptNoRightClick}}
	<div class='login'>
	<table class='login'>
	 <tr>
	  <td colspan='3'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/login_top.png' width='412' height='65' alt='SUMO'></td>
	 </tr>
	 <tr>
	  <td class='login-sx'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/blank.gif' width='15' height='1' alt='-'></td>
	  <td class='login-middle'>	  
		<font class='title'>{{GET:PageName}}</font>	
		<br><br> 
        {{GET:LoginForm}}
        <br>
        <b>{{LANG:User}}</b>&nbsp;&nbsp;{{PUT:User}}
        <br><br>
        <b>{{LANG:Password}}</b>&nbsp;&nbsp;{{PUT:Password}}	 
        <br><br>
        {{LANG:Language}}&nbsp;&nbsp;{{PUT:LanguageLogin}}
        <br><br><br>
        {{BUTTON:Submit}}
        <br><br><br>
        {{LINK:Register}}&nbsp;&nbsp;&nbsp;{{LINK:UnRegister}}
        <br>       
        {{LINK:PasswordLost}}
        </form>  				 
		{{GET:ScriptLoginFocus}}	      
	  </td>
	  <td class='login-dx'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/blank.gif' width='16' height='1' alt='-'></td>
	 </tr>
	 <tr>
	  <td colspan='3'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/login_bottom.png' width='412' height='30' alt='http://sumoam.sourceforge.net'></td>
	 </tr>
	</table>
	</div>
	<font class='note'>{{GET:Note}}</font>

 </body>
</html>