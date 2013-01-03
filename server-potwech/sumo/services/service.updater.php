<?php
/**
 * SERVICE: Updater
 *
 *
 * @version    0.5.0
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 */


// Verify startup errors
$err = FALSE;

if(preg_match("/".basename(__FILE__)."/", $_SERVER['SCRIPT_NAME'])) $err = 'E00001S'; // Can't access this file directly!

// Display startup error then exit
if ($err) require SUMO_PATH.'/inc/inc.startup_errors.php';


switch ($_GET['cmd'])
{
	// update version
	/*
	case 'GET_UPDATER_VERSION':

		$content = file_get_contents(SUMO_PATH."/install/update_to_");

		break;
	*/

	// update IP2Country database
	case 'UPDATE_IP2C':
		// Valid record type:
		// 201620304;201620311;CA;CAN;CANADA
		//
		// Convert original csv file from
		// using Sed:
		// sed 's/^"/NULL;/g;s/"//g' ip-to-country.csv.original > ip-to-country.csv
		$csv_file = SUMO_PATH."/install/ip-to-country.csv";
		#$ip2c_file = SUMO_PATH."tmp/iptocountry";
		#$csv_date  = filectime($csv_file);
		#$ip2c_date = file_get_contents($ip2c_file);

		#if($csv_date > $ip2c_date)
		#{

			if($SUMO['server']['db_type'] == 'sqlite') $fp = fopen (SUMO_PATH."/tmp/ip-to-country.sql", 'w+');

			$query = "DELETE FROM ".SUMO_TABLE_IPTOCOUNTRY;

			if($SUMO['server']['db_type'] == 'sqlite')
				fwrite($fp, $query.";\n");
			else
				$SUMO['DB']->Execute($query);

			// MySQL
			/*
			if($SUMO['server']['db_type'] == 'mysql')
			{
				$query = "LOAD DATA LOCAL INFILE '".$csv_file."'
						  INTO TABLE ".SUMO_TABLE_IPTOCOUNTRY."
						  FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n'";

				$SUMO['DB']->Execute($query);
			}
			// Other databases
			else
			{
			*/
				$handle = fopen($csv_file, "r");
				
				if ($handle)
				{
				    while (!feof($handle))
				    {
				        $content[] = fgets($handle, 4096);
				    }
				    fclose($handle);
				}

				$lines = count($content)-1;

				for($c=0; $c<$lines; $c++)
				{
					$l = str_replace("\"", "", $content[$c]);
					$l = explode(",", $l);

					$query = "INSERT INTO ".SUMO_TABLE_IPTOCOUNTRY." "
				 			."VALUES ('".$l[0]."','".$l[1]."','".trim($l[2])."','".trim($l[3])."','".trim(str_replace("'", "&rsquo;", $l[4]))."')";

				    if($SUMO['server']['db_type'] == 'sqlite')
				    	fwrite($fp, $query.";\n");
				    else
				    	$SUMO['DB']->Execute($query);
				}

				if($SUMO['server']['db_type'] == 'sqlite') fclose($fp);


				if($SUMO['server']['db_type'] == 'sqlite')
				{
					include SUMO_PATH."/configs/config.database.php";

					echo "IP2Country SQL file created.<br><br>"
						."Now you can run these commands from command line:<br><br>"
						."<code>"
						."# sqlite ".$sumo_db['path']."/".$sumo_db['name']."<br>"
						."sqlite> .read ".SUMO_PATH."/tmp/ip-to-country.sql"
						."</code>"
						."<br>Remember that the load can require several minutes.";  // PROVVISORIO
				}
				else {
					echo "IP2Country database updated!";  // PROVVISORIO
				}

			/*
			}

			$fp = fopen ($ip2c_file, 'w+');
				  fwrite($fp, $csv_date);
			  	  fclose($fp);

			echo "IP2Country database updated!";  // PROVVISORIO
		}
		else
		{
			echo "IP2Country database NOT updated!";
		}
		*/
		break;

	// Unknow command
	default:
		echo "E00121X";
		break;
}

?>