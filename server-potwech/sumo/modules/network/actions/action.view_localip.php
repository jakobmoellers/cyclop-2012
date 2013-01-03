<?php
/**
 * SUMO MODULE: Network | View Local IP address
 * 
 * @version    0.4.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

$tab = sumo_get_intranet_ip_info($_GET['id'], 'id', FALSE);
			
switch ($tab['type']) 
{
	case 'L': $type = $language['Locale']; break;
	case 'P': $type = $language['Proxy'];  break;
	default:  $type = $language['Unknow']; break;
}
	
$tpl = array(
			 'GET:Theme'	    => $SUMO['page']['theme'],
			 'GET:MenuModule'	=> $tpl['GET:MenuModule'],
			 'GET:LocalIPType'  => $type,
			 'GET:IP'     	    => $tab['ip'],
			 'LINK:EditLocalIP' => "<a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=edit_localip&id=".$tab['id']."\");' title='".$language["EditDataSource"]."'>".$language["Modify"]."</a>",
			 'LINK:Remove'		=> sumo_verify_permissions(4, 'sumo') ? "<a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=delete_localip&id=".$tab['id']."\");' title='".$language['Remove']."'>".$language['Remove']."</a>" : $language['Remove'],
			 'BUTTON:Back'	  	=> "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"network\",\"?module=network&action=ilist\");'>"
			);

?>