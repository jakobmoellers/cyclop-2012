<?php
/**
 * SUMO MODULE: Security | Export
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
	
if($_SESSION['search_security_'.$table]) 
{			
	$field['message'] = sumo_search_composer($_SESSION['search_security_'.$table], 'message', 'AND');
					
	if($field['message'][0]) $search = "WHERE (".$field['message'][0].")";
} 		
	

$submodule = $_GET['submodule'];

// Table selector
switch($submodule) 
{			            
	case 'system_list': $query = "SELECT * FROM ".SUMO_TABLE_LOG_SYSTEM." ".$search; break;
    case 'access_list': $query = "SELECT * FROM ".SUMO_TABLE_LOG_ACCESS." ".$search; break;
    case 'errors_list': $query = "SELECT * FROM ".SUMO_TABLE_LOG_ERRORS." ".$search; break;
   	default:
   		$query = "SELECT * FROM ".SUMO_TABLE_LOG_SYSTEM." ".$search." 
   	    		  UNION 
                  SELECT * FROM ".SUMO_TABLE_LOG_ACCESS." ".$search." 
                  UNION 
                  SELECT * FROM ".SUMO_TABLE_LOG_ERRORS." ".$search; 
           break;
}
	                             
$query = $query." ORDER BY ".$_SESSION['security'][$submodule]['col_sql']." ".$_SESSION['security'][$submodule]['mode_sql'];	
			

$rs = $SUMO['DB']->CacheExecute(10, $query);
	
switch($_POST['type']) 
{
	case '':
	case 'csv': $ext = 'csv'; break;
	case 'xls': $ext = 'xls'; break;
	case 'csvdump': $ext = 'dump.csv'; break;
}


$filename = "SUMO_".$_SESSION['module']."_".$submodule."_".date("Ymd").".".$ext;


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
			
	if($_SESSION['security'][$submodule]['col'][3]) $list .= "\"id\";";
	if($_SESSION['security'][$submodule]['col'][3]) $list .= "\"".$language['Priority']."\";";				
	if($_SESSION['security'][$submodule]['col'][3]) $list .= "\"".$language['Code']."\";";		
	if($_SESSION['security'][$submodule]['col'][4]) $list .= "\"".$language['Node']."\";";
	if($_SESSION['security'][$submodule]['col'][5]) $list .= "\"".$language['IPClient']."\";";
	if($_SESSION['security'][$submodule]['col'][6]) $list .= "\"".$language['Country']."\";";
	if($_SESSION['security'][$submodule]['col'][7]) $list .= "\"".$language['LogMessage']."\";";
	if($_SESSION['security'][$submodule]['col'][8]) $list .= "\"".$language['Date']."\";";
	
	$list .= "\n";	

	while($tab = $rs->FetchRow()) 
	{						
		$node = sumo_get_node_info($tab['node'], 'ip'); 
		
		if($_SESSION['security'][$submodule]['col'][3]) $list .= $tab['id'].";";
		if($_SESSION['security'][$submodule]['col'][3]) $list .= $tab['priority'].";";				
		if($_SESSION['security'][$submodule]['col'][3]) $list .= "\"".$tab['code']."\";";		
		if($_SESSION['security'][$submodule]['col'][4]) $list .= "\"".$node['name']."\";";
		if($_SESSION['security'][$submodule]['col'][5]) $list .= "\"".$tab['ip']."\";";
		if($_SESSION['security'][$submodule]['col'][6]) $list .= "\"".ucwords(strtolower($tab['country_name']))."\";";
		if($_SESSION['security'][$submodule]['col'][7]) $list .= "\"".str_replace('"','\'', $tab['message'])."\";";
		if($_SESSION['security'][$submodule]['col'][8]) $list .= "\"".sumo_get_human_date($tab['time'], true, true)."\";";
		
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
		
	if($_SESSION['security'][$submodule]['col'][3]) { $excel->WriteText($row, $col, 'id'); $col++; }		
	if($_SESSION['security'][$submodule]['col'][3]) { $excel->WriteText($row, $col, $language['Priority']);   $col++; }
	if($_SESSION['security'][$submodule]['col'][3]) { $excel->WriteText($row, $col, $language['Code']);       $col++; }	
	if($_SESSION['security'][$submodule]['col'][4]) { $excel->WriteText($row, $col, $language['Node']);   $col++; }
	if($_SESSION['security'][$submodule]['col'][5]) { $excel->WriteText($row, $col, $language['IPClient']);   $col++; }
	if($_SESSION['security'][$submodule]['col'][6]) { $excel->WriteText($row, $col, $language['Country']);    $col++; }
	if($_SESSION['security'][$submodule]['col'][7]) { $excel->WriteText($row, $col, $language['LogMessage']); $col++; }
	if($_SESSION['security'][$submodule]['col'][8]) { $excel->WriteText($row, $col, $language['Date']); }
			
		
	while($tab = $rs->FetchRow()) 
	{		
		$col = 0;
		$row++;
			
		$node = sumo_get_node_info($tab['node'], 'ip'); 	
		
		if($_SESSION['security'][$submodule]['col'][3]) { $excel->WriteNumber($row, $col, $tab['id']);       $col++; }
		if($_SESSION['security'][$submodule]['col'][3]) { $excel->WriteNumber($row, $col, $tab['priority']); $col++; }
		if($_SESSION['security'][$submodule]['col'][3]) { $excel->WriteText($row, $col, $tab['code']);       $col++; }
		if($_SESSION['security'][$submodule]['col'][4]) { $excel->WriteText($row, $col, $node['name']);      $col++; }
		if($_SESSION['security'][$submodule]['col'][5]) { $excel->WriteText($row, $col, $tab['ip']);         $col++; }
		if($_SESSION['security'][$submodule]['col'][6]) { $excel->WriteText($row, $col, ucwords(strtolower($tab['country_name']))); $col++; }
		if($_SESSION['security'][$submodule]['col'][7]) { $excel->WriteText($row, $col, $tab['message']); $col++; }
		if($_SESSION['security'][$submodule]['col'][8]) { $excel->WriteText($row, $col, sumo_get_human_date($tab['time'], TRUE, TRUE)); }
	}
	
	//stream Excel for user to download or show on browser
	$excel->SendFile();
}	
	
?>