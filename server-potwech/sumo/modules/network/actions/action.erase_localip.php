<?php
/**
 * SUMO MODULE: Network | Erase Local IP address
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */


$tab = sumo_get_intranet_ip_info($_GET['id'], FALSE);

$delete = sumo_delete_intranet_ip($tab['id']);

if($delete)	
	$tpl['MESSAGE:L'] = sumo_get_message('LocalIPDeleted', $tab['ip']);
else 
	$tpl['MESSAGE:H'] = sumo_get_message('LocalIPNotDeleted', $tab['ip']);


$tpl['GET:MenuModule'] = sumo_get_module_menu($menu['ilist'], 'ilist');

require "action.ilist.php";
	
?>