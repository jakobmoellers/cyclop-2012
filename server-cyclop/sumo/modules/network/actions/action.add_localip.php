<?php
/**
 * SUMO MODULE: Network | Add Local IP address
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
								
$data = array(array('iptype',  $_POST['type'], 1),
			  array('iprange', $_POST['ip'],   1));
			
$validate = sumo_validate_data_network($data, TRUE);
			
if(!$validate[0]) 
{
	$tpl['MESSAGE:H'] = sumo_get_message('LocalIPNotAdded').":<br>".$validate[1];
}
else 
{										
	$insert = sumo_add_intranet_ip(array('type' => $_POST['type'],
										 'ip'	=> $_POST['ip']));
	if($insert)	
		$tpl['MESSAGE:L'] = sumo_get_message('LocalIPAdded', $_POST['ip']);					
	else 
		$tpl['MESSAGE:H'] = sumo_get_message('LocalIPNotAdded');							
}	
	
require "action.new_localip.php";
				
?>