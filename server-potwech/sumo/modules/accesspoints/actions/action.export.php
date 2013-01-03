<?php
/**
 * SUMO MODULE: Accesspoints | Export
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

$query = "SELECT a.id AS id, b.\"name\" AS node_name, a.path AS path, a.\"name\" AS \"name\", 
a.usergroup AS usergroup, a.http_auth AS http_auth, a.filtering AS filtering,
a.pwd_encrypt AS pwd_encrypt, a.registration AS registration, a.reg_group AS reg_group, 
a.change_pwd AS change_pwd, a.theme AS theme, a.created AS created, a.updated AS updated,
b.id AS node_id
FROM ".SUMO_TABLE_ACCESSPOINTS." a, ".SUMO_TABLE_NODES." b 
WHERE a.node = b.id ".$search." ".$group_query." 
ORDER BY ".$_SESSION['accesspoints']['list']['col_sql']." ".$_SESSION['accesspoints']['list']['mode_sql'];

// MySQL Fix
$query = str_replace('"', '', $query);

$rs = $SUMO['DB']->Execute($query);


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
			
	if($_SESSION['accesspoints']['list']['col'][4])  $list .= "\"id\";";
	if($_SESSION['accesspoints']['list']['col'][4])  $list .= "\"".$language['Name']."\";";		
	if($_SESSION['accesspoints']['list']['col'][2])  $list .= "\"".$language['Node']."\";";
	if($_SESSION['accesspoints']['list']['col'][3])  $list .= "\"".$language['Path']."\";";
	if($_SESSION['accesspoints']['list']['col'][5])  $list .= "\"".$language['Groups']."\";";
	if($_SESSION['accesspoints']['list']['col'][6])  $list .= "\"".$language['HTTPAuth']."\";";
	if($_SESSION['accesspoints']['list']['col'][7])  $list .= "\"".$language['Filtering']."\";";
	if($_SESSION['accesspoints']['list']['col'][8])  $list .= "\"".$language['PwdEncrypt']."\";";		
	if($_SESSION['accesspoints']['list']['col'][9])  $list .= "\"".$language['CanRegister']."\";";		
	if($_SESSION['accesspoints']['list']['col'][10]) $list .= "\"".$language['RegGroup']."\";";
	if($_SESSION['accesspoints']['list']['col'][11]) $list .= "\"".$language['PwdChange']."\";";
	if($_SESSION['accesspoints']['list']['col'][12]) $list .= "\"".$language['Theme']."\";";
	if($_SESSION['accesspoints']['list']['col'][13]) $list .= "\"".$language['Created']."\";";
	if($_SESSION['accesspoints']['list']['col'][14]) $list .= "\"".$language['Updated']."\";";
	
	$list .= "\n";	
	
	while($tab = $rs->FetchRow()) 
	{			
		$http_auth    = $tab['http_auth']    ? 'Y' : 'N';
		$filtering    = $tab['filtering']    ? 'Y' : 'N';
		$pwd_encrypt  = $tab['pwd_encrypt']  ? 'Y' : 'N';
		$registration = $tab['registration'] ? 'Y' : 'N';
		$change_pwd   = $tab['change_pwd']   ? 'Y' : 'N';			
		$group 		  = str_replace(';', ', ', $tab['usergroup']);				
		$theme   	  = ucwords($tab['theme']);
		$created 	  = $tab['created'] ? sumo_get_human_date($tab['created']) : '';
		$updated 	  = $tab['updated'] ? sumo_get_human_date($tab['updated']) : '';
		$name 		  = sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']);
		
		if($_SESSION['accesspoints']['list']['col'][4])  $list .= $tab['id'].";";
		if($_SESSION['accesspoints']['list']['col'][4])  $list .= "\"".$name."\";";
		if($_SESSION['accesspoints']['list']['col'][2])  $list .= "\"".$tab['node_name']."\";";		
		if($_SESSION['accesspoints']['list']['col'][3])  $list .= "\"".$tab['path']."\";";
		if($_SESSION['accesspoints']['list']['col'][5])  $list .= "\"".$group."\";";
		if($_SESSION['accesspoints']['list']['col'][6])  $list .= "\"".$http_auth."\";";
		if($_SESSION['accesspoints']['list']['col'][7])  $list .= "\"".$filtering."\";";
		if($_SESSION['accesspoints']['list']['col'][8])  $list .= "\"".$pwd_encrypt."\";";
		if($_SESSION['accesspoints']['list']['col'][9])  $list .= "\"".$registration."\";";
		if($_SESSION['accesspoints']['list']['col'][10]) $list .= "\"".$tab['reg_group']."\";";
		if($_SESSION['accesspoints']['list']['col'][11]) $list .= "\"".$change_pwd."\";";			
		if($_SESSION['accesspoints']['list']['col'][12]) $list .= "\"".$theme."\";";
		if($_SESSION['accesspoints']['list']['col'][13]) $list .= "\"".$created."\";";
		if($_SESSION['accesspoints']['list']['col'][14]) $list .= "\"".$updated."\";";						
		
		$list .= "\n";
	}
	    
    header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".$filename."\";");
	header("Content-Transfer-Encoding: binary");
	
	echo $list;		
}

// Export to Excel
if($ext == 'xls') 
{			
	$excel = new ExcelGen($filename);
	
	$row = $col = 0;
		
	if($_SESSION['accesspoints']['list']['col'][4])  { $excel->WriteText($row, $col, 'id'); $col++; }
	if($_SESSION['accesspoints']['list']['col'][4])  { $excel->WriteText($row, $col, $language['Name']); $col++; }	
	if($_SESSION['accesspoints']['list']['col'][2])  { $excel->WriteText($row, $col, $language['Node']); $col++; }
	if($_SESSION['accesspoints']['list']['col'][3])  { $excel->WriteText($row, $col, $language['Path']); $col++; }
	if($_SESSION['accesspoints']['list']['col'][5])  { $excel->WriteText($row, $col, $language['Groups']); $col++; }
	if($_SESSION['accesspoints']['list']['col'][6])  { $excel->WriteText($row, $col, $language['HTTPAuth']); $col++; }
	if($_SESSION['accesspoints']['list']['col'][7])  { $excel->WriteText($row, $col, $language['Filtering']); $col++; }
	if($_SESSION['accesspoints']['list']['col'][8])  { $excel->WriteText($row, $col, $language['PwdEncrypt']); $col++; }		
	if($_SESSION['accesspoints']['list']['col'][9])  { $excel->WriteText($row, $col, $language['CanRegister']); $col++; }		
	if($_SESSION['accesspoints']['list']['col'][10]) { $excel->WriteText($row, $col, $language['RegGroup']); $col++; }		
	if($_SESSION['accesspoints']['list']['col'][11]) { $excel->WriteText($row, $col, $language['PwdChange']); $col++; }
	if($_SESSION['accesspoints']['list']['col'][12]) { $excel->WriteText($row, $col, $language['Theme']); $col++; }
	if($_SESSION['accesspoints']['list']['col'][13]) { $excel->WriteText($row, $col, $language['Created']); $col++; }
	if($_SESSION['accesspoints']['list']['col'][14]) { $excel->WriteText($row, $col, $language['Updated']); }
		
			
	while($tab = $rs->FetchRow()) 
	{			
		$http_auth    = $tab['http_auth']    ? 'Y' : 'N';
		$filtering    = $tab['filtering']    ? 'Y' : 'N';
		$pwd_encrypt  = $tab['pwd_encrypt']  ? 'Y' : 'N';
		$registration = $tab['registration'] ? 'Y' : 'N';
		$change_pwd   = $tab['change_pwd']   ? 'Y' : 'N';			
		$group 		  = str_replace(';', ', ', $tab['usergroup']);	
		$theme   	  = ucwords($tab['theme']);
		$created 	  = $tab['created'] ? sumo_get_human_date($tab['created']) : '';
		$updated 	  = $tab['updated'] ? sumo_get_human_date($tab['updated']) : '';
		$name 		  = sumo_get_accesspoint_name($tab['name'], $_COOKIE['language']);
		$col 		  = 0;
		$row++;
		
		if($_SESSION['accesspoints']['list']['col'][4])  { $excel->WriteNumber($row, $col, $tab['id']);  $col++; }
		if($_SESSION['accesspoints']['list']['col'][4])  { $excel->WriteText($row, $col, $name);  $col++; }
		if($_SESSION['accesspoints']['list']['col'][2])  { $excel->WriteText($row, $col, $tab['node_name']); $col++; }
		if($_SESSION['accesspoints']['list']['col'][3])  { $excel->WriteText($row, $col, $tab['path']);  $col++; }
		if($_SESSION['accesspoints']['list']['col'][5])  { $excel->WriteText($row, $col, $tab['usergroup']); $col++; }
		if($_SESSION['accesspoints']['list']['col'][6])  { $excel->WriteText($row, $col, $http_auth);    $col++; }
		if($_SESSION['accesspoints']['list']['col'][7])  { $excel->WriteText($row, $col, $filtering);    $col++; }
		if($_SESSION['accesspoints']['list']['col'][8])  { $excel->WriteText($row, $col, $pwd_encrypt);  $col++; }
		if($_SESSION['accesspoints']['list']['col'][9])  { $excel->WriteText($row, $col, $registration); $col++; }
		if($_SESSION['accesspoints']['list']['col'][10]) { $excel->WriteText($row, $col, $tab['reg_group']); $col++; }
		if($_SESSION['accesspoints']['list']['col'][11]) { $excel->WriteText($row, $col, $change_pwd); $col++; }
		if($_SESSION['accesspoints']['list']['col'][12]) { $excel->WriteText($row, $col, $theme); $col++; }
		if($_SESSION['accesspoints']['list']['col'][13]) { $excel->WriteText($row, $col, $created); $col++; }
		if($_SESSION['accesspoints']['list']['col'][14]) { $excel->WriteText($row, $col, $updated); }			
	}
	
	//stream Excel for user to download or show on browser
	$excel->SendFile();
}
	
?>