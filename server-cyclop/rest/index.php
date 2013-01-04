<?php
//the REST API
require 'Slim/Slim.php';
//db information and a more easy query function
require '../includes/db_func.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/hazards(/:bikeId)', function ($bikeId=null) {
	if($bikeId != null){
		$result = query("select \"hazardId\", timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from hazards where \"mobile_dev_ref\" = ".$bikeId);
	}else{
		$result = query("select \"hazardId\",timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from hazards");
	}

	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['hazardId'] = $row['hazardId'];
			$point['properties']['timestamp'] = $row['timestamp'];
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


$app->get('/measurements(/:bikeId)', function ($bikeId=null) {
	if($bikeId != null){
		$result = query("select \"measurementId\", temperature, humidity, light, noise,timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where \"deviceId\" = ".$bikeId);
	}else{
		$result = query("select \"measurementId\", temperature, humidity, light, noise,timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements");
	}

	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurementId'] = $row['measurementId'];
			$point['properties']['temperature'] = $row['temperature'];
			$point['properties']['humidity'] = $row['humidity'];
			$point['properties']['noise'] = $row['noise'];
			$point['properties']['light'] = $row['light'];
			$point['properties']['timestamp'] = $row['timestamp'];
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

$app->get('/measurements/after/:timeafter(/:bikeId)', function ($timeafter,$bikeId=null) {
	if($bikeId != null){
		$result = query("select \"measurementId\", temperature, humidity, light, noise,timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where (timestamp>'".$timeafter."') AND (\"deviceId\" = ".$bikeId.")");
	}else{
		$result = query("select \"measurementId\", temperature, humidity, light, noise,timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where timestamp>'".$timeafter."'");
	}

	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurementId'] = $row['measurementId'];
			$point['properties']['temperature'] = $row['temperature'];
			$point['properties']['humidity'] = $row['humidity'];
			$point['properties']['noise'] = $row['noise'];
			$point['properties']['light'] = $row['light'];
			$point['properties']['timestamp'] = $row['timestamp'];
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

$app->get('/measurements/before/:timebefore(/:bikeId)', function ($timebefore,$bikeId=null) {
	if($bikeId != null){
		$result = query("select \"measurementId\", temperature, humidity, light, noise,timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where (timestamp<'".$timebefore."') AND (\"deviceId\" = ".$bikeId.")");
	}else{
		$result = query("select \"measurementId\", temperature, humidity, light, noise,timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where timestamp<'".$timebefore."'");
	}

	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurementId'] = $row['measurementId'];
			$point['properties']['temperature'] = $row['temperature'];
			$point['properties']['humidity'] = $row['humidity'];
			$point['properties']['noise'] = $row['noise'];
			$point['properties']['light'] = $row['light'];
			$point['properties']['timestamp'] = $row['timestamp'];
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

$app->get('/measurements/interval/:timeafter/:timebefore(/:bikeId)', function ($timeafter,$timebefore,$bikeId=null) {
	if($bikeId != null){
		$result = query("select \"measurementId\", temperature, humidity, light, noise,timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where (timestamp<'".$timebefore."') AND (timestamp>'".$timeafter."') AND (\"deviceId\" = ".$bikeId.")");
	}else{
		$result = query("select \"measurementId\", temperature, humidity, light, noise,timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where (timestamp<'".$timebefore."') AND (timestamp>'".$timeafter."')");
	}

	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurementId'] = $row['measurementId'];
			$point['properties']['temperature'] = $row['temperature'];
			$point['properties']['humidity'] = $row['humidity'];
			$point['properties']['noise'] = $row['noise'];
			$point['properties']['light'] = $row['light'];
			$point['properties']['timestamp'] = $row['timestamp'];
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

$app->get('/last_position/:bikeId', function ($bikeId) {
	
	$result = query("select \"measurementId\", ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where \"deviceId\" = ".$bikeId." order by \"measurementId\" DESC Limit 1");
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurementId'] = $row['measurementId'];
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

$app->get('/mobile_devices/:userId', function ($userId) {
	
	$result = query("select \"deviceId\",devicename from mobile_devices where owner = ".$userId);
	
	$json = array();
	$json['devices'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$id = array();
			$id['id'] = $row['deviceId'];
			$id['devicename'] = $row['devicename'];
			array_push($json['devices'],$id); 	
		}	
	header('Content-Type: application/json');
	echo json_encode($json);
});

$app->get('/boundingbox/measurements/:lat1/:lon1/:lat2/:lon2', function ($lat1,$lon1,$lat2,$lon2) {
	
	$result = query("select \"measurementId\", temperature, humidity, light, noise,timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where geom && ST_MakeEnvelope(".$lat1.", ".$lon1.", ".$lat2.", ".$lon2.", 4326)");

	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurementId'] = $row['measurementId'];
			$point['properties']['temperature'] = $row['temperature'];
			$point['properties']['humidity'] = $row['humidity'];
			$point['properties']['noise'] = $row['noise'];
			$point['properties']['light'] = $row['light'];
			$point['properties']['timestamp'] = $row['timestamp'];
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

$app->get('/boundingbox/hazards/:lat1/:lon1/:lat2/:lon2', function ($lat1,$lon1,$lat2,$lon2) {
	
	$result = query("select \"hazardId\", timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from hazards where geom && ST_MakeEnvelope(".$lat1.", ".$lon1.", ".$lat2.", ".$lon2.", 4326)");

	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['hazardId'] = $row['hazardId'];
			$point['properties']['timestamp'] = $row['timestamp'];
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


$app->get('/sensor/:sensor(/:bikeId)', function ($sensor, $bikeId=null) {
    if($bikeId != null){
		$result = query("select \"measurementId\", ".$sensor.",timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where \"deviceId\" = ".$bikeId);
	}else{
		$result = query("select \"measurementId\", ".$sensor.",timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements");
	}
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['measurementId'] = $row['measurementId'];
			$point['properties'][$sensor] = $row[$sensor];
			$point['properties']['timestamp'] = $row['timestamp'];
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


/*
    curl -i -H "Accept: application/json" -X POST -d "id=2" http://giv-cyclop.uni-muenster.de/rest/index.php/cyclop

*/

$app->post('/cyclop', function () use ($app) {
   echo $app->request()->post('data');
   
});

$app->post('/analysis_return', function () use ($app) {
   echo $app->request()->post('data');

	$res = $app->response();

	// Overwrite response body
	$res->body('Connection established.');

	// Append response body
	$res->write('Ready to receive data.');
   
});

/*
	curl -i -H "Accept: application/json" -X POST -d 'uid=2&mobile_device=2&name=test' http://giv-cyclop.uni-muenster.de/rest/index.php/change_device_name 
*/

$app->post('/change_device_name', function () use ($app) {
	$uid = utf8_encode($app->request()->post('uid'));
	$mobile_device = utf8_encode($app->request()->post('mobile_device'));
	$name = utf8_encode($app->request()->post('name'));

	if($uid && $mobile_device && $name){
		$query = "update mobile_devices set devicename='$name' where ((\"deviceId\"=$mobile_device) AND (owner=$uid))";
		query($query);
	}else{
		echo 'missing data';
	}
});

/*
	curl -i -H "Accept: application/json" -X POST -d 'uid=2&secret_key=5647&name=test' http://giv-cyclop.uni-muenster.de/rest/index.php/add_new_device
*/

$app->post('/add_new_device', function () use ($app) {
	$uid = utf8_encode($app->request()->post('uid'));
	$secret_key = utf8_encode($app->request()->post('secret_key'));
	$name = utf8_encode($app->request()->post('name'));

	if($uid && $secret_key && $name){
		$query = "insert into mobile_devices (\"secretKey\",\"owner\",devicename) values ($secret_key,$uid,'$name')";
		query($query);
	}else{
		echo 'missing data';
	}
});

/*TODOs: 
-Hazard-Infos im Post response body
-Posts für hazards*/

/*
curl -i -H "Accept: application/json" -X POST -d 'data=1;2012-01-01;51.1;7.9;5.0;30;50;8;10;1234' http://giv-cyclop.uni-muenster.de/rest/index.php/postMeasurement
*/
$app->post('/postMeasurement', function() use ($app){
	$data = $app -> request()->post('data');
	error_log("Post Data: ".$data,0);
	echo 'Stuff: '.$data;
	if($data){
		// deviceId, timestamp, lat, lon, height, temperature, humidity, light, noise, secretkey
		$parsed = explode(";", $data);
		
		if(sizeof($parsed) == 10){
			$deviceId = $parsed[0];
			$timestamp = $parsed[1];
			$lat = $parsed[2];
			$lon = $parsed[3];
			$height = $parsed[4];
			$temperature = $parsed[5];
			$humidity = $parsed[6];
			$light = $parsed[7];
			$noise = $parsed[8];
			$key = $parsed[9];
			
			$db_key = null;
			$result = query('select * from mobile_devices where mobile_devices."deviceId" = '.$deviceId.' limit 1');
			if(pg_num_rows($result) > 0){
				while($row = pg_fetch_assoc($result)){
					$db_key = $row['secretKey'];
				}
			}
			if($key==$db_key){
				//Convert Cell-ID to LatLon
				/*$xml = simplexml_load_file('http://www.opencellid.org/cell/get?mnc='.$mnc.'&mcc='.$mcc.'&cellid='.$cellId);
				
				$lat = 0;
				$lon = 0;
				$lat = $xml->cell['lat'];
				$lon = $xml->cell['lon'];*/
				
				//Insert into database
				query('insert into measurements(timestamp, geom, "deviceId", temperature, humidity, light, noise) values(\''.$timestamp.'\'::timestamp,ST_SetSRID(ST_MakePoint('.$lat.','.$lon.','.$height.'), 4326),'.$deviceId.','.$temperature.','.$humidity.','.$light.','.$noise.')');
				
				//echo $lat.'  '.$lon;
		
			}else{
				echo 'Keys did not match';
			}	
		}else{
			echo 'Incomplete data';
		}
		
	}else{
		echo 'Missing data';
	}
	

});

/*$app->post('/post_measurements', function () use ($app) {

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
   
   
   
});*/

$app->run();


?>
