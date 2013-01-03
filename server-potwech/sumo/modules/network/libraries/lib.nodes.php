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
 * Delete node
 * 
 * @return boolean 
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_delete_node($id=0)
{	
	$id = intval($id);
	
	if($id > 1) 
	{		
		GLOBAL $SUMO;
		
		$node = sumo_get_node_info($id, 'id', FALSE);
		
		$SUMO['DB']->CacheFlush();
				
        $query = "DELETE FROM ".SUMO_TABLE_NODES." 
    		   	  WHERE id=".$id;
        
		$SUMO['DB']->Execute($query);
        
		$query = "SELECT COUNT(id) FROM ".SUMO_TABLE_NODES." 
        		  WHERE id=".$id;
		
        // verify if deleted: 
        $rs  = $SUMO['DB']->Execute($query);
		$tab = $rs->FetchRow();
	
        // if yes:
		if($tab[0] == 0) 
		{					
			// Delete all accesspoints of deleted node
			$query1 = "DELETE FROM ".SUMO_TABLE_ACCESSPOINTS." 
	    		   	   WHERE node=".$id;
			$query2 = "DELETE FROM ".SUMO_TABLE_ACCESSPOINTS_STATS." 
				       WHERE id_page=".$id;
	        
			$SUMO['DB']->Execute($query1);
			$SUMO['DB']->Execute($query2);
			
			sumo_write_log('W09000X', array($node['name'], $id, $SUMO['user']['user']), 3, 2, 'system', FALSE);
				      		
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
 * Put data node protocol
 */
function sumo_put_node_protocol($default='', $autosubmit=0, $name='protocol')
{
	$autosubmit = $autosubmit ? " onchange='".$autosubmit."'" : '';
	$availables = array('http', 'https');	
	
	$list = "<select name='".$name."'".$autosubmit.">\n"
	       ." <option value='".$default."'>".$default."</option>\n";
				   
	$num_availables = count($availables);
		
	for($l=0; $l<$num_availables; $l++) 
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
 * Add node
 */
function sumo_add_node($data=array())
{	
	if(!empty($data)) 
	{	
		GLOBAL $SUMO;
				
		$query = "INSERT INTO ".SUMO_TABLE_NODES."
				  (active, host, port, name, protocol, sumo_path)
				  VALUES (
				  	'".$data['active']."', '".$data['host']."', ".$data['port'].",
				  	'".$data['name']."', '".$data['protocol']."', '".$data['sumo_path']."'
				  );";
			
		$SUMO['DB']->Execute($query);
		
		// if node updated
		if(sumo_verify_node_exist($data)) 
		{					
			sumo_write_log('I09009X', 
						   array($data['name'], $data['host'], $SUMO['user']['user']), 
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
 * Update Node data
 */
function sumo_update_node_data($data=array())
{	
	if(!empty($data)) 
	{	
		GLOBAL $SUMO;
		
		// preserve current node
		$data['active'] = sumo_verify_node_local($data['host']) ? 1 : $data['active'];
		
		$query = "UPDATE ".SUMO_TABLE_NODES." 
				  SET				  	
				  	active=".$data['active'].", 
				  	host='".$data['host']."', 
				  	port=".$data['port'].",
				  	name='".$data['name']."', 				  	
				  	protocol='".$data['protocol']."', 
				  	sumo_path='".$data['sumo_path']."' 
				  WHERE id=".$data['id'];
	
		$SUMO['DB']->Execute($query);
			
		// if node updated
		if(sumo_verify_node_exist($data)) 
		{					
			sumo_write_log('I09010X', 
						   array($data['name'], $data['host'], $SUMO['user']['user']), 
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
 * Verify if a Node already exist
 *
 * @global resource $SUMO
 * @return boolean
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_node_exist($data=array()) 
{	
	GLOBAL $SUMO;
		
	$query = "SELECT COUNT(id) AS nodes FROM ".SUMO_TABLE_NODES." 
			  WHERE host='".$data['host']."' 
			  	AND port='".$data['port']."' 
			  	AND protocol='".$data['protocol']."'";

	$rs = $SUMO['DB']->Execute($query);
		
	$tab = $rs->FetchRow();
		
	return ($tab['nodes'] > 0) ? true : false;
}


/**
 * Get numbers of accesspoints for a Node
 *
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_accesspoints_on_node($id=0) 
{	
	GLOBAL $SUMO;
		
	$query = "SELECT COUNT(id) FROM ".SUMO_TABLE_ACCESSPOINTS." 
			  WHERE node=".intval($id);

	$rs = $SUMO['DB']->CacheExecute(300, $query);
		
	$tab = $rs->FetchRow();
		
	return $tab[0];
}

?>