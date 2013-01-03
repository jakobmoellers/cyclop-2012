<?php
//the REST API
require 'Slim/Slim.php';
//db information and a more easy query function
require '../includes/db_func.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();


//get all measurements of a parcel_process
$app->get('/parcel_measurements/:id', function ($id) {
    $result = query("select measurement_id,temp, humidity,time_of_measurement, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height from measurements where parcel_process = ".$id);
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
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
	
	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
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
			array_push($json['features'],$point); 	
		}		
	header('Content-Type: application/json');
	echo json_encode($json);
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

$app->get('/parcel/', function(){
	echo 'blub';
});

/*
    curl -i -H "Accept: application/json" -X POST -d 'data={"device_id":123456,"measurements":[{"timestamp":"15446787","cellid":"109128739432","mcc":"123456","mnc":"123456","temperature":30,"humidity":"80","light":"1","acceleration":{"x":0,"y":0,"z":0},"compass":{"x":0,"y":0,"z":0},"battery":78},{"timestamp":"154543787","cellid":"109128739432","mcc":"123456","mnc":"123456","temperature":"30","humidity":"70","light":"0","acceleration":{"x":0,"y":0,"z":0},"compass":{"x":0,"y":0,"z":0},"battery":76}]}' http://potwech.uni-muenster.de/rest/testIndex.php/post      
	
	Mit Error:
	
	curl -i -H "Accept: application/json" -X POST -d 'data={"device_id":154742364,"measurements":[{"timestamp":"15446787","cellid":"109128739432","mcc":"123456","mnc":"123456","humidity":"80","light":"1","acceleration":{"x":0,"y":0,"z":0},"compass":{"x":0,"y":0,"z":0},"battery":78},{"timestamp":"154543787","cellid":"109128739432","mcc":"123456","mnc":"123456","humidity":"70","light":"0","acceleration":{"x":0,"y":0,"z":0},"compass":{"x":0,"y":0,"z":0},"battery":76}]}' http://potwech.uni-muenster.de/rest/testIndex.php/post  

*/

$app->post('/initParcel', function () use ($app) {
	$query = 'insert into parcel_processes(user_id_ref, mobile_device_id, package_number, start_time) values('.$app->request()->post('uid').','.$app->request()->post('mobile_device').','.$app->request()->post('parcel_number').',now())';
	query($query);
});

$app->post('/testPost', function () use ($app) {
   echo $app->request()->post('data');
});

$app->post('/post', function () use ($app) {
	
	require('notifications.php');
	
	$data = $app->request()->post('data');
	$json = json_decode($data, true);
   
	$device_id = $json['device_id'];
   	$parcel_process = null;
	$result = query('select * from current_parcel_processes where mobile_device_id = '.$device_id.' limit 1');
	if(pg_num_rows($result) > 0){
		while($row = pg_fetch_assoc($result)){
			$parcel_process = $row['parcel_process_id'];
		}
	}
   
   
	
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
		
		
		$notificationArray = array("id" => "123", "sensors" => array("temperature" => $temperature, "humidity" => $humidity, "acceleration" => "1.5"));
		checkNotifications($parcel_process,$notificationArray);
		
		//Convert Cell-ID to LatLon
		$xml = simplexml_load_file('http://www.opencellid.org/cell/get?mnc='.$mnc.'&mcc='.$mcc.'&cellid='.$cellid);
		
		$lat = 0;
		$lon = 0;
		$lat = $xml->cell['lat'];
		$lon = $xml->cell['lon'];
		
		query('insert into measurements(parcel_process, geom, temp, humidity, time_of_measurement) values(1,ST_SetSRID(ST_MakePoint('.$lat.','.$lon.',1.0), 4326), '.$temperature.', '.$humidity.', to_timestamp('.$timestamp.'))');
   }
});

$app->post('/parcel', function () use ($app) {
   insertIntoDb($app->request()->post('id'));
});

$app->run();


function getFromDb($id){
	$query = "SELECT * from mobile_device where id = '$id'"; 
	$result = query($query);

	echo var_dump($result) . "\n";
}

function insertIntoDb($id){
	$query = "INSERT INTO mobile_device VALUES('$id')"; 
	query($query);

}
?>