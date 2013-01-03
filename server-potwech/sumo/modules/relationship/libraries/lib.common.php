<?php
/**
 * SUMO LIBRARY: Relationship
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
 * Get list of pages that user can access
 * 
 * IS THE SAME FUNCTION OF USERS LIBRARY !!!!
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

?>