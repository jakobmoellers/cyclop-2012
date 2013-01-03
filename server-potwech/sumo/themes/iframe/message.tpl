<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{{FILE:copyright.tpl}}
<html>

 <head>
  <title>SUMO {{GET:SumoVersion}} - {{GET:PageName}}</title>
  <link rel='StyleSheet' href='{{GET:PagePath}}/themes/{{GET:PageTheme}}/css/login.css' type='text/css'>
  <link rel="icon" href="{{GET:PagePath}}/themes/{{GET:PageTheme}}/images/favicon.png" type="image/png" />
  <meta http-equiv='content-type' content='text/html; charset={{GET:charset}}'>
  {{GET:Redirect}}
  {{GET:ScriptLogin}}
 </head>

<body {{GET:OnLoad}}>

	<div class='login'>

		<font class='title'>{{GET:PageName}}</font>
		<br><br><br>
		<center>
			<font class='error'>{{GET:Message}}</font>
			<br><br><br>
			{{BUTTON:BackLogin}}	
			<br><br>
		</center>
	  
	</div>
	<div class='note'>{{GET:NoteShort}}</div>
	{{GET:ScriptLoginFocus}}	
	
</body>
</html>