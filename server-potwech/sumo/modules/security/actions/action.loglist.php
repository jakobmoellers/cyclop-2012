<?php
/**
 * SUMO MODULE: Security | Log List (see module.php)
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

// Verify if Log Manager is enabled
$log_type = explode("_", $action);
$log_type = $log_type[0];

if(!$_COOKIE["WarningLogManager$log_type"] && !$SUMO['config']['logs'][$log_type]['database']['enabled'] && $log_type != 'last')
{
	$tpl['MESSAGE:M'] = $language['LogManagerDisabled'];
	
	setcookie("WarningLogManager$log_type", 1, time()+900);
	
	$_COOKIE["WarningLogManager$log_type"] = 1;
}



sumo_set_table_settings();
		
// Create search query
$search = '';
	
if($_SESSION['search_security_'.$action]) 
{
	$field['message'] = sumo_search_composer($_SESSION['search_security_'.$action], 'message', 'AND');
					
	if($field['message'][0]) $search = "WHERE (".$field['message'][0].")";
} 
		
		
// Table selector
switch($action) 
{				                
	case 'system_list': $query1 = "SELECT * FROM ".SUMO_TABLE_LOG_SYSTEM." ".$search; break;
	case 'access_list': $query1 = "SELECT * FROM ".SUMO_TABLE_LOG_ACCESS." ".$search; break;
   	case 'errors_list': $query1 = "SELECT * FROM ".SUMO_TABLE_LOG_ERRORS." ".$search; break;
	default:
	    $query1 = "SELECT * FROM ".SUMO_TABLE_LOG_SYSTEM." ".$search." 
			UNION 
	               SELECT * FROM ".SUMO_TABLE_LOG_ACCESS." ".$search."
			UNION 
	               SELECT * FROM ".SUMO_TABLE_LOG_ERRORS." ".$search; 
        break;
}
		                    
	         
$query2	= $query1." ORDER BY ".$_SESSION['security'][$action]['col_sql']." ".$_SESSION['security'][$action]['mode_sql'];
				
$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();

$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_security_'.$action], $_SESSION['start_security_'.$action]);
$vis = $rs->PO_RecordCount();
	

/**
 * Create list
 */
if($tot > 0) 
{			
	$list = sumo_get_table_header($table['data'][$action]);
	$col  = $_SESSION['security'][$action]['col'];
	
	while($tab = $rs->FetchRow()) 
	{				
		$style = "class='".sumo_alternate_str('tab-row-on', 'tab-row-off')."'";
		$node  = sumo_get_node_info($tab['node'], 'ip'); 
		
		$node['name'] = (!$node['name']) ? '&minus;' : $node['name']; 

		if($search) 
		{
			$tab['code'] 	     = sumo_color_match_string($field['code'][1], $tab['code']);
			$tab['ip'] 	     = sumo_color_match_string($field['ip'][1], $tab['ip']);
			$tab['message']      = sumo_color_match_string($field['message'][1], $tab['message']);
			$tab['country_name'] = sumo_color_match_string($field['country_name'][1], $tab['country_name']);
		}

		$list .= "<tr>\n";
			if($col[2]) $list .= " <td $style align='center'><img src='themes/".$SUMO['page']['theme']."/images/modules/security/priority_".$tab['priority'].".gif' class='log-priority' alt='".$tab['priority']."'></td>\n";
			if($col[3]) $list .= " <td $style align='right'>".$tab['code']."</td>\n";
			if($col[4]) $list .= " <td $style align='right'>".$node['name']."</td>\n";
			if($col[5]) $list .= " <td $style align='right'>".$tab['ip']."</td>\n";
			if($col[6]) $list .= " <td $style align='right'>".ucwords(strtolower($tab['country_name']))."</td>\n";
			if($col[7]) $list .= " <td $style width='100%'>".$tab['message']."</td>\n";
			if($col[8]) $list .= " <td $style align='right'>".sumo_get_human_date($tab['time'], TRUE, TRUE)."</td>\n";
		$list .= "</tr>\n";
	}
	 
	$list .= "</table>";
}
else 
{
	$list = "<div class='no-results'>".$language['LogsNotFound']."</div>";
}
				
$searched = $search ? $_SESSION['search_security_'.$action] : '';
	
	
// Template Data
$tpl = array(
		  'MESSAGE:H'	      => $tpl['MESSAGE:H'],
		  'MESSAGE:M'	      => $tpl['MESSAGE:M'],
		  'MESSAGE:L'	      => $tpl['MESSAGE:L'],
		  'GET:MenuModule'    => $tpl['GET:MenuModule'],
		  'GET:MessagesList'  => $list,
		  'GET:TotalRows'     => number_format($tot, 0, "", "."),
		  'GET:StartRow'      => number_format($_SESSION['start_security_'.$action], 0, "", "."),
		  'GET:EndRow'        => number_format($_SESSION['start_security_'.$action] + $vis, 0, "", "."),
		  'GET:PagingResults' => sumo_paging_results($tot, $vis, 
		  							 $_SESSION['rows_security_'.$action], 5,
		  							 $_SESSION['start_security_'.$action], 'start_security_'.$action),
		  'GET:TableSettings' => sumo_get_table_settings($table['data'][$action]),
		  'GET:SearchForm'    => sumo_get_form_search($searched),					  
		  'GET:ExportData'    => sumo_get_export_data(),
		  'GET:WindowScripts' => 'sumo_unrefresh_window("security");'
		  			.'sumo_refresh_window("security", "'.$action.'", '.SUMO_TIMER_LOGS.', "index.php?module=security");'
	);

$tpl['GET:Pagination'] = $tot>0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";
	
?>