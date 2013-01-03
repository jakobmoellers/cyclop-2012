<table id='window{{GET:WindowElement}}' style='opacity: 0.7'
	onmousedown='dd.elements.{{GET:WindowElement}}.maximizeZ();'
	onmouseover='javascript:windowFocus("{{GET:WindowElement}}");'>
 <tr>
  <td><img src='themes/{{GET:PageTheme}}/images/modules/settings/bg_sx_top.gif'></td>
  <td style='background: url("themes/{{GET:PageTheme}}/images/modules/settings/bg_middle_top.gif") repeat-x'></td>
  <td><img src='themes/{{GET:PageTheme}}/images/modules/settings/bg_dx_top.gif'></td>
 </tr>
 <tr>
  <td style='background: url("themes/{{GET:PageTheme}}/images/modules/settings/bg_sx_middle.gif") repeat-y'></td>
  <td style='text-align:center;background-color:black'>

  	  <style>
		.setdate {
			font-size:17px;
			color:#FEFEFE;
			background-color: black;
		}
		.setclock {
			font-size:30px;
			color:#FFFFFF;
			background-color: black;
		}
	  </style>

  	  {{GET:UpdateForm}}

  	  <div style="font-size:7px;text-align:right;position:relative;top:-7px;left:5px"><a href='javascript:sumo_remove_window("{{GET:WindowElement}}");'><img src='themes/{{GET:PageTheme}}/images/modules/settings/close_clock.gif' style='opacity: 0.6'></a></div>
  	  <div style="white-space:nowrap;font-size:17px;color:#FEFEFE;letter-spacing:1px;position:relative;top:-7px;">{{PUT:Date}}</div>
	  <div style="white-space:nowrap;font-size:30px;color:#FFFFFF;padding-left:15px;padding-right:15px;">{{PUT:Time}}</div>

	  <br>
	  {{BUTTON:Ok}}

	  </form>

  </td>
  <td style='background: url("themes/{{GET:PageTheme}}/images/modules/settings/bg_dx_middle.gif") repeat-y'></td>
 </tr>
 <tr>
  <td><img src='themes/{{GET:PageTheme}}/images/modules/settings/bg_sx_bottom.gif'></td>
  <td style='background: url("themes/{{GET:PageTheme}}/images/modules/settings/bg_middle_bottom.gif") repeat-x'></td>
  <td><img src='themes/{{GET:PageTheme}}/images/modules/settings/bg_dx_bottom.gif'></td>
 </tr>
</table>
