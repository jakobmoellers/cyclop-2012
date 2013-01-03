<?php
/**
 * SUMO MODULE: Settings | Modify Time
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

if($_GET['save'] && $_SERVER["USER"] == 'root')
{
	$d = intval($_POST['d']) > 0 ? intval($_POST['d']) : date("d");
	$m = intval($_POST['m']) > 0 ? intval($_POST['m']) : date("m");
	$Y = intval($_POST['Y']) >= 2008 ? intval($_POST['Y']) : date("Y");
	$H = intval($_POST['H']) > 0 ? intval($_POST['H']) : date("H");
	$i = intval($_POST['i']) > 0 ? intval($_POST['i']) : date("i");

	$d = $d < 10 ? "0".$d : $d;
	$m = $m < 10 ? "0".$m : $m;
	$H = $H < 10 ? "0".$H : $H;
	$i = $i < 10 ? "0".$i : $i;
	
	exec("date $m$d$H$i$Y");

	// update user session
	$query = "UPDATE ".SUMO_TABLE_SESSIONS."
			  SET expire=".(strtotime("$Y-$m-$d $H:$i") + $SUMO['config']['sessions']['timeout'])."
			  WHERE session_id='".$SUMO['client']['session_id']."'";
	
	// Reload page to update clock
	header("Location: ".$SUMO['page']['path']);
}


$tpl['GET:WindowElement'] = "settings_view_clock";
$tpl['GET:UpdateForm']    = "<form name='Modify_clockSettings' onsubmit=\"javascript:sumo_ajax_post('settings_modify_clock',this,true);return false;\" action='?module=settings&action=modify_clock&save=1&decoration=false' method='post'>";

$tpl['PUT:Date'] = "<input type='text' name='d' value='".date("d")."' size='2' class='setdate'>/"
				  ."<input type='text' name='m' value='".date("m")."' size='2' class='setdate'>/"
			 	  ."<input type='text' name='Y' value='".date("Y")."' size='4' class='setdate'>";

$tpl['PUT:Time'] = "<input type='text' name='H' value='".date("H")."' size='2' class='setclock'>:"
				  ."<input type='text' name='i' value='".date("i")."' size='2' class='setclock'>";

?>