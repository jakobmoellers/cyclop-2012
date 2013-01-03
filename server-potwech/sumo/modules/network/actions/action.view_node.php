<?php
/**
 * SUMO MODULE: Network | View Node
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_node_info($_GET['id'], 'id', FALSE);

$msg 	= sumo_get_simple_rand_string(4, "123456789");
$delete = "<div class='sub-module-icon' "
		 ."onmouseover='this.style.outline=\"1px solid #999999\";this.style.background=\"#FFFFFF\"' "
		 ."onmouseout='this.style.outline=\"\";this.style.background=\"\"'>"
		 ."<a href=\"javascript:"
		 ."sumo_show_message('msg$msg', '".htmlspecialchars(sumo_get_message('AreYouSureDeleteNode', $tab['name']))."', 
							 'h', 0, 
							 '".base64_encode(sumo_get_form_req('', 'erase_node', 'id='.$tab['id']))."',
							 '".base64_encode('')."',
							 '".base64_encode("<input type='button' value='".$language['Cancel']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."',
							 '".base64_encode("<input type='submit' value='".$language['Ok']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."'
							);\">"
		 ."<img src='themes/".$SUMO['page']['theme']."/images/modules/network/remove_node.png' vspace='4'><br>"
		 .$language['Remove']
		 ."</a>"
		 ."</div>";
		 	 
$tpl = array(
            'MESSAGE:H'         => $tpl['MESSAGE:H'],
            'MESSAGE:M' 	=> $tpl['MESSAGE:M'],
	    'MESSAGE:L'	        => $tpl['MESSAGE:L'],
	    'GET:Theme'	        => $SUMO['page']['theme'],
	    'GET:MenuModule'    => $tpl['GET:MenuModule'],
	    'GET:Status'        => $tab['active'] ? "<font class='status-green'>".$language['Active']."</font>" : "<font class='status-red'>".$language['Disabled']."</font>",
	    'GET:NodeName'      => $tab['name'],
	    'GET:Host'    	=> $tab['host'],
	    'GET:Port'		=> $tab['port'],
            'GET:Protocol'	=> $tab['protocol'],					 
	    'GET:SumoPath'	=> $tab['sumo_path'],
            'LINK:Add'  	=> sumo_verify_permissions(4, 'sumo') ? sumo_get_action_icon("network", "add_node", "network.content", "?module=network&action=new_node&decoration=false") : sumo_get_action_icon("", "add_node"),
	    'LINK:Edit'  	=> (sumo_verify_permissions(4, 'sumo') && $tab['id'] > 1) ? sumo_get_action_icon("network", "edit_node", "network.content", "?module=network&action=edit_node&id=".$tab['id']."&decoration=false") : sumo_get_action_icon("", "edit_node"),
	    'LINK:Remove'       => (sumo_verify_permissions(4, 'sumo') && $tab['id'] > 1) ? $delete : sumo_get_action_icon("", "remove_node"),
	    'BUTTON:Back'	=> "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"network.content\",\"?module=network&action=nlist&decoration=false\");'>"			 
	    //'LINK:EditNode'  => (sumo_verify_permissions(4, 'sumo') && $tab['id'] > 1) ? "<a href='javascript:sumo_ajax_get(\"network.content\",\"?module=network&action=edit_node&id=".$tab['id']."&decoration=false\");' title='".$language["EditDataSource"]."'>".$language["Modify"]."</a>" : $language['Modify'],
	    //'LINK:Remove'	  => (sumo_verify_permissions(4, 'sumo') && $tab['id'] > 1) ? $delete : $language['Remove'],
	    //'BUTTON:Back'	  => "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"network.content\",\"?module=network&action=nlist&decoration=false\");'>"
	    );					

?>