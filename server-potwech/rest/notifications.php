<?
/* Morin */

require_once '../includes/db_func.php';
require_once '../includes/phpmailer/class.phpmailer.php';


/*$testParcel = array("id" => "123", "sensors" => array("temperature" => "212.0", "humidity" => "80.2", "acceleration" => "1.5"));
checkNotifications($testParcel);*/


/**
Checks if the currently posted sensor values exceed a certain threshold.
The threshold can either be default or user defined.
All exceeded thresholds will be gathered into one message, that is sent via email eventually.

@param	parcel	The current parcel and all its information as an array ("id" => "1", "sensors" => ("temperature" => "21.0", "humidity" => "90.0")), etc.)
@return 1 if notification was sent; 0 if notification was not sent; -1 if an error occurred
**/
function checkNotifications($parcel_process_id, $parcel) {
	$user = array();
	
	//Initialize message body
	$messageBody = '';
	
	//Get associated user account from DB
	$result = query('SELECT * FROM current_parcel_processes JOIN sumo_users ON current_parcel_processes.user_id_ref = sumo_users.id WHERE current_parcel_processes.mobile_device_id = '. $parcel['id']);
	while($row = pg_fetch_assoc($result)) {
		if (isset($row['id'])) $user['id'] = $row['id'];
		if (isset($row['username'])) $user['username'] = $row['username'];
		if (isset($row['firstname'])) $user['firstname'] = $row['firstname'];
		if (isset($row['lastname'])) $user['lastname'] = $row['lastname'];
		if (isset($row['usergroup'])) $user['usergroup'] = $row['usergroup'];
		if (isset($row['email'])) $user['email'] = $row['email'];
	}
	
	//Set default thresholds
	$thresholds = array();
	$thresholds['temperature'] = array();
	$thresholds['temperature']['min'] = 19.0;
	$thresholds['temperature']['max'] = 21.0;
	
	$thresholds['humidity'] = array();
	$thresholds['humidity']['min'] = 49.0;
	$thresholds['humidity']['max'] = 51.0;
	
	$thresholds['acceleration'] = array();
	//$thresholds['acceleration']['min'] = 0.0; //This means: there is no minimum
	$thresholds['acceleration']['max'] = 2.0;
	
	$thresholds['light'] = array();
	//$thresholds['light']['min'] = 0.0; //This means: there is no minimum
	$thresholds['light']['max'] = 1.0;
	
	$thresholds['battery'] = array();
	$thresholds['battery']['min'] = 0.1;
	$thresholds['battery']['max'] = 1.1;
		
	
	//Overwrite default threshold with user defined thresholds (if any)
	$result = query('SELECT * FROM notifications_settings JOIN current_parcel_processes ON notifications_settings.pid = current_parcel_processes.parcel_process_id WHERE current_parcel_processes.mobile_device_id = '. $parcel['id']);
	while($row = pg_fetch_assoc($result)) {
		if (isset($row['min'])) $thresholds[trim($row['sensor'])]['min'] = $row['min'];
		if (isset($row['max'])) $thresholds[trim($row['sensor'])]['max'] = $row['max'];
	}
	
	//print_r($thresholds);
	//die();
	
	//Compare every sensor value against the threshold
	foreach ($thresholds as $thresholdName => $thresholdValues) {
		if (isset($parcel['sensors'][$thresholdName]) && isset($thresholdValues['min'])) {
			if ($parcel['sensors'][$thresholdName] < $thresholdValues['min']) {
				//If value is below threshold, add to message body
				$messageBody .= '<p>'. getSensorName($thresholdName) .': '. $parcel['sensors'][$thresholdName] .''. getSensorUnit($thresholdName) .'</p>';
				query('INSERT into problematic_parcels(parcel_process) values('.$parcel_process_id.')');
			}
		}
		if (isset($parcel['sensors'][$thresholdName]) && isset($thresholdValues['max'])) {
			if ($parcel['sensors'][$thresholdName] > $thresholdValues['max']) {
				//If value is above threshold, add to message body
				$messageBody .= '<p>'. getSensorName($thresholdName) .': '. $parcel['sensors'][$thresholdName] .''. getSensorUnit($thresholdName) .'</p>';
				query('INSERT into problematic_parcels(parcel_process) values('.$parcel_process_id.')');
			}
		}
	}
		
	//Check if message body is empty
	if ($messageBody != '') {
		//Mail non-empty message body to associated user and return YES
		$messageBody	= file_get_contents('../includes/templates/notifications/prefix.tpl') . $messageBody . file_get_contents('../includes/templates/notifications/suffix.tpl');
		$templateNames	= array('###FIRSTNAME###', '###LASTNAME###', '###PARCELID###');
		$templateValues	= array($user['firstname'], $user['lastname'], $parcel['id']);
		$messageBody 	= str_replace($templateNames, $templateValues, $messageBody);
		
		//echo $messageBody;
		//die();
				
		$mail = new PHPMailer();
		$mail->SetFrom('potwech@uni-muenster.de', 'POTWECH');
		$mail->AddAddress($user['email'], $user['firstname'].' '.$user['lastname']);
		$mail->Subject = 'POTWECH Notification Alert';
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer.';
		$mail->MsgHTML($messageBody);
		if(!$mail->Send()) {
			echo "Mail not sent. Error: ". $mail->ErrorInfo;
			return -1;
		} else {
			return 1;
		}
	}
		
    return 0;
}

/**
Returns the human readable name for a sensor type (e.g. returns "Temperature" for "temperature")

@param	sensor	The sensors internal name
@return The human readable name
**/
function getSensorName($sensor) {
	switch($sensor) {
		case 'temperature': return 'Temperature';
		case 'humidity': return 'Humidity';
		case 'acceleration': return 'Acceleration';
		case 'light': return 'Box was opened';
		case 'battery': return 'Battery';
		default: return $sensor;
	}
}

/**
Returns the unit in which a sensor value is measured

@param	sensor	The sensor type
@return The measurement unit for "sensor"
**/
function getSensorUnit($sensor) {
	switch($sensor) {
		case 'temperature': return '&deg;C';
		case 'humidity': return '%';
		case 'acceleration': return 'g';
		case 'light': return '% probability';
		case 'battery': return '%';
		default: return $sensor;
	}
}

?>