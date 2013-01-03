<?php
/**
 * SUMO MODULE: Security | Banned
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

// ...verify if user can enable banned ip's
$enableip = sumo_verify_permissions(4, 'sumo');

// enable IP address
if($_GET['enableip'] != '' && $enableip) sumo_enable_bannedip($_GET['enableip']);

// set table settings		
sumo_set_table_settings();

// Create search query
$search = '';

if($_SESSION['search_security_banned']) 
{			
	$field['ip'] = sumo_search_composer($_SESSION['search_security_banned'], 'ip');
				
	$search = ($field['ip'][0]) ? " WHERE ".$field['ip'][0]." " : '';
} 

		
$query1 = "SELECT * FROM ".SUMO_TABLE_BANNED." ".$search." ";
$query2	= $query1." ORDER BY ".$_SESSION['security']['banned']['col_sql']." ".$_SESSION['security']['banned']['mode_sql'];	
					
$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();		       
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_security_banned'], $_SESSION['start_security_banned']);
$vis = $rs->PO_RecordCount(); 


/**
 * Create list
 */
$col = $_SESSION['security']['banned']['col'];

if($tot > 0) 
{			
	$list = sumo_get_table_header($table['data']['banned']);
	
	while($tab = $rs->FetchRow()) 
	{													
		$ip   = $search ? sumo_color_match_string($field['ip'][1], $tab['ip']) : $tab['ip'];
		$time = sumo_get_human_date(($tab['time'] + $SUMO['config']['security']['banned_time']), true, true);
					
		$style = sumo_alternate_str('tab-row-on', 'tab-row-off');
									
		$list .= "<tr>\n";	
			if($col[2]) $list .= " <td class='".$style."'>".$ip."</td>\n";
			if($col[3]) $list .= " <td class='".$style."'>".$time."</td>\n";
			if(($col[2] || $col[3]) && $enableip) $list .= " <td class='".$style."'>"
				."<a href='javascript:sumo_ajax_get(\"security\",\"?module=security&action=banned&enableip=".$tab['id']."\");'>"
				.$language['enable']."</a></td>\n";
		$list .= "</tr>\n";
	}
	
	$list .= "</table>";
}
else 
{
	$list = "<div class='no-results'>".$language['BannedIPNotFound']."</div>";
}
					
$searched = ($search) ? $_SESSION['search_security_banned'] : '';

// Template Data
$tpl = array(
			  'GET:MenuModule' 	  => $tpl['GET:MenuModule'],
			  'GET:BannedIPList'  => $list,
			  'GET:TotalRows'	  => number_format($tot, 0, "", "."),
			  'GET:StartRow'      => number_format($_SESSION['start_security_banned'], 0, "", "."),
			  'GET:EndRow'   	  => number_format($_SESSION['start_security_banned'] + $vis, 0, "", "."),
			  'GET:PagingResults' => sumo_paging_results($tot, $vis, $_SESSION['rows_security_banned'], 5, $_SESSION['start_security_banned'], 'start_security_banned'),
			  'GET:TableSettings' => sumo_get_table_settings($table['data']['banned']),
			  'GET:SearchForm'    => sumo_get_form_search($searched),
			  //'GET:ExportData'  => sumo_get_export_data()
			  'GET:ExportData'	  => '',
			  'GET:WindowScripts' => 'sumo_unrefresh_window("security");'
			  						.'sumo_refresh_window("security", "banned", '.SUMO_TIMER_BANNED.', "index.php?module=security");'
			 );

$tpl['GET:Pagination'] = ($tot > 0) ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";

?>