
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
    <td style='padding: 10px; vertical-align: top; text-align: center;'>
	      
	  <table>
	   <tr>
		<td class='data-title'>{{LANG:Name}}</td>
		<td class='data-record'>{{PUT:Name}}</td> 
	   </tr>  
	   <tr>
		<td class='data-title'>{{LANG:Node}}</td>
		<td class='data-record'>{{PUT:Node}}</td> 
	   </tr> 
	   <tr>
		<td class='data-title'>{{LANG:Path}}</td>
		<td class='data-record'>{{PUT:Path}}</td> 
	   </tr>
	   <tr>
	    <td colspan='2'><div class="sub-module">{{LANG:SecurityOptions}}</div></td>
	   </tr>
	   <tr>
		<td class='data-title'>{{LANG:Groups}}</td>
		<td class='data-record'>{{PUT:Groups}}</td> 
	   </tr>
	   <tr>
		<td class='data-title'>{{LANG:AddGroup}}</td>
		<td class='data-record'>{{PUT:AddGroup}}</td> 
	   </tr>
	   <tr>
		<td class='data-title'>{{LANG:HTTPAuth}}</td>
		<td class='data-record'>{{PUT:HTTPAuth}}</td> 
	   </tr>
	   <tr>
		<td class='data-title'>{{LANG:Filtering}}</td>
		<td class='data-record'>{{PUT:Filtering}}</td> 
	   </tr>
	   <tr>
		<td class='data-title'>{{LANG:PwdEncrypt}}</td>
		<td class='data-record'>{{PUT:PwdEncrypt}}</td> 
	   </tr>
	   <tr>
		<td class='data-title'>{{LANG:ChangePwd}}</td>
		<td class='data-record'>{{PUT:ChangePwd}}</td> 
	   </tr>
	   <tr>
		<td class='data-title'>{{LANG:Registration}}</td>
		<td class='data-record'>{{PUT:Registration}}</td> 
	   </tr>
	   <tr>
		<td class='data-title'>{{LANG:RegGroup}}</td>
		<td class='data-record'>{{PUT:AddRegGroup}}</td> 
	   </tr>
	   <tr>
	    <td colspan='2'><div class="sub-module">{{LANG:Layout}}</div></td>
	   </tr>
	   <tr>
		<td class='data-title'>{{LANG:Theme}}</td>
		<td class='data-record'>{{PUT:Theme}}</td> 
	   </tr>
	  </table>
	  
      <br><br>{{BUTTON:Back}}&nbsp;&nbsp;&nbsp;{{BUTTON:Save}}
      
	</td>
   </tr>
  </table>
  
  </form>