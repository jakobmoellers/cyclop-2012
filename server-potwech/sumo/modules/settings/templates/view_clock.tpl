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
		.close-clock {
			font-size:7px;
			text-align:right;
			position:relative;
			top:-7px;
			left:5px;
		}
		.date-big {
			font-size:17px;
			color:#FEFEFE;
			letter-spacing:1px;
			position:relative;
			top:-7px;
		}
		.clock-big {
			font-size:30px;
			color:#FFFFFF;
			padding-left:15px;
			padding-right:15px;
		}
	  </style>
  	  <div class="close-clock"><a href='javascript:sumo_remove_window("{{GET:WindowElement}}");'><img src='themes/{{GET:PageTheme}}/images/modules/settings/close_clock.gif' style='opacity: 0.6'></a></div>
  	  <div class="date-big">{{GET:Date}}</div>
	  <div class="clock-big">{{GET:Time}}</div>

  </td>
  <td style='background: url("themes/{{GET:PageTheme}}/images/modules/settings/bg_dx_middle.gif") repeat-y'></td>
 </tr>
 <tr>
  <td><img src='themes/{{GET:PageTheme}}/images/modules/settings/bg_sx_bottom.gif'></td>
  <td style='background: url("themes/{{GET:PageTheme}}/images/modules/settings/bg_middle_bottom.gif") repeat-x'></td>
  <td><img src='themes/{{GET:PageTheme}}/images/modules/settings/bg_dx_bottom.gif'></td>
 </tr>
</table>