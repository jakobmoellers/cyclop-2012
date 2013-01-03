
  {{FILE:module_menu.tpl}} 
  
     <!-- DataSources List -->
     <table class='tab'>
      <tr>
       <td class='tab-gray' colspan='2' style='padding:6px;padding-left:10px;width:100%;'>    	 
       {{FILE:table.tpl}}	 
       </td>
       <td valign='top' rowspan='2' class='tab-icon'>{{GET:AddDataSource}}</td>
      </tr>
      <tr>
       <td>{{GET:DataSourcesList}}</td>
      </tr>       
     </table> 
     <br>
     <center>
       {{GET:PagingResults}}
     </center>
     {{BUTTON:TestConnection}}