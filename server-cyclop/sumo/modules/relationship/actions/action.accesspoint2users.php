<?php
/**
 * SUMO MODULE: Relationship
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

if($_SESSION['search_relationship_accesspoint2users']) 
{			
	$field['path'] = sumo_search_composer($_SESSION['search_relationship_accesspoint2users'], 'path');
	$field['name'] = sumo_search_composer($_SESSION['search_relationship_accesspoint2users'], 'name');
	
	$search = ($field['path'][0] && $field['name'][0]) ? " WHERE (".$field['path'][0]." OR ".$field['name'][0].") " : '';
} 

// Create sql query	to select only groups of user
$group_query = sumo_get_group_query($search);

$query1 = "SELECT * FROM ".SUMO_TABLE_ACCESSPOINTS." ".$group_query." ".$search."";
$query2	= $query1." ORDER BY ".$_SESSION['relationship']['accesspoint2users']['col_sql']." ".$_SESSION['relationship']['accesspoint2users']['mode_sql'];	


$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_relationship_accesspoint2users'], $_SESSION['start_relationship_accesspoint2users']);
$vis = $rs->PO_RecordCount();
	

/**
 * Create list
 */
$list = sumo_get_table_header($table['data']['accesspoint2users']);

while($tab = $rs->FetchRow()) 
{	  	
	$style  = sumo_alternate_str('tab-row-on', 'tab-row-off');
	$groups = explode(";", $tab['usergroup']);
	$group  = array();
	
	for($i=0; $i<count($groups); $i++)
	{
		$group[] = " usergroup LIKE '%".$groups[$i]."%' ";
	}
	
	$groups = implode(" OR ", $group);
	
	$query = "SELECT id,username,firstname,lastname,usergroup,active
			  FROM ".SUMO_TABLE_USERS."
			  WHERE ($groups
					 OR usergroup LIKE 'sumo:%'
					 OR usergroup LIKE '%;sumo:%')
			  AND username<>'sumo'
			  ORDER BY username,lastname,firstname";
	
	$rs2 = $SUMO['DB']->Execute($query);
	
	$users = "<table width='100%'>";
	$u = 0;
	
	while($tab2 = $rs2->FetchRow()) 
	{
		$rowcolor = $tab2['active'] ? '' : " class='row-null'";
		
		if(ereg('sumo:', $tab2['usergroup'])) 
			$style2 = "tab-row-highlight";
		else
			$style2 = sumo_alternate_str('tab-row-on', 'tab-row-off', $tab['usergroup']);
		
		$users .= "<tr".$rowcolor.">"
				 ."<td width='100%' class='".$style2."'>"
				 ."<a href='javascript:sumo_ajax_get(\"users\",\"?module=users&action=view&id=".$tab2['id']."\");'>".$tab2['user']."</a>"
				 ."</td>"
				 ."<td class='".$style2."' nowrap>"
				 ."<a href='javascript:sumo_ajax_get(\"users\",\"?module=users&action=view&id=".$tab2['id']."\");'>"
				 .$tab2['lastname']." ".$tab2['firstname']."</a>"
				 ."</td>"
				 ."</tr>\n";
		$u++;
	}
	
	$users .= "</table>";
	
	
	$tab['name'] = sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']);
	
	if($search) 
	{
		$tab['name'] = sumo_color_match_string($field['name'][1], $tab['name']);
		$tab['path'] = sumo_color_match_string($field['path'][1], $tab['path']);
	}
		
	// Get nodes info
	$node = sumo_get_node_info($tab['node']);
	
	$list .= "<tr>\n"
			." <td class='".$style."'>"
			."<a href='javascript:sumo_ajax_get(\"accesspoints\",\"?module=accesspoints&decoration=true&action=edit&id=".$tab['id']."\");'><b>".$tab['name']."</b></a>"
			."<br><br>".$node['name']."<br>".$tab['path']
			."<br>($u ".$language['users'].")</td>\n";
			
	if($_SESSION['relationship']['accesspoint2users']['col'][100])
	{
		$list .= " <td>".$users."</td>\n";
	}
	
	if($_SESSION['relationship']['accesspoint2users']['col'][101])
	{
		$list .= " <td style='border-bottom:1px solid #DCDCDC'><img onclick='javascript:window.open(\"services.php?module=relationship&service=relationship&cmd=GET_ACCESSPOINT2USERS&id=".$tab['id']."\",\"ACCESSPOINT2USERS\",\"height=200,width=500,resizable=yes,scrollbars=yes\");' "
				." src='services.php?module=relationship&service=relationship&cmd=GET_ACCESSPOINT2USERS&id=".$tab['id']."' alt='' width='400' height='200'></td>\n"
				."</tr>\n";		
	}	
}

$list .= "</table>";	
				
$searched = $search ? $_SESSION['search_relationship_group2users'] : '';	

// Template Data
$tpl = array(
			  'GET:Theme'			  => $SUMO['page']['theme'],
			  'GET:MenuModule' 		  => $tpl['GET:MenuModule'],
			  'GET:List'  	      	  => $list,
			  'GET:TotalRows'		  => number_format($tot, 0, "", "."),
			  'GET:StartRow'      	  => number_format($_SESSION['start_relationship_accesspoint2users'], 0, "", "."),
			  'GET:EndRow'   	   	  => number_format($_SESSION['start_relationship_accesspoint2users'] + $vis, 0, "", "."),
			  'GET:PagingResults'	  => sumo_paging_results($tot, $vis, $_SESSION['rows_relationship_accesspoint2users'], 5, $_SESSION['start_relationship_accesspoint2users'], 'start_relationship_accesspoint2users'),
			  'GET:TableSettings'	  => sumo_get_table_settings($table['data']['accesspoint2users']),
			  'GET:SearchForm'  	  => sumo_get_form_search($searched),					  
			  'GET:ExportData'	 	  => sumo_get_export_data()
			 );

$tpl['GET:Pagination'] = $tot > 0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";
	
?>