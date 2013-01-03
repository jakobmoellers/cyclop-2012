<?php
/**
 * SUMO LIBRARY: Accesspoints
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
 * Update accesspoint group
 * 
 * @author Alberto Basso
 */
function sumo_update_accesspoint_group($id=0, $group=FALSE)
{
	$id = intval($id);	
	
	if($id > 0 && sumo_validate_group($group, FALSE) && sumo_verify_permissions(5, $group))
	{		
		GLOBAL $SUMO;
						
		$query1 = "SELECT usergroup FROM ".SUMO_TABLE_ACCESSPOINTS." 
				   WHERE id=".$id;
		
		$rs  	= $SUMO['DB']->Execute($query1);
		$tab 	= $rs->FetchRow();
		
		$new_group = sumo_get_normalized_group(str_replace($group, '', $tab[0]), TRUE);
		
		$query2 = "UPDATE ".SUMO_TABLE_ACCESSPOINTS." 
				   SET usergroup='".$new_group."' 
				   WHERE id=".$id;
		
		$SUMO['DB']->CacheFlush();
		$SUMO['DB']->Execute($query1);
		$SUMO['DB']->Execute($query2);
		
		sumo_write_log('I07001X', array($id, $group, $new_group, $SUMO['user']['user']), '0,1', 3, 'system', FALSE);
		
		return TRUE;		
	}
	else return FALSE;
}


/**
 * Add group and "registration group" to accesspoint
 * 
 * @author Alberto Basso
 */
function sumo_add_accesspoint_group($groups_exist=array(), $name='', $enabled=TRUE)
{		
	GLOBAL $SUMO;
	
	$group_exist  = is_array($groups_exist)  ? '' : $groups_exist;
	$groups_exist = !is_array($groups_exist) ? array($groups_exist) : $groups_exist;	
	$group_name   = sumo_get_grouplevel(sumo_get_user_available_group($SUMO['user']['user']), TRUE);
	$name		  = $name    ? $name : 'newgroup';
	$disabled	  = $enabled ? ''    : ' disabled';
	$available 	  = FALSE;
	
	$list = "<select name='".$name."'".$disabled.">\n"
		   ."<option value='".$group_exist."'>".$group_exist."</option>\n";
	
	for($g=0; $g<count($group_name); $g++) 
	{	
		if($group_name[$g] == 'sumo') 
		{			
			$available_group = sumo_get_available_group();
						
			//$list = "<select name='".$name."'>\n<option></option>\n";			
			//if(!in_array('sumo', $group_exist)) $list .= "<option value='sumo' style='color:#BB0000'>sumo</option>\n";
			
			for($g=0; $g<count($available_group); $g++) 
			{										
				//if(!in_array($available_group[$g], $group_exist) && $available_group[$g] != 'sumo') 
				if(!in_array($available_group[$g], $groups_exist)) 
				{					
					$style = $available_group[$g]=='sumo' ? " style='color:#BB0000'" : "";					
					$list .= "<option value='".$available_group[$g]."'$style>".$available_group[$g]."</option>\n";
				}
			}
			
			$available = TRUE;
			
			break;
		}
		else 
		{
			if(!in_array($group_name[$g], $group_exist)) 
			{
				$list 	  .= "<option value='".$group_name[$g]."'>".$group_name[$g]."</option>\n";
				$available = TRUE;
			}
		}			
	}
	
	$list .= "</select>";
	
	if(!$available) $list = '';
	
	return $list;
}


/**
 * Validate data accesspoint
 * See also sumo_validate_data() in libs/lib.core.php
 * 
 * @author Alberto Basso
 */
