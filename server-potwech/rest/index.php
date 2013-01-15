<?php
//the REST API
require 'Slim/Slim.php';
//db information and a more easy query function
require '../includes/db_func.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();


//get all measurements of a parcel_process
$app->get('/parcel_measurements/:id', function ($id) {
    $result = query("select measurement_id,temp, humidity,time_of_measurement, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height, battery from measurements where parcel_process = ".$id);
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurement_id'] = $row['measurement_id'];
			$point['properties']['temperature'] = (double)$row['temp'];
			$point['properties']['humidity'] = (double)$row['humidity'];
			$point['properties']['time'] = $row['time_of_measurement'];
			$point['properties']['battery'] = $row['battery'];
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
			array_push($json['features'],$point); 	
		}
		
	header('Content-Type: application/json');
	echo json_encode($json);
});

<<<<<<< HEAD
=======
//get all measurements of a parcel_process
$app->get('/distinct_parcel_measurements/:id', function ($id) {
    $result = query("select measurement_id,temp, humidity,time_of_measurement, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height, battery from measurements where parcel_process = ".$id." order by lat,lon, time_of_measurement");
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		$oldLat = 0;
		$oldLon = 0;
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurement_id'] = $row['measurement_id'];
			$point['properties']['temperature'] = (double)$row['temp'];
			$point['properties']['humidity'] = (double)$row['humidity'];
			$point['properties']['time'] = $row['time_of_measurement'];
			$point['properties']['battery'] = $row['battery'];
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();			
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
			
			if($oldLat != floatval($row['lat']) && $oldLon != floatval($row['lon'])){
				array_push($json['features'],$point); 	
			}
			$oldLat = floatval($row['lat']);
			$oldLon = floatval($row['lon']);
		}
		
	header('Content-Type: application/json');
	echo json_encode($json);
});

>>>>>>> Server Changes - final?

//get all events of a parcel_process
$app->get('/parcel_events/:id', function ($id) {
    $result = query("select events_id, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from events where parcel_process = ".$id);
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['events_id'] = $row['events_id'];
			//$point['properties']['accuracy'] = $row['location_accuracy'];
			//$point['properties']['type'] = ($row['velocity']!=null ? 'auto' : 'manu');
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
			array_push($json['features'],$point); 	
		}
	header('Content-Type: application/json');	
	echo json_encode($json);
});

//get all light events of a parcel_process
$app->get('/parcel_light-events/:id', function ($id) {
    $result = query("select events_id, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height, light, time_of_event as time from all_light_events where parcel_process = ".$id);
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['events_id'] = $row['events_id'];
			$point['properties']['light']=$row['light'];
			$point['properties']['time']=$row['time'];
			//$point['properties']['accuracy'] = $row['location_accuracy'];
			//$point['properties']['type'] = ($row['velocity']!=null ? 'auto' : 'manu');
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
			array_push($json['features'],$point); 	
		}
	header('Content-Type: application/json');	
	echo json_encode($json);
});

//get all vibration events of a parcel_process
$app->get('/parcel_vibration-events/:id', function ($id) {
    $result = query("select events_id, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height, vibration, time_of_event as time from all_vibration_events where parcel_process = ".$id);
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['events_id'] = $row['events_id'];
			$point['properties']['vibration']=$row['vibration'];
			$point['properties']['time']=$row['time'];
			//$point['properties']['accuracy'] = $row['location_accuracy'];
			//$point['properties']['type'] = ($row['velocity']!=null ? 'auto' : 'manu');
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
			array_push($json['features'],$point); 	
		}
	header('Content-Type: application/json');	
	echo json_encode($json);
});

//get all acceleration events of a parcel_process
$app->get('/parcel_acceleration-events/:id', function ($id) {
    $result = query("select events_id, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height, acceleration, time_of_event as time from all_acceleration_events where parcel_process = ".$id);
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['events_id'] = $row['events_id'];
			$point['properties']['acceleration']=$row['acceleration'];
			$point['properties']['time']=$row['time'];
			//$point['properties']['accuracy'] = $row['location_accuracy'];
			//$point['properties']['type'] = ($row['velocity']!=null ? 'auto' : 'manu');
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
			array_push($json['features'],$point); 	
		}
	header('Content-Type: application/json');	
	echo json_encode($json);
});

