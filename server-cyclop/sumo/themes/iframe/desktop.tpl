<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
{{FILE:copyright.tpl}}
<html>
 <head>
  <title>SUMO {{GET:SumoVersion}} - {{GET:PageName}}</title>
  <link rel='StyleSheet' media='screen' type='text/css' href='themes/{{GET:PageTheme}}/css/style.css'>
  <link rel="icon" href="themes/{{GET:PageTheme}}/images/favicon.png" type="image/png">
  <meta http-equiv='content-type' content='text/html; charset={{GET:charset}}'>
	  
  {{GET:ScriptLibraries}}
  
 </head>
 
 <body {{GET:OnLoad}}> 

 {{GET:Splashscreen}}
 
 <div id='desktop' class='desktop'>
 
 {{GET:ScriptDragDrop}}
 
 <div id='ModulesWindows'>
 	{{GET:ModulesWindows}}
 </div>
  
 <!-- Menu Top -->
    <table class='menu-top'>
     <tr>
      <td valign='top' style='padding-top: 2px; padding-left: 5px'><img src='themes/{{GET:PageTheme}}/images/sumo_logo_menu.gif' alt='SUMO'></td>      
      <td valign='top' style='padding-top: 4px; padding-left: 15px; width: 100%'>
                  
       <div id='menu-top'>
        <ul>              
	      <li>{{LINK:Console}}</li>
          <li>{{LINK:Help}}</li>
          <li>{{LINK:LogOut}}</li>	      
        </ul>
       </div>
       
      </td>
      <td valign='top' style='padding-top: 4px; padding-left: 12px;'><img src='themes/{{GET:PageTheme}}/images/user.gif' alt='User'></td>
      <td valign='top' style='padding-top: 4px; padding-right: 12px;'>{{GET:UserName}}</td>
      <td class='clean'>{{LINK:Clean}}</td>
      <td class='flags'>{{GET:Flags}}</td>
      <td class='datetime'>{{GET:Date}}&nbsp;&nbsp;{{GET:Clock}}</td>
     </tr>
    </table>
    
    <div id='menuConsole' class='menu'>
     {{LINK:NetworkModule}}
     {{LINK:AccesspointsModule}}
     {{LINK:UsersgroupsModule}}
     {{LINK:SecurityModule}}
     {{LINK:SessionsModule}}
     {{LINK:SettingsModule}}
    </div>
 <!-- End Menu Top -->
		
	 {{GET:ModuleIconNetwork}}
	 {{GET:ModuleIconSessions}}
	 {{GET:ModuleIconSecurity}}
	 {{GET:ModuleIconAccesspoints}}
	 {{GET:ModuleIconUsersgroups}}
	 {{GET:ModuleIconSettings}}  
		    
	 {{GET:ScriptTooltip}}
	 
	 </div>

 </body>
</html>