function sumo_validate_accesspoint_data($data=array(), $message=FALSE)
{	
	$elements = count($data);
	$err 	  = FALSE;
	
	if($elements > 0) 
	{		
		for($d=0; $d<$elements; $d++) 
		{	              			
			if($data[$d][2] == 1 || ($data[$d][2] == 0 && $data[$d][1])) 
			{									
				switch($data[$d][0]) 
				{	
					case 'id':
						// INT = 256^4-1
						if($data[$d][1] < 1 || $data[$d][1] > 4294967296) $err = 'W00029C';
						break;
					
					case 'path':						
						if(!preg_match("/^(\/)+[_\.\/a-z0-9-]{1,}(\.){1}(php|php4|php5|html|htm|asp|pl|jsp){1}$/i", $data[$d][1])) $err = 'W07003C';
						break;
						
					case 'node':					
						if($data[$d][1] < 1 || $data[$d][1] > 4294967296) $err = 'W07004C';
						break;
						
					case 'name':	
						$languages = sumo_get_available_languages();
						
						for($l=0; $l<count($languages); $l++)
						{
							if(!preg_match("/^[-a-z0-9_\.\=\&\/\'".SUMO_REGEXP_ALLOWED_CHARS." ]{5,128}$/i", $data[$d][1][$languages[$l]])) $err = 'W00031C';
						}
						
						break;
						
					case 'usergroup':						
						if(!sumo_validate_group($data[$d][1], FALSE)) $err = 'W07002C';
						break;
						
					case 'reg_group':						
						if(!sumo_validate_group($data[$d][1], FALSE)) $err = 'W07005C';
						break;
											
					case 'boolean':
						if($data[$d][1] != 0 && $data[$d][1] != 1) $err = 'W00032C';
						break;	
                    					
					case 'theme':					
						if(!in_array($data[$d][1], sumo_get_available_themes())) $err = 'W00033C';
						break;		
						
					default:
						$err = 'W00019C';
						break;						
				}
				
				if($err) break;
			}
		}		
		
		if($message) 
		{
			return (!$err) ? array(TRUE, '') : array(FALSE, sumo_get_message($err));
		}
		else 
		{
			return (!$err) ? TRUE : FALSE;
		}		
	}
	else return FALSE;
}


/**
 * Delete accesspoint
 * 
 * @author Alberto Basso
 */
function sumo_delete_accesspoint($id=0)
{	
	$id = intval($id);
	
	if($id > 1) 
	{		
		GLOBAL $SUMO;		
		
		$accesspoint = sumo_get_accesspoint_info($id, 'id', FALSE);
		$node		 = sumo_get_node_info($accesspoint['node']);
		
		$query1 = "DELETE FROM ".SUMO_TABLE_ACCESSPOINTS." WHERE id=".$id;
		$query2 = "DELETE FROM ".SUMO_TABLE_ACCESSPOINTS_STATS." WHERE id_page=".$id;
		$query3 = "SELECT COUNT(id) FROM ".SUMO_TABLE_ACCESSPOINTS." WHERE id=".$id;

		$SUMO['DB']->Execute($query1);
		$SUMO['DB']->Execute($query2);
		
		// if deleted					
		$rs  = $SUMO['DB']->Execute($query3);
		$tab = $rs->FetchRow();
				
		if($tab[0] == 0) 
		{			
			$SUMO['DB']->CacheFlush();
			
			sumo_write_log('I07004X', array(sumo_get_accesspoint_name($accesspoint['name'], $SUMO['config']['server']['language']), 
										 	$node['name'], 
										 	$accesspoint['path'], 
										 	$SUMO['user']['user']), 3, 3, 'system', FALSE);				
			return TRUE;
		}
		else 
			return FALSE;		
	}
	else return FALSE;
}


/**
 * Put accesspoint group (in Html format)
 * 
 * @author Alberto Basso
 */
function sumo_put_accesspoint_group($id=FALSE)
{	
	if($id > 0) 
	{	
		$accesspoint = sumo_get_accesspoint_info($id, 'id', FALSE);
					
		if(!empty($accesspoint['usergroup'])) 
		{			
			GLOBAL $SUMO, $language;
			
			$list = "<table cellspacing='0' class='tab'>\n"
				   ." <tr>\n"
				   ."  <td class='tab-title'>".$language['Name']."</td>\n"
				   ."  <td class='tab-title'>".$language['Description']."</td>\n"
				   ."  <td class='tab-title'>&nbsp;</td>\n"
				   ." </tr>\n";
							
			for($g=0; $g<count($accesspoint['usergroup']); $g++) 
			{							
				if($accesspoint['usergroup'][$g]) 
				{					
					$style 		= sumo_alternate_str('tab-row-on', 'tab-row-off');
					$ap_name	= $accesspoint['usergroup'][$g] == 'sumo' ? "<font color='#BB0000'><b>sumo</b></font>" : $accesspoint['usergroup'][$g];
					$group_name = "<input type='hidden' size='25' name='group[".$g."]' value='".$accesspoint['usergroup'][$g]."'>".$ap_name;
	
					// Create link to remove group				
					$delete = "<a href='javascript:sumo_ajax_get(\"".$_SESSION['module']."\",\""
                             ."?module=accesspoints&action=deletegroup&group=".$accesspoint['usergroup'][$g]."&id=".intval($id)
                             ."&SecurityOptions_visibility=1\");'>"
                             .$language['Remove']."</a>";
														
					$list .= "<tr>\n"							
							." <td class='".$style."'>".$group_name."</td>\n"
							." <td class='".$style."'>".sumo_get_group_description($accesspoint['usergroup'][$g])."</td>\n"
							." <td class='".$style."'>".$delete."</td>\n"			
							."</tr>\n";
				}					
			}
			
			$list .= "</table>";
					
			return $list;
		}
	}
	else return FALSE;
}


