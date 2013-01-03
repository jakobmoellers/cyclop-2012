
  {{FILE:module_menu.tpl}}               
  <table class='tab'>
   <tr>
    <td class='tab-gray' style='padding:6px;padding-left:10px;width:100%;'>    	 
    {{FILE:table.tpl}}	 
	</td>
   </tr>
   <tr>
    <td>{{GET:SessionsList}}</td>
   </tr>       
  </table>  
  <br>
  <center>
    {{GET:PagingResults}}
  </center>
  
  <!-- Box sessions report -->
	  <table>
	   <tr>
	    <td class='tab-gray' style='padding-left:14px'>{{LANG:RunningSessions}}</td>
		<td class='tab-gray'><b>{{GET:NumSessions}}</b></td>
	   </tr>
	   <tr>
	    <td class='tab-white'><img src='themes/{{GET:Theme}}/images/modules/sessions/status_green.gif' align='middle' class='session-status'> {{LANG:ActiveSessions}}</td>
		<td class='tab-white'><b>{{GET:NumSessionsActive}}</b></td>
	   </tr>
	   <tr>
	    <td class='tab-gray'><img src='themes/{{GET:Theme}}/images/modules/sessions/status_red.gif' align='middle' class='session-status'> {{LANG:NotActiveSessions}}</td>
		<td class='tab-gray'><b>{{GET:NumSessionsNotActive}}</b></td>
	   </tr>
	  </table>
  <!-- End Box sessions report -->