<?php
/**
 * SUMO MODULE: Network | New Local IP address
 * 
 * @version    0.2.10
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */
						
switch ($_POST['type'])
{
	case 'L':
		$type = $language['Locale'];
		break;
		
	case 'P':
		$type = $language['Proxy'];
		break;
		
	default:
		$type = '';
		break;
}
				
$tpl['PUT:LocalIPType'] = "<select name='type'>\n<option value='".$_POST['type']."'>".$type."</option>\n"
						 ."<option value='L'>".$language['Locale']."</option>\n<option value='P'>".$language['Proxy']."</option>\n"
						 ."</select>";
$tpl['PUT:IP']    		= "<input type='text' size='25' name='ip' value='".$_POST['ip']."'>";
$tpl['BUTTON:Back']		= "<input type='button' class='button-red' value='".$language["Back"]."' onclick='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_localip&id=".$tab['id']."\");'>";
$tpl['GET:AddForm']	 	= sumo_get_form_req('', 'add_localip');
				
?>