<?php
/**
 * SUMO MODULE: Network | Datasource Erase
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_datasource_info($_GET['id'], FALSE);
																	 
if($_GET['id'] == 1)
{
	$tpl['MESSAGE:M'] = $language['CannotDeleteDataSource'];
}
else 
{
	$delete = sumo_delete_datasource($_GET['id']);
	
	if($delete)
		$tpl['MESSAGE:L'] = sumo_get_message('DataSourceDeleted', $tab['name']);
	else 
		$tpl['MESSAGE:H'] = sumo_get_message('DataSourceNotDeleted', $tab['name']);
}

$tpl['GET:MenuModule'] = sumo_get_module_menu($menu['dlist'], 'dlist');

require "action.dlist.php";

?>