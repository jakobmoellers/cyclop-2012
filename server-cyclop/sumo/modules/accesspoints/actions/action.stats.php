<?php
/**
 * SUMO MODULE: Accesspoints | Statistics
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

// Verify if Stats are enabled and get last update
if($SUMO['config']['accesspoints']['stats']['enabled'] != 'on' && !$_SESSION['accesspoints']['stats']['col_sql'])
{
	$query = "SELECT MAX(updated) FROM ".SUMO_TABLE_ACCESSPOINTS_STATS;
			  
	$rs  = $SUMO['DB']->Execute($query);
	$tab = $rs->FetchRow();
	
	$updated = sumo_get_human_date($tab[0], true, true);
	$enable  = "<input type='button' class='button' value='".$language['EnableStatistics']."' "
			  ."onclick='sumo_ajax_get(\"settings\",\"?module=settings&action=edit&AccessPointOptions_visibility=1\");'>";
			
	$tpl['MESSAGE:M'] = sumo_get_message('StatisticsDisabled', array($updated, $enable));
}


sumo_set_table_settings();
	
// Create search query
$search = '';
	
if($_SESSION['search_accesspoints_stats']) 
{			
	$field['path'] = sumo_search_composer($_SESSION['search_accesspoints_stats'], 'a.path');
	$field['name'] = sumo_search_composer($_SESSION['search_accesspoints_stats'], 'a.name');
	
	$search = ($field['path'][0] && $field['name'][0]) ? " WHERE (".$field['path'][0]." OR ".$field['name'][0].") " : '';
} 
	
			
// Create sql query	to select only groups of user
$group_query = sumo_get_group_query($search);
$operand     = ($search || $group_query) ? ' AND ' : ' WHERE ';
	
$query1 = "SELECT b.node AS node, b.id_page AS id_page, a.name AS name, a.path AS path, b.access AS access, 
				  b.activity AS activity, b.last_login AS last_login, b.updated AS updated
		   FROM ".SUMO_TABLE_ACCESSPOINTS." a, ".SUMO_TABLE_ACCESSPOINTS_STATS." b 
		   ".$search.$group_query.$operand."
		   a.id = b.id_page";
	
$query2	= $query1." ORDER BY ".$_SESSION['accesspoints']['stats']['col_sql']." ".$_SESSION['accesspoints']['stats']['mode_sql'];

			
$rs  = $SUMO['DB']->CacheExecute(15, $query1);
$tot = $rs->PO_RecordCount();
       $SUMO['DB']->cacheSecs = 15;
$rs  = $SUMO['DB']->CacheSelectLimit($query2, $_SESSION['rows_accesspoints_stats'], $_SESSION['start_accesspoints_stats']);
$vis = $rs->PO_RecordCount(); 
	

/**
 * Create list
 */
if($tot > 0) 
{	
	// Get nodes info
	$node = sumo_get_node_info();					
	$list = sumo_get_table_header($table['data']['stats']);
	
	while($tab = $rs->FetchRow()) 
	{											
		$query2 	= "SELECT MAX(access) FROM ".SUMO_TABLE_ACCESSPOINTS_STATS;
		$query3 	= "SELECT MAX(activity) FROM ".SUMO_TABLE_ACCESSPOINTS_STATS;			
		$style  	= sumo_alternate_str('tab-row-on', 'tab-row-off'); 
		$rs2    	= $SUMO['DB']->CacheExecute(15, $query2);
		$rs3    	= $SUMO['DB']->CacheExecute(15, $query3);
		$max2   	= $rs2->FetchRow();
		$max3   	= $rs3->FetchRow();			
		$path2   	= $tab['path'];
		$path3		= sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']);
		$max_access	= $max2[0];	
		$max_activity	= $max3[0];
        
		if($search) 
		{
			$path2 = sumo_color_match_string($field['path'][1], $tab['path']);
			$path3 = sumo_color_match_string($field['name'][1], sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']));
		} 
		
		// verify if user is current node/path
		if($SUMO['page']['node'] == $tab['node'] && $SUMO['page']['path'] == $tab['path']) $style = 'tab-row-highlight';
	
		$list .= "<tr>\n";					
			if($_SESSION['accesspoints']['stats']['col'][3]) $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"accesspoints\",\"?module=accesspoints&action=view&id=".$tab['id_page']."\");'>".$path3."</a></td>\n";
			if($_SESSION['accesspoints']['stats']['col'][1]) $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_node&id=".$tab['node']."\");'>".$node[$tab['node']]['name']."</a></td>\n";
			if($_SESSION['accesspoints']['stats']['col'][4]) $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"accesspoints\",\"?module=accesspoints&action=view&id=".$tab['id_page']."\");'>".$path2."</a></td>\n";
			if($_SESSION['accesspoints']['stats']['col'][5]) $list .= " <td class='".$style."' align='right'>".number_format($tab['access'], 0, '', '.')."</td>\n";
			if($_SESSION['accesspoints']['stats']['col'][5]) $list .= " <td class='".$style."'>".sumo_get_graph($tab['access'], $max_access, 2)."</td>\n";
			if($_SESSION['accesspoints']['stats']['col'][6]) $list .= " <td class='".$style."' align='right'>".number_format($tab['activity'], 0, '', '.')."</td>\n";
			if($_SESSION['accesspoints']['stats']['col'][6]) $list .= " <td class='".$style."'>".sumo_get_graph($tab['activity'], $max_activity, 2)."</td>\n";
			if($_SESSION['accesspoints']['stats']['col'][7]) $list .= " <td class='".$style."' align='right'>".sumo_get_human_date($tab['last_login'])."</td>\n";
			if($_SESSION['accesspoints']['stats']['col'][8]) $list .= " <td class='".$style."' align='right'>".sumo_get_human_date($tab['updated'])."</td>\n";				
		$list .= "</tr>\n";
	}
	
	$list .= "</table>";	
}
else 
{						
	if($SUMO['config']['accesspoints']['stats']['enabled']) $list = "<div class='no-results'>".$language['NoStatsForAccesspoints']."</div>";
	#else 
	#	$list = "<div class='no-results'>".$language['StatisticsDisabled']."</div>";
}
				
$searched = $search ? $_SESSION['search_accesspoints_stats'] : '';
		
// Template Data
$tpl = array(
		  'GET:MenuModule' 	 => $tpl['GET:MenuModule'],
		  'MESSAGE:M'	 	 => $tpl['MESSAGE:M'],
		  'MESSAGE:A'		 => 1,
		  'GET:AccessPointsList' => $list,
		  'GET:TotalRows'	 => number_format($tot, 0, "", "."),
		  'GET:StartRow'      	 => number_format($_SESSION['start_accesspoints_stats'], 0, "", "."),
		  'GET:EndRow'   	 => number_format($_SESSION['start_accesspoints_stats'] + $vis, 0, "", "."),
		  'GET:PagingResults'	 => sumo_paging_results($tot, $vis, $_SESSION['rows_accesspoints_stats'], 5, $_SESSION['start_accesspoints_stats'], 'start_accesspoints_stats'),
		  'GET:TableSettings'	 => sumo_get_table_settings($table['data']['stats']),
		  'GET:SearchForm'  	 => sumo_get_form_search($searched),				  
		  'GET:ExportData'	 => '',
		  // delete old refresh window and update
		  'GET:WindowScripts'	 => 'sumo_unrefresh_window("accesspoints");'
					   .'sumo_refresh_window("accesspoints", "stats", '.SUMO_TIMER_APSTATS.', "index.php?module=accesspoints");'
	);

$tpl['GET:Pagination'] = $tot>0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";

?>