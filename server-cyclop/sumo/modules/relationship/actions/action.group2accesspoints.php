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
		
$num_groups = count($SUMO['user']['group']);		
			
// Create sql query	to select only groups of user
// and optionally search only users of a group
$group_query = sumo_get_group_query(false, true);
	

// Create search query
$search = '';

if($_SESSION['search_relationship_group2accesspoints']) 
{		
	$field['usergroup'] = sumo_search_composer($_SESSION['search_relationship_group2accesspoints'], 'usergroup');
	
	$search = $group_query ? " AND " : " WHERE ";		
	$search = $search." (".$field['usergroup'][0].")";
} 


$query1 = "SELECT id,usergroup FROM ".SUMO_TABLE_GROUPS." ".$group_query." ".$search."";
$query2	= $query1." ORDER BY ".$_SESSION['relationship']['group2accesspoints']['col_sql']." ".$_SESSION['relationship']['group2accesspoints']['mode_sql'];	

$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_relationship_group2accesspoints'], $_SESSION['start_relationship_group2accesspoints']);
$vis = $rs->PO_RecordCount();
	

/**
 * Create list
 */
$list = sumo_get_table_header($table['data']['group2accesspoints']);

while($tab = $rs->FetchRow()) 
{	  	
	$style = sumo_alternate_str('tab-row-on', 'tab-row-off');
	
	$query = "SELECT id,node,path,name FROM ".SUMO_TABLE_ACCESSPOINTS."
			  WHERE (
			  		 usergroup LIKE '".$tab['usergroup']."' 
					 OR usergroup LIKE '".$tab['usergroup'].";%'
					 OR usergroup LIKE '%;".$tab['usergroup']."'
					 OR usergroup LIKE '%;".$tab['usergroup'].";%'
					 )
			  ORDER BY node,name,path";

	$rs2 = $SUMO['DB']->Execute($query);
	
	$ap = "<table width='100%'>";
	$a = 0;
	
	while($tab2 = $rs2->FetchRow()) 
	{
		$style2 = sumo_alternate_str('tab-row-on', 'tab-row-off', $tab['usergroup']);
		
		$tab2['name'] = sumo_get_accesspoint_name($tab2['name'], $_COOKIE['language']);
		
		$ap .= "<tr>"
			  ."<td width='100%' class='".$style2."' nowrap>"
			  ."<a href='javascript:sumo_ajax_get(\"accesspoints\",\"?module=accesspoints&action=edit&id=".$tab2['id']."\");'>"
			  .$tab2['name']
			  ."</a>"
			  ."</td>"
			  ."<td class='".$style2."'>"
			  ."<a href='javascript:sumo_ajax_get(\"accesspoints\",\"?module=accesspoints&action=edit&id=".$tab2['id']."\");'>"
			  .$tab2['path']
			  ."</a>"
			  ."</td>"
			  ."</tr>\n";
		
		$a++;
	}
	
	$ap .= "</table>";
	
	if($search) 
	{
		$tab['usergroup'] = sumo_color_match_string($field['usergroup'][1], $tab['usergroup']);
	}
		
	$width = $a > 5 ? " width='450'" : '';
	
	$list .= "<tr>\n"
			." <td class='".$style."'><b>"
			."<a href='javascript:sumo_ajax_get(\"groups\",\"?module=groups&action=edit&id=".$tab['id']."\");'>".$tab['usergroup']."</a>"
			."</b>"
			."<br>($a ".$language['accesspoints'].")</td>\n";
	
	if($_SESSION['relationship']['group2accesspoints']['col'][100])
	{
		$list .= " <td>".$ap."</td>\n";
	}
	
	if($_SESSION['relationship']['group2accesspoints']['col'][101])
	{
		$list .= " <td style='border-bottom:1px solid #DCDCDC'><img onclick='javascript:window.open(\"services.php?module=relationship&service=relationship&cmd=GET_GROUP2ACCESSPOINTS&id=".$tab['id']."\",\"group2accesspoints\",\"height=200,width=500,resizable=yes,scrollbars=yes\");' "
				." src='services.php?module=relationship&service=relationship&cmd=GET_GROUP2ACCESSPOINTS&id=".$tab['id']."' alt=''$width></td>\n"
				."</tr>\n";		
	}
}

$list .= "</table>";	
				
$searched = $search ? $_SESSION['search_relationship_group2accesspoints'] : '';	

// Template Data
$tpl = array(
			  'GET:Theme'			  => $SUMO['page']['theme'],
			  'GET:MenuModule' 		  => $tpl['GET:MenuModule'],
			  'GET:List'  	      	  => $list,
			  'GET:TotalRows'		  => number_format($tot, 0, "", "."),
			  'GET:StartRow'      	  => number_format($_SESSION['start_relationship_group2accesspoints'], 0, "", "."),
			  'GET:EndRow'   	   	  => number_format($_SESSION['start_relationship_group2accesspoints'] + $vis, 0, "", "."),
			  'GET:PagingResults'	  => sumo_paging_results($tot, $vis, $_SESSION['rows_relationship_group2accesspoints'], 5, $_SESSION['start_relationship_group2accesspoints'], 'start_relationship_group2accesspoints'),
			  'GET:TableSettings'	  => sumo_get_table_settings($table['data']['group2accesspoints']),
			  'GET:SearchForm'  	  => sumo_get_form_search($searched),					  
			  'GET:ExportData'	 	  => sumo_get_export_data()
			 );

$tpl['GET:Pagination'] = $tot > 0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";
	
?>