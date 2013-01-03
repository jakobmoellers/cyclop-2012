<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{{FILE:copyright.tpl}}
<html>

 <head>
  <title>SUMO Access Manager - {{GET:PageName}}</title>
  <link rel='StyleSheet' href='{{GET:PagePath}}/themes/{{GET:PageTheme}}/css/login.css' type='text/css'>
  <link rel="icon" href="{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/favicon.png" type="image/png" />
  <meta http-equiv='content-type' content='text/html; charset={{GET:charset}}'>
  {{GET:Redirect}}
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
		<font class='title'>{{GET:PageName}}</font>
		<br><br><br>
		<center>
			<font class='error'>{{GET:Message}}</font>
			<br><br><br>
			{{BUTTON:BackLogin}}	
			<br><br>
		</center>
	  </td>
	  <td class='login-dx'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/blank.gif' width='16' height='1'></td>
	 </tr>
	 <tr>
	  <td colspan='3'><img src='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/login_bottom.png' width='412' height='30' alt='http://sumoam.sourceforge.net'></td>
	 </tr>
	</table>
	</div>
	<font class='note'>{{GET:Note}}</font>
	{{GET:ScriptLoginFocus}}	
</body>
</html>