<?php
/**
 * SUMO MODULE: Network | Local network list
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

if($_SESSION['search_network_ilist']) 
{								
	$field['ip'] = sumo_search_composer($_SESSION['search_network_ilist'], 'ip');
				
	$search = $field['ip'][0] ? " WHERE ".$field['ip'][0]." " : '';
} 
			
$query1 = "SELECT * FROM ".SUMO_TABLE_INTRANETIP." ".$search;
$query2	= $query1." ORDER BY ".$_SESSION['network']['ilist']['col_sql']." ".$_SESSION['network']['ilist']['mode_sql'];	
$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_network_ilist'], $_SESSION['start_network_ilist']);
$vis = $rs->PO_RecordCount();
	
$list = sumo_get_table_header($table['data']['ilist']);


/**
 * Create list
 */
$col = $_SESSION['network']['ilist']['col'];

while($tab = $rs->FetchRow()) 
{			
	$style = sumo_alternate_str('tab-row-on', 'tab-row-off');
				
	if($search) 
	{
		$tab['ip'] = sumo_color_match_string($field['ip'][1], $tab['ip']);
	}
				
	switch ($tab['type']) 
	{
		case 'L': $tab['type'] = $language['Locale']; break;
		case 'P': $tab['type'] = $language['Proxy'];  break;
		default:  $tab['type'] = $language['Unknow']; break;
	}
	
	
	// verify permission to delete node
	// NOTE: NOT use sumo_verify_permissions() for best performance!
	$delete = '';
	
	if($SUMO['user']['group_level']['sumo'] > 4) 
	{	
		$msg = sumo_get_simple_rand_string(4, "123456789");
		
		$delete = "<a href=\"javascript:"
				 ."sumo_show_message('msg$msg', '".htmlspecialchars(sumo_get_message('AreYouSureDeleteLocalIP', array($tab['ip'], $tab['type'])))."', 
									 'h', 0,
									 '".base64_encode(sumo_get_form_req('', 'erase_localip', 'id='.$tab['id']))."',
									 '".base64_encode('')."',
									 '".base64_encode("<input type='button' value='".$language['Cancel']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."',
									 '".base64_encode("<input type='submit' value='".$language['Ok']."' onclick='javascript:sumo_remove_window(\"msg$msg\");' class='button'>")."'
									);\">"
				 ."<img src='themes/".$SUMO['page']['theme']."/images/modules/network/remove.gif'></a>&nbsp;&nbsp;";
	}
	
	
	$list .= "<tr>\n";
		if($col[3])  $list .= "<td class='".$style."'>"
							 .$delete
							 ."<a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_localip&id=".$tab['id']."\");' title='".$language['View']."'>"
							 .$tab['type']
							 ."</a></td>\n";
		if($col[2])  $list .= "<td class='".$style."' align='right'>"
							 ."<a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_localip&id=".$tab['id']."\");' title='".$language['View']."'>"
							 .$tab['ip']
							 ."</a></td>\n";
	$list .= "</tr>\n";			
}
	
$list    .= "</table>";			
	
	
$searched = $search ? $_SESSION['search_network_ilist'] : '';


// Template Data
$tpl = array(
			  'MESSAGE:H'			 => $tpl['MESSAGE:H'],
			  'MESSAGE:M'			 => $tpl['MESSAGE:M'],
			  'MESSAGE:L'			 => $tpl['MESSAGE:L'],
			  'GET:Theme'		  	 => $SUMO['page']['theme'],
			  'GET:MenuModule' 	     => $tpl['GET:MenuModule'],
			  'GET:LocalNetworkList' => $list,						  
			  'GET:TotalRows'	     => number_format($tot, 0, "", "."),
			  'GET:StartRow'         => number_format($_SESSION['start_network_ilist'], 0, "", "."),
			  'GET:EndRow'   	     => number_format($_SESSION['start_network_ilist'] + $vis, 0, "", "."),
			  'GET:PagingResults'    => sumo_paging_results($tot, $vis, $_SESSION['rows_network_ilist'], 5, $_SESSION['start_network_ilist'], 'start_network_ilist'),
			  'GET:TableSettings'    => sumo_get_table_settings($table['data']['ilist']),
			  'GET:SearchForm'       => sumo_get_form_search($searched),					  
			  'GET:ExportData'	     => ''
	 		 );
		
$tpl['GET:Pagination'] = $tot>0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";

?>