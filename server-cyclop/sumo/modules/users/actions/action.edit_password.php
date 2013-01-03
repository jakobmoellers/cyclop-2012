<?php
/**
 * SUMO MODULE: Users | Edit password
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tpl = array(
	'MESSAGE:H'	    => $tpl['MESSAGE:H'],
	'MESSAGE:M'	    => $tpl['MESSAGE:M'],
	'MESSAGE:L'	    => $tpl['MESSAGE:L'],
	'PUT:NewPassword'   => "<input type='password' class='password' size='20' name='new_password' autocomplete='off'>",
	'PUT:ReNewPassword' => "<input type='password' class='password' size='20' name='renew_password' autocomplete='off'>",
	'GET:FormName'	    => sumo_get_form_name('', 'modify_password'),
	'GET:UpdateForm'    => sumo_get_form_req('', 'modify_password'),
	'BUTTON:Save'       => '<input type="submit" class="button-green" value="'.$language["Save"].'">',
	'BUTTON:Cancel'     => '<input type="button" class="button-red" value="'.$language["Cancel"].'" onClick="sumo_remove_window(\'users\');">',
	'GET:WindowScripts' => $tpl['GET:WindowScripts']
);

?>