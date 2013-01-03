<?php
/**
 * SUMO LIBRARY: Network
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Console
 */


/**
 * Delete datasource
 * 
 * @return boolean 
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_delete_datasource($id=0)
{	
	$id = intval($id);
	
	if($id > 1) 
	{		
		GLOBAL $SUMO;
		
		$datasource = sumo_get_datasource_info($id, FALSE);
		
		$SUMO['DB']->CacheFlush();

	        $query0 = "DELETE FROM ".SUMO_TABLE_DATASOURCES." 
    		   	   WHERE id=".$id;
		$query1 = "SELECT * FROM ".SUMO_TABLE_DATASOURCES." 
        		   WHERE id=".$id;
        
		$SUMO['DB']->Execute($query0);
		$SUMO['DB']->Execute($query1);
        
		// verify if deleted: 
		$rs = $SUMO['DB']->Execute($query1);
	
		// if deleted:
		if($rs->PO_RecordCount(SUMO_TABLE_DATASOURCES, "id=".$id) == 0) 
		{							  
			sumo_write_log('I09001X', array($datasource['name'], $id, $SUMO['user']['user']), '0,1', 3, 'system', FALSE);

			return TRUE;
		}		
		else 
		{
			return FALSE; 
		}
	}
	else
	{
		return FALSE; 
	}
}


/**
 * Add data source
 */
function sumo_add_datasource($data=array())
{	
	if(!empty($data)) 
	{	
		GLOBAL $SUMO;
		
		$data['port'] = is_int($data['port']) ? $data['port'] : 'null';
		
		$query = "INSERT INTO ".SUMO_TABLE_DATASOURCES." 
				  (
				  	name, type, host, port, username, password, db_name,
				  	db_table, db_field_user, db_field_password, enctype,
				  	ldap_base
				  ) 
				  VALUES (
				  	'".$data['name']."', '".$data['type']."', '".$data['host']."',
				  	".$data['port'].", '".$data['username']."', '".$data['password']."',
				  	'".$data['db_name']."', '".$data['db_table']."', 
				  	'".$data['db_field_user']."', '".$data['db_field_password']."',
				  	'".$data['enctype']."', 
				  	'".$data['ldap_base']."'
				  )";
		
		$SUMO['DB']->Execute($query);
		
		// if data source added
		if(sumo_verify_datasource_exist('name', $data['name'])) 
		{					
			sumo_write_log('I09003X', array($data['name'], $SUMO['user']['user']), 3, 3, 'system', FALSE);
						
			return TRUE;
		}		
		else 
		{
			return FALSE; 
		}
	}
	else return FALSE;
}


/**
 * Update data source
 */
function sumo_update_datasource_data($data=array())
{	
	if(!empty($data)) 
	{	
		GLOBAL $SUMO;

		$query = "UPDATE ".SUMO_TABLE_DATASOURCES." 
				SET
			  	name='".$data['name']."', 
			  	type='".$data['type']."', 
			  	host='".$data['host']."',
			  	port=".$data['port'].",
			  	username='".$data['username']."',
			  	password='".$data['password']."',
			  	db_name='".$data['db_name']."',
			  	db_table='".$data['db_table']."',
			  	db_field_user='".$data['db_field_user']."',
			  	db_field_password='".$data['db_field_password']."',
			  	enctype='".$data['enctype']."',
			  	ldap_base='".$data['ldap_base']."'
			  WHERE id=".$data['id']." AND id<>1";

		$SUMO['DB']->Execute($query);
		
		// if data source updated
		if(sumo_verify_datasource_exist('id', $data['id'])) 
		{					
			sumo_write_log('I09006X', 
						   array($data['id'], $data['name'], $SUMO['user']['user']), 
						   3, 3, 'system', FALSE);
						
			return TRUE;
		}		
		else 
		{
			return FALSE; 
		}
	}
	else return FALSE;
}


/**
 * Put datasources Encryption type
 */
