<?php
/**
 * SUMO MODULE: Messages | Main
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$m    = intval($_GET['m']);
$msg  = $_GET['msg'];
$data = explode("_", $_GET['data']);

$onclick = array(
				 // Hight
				 'ErrorsMessages'  => 'sumo_ajax_get(\'security\',\'?module=security&action=errors_list\');'
				 					 .'sumo_remove_window(\'messages'.$m.'\')',
				 'BannedUsers'     => 'sumo_ajax_get(\'security\',\'?module=security&action=banned\');'
				 					 .'sumo_remove_window(\'messages'.$m.'\')',
				 'ChangePassword'  => 'sumo_ajax_get(\'users\',\'?module=users&action=view&id='.$data[0].'\');'
				 					 .'sumo_remove_window(\'messages'.$m.'\')',
				 'SQLiteError'	   => '',
				 
				 // Medium
				 'IP2CountryEmpty'   => 'sumo_ajax_get(\'settings\',\'?module=settings&action=edit\');'
				 					   .'sumo_remove_window(\'messages'.$m.'\')',
				 'RemoveExamplesDir' => '',
				 'RemoveInstallDir'	 => '',
				 
				 // Low
				 'UserLogin'       => 'sumo_remove_window(\'messages'.$m.'\')',
				 'UserLogout'	   => 'sumo_remove_window(\'messages'.$m.'\')',
				 
				 $language['Undefined']	=> 'sumo_remove_window(\'messages'.$m.'\')'
				);

$msg = in_array($msg, array_keys($onclick)) ? $msg : $language['Undefined'];
				
$tpl = array(
			  'GET:PageTheme'	  => $SUMO['page']['theme'],
			  'GET:WindowElement' => 'messages'.$m,
			  'MESSAGE'	  	  	  => sumo_get_message($msg, $data),
			  'GET:Cancel' 		  => '<input type="button" class="button" value="'.$language['Cancel'].'" '
			  						.'onclick="javascript:sumo_remove_window(\'messages'.$m.'\');">',
			  'GET:Ok' 		   	  => '<input type="button" class="button" value="'.$language['Ok'].'" '
			  						.'onclick="javascript:'.$onclick[$msg].';">',
			  'GET:Close' 	  	  => '<input type="button" class="button" value="'.$language['Close'].'" '
			  						.'onclick="javascript:sumo_remove_window(\'messages'.$m.'\');">'
			 );
			
$tpl_file = $service[$_GET['cmd']]['template'];
 
?>