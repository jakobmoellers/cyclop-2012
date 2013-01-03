<?php
/**
 * SUMO LIBRARY: Groups
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
 * Validate group name
 */
function sumo_validate_group_name($name='')
{	
	return (preg_match("/^[[:alpha:]\/\-\_".SUMO_REGEXP_ALLOWED_CHARS."]{2,50}$/i", $name)) ? true : false; 
}


/**
 * Validate group description
 */
function sumo_validate_group_desc($desc='')
{	
	return (preg_match("/^[[:alpha:]\.\ \/\-\_".SUMO_REGEXP_ALLOWED_CHARS."]{3,255}$/i", $desc)) ? true : false;
}


/**
 * Verify if a group exist into SUMO_TABLE_GROUPS
 * 
 * @return boolean
 * @author Alberto Basso
 */
function sumo_verify_group_exist($group='') 
{	
	GLOBAL $SUMO;
		
	if(!$group || strlen($group) > 50) 
	{
		//
	}	
	else 
	{			
		$query = "SELECT usergroup FROM ".SUMO_TABLE_GROUPS." 
				  WHERE usergroup='".$group."'";
						
		$rs = $SUMO['DB']->Execute($query);
									
		if($rs->PO_RecordCount(SUMO_TABLE_GROUPS, "usergroup='".$group."'") == 0) 
			return FALSE;
		else
			return TRUE;
	}
}


/**
 * Add group
 */
function sumo_add_group($data=array())
{	
	if(!empty($data)) 
	{	
		GLOBAL $SUMO;
		
		$groupname = trim(strtolower($data['usergroup']));
		$groupdesc = trim($data['groupdesc']);
					
		$query = "INSERT INTO ".SUMO_TABLE_GROUPS." 
				  (
				  	usergroup,description,created
				  ) 
				  VALUES (
				  	'".$groupname."', '".$groupdesc."', ".$SUMO['server']['time']."
				  )";
				
		$SUMO['DB']->Execute($query);
		
		// if group added
		if(sumo_verify_group_exist($groupname)) 
		{					
			sumo_write_log('I02000X', array($groupname, $SUMO['user']['user']), 3, 3, 'system', FALSE);
						
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
 * Update group data
 */
function sumo_update_group_data($data=array())
{	
	if(!empty($data)) 
	{	
		GLOBAL $SUMO;
		
		$id	   	   = intval($data['id']);
		$groupname = trim(strtolower($data['usergroup']));
		$groupdesc = trim(preg_replace('/[\s\,]+/', ' ', $data['groupdesc']));
						
		// Delete cache data of user
		if($groupname) $SUMO['DB']->CacheFlush("SELECT * FROM ".SUMO_TABLE_GROUPS."
												WHERE usergroup='".$groupname."'");
											
		// create query
		$query1 = "UPDATE ".SUMO_TABLE_GROUPS." 
				   SET usergroup='".$groupname."', 
				   description='".$groupdesc."', 
				   updated=".$SUMO['server']['time']." 
				   WHERE id=".$id;
				 								
		// verify query success
		$query2 = "SELECT * FROM ".SUMO_TABLE_GROUPS." 
				   WHERE usergroup='".$groupname."' 
				   AND description='".$groupdesc."' 
				   AND updated=".$SUMO['server']['time']." 
				   AND id=".$id;
			   
			   $SUMO['DB']->Execute($query1);				
		$rs  = $SUMO['DB']->Execute($query2);
		$tab = $rs->FetchRow();
		
		// if updated:
		if($rs->PO_RecordCount(SUMO_TABLE_GROUPS, 
				   "usergroup='".$groupname."' 
				   AND description='".$groupdesc."' 
				   AND updated=".$SUMO['server']['time']." 
				   AND id=".$id) == 1) 
		{												
			sumo_write_log('I02001X', array($tab['usergroup'], $SUMO['user']['user']), 3, 3, 'system', FALSE);
						
			sumo_update_users_group($data['oldgroup'], $groupname);
			
			return TRUE;
		}		
		else {
			return FALSE; 
		}
	}
}


/**
 * Update group name to all users
 * Note: function written for performance reason
 *       see also sumo_update_user_group on users module
 */
function sumo_update_users_group($oldgroup='', $newgroup='')
{
	GLOBAL $SUMO;

	$query1 = "SELECT id,usergroup FROM ".SUMO_TABLE_USERS."
			   WHERE usergroup LIKE '".$oldgroup.":%'";
	
	$query2 = "SELECT id,usergroup FROM ".SUMO_TABLE_USERS."
			   WHERE usergroup LIKE '%;".$oldgroup.":%'";
	
	$updated = 0;
	
	$rs = $SUMO['DB']->Execute($query1);
	
	while($tab = $rs->FetchRow()) 
	{	
		$group  = str_replace($oldgroup.":", $newgroup.":", $tab['usergroup']);
		
		$update = "UPDATE ".SUMO_TABLE_USERS." 
				   SET usergroup='".$group."',
				   updated=".$SUMO['server']['time']." 
				   WHERE id=".$tab['id'];
		
		$rs1 = $SUMO['DB']->Execute($update);
		
		$updated++;
	}
	
	$rs = $SUMO['DB']->Execute($query2);
	
	while($tab = $rs->FetchRow()) 
	{	
		$group  = str_replace(";".$oldgroup.":", ";".$newgroup.":", $tab['usergroup']);
		
		$update = "UPDATE ".SUMO_TABLE_USERS." 
				   SET usergroup='".$group."',
				   updated=".$SUMO['server']['time']." 
				   WHERE id=".$tab['id'];
		
		$rs2 = $SUMO['DB']->Execute($update);
		
		$updated++;
	}
	
	sumo_write_log('I02002X', array($oldgroup, $newgroup, $updated), '0,1', 3, 'system', false);
}


/**
 * Delete group
 */
function sumo_delete_group($id=0)
{	
	$id = intval($id);
	
	if($id > 0) 
	{		
		GLOBAL $SUMO;
						
		$group = sumo_get_group_info($id);
		
		$SUMO['DB']->CacheFlush();
		
		$query = "DELETE FROM ".SUMO_TABLE_GROUPS." 
				  WHERE id=".$id;
				
		$SUMO['DB']->Execute($query);		
		
		sumo_write_log('I02003X', array($group['usergroup'], $SUMO['user']['user']), '0,1', 2, 'system', false);
	}
}


/**
 * Get num users for group
 */
function sumo_get_group_users($id)
{
	$id = intval($id);
	
	if($id > 0) 
	{		
		GLOBAL $SUMO;
		
		$tab = sumo_get_group_info($id);
		
		$query = "SELECT COUNT(id) FROM ".SUMO_TABLE_USERS."
	        	  WHERE (
	        	  	usergroup LIKE '".$tab['usergroup'].":%' 
				  	OR usergroup LIKE '%;".$tab['usergroup'].":%'
				  	OR usergroup LIKE 'sumo:%'
				  	OR usergroup LIKE '%;sumo:%'
				  	)
				  	AND username<>'sumo'";
        	
        $rs = $SUMO['DB']->CacheExecute(30, $query);
        	
        $users = $rs->FetchRow();
        
        return $users[0];
	}
	else return false;
}

?>
