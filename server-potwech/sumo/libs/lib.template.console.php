<?php
/**
 * SUMO TEMPLATE LIBRARY
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Console
 */

// Fix PNG images if client browser is Internet Explorer
$pngfix = preg_match("/Internet Explorer/i", $SUMO['client']['browser']) ? "PNGFix();" : "";

// Create IP2Country table for first installation (1min available before timeout)
$ip2country = (!file_exists(SUMO_PATH.'/tmp/iptocountry') && $SUMO['server']['db_type'] != 'sqlite') ? "sumo_ajax_get_bg(\"services.php?service=updater&cmd=UPDATE_IP2C\");" : "";

// If user is "sumo" display access level
if(sumo_verify_current_group('sumo'))
{
    if($SUMO['user']['group_level']['sumo'] >= 1) $ul_color = 'green';
    if($SUMO['user']['group_level']['sumo'] >= 4) $ul_color = 'orange';
    if($SUMO['user']['group_level']['sumo'] >  5) $ul_color = 'red';
    
    $ul_graph = sumo_get_graph($SUMO['user']['group_level']['sumo'], 7, 0, $ul_color, 50, 2);
}
else $ul_graph = "";

// Clock
$clock = explode(':', $SUMO['config']['server']['time_format']);
$clock = date(intval($clock[0]).':'.$clock[1]);

$console['template'] = array(

"GET:SumoVersion"      	  => SUMO_VERSION,
"GET:UserName"		  => "<iframe name='CSID' src='' style='visibility:hidden;width:0px;height:0px;display:none'></iframe>" // SID
			    ."<a style='color:black;' href='javascript:sumo_ajax_get(\"users\",\"?module=users&action=view&id=".$SUMO['user']['id']."\");'>".$SUMO['user']['user'].$ul_graph."</a>",
"GET:PagePath"	  	  => $SUMO['page']['web_path'],
"GET:PageUrl"	   	  => $SUMO['page']['url'],
"GET:PageName"		  => sumo_get_accesspoint_name($SUMO['page']['name'], $_COOKIE['language']),
"GET:PageTheme"		  => $SUMO['page']['theme'],
"GET:charset"		  => $SUMO['config']['server']['charset'],
"GET:Date" 		  => "<a href='javascript:opacity(\"settings_view_clock\", 0, 100, 300);"
                            ."sumo_ajax_get(\"settings_view_clock\", \"?module=settings&action=view_clock&decoration=false\")'>"
                            .date($SUMO['config']['server']['date_format'])
                            ."</a>",
"GET:Clock"		  => "<a href='javascript:opacity(\"settings_view_clock\", 0, 100, 300);"
			    ."sumo_ajax_get(\"settings_view_clock\", \"?module=settings&action=view_clock&decoration=false\")'>"
			    ."<span id='clock'>".$clock."</span>"
                            ."</a>",
"GET:ScriptTooltip"	  => sumo_get_script_tag('wz_tooltip.js')."\n"
                            .sumo_get_script_tag('tip_centerwindow.js'),
"GET:ScriptDragDrop"	  => sumo_get_script_tag('wz_dragdrop.js'),
"GET:ScriptLibraries" 	  => "<script language='javascript' type='text/javascript'>\n"
                            ."var sumo_theme='".$SUMO['page']['theme']."';\n"
                            ."</script>\n"
                            .sumo_get_script_tag('ajax.js')."\n"
			    .sumo_get_script_tag('sumo_common.js')."\n"
			    .sumo_get_script_tag('sumo_crypt.js')."\n"
			    .sumo_get_script_tag('sumo_ajax.js')."\n"
			    .sumo_get_script_tag('sumo_gui.js')."\n"
			    .sumo_get_script_tag('sumo_menu.js')."\n"
			    .sumo_get_script_tag('calendar.php?sumo_lang='.$_COOKIE['language'])."\n"
			    .sumo_get_script_tag('clock.php?sumo_lang='.$_COOKIE['language'])."\n"
			    .sumo_get_script_tag("messages.php?id=".$SUMO['user']['id']."&loggedin=".intval($_COOKIE['loggedin'])."&group=".base64_encode(implode(";", $SUMO['user']['group'])))."\n",
"GET:OnLoad"		  => "onload='javascript:startClock();opacity(\"menuConsole\", 100, 88, 1);opacity(\"menuLanguages\", 100, 88, 1);".$pngfix.$ip2country."'",
"GET:Note"		  => $sumo_lang_core['PoweredBy']." <b>SUMO ".SUMO_VERSION."</b> &minus; &copy; Copyright 2003-".date("Y")." by <b>Basso Alberto</b><br>"
                            .$sumo_lang_core['ProjectPage']." <b><a href='http://sumoam.sourceforge.net' target='_blank'>http://sumoam.sourceforge.net</a></b>",

"LINK:Console"		  => "<a href='javascript:void(0)' onClick='return clickreturnvalue()' onMouseover='dropdownmenu(this, event, \"menuConsole\");' title='".$console['language']['ConsoleTitle']."'>".$console['language']['Console']."</a>",
"LINK:Clean"		  => "<a href='".$SUMO['page']['url']."' title='".$console['language']['CleanTitle']."'><img src='".$SUMO['page']['web_path']."/themes/".$SUMO['page']['theme']."/images/desktop.png' alt='".$console['language']['Clean']."'></a>",
"LINK:LogOut"		  => "<a href='javascript:sumo_user_logout();' title='".$console['language']['LogOutTitle']."'>".$console['language']['LogOut']."</a>",
"LINK:Help"		  => sumo_get_module_link('help', '', $console['language']['help'], false),

"BUTTON:Ok"	   	  => "<input type='submit' class='button' value='".$sumo_lang_core["Ok"]."'>",
"BUTTON:Submit"           => "<input id='ok' type='submit' class='button-green' value='".$sumo_lang_core["Ok"]."'>",
"BUTTON:Save"	          => "<input id='save' type='submit' class='button-green' value='".$console['language']["Save"]."'>",
"BUTTON:Back"	          => "<input type='button' class='button-red' value='".$console['language']["Back"]."' onclick='javascript:sumo_ajax_get(\"".$_SESSION['module'].".content\",\"?module=".$_SESSION['module']."&decoration=false\");'>",
"BUTTON:Cancel"	  	  => "<input id='cancel' type='button' class='button-red' value='".$console['language']["Cancel"]."' onclick='javascript:sumo_ajax_get(\"".$_SESSION['module'].".content\",\"?module=".$_SESSION['module']."&decoration=false\");'>"

);

?>
