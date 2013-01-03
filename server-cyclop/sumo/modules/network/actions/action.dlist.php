<?php
/**
 * SUMO MODULE: Network | Datasource List
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
	
if($_SESSION['search_network_dlist']) 
{			
	$field['name'] = sumo_search_composer($_SESSION['search_network_dlist'], 'name');
		
	$search = $field['name'][0] ? " WHERE ".$field['name'][0]." " : '';
} 
		
$query1 = "SELECT * FROM ".SUMO_TABLE_DATASOURCES." ".$search;
$query2	= $query1." ORDER BY ".$_SESSION['network']['dlist']['col_sql']." ".$_SESSION['network']['dlist']['mode_sql'];	
						
$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_network_dlist'], $_SESSION['start_network_dlist']);
$vis = $rs->PO_RecordCount();
	

/**
 * Create list
 */
$node = sumo_get_node_info(1, 'id', FALSE);  // to testing DS connection
$list = sumo_get_table_header($table['data']['dlist']);
$col  = $_SESSION['network']['dlist']['col'];

while($tab = $rs->FetchRow()) 
{			
	$style = sumo_alternate_str('tab-row-on', 'tab-row-off');
	
	if($search) 
	{
		$tab['name'] = sumo_color_match_string($field['name'][1], $tab['name']);
	}

	if($tab['password']) $tab['password'] = '******';
			
			
	$name = "<a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_datasource&id=".$tab['id']."\");' title='".$language['View']."'>"
		   .$tab['name']
		   ."</a>";
					
	// Check Datasource	
	if($tab['type'] != 'SUMO' && $_GET['test'])
	{			
		$url 		 = parse_url($node['protocol']."://".$node['host'].":".$node['port'].$node['sumo_path']."services.php");
		$url['path'] = $url['path']."?module=network&service=network&cmd=GET_DS_STATUS&id=".$tab['id']."&type=".$tab['type'];
		
		list($usec, $sec) = explode(" ", microtime());
		
		$time_start = ((float)$usec + (float)$sec);
		
		//$status = sumo_get_http_contents($url['host'], $url['path'], $url['port'], $url['scheme']);			
		$status = file_get_contents($node['protocol']."://".$node['host'].":".$node['port'].$url['path'], null, null, null, 128);
		
		list($usec, $sec) = explode(" ",microtime());
		
		$time_end = ((float)$usec + (float)$sec);
		$time = round($time_end - $time_start, 4);
		
		if($status == 0) 
		{
			$status  = "<div style='color:white;text-align:center;background:#BB3333 url(themes/".$SUMO['page']['theme']."/images/button_red.jpg) repeat-x;'>\n"
				      ."<blink><b>".$language['Failed']."</b></blink>"
				      ."</div>";
		}
		else 
		{
			$status  = "<div style='color:#00BB00'>"
					  ."<b>".$language['Ok']."</b> &rsaquo; ".$time."s"
					  ."</div>";
		}
		
	}
	else $status = '';
	
	//
	$tab['enctype'] = $tab['type'] == 'SUMO' ? 'sumo' : $tab['enctype'];
			
	$list .= "<tr>\n";
		if($col[0])  $list .= " <td class='".$style."'>".$status."</td>\n";
		if($col[2])  $list .= " <td class='".$style."'>".$name."</td>\n";
		if($col[3])  $list .= " <td class='".$style."'>".$language[$tab['type']]."</td>\n";
		if($col[4])  $list .= " <td class='".$style."'>".$tab['host']."</td>\n";
		if($col[5])  $list .= " <td class='".$style."' align='right'>".$tab['port']."</td>\n";
		if($col[6])  $list .= " <td class='".$style."'>".$tab['username']."</td>\n";
		if($col[8])  $list .= " <td class='".$style."'>".$tab['db_name']."</td>\n";
		if($col[12]) $list .= " <td class='".$style."'>".$tab['enctype']."</td>\n";
		if($col[13]) $list .= " <td class='".$style."'>".$tab['ldap_base']."</td>\n";
	$list .= "</tr>\n";			
}
		
$list .= "</table>";			
		
$searched = $search ? $_SESSION['search_network_dlist'] : '';
		

// Template Data
$tpl = array(
			  'MESSAGE:H'		  => $tpl['MESSAGE:H'],
			  'MESSAGE:M'		  => $tpl['MESSAGE:M'],
			  'MESSAGE:L'		  => $tpl['MESSAGE:L'],
			  'GET:Theme'		  => $SUMO['page']['theme'],
			  'GET:MenuModule' 	  => $tpl['GET:MenuModule'],
			  'GET:DataSourcesList'	  => $list,						  
			  'GET:TotalRows'	  => number_format($tot, 0, "", "."),
			  'GET:StartRow'          => number_format($_SESSION['start_network_dlist'], 0, "", "."),
			  'GET:EndRow'   	  => number_format($_SESSION['start_network_dlist'] + $vis, 0, "", "."),
			  'GET:PagingResults'     => sumo_paging_results($tot, $vis, $_SESSION['rows_network_dlist'], 5, $_SESSION['start_network_dlist'], 'start_network_dlist'),
			  'GET:TableSettings'     => sumo_get_table_settings($table['data']['dlist']),
			  'GET:SearchForm'        => sumo_get_form_search($searched),					  
			  'GET:ExportData'	  => sumo_get_export_data('network', 'dlist', 'export_datasource'),
			  'GET:AddDataSource'	  => sumo_verify_permissions(4, 'sumo') ? sumo_get_action_icon("network", "add_datasource", "network.content", "?module=network&action=new_datasource&decoration=false") : sumo_get_action_icon("", "add_datasource"),
			  'BUTTON:TestConnection' => "<input value='".$language['TestConnection']."' type='button' class='button' onclick='javascript:sumo_ajax_get(\"network.content\",\"?module=network&action=dlist&test=1&decoration=false&network_dlist_view_col=0.1"."\");'>"
	 		 );

$tpl['GET:Pagination'] = $tot>0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";
		
?>