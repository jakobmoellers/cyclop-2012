<?php
/**
 * SUMO MODULE: Sessions | Sessions list
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

if($_SESSION['search_sessions_slist'])
{			
	//$field['id'] = sumo_search_composer($_SESSION['search_sessions_slist'], 'id');
	$field['user'] = sumo_search_composer($_SESSION['search_sessions_slist'], 'username');
		
	$search = $field['user'][0] ? " WHERE ".$field['user'][0]." " : '';
} 


// Get nodes info
$query = "SELECT id,host,name FROM ".SUMO_TABLE_NODES."
	  WHERE active = 1";

$rs = $SUMO['DB']->CacheExecute(300, $query);

while($tab = $rs->FetchRow()) 
{
	$node[$tab['host']] = $tab;	
}


$query = "SELECT 'max_activity' as status,MAX(activity) AS value FROM ".SUMO_TABLE_SESSIONS."
		  UNION
		  SELECT 'active',COUNT(id) AS value FROM ".SUMO_TABLE_SESSIONS."
		  WHERE expire > ".$SUMO['server']['time']."
		  UNION
		  SELECT 'inactive',COUNT(id) AS value FROM ".SUMO_TABLE_SESSIONS."
		  WHERE expire < ".$SUMO['server']['time'];

$rs = $SUMO['DB']->Execute($query);

while($tab = $rs->FetchRow()) 
{
	if($tab['status'] == 'max_activity')	$max = $tab['value'];
	if($tab['status'] == 'active')	$id_active = $tab['value'];
	if($tab['status'] == 'inactive')	$id_inactive = $tab['value'];	
}


$query1 = "SELECT * FROM ".SUMO_TABLE_SESSIONS." ".$search;
$query2	= $query1." ORDER BY ".$_SESSION['sessions']['slist']['col_sql']." ".$_SESSION['sessions']['slist']['mode_sql'];	

$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_sessions_slist'], $_SESSION['start_sessions_slist']);
$vis = $rs->PO_RecordCount();


/**
 * Create list
 */
$list = sumo_get_table_header($table['data']['slist']);
$col  = $_SESSION['sessions']['slist']['col'];

while($tab = $rs->FetchRow()) 
{			
	$style = sumo_alternate_str('tab-row-on', 'tab-row-off');
	
	// verify if user is current user
	if($tab['session_id'] == session_id()) $style = 'tab-row-highlight';
				 
	$color = 'green';
	
	if($tab['expire'] < $SUMO['server']['time']+500) $color = 'orange';
	if($tab['expire'] < $SUMO['server']['time']+300) $color = 'red';	
				
	$country    = explode('-', $tab['country_name']);
	$country[0] = ucwords(strtolower($country[0]));
	$country[1] = strtolower($country[1]);
	$flag 	    = trim($country[1]) ? trim($country[1]).".png" : "blank.png";
	
	if(!$country[1]) $country[1] = 'blank';
				 
	$user = $search ? sumo_color_match_string($field['user'][1], $tab['username']) : $tab['username'];			
	
	$username = sumo_get_username($tab['username']);
	$apinfo   = sumo_get_accesspoint_info(sumo_get_normalized_accesspoint($tab['url']), 'path');
	$apname   = sumo_get_accesspoint_name($apinfo['name'], $_COOKIE['language']);
	
	$list .= "<tr>\n";
		if($col[1])  $list .= " <td class='".$style."'><img src='themes/".$SUMO['page']['theme']."/images/modules/sessions/status_".$color.".gif' class='session-status'>&nbsp;&nbsp;".$tab['id']."</td>\n";				
		if($col[4])  $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"users\",\"?module=users&action=view&id=".$tab['id_user']."\");"
							 ."' title='".$language['ViewUser'].": ".$username."'>".$user."</a></td>\n";
		if($col[2])  $list .= " <td class='".$style."' align='right'><a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_node&id=".$node[$tab['node']]['id']."\");'>".$node[$tab['node']]['name']."</a></td>\n";
		//if($col[2])  $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=nlist\");'>".$node[$tab['node']]['name']."</a></td>\n";
		if($col[7])  $list .= " <td class='".$style."' align='right'>".$tab['ip']."</td>\n";
		if($col[8])  $list .= " <td class='".$style."' align='right'>".$tab['hostname']."</td>\n";
		if($col[9])  $list .= " <td class='".$style."'><img src='applications/ip2country/flags/small/".$flag."' width='17' alt='".$country[1]."'>&nbsp;".$country[0]."</td>\n";
		if($col[5])  $list .= " <td class='".$style."'>".sumo_get_human_date($tab['connected'])."</td>\n";
		if($col[6])  $list .= " <td class='".$style."'>".sumo_get_human_date($tab['expire'])."</td>\n";
		if($col[10]) $list .= " <td class='".$style."' width='100%'><a href='".$tab['url']."' target='_blank'>".$apname."</a></td>\n";
		if($col[11]) $list .= " <td class='".$style."'>".$tab['client']."</td>\n";
		if($col[12]) $list .= " <td class='".$style."' align='right'>".sumo_get_graph($tab['activity'], $max, 2)."</td>\n";
	$list .= "</tr>\n";
}

$list .= "</table>";	
				
$searched = $search ? $_SESSION['search_sessions_slist'] : '';

// Template Data
$tpl = array(
			  'MESSAGE:H'			 	 => $tpl['MESSAGE:H'],
			  'MESSAGE:M'			 	 => $tpl['MESSAGE:M'],
			  'MESSAGE:L'			 	 => $tpl['MESSAGE:L'],
			  'GET:Theme'			  	 => $SUMO['page']['theme'],
			  'GET:MenuModule' 	  	 	 => $tpl['GET:MenuModule'],
			  'GET:SessionsList'  		 => $list,
			  'GET:NumSessions'   		 => number_format(($id_active + $id_inactive), 0, "", "."),
			  'GET:NumSessionsActive'    => number_format($id_active, 0, "", "."),
			  'GET:NumSessionsNotActive' => number_format($id_inactive, 0, "", "."),
			  'GET:TotalRows'	  		 => number_format($tot, 0, "", "."),
			  'GET:StartRow'      		 => number_format($_SESSION['start_sessions_slist'], 0, "", "."),
			  'GET:EndRow'   	  		 => number_format($_SESSION['start_sessions_slist'] + $vis, 0, "", "."),
			  'GET:PagingResults' 		 => sumo_paging_results($tot, $vis, $_SESSION['rows_sessions_slist'], 5, $_SESSION['start_sessions_slist'], 'start_sessions_slist'),
			  'GET:TableSettings' 		 => sumo_get_table_settings($table['data']['slist']),
			  'GET:SearchForm'    		 => sumo_get_form_search($searched),					  
			  'GET:ExportData'	  		 => '',
			  'GET:WindowScripts'		 => 'sumo_unrefresh_window("sessions");'
										   .'sumo_refresh_window("sessions", "slist", '.SUMO_TIMER_SESSIONS.', "index.php?module=sessions");'
	 		 );

$tpl['GET:Pagination'] = $tot > 0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";

?>