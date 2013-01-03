<?php
/**
 * SUMO LIBRARY: Users
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
 * Get user image
 */
function sumo_get_user_image($id=FALSE)
{
	$image = '';
	$id    = intval($id);

	if($id > 0)
	{
		GLOBAL $SUMO;

		$query = "SELECT * FROM ".SUMO_TABLE_USERS_IMAGES."
				  WHERE id_user=".$id;

		$rs  = $SUMO['DB']->Execute($query);
		$tab = $rs->FetchRow();

		$image = $tab['image'];
		$type  = $tab['type'];
	}

	if(!$image)
	{
		$image = SUMO_PATH."/themes/".$SUMO['page']['theme']."/images/modules/users/anonymous.gif";

		if(@file_exists($image) && @is_readable($image))
		{
			$image = @file_get_contents($image);
			$type = 'image/gif';
		}
	}

	return array($image, $type);
}


/**
 * Put data source for user
 */
function sumo_put_datasource($id='')
{
	GLOBAL $SUMO, $language;

	$list = "<select name='datasource_id' "
		   ."onchange='if(this.value>1){if(document.forms[0].new_password!=null){document.forms[0].new_password.disabled=true;document.forms[0].renew_password.disabled=true;}}"
		   ."else{if(document.forms[0].new_password!=null){document.forms[0].new_password.disabled=false;document.forms[0].renew_password.disabled=false;}}"
		   ."'>\n";

	$query = "SELECT * FROM ".SUMO_TABLE_DATASOURCES."
			  ORDER BY name";

	$rs  = $SUMO['DB']->Execute($query);

	while($tab = $rs->FetchRow())
	{
		$selected = ($tab['id'] == $id || $id == "") ? ' selected' : '';

		$list .= " <option value='".$tab['id']."'$selected>"
				.$tab['name']
				."</option>\n";
	}

	$selected = $id === 0 ? ' selected' : '';

	if($_SERVER["USER"] == 'root') $list .= " <option value='0'$selected>".$language['Unix']."</option>\n";

	$list .= "</select>";

	return $list;
}


/**
 * Put user group and relative access_level
 */
function sumo_put_user_grouplevel($id=FALSE)
{
	$user	     = sumo_get_user_info($id, 'id', FALSE);
	$group_level = $user['group_level'];

	if(!empty($group_level))
	{
		GLOBAL $SUMO, $language;

		$num_groups = count($group_level);
		$group 		= array_keys($group_level);
		$value		= array_values($group_level);
		$list 		= '';

		for($g=0; $g<$num_groups; $g++)
		{
			if($group[$g])
			{
				$SUMO['user']['group_level'][$group[$g]] = !isset($SUMO['user']['group_level'][$group[$g]]) ? '' : $SUMO['user']['group_level'][$group[$g]];

				$style 			= sumo_alternate_str('tab-row-on', 'tab-row-off');
				$val 			= "<select name='group_level[$g]'>\n<option value='".$value[$g]."'>".$value[$g]."</option>\n";
				$last_value		= !isset($SUMO['user']['group_level'][$group[$g]]) ? 7 : $SUMO['user']['group_level'][$group[$g]];
				$last_value     = (in_array('sumo', $SUMO['user']['group']) && $group[$g] != 'sumo') ? 7 : $last_value;
				$group_name[$g] = "<input type='hidden' name='group_name[$g]' value='".$group[$g]."'>".$group[$g];

				// Create link to remove group
				if($SUMO['user']['group_level'][$group[$g]] > $value[$g] || $SUMO['user']['group_level']['sumo'] >= 4)
					$delete = "<a href='javascript:sumo_ajax_get(\"".$_SESSION['module'].".content\",\"?module=users&action=deletegroup&group=".$group[$g].":".$value[$g]."&id=".intval($id)."&decoration=false&SecurityOptions_visibility=1\");'>".$language['Remove']."</a>";
				else
					$delete = '';


				if($SUMO['user']['group_level'][$group[$g]] > $value[$g] || in_array('sumo', $SUMO['user']['group']))
				{
					for($l=1; $l<=$last_value; $l++)
					{
						if($l != $value[$g]) $val .= "<option value='$l'>$l</option>\n";
					}
				}

				$val .= "</select>";

				// Only for SUMO user (administrator)
				if($user['user'] == 'sumo')
				{
					$val    = 7;
					$delete = '';
				}


				$list .= "<tr>\n"
						." <td class='".$style."'>".$group_name[$g]."</td>\n"
						." <td class='".$style."'>".sumo_get_group_description($group[$g])."</td>\n"
						." <td class='".$style."'>".$val."</td>\n"
						." <td class='".$style."'>".$delete."</td>\n"
						."</tr>\n";
			}
		}

		return $list;
	}
	else return FALSE;
}


