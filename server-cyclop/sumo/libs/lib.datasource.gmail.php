<?php
/**
 * SUMO CORE DATASOURCE LIBRARY
 *
 * @version    0.4.2
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 * @category   Core
 */

require SUMO_PATH.'/classes/class.gmailer.php';


/**
 * Check datasource connection
 * 
 * @global resource $SUMO
 */
$sumo_verify_datasource_connection = create_function('$id=false', '

	$info = pathinfo(GM_LNK_GMAIL_HTTP);
		
	$stream = stream_socket_client(str_replace("http://", "tcp://", $info["dirname"]).":80", $errno, $errstr, 10);
	
	$connection = $stream ? true : false;

	fclose($stream);
		
	return $connection;
');


/**
 * Verify password of current user
 * 
 * Return:
 * 
 * FALSE: password error
 * TRUE: password ok
 * 
 * @global resource $SUMO
 * @author Alberto Basso <albertobasso@users.sourceforge.net>
 */
$sumo_verify_datasource_authentication = create_function('$id=FALSE', ' 

	GLOBAL $SUMO;
	
	if(!$_SESSION["ds_connect"][$id]) 
	{	
		$ds = sumo_get_datasource_info($id);		

		$gmailer = new GMailer();
		
		if ($gmailer->created) 
		{		
			$gmailer->setLoginInfo($SUMO["user"]["user"], $_SESSION["user"]["password"], (date("Z")/3600));
			//$gmailer->setProxy("proxy.company.com");
						
			if ($gmailer->connect()) 
			{		
				$_SESSION["ds_connect"][$id] = true;

				$SUMO["user"]["password"] = "";
			} 
			else 
			{
				sumo_write_log("W00051X", $ds["name"], "0,1", 2);
				
				$err = $gmailer->lastActionStatus();
				
				// GMail require CHAPCHA
				if($err == "login challenge") 
				{
					echo "<script>alert(\"- GMail ERROR - Please, exit from your GMail account and repeat login !\");</script>";
				}
				else 
				{
					echo "<script>alert(\"- GMail ERROR - Fail to connect because: $err\");</script>";
				}
			}
		
		} else {
			//die("Failed to create GMailer because: ".$gmailer->lastActionStatus());
		}
			
	}
	
	return $_SESSION["ds_connect"][$id] ? true : false;
');

?>