  
  {{FILE:module_menu.tpl}}
  <table class='tab'>	
	<tr>
	 <td class='tab-title' colspan='2'>
	 	<img src='themes/{{GET:Theme}}/images/modules/network/edit_localip_small.gif' align='middle'> {{LINK:EditLocalIP}}  |   		
		<img src='themes/{{GET:Theme}}/images/modules/network/remove_localip_small.gif' align='middle'> {{LINK:Remove}}
	 </td>
	</tr>
  	<tr>	 
	 <td style='padding: 10px; vertical-align: top; width: 100%'>
     
	  <table>
	   <tr>
	    <td class='data-title'>{{LANG:LocalIPType}}</td>
		<td class='data-record'><b>{{GET:LocalIPType}}</b></td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:IPAddress}}</td>
		<td class='data-record'>{{GET:IP}}</td>
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