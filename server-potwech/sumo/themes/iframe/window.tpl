<table id='window{{GET:WindowElement}}'>
 <tr>
  <td class='window-top-sx'></td>
  <td class='window-top'><table style='width:100%'>
	<tr>
		<td class='window-title' onmouseup='javascript:{{GET:SaveWindowSettings}}'>{{IMG:WindowIcon}} {{GET:WindowTitle}}</td>
		<td style='background:none'><div id='minwin{{GET:WindowElement}}' style='visibility:hidden' onclick='{{GET:WindowMinimize}}'><img src='{{GET:PagePath}}themes/{{GET:PageTheme}}/images/wx_minimize.png'
        	onmouseover='this.src="{{GET:PagePath}}themes/{{GET:PageTheme}}/images/wx_minimize_on.png";'
			onmouseout='this.src="{{GET:PagePath}}themes/{{GET:PageTheme}}/images/wx_minimize.png";'></div></td>
		<td style='background:none'><div onclick='{{GET:WindowMinimize}}'><img src='themes/{{GET:PageTheme}}/images/wx_minimize.png'
        	onmouseover='this.src="themes/{{GET:PageTheme}}/images/wx_minimize_on.png";'
			onmouseout='this.src="themes/{{GET:PageTheme}}/images/wx_minimize.png";' hspace='4'></div></td>
        <td style='background:none'><div onclick='{{GET:WindowClose}}'><img src='themes/{{GET:PageTheme}}/images/wx_close.png'
        	onmouseover='this.src="themes/{{GET:PageTheme}}/images/wx_close_on.png";'
			onmouseout='this.src="themes/{{GET:PageTheme}}/images/wx_close.png";'></div></td>
	 </tr>
	</table></td>
  <td class='window-top-dx'></td>
 </tr>
 <tr>
  <td class='window-middle-sx'></td>
  <td class='window-middle'>
  	<div id='{{GET:WindowModule}}.content'>{{GET:WindowContent}}</div>
  </td>
  <td class='window-middle-dx'></td>
 </tr>
 <tr>
  <td><img src='themes/{{GET:PageTheme}}/images/wx_bottom_sx.png'></td>
  <td class='window-bottom'></td>
  <td><img src='themes/{{GET:PageTheme}}/images/wx_bottom_dx.png'></td>
 </tr>
</table>
<!--[if lt IE 7]>
<script defer language='javascript' type='text/javascript'>
	PNGFix();
</script>
<![endif]-->