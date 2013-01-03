<?php
/**
 * SUMO MODULE: Help | Licence
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$lang = isset($_GET['language']) ? $_GET['language'] : $_COOKIE['language'];


$languages = sumo_get_available_languages();
$flags	   = "";

for($l=0; $l<count($languages); $l++)
{
	$flags .= "<a onclick='javascript:sumo_ajax_get(\"help.content\", \"?module=help&decoration=false&action=licence&language=".$languages[$l]."\")' href='#'>"
			 ."<img src='themes/".$SUMO['page']['theme']."/images/flags/".$languages[$l].".png' hspace='5'>"				
			 ."</a>";
}


if(!in_array($lang, $languages)) $lang = 'en';

$tpl['GET:Licence'] = $flags."<br><textarea rows='30' cols='75' style='font-size:10px' readonly>"
			.file_get_contents(SUMO_PATH.'/docs/licence_'.$lang.'.txt')
			."</textarea>"
			."<br><br><a href='http://www.gnu.org/licenses/old-licenses/gpl-2.0-translations.html' target='_new'>Unofficial GNU GPL v2.0 Translations</a>";

?>