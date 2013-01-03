  
  {{FILE:module_menu.tpl}}
  <table class='tab'>	
	<tr>
	 <td class="tab-icon">

	 	<table>
	 		<tr>
	 		    <td>{{LINK:Add}}</td>
	 			<td>{{LINK:Edit}}</td> 
				<td>{{LINK:Remove}}</td>
	 		</tr>
	 	</table>
	 
	 </td> 
	</tr>
	
  	<tr>	 
	 <td style='padding: 10px; vertical-align: top; width: 100%'>
     
	  <table>
	   <tr>
	    <td class='data-title'>{{LANG:DataSourceName}}</td>
		<td class='data-record'><b>{{GET:DataSourceName}}</b></td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:DataSourceType}}</td>
		<td class='data-record'>{{GET:DataSourceType}}</td>
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:Host}}</td>
		<td class='data-record'>{{GET:DataSourceHost}}</td>
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:Port}}</td>
		<td class='data-record'>{{GET:DataSourcePort}}</td> 
	   </tr>	
	   <tr>
	    <td class='data-title'>{{LANG:User}}</td>
		<td class='data-record'>{{GET:DataSourceUser}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Password}}</td>
		<td class='data-record'>{{GET:DataSourcePassword}}</td> 
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:DBName}}</td>
		<td class='data-record'>{{GET:DBName}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:DBTable}}</td>
		<td class='data-record'>{{GET:DBTable}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:DBFieldUser}}</td>
		<td class='data-record'>{{GET:DBFieldUser}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:DBFieldPassword}}</td>
		<td class='data-record'>{{GET:DBFieldPassword}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:LDAPBase}}</td>
		<td class='data-record'>{{GET:LDAPBase}}</td> 
	   </tr>
	  
	    <tr>
	     <td class='data-title'>{{LANG:EncType}}</td>
		 <td class='data-record'>{{GET:EncType}}</td> 
	    </tr>
	   
	   <tr>
	    <td colspan='2' align='center'>
	     <br><br>
	     {{BUTTON:Back}}
	    </td>
       </tr>
	   
	  </table>  
	  
	 </td>
	</tr>	
  </table>