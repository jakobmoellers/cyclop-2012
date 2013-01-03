<?php
/**
 * SUMO MODULE: Groups | Delete Group
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab    = sumo_get_group_info($_GET['id']);
$delete = sumo_delete_group($_GET['id']);

//if($delete)
	$tpl['MESSAGE:L'] = sumo_get_message('GroupDeleted', $_POST['usergroup']);
//else 
	//$tpl['MESSAGE:H'] = $language['GroupNotDeleted'];


$_SESSION['action'] = 'list';
	
require "action.list.php";

?>