
  {{FILE:module_menu.tpl}}
  {{GET:EditForm}}
  
  <table class='tab' style='border:1px solid red'>
 
   <td class="tab-icon" colspan='2'>

	 	<table>
	 		<tr>
	 			<td>{{LINK:AddGroup}}</td> 
	 			<td>{{LINK:EditGroup}}</td> 
				<td>{{LINK:Remove}}</td>
	 		</tr>
	 	</table>
	 
	 </td> 
	 
   <tr>
	<td class='data-title' style='padding-top:10px'>{{LANG:GroupName}}</td>
	<td class='data-record' style='padding-top:10px'>{{PUT:GroupName}}</td> 
   </tr>  
   <tr>
	<td class='data-title'>{{LANG:GroupDesc}}</td>
	<td class='data-record'>{{PUT:GroupDesc}}</td> 
   </tr>
   <tr>
	<td style='padding: 15px;text-align:center' colspan='2'>{{BUTTON:Cancel}}&nbsp;&nbsp;&nbsp;{{BUTTON:Submit}}</td>
   </tr>
  </table>    
   
  </form>   