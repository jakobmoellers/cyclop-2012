<?php
/**
 * SUMO MODULE: Network | Modify Local IP Address
 * 
 * @version    0.2.10
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
							
$data = array(array('id',	   $_GET['id'],    1),
			  array('iptype',  $_POST['type'], 1),
			  array('iprange', $_POST['ip'],   1));
		
$validate = sumo_validate_data_network($data, TRUE);
				
if(!$validate[0]) 
{
	$tpl['MESSAGE:M'] = sumo_get_message('LocalIPNotUpdated').":<br>".$validate[1];
}
else 
{										
	$update = sumo_update_intranet_ip_data(array('id'	=> $_GET['id'],
												 'type' => $_POST['type'],
												 'ip'	=> $_POST['ip']));
	
	if($update)	
		$tpl['MESSAGE:L'] = sumo_get_message('LocalIPUpdated', $_POST['ip']);
	else 
		$tpl['MESSAGE:H'] = sumo_get_message('LocalIPNotUpdated');				
}	
		
				
require "action.edit_localip.php";
		
?>