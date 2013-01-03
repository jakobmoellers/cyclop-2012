  
  {{FILE:module_menu.tpl}}
  {{GET:AddForm}}
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
	    
	<tr>	 
	 <td style='padding: 10px; vertical-align: top; width: 100%'>
     	 
	  <table>
	   <tr>
	    <td class='data-title'>{{LANG:NodeName}}</td>
		<td class='data-record'><b>{{PUT:NodeName}}</b></td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Host}}</td>
		<td class='data-record'>{{PUT:Host}}</td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:Status}}</td>
		<td class='data-record'>{{PUT:Status}}</td>
	   </tr> 
	   <tr>
	    <td class='data-title'>{{LANG:Port}}</td>
		<td class='data-record'>{{PUT:Port}}</td>
	   </tr>	   
	   <tr>
	    <td class='data-title'>{{LANG:Protocol}}</td>
		<td class='data-record'>{{PUT:Protocol}}</td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:SumoPath}}</td>
		<td class='data-record'>{{PUT:SumoPath}}</td>
	   </tr>
	   <tr>
	    <td colspan='2' align='center'>
	     <br><br>
	     {{BUTTON:Back}}&nbsp;&nbsp;&nbsp;{{BUTTON:Save}}
	    </td>
       </tr>
	   
	  </table>  
	  
	 </td>
	</tr>	
  </table>
 </form>