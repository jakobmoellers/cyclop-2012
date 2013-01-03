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

if($_SESSION['search_relationship_accesspoint2groups']) 
{			
	$field['path'] = sumo_search_composer($_SESSION['search_relationship_accesspoint2groups'], 'path');
	$field['name'] = sumo_search_composer($_SESSION['search_relationship_accesspoint2groups'], 'name');
	
	$search = ($field['path'][0] && $field['name'][0]) ? " WHERE (".$field['path'][0]." OR ".$field['name'][0].") " : '';
} 

// Create sql query	to select only groups of user
$group_query = sumo_get_group_query($search);

$query1 = "SELECT * FROM ".SUMO_TABLE_ACCESSPOINTS." ".$group_query." ".$search."";
$query2	= $query1." ORDER BY ".$_SESSION['relationship']['accesspoint2groups']['col_sql']." ".$_SESSION['relationship']['accesspoint2groups']['mode_sql'];	


$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_relationship_accesspoint2groups'], $_SESSION['start_relationship_accesspoint2groups']);
$vis = $rs->PO_RecordCount();
	

/**
 * Create list
 */
$list = sumo_get_table_header($table['data']['accesspoint2groups']);

while($tab = $rs->FetchRow()) 
{	  	
	$style = sumo_alternate_str('tab-row-on', 'tab-row-off');
	$group = explode(";", $tab['usergroup']);
	
	$groups = "<table width='100%'>";
	$g = 0;
	
	for($i=0; $i<count($group); $i++)
	{
		$style2 = sumo_alternate_str('tab-row-on', 'tab-row-off', $tab['usergroup']);
		
		$groups .= "<tr".$rowcolor.">"
				 ."<td width='100%' class='".$style2."'>"
				 ."<a href='javascript:sumo_ajax_get(\"groups\",\"?module=groups&action=list\");'>".$group[$i]."</a>"
				 ."</td>"
				 ."</tr>\n";
		$g++;
	}
	
	$groups .= "</table>";
	
	
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
			."<br>($g ".$language['groups'].")</td>\n";
			
	if($_SESSION['relationship']['accesspoint2groups']['col'][100])
	{
		$list .= " <td>".$groups."</td>\n";
	}
	
	if($_SESSION['relationship']['accesspoint2groups']['col'][101])
	{
		$width = $g > 5 ? " width='450'" : '';
		
		$list .= " <td style='border-bottom:1px solid #DCDCDC'><img onclick='javascript:window.open(\"services.php?module=relationship&service=relationship&cmd=GET_ACCESSPOINT2GROUPS&id=".$tab['id']."\",\"accesspoint2groups\",\"height=200,width=500,resizable=yes,scrollbars=yes\");' "
				." src='services.php?module=relationship&service=relationship&cmd=GET_ACCESSPOINT2GROUPS&id=".$tab['id']."' alt=''$width></td>\n"
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
			  'GET:StartRow'      	  => number_format($_SESSION['start_relationship_accesspoint2groups'], 0, "", "."),
			  'GET:EndRow'   	   	  => number_format($_SESSION['start_relationship_accesspoint2groups'] + $vis, 0, "", "."),
			  'GET:PagingResults'	  => sumo_paging_results($tot, $vis, $_SESSION['rows_relationship_accesspoint2groups'], 5, $_SESSION['start_relationship_accesspoint2groups'], 'start_relationship_accesspoint2groups'),
			  'GET:TableSettings'	  => sumo_get_table_settings($table['data']['accesspoint2groups']),
			  'GET:SearchForm'  	  => sumo_get_form_search($searched),					  
			  'GET:ExportData'	 	  => sumo_get_export_data()
			 );

$tpl['GET:Pagination'] = $tot > 0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";
	
?>