<?php
/**
 * SUMO: Load required core language file 
 *
 * @version    0.3.5
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

if(!empty($_REQUEST['sumo_lang']) || !$_COOKIE['language']) 
{		
	$available_lang = sumo_get_available_languages();	
		
	// try to detect browser language...
	$detected_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	$default_lang  = in_array($detected_lang, $available_lang)         ? $detected_lang         : $SUMO['config']['server']['language'];	
	$language 	   = in_array($_REQUEST['sumo_lang'], $available_lang) ? $_REQUEST['sumo_lang'] : $default_lang;
	
	setcookie('language', $language, $SUMO['server']['time']+5184000); // 60gg
	
	// ...because cookie need time to write ;)
	$_COOKIE['language'] = $language;
}
                                                               
// Load core language file
$lang_core  = SUMO_PATH."/languages/".$SUMO['config']['server']['language']."/lang.core.php";
$lang_login = SUMO_PATH."/languages/".$_COOKIE['language']."/lang.login.php";

if(sumo_verify_file($lang_core))  require $lang_core;
if(sumo_verify_file($lang_login)) require $lang_login;

$sumo_lang_core = array_merge($sumo_lang_core, $sumo_lang_login);

?>