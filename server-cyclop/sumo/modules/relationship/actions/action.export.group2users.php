<?php
/**
 * SUMO MODULE: Relationship | Export Data
 * 
 * @version    0.3.5
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
	
if($_SESSION['search_relationship_group2users']) 
{			
	$field['usergroup'] = sumo_search_composer($_SESSION['search_relationship_group2users'], 'usergroup');
				
	$search = $field['usergroup'][0] ? "WHERE (".$field['usergroup'][0].")" : "";
} 
	
// Create sql query	to select only groups of user
$group_query = sumo_get_group_query($search);		
	
$query = "SELECT * FROM ".SUMO_TABLE_GROUPS." 
		 ".$search." 
		 ".$group_query." 
		  ORDER BY ".$_SESSION['relationship']['group2users']['col_sql']." ".$_SESSION['relationship']['group2users']['mode_sql'];												
				
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
			
	$list .= "\"".$language['GroupName']."\";";		
	$list .= "\"".$language['UsersList']."\";";
	
	$list .= "\n";	

	while($tab = $rs->FetchRow()) 
	{			
		$query = "SELECT id,username,firstname,lastname
				  FROM ".SUMO_TABLE_USERS."
				  WHERE (usergroup LIKE '".$tab['usergroup'].":%' 
						 OR usergroup LIKE '%;".$tab['usergroup'].":%'
						 OR usergroup LIKE 'sumo:%'
						 OR usergroup LIKE '%;sumo:%')
				  AND active=1 
				  AND username<>'sumo'
				  ORDER BY username, lastname, firstname";
		
		$rs2 = $SUMO['DB']->Execute($query);
	
		$users = "";
		
		while($tab2 = $rs2->FetchRow()) 
		{
			$users .= $tab2['user']." - ".$tab2['lastname']." ".$tab2['firstname'].", ";
		}
		
		$list .= "\"".$tab['usergroup']."\";\"".$users."\";\n";
	}
	    
	// bugfix
	$list = str_replace(", \";", "\";", $list);
	
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
		
	$excel->WriteText($row, $col, $language['GroupName']); $col++; 
	$excel->WriteText($row, $col, $language['UsersList']); 
						
	while($tab = $rs->FetchRow()) 
	{	  
		$col = 0;
		$row++;
		
		$excel->WriteText($row, $col, $tab['usergroup']); 
		
		$query = "SELECT id,username,firstname,lastname
				  FROM ".SUMO_TABLE_USERS."
				  WHERE (usergroup LIKE '".$tab['usergroup'].":%' 
						 OR usergroup LIKE '%;".$tab['usergroup'].":%'
						 OR usergroup LIKE 'sumo:%'
						 OR usergroup LIKE '%;sumo:%')
				  AND active=1 
				  AND username<>'sumo'
				  ORDER BY username, lastname, firstname";
		
		$rs2 = $SUMO['DB']->Execute($query);
		
		while($tab2 = $rs2->FetchRow()) 
		{
			$col = 1;
			$excel->WriteText($row, $col, $tab2['user']);
			$col++;
			$excel->WriteText($row, $col, $tab2['lastname']." ".$tab2['firstname']);
			$row++;
		}
	}
	
	//stream Excel for user to download or show on browser
	$excel->SendFile();
}

?>