<?php
/**
 * SUMO MODULE: Network | Edit Local IP Address
 * 
 * @version    0.2.10
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
						
$tab = sumo_get_intranet_ip_info($_GET['id'], 'id', FALSE);
				
switch ($tab['type']) 
{
	case 'L':
		$type = $language['Locale'];
		break;
	
	case 'P':
		$type = $language['Proxy'];
		break;
		
	default:
		$type = $language['Unknow'];
		break;
}

$tpl['GET:UpdateForm']	 = sumo_get_form_req('', 'modify_localip', 'id='.$tab['id']);
$tpl['PUT:LocalIPType']  = "<select name='type'>\n<option value='".$tab['type']."'>".$type."</option>\n"
						  ."<option value='L'>".$language['Locale']."</option>\n<option value='P'>".$language['Proxy']."</option>\n"
						  ."</select>";
$tpl['PUT:IP']    		 = "<input type='text' size='25' name='ip' value='".$tab['ip']."'>";
$tpl['BUTTON:Back']		 = "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_localip&id=".$tab['id']."\");'>";
$tpl['LINK:EditLocalIP'] = $language["Modify"];

if(sumo_verify_permissions(4, 'sumo')) 
	$tpl['LINK:Remove'] = "<a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=delete_localip&id=".$tab['id']."\");' title='".$language['Remove']."'>".$language['Remove']."</a>";
else
	$tpl['LINK:Remove'] = $language['Remove'];
	
?>