//get all current_parcel_processes
$app->get('/current_parcels/', function () {
    $result = query("select parcel_process_id, user_id_ref from current_parcel_processes");
	
	$json = array();
	$json['parcels'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$parcel = array();
			$parcel['parcel_process'] = $row['parcel_process_id'];
			$parcel['user_id_ref'] = $row['user_id_ref'];
			array_push($json['parcels'],$parcel); 	
		}
	header('Content-Type: application/json');
	echo json_encode($json);
});


//get all parcel_processes ever
$app->get('/all_parcels/', function () {
    $result = query("SELECT parcel_process_id FROM parcel_processes");
	
	$json = array();
	$json['parcels'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$parcel = array();
			$parcel['parcel_process'] = $row['parcel_process_id'];
			$parcel['geometry'] = array("type"=>"LineString","coordinates"=>array());
				$result2 = query("select ST_X(geom) as lat, ST_Y(geom) as lon from measurements where parcel_process = ".$row['parcel_process_id']);
				while($row2 = pg_fetch_assoc($result2)){
					array_push($parcel['geometry']['coordinates'],array(floatval($row2['lon']),floatval($row2['lat'])));
				}
			array_push($json['parcels'],$parcel); 	 
		}
	header('Content-Type: application/json');
	echo json_encode($json);
});


//get all parcels which where set as problematic
$app->get('/problematic_parcels/', function () {
    $result = query("select distinct parcel_process_id from current_parcel_processes inner join problematic_parcels on (current_parcel_processes.parcel_process_id = problematic_parcels.parcel_process)");
	
	$json = array();
	$json['parcels'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$parcel = array();
			$parcel['parcel_process'] = $row['parcel_process_id'];
			array_push($json['parcels'],$parcel); 	
		}
	header('Content-Type: application/json');
	echo json_encode($json);
});

//get all measurements and events of a parcel_process
$app->get('/parcel/:id', function ($id) {
    $result_measurements = query("select measurement_id,temp, humidity,time_of_measurement, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where parcel_process = ".$id);
	$result_events = query("select events_id, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from events where parcel_process = ".$id);
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result_measurements)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurement_id'] = $row['measurement_id'];
			$point['properties']['temperature'] = $row['temp'];
			$point['properties']['humidity'] = $row['humidity'];
			$point['properties']['time'] = $row['time_of_measurement'];
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
			array_push($json['features'],$point); 	
		}
		
		while($row = pg_fetch_assoc($result_events)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['events_id'] = $row['events_id'];
			//$point['properties']['accuracy'] = $row['location_accuracy'];
			//$point['properties']['type'] = ($row['velocity']!=null ? 'auto' : 'manu');
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
			array_push($json['features'],$point); 
		}
	header('Content-Type: application/json');	
	echo json_encode($json);
});

//get all measurements and events of a parcel_process
$app->get('/latest_parcel/:id', function ($id) {
    $result_measurements = query("select measurement_id,temp, humidity,time_of_measurement, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where parcel_process = ".$id." order by time_of_measurement desc limit 1");
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result_measurements)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurement_id'] = $row['measurement_id'];
			$point['properties']['temperature'] = $row['temp'];
			$point['properties']['humidity'] = $row['humidity'];
			$point['properties']['time'] = $row['time_of_measurement'];
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
			array_push($json['features'],$point); 	
		}

	header('Content-Type: application/json');	
	echo json_encode($json);
});

//get max acceleration of a parcel_process
$app->get('/maxAcceleration/:id', function ($id) {
    $result = query("select events_id,acceleration, time_of_event, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from acceleration_events join events on events_id = event_id_ref where parcel_process = ".$id." order by acceleration DESC limit 1");
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['event_id'] = $row['event_id'];
			$point['properties']['maxAcceleration'] = $row['acceleration'];
			$point['properties']['time'] = $row['time_of_event'];
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
			array_push($json['features'],$point); 	
		}		
	header('Content-Type: application/json');
	echo json_encode($json);
});

