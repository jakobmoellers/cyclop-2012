
  {{FILE:module_menu.tpl}}
  {{GET:AddForm}}
  
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
	  <br>
	  <table>
	   <tr>
	    <td class='data-title'>{{LANG:User}}</td>
		<td class='data-record'><b>{{PUT:User}}</b></td>
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
	    <td class='data-title'>{{LANG:Status}}</td>
		<td class='data-record'>{{PUT:Status}}</td> 
	   </tr>	
	   <tr>
	    <td class='data-title'>{{LANG:Email}}</td>
		<td class='data-record'>{{PUT:Email}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Language}}</td>
		<td class='data-record'>{{PUT:Language}}</td> 
	   </tr>
	  
	   <tr>
	    <td colspan='2'><br><div class="sub-module">{{LANG:SecurityOptions}}</div><br></td>
	   </tr>
	   	     
	   <tr>
	    <td class='data-title'>{{LANG:DataSourceType}}</td>
		<td class='data-record'>{{PUT:DataSourceType}}</td> 
	   </tr>
       
	   <tr>
	    <td class='data-title'>{{LANG:GroupLevel}}</td>
		<td class='data-record'>{{PUT:AddGroupLevel}}</td> 
	   </tr>	   
	   <tr>
	    <td colspan='2'><br></td>
	   </tr> 	   
	   <tr>
	    <td class='data-title'>{{LANG:IP}}</td>
		<td class='data-record'>{{PUT:IP}}</td> 
	   </tr>	   	   
	   <tr>
	    <td class='data-title'>{{LANG:DayLimit}}</td>
		<td class='data-record'>{{PUT:DayLimit}}</td> 
	   </tr> 
       <!--	 	  
	   <tr>
	    <td class='data-title'>{{LANG:PwdExpiration}}</td>
		<td class='data-record'>{{GET:PwdExpiration}}</td> 
	   </tr>	   
       -->
	   <tr>
	    <td class='data-title'>{{LANG:NewPassword}}</td>
		<td class='data-record'>{{PUT:NewPassword}}</td> 
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:ReNewPassword}}</td>
		<td class='data-record'>{{PUT:ReNewPassword}}</td> 
	   </tr>  
	  
	  </table>  
	  
	  <br><br>
	  <center>
	  	{{BUTTON:Cancel}}&nbsp;&nbsp;&nbsp;{{BUTTON:Save}}
	  </center>
	  <br>
	 </td>
	</tr>	
  </table>
  
  </form>