/**
 * Put node (in Html format)
 * 
 * @author Alberto Basso
 */
function sumo_put_node($id=0, $disabled=false)
{			
	$node = sumo_get_node_info();	
	$max  = array_keys($node);
	$n = 'node';
	$d = $h = '';
		
	if(!empty($node))
	{
		if($disabled)
		{
			$n = 'node2';
			$d = ' disabled';
		}
		
		$list = "<select name='$n'$d>\n";
			
		for($n=0; $n<=max($max); $n++) 
		{
			$selected = $n==$id || (!$id && $n == 1) ? ' selected' : '';
			
			if($node[$n]['name'])
			{
				$list .= "<option value='".$n."'".$selected.">"
					.$node[$n]['name']." [".$node[$n]['host']."]"
					."</option>\n";
					
				if($disabled && $selected) $h = "<input type='hidden' name='node' value='".$n."'>";	
			}
		}			
				
		$list .= "</select>$h";
	}
	else 
	{
		$list = "&nbsp;";
	}
					
	return $list;
}


/**
 * Verify if an accesspoint exist
 * 
 * @return boolean
 * @author Alberto Basso
 */
function sumo_verify_accesspoint_exist($node=false, $path='') 
{	
	GLOBAL $SUMO;
		
	$query = "SELECT COUNT(id) FROM ".SUMO_TABLE_ACCESSPOINTS." 
			  WHERE path='".$path."' 
			  AND node=".intval($node);
						
	$rs  = $SUMO['DB']->Execute($query);
	$tab = $rs->FetchRow();
			
	return ($tab[0] == 0) ? FALSE : TRUE;
}


/**
 * Add accesspoint
 */
