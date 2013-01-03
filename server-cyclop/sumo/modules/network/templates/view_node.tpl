  
  {{FILE:module_menu.tpl}}
  <table class='tab' style='width: 300px'>	
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
  	<tr>	 
	 <td style='padding: 10px; vertical-align: top; width: 100%'>
	 
	  <table>
	   <tr>
	    <td class='data-title'>{{LANG:NodeName}}</td>
		<td class='data-record'><b>{{GET:NodeName}}</b></td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Host}}</td>
		<td class='data-record'>{{GET:Host}}</td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Status}}</td>
		<td class='data-record'>{{GET:Status}}</td>
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:Port}}</td>
		<td class='data-record'>{{GET:Port}}</td>
	   </tr>	   
	   <tr>
	    <td class='data-title'>{{LANG:Protocol}}</td>
		<td class='data-record'>{{GET:Protocol}}</td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:SumoPath}}</td>
		<td class='data-record'>{{GET:SumoPath}}</td>
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