<?php
/**
 * SUMO MODULE: Groups | Export Data
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
	
if($_SESSION['search_groups_list']) 
{			
	$field['usergroup']   = sumo_search_composer($_SESSION['search_groups_list'], 'usergroup');
	$field['description'] = sumo_search_composer($_SESSION['search_groups_list'], 'description');
				
	$search = ($field['usergroup'][0] && $field['description'][0]) ? "WHERE (".$field['usergroup'][0]." OR ".$field['description'][0].")" : "";
} 
	
// Create sql query	to select only groups of user
$group_query = sumo_get_group_query($search);		
	
$query = "SELECT * FROM ".SUMO_TABLE_GROUPS." 
		 ".$search." 
		 ".$group_query." 
		  ORDER BY ".$_SESSION['groups']['list']['col_sql']." ".$_SESSION['groups']['list']['mode_sql'];												
				
$rs = $SUMO['DB']->CacheExecute(10, $query);


switch($_POST['type']) 
{
	case '':
	case 'csv': $ext = 'csv'; break;
	case 'xls': $ext = 'xls'; break;
	case 'csvdump': $ext = 'dump.csv'; break;
}

$filename = "SUMO_".$_SESSION['module']."_list_".date("Ymd").".".$ext;


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
			
	if($_SESSION['groups']['list']['col'][2])   $list .= "\"id\";";
	if($_SESSION['groups']['list']['col'][2])   $list .= "\"".$language['Groups']."\";";		
	if($_SESSION['groups']['list']['col'][3])   $list .= "\"".$language['Description']."\";";
	if($_SESSION['groups']['list']['col'][100]) $list .= "\"".$language['Users']."\";";
	if($_SESSION['groups']['list']['col'][5])   $list .= "\"".$language['Updated']."\";";
	if($_SESSION['groups']['list']['col'][4])   $list .= "\"".$language['Created']."\";";
	
	$list .= "\n";	

	while($tab = $rs->FetchRow()) 
	{			
		$created = ($tab['created']) ? sumo_get_human_date($tab['created']) : '';
		$updated = ($tab['updated']) ? sumo_get_human_date($tab['updated']) : '';	
		 
		if($_SESSION['groups']['list']['col'][100])
        {
        	$users = sumo_get_group_users($tab['id']);
        	
        	if($users == 0) $users = $language['NoUsers'];
        }
        
		if($_SESSION['groups']['list']['col'][2])   $list .= $tab['id'].";";
		if($_SESSION['groups']['list']['col'][2])   $list .= "\"".$tab['usergroup']."\";";		
		if($_SESSION['groups']['list']['col'][3])   $list .= "\"".$tab['description']."\";";
		if($_SESSION['groups']['list']['col'][100]) $list .= "\"".$users."\";";
		if($_SESSION['groups']['list']['col'][5])   $list .= "\"".$updated."\";";
		if($_SESSION['groups']['list']['col'][4])   $list .= "\"".$created."\";";
		
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
		
	if($_SESSION['groups']['list']['col'][2])   { $excel->WriteText($row, $col, 'id'); 				       $col++; }
	if($_SESSION['groups']['list']['col'][2])   { $excel->WriteText($row, $col, $language['Groups']);      $col++; }	
	if($_SESSION['groups']['list']['col'][3])   { $excel->WriteText($row, $col, $language['Description']); $col++; }
	if($_SESSION['groups']['list']['col'][100]) { $excel->WriteText($row, $col, $language['Users']);       $col++; }
	if($_SESSION['groups']['list']['col'][5])   { $excel->WriteText($row, $col, $language['Updated']);     $col++; }
	if($_SESSION['groups']['list']['col'][4])   { $excel->WriteText($row, $col, $language['Created']); }
						
	while($tab = $rs->FetchRow()) 
	{
		$created = $tab['created'] ? sumo_get_human_date($tab['created']) : '';
		$updated = $tab['updated'] ? sumo_get_human_date($tab['updated']) : '';
		  
		if($_SESSION['groups']['list']['col'][100])
        {
        	$users = sumo_get_group_users($tab['id']);
        }
        
		$col = 0;
		$row++;
					
		if($_SESSION['groups']['list']['col'][2])   { $excel->WriteNumber($row, $col, $tab['id']);        $col++; }
		if($_SESSION['groups']['list']['col'][2])   { $excel->WriteText($row, $col, $tab['usergroup']);   $col++; }
		if($_SESSION['groups']['list']['col'][3])   { $excel->WriteText($row, $col, $tab['description']); $col++; }
		if($_SESSION['groups']['list']['col'][100]) { $excel->WriteNumber($row, $col, $users);            $col++; }
		if($_SESSION['groups']['list']['col'][5])   { $excel->WriteText($row, $col, $updated);            $col++; }
		if($_SESSION['groups']['list']['col'][4])   { $excel->WriteText($row, $col, $created); }
	}
	
	//stream Excel for user to download or show on browser
	$excel->SendFile();
}

?>