function sumo_add_accesspoint($data=array())
{	
	if(!empty($data)) 
	{	
		GLOBAL $SUMO;
				
		$path	      = sumo_get_normalized_accesspoint($data['path']);
		$node		  = $data['node'] ? $data['node'] : "NULL";
		$group	   	  = sumo_get_ordered_groups($data['usergroup']);
		$reg_group 	  = $data['reg_group'] ? $data['reg_group'] : $SUMO['config']['accesspoints']['def_group'];
		$theme	   	  = $data['theme'];		
		$http_auth    = ($data['http_auth']    == 'on' || $data['http_auth']    == 1) ? 1 : 0;
		$filtering    = ($data['filtering']    == 'on' || $data['filtering']    == 1) ? 1 : 0;
		$pwd_encrypt  = ($data['pwd_encrypt']  == 'on' || $data['pwd_encrypt']  == 1) ? 1 : 0;
		$change_pwd	  = ($data['change_pwd']   == 'on' || $data['change_pwd']   == 1) ? 1 : 0;
		$registration = ($data['registration'] == 'on' || $data['registration'] == 1) ? 1 : 0;
			
		//
		$filtering = ($data['filtering'] == 'false' && sumo_verify_is_console($path)) ? 1 : $filtering;
		
		// AP names
		$languages = sumo_get_available_languages();
		$names = "";
		
		for($l=0; $l<count($languages); $l++)
		{
			$names[$l] = $languages[$l].":".$data['name'][$languages[$l]];
		}
		
		$name = implode(";", $names);
		
			
		$query = "INSERT INTO ".SUMO_TABLE_ACCESSPOINTS." 
				  (
					node, path, name,usergroup, http_auth, filtering, pwd_encrypt,
				  	reg_group, change_pwd, registration, theme, created
				  ) 
				  VALUES (
				  	".$node.", '".$path."', '".$name."', '".$group."', ".$http_auth.", ".$filtering.", 
				  	".$pwd_encrypt.", '".$reg_group."', ".$change_pwd.", ".$registration.", '".$theme."', 
				  	".$SUMO['server']['time']."
				  )";
								
		$SUMO['DB']->Execute($query);
		
		// Create row stats for accesspoint
		$accesspoint = sumo_get_accesspoint_info($SUMO['server']['time'], 'created', FALSE);
				
		$query = "INSERT INTO ".SUMO_TABLE_ACCESSPOINTS_STATS." 
				  (
				  	node, id_page, access, activity, updated
				  ) 
				  VALUES (
				  	".$node.", ".$accesspoint['id'].", 0, 0, ".$SUMO['server']['time']."
				  )";
				 				 
		$SUMO['DB']->Execute($query);
					
		// if accesspoint was added
		if(sumo_verify_accesspoint_exist($node, $path)) 
		{			
			sumo_write_log('I07003X', 
						   array(sumo_get_accesspoint_name($name, $SUMO['config']['server']['language']), 
						   $node, 
						   $path, 
						   $SUMO['user']['user']), 
						   3, 3, 
						   'system', FALSE);
			
			$SUMO['DB']->CacheFlush();
			
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
 * Update accesspoint data
 */
function sumo_update_accesspoint_data($data=array())
{		
	if(!empty($data)) 
	{	
		GLOBAL $SUMO;
				
		$id	   	      = intval($data['id']);
		$node  	      = $data['node'] ? intval($data['node']) : "NULL";
		$path	      = $data['path'];
		$group	   	  = $data['group'];
		$reg_group 	  = $data['reg_group'];
		$theme	   	  = $data['theme'];
		$http_auth    = ($data['http_auth'] == 'on'    || $data['http_auth'] == 1)    ? 1 : 0;
		$filtering    = ($data['filtering'] == 'on'    || $data['filtering'] == 1)    ? 1 : 0;
		$pwd_encrypt  = ($data['pwd_encrypt'] == 'on'  || $data['pwd_encrypt'] == 1)  ? 1 : 0;
		$change_pwd	  = ($data['change_pwd'] == 'on'   || $data['change_pwd'] == 1)   ? 1 : 0;
		$registration = ($data['registration'] == 'on' || $data['registration'] == 1) ? 1 : 0;
					
		// AP names
		$languages = sumo_get_available_languages();
		$names = "";
		
		for($l=0; $l<count($languages); $l++)
		{
			$names[$l] = $languages[$l].":".$data['name'][$languages[$l]];
		}
		
		$name = implode(";", $names);
		
		
		$filtering = sumo_verify_is_console($path) ? 1 : $filtering;
		
		/**
		 * Kill all sessions at path where pwd_encrypt 
		 * or http_auth it has been changed
		 */
		$accesspoint = sumo_get_accesspoint_info($id, 'id', FALSE);
		$nodeinfo	 = sumo_get_node_info($node);
		
		if($accesspoint['pwd_encrypt'] != $pwd_encrypt || $accesspoint['http_auth'] != $http_auth) 
		{			
			$query = "DELETE FROM ".SUMO_TABLE_SESSIONS." 
					  WHERE node='".$nodeinfo['ip']."' AND url LIKE '%".$path."'";
						
			$SUMO['DB']->Execute($query);	
		}
								
		// Delete cached data			
		#if($path) $SUMO['DB']->CacheFlush("SELECT * FROM ".SUMO_TABLE_ACCESSPOINTS." 
		#								   WHERE path='".$path."'");		             		
                      
		if($node >= 1) $record['node']      = "node=".$node;
		if($path)      $record['path']      = "path='".$path."'";
		if($name)      $record['name']      = "name='".$name."'";
		if($group)     $record['group']     = "usergroup='".sumo_get_ordered_groups($group)."'";
		if($reg_group) $record['reg_group'] = "reg_group='".$reg_group."'";
		if($theme)     $record['theme']     = "theme='".$theme."'";
		
		$record['http_auth']    = "http_auth=".$http_auth;
		$record['filtering']    = "filtering=".$filtering;
		$record['pwd_encrypt']  = "pwd_encrypt=".$pwd_encrypt;
		$record['change_pwd']   = "change_pwd=".$change_pwd;
		$record['registration'] = "registration=".$registration;
		$record['updated'] 		= "updated=".$SUMO['server']['time'];
		
		// Create fields for query
		$new_record = array_values($record);
		
		for($r=0; $r<count($new_record); $r++) 
		{
			if($new_record[$r]) $records[$r] = $new_record[$r];
		}
				
		$update = implode(', ', $records);
		$select = implode(' AND ', $records);
				
		// create query
		$query = "UPDATE ".SUMO_TABLE_ACCESSPOINTS." 
				  SET ".$update." 
				  WHERE id=".$id;
		
		$SUMO['DB']->CacheFlush();
		$SUMO['DB']->Execute($query);	
		
		// verify query success
		$query = "SELECT COUNT(id) FROM ".SUMO_TABLE_ACCESSPOINTS." 
				  WHERE id=".$id." 
				  AND ".$select;
								
		$rs  = $SUMO['DB']->Execute($query);
		$tab = $rs->FetchRow();
		
		// if updated:
		if($tab[0] == 1) 
		{            
			if($nodeinfo['ip'] == '') $nodeinfo['ip'] = 'UNDEFINED';
			
			$apname = sumo_get_accesspoint_name($name, $SUMO['config']['server']['language']);
			
			sumo_write_log('I07000X', 
							array($id, $apname, $nodeinfo['ip'], $SUMO['user']['user']), 
							3, 3, 
							'system', FALSE);
					
			return TRUE;
		}		
		else 
		{
			return FALSE; 
		}
	}
}

?>