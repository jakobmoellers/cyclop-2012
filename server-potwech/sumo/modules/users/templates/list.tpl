
  {{FILE:module_menu.tpl}}    
  <table class='tab'>
   <tr>
    <td class='tab-gray' style='padding:6px;padding-left:10px;width:100%;'>    	 
    {{FILE:table.tpl}}	 
    </td>
    <td valign='top' rowspan='2' class='tab-icon'>{{LINK:AddUser}}</td>
   </tr>
   <tr>
    <td>{{GET:UsersList}}</td>
   </tr>     
  </table>
  <br>
  <center>
    {{GET:PagingResults}}
  </center>

  	  <table>
	   <tr>
	    <td class='tab-gray' style='padding-left:22px'>{{LANG:Users}}</td>
		<td class='tab-gray'><b>{{GET:NumUsers}}</b></td>
	   </tr>
	   <tr>
	    <td class='tab-white'><img src='themes/{{GET:Theme}}/images/modules/users/user_on.gif' align='middle'> {{LANG:ActiveUsers}}</td>
		<td class='tab-white'><b>{{GET:NumUsersActive}}</b></td>
	   </tr>
	   <tr>
	    <td class='tab-gray'><img src='themes/{{GET:Theme}}/images/modules/users/user_off.gif' align='middle'> {{LANG:SuspendedUsers}}</td>
		<td class='tab-gray'><b>{{GET:NumSuspendedUsers}}</b></td>
	   </tr>
	  </table>