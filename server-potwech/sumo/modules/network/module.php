<?php
/**
 * SUMO MODULE: Network
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */


/**
 * MODULE CONFIGURATION EXCEPTION!!!  :(
 */
$menu['new_datasource'] = $menu['add_datasource'] = $menu['view_datasource'] = $menu['edit_datasource'] = $menu['modify_datasource'] = $menu['dlist'];
$menu['new_localip'] = $menu['add_localip'] = $menu['view_localip'] = $menu['edit_localip'] = $menu['modify_localip'] = $menu['ilist'];
$menu['new_node'] = $menu['add_node'] = $menu['view_node'] = $menu['edit_node'] = $menu['modify_node'] = $menu['nlist'];

$tpl['GET:MenuModule'] = sumo_get_module_menu($menu[$action], $action);
$tpl['GET:Theme']      = $SUMO['page']['theme'];

?>