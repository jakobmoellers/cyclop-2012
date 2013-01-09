<?
ini_set('user_agent', 
  'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
/*
Test Curl 

curl -i -H "Accept: application/json" -X POST -d 'mcc=262&mnc=02&lac=270&cellId=753' https://www.enaikoon.de/gpsSuiteCellId/gsmCell/service/query/tower


*/
//echo getLocationFromCell(262,02,753,270);



function getLocationFromCell($mcc, $mnc, $cellId, $lac){
	// Submit those variables to the server
	$post_data = array(
	'mcc' => $mcc,
	'mnc' => $mnc,
	'cellId' => $cellId,
	'lac' => $lac
	);

	 
	// Build Http query using params
	$query = http_build_query ($post_data);
	 
	// Create Http context details
	// $contextData = array ( 
					// 'method' => 'POST',
					// 'header' => "Connection: close\r\n",
								// "Content-Length: ".strlen($query)."\r\n",
								// 'Content-Type: text/html\r\n',
					// 'content'=> $query );
					
	$contextData = array ( 
					'method' => 'POST',
					'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Content-Length: " . strlen($query) . "\r\n"."Connection: close\r\n",
					'content'=> $query );
	 
	// Create context resource for our request
	$context = stream_context_create (array ( 'http' => $contextData ));
	 
	// Read page rendered as result of your POST request
	$result =  file_get_contents (
					  'https://www.enaikoon.de/gpsSuiteCellId/gsmCell/service/query/tower',  // page url
					  false,
					  $context);
	 
	// Server response is now stored in $result variable so you can process it
	return $result;
}
?>