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

if($_SESSION['search_relationship_user2accesspoints']) 
{		
	$field['user']      = sumo_search_composer($_SESSION['search_relationship_user2accesspoints'], 'username');
	$field['firstname'] = sumo_search_composer($_SESSION['search_relationship_user2accesspoints'], 'firstname');
	$field['lastname']  = sumo_search_composer($_SESSION['search_relationship_user2accesspoints'], 'lastname');
	
	$search = $group_query ? " AND " : " WHERE ";		
	$search = $search."( (".$field['user'][0].") OR "
			."((".$field['firstname'][0].") OR (".$field['lastname'][0].")))";
} 


$query1 = "SELECT id,username,firstname,lastname FROM ".SUMO_TABLE_USERS." ".$group_query." ".$search."";
$query2	= $query1." ORDER BY ".$_SESSION['relationship']['user2accesspoints']['col_sql']." ".$_SESSION['relationship']['user2accesspoints']['mode_sql'];	

$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_relationship_user2accesspoints'], $_SESSION['start_relationship_user2accesspoints']);
$vis = $rs->PO_RecordCount();
	

/**
 * Create list
 */
$list = sumo_get_table_header($table['data']['user2accesspoints']);

while($tab = $rs->FetchRow()) 
{	  	
	$style = sumo_alternate_str('tab-row-on', 'tab-row-off', $tab['username']);
	
	$ap = sumo_get_user_accesspoints($tab['id'], true);
	
	if($search) 
	{
		$tab['username']  = sumo_color_match_string($field['username'][1], $tab['username']);
		$tab['firstname'] = sumo_color_match_string($field['firstname'][1], $tab['firstname']);
		$tab['lastname']  = sumo_color_match_string($field['lastname'][1], $tab['lastname']);
	}
		
	//$width = $a > 5 ? " width='400'" : '';
	
	$list .= "<tr>\n"
			." <td class='".$style."' style='padding:10px'>"
			."<a href='javascript:sumo_ajax_get(\"users\",\"?module=users&action=view&id=".$tab['id']."\");'>"
			."<b>".$tab['username']."</b><br>".$tab['lastname']." ".$tab['firstname']
			."</a>"
			//."<br>($a ".$language['accesspoints'].")</td>\n";
			."</td>\n";
	
	if($_SESSION['relationship']['user2accesspoints']['col'][100])
	{
		$list .= " <td>".$ap."</td>\n";
	}
	/*
	if($_SESSION['relationship']['user2accesspoints']['col'][101])
	{
		$list .= " <td style='border-bottom:1px solid #DCDCDC'><img onclick='javascript:window.open(\"services.php?module=relationship&service=relationship&cmd=GET_USER2ACCESSPOINTS&id=".$tab['id']."\",\"user2accesspoints\",\"height=200,width=500,resizable=yes,scrollbars=yes\");' "
				." src='services.php?module=relationship&service=relationship&cmd=GET_USER2ACCESSPOINTS&id=".$tab['id']."' alt=''$width></td>\n"
				."</tr>\n";		
	}
	*/
}

$list .= "</table>";	
				
$searched = $search ? $_SESSION['search_relationship_user2accesspoints'] : '';	

// Template Data
$tpl = array(
			  'GET:Theme'			  => $SUMO['page']['theme'],
			  'GET:MenuModule' 		  => $tpl['GET:MenuModule'],
			  'GET:List'  	      	  => $list,
			  'GET:TotalRows'		  => number_format($tot, 0, "", "."),
			  'GET:StartRow'      	  => number_format($_SESSION['start_relationship_user2accesspoints'], 0, "", "."),
			  'GET:EndRow'   	   	  => number_format($_SESSION['start_relationship_user2accesspoints'] + $vis, 0, "", "."),
			  'GET:PagingResults'	  => sumo_paging_results($tot, $vis, $_SESSION['rows_relationship_user2accesspoints'], 5, $_SESSION['start_relationship_user2accesspoints'], 'start_relationship_user2accesspoints'),
			  'GET:TableSettings'	  => sumo_get_table_settings($table['data']['user2accesspoints']),
			  'GET:SearchForm'  	  => sumo_get_form_search($searched),					  
			  'GET:ExportData'	 	  => sumo_get_export_data()
			 );

$tpl['GET:Pagination'] = $tot > 0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";
	
?>