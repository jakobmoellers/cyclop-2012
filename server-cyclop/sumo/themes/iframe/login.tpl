<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{{FILE:copyright.tpl}}
<html>

 <head>
  <title>SUMO {{GET:SumoVersion}} - {{GET:PageName}}</title>
  <link rel='StyleSheet' href='{{GET:PagePath}}/themes/{{GET:PageTheme}}/css/login.css' type='text/css'>
  <link rel='icon' href='{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/favicon.png' type='image/png'>
  <meta http-equiv='content-type' content='text/html; charset={{GET:charset}}'>
  {{GET:ScriptLogin}}
 </head>
 
 <body {{GET:OnLoad}}>

	{{GET:ScriptNoRightClick}}
	<div class='login'>

		<font class='title'>{{GET:PageName}}</font>	
		<br>
        {{GET:LoginForm}}
        <br>
        {{LANG:User}}&nbsp;&nbsp;{{PUT:User}}
        <br><br>
        {{LANG:Password}}&nbsp;&nbsp;{{PUT:Password}}	 
        <br><br>
        {{BUTTON:Submit}}
        <br>
        {{LINK:Register}}&nbsp;&nbsp;&nbsp;{{LINK:UnRegister}}
        <br>
        {{LINK:PasswordLost}}
        </form>
        <div class='note'>{{GET:NoteShort}}</div>
		{{GET:ScriptLoginFocus}}	      
	  
	</div>

 </body>
</html>