//get max value of event type of a parcel_process (works only if every event-type will only have one variable)
$app->get('/maxValue/:id/:eventType', function ($id,$eventType) {
    $result = query("select events_id,".$eventType.", time_of_event, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from ".$eventType."_events join events on events_id = event_id_ref where parcel_process = ".$id." order by ".$eventType." DESC limit 1");
	
	//$json = array();
	//$json['type'] = 'FeatureCollection';
	//$json['features'] = array();
	$point = array();
		while($row = pg_fetch_assoc($result)){
			
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['event_id'] = $row['events_id'];
			$point['properties']['max'.$eventType] = $row[$eventType];
			$point['properties']['time'] = $row['time_of_event'];
			$point['geometry'] = array();
			$point['geometry']['type'] = 'Point';
			$point['geometry']['coordinates'] = array();
			array_push($point['geometry']['coordinates'],floatval($row['lon']));
			array_push($point['geometry']['coordinates'],floatval($row['lat']));
			array_push($point['geometry']['coordinates'],floatval($row['height']));
		//	array_push($json['features'],$point); 	
		}		
	header('Content-Type: application/json');
	echo json_encode($point);
});

//Get the parcel_processes of a specific user
$app->get('/owner/:id', function($id){
	$result_active = query("select parcel_process_id from current_parcel_processes where user_id_ref = ".$id);
	$result_historic = query("select parcel_process_id from historic_parcel_processes where user_id_ref = ".$id);
	$json = array();
	$json['active_parcel_processes'] = array();
	$json['historic_parcel_processes'] = array();
		
	while($row = pg_fetch_assoc($result_active)){
		array_push($json['active_parcel_processes'],$row['parcel_process_id']);	
	}
	while($row = pg_fetch_assoc($result_historic)){
		array_push($json['historic_parcel_processes'],$row['parcel_process_id']);	
	}
	echo json_encode($json);		
});


$app->get('/notifications_settings/:id', function($id){
	$result = query("select * from notifications_settings where pid = ".$id);
	
	$json = array();
	
	while($row = pg_fetch_assoc($result)){	
		$json[trim($row['sensor'])] = array();
		$json[trim($row['sensor'])]['min'] = $row['min'];
		$json[trim($row['sensor'])]['max'] = $row['max'];
		
	}
	echo json_encode($json);

});


/*
    curl -i -H "Accept: application/json" -X POST -d 'data={"device_id":154742364,"measurements":[{"timestamp":"15446787","cellid":"109128739432","mcc":"123456","mnc":"123456","temperature":"29","humidity":"80","light":"1","acceleration":{"x":0,"y":0,"z":0},"compass":{"x":0,"y":0,"z":0},"battery":78},{"timestamp":"154543787","cellid":"109128739432","mcc":"123456","mnc":"123456","temperature":"75","humidity":"90","light":"0","acceleration":{"x":0,"y":0,"z":0},"compass":{"x":0,"y":0,"z":0},"battery":76}]}' http://potwech.uni-muenster.de/rest/index.php/post  

	curl -i -H "Accept: application/json" -X POST -d 'uid=2&mobile_device=123&parcel_number=555' http://potwech.uni-muenster.de/rest/index.php/initParcel  
	
*/

$app->post('/initParcel', function () use ($app) {
	$uid = utf8_encode($app->request()->post('uid'));
	$mobile_device = utf8_encode($app->request()->post('mobile_device'));
	$parcel_number = utf8_encode($app->request()->post('parcel_number'));
		
	$result = query('select * from current_parcel_processes where mobile_device_id = '.$mobile_device);
	if(pg_num_rows($result) > 0){
		while($row = pg_fetch_assoc($result)){
			query('update parcel_processes set end_time = now() where parcel_process_id = '.$row['parcel_process_id']);
		}
	}

	if($uid && $mobile_device && $parcel_number){
		$query = 'insert into parcel_processes(user_id_ref, mobile_device_id, package_number, start_time) values('.$uid.','.$mobile_device.','.$parcel_number.',now())';
		query($query);
	}else{
		echo 'missing data';
	}
});