function sumo_put_datasources_enctype($default='', $datasource_type='')
{
	$availables = sumo_get_datasource_enctype($datasource_type);
	
	$list = "<select name='enctype'>\n";
	       
	if(in_array($default, $availables)) $list .= " <option value='".$default."'>".$default."</option>\n";
	 	
	$list .= " <option value=''></option>\n";	
	       
	for($l=0; $l<count($availables); $l++) 
	{		
		if($availables[$l] != $default) 
		{
			$list .= " <option value='".$availables[$l]."'>\n"
				    .$availables[$l]
				    ."</option>\n";
		}
	}
		
	$list .= "</select>";
		
	return $list;
}


/**
 * Put datasources type
 */
function sumo_put_datasources_type($default='', $form_name='')
{
	GLOBAL $language;
	
	$availables = sumo_get_available_datasources();	
	
	$name = strtolower(str_replace('Network', '', $form_name));
	
	$list = "<select name='type' "
		   ."onchange='if(document.$form_name.type.value==\"LDAP\" || document.$form_name.type.value==\"LDAPS\" || document.$form_name.type.value==\"ADAM\"){"
		   ."document.$form_name.enctype.options[0]=new Option(\"\", \"\");"
		   ."document.$form_name.enctype.options[1]=new Option(\"md5\",\"md5\");"
		   ."document.$form_name.enctype.options[2]=new Option(\"crc32\",\"crc32\");"
		   ."document.$form_name.enctype.options.length=3;"
		   ."HideElement(\"network.$name.DatabaseOptions\");ShowElement(\"network.$name.LDAPOptions\")}"
		   ."else\n"
		   ."if(document.$form_name.type.value==\"MySQL\" || document.$form_name.type.value==\"Joomla15\" || "
		   ."document.$form_name.type.value==\"Postgres\" || document.$form_name.type.value==\"Oracle\"){"
		   ."document.$form_name.enctype.options[0]=new Option(\"\", \"\");"
		   ."document.$form_name.enctype.options[1]=new Option(\"md5\",\"md5\");"
		   ."document.$form_name.enctype.options[2]=new Option(\"crypt\",\"crypt\");"
		   ."document.$form_name.enctype.options[3]=new Option(\"sha1\",\"sha1\");"
		   ."document.$form_name.enctype.options[4]=new Option(\"crc32\",\"crc32\");"
		   ."HideElement(\"network.$name.LDAPOptions\");ShowElement(\"network.$name.DatabaseOptions\")}"
		   ."else{HideElement(\"network.$name.LDAPOptions\");HideElement(\"network.$name.DatabaseOptions\")}"

		   // MySQL
		   ."if(document.$form_name.type.value==\"MySQL\"){"
		   ."document.$form_name.port.value=\"3306\";"
		   ."document.$form_name.host.disabled=false;"
		   ."document.$form_name.port.disabled=false;"
		   ."document.$form_name.username.disabled=false;"
		   ."document.$form_name.password.disabled=false;"
		   ."document.$form_name.re_password.disabled=false;"
		   ."document.$form_name.db_name.value=\"\";"
		   ."document.$form_name.db_table.value=\"\";"
		   ."document.$form_name.db_field_user.value=\"\";"
		   ."document.$form_name.db_field_password.value=\"\";"
		   ."document.$form_name.db_name.readOnly=false;"
		   ."document.$form_name.db_table.readOnly=false;"
		   ."document.$form_name.db_field_user.readOnly=false;"
		   ."document.$form_name.db_field_password.readOnly=false;"
		   ."document.$form_name.enctype.disabled=false;"
		   ."document.$form_name.db_name.disabled=false;"
		   ."document.$form_name.db_table.disabled=false;"
		   ."document.$form_name.db_field_user.disabled=false;"
		   ."document.$form_name.db_field_password.disabled=false;"
		   ."}"
		   
		   // MySQLUsers
		   ."if(document.$form_name.type.value==\"MySQLUsers\"){"
		   ."document.$form_name.port.value=\"3306\";"
		   ."document.$form_name.host.disabled=false;"
		   ."document.$form_name.port.disabled=false;"
		   ."document.$form_name.username.disabled=false;"
		   ."document.$form_name.password.disabled=false;"
		   ."document.$form_name.re_password.disabled=false;"
		   ."document.$form_name.db_name.value=\"mysql\";"
		   ."document.$form_name.db_table.value=\"user\";"
		   ."document.$form_name.db_field_user.value=\"User\";"
		   ."document.$form_name.db_field_password.value=\"Password\";"
		   ."document.$form_name.db_name.disabled=false;"
		   ."document.$form_name.db_table.disabled=false;"
		   ."document.$form_name.db_field_user.disabled=false;"
		   ."document.$form_name.db_field_password.disabled=false;"
		   ."document.$form_name.db_name.readOnly=true;"
		   ."document.$form_name.db_table.readOnly=true;"
		   ."document.$form_name.db_field_user.readOnly=true;"
		   ."document.$form_name.db_field_password.readOnly=true;"
		   ."document.$form_name.enctype.disabled=true;"
		   ."}"
		   
		   // Joomla
		   ."if(document.$form_name.type.value==\"Joomla15\"){"
		   ."document.$form_name.port.value=\"3306\";"
		   ."document.$form_name.host.disabled=false;"
		   ."document.$form_name.port.disabled=false;"
		   ."document.$form_name.username.disabled=false;"
		   ."document.$form_name.password.disabled=false;"
		   ."document.$form_name.re_password.disabled=false;"
		   ."document.$form_name.db_table.value=\"jos_users\";"
		   ."document.$form_name.db_field_user.value=\"username\";"
		   ."document.$form_name.db_field_password.value=\"password\";"
		   ."document.$form_name.db_name.disabled=false;"
		   ."document.$form_name.db_table.disabled=false;"
		   ."document.$form_name.db_field_user.disabled=false;"
		   ."document.$form_name.db_field_password.disabled=false;"
		   ."document.$form_name.db_field_user.readOnly=true;"
		   ."document.$form_name.db_field_password.readOnly=true;"
		   ."document.$form_name.enctype.disabled=true;"
		   ."}"
		   
		   // Oracle
		   ."if(document.$form_name.type.value==\"Oracle\"){"
		   ."document.$form_name.port.value=\"1521\";"
		   ."document.$form_name.host.disabled=false;"
		   ."document.$form_name.port.disabled=false;"
		   ."document.$form_name.username.disabled=false;"
		   ."document.$form_name.password.disabled=false;"
		   ."document.$form_name.re_password.disabled=false;"
		   ."document.$form_name.db_name.value=\"\";"
		   ."document.$form_name.db_table.value=\"\";"
		   ."document.$form_name.db_field_user.value=\"\";"
		   ."document.$form_name.db_field_password.value=\"\";"
		   ."document.$form_name.db_name.readOnly=false;"
		   ."document.$form_name.db_table.readOnly=false;"
		   ."document.$form_name.db_field_user.readOnly=false;"
		   ."document.$form_name.db_field_password.readOnly=false;"
		   ."document.$form_name.enctype.disabled=false;"
		   ."document.$form_name.db_name.disabled=false;"
		   ."document.$form_name.db_table.disabled=false;"
		   ."document.$form_name.db_field_user.disabled=false;"
		   ."document.$form_name.db_field_password.disabled=false;"
		   ."}"
		   
		   // Postgres
		   ."if(document.$form_name.type.value==\"Postgres\"){"
		   ."document.$form_name.port.value=\"5432\";"
		   ."document.$form_name.host.disabled=false;"
		   ."document.$form_name.port.disabled=false;"
		   ."document.$form_name.username.disabled=false;"
		   ."document.$form_name.password.disabled=false;"
		   ."document.$form_name.re_password.disabled=false;"
		   ."document.$form_name.db_name.value=\"\";"
		   ."document.$form_name.db_table.value=\"\";"
		   ."document.$form_name.db_field_user.value=\"\";"
		   ."document.$form_name.db_field_password.value=\"\";"
		   ."document.$form_name.db_name.readOnly=false;"
		   ."document.$form_name.db_table.readOnly=false;"
		   ."document.$form_name.db_field_user.readOnly=false;"
		   ."document.$form_name.db_field_password.readOnly=false;"
		   ."document.$form_name.enctype.disabled=false;"
		   ."document.$form_name.db_name.disabled=false;"
		   ."document.$form_name.db_table.disabled=false;"
		   ."document.$form_name.db_field_user.disabled=false;"
		   ."document.$form_name.db_field_password.disabled=false;"
		   ."}"
		   
		    // LDAP or ADAM
		   ."if(document.$form_name.type.value==\"LDAP\" || document.$form_name.type.value==\"LDAPS\" || document.$form_name.type.value==\"ADAM\"){"
		   ."document.$form_name.port.value=\"389\";"
		   ."document.$form_name.host.disabled=false;"
		   ."document.$form_name.port.disabled=false;"
		   ."document.$form_name.username.disabled=false;"
		   ."document.$form_name.password.disabled=false;"
		   ."document.$form_name.re_password.disabled=false;"
		   ."document.$form_name.db_name.value=\"\";"
		   ."document.$form_name.db_table.value=\"\";"
		   ."document.$form_name.db_field_user.value=\"\";"
		   ."document.$form_name.db_field_password.value=\"\";"
		   ."document.$form_name.db_name.readOnly=false;"
		   ."document.$form_name.db_table.readOnly=false;"
		   ."document.$form_name.db_field_user.readOnly=false;"
		   ."document.$form_name.db_field_password.readOnly=false;"
		   ."document.$form_name.enctype.disabled=false;"
		   ."}"
		   
		   // LDAPS
		   ."if(document.$form_name.type.value==\"LDAPS\"){"
		   ."document.$form_name.port.value=\"636\";"
		   ."}"
		   
		   // GMail
		   ."if(document.$form_name.type.value==\"GMail\"){"
		   ."document.$form_name.host.value=\"\";"
		   ."document.$form_name.port.value=\"\";"
		   ."document.$form_name.username.value=\"\";"
		   ."document.$form_name.password.value=\"\";"
		   ."document.$form_name.re_password.value=\"\";"
		   ."document.$form_name.host.disabled=true;"
		   ."document.$form_name.port.disabled=true;"
		   ."document.$form_name.username.disabled=true;"
		   ."document.$form_name.password.disabled=true;"
		   ."document.$form_name.re_password.disabled=true;"
		   ."document.$form_name.db_table.value=\"\";"
		   ."document.$form_name.db_field_user.value=\"\";"
		   ."document.$form_name.db_field_password.value=\"\";"
		   ."document.$form_name.db_name.disabled=true;"
		   ."document.$form_name.db_table.disabled=true;"
		   ."document.$form_name.db_field_user.disabled=true;"
		   ."document.$form_name.db_field_password.disabled=true;"
		   ."document.$form_name.enctype.disabled=true;"
		   ."}"
		   
		   ."'>\n"
	       ." <option value='".$default."'>".$language[$default]."</option>\n";
				   
	$num_availables = count($availables);
		
	for($l=0; $l<$num_availables; $l++) 
	{		
		if($availables[$l] != $default) 
		{
			// ...because SUMO Auth is embedded!
			if($availables[$l] != 'SUMO' && $availables[$l] != 'Unix') 
			{			
				$list .= " <option value='".$availables[$l]."'>\n"
					    .$language[$availables[$l]]
					    ."</option>\n";
			}
		}
	}
		
	$list .= "</select>";
		
	return $list;
}


/**
 * Return supported encryption algorithms
 */
function sumo_get_datasource_enctype($datasource_type='')
{	
	$ds = sumo_get_available_datasources(true);
	
	switch ($datasource_type) 
	{
		// for databases based ds
		case in_array($datasource_type, $ds): 
			return array('md5', 'crypt', 'sha1', 'crc32');
			break;
			
		case in_array($datasource_type, array('LDAP', 'LDAPS', 'ADAM')):
			return array('md5', 'crc32');
			break;
	}
}


/**
 * Verify if datasource exist
 *
 * @global resource $SUMO
 * @return boolean
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_datasource_exist($field='name', $value='') 
{	
	GLOBAL $SUMO;
	
	$field = $field == 'name' ? 'name' : 'id';
	
	$query = "SELECT id FROM ".SUMO_TABLE_DATASOURCES." 
			  WHERE ".$field."='".$value."'";

	$rs = $SUMO['DB']->Execute($query);
		
	$datasource = $rs->PO_RecordCount(SUMO_TABLE_DATASOURCES, $field."='".$value."'");
	
	return ($datasource != 0) ? true : false;
}

?>