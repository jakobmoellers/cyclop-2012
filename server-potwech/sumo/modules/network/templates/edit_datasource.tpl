  
  {{FILE:module_menu.tpl}}
  {{GET:Form}}
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
		<td class='data-record'><b>{{PUT:DataSourceName}}</b></td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:DataSourceType}}</td>
		<td class='data-record'>{{PUT:DataSourceType}}</td>
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:Host}}</td>
		<td class='data-record'>{{PUT:DataSourceHost}}</td>
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:Port}}</td>
		<td class='data-record'>{{PUT:DataSourcePort}}</td> 
	   </tr>	
	   <tr>
	    <td class='data-title'>{{LANG:User}}</td>
		<td class='data-record'>{{PUT:DataSourceUser}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Password}}</td>
		<td class='data-record'>{{PUT:DataSourcePassword}}</td> 
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:RePassword}}</td>
		<td class='data-record'>{{PUT:DataSourceRePassword}}</td> 
	   </tr>
	  </table>
	   
	   
	    {{LINK:DatabaseOptions}}
	    <fieldset>
		<legend align='left'>&nbsp;&nbsp;{{LANG:DatabaseOptions}}&nbsp;&nbsp;
	    <table>
		   <tr>
		    <td class='data-title'>{{LANG:DBName}}</td>
			<td class='data-record'>{{PUT:DBName}}</td> 
		   </tr>
		   <tr>
		    <td class='data-title'>{{LANG:DBTable}}</td>
			<td class='data-record'>{{PUT:DBTable}}</td> 
		   </tr>
		   <tr>
		    <td class='data-title'>{{LANG:DBFieldUser}}</td>
			<td class='data-record'>{{PUT:DBFieldUser}}</td> 
		   </tr>
		   <tr>
		    <td class='data-title'>{{LANG:DBFieldPassword}}</td>
			<td class='data-record'>{{PUT:DBFieldPassword}}</td> 
		   </tr>
		   </table>
		    </legend>
		   </fieldset> 
	   </div>
	  
	   
	   {{LINK:LDAPOptions}}
	   <fieldset>
		<legend align='left'>&nbsp;&nbsp;{{LANG:LDAPOptions}}&nbsp;&nbsp;
	   	<table>
		   <tr>
		    <td class='data-title'>{{LANG:LDAPBase}}</td>
			<td class='data-record'>{{PUT:LDAPBase}}</td> 
		   </tr>
	    </table>
	    </legend>
		</fieldset>
	   </div>
	  
	   <table>
	    <tr>
	     <td class='data-title'>{{LANG:EncType}}</td>
		 <td class='data-record'>{{PUT:EncType}}</td> 
	    </tr>
	   </table>
	   
	   <center>
	     <br><br>
	     {{BUTTON:Cancel}}&nbsp;&nbsp;&nbsp;{{BUTTON:Save}}
	   </center>  
	  
	 </td>
	</tr>	
  </table>
  </form>