/*
<<<<<<< HEAD
	curl -i -H "Accept: application/json" -X POST -d 'uid=2&mobile_device=123&parcel_number=555' http://potwech.uni-muenster.de/rest/index.php/endParcel  
*/

$app->post('/endParcel', function () use ($app) {
	$uid = utf8_encode($app->request()->post('uid'));
=======
	curl -i -H "Accept: application/json" -X POST -d 'mobile_device=123&parcel_number=555' http://potwech.uni-muenster.de/rest/index.php/endParcel  
*/

$app->post('/endParcel', function () use ($app) {
>>>>>>> Server Changes - final?
	$mobile_device = utf8_encode($app->request()->post('mobile_device'));
	$parcel_number = utf8_encode($app->request()->post('parcel_number'));
		
	$result = query('select * from current_parcel_processes where mobile_device_id = '.$mobile_device);
	if(pg_num_rows($result) > 0){
		while($row = pg_fetch_assoc($result)){
			query('update parcel_processes set end_time = now() where parcel_process_id = '.$row['parcel_process_id']);
		}
	}

	/*
	if($uid && $mobile_device && $parcel_number){
		$query = 'insert into parcel_processes(user_id_ref, mobile_device_id, package_number, start_time) values('.$uid.','.$mobile_device.','.$parcel_number.',now())';
		query($query);
	}else{
		echo 'missing data';
	}*/
});

$app->post('/testPost', function () use ($app) {
   echo $app->request()->post('data');
});

/*
<<<<<<< HEAD
curl -i -H "Accept: application/json" -X POST -d 'data=123;1357660358;02F1;262;02;010E;22;50;70;123;1357660358;02F1;262;02;010E;23;51;71' http://potwech.uni-muenster.de/rest/index.php/postMeasurement
=======
curl -i -H "Accept: application/json" -X POST -d 'data=123;1358260818;02f1;262;02;010E;23;27;75;123;1358250823;02f1;262;02;010E;23;27;75;123;1358260818;02f1;262;02;010E;23;27;75;123;1358250823;02f1;262;02;010E;23;27;75' http://potwech.uni-muenster.de/rest/index.php/postMeasurement
>>>>>>> Server Changes - final?
*/
$app->post('/postMeasurement', function() use ($app){
	require('Location.php');
	require('notifications.php');
	
	$data = $app -> request()->post('data');
	error_log("Post Data: ".$data,0);
	//echo 'Stuff: '.$data;
	if($data){
		// deviceId, timestamp, cellId, mcc, mnc, temperature, humidity, battery
		$parsed = explode(";", $data);
		
		if((sizeof($parsed) % 9)==0){
<<<<<<< HEAD
			for ($i=0;$i<sizeof($parsed);$i=$i+9){
			$deviceId = $parsed[$i];
			$timestamp = gmdate("Y-m-d H:i:s ",$parsed[$i+1]);
			$cellId = hexToStr($parsed[$i+2]);
			$mcc = $parsed[$i+3];
			$mnc = $parsed[$i+4];
			$lac = hexToStr($parsed[$i+5]);
			$temperature = $parsed[$i+6];
			$humidity = $parsed[$i+7];
			$battery = $parsed[$i+8];
			
			$parcel_process = null;
			$result = query('select * from current_parcel_processes where mobile_device_id = '.$deviceId.' limit 1');
			if(pg_num_rows($result) > 0){
				while($row = pg_fetch_assoc($result)){
					$parcel_process = $row['parcel_process_id'];
				}
			}
			
			
			if($parcel_process != null){
				//Convert Cell-ID to LatLon
				//$mcc, $mnc, $cellId, $lac
				
				//$location = getLocationFromCell(262,02,753,270);
				$lat = 0;
				$lon = 0;
				$location = getLocationFromCell($mcc, $mnc, $cellId, $lac);
				$location = json_decode($location, true);
				$lat = $location['coordinate']['latitude'];
				$lon = $location['coordinate']['longitude'];
				
				if($lat != 0 && $lon !=0){
				//Insert into database
					query('insert into measurements(parcel_process, geom, temp, humidity, time_of_measurement) values('.$parcel_process.',ST_SetSRID(ST_MakePoint('.$lat.','.$lon.',1.0), 4326), '.$temperature.', '.$humidity.', \''.$timestamp.'\')');
				}else{
					echo 'No location information available';
				}
				//echo $lat.'  '.$lon;
		
			}else{
				echo 'No parcel process';
			}	}
=======
			$oldLac = null;
			$oldMnc = null;
			$oldMcc = null;
			$oldCellId = null;
			$lat = null;
			$lon = null;
		
			for ($i=0;$i<sizeof($parsed);$i=$i+9){
				$deviceId = $parsed[$i];
				$timestamp = gmdate("Y-m-d H:i:s ",$parsed[$i+1]);
				$cellId = hexToStr($parsed[$i+2]);
				$mcc = $parsed[$i+3];
				$mnc = $parsed[$i+4];
				$lac = hexToStr($parsed[$i+5]);
				$temperature = $parsed[$i+6];
				$humidity = $parsed[$i+7];
				//$battery = 75;
				$battery = str_replace("\\r", '',$parsed[$i+8]);
				
				$parcel_process = null;
				$result = query('select * from current_parcel_processes where mobile_device_id = '.$deviceId.' limit 1');
				if(pg_num_rows($result) > 0){
					while($row = pg_fetch_assoc($result)){
						$parcel_process = $row['parcel_process_id'];
					}
				}
				
				
				if($parcel_process != null){
					//Convert Cell-ID to LatLon
					//$mcc, $mnc, $cellId, $lac
					
					//$location = getLocationFromCell(262,02,753,270);
					if(isset($mcc) && isset($mnc) && isset($cellId) && isset($lac)){
					
						if($mcc != $oldMcc or $mnc != $oldMnc or $lac != $oldLac or $oldCellId != $cellId){
							$oldLac = $lac;
							$oldMnc = $mnc;
							$oldMcc = $mcc;
							$oldCellId = $cellId;
							
							$location = getLocationFromCell($mcc, $mnc, $cellId, $lac);
							$location = json_decode($location, true);
							$lat = $location['coordinate']['latitude'];
							$lon = $location['coordinate']['longitude'];
							error_log("new location",0);
						}else{
						error_log("old location",0);
						}
					}
					
					if(isset($lat) && isset($lon)){
					//Insert into database
						query('insert into measurements(parcel_process, geom, temp, humidity, battery, time_of_measurement) values('.$parcel_process.',ST_SetSRID(ST_MakePoint('.$lat.','.$lon.',1.0), 4326), '.$temperature.', '.$humidity.', '.$battery.', \''.$timestamp.'\')');
					}else{
						echo 'No location information available';
						error_log("Post no location information",0);
					}
					//echo $lat.'  '.$lon;
			
				}else{
					echo 'No parcel process';
					error_log("No parcel process",0);
				}	
			}
>>>>>>> Server Changes - final?
		}else{
			echo 'Incomplete data';
			error_log("Incomplete data",0);
		}
	
	}else{
		echo 'Missing data';
		error_log("Missing data",0);
	}
	
error_log("post succes",0);
});



/*
curl -i -H "Accept: application/json" -X POST -d 'data=123;1357660358;02F1;262;02;010E;500;123;1357660358;02F1;262;02;010E;700' http://potwech.uni-muenster.de/rest/index.php/postLight
*/
$app->post('/postLight', function() use ($app){
	require('Location.php');
	
	$data = $app -> request()->post('data');
	error_log("Post Light Data: ".$data,0);
	if($data){
		// deviceId, timestamp, cellId, mcc, mnc, lac, light
		$parsed = explode(";", $data);
		
		if((sizeof($parsed) % 7)==0){
		for ($i=0;$i<sizeof($parsed);$i=$i+7){

			$deviceId = $parsed[$i+0];
			$timestamp = $parsed[$i+1];
			$cellId = hexToStr($parsed[$i+2]);
			$mcc = $parsed[$i+3];
			$mnc = $parsed[$i+4];
			$lac = hexToStr($parsed[$i+5]);
			$light = $parsed[$i+6];
			
			
			$parcel_process = null;
			$result = query('select * from current_parcel_processes where mobile_device_id = '.$deviceId.' limit 1');
			if(pg_num_rows($result) > 0){
				while($row = pg_fetch_assoc($result)){
					$parcel_process = $row['parcel_process_id'];
				}
			}
			if($parcel_process != null){
				//Convert Cell-ID to LatLon
<<<<<<< HEAD
				$lat = 0;
				$lon = 0;
				$location = getLocationFromCell($mcc, $mnc, $cellId, $lac);
				$location = json_decode($location, true);
				$lat = $location['coordinate']['latitude'];
				$lon = $location['coordinate']['longitude'];
				
				if($lat != 0 && $lon !=0){				
					//Insert into database
					//query('insert into measurements(parcel_process, geom, temp, humidity, time_of_measurement) values('.$parcel_process.',ST_SetSRID(ST_MakePoint(51.96,7.96,1.0), 4326), '.$temperature.', '.$humidity.', now())');
					$event =  query('insert into events(parcel_process, geom, time_of_event) values('.$parcel_process.',ST_SetSRID(ST_MakePoint('.$lat.','.$lon.',1.0), 4326), to_timestamp('.$timestamp.')) returning events_id');
					
					$event_id = pg_fetch_assoc($event);
					
					query('insert into light_events(event_id_ref,light) values ('.$event_id['events_id'].','.$light.')');
				}else{
					echo 'No location information available';
				}
		
			}else{
				echo 'No parcel process';
			}	}
=======
				if(isset($mcc) && isset($mnc) && isset($cellId) && isset($lac)){
					$lat = 0;
					$lon = 0;
					$location = getLocationFromCell($mcc, $mnc, $cellId, $lac);
					$location = json_decode($location, true);
					$lat = $location['coordinate']['latitude'];
					$lon = $location['coordinate']['longitude'];
					
					if($lat != 0 && $lon !=0){				
						//Insert into database
						//query('insert into measurements(parcel_process, geom, temp, humidity, time_of_measurement) values('.$parcel_process.',ST_SetSRID(ST_MakePoint(51.96,7.96,1.0), 4326), '.$temperature.', '.$humidity.', now())');
						$event =  query('insert into events(parcel_process, geom, time_of_event) values('.$parcel_process.',ST_SetSRID(ST_MakePoint('.$lat.','.$lon.',1.0), 4326), to_timestamp('.$timestamp.')) returning events_id');
						
						$event_id = pg_fetch_assoc($event);
						
						query('insert into light_events(event_id_ref,light) values ('.$event_id['events_id'].','.$light.')');
					}else{
						echo 'No location information available';
					}
				}else{
					echo 'No cell id';
				}
			}else{
				echo 'No parcel process';
				error_log("no parcel process",0);
			}
			}
>>>>>>> Server Changes - final?
		}else{
			echo 'Incomplete data';
			error_log("incomplete data",0);
		}
		
	}else{
		echo 'Missing data';
		error_log("missing data",0);
	}
});

