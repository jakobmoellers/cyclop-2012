<?php
//the REST API
require 'Slim/Slim.php';
//db information and a more easy query function
require '../includes/db_func.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/hazards(/:bikeId)', function ($bikeId=null) {
	if($bikeId != null){
		$result = query("select \"hazardId\", timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height, description from hazards where \"mobile_dev_ref\" = ".$bikeId);
	}else{
		$result = query("select \"hazardId\",timestamp, ST_X(geom) as lat, ST_Y(geom) as lon, ST_Z(geom) as height, description from hazards");
	}
	error_log("GET hazards: ",0);

	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['hazardId'] = $row['hazardId'];
			$point['properties']['timestamp'] = $row['timestamp'];
			$point['properties']['description']=$row['description'];
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

$app->get('/hazards_csv(/:bikeId)', function ($bikeId=null) {
	if($bikeId != null){
		$result = query("select \"hazardId\", timestamp, distinct ST_X(geom) as lat, distinct ST_Y(geom) as lon, ST_Z(geom) as height, description from hazards where \"mobile_dev_ref\" = ".$bikeId);
	}else{
		$result = query("select \"hazardId\",timestamp, ST_X(geom) as lat,ST_Y(geom) as lon, ST_Z(geom) as height, description from hazards");
	}
	
	$returnString = "";
			
		while($row = pg_fetch_assoc($result)){
			$returnString .= $row['lat'].','.$row['lon'].';';	
			//echo($row['lat'].','.$row['lon'].'<br>');	
		}	
	//header('Content-Type: application/json');
	echo substr($returnString, 0, strlen($returnString)-1);
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
	
	//order by reingebaut, damit die ids immer in der gleichen reihenfolge ausgegeben werden (ger)
	//theft_monitoring_enabled eingebaut
	$result = query("select \"deviceId\",devicename,theft_monitoring_enabled from mobile_devices where owner = ".$userId." order by \"deviceId\"");
	
	$json = array();
	$json['devices'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$id = array();
			$id['id'] = $row['deviceId'];
			$id['devicename'] = $row['devicename'];
			$id['theft_monitoring'] = ($row['theft_monitoring_enabled']=='1' ? true : false);
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

$app->get('/hazards_within/:lat/:lon/:distance', function ($lat, $lon, $distance) {
	$distance = $distance * 0.009;
	
	$result = query('select "hazardId", timestamp, description, st_x(geom) as lat, st_y(geom) as lon, st_z(geom) as height from hazards where ST_Dwithin(geom, ST_SetSRID(ST_MakePoint('.$lat.','.$lon.',0), 4326), '.$distance.')');

	$json = array();
	$json['type'] = 'FeatureCollection';
	$json['features'] = array();
		
		while($row = pg_fetch_assoc($result)){
			$point = array();
			$point['type'] = 'Feature';
			$point['properties'] = array();
			$point['properties']['hazardId'] = $row['hazardId'];
			$point['properties']['timestamp'] = $row['timestamp'];
			$point['properties']['description'] = $row['description'];
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


//<Dreisterweise von Gerald hier rein gecodet! ha!>

/*
	curl -i -H "Accept: application/json" -X POST -d 'mobile_device=2&secret_key=5647' http://cyclop.uni-muenster.de/rest/index.php/unregister_device
*/

$app->post('/unregister_device', function () use ($app) {
	$secret_key = utf8_encode($app->request()->post('secret_key'));
	$device_id = utf8_encode($app->request()->post('mobile_device'));

	if($device_id && $secret_key){
		$query = "UPDATE mobile_devices SET owner = null WHERE (\"secretKey\" = '$secret_key' AND \"deviceId\" = $device_id)";
		query($query);
	}else{
		echo 'missing data';
	}
});

$app->get('/get(/bike)', function ($bikeId=null) {
	echo '&nbsp;&nbsp;&nbsp;o<br>'.PHP_EOL;
	echo '(<=.<br>'.PHP_EOL;
	echo '()&nbsp;\'&nbsp;()<br>'.PHP_EOL;
});

//get thefts for bike id 

$app->get('/theft_events/:bikeId', function ($bikeId) {
	
	$sql = 'SELECT to_char(timestamp, \'MM-DD-IYYY HH24:MI:SS\') as timestamp,mobile_dev_ref,ST_X(geom) as lat, ST_Y(geom) as lon FROM theft_alerts WHERE mobile_dev_ref = '.$bikeId.' ORDER BY timestamp desc';
	/*
	 *
	 * Introduce a 'theft not valid' column in the table?
	 * Or mark as returned
	 *
	 *
	 */

	$result = query($sql);
	header('Content-Type: application/json');
	
	if(pg_num_rows($result) == 0){
		echo json_encode(array('thefts' => 'no_thefts'));
	}else{
	
		$json = array();
		$json['thefts']['device_id'] = $bikeId;
		//device_id is the 
		
		$locations['type'] = 'FeatureCollection';
		$locations['features'] = array();
		
			while($row = pg_fetch_assoc($result)){
				$point = array();
				$point['type'] = 'Feature';
				$point['properties'] = array();
				$point['properties']['timestamp'] = $row['timestamp'];	
				$point['geometry'] = array();
				$point['geometry']['type'] = 'Point';
				$point['geometry']['coordinates'] = array();
				array_push($point['geometry']['coordinates'],floatval($row['lon']));
				array_push($point['geometry']['coordinates'],floatval($row['lat']));
				array_push($locations['features'],$point);
				//array_push($json['thefts'],$theft); 	
			}	
		$json['thefts']['locations'] = $locations;
		echo json_encode($json);
	}
	
});

//post for deletion of thefts
/*
	curl -i -H "Accept: application/json" -X POST -d 'mobile_device=3&secret_key=5647' http://cyclop.uni-muenster.de/rest/index.php/delete_theft_alerts
*/

$app->post('/delete_theft_alerts', function () use ($app) {
	$secret_key = utf8_encode($app->request()->post('secret_key'));
	$device_id = utf8_encode($app->request()->post('mobile_device'));
	
	if($device_id && $secret_key){
		$query = "DELETE FROM theft_alerts WHERE mobile_dev_ref = (SELECT \"deviceId\" FROM mobile_devices WHERE (\"secretKey\" = '$secret_key' AND \"deviceId\" = $device_id))";
		//echo $query;
		query($query);
	}else{
		echo 'missing data';
	}
});


//enable/disable theft monitoring
/*
	curl -i -H "Accept: application/json" -X POST -d 'mobile_device=3&state=true' http://cyclop.uni-muenster.de/rest/index.php/set_theft_monitoring
*/
$app->post('/set_theft_monitoring', function () use ($app) {
	$device_id = utf8_encode($app->request()->post('mobile_device'));
	$theft_enabled = (utf8_encode($app->request()->post('state'))=='true' ? '1' : '0');

						//wtf php is weird!
	if($device_id && ($theft_enabled || $theft_enabled == '0')){
		$query = "update mobile_devices set theft_monitoring_enabled = $theft_enabled where (\"deviceId\"=$device_id)";
		query($query);
	}else{
		echo 'missing data '.$theft_enabled;
	}
});

//</Dreister code Ende>

/*
curl -i -H "Accept: application/json" -X POST -d 'data=true;1357734361;238;345;3;52;7;25;50;1337;100;43;5;true;1357734361;238;345;3;52;7;25;50;1337;100;43;5' http://giv-cyclop.uni-muenster.de/rest/index.php/postMeasurement 
*/

// rain, timestamp, no2, co, g, lat, lon, temperature, humidity, secretKey, dust, noise, deviceID
$app->post('/postMeasurement', function() use ($app){
	$data = $app -> request()->post('data');
	error_log("Post Data: ".$data,0);
	//echo 'Stuff: '.$data;
	if($data){
		$parsed = explode(";", $data);
		
		$max = 13;
		if((sizeof($parsed) % $max)==0){
		for ($i=0;$i<sizeof($parsed);$i=$i+13){
			$rain = $parsed[$i+0];
			$timestamp = $parsed[$i+1];
			$no2 = $parsed[$i+2];
			$co = $parsed[$i+3];
			$g = $parsed[$i+4];
			$lat = $parsed[$i+5];
			$lon = $parsed[$i+6];
			$temperature = $parsed[$i+7];
			$humidity = $parsed[$i+8];
			$key = $parsed[$i+9];
			$dust = $parsed[$i+10];
			$noise = $parsed[$i+11];
			$deviceId = $parsed[$i+12];

			
			
			$db_key = null;
			$result = query('select * from mobile_devices where mobile_devices."deviceId" = '.$deviceId.' limit 1');
			if(pg_num_rows($result) > 0){
				while($row = pg_fetch_assoc($result)){
					$db_key = $row['secretKey'];
				}
			}
			if($key==$db_key){

				//Insert into database   // rain, timestamp, no2, co, g, lat, lon, temperature, humidity, secretKey, dust, noise, deviceID
					query('insert into measurements(timestamp, geom, "deviceId", temperature, humidity, noise, rain, no2, co, g, dust) values(to_timestamp('.$timestamp.'),ST_SetSRID(ST_MakePoint('.$lat.','.$lon.',0), 4326),'.$deviceId.','.$temperature.','.$humidity.','.$noise.','.$rain.','.$no2.','.$co.','.$g.','.$dust.')');
				
				if($i == $max){
					$hazards = query('select st_x(geom) as lat, st_y(geom) as lon from hazards where ST_Dwithin(geom, ST_SetSRID(ST_MakePoint('.$lat.','.$lon.'), 4326), 0.02)');
					
					$hazardReturn = "";
					while($row = pg_fetch_assoc($hazards)){
						$hazardReturn .= $row['lat'].','.$row['lon'].';';
					}
					echo substr($hazardReturn, 0, strlen($hazardReturn)-1);
				}
			}else{
				echo 'failure: wrong key';
				} 
			}	
		}else{
			echo 'failure: wrong size';
		}	
	}else{
		echo 'failure: no data';
	}
	

});

/*
curl -i -H "Accept: application/json" -X POST -d 'data=hazard;1357734361;51.1;7.9;1337;5;hazard;1357734361;51.1;7.9;1337;5' http://giv-cyclop.uni-muenster.de/rest/index.php/postHazard 
*/

//unsinn,timestamp, lat, lon, secretkey, devideId
$app->post('/postHazard', function() use ($app){
	$data = $app -> request()->post('data');
	error_log("Post Hazard Data: ".$data,0);
	//echo 'Stuff: '.$data;
	if($data){
		$parsed = explode(";", $data);
		
		$max = 6;
		if((sizeof($parsed) % $max)==0){
		for ($i=0;$i<sizeof($parsed);$i=$i+6){
			$unsinn = $parsed[$i+0];
			$timestamp = $parsed[$i+1];
			$lat = $parsed[$i+2];
			$lon = $parsed[$i+3];
			$key = $parsed[$i+4];
			$deviceId = $parsed[$i+5];
			
			$db_key = null;
			$result = query('select * from mobile_devices where mobile_devices."deviceId" = '.$deviceId.' limit 1');
			if(pg_num_rows($result) > 0){
				while($row = pg_fetch_assoc($result)){
					$db_key = $row['secretKey'];
				}
			}
			if($key==$db_key){

				//Insert into database
					query('insert into hazards(timestamp, geom, mobile_dev_ref) values(to_timestamp('.$timestamp.'),ST_SetSRID(ST_MakePoint('.$lat.','.$lon.',0), 4326),'.$deviceId.')');
				
			}else{
				echo 'failure';
			} 
		}	
		}else{
			echo 'failure';
		}	
	}else{
		echo 'failure';
	}
	

});

$app->run();


?>
