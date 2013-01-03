<?php
/**
 * SUMO MODULE: Manpages | View
 * 
 * @version    0.2.10
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$m 	  = $_GET['mod']  ? $_GET['mod'] : $_SESSION['module'];
$page = $_GET['page'] ? intval($_GET['page']) : 0;
$lang = $_COOKIE['language'];
$file = SUMO_PATH_MODULE."/pages/".$lang."/".$m.".".$page.".html";
	
$manpages = sumo_get_available_manpages($lang);
		

// Load Manpage
$tpl['GET:ManPage'] = file_exists($file) ? file_get_contents($file) : $language['ManPageNotFound'];
	
// Create links to required manpages
for($p=0; $p<count($manpages); $p++)
{
	$man = explode(".", $manpages[$p]);		
		
	$link = "<a href=\"javascript:"
		   ."sumo_ajax_get('manpages.content','?module=manpages&decoration=false&mod=".$man[0]."&action=view&page=".$man[1]."');"
		   ."\">";

	$tpl['GET:ManPage'] = str_replace("<MAN:".$manpages[$p].">", $link, $tpl['GET:ManPage']);
	$tpl['GET:ManPage'] = str_replace("</MAN>", "</a>", $tpl['GET:ManPage']);		
}	
	
?>