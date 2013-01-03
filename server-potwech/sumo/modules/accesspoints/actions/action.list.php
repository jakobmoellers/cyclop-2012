<?php
/**
 * SUMO MODULE: Accesspoints | List
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

if($_SESSION['search_accesspoints_list'])
{
	$field['b.name'] = sumo_search_composer($_SESSION['search_accesspoints_list'], 'b.name');
	$field['a.path'] = sumo_search_composer($_SESSION['search_accesspoints_list'], 'a.path');
	$field['a.name'] = sumo_search_composer($_SESSION['search_accesspoints_list'], 'a.name');

	$search = ($field['a.path'][0] && $field['a.name'][0] && $field['b.name'][0]) ? " AND (".$field['a.path'][0]." OR ".$field['a.name'][0]." OR ".$field['b.name'][0].") " : '';
}


// Create sql query	to select only groups of user
$group_query = sumo_get_group_query($search);

$query1 = "SELECT a.id AS id, b.\"name\" AS node_name, a.path AS path, a.\"name\" AS \"name\", 
a.usergroup AS usergroup, a.http_auth AS http_auth, a.filtering AS filtering,
a.pwd_encrypt AS pwd_encrypt, a.registration AS registration, a.reg_group AS reg_group, 
a.change_pwd AS change_pwd, a.theme AS theme, a.created AS created, a.updated AS updated,
b.id AS node_id
FROM ".SUMO_TABLE_ACCESSPOINTS." a, ".SUMO_TABLE_NODES." b 
WHERE a.node = b.id ".$search." ".$group_query." ";

// MySQL Fix
$query1 = str_replace('"', '', $query1);

$query2	= $query1." ORDER BY ".$_SESSION['accesspoints']['list']['col_sql']." ".$_SESSION['accesspoints']['list']['mode_sql'];


$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_accesspoints_list'], $_SESSION['start_accesspoints_list']);
$vis = $rs->PO_RecordCount();


/**
 * Create list
 */
if($tot > 0)
{
	$list = sumo_get_table_header($table['data']['list']);
	$col  = $_SESSION['accesspoints']['list']['col'];
	
	while($tab = $rs->FetchRow())
	{
		if($search)
		{
			$path2 = sumo_color_match_string($field['a.path'][1], $tab['path']);
			$path3 = sumo_color_match_string($field['a.name'][1], sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']));
			$node  = sumo_color_match_string($field['b.name'][1], $tab['node_name']);
		}
		else
		{
			$path2 = $tab['path'];
			$path3 = sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']);
			$node  = $tab['node_name'];
		}
		
		$http_auth    = $tab['http_auth']    ? 'httpauth'  : 'nohttpauth';
		$filtering    = $tab['filtering']    ? 'filtering' : 'nofiltering';
		$pwd_encrypt  = $tab['pwd_encrypt']  ? 'encrypt'   : 'noencrypt';
		$registration = $tab['registration'] ? 'reg' 	   : 'noreg';
		$change_pwd   = $tab['change_pwd']   ? 'changepwd' : 'nochangepwd';
		$group 	      = str_replace(';', ', ', $tab['usergroup']);
		$group 	      = strlen($group)>50 ? substr($group, 0, 50).'...' : $group;
		$theme        = ucwords($tab['theme']);
		$created      = $tab['created'] ? sumo_get_human_date($tab['created']) : '';
		$updated      = $tab['updated'] ? sumo_get_human_date($tab['updated']) : '';

		$style  = sumo_alternate_str('tab-row-on', 'tab-row-off');
		$style2 = ($tab['updated'] > $SUMO['server']['time'] - 10) ? " style='border-top:1px solid #FF7722;border-bottom:1px solid #FF7722'" : "";

		if(sumo_verify_is_console($tab['path'])) $path3 = '<b>'.$path3.'</b>';
		
		
		$list .= "<tr$style2>\n";
			if($col[4])  $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"accesspoints.content\",\"?module=accesspoints&decoration=false&action=view&id=".$tab['id']."\");'>".$path3."</a></td>\n";
			if($col[2])  $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_node&id=".$tab['node_id']."\");'>".$node."</a></td>\n";
			if($col[3])  $list .= " <td class='".$style."'><a href='".$tab['path']."' target='_blank'>".$path2."</a></td>\n";
			if($col[5])  $list .= " <td class='".$style."' width='100%'>".$group."</td>\n";
			if($col[6])  $list .= " <td class='".$style."' align='center'><img src='themes/".$SUMO['page']['theme']."/images/modules/accesspoints/".$http_auth.".gif'></td>\n";
			if($col[7])  $list .= " <td class='".$style."' align='center'><img src='themes/".$SUMO['page']['theme']."/images/modules/accesspoints/".$filtering.".gif'></td>\n";
			if($col[8])  $list .= " <td class='".$style."' align='center'><img src='themes/".$SUMO['page']['theme']."/images/modules/accesspoints/".$pwd_encrypt.".gif'></td>\n";
			if($col[9])  $list .= " <td class='".$style."' align='center'><img src='themes/".$SUMO['page']['theme']."/images/modules/accesspoints/".$registration.".gif'></td>\n";
			if($col[10]) $list .= " <td class='".$style."' align='center'>".$tab['reg_group']."</td>\n";
			if($col[11]) $list .= " <td class='".$style."' align='center'><img src='themes/".$SUMO['page']['theme']."/images/modules/accesspoints/".$change_pwd.".gif'></td>\n";
			if($col[12]) $list .= " <td class='".$style."'>".$theme."</td>\n";
			if($col[13]) $list .= " <td class='".$style."'>".$created."</td>\n";
			if($col[14]) $list .= " <td class='".$style."'>".$updated."</td>\n";
		$list .= "</tr>\n";
	}

	$list .= "</table>";
}
else
{
	$list = "<div class='no-results'>".$language['AccesspointsNotFound']."</div>";
}


$searched = $search ? $_SESSION['search_accesspoints_list'] : '';


// Template Data
$tpl = array(
			  'MESSAGE:H'		 => $tpl['MESSAGE:H'],
			  'MESSAGE:M'		 => $tpl['MESSAGE:M'],
			  'MESSAGE:L'		 => $tpl['MESSAGE:L'],
			  'GET:MenuModule' 	 => $tpl['GET:MenuModule'],
			  'GET:AccessPointsList' => $list,
			  'GET:TotalRows'	 => number_format($tot, 0, "", "."),
			  'GET:StartRow'      	 => number_format($_SESSION['start_accesspoints_list'], 0, "", "."),
			  'GET:EndRow'   	 => number_format($_SESSION['start_accesspoints_list'] + $vis, 0, "", "."),
			  'GET:PagingResults'	 => sumo_paging_results($tot, $vis, $_SESSION['rows_accesspoints_list'], 5, $_SESSION['start_accesspoints_list'], 'start_accesspoints_list'),
			  'GET:TableSettings'	 => sumo_get_table_settings($table['data']['list']),
			  'GET:SearchForm'  	 => sumo_get_form_search($searched),
			  'GET:ExportData'	 => sumo_get_export_data(),
			  'LINK:AddAccessPoint'	 => sumo_verify_permissions(5, 'sumo') ? sumo_get_action_icon("", "add", "accesspoints.content", "?module=accesspoints&action=new&decoration=false") : sumo_get_action_icon("", "add"),
	 		 );

$tpl['GET:Pagination'] = $tot>0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";

?>