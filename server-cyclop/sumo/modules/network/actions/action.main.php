<?php
/**
 * SUMO MODULE: Network | Main
 * 
 * @version    0.2.10
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tpl = array(
			 'GET:ModuleIconNetworkNodes'  	  	 => sumo_get_module_icon('network', 'nlist', $language['Nodes'],        false, 'nodes'),
			 'GET:ModuleIconNetworkDatasources'  => sumo_get_module_icon('network', 'dlist', $language['DataSources'],  false, 'datasources'),
			 'GET:ModuleIconNetworkLocalNetwork' => sumo_get_module_icon('network', 'ilist', $language['LocalNetwork'], false, 'intranet')
			);
				
?>