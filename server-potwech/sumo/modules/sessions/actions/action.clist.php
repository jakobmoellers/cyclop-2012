<?php
/**
 * SUMO MODULE: Sessions | Connections list
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

if($_SESSION['search_sessions_clist']) 
{			
	$field['ip'] = sumo_search_composer($_SESSION['search_sessions_clist'], 'ip');
		
	$search = " WHERE ".$field['ip'][0]."";
} 
	

$query1 = "SELECT node,ip,requests,time FROM ".SUMO_TABLE_CONNECTIONS." ".$search;
$query2	= $query1." ORDER BY ".$_SESSION['sessions']['clist']['col_sql']." ".$_SESSION['sessions']['clist']['mode_sql'];	
		
$rs  = $SUMO['DB']->CacheExecute(5, $query1);
$tot = $rs->PO_RecordCount();
       $SUMO['DB']->cacheSecs = 5;
$rs  = $SUMO['DB']->CacheSelectLimit($query2, $_SESSION['rows_sessions_clist'], $_SESSION['start_sessions_clist']);
$vis = $rs->PO_RecordCount(); 


/**
 * Create list
 */
if($tot > 0) 
{							
	$list = sumo_get_table_header($table['data']['clist']);
	$col  = $_SESSION['sessions']['clist']['col'];
	
	while($tab = $rs->FetchRow()) 
	{				
		$style = sumo_alternate_str('tab-row-on', 'tab-row-off');

		if($search) $tab['ip'] = sumo_color_match_string($field['ip'][1], $tab['ip']);
							
		$list .= "<tr>\n";
			if($col[1]) $list .= " <td class='".$style."' align='right'>"
								."<a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=nlist\");'>"
								.$tab['node']."</a></td>\n";
			if($col[2]) $list .= " <td class='".$style."' align='right'>".$tab['ip']."</td>\n";
			if($col[3]) $list .= " <td class='".$style."' align='right'>".$tab['requests']."</td>\n";
			if($col[4]) $list .= " <td class='".$style."'>".sumo_get_human_date($tab['time'], TRUE, TRUE)."</td>\n";
			if($col[2]) $list .= " <td class='".$style."' width='100%'>&nbsp;</td>\n";
		$list .= "</tr>\n";
	}
	
	$list .= "</table>";	
}
else
{
	$list = "<div class='no-results'>".$language['ConnectionsNotFound']."</div>";
}
		
$searched = $search ? $_SESSION['search_sessions_clist'] : '';
		
// Template Data
$tpl = array(
 			  'MESSAGE:H'			=> $tpl['MESSAGE:H'],
			  'MESSAGE:M'			=> $tpl['MESSAGE:M'],
			  'MESSAGE:L'			=> $tpl['MESSAGE:L'],
			  'GET:Theme'			=> $SUMO['page']['theme'],
			  'GET:MenuModule' 		=> $tpl['GET:MenuModule'],
			  'GET:ConnectionsList' => $list,
			  'GET:TotalRows'		=> number_format($tot, 0, "", "."),
			  'GET:StartRow'      	=> number_format($_SESSION['start_sessions_clist'], 0, "", "."),
			  'GET:EndRow'   	   	=> number_format($_SESSION['start_sessions_clist'] + $vis, 0, "", "."),
			  'GET:PagingResults'	=> sumo_paging_results($tot, $vis, $_SESSION['rows_sessions_clist'], 5, $_SESSION['start_sessions_clist'], 'start_sessions_clist'),
			  'GET:TableSettings'	=> sumo_get_table_settings($table['data']['clist']),
			  'GET:SearchForm'  	=> sumo_get_form_search($searched),					  
			  'GET:ExportData'	 	=> '',
			  // delete old refresh window and update
			  'GET:WindowScripts'	=> 'sumo_unrefresh_window("sessions");sumo_refresh_window("sessions", "clist", '.SUMO_TIMER_CONNECTIONS.', "index.php?module=sessions");'
 			 );
	
$tpl['GET:Pagination'] = $tot>0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";
		
?>		