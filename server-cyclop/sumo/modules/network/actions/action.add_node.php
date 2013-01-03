<?php
/**
 * SUMO MODULE: Network | Add Node
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
					
if($_POST['host'] == '127.0.0.1') $_POST['host'] = 'localhost';

$data = array(array('node_name', $_POST['name'],      1),
		array('host',  	 $_POST['host'],      1),
		array('port',      $_POST['port'],      1),
		array('status',    $_POST['status'],    1),						  
		array('protocol',  $_POST['protocol'],  1),
		array('sumo_path', $_POST['sumo_path'], 1));
		
$validate = sumo_validate_data_network($data, TRUE);

// verify if node already exist
if(sumo_verify_node_exist($_POST)) $validate = array(FALSE, sumo_get_message('W09016C'));
	
				
if(!$validate[0]) 
{
	$tpl['MESSAGE:H'] = sumo_get_message('NodeNotAdded').":<br>".$validate[1];
}
else 
{				
	$insert = sumo_add_node(array(	'name'	    => $_POST['name'],
					'host'	    => $_POST['host'],
					'port'	    => $_POST['port'],
					'active'    => $_POST['status'],										  	  
					'protocol'  => $_POST['protocol'],
					'sumo_path' => $_POST['sumo_path']));
				
	if($insert) { 
		$tpl['MESSAGE:L'] = sumo_get_message('NodeAdded');
		$tpl['GET:WindowScripts'] = "sumo_ajax_get('network.content','?module=network&action=nlist&decoration=false');";
	}	
	else 
		$tpl['MESSAGE:H'] = sumo_get_message('NodeNotAdded');
}

require "action.new_node.php";

?>	