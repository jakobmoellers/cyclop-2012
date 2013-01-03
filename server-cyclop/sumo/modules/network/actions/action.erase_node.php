<?php
/**
 * SUMO MODULE: Network | Erase Node
 * 
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */


$tab = sumo_get_node_info($_GET['id'], 'id', FALSE);
	
$tpl['GET:DeleteForm'] = sumo_get_form_req('', 'erase_node', 'id='.$tab['id']);

if($_GET['id'] == 1)
{
	$tpl['MESSAGE:H'] = $language['CannotDeleteNode'];
}
else 
{		
	$delete = sumo_delete_node($_GET['id']);
	
	if($delete)
		$tpl['MESSAGE:L'] = sumo_get_message('NodeDeleted', $tab['name']);
	else 
		$tpl['MESSAGE:H'] = sumo_get_message('NodeNotDeleted', $tab['name']);	
}

$tpl['GET:MenuModule'] = sumo_get_module_menu($menu['nlist'], 'nlist');

require "action.nlist.php";

?>