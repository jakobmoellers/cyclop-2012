<?php
/**
 * SUMO MODULE: Users | Export
 * 
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @package    SUMO
 * @category   Console
 */

// set table settings		
sumo_set_table_settings();
					
$num_groups = count($SUMO['user']['group']);		
		
// Create sql query to select only groups of user
$group_query = sumo_get_group_query(false, true);
$users_lang  = sumo_get_string_languages();

// Create search query
$search = '';
	
if($_SESSION['search_users_list']) 
{		
	$field['user'] 	    = sumo_search_composer($_SESSION['search_users_list'], 'username');
	$field['firstname'] = sumo_search_composer($_SESSION['search_users_list'], 'firstname');
	$field['lastname']  = sumo_search_composer($_SESSION['search_users_list'], 'lastname');
	$field['email']     = sumo_search_composer($_SESSION['search_users_list'], 'email');
	
	$search  = $group_query ? " AND " : " WHERE ";		
	$operand = count($field['lastname'][1]) > 1 ? 'AND' : 'OR'; 
	
	if($field['user'][0] && $field['firstname'][0] && $field['lastname'][0])
	{
		$search = $search."((".$field['user'][0].") OR (".$field['email'][0].") OR "
				."((".$field['firstname'][0].") ".$operand." (".$field['lastname'][0].")) OR "
				."(".$field['usergroup'][0]."))";
	}
	else 
	{
		$search  = '';
	}
} 
		
$query = "SELECT * FROM ".SUMO_TABLE_USERS." ".$group_query." ".$search." 
	  ORDER BY ".$_SESSION['users']['list']['col_sql']." ".$_SESSION['users']['list']['mode_sql'];		
				
$rs = $SUMO['DB']->CacheExecute(10, $query);


// Get datasources list
if($_SESSION['users']['list']['col'][9])
{
	$datasources = sumo_get_datasource_info();
	
	for($d=0; $d<count($datasources); $d++)
	{
		$datasource[$datasources[$d]['id']] = $datasources[$d]['name'];
	}
}


switch($_POST['type']) 
{
	case '':
	case 'csv': $ext = 'csv'; break;
	case 'xls': $ext = 'xls'; break;
	case 'csvdump': $ext = 'dump.csv'; break;
}

$filename = "SUMO_".$_SESSION['module']."_".date("Ymd").".".$ext;


// Export full database table to CSV
if($ext == 'dump.csv') 
{			    
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".$filename."\";");
	header("Content-Transfer-Encoding: binary");
	
	include_once(SUMO_PATH.'/applications/adodb/toexport.inc.php');
	
	rs2tabout($rs);	
	
	exit;
}


// Export to CSV
if($ext == 'csv') 
{		
	// Create list
	$list = "";
			
	if($_SESSION['users']['list']['col'][6])  $list .= "\"id\";";
	if($_SESSION['users']['list']['col'][2])  $list .= "\"".$language['User']."\";";		
	if($_SESSION['users']['list']['col'][4])  $list .= "\"".$language['UserName']."\";";
	if($_SESSION['users']['list']['col'][8])  $list .= "\"".$language['Group']."\";";
	if($_SESSION['users']['list']['col'][9])  $list .= "\"".$language['DataSource']."\";";
	if($_SESSION['users']['list']['col'][13]) $list .= "\"".$language['Email']."\";";
	if($_SESSION['users']['list']['col'][12]) $list .= "\"".$language['Language']."\";";
	if($_SESSION['users']['list']['col'][10]) $list .= "\"".$language['LastLogin']."\";";
	if($_SESSION['users']['list']['col'][15]) $list .= "\"".$language['Created']."\";";
	if($_SESSION['users']['list']['col'][11]) $list .= "\"".$language['Expire']."\";";
	
	$list .= "\n";	

	while($tab = $rs->FetchRow()) 
	{			
		$last_login = $tab['last_login'] ? sumo_get_human_date($tab['last_login']) : '';
		$created    = $tab['created']    ? sumo_get_human_date($tab['created']) : '';
		$expire     = $tab['day_limit'] != NULL ? sumo_get_human_date($tab['day_limit'] * 86400 + $SUMO['server']['time'], FALSE) : '';
		$username   = sumo_get_formatted_username($tab['firstname'], $tab['lastname']);	      
        
		if($_SESSION['users']['list']['col'][6])  $list .= $tab['id'].";";
		if($_SESSION['users']['list']['col'][2])  $list .= "\"".$tab['username']."\";";		
		if($_SESSION['users']['list']['col'][4])  $list .= "\"".$username."\";";
		if($_SESSION['users']['list']['col'][8])  $list .= "\"".$tab['usergroup']."\";";
		if($_SESSION['users']['list']['col'][9])  $list .= "\"".$datasource[$tab['datasource_id']]."\";";
		if($_SESSION['users']['list']['col'][13]) $list .= "\"".$tab['email']."\";";
		if($_SESSION['users']['list']['col'][12]) $list .= "\"".ucfirst($users_lang[$tab['language']])."\";";
		if($_SESSION['users']['list']['col'][10]) $list .= "\"".$last_login."\";";
		if($_SESSION['users']['list']['col'][15]) $list .= "\"".$created."\";";
		if($_SESSION['users']['list']['col'][11]) $list .= "\"".$expire."\";";
		
		$list .= "\n";
	}
	    
    header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header("Content-Type: application/octet-stream");
	//header ("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"".$filename."\";");
	header("Content-Transfer-Encoding: binary");
	
	echo $list;		
}
	
