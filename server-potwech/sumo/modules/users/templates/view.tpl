
  {{FILE:module_menu.tpl}}
  <table class='tab'>
	<tr>
	 <td class="tab-icon">

	 	<table>
	 		<tr>
	 			<td>{{LINK:AddUser}}</td> 
	 			<td>{{LINK:EditUser}}</td> 
				<td>{{LINK:Remove}}</td>
	 		</tr>
	 	</table>
	 
	 </td> 
	</tr>
	<tr>	 
	 <td style='vertical-align: top; width: 100%'>
                     
	  <table style='width: 100%; margin: 5px;'>	   
	   <tr>
	    <td class='data-title'>{{LANG:User}}</td>
		<td class='data-record'>{{GET:User}}</td>
        <td rowspan='6' style='text-align: right; padding: 5px'>
	     {{IMG:User}}
	    </td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:UserName}}</td>
		<td class='data-record'><b>{{GET:FirstName}} {{GET:LastName}}</b></td>
	   </tr>
       <tr>
	    <td class='data-title'>{{LANG:Status}}</td>
		<td class='data-record'>{{GET:Status}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Email}}</td>
		<td class='data-record'>{{GET:Email}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Language}}</td>
		<td class='data-record'>{{IMG:Language}} {{GET:Language}}</td>         
	   </tr>
       <tr><td colspan='3'>&nbsp;</td></tr> 
      </table>                 
                                 
     
      {{LINK:AccountDetails}}
          <table class='sub-module'>              	   
	       <tr>
	        <td class='data-title'>{{LANG:LastLogin}}</td>
		    <td class='data-record' width='100%'>{{GET:LastLogin}}</td> 
	       </tr>	
	       <tr>
	        <td class='data-title'>{{LANG:AccountCreated}}</td>
		    <td class='data-record'>{{GET:AccountCreated}}</td>
		   </tr>
	       <tr>
	        <td class='data-title'>{{LANG:by}}</td>
		    <td class='data-record'>{{GET:AccountCreatedBy}}</td>
	       </tr>
	       <tr>
	        <td class='data-title'>{{LANG:Expire}}</td>
		    <td class='data-record'>{{GET:Expire}}</td> 
	       </tr>
	       <tr>
	        <td class='data-title'>{{LANG:Modified}}</td>
		    <td class='data-record'>{{GET:Modified}}</td> 
	       </tr>   
	      </table> 
      </div>        
     
      
      {{LINK:SecurityOptions}}           
          <table class='sub-module'>                 
	       <tr>
	        <td class='data-title'>{{LANG:DataSourceType}}</td>
		    <td class='data-record'>{{GET:DataSourceType}}</td> 
	       </tr>
	       <tr>
	        <td class='data-title'>{{LANG:Group}}</td>
		    <td class='data-record'>
		      {{FILE:/modules/users/templates/group_level.tpl}}
		    </td> 
	       </tr>		   
	       <tr>
	        <td class='data-title'>{{LANG:IP}}</td>
		    <td class='data-record'>{{GET:IP}}</td> 
	       </tr>	   
	       <tr>
	        <td class='data-title'>{{LANG:AccessTo}}</td>
		    <td class='data-record'>{{GET:UserAccessPages}}</td> 
	       </tr>
	       <tr>
	        <td class='data-title'>{{LANG:DayLimit}}</td>
		    <td class='data-record'>{{GET:DayLimit}}</td> 
	       </tr>
	       <tr>
	        <td class='data-title'>{{LANG:PwdUpdated}}</td>
		    <td class='data-record'>{{GET:PwdUpdated}}</td> 
	       </tr>
	       <tr>
	        <td class='data-title'>{{LANG:PwdExpiration}}</td>
		    <td class='data-record'>{{GET:PwdExpiration}}</td> 
	       </tr>      
	      </table>  
       </div>
     
	 </td>
	</tr>
    
  </table> 