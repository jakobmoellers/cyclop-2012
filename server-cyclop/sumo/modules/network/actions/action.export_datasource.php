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
	
if($_SESSION['search_network_dlist']) 
{	
	$field['name'] = sumo_search_composer($_SESSION['search_network_dlist'], 'name');
	
	$search = $field['name'][0] ? " WHERE ".$field['name'][0]." " : '';
} 

			
$query = "SELECT * FROM ".SUMO_TABLE_DATASOURCES." 
		".$search." 
		 ORDER BY ".$_SESSION['network']['dlist']['col_sql']." ".$_SESSION['network']['dlist']['mode_sql'];	
		
$rs = $SUMO['DB']->Execute($query);


switch($_POST['type']) 
{
	case '':
	case 'csv': $ext = 'csv'; break;
	case 'xls': $ext = 'xls'; break;
	case 'csvdump': $ext = 'dump.csv'; break;
}

$filename = "SUMO_".$_SESSION['module']."_datasources_".date("Ymd").".".$ext;


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
			
	//if($_SESSION['network']['dlist']['col'][0])  $list .= "\"".$language['Status']."\";";
	if($_SESSION['network']['dlist']['col'][2])  $list .= "\"".$language['DataSourceName']."\";";
	if($_SESSION['network']['dlist']['col'][3])  $list .= "\"".$language['DataSourceType']."\";";
	if($_SESSION['network']['dlist']['col'][4])  $list .= "\"".$language['Hostname']."\";";		
	if($_SESSION['network']['dlist']['col'][5])  $list .= "\"".$language['Port']."\";";
	if($_SESSION['network']['dlist']['col'][6])  $list .= "\"".$language['User']."\";";
	if($_SESSION['network']['dlist']['col'][8])  $list .= "\"".$language['DBName']."\";";		
	if($_SESSION['network']['dlist']['col'][12]) $list .= "\"".$language['EncType']."\";";
	if($_SESSION['network']['dlist']['col'][13]) $list .= "\"".$language['LDAPBase']."\";";
	
	$list .= "\n";	
	
	while($tab = $rs->FetchRow()) 
	{
		//if($_SESSION['network']['dlist']['col'][0])  $list .= $tab['id'].";";
		if($_SESSION['network']['dlist']['col'][2])  $list .= "\"".$tab['name']."\";";		
		if($_SESSION['network']['dlist']['col'][3])  $list .= "\"".$language[$tab['type']]."\";";
		if($_SESSION['network']['dlist']['col'][4])  $list .= "\"".$tab['host']."\";";
		if($_SESSION['network']['dlist']['col'][5])  $list .= "\"".$tab['port']."\";";
		if($_SESSION['network']['dlist']['col'][6])  $list .= "\"".$tab['username']."\";";
		if($_SESSION['network']['dlist']['col'][8])  $list .= "\"".$tab['db_name']."\";";
		if($_SESSION['network']['dlist']['col'][12]) $list .= "\"".$tab['enctype']."\";";
		if($_SESSION['network']['dlist']['col'][13]) $list .= "\"".$tab['ldap_base']."\";";
		
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
		
	//if($_SESSION['network']['dlist']['col'][0])  { $excel->WriteText($row, $col, $language['Status']); $col++; }
	if($_SESSION['network']['dlist']['col'][2])  { $excel->WriteText($row, $col, $language['DataSourceName']); $col++; }
	if($_SESSION['network']['dlist']['col'][3])  { $excel->WriteText($row, $col, $language['DataSourceType']); $col++; }
	if($_SESSION['network']['dlist']['col'][4])  { $excel->WriteText($row, $col, $language['Hostname']); $col++; }
	if($_SESSION['network']['dlist']['col'][5])  { $excel->WriteText($row, $col, $language['Port']); $col++; }
	if($_SESSION['network']['dlist']['col'][6])  { $excel->WriteText($row, $col, $language['User']); $col++; }
	if($_SESSION['network']['dlist']['col'][8])  { $excel->WriteText($row, $col, $language['DBName']); $col++; }		
	if($_SESSION['network']['dlist']['col'][12]) { $excel->WriteText($row, $col, $language['EncType']); $col++; }
	if($_SESSION['network']['dlist']['col'][13]) { $excel->WriteText($row, $col, $language['LDAPBase']); $col++; }
	
	while($tab = $rs->FetchRow()) 
	{			
		$col = 0;
		$row++;
		
		//if($_SESSION['network']['dlist']['col'][0])  { $excel->WriteNumber($row, $col, $tab['id']);  $col++; }
		if($_SESSION['network']['dlist']['col'][2])  { $excel->WriteText($row, $col, $tab['name']); $col++; }
		if($_SESSION['network']['dlist']['col'][3])  { $excel->WriteText($row, $col, $language[$tab['type']]);  $col++; }
		if($_SESSION['network']['dlist']['col'][4])  { $excel->WriteText($row, $col, $tab['host']);  $col++; }
		if($_SESSION['network']['dlist']['col'][5])  { $excel->WriteText($row, $col, $tab['port']); $col++; }
		if($_SESSION['network']['dlist']['col'][6])  { $excel->WriteText($row, $col, $tab['username']);    $col++; }
		if($_SESSION['network']['dlist']['col'][8])  { $excel->WriteText($row, $col, $tab['db_name']);  $col++; }
		if($_SESSION['network']['dlist']['col'][12]) { $excel->WriteText($row, $col, $tab['enctype']); $col++; }
		if($_SESSION['network']['dlist']['col'][13]) { $excel->WriteText($row, $col, $tab['ldap_base']); $col++; }
	}
	
	//stream Excel for user to download or show on browser
	$excel->SendFile();
}
	
?>