/*
curl -i -H "Accept: application/json" -X POST -d 'data=123;1357660358;02F1;262;02;010E;2;123;1357660358;02F1;262;02;010E;1.5' http://potwech.uni-muenster.de/rest/index.php/postShock
*/
$app->post('/postShock', function() use ($app){
	require('Location.php');
	
	$data = $app -> request()->post('data');
	error_log("Post Shock Data: ".$data,0);
	if($data){
		// deviceId, timestamp, cellId, mcc, mnc,lac temperature, humidity, battery
		$parsed = explode(";", $data);
		
		if((sizeof($parsed) % 7)==0){
		for ($i=0;$i<sizeof($parsed);$i=$i+7){
			$deviceId = $parsed[$i+0];
			$timestamp = $parsed[$i+1];
			$cellId = hexToStr($parsed[$i+2]);
			$mcc = $parsed[$i+3];
			$mnc = $parsed[$i+4];
			$lac = hexToStr($parsed[$i+5]);
			//has to be divided by 10
			$shock = ((double)$parsed[$i+6])/10;
			
			
			$parcel_process = null;
			$result = query('select * from current_parcel_processes where mobile_device_id = '.$deviceId.' limit 1');
			if(pg_num_rows($result) > 0){
				while($row = pg_fetch_assoc($result)){
					$parcel_process = $row['parcel_process_id'];
				}
			}
			if($parcel_process != null){
				//Convert Cell-ID to LatLon
<<<<<<< HEAD
=======
				if(isset($mcc) && isset($mnc) && isset($cellId) && isset($lac)){
>>>>>>> Server Changes - final?
				$lat = 0;
				$lon = 0;
				$location = getLocationFromCell($mcc, $mnc, $cellId, $lac);
				$location = json_decode($location, true);
				$lat = $location['coordinate']['latitude'];
				$lon = $location['coordinate']['longitude'];
				
				if($lat != 0 && $lon !=0){
				
				//Insert into database
				//query('insert into measurements(parcel_process, geom, temp, humidity, time_of_measurement) values('.$parcel_process.',ST_SetSRID(ST_MakePoint(51.96,7.96,1.0), 4326), '.$temperature.', '.$humidity.', now())');
					$event =  query('insert into events(parcel_process, geom, time_of_event) values('.$parcel_process.',ST_SetSRID(ST_MakePoint('.$lat.','.$lon.',1.0), 4326), to_timestamp('.$timestamp.')) returning events_id');
					
					$event_id = pg_fetch_assoc($event);
					
					query('insert into acceleration_events(event_id_ref,acceleration) values ('.$event_id['events_id'].','.$shock.')');
				}else{
					echo 'No location information available';
				}
<<<<<<< HEAD
		
=======
			}else{
				echo 'no cell id';
			
			}
>>>>>>> Server Changes - final?
			}else{
				echo 'No parcel process';
			}	}
		}else{
			echo 'Incomplete data';
		}
		
	}else{
		echo 'Missing data';
	}
});