// Export to Excel
if($ext == 'xls') 
{					
	$excel = new ExcelGen($filename);
	
	$row = $col = 0;
		
	if($_SESSION['users']['list']['col'][6])  { $excel->WriteText($row, $col, 'id'); $col++; }
	if($_SESSION['users']['list']['col'][2])  { $excel->WriteText($row, $col, $language['User']); $col++; }	
	if($_SESSION['users']['list']['col'][4])  { $excel->WriteText($row, $col, $language['UserName']); $col++; }
	if($_SESSION['users']['list']['col'][8])  { $excel->WriteText($row, $col, $language['Group']); $col++; }
	if($_SESSION['users']['list']['col'][9])  { $excel->WriteText($row, $col, $language['DataSource']); $col++; }
	if($_SESSION['users']['list']['col'][13]) { $excel->WriteText($row, $col, $language['Email']); $col++; }
	if($_SESSION['users']['list']['col'][12]) { $excel->WriteText($row, $col, $language['Language']); $col++; }
	if($_SESSION['users']['list']['col'][10]) { $excel->WriteText($row, $col, $language['LastLogin']); $col++; }
	if($_SESSION['users']['list']['col'][15]) { $excel->WriteText($row, $col, $language['Created']); $col++; }
	if($_SESSION['users']['list']['col'][11]) { $excel->WriteText($row, $col, $language['Expire']); }
			
		
	while($tab = $rs->FetchRow()) 
	{			
		$last_login = $tab['last_login'] ? sumo_get_human_date($tab['last_login']) : '';
		$created    = $tab['created']    ? sumo_get_human_date($tab['created'])    : '';
		$expire     = $tab['day_limit'] != NULL ? sumo_get_human_date($tab['day_limit'] * 86400 + $SUMO['server']['time'], FALSE) : '';
		$username   = sumo_get_formatted_username($tab['firstname'], $tab['lastname']);	        	        	        
		$col 	    = 0;
		$row++;
		
		if($username == '&nbsp;') $username = '';
		
		if($_SESSION['users']['list']['col'][6])  { $excel->WriteNumber($row, $col, $tab['id']);  $col++; }
		if($_SESSION['users']['list']['col'][2])  { $excel->WriteText($row, $col, $tab['username']);  $col++; }
		if($_SESSION['users']['list']['col'][4])  { $excel->WriteText($row, $col, $username);     $col++; }
		if($_SESSION['users']['list']['col'][8])  { $excel->WriteText($row, $col, $tab['usergroup']); $col++; }
		if($_SESSION['users']['list']['col'][9])  { $excel->WriteText($row, $col, $datasource[$tab['datasource_id']]); $col++; }
		if($_SESSION['users']['list']['col'][13]) { $excel->WriteText($row, $col, $tab['email']); $col++; }
		if($_SESSION['users']['list']['col'][12]) { $excel->WriteText($row, $col, ucfirst($users_lang[$tab['language']])); $col++; }
		if($_SESSION['users']['list']['col'][10]) { $excel->WriteText($row, $col, $last_login);   $col++; }
		if($_SESSION['users']['list']['col'][15]) { $excel->WriteText($row, $col, $created);      $col++; }
		if($_SESSION['users']['list']['col'][11]) { $excel->WriteText($row, $col, $expire); }
	}
	
	//stream Excel for user to download or show on browser
	$excel->SendFile();
}
	
?>