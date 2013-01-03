
  {{FILE:module_menu.tpl}}
  {{GET:UpdateForm}}
  <table class='tab' style='border:1px solid red'>
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
	 <td style='padding-top: 5px; vertical-align: top; width: 100%'>
	 
	  <table style='width: 100%'>
	   <tr>
	    <td class='data-title'>{{LANG:User}}</td>
		<td class='data-record'>{{GET:User}}</td>
        <td rowspan='6' style='text-align: right; padding: 3px'>
	     {{IMG:User}}
	    </td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Status}}</td>
		<td class='data-record'>{{GET:Status}}&nbsp;&nbsp;&nbsp;{{PUT:Status}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:FirstName}}</td>
		<td class='data-record'>{{PUT:FirstName}}</td>
	   </tr>       	
	   <tr>
	    <td class='data-title'>{{LANG:LastName}}</td>
		<td class='data-record'>{{PUT:LastName}}</td>
	   </tr>	   
	   <tr>
	    <td class='data-title'>{{LANG:Email}}</td>
		<td class='data-record'>{{PUT:Email}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Language}}</td>
		<td class='data-record'>{{IMG:Language}} {{PUT:Language}}</td> 
	   </tr>
       <tr>
        <td colspan='3' style='text-align: right'>{{PUT:UserImage}}</td>
       </tr>
      </table>
       
	  
      {{LINK:AccountDetails}}
      	  <table class='sub-module'>      	   
	       <tr>
            <td class='data-title'>{{LANG:LastLogin}}</td>
		    <td class='data-record' width='100%'>{{GET:LastLogin}}</td> 
	       </tr> 
	       <tr>
	        <td class='data-title'>{{LANG:AccountCreated}}</td>
		    <td class='data-record'>{{GET:AccountCreated}} - {{GET:AccountCreatedBy}}</td> 
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
		    <td class='data-record'>{{PUT:DataSourceType}}</td> 
	       </tr>
	       <tr>
	        <td class='data-title'>{{LANG:Group}}</td>
		    <td class='data-record'>
			    <!-- Group Level -->
			    <table class='tab'>
			     <tr>			  
			      <td class='tab-title'>{{LANG:Group}}</td>
			      <td class='tab-title' width='100%'>{{LANG:Description}}</td>
			      <td class='tab-title'>{{LANG:Level}}</td>
			      <td class='tab-title'></td>
			     </tr>		
			     {{PUT:GroupLevel}}
			    </table>
			    <!-- -->
		    </td> 
	       </tr>	   
	       <tr>
	        <td class='data-title'>{{LANG:GroupLevel}}</td>
		    <td class='data-record'>{{PUT:AddGroupLevel}}&nbsp;&nbsp;{{BUTTON:AddGroup}}</td> 
	       </tr>
	       <tr>
	        <td colspan='2'><br></td>
	       </tr> 	   
	       <tr>
	        <td class='data-title'>{{LANG:IP}}</td>
		    <td class='data-record'>{{PUT:IP}}</td> 
	       </tr>
	       <tr>
	        <td class='data-title'>{{LANG:AccessTo}}</td>
		    <td class='data-record'>{{GET:UserAccessPages}}</td> 
	       </tr> 
	       <tr>
	        <td class='data-title'>{{LANG:DayLimit}}</td>
		    <td class='data-record'>{{PUT:DayLimit}}</td> 
	       </tr>		   
	       <tr>
	        <td class='data-title'>{{LANG:PwdUpdated}}</td>
		    <td class='data-record'>{{GET:PwdUpdated}}</td> 
	       </tr>
	       <tr>
	        <td class='data-title'>{{LANG:PwdExpiration}}</td>
		    <td class='data-record'>{{GET:PwdExpiration}}</td> 
	       </tr>	   
	       <tr>
	        <td class='data-title'>{{LANG:NewPassword}}</td>
		    <td class='data-record'>{{PUT:NewPassword}}</td> 
	       </tr>
	       <tr>
	        <td class='data-title'>{{LANG:ReNewPassword}}</td>
		    <td class='data-record'>{{PUT:ReNewPassword}}</td> 
	       </tr>	    
          </table>  
      </div> 
          
      <br>
       <center>{{BUTTON:Back}}&nbsp;&nbsp;&nbsp;{{BUTTON:Save}}</center>  
	  <br>
	  
	 </td>
	</tr>	
  </table>
  </form>    