/*
$app->post('/post', function () use ($app) {

	require('notifications.php');
	
	$data = $app->request()->post('data');
	error_log("POST", 0);
	if($data){
		$json = json_decode($data, true);
		
		error_log("ErrorLog: ".$data, 0);
	   
	   $device_id = $json['device_id'];
	   $parcel_process = null;
	   $result = query('select * from current_parcel_processes where mobile_device_id = '.$device_id.' limit 1');
		if(pg_num_rows($result) > 0){
			while($row = pg_fetch_assoc($result)){
				$parcel_process = $row['parcel_process_id'];
			}
		}
		if($parcel_process != null){
	   
		
		   foreach($json['measurements'] as $measurement){
				$timestamp = $measurement['timestamp'];
				//in postgres:  to_timestamp(1195374767);
			   $cellid = $measurement['cellid'];
			   $mcc = $measurement['mcc'];
			   $mnc = $measurement['mnc'];
			   $temperature = $measurement['temperature'];
			   $humidity = $measurement['humidity'];
			   $light = $measurement['light'];  
			   $acceleration_x = $measurement['acceleration']['x'];
			   $acceleration_y = $measurement['acceleration']['y'];
			   $acceleration_z = $measurement['acceleration']['z'];
			   $compass_x = $measurement['compass']['x'];
			   $compass_y = $measurement['compass']['y'];
			   $compass_z = $measurement['compass']['z'];   
			   $battery = $measurement['battery'];

				query('insert into measurements(parcel_process, geom, temp, humidity, time_of_measurement) values('.$parcel_process.',ST_SetSRID(ST_MakePoint(51.96,7.96,1.0), 4326), '.$temperature.', '.$humidity.', now())');
				
				$notificationArray = array("id" => "123", "sensors" => array("temperature" => $temperature, "humidity" => $humidity, "acceleration" => "1.5"));
				checkNotifications($parcel_process,$notificationArray);
			
			}
		}else{
			echo 'No registered parcel process';
		}
   }else{
	echo 'missing data';
   }
   
   
   
});
*/
//  curl -i -H "Accept: application/json" -X POST -d 'data={"pid":123,"settings":{"temperature":{"min":"15","max":"25"},"humidity":{"min":"60","max":"70"}}}' http://potwech.uni-muenster.de/rest/index.php/notifications_settings
$app->post('/notifications_settings', function() use($app){
	$data = $app->request()->post('data');
	
	echo $data;
	
	if($data){
		$json = json_decode(trim($data), true);
		
		$pid = $json['pid'];

		$tempMin = $json['settings']['temperature']['min'];
		$tempMax = $json['settings']['temperature']['max'];
		$humMin = $json['settings']['humidity']['min'];
		$humMax = $json['settings']['humidity']['max'];		
		$batteryMin = $json['settings']['battery']['min'];
		$batteryMax = $json['settings']['battery']['max'];		
		$accMin = $json['settings']['acceleration']['min'];
		$accMax = $json['settings']['acceleration']['max'];		
		$lightMin = $json['settings']['light']['min'];
		$lightMax = $json['settings']['light']['max'];
		
		$result = query('select * from notifications_settings where pid = '.$pid.'');
		if(pg_num_rows($result) > 0){
			query('update notifications_settings set min='.$tempMin.', max='.$tempMax.' where pid= '.$pid.' and sensor =\'temperature\'');
			query('update notifications_settings set min='.$humMin.', max='.$humMax.' where pid= '.$pid.' and sensor =\'humidity\'');
			query('update notifications_settings set min='.$batteryMin.', max='.$batteryMax.' where pid= '.$pid.' and sensor =\'battery\'');
			query('update notifications_settings set min='.$accMin.', max='.$accMax.' where pid= '.$pid.' and sensor =\'acceleration\'');
			query('update notifications_settings set min='.$lightMin.', max='.$lightMax.' where pid= '.$pid.' and sensor =\'light\'');
		}else{
			query('insert into notifications_settings(sensor, min, max, pid) VALUES(\'temperature\','.$tempMin.','.$tempMax.','.$pid.')');
			query('insert into notifications_settings(sensor, min, max, pid) VALUES(\'humidity\','.$humMin.','.$humMax.','.$pid.')');
			query('insert into notifications_settings(sensor, min, max, pid) VALUES(\'battery\','.$batteryMin.','.$batteryMax.','.$pid.')');
			query('insert into notifications_settings(sensor, min, max, pid) VALUES(\'acceleration\','.$accMin.','.$accMax.','.$pid.')');
			query('insert into notifications_settings(sensor, min, max, pid) VALUES(\'light\','.$lightMin.','.$lightMax.','.$pid.')');
		}
		
	}else{
		echo 'missing data';
	}
});



// curl -i -H "Accept: application/json" -X POST -d 'data={"temperature":22}' http://potwech.uni-muenster.de/rest/index.php/post2
//{"temperature":22}
$app->post('/post2', function () use ($app) {
	
	$data = $app->request()->post('data');
	error_log("POST", 0);
	if($data){
		$json = json_decode($data, true);
		
		error_log("ErrorLog: ".$data, 0);
	   
	   $temp = $json['temperature'];

				query('insert into measurements(parcel_process, geom, temp, humidity, time_of_measurement) values(5,ST_SetSRID(ST_MakePoint(51.96,7.96,1.0), 4326), '.$temp.', 50, now())');
	
		
	}else{
		echo 'missing data';
	}
});

function hexToStr($hex)
{
/*
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2)
    {
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
*/
	return hexdec($hex);
}






$app->run();

?>
