<?php
/**
 * SUMO MODULE: Users | List
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
			
// Create sql query to select only groups of user
// and optionally search only users of a group
$group_query = sumo_get_group_query(false, true);
	

// Create search query
$search = '';
	
if($_SESSION['search_users_list']) 
{		
	$field['id'] 	    = sumo_search_composer($_SESSION['search_users_list'], 'id');
	$field['username']  = sumo_search_composer($_SESSION['search_users_list'], 'username');
	$field['firstname'] = sumo_search_composer($_SESSION['search_users_list'], 'firstname');
	$field['lastname']  = sumo_search_composer($_SESSION['search_users_list'], 'lastname');
	$field['usergroup'] = sumo_search_composer($_SESSION['search_users_list'], 'usergroup');
	$field['email']     = sumo_search_composer($_SESSION['search_users_list'], 'email');
	
	$search  = $group_query ? " AND " : " WHERE ";		
	$operand = count($field['lastname'][1]) > 1 ? 'AND' : 'OR'; 
		
	if($field['username'][0] && $field['firstname'][0] && $field['lastname'][0])
	{
		$search = $search."((".$field['id'][0].") OR (".$field['username'][0].") OR (".$field['email'][0].") OR "
				."((".$field['firstname'][0].") ".$operand." (".$field['lastname'][0].")) OR "
				."(".$field['usergroup'][0]."))";
	}
	else 
	{
		$search  = '';
	}
} 

// Get number of active users
$query = "SELECT 'total' AS status,COUNT(id) AS value FROM ".SUMO_TABLE_USERS."
		UNION
	  SELECT 'active',COUNT(id) AS value FROM ".SUMO_TABLE_USERS."
	  WHERE active=1";

if($cache)
	$rs = $SUMO['DB']->CacheExecute(300, $query);
else
	$rs = $SUMO['DB']->Execute($query);

while($tab = $rs->FetchRow()) 
{
	if($tab['status'] == 'total')  $users  = $tab['value'];
	if($tab['status'] == 'active') $active = $tab['value'];
}

	
$query1 = "SELECT * FROM ".SUMO_TABLE_USERS." ".$group_query." ".$search;
$query2	= $query1." ORDER BY ".$_SESSION['users']['list']['col_sql']." ".$_SESSION['users']['list']['mode_sql'];	


$rs  = $SUMO['DB']->Execute($query1);
$tot = $rs->PO_RecordCount();
$rs  = $SUMO['DB']->SelectLimit($query2, $_SESSION['rows_users_list'], $_SESSION['start_users_list']);
$vis = $rs->PO_RecordCount();


$list = sumo_get_table_header($table['data']['list']);
	
// Get datasources list
if($_SESSION['users']['list']['col'][9])
{
	$datasources = sumo_get_datasource_info();
	
	for($d=0; $d<=count($datasources); $d++)
	{
		if(isset($datasources[$d]['id'])) $datasource[$datasources[$d]['id']] = $datasources[$d]['name'];
	}
}

/**
 * Create list
 */
$users_lang      = sumo_get_string_languages();
$available_group = sumo_get_available_group();

$col = $_SESSION['users']['list']['col'];

