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
 * Delete local IP address from table
 * 
 * @return boolean 
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_delete_intranet_ip($id=0)
{	
	$id = intval($id);
	
	if($id > 0) 
	{		
		GLOBAL $SUMO;
		
		$ip = sumo_get_intranet_ip_info($id, 'id', FALSE);
		
		$SUMO['DB']->CacheFlush();
				
        $query0 = "DELETE FROM ".SUMO_TABLE_INTRANETIP." 
    		   	   WHERE id=".$id;

        $query1 = "SELECT * FROM ".SUMO_TABLE_INTRANETIP." 
        		   WHERE id=".$id;
        
		$SUMO['DB']->Execute($query0);
		$SUMO['DB']->Execute($query1);
        
        // verify if deleted: 
        $rs = $SUMO['DB']->Execute($query1);
				
        // if deleted:
		if($rs->PO_RecordCount(SUMO_TABLE_INTRANETIP, "id=".$id) == 0) 
		{							  
			sumo_write_log('I09011X', array($ip['ip'], $id, $SUMO['user']['user']), '0,1', 3, 'system', FALSE);
				      		
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
 * Get Local IP address info
 * 
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_get_intranet_ip_info($value='', $field='id', $cache=TRUE, $time=300) 
{	
	GLOBAL $SUMO;
	
	switch ($field) 
	{		
		case 'id':	 $value = "id=".intval($value); break;
		case 'host': $value = "host='".$value."'";	break;
		default: 	 $value = "id=".intval($value); break;
	}
	
	$query = "SELECT * FROM ".SUMO_TABLE_INTRANETIP." 
			  WHERE ".$value;
		
	if($cache)
		$rs = $SUMO['DB']->CacheExecute($time, $query);
	else
		$rs = $SUMO['DB']->Execute($query);
		
	$ip = $rs->FetchRow();
			
	return $ip;
}


/**
 * Insert Intranet IP
 */
function sumo_add_intranet_ip($data=array())
{	
	if(!empty($data)) 
	{	
		GLOBAL $SUMO;
				
		$query = "INSERT INTO ".SUMO_TABLE_INTRANETIP."
				  (ip, type)
				  VALUES (
				  	'".$data['ip']."', '".$data['type']."'
				  );";

		$SUMO['DB']->Execute($query);
		
		// if intranet IP updated
		if(sumo_verify_intranet_ip_exist($data['ip'])) 
		{					
			sumo_write_log('I09008X', array($data['ip'], $SUMO['user']['user']), 3, 3, 'system', FALSE);
						
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
 * Update intranet IP
 */
function sumo_update_intranet_ip_data($data=array())
{	
	if(!empty($data)) 
	{	
		GLOBAL $SUMO;
		
		$query = "UPDATE ".SUMO_TABLE_INTRANETIP." 
				  SET				  	
				  	type='".$data['type']."',
				  	ip='".$data['ip']."' 
				  WHERE id=".$data['id'];

		$SUMO['DB']->Execute($query);
		
		// if intranet IP updated
		if(sumo_verify_intranet_ip_exist($data['ip'])) 
		{					
			sumo_write_log('I09007X', 
						   array($data['ip'], $SUMO['user']['user']), 
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
 * Verify if intranet ip exist
 *
 * @global resource $SUMO
 * @return boolean
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
function sumo_verify_intranet_ip_exist($ip='') 
{	
	GLOBAL $SUMO;
		
	$query = "SELECT id FROM ".SUMO_TABLE_INTRANETIP." 
			  WHERE ip='".$ip."'";
						
	$rs = $SUMO['DB']->Execute($query);
		
	$ip = $rs->PO_RecordCount(SUMO_TABLE_INTRANETIP, "ip='".$ip."'");
	
	return ($ip != 0) ? true : false;
}


?>