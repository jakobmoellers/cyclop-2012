
  {{FILE:module_menu.tpl}}
  <table class='tab'>
  	<td class="tab-icon" colspan='2'>

	 	<table>
	 		<tr>
	 			<td>{{LINK:EditSettings}}</td> 
	 		</tr>
	 	</table>
	 
	 </td> 
   
	<tr>	 
	 <td style='vertical-align: top; width: 500px'>  
                                              
     {{LINK:GenericOptions}}                                  
      <table class='sub-module'> 
       	   <tr>
	    <td class='data-title'>{{LANG:server.admin.name}}</td>
		<td class='data-record' colspan='3'>{{GET:server.admin.name}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:server.admin.email}}</td>
		<td class='data-record' colspan='3'>{{GET:server.admin.email}}</td> 
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:server.language}}</td>
		<td class='data-record' colspan='3'>{{IMG:server.language}} {{GET:server.language}}</td>   
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:server.date_format}}</td>
		<td class='data-record'>{{GET:server.date_format}}</td> 
		<td class='data-title'>{{LANG:server.time_format}}</td>
		<td class='data-record'>{{GET:server.time_format}}</td> 
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:iptocountry.enabled}}</td>
		<td class='data-record' colspan='3'>{{GET:iptocountry.enabled}}</td> 
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:database.optimize_hits}}</td>
		<td class='data-record' colspan='3'>{{GET:database.optimize_hits}}&nbsp;&nbsp;{{TIP:database.optimize_hits_desc}}</td> 
	   </tr>
      </table> 
     </div>      
     
     {{LINK:ConsoleOptions}}
      <table class='sub-module'>
	    <tr>
	        <td class='data-title'>{{LANG:console.tip}}</td>
		<td class='data-record' style='width: 100%'>{{GET:console.tip}}</td> 
	    </tr>
      </table> 
     </div>  
     
     {{LINK:SecurityOptions}}
      <table class='sub-module'>        
       <tr>
	    <td class='data-title'>{{LANG:security.max_login_attempts}}</td>
		<td class='data-record' style='width: 100%'>{{GET:security.max_login_attempts}}</td> 
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:security.banned_time}}</td>
		<td class='data-record'>{{GET:security.banned_time}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:security.access_violations}}</td>
		<td class='data-record'>{{GET:security.access_violations}}</td> 
	   </tr>
      </table>
     </div>     
     
     {{LINK:AccountsOptions}}
      <table class='sub-module'>
       <tr>
	    <td class='data-title'>{{LANG:accounts.life}}</td>
		<td class='data-record' style='width: 100%'>{{GET:accounts.life}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:accounts.password.life}}</td>
		<td class='data-record'>{{GET:accounts.password.life}}</td> 
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:accounts.registration.enabled}}</td>
		<td class='data-record'>{{GET:accounts.registration.enabled}}</td>         
	   </tr>   
       <tr>
	    <td class='data-title'>{{LANG:accounts.registration.life}}</td>
		<td class='data-record'>{{GET:accounts.registration.life}}</td> 
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:accounts.registration.notify.reg}}</td>
		<td class='data-record'>{{GET:accounts.registration.notify.reg}}</td>         
	   </tr>   
       <tr>
	    <td class='data-title'>{{LANG:accounts.registration.notify.unreg}}</td>
		<td class='data-record'>{{GET:accounts.registration.notify.unreg}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:accounts.notify.updates}}</td>
		<td class='data-record'>{{GET:accounts.notify.updates}}</td>         
	   </tr>   
       <tr>
	    <td class='data-title'>{{LANG:accounts.notify.status}}</td>
		<td class='data-record'>{{GET:accounts.notify.status}}</td> 
	   </tr>  
	   <tr>
	    <td class='data-title'>{{LANG:accounts.notify.expired}}</td>
		<td class='data-record'>{{GET:accounts.notify.expired}}</td> 
	   </tr> 
      </table> 
     </div>
    
    {{LINK:AccessPointOptions}} 
     <table class='sub-module'>  
       <tr>
	    <td class='data-title'>{{LANG:accesspoints.stats}}</td>
		<td class='data-record'>{{GET:accesspoints.stats.enabled}}</td> 
	   </tr> 
       <tr>
	    <td class='data-title'>{{LANG:accesspoints.def_name}}</td>
		<td class='data-record' style='width: 100%'>{{GET:accesspoints.def_name}}</td>         
	   </tr>   
       <tr>
	    <td class='data-title'>{{LANG:accesspoints.def_group}}</td>
		<td class='data-record'>{{GET:accesspoints.def_group}}</td> 
	   </tr>  
	   <tr>
	    <td class='data-title'>{{LANG:accesspoints.def_theme}}</td>
		<td class='data-record'>{{GET:accesspoints.def_theme}}</td> 
	   </tr>  
      </table> 
     </div> 
           
    {{LINK:SessionsOptions}}
      <table class='sub-module'>
       <tr>
	    <td class='data-title'>{{LANG:sessions.timeout}}</td>
		<td class='data-record' style='width: 100%'>{{GET:sessions.timeout}}&nbsp;&nbsp;{{TIP:sessions.timeout_desc}}</td> 
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:sessions.auto_regenerate_id}}</td>
		<td class='data-record' style='width: 100%'>{{GET:sessions.auto_regenerate_id}}&nbsp;&nbsp;{{TIP:sessions.auto_regenerate_id_desc}}</td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:connections.timeout}}</td>
		<td class='data-record' style='width: 100%'>{{GET:connections.timeout}}&nbsp;&nbsp;{{TIP:connections.timeout_desc}}</td> 
	   </tr>
      </table>
     </div>
      
    {{LINK:LoggingOptions}}
    <table class='sub-module'> 
    	<tr>
    		<td></td>
    		<td class='data-subtitle'>{{LANG:logs.system}}</td>
    		<td class='data-subtitle'>{{LANG:logs.errors}}</td>
    		<td class='data-subtitle'>{{LANG:logs.access}}</td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.database.enabled}}</td>
    		<td class='data-record'>{{GET:logs.system.database.enabled}}</td>
    		<td class='data-record'>{{GET:logs.errors.database.enabled}}</td>
    		<td class='data-record'>{{GET:logs.access.database.enabled}}</td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.database.life}}</td>
    		<td class='data-record'>{{GET:logs.system.database.life}}</td>
    		<td class='data-record'>{{GET:logs.errors.database.life}}</td>
    		<td class='data-record'>{{GET:logs.access.database.life}}</td>
    	</tr>
    	<tr>
    		<td colspan='4'><hr size='1' color='#DDDDDD'></td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.file.enabled}}</td>
    		<td class='data-record'>{{GET:logs.system.file.enabled}}</td>
    		<td class='data-record'>{{GET:logs.errors.file.enabled}}</td>
    		<td class='data-record'>{{GET:logs.access.file.enabled}}</td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.file.life}}</td>
    		<td class='data-record'>{{GET:logs.system.file.life}}</td>
    		<td class='data-record'>{{GET:logs.errors.file.life}}</td>
    		<td class='data-record'>{{GET:logs.access.file.life}}</td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.file.size}}</td>
    		<td class='data-record'>{{GET:logs.system.file.size}}</td>
    		<td class='data-record'>{{GET:logs.errors.file.size}}</td>
    		<td class='data-record'>{{GET:logs.access.file.size}}</td>
    	</tr>
    	<tr>
    		<td colspan='4'><hr size='1' color='#DDDDDD'></td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.email.enabled}}</td>
    		<td class='data-record'>{{GET:logs.system.email.enabled}}</td>
    		<td class='data-record'>{{GET:logs.errors.email.enabled}}</td>
    		<td class='data-record'>{{GET:logs.access.email.enabled}}</td>
    	</tr>
    </table>
    <br><br>
     </div>
                   
	 </td>
	</tr>
    
  </table> 