<?php
/**
 * SUMO MODULE: Network | Modify Node
 * 
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
		
$data = array(array('id',	  		$_GET['id'],    1),
			  array('node_name',	$_POST['name'], 1),
			  array('host',	  		$_POST['host'], 1),
			  array('port',   		$_POST['port'], 1),
			  array('status', 	    $_POST['status'],    1),						  
			  array('protocol',		$_POST['protocol'],  1),
			  array('sumo_path', 	$_POST['sumo_path'], 1));

$validate = sumo_validate_data_network($data, TRUE);
				
if(!$validate[0]) 
{				
	$tpl['MESSAGE:H'] = sumo_get_message('NodeNotUpdated').":<br>".$validate[1];
}
else 
{							
	$update = sumo_update_node_data(array('id'        => $_GET['id'],
									  	  'name'	  => $_POST['name'],
									  	  'host'  	  => $_POST['host'],
									  	  'port'      => $_POST['port'],
									  	  'active'    => $_POST['status'],												  	  
									  	  'protocol'  => $_POST['protocol'],
									  	  'sumo_path' => $_POST['sumo_path']));

	if($update)	
		$tpl['MESSAGE:L'] = sumo_get_message('NodeUpdated');
	else 
		$tpl['MESSAGE:H'] = sumo_get_message('NodeNotUpdated');
}	

require "action.edit_node.php";
        
?>