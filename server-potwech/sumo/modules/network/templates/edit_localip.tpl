  
  {{FILE:module_menu.tpl}}
  {{GET:UpdateForm}}  
  <table class='tab' style='border:1px solid red'>	
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
		<td class='data-record'><b>{{PUT:LocalIPType}}</b></td>
	   </tr>
	   <tr>
	    <td class='data-title'>{{LANG:IPAddress}}</td>
		<td class='data-record'>{{PUT:IP}}</td>
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