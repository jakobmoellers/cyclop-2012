<?php
/**
 * SUMO MODULE: Groups | Groups list
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
	
if($_SESSION['search_groups_list']) 
{		
	$field['usergroup']   = sumo_search_composer($_SESSION['search_groups_list'], 'usergroup');
	$field['description'] = sumo_search_composer($_SESSION['search_groups_list'], 'description');
				
	$search = ($field['usergroup'][0] && $field['description'][0]) ? "WHERE (".$field['usergroup'][0]." OR ".$field['description'][0].")" : "";
} 
	
// Create sql query	to select only groups of user
$group_query = sumo_get_group_query($search);		

$query1 = "SELECT * FROM ".SUMO_TABLE_GROUPS." ".$search." ".$group_query." ";
$query2	= $query1." ORDER BY ".$_SESSION['groups']['list']['col_sql']." ".$_SESSION['groups']['list']['mode_sql'];	

$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_groups_list'], $_SESSION['start_groups_list']);
$vis = $rs->PO_RecordCount();


/**
 * Create list
 */
if($tot > 0) 
{
	$list = sumo_get_table_header($table['data']['list']);
	$col  = $_SESSION['groups']['list']['col'];
	
	while($tab = $rs->FetchRow()) 
	{	
		$style = sumo_alternate_str('tab-row-on', 'tab-row-off');
				
		if($search) 
		{
			$tab['usergroup']   = sumo_color_match_string($field['usergroup'][1], $tab['usergroup']);
			$tab['description'] = sumo_color_match_string($field['description'][1], $tab['description']);
		}
		
		if(!$tab['description']) $tab['description'] = '&nbsp;';
		   
		if($_SESSION['groups']['list']['col'][100])
		{
			$users = sumo_get_group_users($tab['id']);
			
			if($users == 0) 
				$users = "<font color='red'>".$language['NoUsers']."</font>";
			else 
				$users = "<a href=\"javascript:sumo_ajax_get('relationship','?module=relationship&action=group2users&id=".$tab['id']."');\">".$users."</a>";
		}
        
		$created = $tab['created'] ? sumo_get_human_date($tab['created']) : '&nbsp;';
		$updated = $tab['updated'] ? sumo_get_human_date($tab['updated']) : '&nbsp;';			
		$style2  = ($tab['updated'] > $SUMO['server']['time'] - 10) ? " style='border-top:1px solid #FF7722;border-bottom:1px solid #FF7722'" : "";
		
		$list .= "<tr$style2>\n";
			if($col[2])   $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"groups\",\"?module=groups&action=edit&id=".$tab['id']."\");' title='".$language['Edit']."'>".$tab['usergroup']."</a></td>\n";
			if($col[3])   $list .= " <td class='".$style."'>".$tab['description']."</td>\n";
			if($col[100]) $list .= " <td class='".$style."' align='right'>".$users."</td>\n";
			if($col[4])   $list .= " <td class='".$style."'>".$created."</td>\n";
			if($col[5])   $list .= " <td class='".$style."'>".$updated."</td>\n";      					
		$list .= "</tr>\n";
	}
	
	$list .= "</table>";
}
else 
{
	$list = "<div class='no-results'>".$language['GroupsNotFound']."</div>";
}

	
$searched = $search ? $_SESSION['search_groups_list'] : '';
	
// Template Data
$tpl = array(
		'MESSAGE:H'	    => $tpl['MESSAGE:H'],
		'MESSAGE:M'	    => $tpl['MESSAGE:M'],
		'MESSAGE:L'	    => $tpl['MESSAGE:L'],
		'GET:MenuModule'    => $tpl['GET:MenuModule'],
		'GET:GroupsList'    => $list,
		'GET:TotalRows'	    => number_format($tot, 0, "", "."),
		'GET:StartRow'      => number_format($_SESSION['start_groups_list'], 0, "", "."),
		'GET:EndRow'   	    => number_format($_SESSION['start_groups_list'] + $vis, 0, "", "."),
		'GET:PagingResults' => sumo_paging_results($tot, $vis, $_SESSION['rows_groups_list'], 5, $_SESSION['start_groups_list'], 'start_groups_list', 'list'),
		'GET:TableSettings' => sumo_get_table_settings($table['data']['list']),
		'GET:SearchForm'    => sumo_get_form_search($searched),					  
		'GET:ExportData'    => sumo_get_export_data(),
		'LINK:AddGroup'	    => sumo_verify_permissions(5, 'sumo') ? sumo_get_action_icon("", "add", "groups.content", "?module=groups&action=new&decoration=false") : sumo_get_action_icon("", "add"),
	);

$tpl['GET:Pagination'] = $tot>0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";
	
?>