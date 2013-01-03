
  {{FILE:module_menu.tpl}}
  <table class='tab' style='border:1px solid red'>
  	<td class="tab-icon" colspan='2'>

	 	<table>
	 		<tr>
	 			<td>{{LINK:EditSettings}}</td> 
	 		</tr>
	 	</table>
	 
	 </td> 
  
	<tr>	 
	 <td style='vertical-align: top; width: 500px'>  
                      	                 
     {{GET:UpdateForm}}   
        
	 {{LINK:GenericOptions}}                                  
      <table class='sub-module'> 
       	   <tr>
	    <td class='data-title'>{{LANG:server.admin.name}}</td>
		<td class='data-record' colspan='3'>{{PUT:server.admin.name}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:server.admin.email}}</td>
		<td class='data-record' colspan='3'>{{PUT:server.admin.email}}</td> 
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:server.language}}</td>
		<td class='data-record' colspan='3'>{{IMG:server.language}} {{PUT:server.language}}</td>   
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:server.date_format}}</td>
		<td class='data-record'>{{PUT:server.date_format}}</td> 
		<td class='data-title'>{{LANG:server.time_format}}</td>
		<td class='data-record'>{{PUT:server.time_format}}</td> 
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:iptocountry.enabled}}</td>
		<td class='data-record' colspan='3'>{{PUT:iptocountry.enabled}}&nbsp;&nbsp;{{GET:iptocountry.updater}}</td> 
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:database.optimize_hits}}</td>
		<td class='data-record' colspan='3'>{{PUT:database.optimize_hits}}&nbsp;&nbsp;{{TIP:database.optimize_hits_desc}}</td> 
	   </tr>	   
      </table> 
     </div>      
     
      {{LINK:ConsoleOptions}}
      <table class='sub-module'>
	    <tr>
	        <td class='data-title'>{{LANG:console.tip}}</td>
		<td class='data-record' style='width:100%'>{{PUT:console.tip}}</td> 
	    </tr>
      </table> 
     </div>
     
     {{LINK:SecurityOptions}}
      <table class='sub-module'>        
       <tr>
	    <td class='data-title'>{{LANG:security.max_login_attempts}}</td>
		<td class='data-record' style='width: 100%'>{{PUT:security.max_login_attempts}}</td> 
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:security.banned_time}}</td>
		<td class='data-record'>{{PUT:security.banned_time}}&nbsp;&nbsp;(sec.)</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:security.access_violations}}</td>
		<td class='data-record'>{{PUT:security.access_violations}}</td> 
	   </tr>
      </table>
     </div>     
     
     {{LINK:AccountsOptions}}
      <table class='sub-module'>
       <tr>
	    <td class='data-title'>{{LANG:accounts.life}}</td>
		<td class='data-record' style='width: 100%'>{{PUT:accounts.life}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:accounts.password.life}}</td>
		<td class='data-record'>{{PUT:accounts.password.life}}</td> 
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:accounts.registration.enabled}}</td>
		<td class='data-record'>{{PUT:accounts.registration.enabled}}</td>         
	   </tr>   
       <tr>
	    <td class='data-title'>{{LANG:accounts.registration.life}}</td>
		<td class='data-record'>{{PUT:accounts.registration.life}}</td> 
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:accounts.registration.notify.reg}}</td>
		<td class='data-record'>{{PUT:accounts.registration.notify.reg}}</td>         
	   </tr>   
       <tr>
	    <td class='data-title'>{{LANG:accounts.registration.notify.unreg}}</td>
		<td class='data-record'>{{PUT:accounts.registration.notify.unreg}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:accounts.notify.updates}}</td>
		<td class='data-record'>{{PUT:accounts.notify.updates}}</td>         
	   </tr>   
       <tr>
	    <td class='data-title'>{{LANG:accounts.notify.status}}</td>
		<td class='data-record'>{{PUT:accounts.notify.status}}</td> 
	   </tr>  
	   <tr>
	    <td class='data-title'>{{LANG:accounts.notify.expired}}</td>
		<td class='data-record'>{{PUT:accounts.notify.expired}}</td> 
	   </tr> 
      </table> 
     </div>
    
    {{LINK:AccessPointOptions}} 
     <table class='sub-module'>  
       <tr>
	    <td class='data-title'>{{LANG:accesspoints.stats}}</td>
		<td class='data-record'>{{PUT:accesspoints.stats.enabled}}</td> 
	   </tr> 
       <tr>
	    <td class='data-title'>{{LANG:accesspoints.def_name}}</td>
		<td class='data-record' style='width: 100%'>{{PUT:accesspoints.def_name}}</td>         
	   </tr>   
       <tr>
	    <td class='data-title'>{{LANG:accesspoints.def_group}}</td>
		<td class='data-record'>{{PUT:accesspoints.def_group}}</td> 
	   </tr>  
	   <tr>
	    <td class='data-title'>{{LANG:accesspoints.def_theme}}</td>
		<td class='data-record'>{{PUT:accesspoints.def_theme}}</td> 
	   </tr>  
      </table> 
     </div> 
           
    {{LINK:SessionsOptions}}
      <table class='sub-module'>
       <tr>
	    <td class='data-title'>{{LANG:sessions.timeout}}</td>
		<td class='data-record' style='width: 100%'>{{PUT:sessions.timeout}}&nbsp;&nbsp;(sec.)&nbsp;&nbsp;{{TIP:sessions.timeout_desc}}</td> 
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:sessions.auto_regenerate_id}}</td>
		<td class='data-record' style='width: 100%'>{{PUT:sessions.auto_regenerate_id}}&nbsp;&nbsp;{{TIP:sessions.auto_regenerate_id_desc}}</td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:connections.timeout}}</td>
		<td class='data-record' style='width: 100%'>{{PUT:connections.timeout}}&nbsp;&nbsp;(sec.)&nbsp;&nbsp;{{TIP:connections.timeout_desc}}</td> 
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
    		<td class='data-record'>{{PUT:logs.system.database.enabled}}</td>
    		<td class='data-record'>{{PUT:logs.errors.database.enabled}}</td>
    		<td class='data-record'>{{PUT:logs.access.database.enabled}}</td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.database.life}}</td>
    		<td class='data-record'>{{PUT:logs.system.database.life}}</td>
    		<td class='data-record'>{{PUT:logs.errors.database.life}}</td>
    		<td class='data-record'>{{PUT:logs.access.database.life}}</td>
    	</tr>
    	<tr>
    		<td colspan='4'><hr size='1' color='#DDDDDD'></td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.file.enabled}}</td>
    		<td class='data-record'>{{PUT:logs.system.file.enabled}}</td>
    		<td class='data-record'>{{PUT:logs.errors.file.enabled}}</td>
    		<td class='data-record'>{{PUT:logs.access.file.enabled}}</td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.file.life}}</td>
    		<td class='data-record'>{{PUT:logs.system.file.life}}</td>
    		<td class='data-record'>{{PUT:logs.errors.file.life}}</td>
    		<td class='data-record'>{{PUT:logs.access.file.life}}</td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.file.size}}</td>
    		<td class='data-record'>{{PUT:logs.system.file.size}}</td>
    		<td class='data-record'>{{PUT:logs.errors.file.size}}</td>
    		<td class='data-record'>{{PUT:logs.access.file.size}}</td>
    	</tr>
    	<tr>
    		<td colspan='4'><hr size='1' color='#DDDDDD'></td>
    	</tr>
    	<tr>
    		<td class='data-title'>{{LANG:logs.email.enabled}}</td>
    		<td class='data-record'>{{PUT:logs.system.email.enabled}}</td>
    		<td class='data-record'>{{PUT:logs.errors.email.enabled}}</td>
    		<td class='data-record'>{{PUT:logs.access.email.enabled}}</td>
    	</tr>
    </table>
      <br><br>
    </div>
      
    
    <br>
	   <center>{{BUTTON:Cancel}}&nbsp;&nbsp;&nbsp;{{BUTTON:Save}}</center>
	<br>     
	     
    </form>
    
	 </td>
	</tr>
   
       
  </table> 