/**
 * Get list of pages that user can access
 */
function sumo_get_user_accesspoints($id=NULL, $html=FALSE)
{
	if($id)
	{
		GLOBAL $SUMO, $language;

		$user_data   = sumo_get_user_info($id, 'id', FALSE);
		$num_groups  = count($user_data['group']);
		$group_query = '';

		if(!in_array('sumo', $user_data['group']))
		{
			$group_query = " WHERE ";

			for($g=0; $g<$num_groups; $g++)
			{
				$group_query .= "usergroup='".$user_data['group'][$g]."' OR
							     usergroup LIKE '".$user_data['group'][$g].";%' OR
							     usergroup LIKE '%;".$user_data['group'][$g].";%'";

				if($g < $num_groups-1) $group_query .= " OR ";
			}
		}

		$query = "SELECT * FROM ".SUMO_TABLE_ACCESSPOINTS."
				 ".$group_query."
				  ORDER BY name";

		$rs = $SUMO['DB']->Execute($query);

		$ap = array();

		while($tab = $rs->FetchRow())
		{
			$ap[] = $tab;
		}

		// html output
		if($html)
		{
			if(in_array('sumo', $user_data['group'])) return $language['AllAccessPoints'];

			$list 	= '';
			$num_ap = count($ap);

			if($num_ap > 0)
			{
				$list  = "<table cellspacing='0' class='tab'>\n"
						." <tr>\n"
						."  <td class='tab-title'>".$language['Page']."</td>\n"
						."  <td class='tab-title'>".$language['Path']."</td>\n"
						//."  <td class='tab-title'>".$language['Group']."</td>\n"
						." </tr>\n";

				for($p=0; $p<$num_ap; $p++)
				{
					$style = sumo_alternate_str('tab-row-on', 'tab-row-off');

					// Format group string to display it
					$group = preg_replace("/sumo:7/", "<b><font color='#BB0000'>sumo:7</font></b>", $ap[$p]['usergroup']);
					$group = preg_replace("/sumo:/", "<font color='#BB0000'>sumo</font>:", $group);
					$group = str_replace(';', '; ', $group);
					$group = strlen(strip_tags($group)) > 50 ? substr($group, 0, 50).'...' : $group;

					// Format path string to display it
					$path = strlen($ap[$p]['path']) > 50 ? substr($ap[$p]['path'], 0, 50).'...' : $ap[$p]['path'];
					$path = "<a href='".$ap[$p]['path']."' target='_blank'>".$path."</a>";
					$name = sumo_get_accesspoint_name($ap[$p]['name'], $_COOKIE['language']);

					$list .= "<tr>\n"
							." <td class='".$style."'>".$name."</td>\n"
							." <td class='".$style."'>".$path."</td>\n"
							//." <td class='".$style."'>".$group."</td>\n"
							."</tr>\n";
				}

				$list .= "</table>";
			}

			$ap = $list;
		}

		return $ap;
	}
	else return FALSE;
}


/**
 * Verify if password was updated
 */
