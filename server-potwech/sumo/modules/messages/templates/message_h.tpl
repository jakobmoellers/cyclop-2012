<table id='window{{GET:WindowElement}}' style='opacity: 0.8;width:300px'
	onmousedown='dd.elements.{{GET:WindowElement}}.maximizeZ();'
	onmouseover='javascript:windowFocus("{{GET:WindowElement}}");'>
 <tr>
  <td><img src='themes/{{GET:PageTheme}}/images/modules/messages/message_h_sx_top.gif'></td>
  <td style='background: url("themes/{{GET:PageTheme}}/images/modules/messages/message_h_middle_top.gif") repeat-x'></td>
  <td><img src='themes/{{GET:PageTheme}}/images/modules/messages/message_h_dx_top.gif'></td>
 </tr>
 <tr>
  <td style='background: url("themes/{{GET:PageTheme}}/images/modules/messages/message_h_sx_middle.gif") repeat-y'></td>
  <td style='text-align:center;background-color:black;color:white;padding-left:15px;padding-right:15px;font-size:13px;width:100%'>

  	  <img src='themes/{{GET:PageTheme}}/images/modules/messages/message_critical.png' width='48' alt='Critical'>
  	  <br><br>
	  {{MESSAGE}}
	  <br><br>
	  {{MESSAGE:F}}
	  {{BUTTON:1}} {{BUTTON:2}} {{BUTTON:3}}
	  </form>
	  
  </td>
  <td style='background: url("themes/{{GET:PageTheme}}/images/modules/messages/message_h_dx_middle.gif") repeat-y'></td>
 </tr>
 <tr>
  <td><img src='themes/{{GET:PageTheme}}/images/modules/messages/message_h_sx_bottom.gif'></td>
  <td style='background: url("themes/{{GET:PageTheme}}/images/modules/messages/message_h_middle_bottom.gif") repeat-x'></td>
  <td><img src='themes/{{GET:PageTheme}}/images/modules/messages/message_h_dx_bottom.gif'></td>
 </tr>
</table>