while($tab = $rs->FetchRow()) 
{	    		
	// Get user status of local Unix user
	if($tab['datasource_id'] == 0) // equal to:  if($user_data['datasource_type'] == 'Unix') 
	{
		$u = exec("egrep \"^{$tab['username']}:\" /etc/shadow");
		$p = explode(":", $u);
		$a = explode(" ", exec("passwd -S {$tab['username']}"));
		
		$tab['active'] = $a[1] == "P" ? 1 : 0;
	}
	
	$color 	    = $tab['active']	 ? 'on' : 'off';
	$rowcolor   = $tab['active'] 	 ? ''   : " class='row-null'";
	$last_login = $tab['last_login'] ? sumo_get_human_date($tab['last_login']) : '&nbsp;';
	$created    = $tab['created']    ? sumo_get_human_date($tab['created'])    : '&nbsp;';
	$expire	    = $tab['day_limit'] != NULL ? sumo_get_human_date($tab['day_limit'] * 86400 + $SUMO['server']['time'], FALSE) : '';
	$style      = $tab['username'] == $SUMO['user']['user'] ? 'tab-row-highlight' : sumo_alternate_str('tab-row-on', 'tab-row-off');
	//$style2     = ($tab['modified'] > $SUMO['server']['time'] - 10) ? " style='border-top:1px solid #FF7722;border-bottom:1px solid #FF7722'" : "";
	$username   = sumo_get_formatted_username($tab['firstname'], $tab['lastname']);

	// Format group string to display it
	$group = preg_replace("/sumo:7/", "<b><font color='#BB0000'>sumo:7</font></b>", $tab['usergroup']);
	$group = preg_replace("/sumo:/", "<font color='#BB0000'>sumo</font>:", $group);
	$group = str_replace(';', ', ', $group);		
	$group = strlen(strip_tags($group)) > 50 ? substr($group, 0, 50).'...' : $group;
	
	//
	$usergroup = sumo_get_grouplevel($tab['usergroup'], true);
	
	for($g=0; $g<count($usergroup); $g++)
	{
		if(!in_array($usergroup[$g], $available_group))	$group = str_replace($usergroup[$g], '<strike>'.$usergroup[$g].'</strike>', $group);
	}
	
	
	if($search) 
	{
		$tab['username'] = sumo_color_match_string($field['username'][1],  $tab['username']);
		$tab['email']    = sumo_color_match_string($field['email'][1], $tab['email']);
		$group		 = sumo_color_match_string($field['usergroup'][1], strip_tags($group));
		$username        = sumo_color_match_string(array_merge($field['firstname'][1], $field['lastname'][1]), $username);
	}			
	
	$list .= "<tr".$rowcolor.">\n";
		if($col[6])  $list .= " <td class='".$style."'><img src='themes/".$SUMO['page']['theme']."/images/modules/users/user_".$color.".gif' alt='&bull;'></td>\n";
		if($col[1])  $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"users.content\",\"?module=users&action=view&id=".$tab['id']."&decoration=false\");' title='".$language['ViewUser']."'>".$tab['id']."</a></td>\n";
		if($col[2])  $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"users.content\",\"?module=users&action=view&id=".$tab['id']."&decoration=false\");' title='".$language['ViewUser']."'>".$tab['username']."</a></td>\n";
		if($col[4])  $list .= " <td class='".$style."'><a href='javascript:sumo_ajax_get(\"users.content\",\"?module=users&action=view&id=".$tab['id']."&decoration=false\");' title='".$language['ViewUser']."'>".$username."</a></td>\n";
		if($col[8])  $list .= " <td class='".$style."' nowrap>".$group."</td>\n";
		if($col[9])  $list .= " <td class='".$style."' nowrap><a href='javascript:sumo_ajax_get(\"network\",\"?module=network&action=view_datasource&id=".$tab['datasource_id']."\");'>".$tab['datasource_id'].$datasource[$tab['datasource_id']]."</a></td>\n";
		if($col[13]) $list .= " <td class='".$style."' align='right' nowrap>".$tab['email']."</td>\n";
		if($col[12]) $list .= " <td class='".$style."' align='right'><img src='themes/".$SUMO['page']['theme']."/images/flags/".$tab['language'].".png'>&nbsp;&nbsp;".ucfirst($users_lang[$tab['language']])."</td>\n";
		if($col[10]) $list .= " <td class='".$style."' align='right'>".$last_login."</td>\n";
		if($col[15]) $list .= " <td class='".$style."' align='right'>".$created."</td>\n";
		if($col[11]) $list .= " <td class='".$style."' align='right'>".$expire."</td>\n";
	$list .= "</tr>\n";		
}

$list .= "</table>";	
				
$searched = $search ? $_SESSION['search_users_list'] : '';	
	
// Template Data
$tpl = array(
		'MESSAGE:H'		=> $tpl['MESSAGE:H'],
		'MESSAGE:M'		=> $tpl['MESSAGE:M'],
		'MESSAGE:L'		=> $tpl['MESSAGE:L'],
		'GET:Theme'		=> $SUMO['page']['theme'],
		'GET:MenuModule'	=> $tpl['GET:MenuModule'],
		'GET:NumUsers' 	  	=> number_format($users, 0, "", "."),
		'GET:NumUsersActive'    => number_format($active, 0, "", "."),
		'GET:NumSuspendedUsers' => "<a href='javascript:sumo_ajax_get(\"users.content\",\"index.php?start_users_list=0&module=users&action=list&om=0&oc=6&decoration=false\");'>"
						.number_format($users-$active, 0, "", ".")
		 				."</a>", 
		'GET:UsersList'		=> $list,
		'GET:TotalRows'		=> number_format($tot, 0, "", "."),
		'GET:StartRow'		=> number_format($_SESSION['start_users_list'], 0, "", "."),
		'GET:EndRow'  		=> number_format($_SESSION['start_users_list'] + $vis, 0, "", "."),
		'GET:PagingResults'	=> sumo_paging_results($tot, $vis, $_SESSION['rows_users_list'], 5, $_SESSION['start_users_list'], 'start_users_list', 'list'),
		'GET:TableSettings'	=> sumo_get_table_settings($table['data']['list']),
		'GET:SearchForm'  	=> sumo_get_form_search($searched),					  
		'GET:ExportData'	=> sumo_get_export_data(),
		'LINK:AddUser'		=> sumo_get_action_icon("", "new", "users.content", "?module=users&action=new&decoration=false")
	);

$tpl['GET:Pagination'] = $tot > 0 ? $tpl['GET:StartRow']."...".$tpl['GET:EndRow']."&nbsp;&nbsp;".$language['of']."&nbsp;<b>".$tpl['GET:TotalRows']."</b>" : "";
	
?>