function sumo_update_password_date($id=FALSE, $new_pwd=FALSE)
{
	if($id)
	{
		GLOBAL $SUMO;

		$query = "SELECT password FROM ".SUMO_TABLE_USERS."
				  WHERE id=".$id;

		$rs  = $SUMO['DB']->Execute($query);
		$tab = $rs->FetchRow();

		// update password date
		if($tab[0] != $new_pwd && $new_pwd)
		{
			$query = "UPDATE ".SUMO_TABLE_USERS."
					  SET pwd_updated=".$SUMO['server']['time']."
                      WHERE id=".$id;

			$SUMO['DB']->Execute($query);
		}
	}
	else return FALSE;
}


/**
 * Add user
 */
function sumo_add_user($data=array())
{
	if(!empty($data))
	{
		GLOBAL $SUMO;

		$user	   = strtolower($data['username']);
		$datasource_id = intval($data['datasource_id']);
		$active	   = (isset($data['active'])) ? intval($data['active']) : FALSE;
		$firstname = ucwords(preg_replace('/[\s\,]+/', ' ', $data['firstname']));
		$lastname  = ucwords(preg_replace('/[\s\,]+/', ' ', $data['lastname']));
		$firstname = get_magic_quotes_gpc() ? $firstname : addslashes($firstname);
		$lastname  = get_magic_quotes_gpc() ? $lastname  : addslashes($lastname);
		$ip    	   = str_replace(";;", ";", str_replace(",", ";", preg_replace('/[\s\,]+/', ';', $data['ip'])));
		$email 	   = strtolower($data['email']);
		$password  = $data['password'];
		$language  = $data['language'];
		$sumogroup = sumo_verify_sumogroup($data['group']);
		$group	   = $sumogroup ? $sumogroup : $data['group'];
		$group     = sumo_get_normalized_group($group);
		$day_limit = intval($data['day_limit']);
		$day_limit = $day_limit > 0 ? $day_limit : 'NULL';

		$query = "INSERT INTO ".SUMO_TABLE_USERS."
			  (
			  	username,firstname,lastname,password,active,ip,usergroup,datasource_id,
			  	last_login,day_limit,language,email,pwd_updated,created,owner_id,modified,updated
			  )
			  VALUES (
		  		'".$user."', '".$firstname."', '".$lastname."', '".$password."', '".$active."',
		  		'".$ip."', '".$group."', '".$datasource_id."', NULL , ".$day_limit.",
		  		'".$language."', '".$email."', NULL , ".$SUMO['server']['time'].",
		   		".$SUMO['user']['id'].", NULL, NULL
			  )";

		$SUMO['DB']->Execute($query);

		// if user was added
		if(sumo_verify_user_exist($user))
		{
			sumo_write_log('I01001X', array($user, $SUMO['user']['user']), 3, 3, 'system', FALSE);

			// Send user notify
			if($SUMO['config']['accounts']['notify']['updates'] && $email)
			{
				$object  = sumo_get_message("I00001M", $SUMO['server']['name']);
				$message = sumo_get_message("I00107M", array($firstname." ".$lastname,
										$SUMO['server']['name'],
										$SUMO['user']['user']));
				
				if(!$SUMO['config']['server']['admin']['email'])
				{
					sumo_write_log('E06000X', '', '0,1', 2, 'system', FALSE);
				}
				else {
					$m = new Mail;
					$m->From($SUMO['config']['server']['admin']['email']);
					$m->To($email);
					$m->Subject($object);
					$m->Body($message, SUMO_CHARSET);
					$m->Priority(3);
					$m->Send();
				}
			}

			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	else return FALSE;
}


/**
 * Combo box to add group and relative level to user
 */
function sumo_add_user_grouplevel($form_name='', $group_exist=array())
{
	GLOBAL $SUMO;
	
	$groups_array = sumo_get_grouplevel(sumo_get_user_available_group($SUMO['user']['user']));
	$groups_name  = array_keys($groups_array);
	$form_name    = $form_name ? $form_name : ucfirst($_SESSION['action']).ucfirst($_SESSION['module']);
	$available    = FALSE;
	$script 	  = "";
	$change = "n=document.forms['$form_name'].group;\n"
			 ."l=document.forms['$form_name'].newgroup;\n"
			 ."gr=n.options[n.selectedIndex].value;\n"
			 ."ls=g[gr];l.options.length=0;if(!gr)return;\n"
			 ."for(i=0;i<ls.length;i+=2){l.options[i/2]=new Option(ls[i],ls[i+1]);}\n";

	$list   = "<select name='group' onchange=\"".$change."\">\n<option></option>\n";

	for($g=0; $g<count($groups_name); $g++)
	{
		// ...administrator can add all groups
		if($groups_name[$g] == 'sumo')
		{
			$available_group = sumo_get_available_group();

			// ...to display 'sumo' group on top
			//if(!in_array('sumo', $group_exist))
			//	$list .= " <option value='sumo' style='color:#BB0000'>sumo</option>\n";

			for($g=0; $g<count($available_group); $g++)
			{
				// create levels
				for($l=1; $l<=7; $l++)
				{
					$value[$l] = $l.",'".$available_group[$g].":".$l."'";

					if($available_group[$g] == 'sumo' && $SUMO['user']['group_level']['sumo'] <= $l) break;
				}
				$script .= "g['".$available_group[$g]."']=new Array(".implode(',',$value).");\n";
				//

				if(!in_array($available_group[$g], $group_exist))
				{
					$list   .= " <option value='".$available_group[$g]."'>"
							  .$available_group[$g]
							  ."</option>\n";
				}
			}

			$available = TRUE;

			break;
		}
		else
		{
			// create levels
			for($l=1; $l<=$groups_array[$groups_name[$g]]; $l++)
			{
				$value[$l] = $l.",'".$groups_name[$g].":".$l."'";
			}
			$script .= "g['".$groups_name[$g]."']=new Array(".implode(',',$value).");";
			//

			if(!in_array($groups_name[$g], $group_exist))
			{
				$list .= " <option value='".$groups_name[$g]."'>".$groups_name[$g]."</option>\n";

				$available = TRUE;
			}
		}
	}

	$list .= "</select>&nbsp;:&nbsp;<select name='newgroup'></select>";

	$list = str_replace("onchange=\"", "onchange=\"g=new Array();".$script, $list);

	return ($available) ? $list : '';
}


/**
 * Verify if in a group string there is administrator group.
 */
function sumo_verify_sumogroup($group=array())
{
	if(is_array($group) && !empty($group))
	{
		$num_group = count($group);

		for($g=0; $g<$num_group; $g++)
		{
			$group_level = explode(":", $group[$g]);
			if($group_level[0] == 'sumo') return 'sumo:'.$group_level[1];
		}
	}
	else return FALSE;
}


/**
 * Update user data
 */
function sumo_update_user_data($data=array())
{
	if(!empty($data))
	{
		GLOBAL $SUMO;

		$id	   = intval($data['id']);
		$day_limit = intval($data['day_limit']);
		$active	   = $data['active']!=='' ? intval($data['active']) : FALSE;
		$firstname = ucwords(preg_replace('/[\s\,]+/', ' ', $data['firstname']));
		$lastname  = ucwords(preg_replace('/[\s\,]+/', ' ', $data['lastname']));
		$ip    	   = str_replace(";;", ";", str_replace(",", ";", preg_replace('/[\s\,]+/', ';', $data['ip'])));
		$email 	   = strtolower($data['email']);
		$language  = $data['language'];
		$sumogroup = sumo_verify_sumogroup($data['usergroup']);
		$group	   = $sumogroup ? $sumogroup : $data['usergroup'];
		$group     = sumo_get_normalized_group($group);

		if($day_limit > 0)
		{
			$daylimit[0] = 'day_limit='.$day_limit.', ';
			$daylimit[1] = 'day_limit='.$day_limit.' AND ';
		}
		else
		{
			$daylimit[0] = 'day_limit=NULL, ';
			$daylimit[1] = 'day_limit IS NULL AND ';
		}

        // Get user data
        $userdata   = sumo_get_user_info($id, 'id', FALSE);
        $sumouser   = sumo_get_user_info($SUMO['user']['user']);
        $datasource = sumo_get_datasource_info($data['datasource_id'], FALSE);

        // Change password
        if($data['password'] && ($SUMO['user']['id'] == $id || $SUMO['user']['id'] == $userdata['owner_id'] || $SUMO['user']['user'] == 'sumo'))
        {        	
        	switch ($datasource['type'])
        	{
        		case 'Unix':
        		case 'SUMO':
        			$record['password'] = "password='".$data['password']."'";
					sumo_update_password_date($id, $data['password']);
        			break;
        		case 'MySQLUsers':
        			require SUMO_PATH.'/libs/lib.datasource.mysql_users.php';
        			$sumo_update_password($userdata['username'], $data['password']);
        			break;
        		case 'Joomla15':
        			require SUMO_PATH.'/libs/lib.datasource.joomla15.php';		
        			$sumo_update_password($userdata['username'], $data['password']);
        			break;
        		default:
        			$record['password'] = "";
        			break;
        	}
        }
        
  
	if($group) $record['usergroup'] = "usergroup='$group'"; // group
        if($sumouser['id'] != $id) $record['active'] = "active=".$active; // active

	// verify if user can change some parameters...
	if($SUMO['user']['id'] == $id || in_array('sumo', $SUMO['user']['group']) ||
	   $SUMO['user']['id'] == $userdata['owner_id'])
	{
		$firstname = get_magic_quotes_gpc() ? $firstname : addslashes($firstname);
		$lastname  = get_magic_quotes_gpc() ? $lastname  : addslashes($lastname);
	
		$record['firstname'] = "firstname='".$firstname."'";
		$record['lastname']  = "lastname='".$lastname."'";
		$record['email']     = "email='$email'";
		$record['language']  = "language='$language'";
	}
	else
	{
		$record['firstname'] = "";
		$record['lastname']  = "";
		$record['email']     = "";
		$record['language']  = "";
	}

	//... to change IP address
	if(in_array('sumo', $SUMO['user']['group']) || $SUMO['user']['id'] == $userdata['owner_id'])
		$record['ip'] = "ip='".$ip."'";
	else
		$record['ip'] = "";
		
	// Data source
	$record['datasource_id'] = "datasource_id=".$data['datasource_id'];

	// modified
	$record['modified'] = "modified=".$SUMO['server']['time'];

	// Create fields for query
	$new_record = array_values($record);

	for($r=0; $r<count($new_record); $r++)
	{
		if($new_record[$r]) $records[$r] = $new_record[$r];
	}

	$update = implode(', ', $records);
	$select = implode(' AND ', $records);

	// create query for update
	$query = "UPDATE ".SUMO_TABLE_USERS."
		  SET ".$daylimit[0]." ".$update."
		  WHERE id=".$id;

	$SUMO['DB']->Execute($query);

	if($select || $day_limit[1]) $select = $select." AND ";
		
	// verify query success
	$query = "SELECT * FROM ".SUMO_TABLE_USERS."
		  WHERE ".$daylimit[1]."
		  ".$select."
		  id=".$id;

	$rs  = $SUMO['DB']->Execute($query);
	$tab = $rs->FetchRow();
	$upd = $rs->PO_RecordCount();

	// if updated:
	if($upd == 1)
	{
		$SUMO['DB']->CacheFlush();
		
		if($record['password'])
		{
			// ...to change current session password
			if($id == $SUMO['user']['id'])
			{
				$_SESSION['user']['password'] = sumo_get_hex_hmac_sha1($SUMO['connection']['security_string'], $data['password']);
				$_SESSION['pwd_changed']      = $SUMO['server']['time'];
			}
			// kill session
			else
			{
			    sumo_delete_session(NULL, NULL, $data['user']);
			}
		}

		sumo_write_log('I01000X', array($tab['username'], $SUMO['user']['user']), 3, 3, 'system', FALSE);

		// Send user notify
		if($SUMO['config']['accounts']['notify']['updates'] && $email)
		{
			if(!$SUMO['config']['server']['admin']['email'])
			{
				sumo_write_log('E06000X', '', '0,1', 2, 'system', FALSE);
			}
			else {
				$object  = sumo_get_message("I00001M", $SUMO['server']['name']);
				$message = sumo_get_message("I00106M", array($firstname." ".$lastname,
										$SUMO['server']['name'],
										$SUMO['user']['user']));
				$m = new Mail;
				$m->From($SUMO['config']['server']['admin']['email']);
				$m->To($email);
				$m->Subject($object);
				$m->Body($message, SUMO_CHARSET);
				$m->Priority(1);
				$m->Send();
			}
		}

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
 * Update user image
 */
function sumo_update_user_image($id=0, $size_limit=30720)
{
	$id = intval($id);

	if($id > 0) 
	{
		GLOBAL $SUMO;

		$file = pathinfo($_FILES['user_image']['name']);

		if(!in_array($file['extension'], array('jpg','jpeg','png','gif','bmp','tif')))
		{
			return FALSE;
		}
		elseif(is_uploaded_file($_FILES['user_image']['tmp_name']))
		{
			// check the file is less than the maximum file size

	        if($_FILES['user_image']['size'] <= $size_limit)
	        {
	        	// prepare the image for insertion
		        $data  = file_get_contents($_FILES['user_image']['tmp_name']);
		        //$image = get_magic_quotes_gpc() ? $data : addslashes($data);
		        $image = $SUMO['server']['db_type'] != 'postgres' ? addslashes($data) : pg_escape_bytea($data);
				//$image = mysql_real_escape_string($data);
	
		       	// get the image info..
		       	//$size = getimagesize($_FILES['user_image']['tmp_name']);
	
				sumo_delete_user_image($id);
	
				// put the image in the db...
				$query = "INSERT INTO ".SUMO_TABLE_USERS_IMAGES."
							(id_user, type, image)
				          VALUES (
				          		  ".$id.",
				          		  '".$_FILES['user_image']['type']."',
				          		  '{$image}'
				          )";
					
		      	$SUMO['DB']->Execute($query);
				
				return TRUE;
	        }
	    }
	    else
	    {
			 return FALSE;
	    }
	}
	else return FALSE;
}


/**
 *  Delete user image
 */
function sumo_delete_user_image($id='')
{
    $id = intval($id);

     if($id > 0)
     {
		GLOBAL $SUMO;

        $query = "DELETE FROM ".SUMO_TABLE_USERS_IMAGES."
        		  WHERE id_user=".$id;

	    $SUMO['DB']->Execute($query);
     }
}


/**
 * Update user group
 */
function sumo_update_user_group($id=0, $group=FALSE)
{
	$group_level = explode(":", $group);
	$id = intval($id);

	if($id > 0 && sumo_validate_group($group) && sumo_verify_permissions($group_level[1], $group_level[0]))
	{
		GLOBAL $SUMO;

		$query1 = "SELECT usergroup FROM ".SUMO_TABLE_USERS."
				   WHERE id=".$id;

		$rs  	= $SUMO['DB']->Execute($query1);
		$tab 	= $rs->FetchRow();

		$new_group = sumo_get_normalized_group(str_replace($group, '', $tab[0]));

		$query2 = "UPDATE ".SUMO_TABLE_USERS."
				   SET usergroup='".$new_group."',
				   		modified=".$SUMO['server']['time']."
				   WHERE id=".$id;

		$SUMO['DB']->CacheFlush("SELECT * FROM ".SUMO_TABLE_USERS."
						 		 WHERE id=".$id);

		$SUMO['DB']->Execute($query1);
		$SUMO['DB']->Execute($query2);

		sumo_write_log('I01002X', array($group, $id, $SUMO['user']['user']), '0,1', 3, 'system', FALSE);

		return TRUE;
	}
	else return FALSE;
}

?>