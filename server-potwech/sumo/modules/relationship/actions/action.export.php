<?php
/**
 * SUMO MODULE: Relationship | Export Data
 * 
 * @version    0.3.5
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

switch ($_GET['submodule'])
{
	case 'group2users':       $exp = 'group2users';       break;
	case 'accesspoint2users': $exp = 'accesspoint2users'; break;
	case 'accesspoint2group': $exp = 'accesspoint2group'; break;
	case 'group2accesspoint': $exp = 'group2accesspoint'; break;
	case 'user2accesspoint':  $exp = 'user2accesspoint'; break;
}

require SUMO_PATH_MODULE."/actions/action.export.".$exp.".php";

?>