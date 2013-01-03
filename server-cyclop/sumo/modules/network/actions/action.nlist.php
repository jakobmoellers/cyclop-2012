<?php
/**
 * SUMO MODULE: Network | Network List
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */


sumo_set_table_settings();				
					
// Create search query		
$search = '';

if($_SESSION['search_network_nlist']) 
{
	$field['name'] = sumo_search_composer($_SESSION['search_network_nlist'], 'name');
	$field['host'] = sumo_search_composer($_SESSION['search_network_nlist'], 'host');
		
	$search = ($field['name'][0] && $field['host'][0]) ? " WHERE (".$field['name'][0]." OR ".$field['host'][0].") " : '';
} 
		
$query1 = "SELECT * FROM ".SUMO_TABLE_NODES." ".$search;
$query2	= $query1." ORDER BY ".$_SESSION['network']['nlist']['col_sql']." ".$_SESSION['network']['nlist']['mode_sql'];	
				
$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_network_nlist'], $_SESSION['start_network_nlist']);
$vis = $rs->PO_RecordCount();


/**
 * Create list
 */
$list = sumo_get_table_header($table['data']['nlist']);
$col  = $_SESSION['network']['nlist']['col'];

while($tab = $rs->FetchRow()) 
{						
	$style  = sumo_alternate_str('tab-row-on', 'tab-row-off');					
	$active = $tab['active'] ? 'enabled' : 'disabled';
			
	$tab['name'] = $tab['active'] ? $tab['name'] : "<font color='gray'>".$tab['name']."</font>";
	
	if($search) 
	{
		$tab['name'] = sumo_color_match_string($field['name'][1], $tab['name']);
		$tab['host'] = sumo_color_match_string($field['host'][1], $tab['host']);				
	}
			
	/**
	 * Verify node status and response time
	 */
	if($_GET['test'] && $tab['active'])
	{
		list($usec, $sec) = explode(" ",microtime());
		
		$time_start = ((float)$usec + (float)$sec);
		
		$status = sumo_get_http_contents($tab['host'], 
								$tab['sumo_path'].'services.php?module=network&service=network&cmd=GET_NODE_STATUS', 
								$tab['port'],
								$tab['protocol']);	
		//$status = file_get_contents($tab['protocol']."://".$tab['host'].":".$tab['port'].$tab['sumo_path'].'services.php?module=network&service=network&cmd=GET_NODE_STATUS', null, null, null, 128);
		
		list($usec, $sec) = explode(" ", microtime());
		
		$time_end = ((float)$usec + (float)$sec);
		$time     = round($time_end - $time_start, 4);
	
		if($status != 'I00013X') 
		{
			$style2 = "class='tab-row-highlight' style='color:#BB0000;'";
			$status = "<blink><b>".$language['Failed']."</b></blink> &raquo;".strip_tags($status);
		}
		else 
		{
			$style2 = "class='$style' style='color:#00BB00;'";
			$status = "<b>".$language['Ok']."</b> ".$time." sec.";
		}
	}
	elseif($_GET['test'] && !$tab['active'])
	{
		$style2 = "class='$style' style='color:#BB0000;'";
		$status = $language['Disabled'];
	}
	else 
	{
		$style2 = "class='$style'";
		$status = '&nbsp;';
	}
	
	// Get numbers of AP for a node
	$ap = sumo_get_accesspoints_on_node($tab['id']);
	$ap = $ap == 0 ? "<font color='#BB0000'>-</font>" : "<a href='javascript:sumo_ajax_get(\"accesspoints\",\"?module=accesspoints&action=list&oc=2\");'>".number_format($ap, 0, "", ".")."</a>";
		
	// verify if it's current node
	if($SUMO['page']['node'] == $tab['id']) $style  = 'tab-row-highlight';
		
	$list .= "<tr>\n";			
		if($col[100]) $list .= " <td '".$style2."'>".$status."</td>\n";	
		if($col[2])   $list .= " <td class='".$style."'><img src='themes/".$SUMO['page']['theme']."/images/modules/network/".$active.".gif'></td>\n";
		if($col[5])   $list .= " <td class='".$style."'>"
							  ."<a href='javascript:sumo_ajax_get(\"network.content\",\"?module=network&action=view_node&id=".$tab['id']."&decoration=false\");'>"
							  .$tab['name']
							  ."</a></td>\n";			
		if($col[3])   $list .= " <td class='".$style."' align='right'>".$tab['host']."</td>\n";
		if($col[4])   $list .= " <td class='".$style."' align='right'>".$tab['port']."</td>\n";
		if($col[6])   $list .= " <td class='".$style."'>".$tab['protocol']."</td>\n";
		if($col[7])   $list .= " <td class='".$style."'>".$tab['sumo_path']."</td>\n";
		if($col[8])   $list .= " <td class='".$style."' align='right'>".$ap."</td>\n";
	$list .= "</tr>\n";			
}
	
$list .= "</table>";	
	
	
$searched = $search ? $_SESSION['search_network_nlist'] : '';

// Template Data
$tpl = array(
		'MESSAGE:H'		=> $tpl['MESSAGE:H'],
		'MESSAGE:M'		=> $tpl['MESSAGE:M'],
		'MESSAGE:L'		=> $tpl['MESSAGE:L'],
		'GET:Theme'		=> $SUMO['page']['theme'],
		'GET:MenuModule' 	=> $tpl['GET:MenuModule'],
		'GET:NodesList'	  	=> $list,
		'GET:TotalRows'	  	=> number_format($tot, 0, "", "."),
		'GET:StartRow'      	=> number_format($_SESSION['start_network_nlist'], 0, "", "."),
		'GET:EndRow'   	  	=> number_format($_SESSION['start_network_nlist'] + $vis, 0, "", "."),
		'GET:PagingResults'	=> sumo_paging_results($tot, $vis, $_SESSION['rows_network_nlist'], 5, $_SESSION['start_network_nlist'], 'start_network_nlist'),
		'GET:TableSettings'	=> sumo_get_table_settings($table['data']['nlist']),
		'GET:SearchForm'   	=> sumo_get_form_search($searched),					  
		'GET:ExportData'	=> '',
		'LINK:AddNode'	  	=> sumo_verify_permissions(4, 'sumo') ? sumo_get_action_icon("network", "add_node", "network.content", "?module=network&action=new_node&decoration=false") : sumo_get_action_icon("", "add_node"),
		'BUTTON:TestConnection' => "<input value='".$language['TestConnection']."' type='button' class='button' onclick='javascript:sumo_ajax_get(\"network.content\",\"?module=network&action=nlist&test=1&network_nlist_view_col=100.1&decoration=false"."\");'>"
	);

$tpl['GET:Pagination'] = $tot>0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";
		 
?>