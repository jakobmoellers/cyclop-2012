<?php
/**
 * SUMO MODULE: Settings | View Clock
 * 
 * @version    0.3.5
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tpl['GET:WindowElement'] = "settings_view_clock";

if($_SERVER["USER"] == 'root')
{
	$tpl['GET:Date'] = "<div ondblclick='javascript:sumo_ajax_get(\"".$tpl['GET:WindowElement']."\", \"?module=settings&action=modify_clock&decoration=false\")'>"
					  .date($SUMO['config']['server']['date_format'])
					  ."</div>";
					  
	$tpl['GET:Time'] = "<div id='clock-big' ondblclick='javascript:sumo_ajax_get(\"".$tpl['GET:WindowElement']."\", \"?module=settings&action=modify_clock&decoration=false\")'>"
					  .date($SUMO['config']['server']['time_format'])
					  ."</div>";
}
else 
{
	$tpl['GET:Date'] = date($SUMO['config']['server']['date_format']);
	$tpl['GET:Time'] = "<div id='clock-big'>".date($SUMO['config']['server']['time_format'])."</div>";
}

?>