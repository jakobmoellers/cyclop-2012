<?
//Redirects to a page without POST data to avoid annoying confirm resending data messages of your browser.
if (isset($_POST['sumo_user'])) {
	$avoidPostback = true;
}
require 'sumo/sumo.php';
require 'includes/db_func.php';
$user_id = $SUMO['user']['id'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
	  <?
		if ($avoidPostback) {
			echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'">';
		}
	  ?>
    <meta charset="utf-8">
    <title>POTWECH - Logistics Company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="'The Parcel on the Web Challenge' a course at ifgi 2012">
    <meta name="author" content="Dennis, Gerald, Jakob, Jan, Maurin, Morin, Niels">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
	<link rel="stylesheet" href="../includes/leaflet/leaflet.css" />
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="../includes/leaflet/leaflet.ie.css" />
    <![endif]-->
     <script src="../includes/jquery/jquery-1.8.3.min.js"></script>
    <style>

    footer{
		margin-top: 100px;
	}
	
	
	#map{
		height: 500px;
		width: 100%;
	}
	
    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>



    <!-- NAVBAR
    ================================================== -->
    <!-- Wrap the .navbar in .container to center it on the page and provide easy way to target it with .navbar-wrapper. -->
    <div class="container navbar-wrapper">

      <div class="navbar navbar-inverse">
        <div class="navbar-inner">
          <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="http://potwech.uni-muenster.de">POTWECH</a>
          <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="insurance_company.php">Overview</a></li>
              <li><a href="?sumo_action=logout">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!-- /.navbar-inner -->
      </div><!-- /.navbar -->
    </div><!-- /.container -->

    <div class="container">
	<div id="map"></div>
 <!--       <div id="slider" style="width:200px">
            <div class="ui-slider-handle"></div> -->
        </div>
        <script src="../includes/leaflet/leaflet-src.js"></script>
        <script>
            var marker = new Array();
			var maxValue = 0;

            function initMap() {
                map = L.map('map').setView([51.966667, 7.633333], 6);

                L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
                }).addTo(map);


             }

			
			function callback(feature, latlng){    
				var myIcon = L.icon({ 
					iconUrl: 'images/parcel_kleiner.png'
				});

				marker = L.marker(latlng, {icon: myIcon});
				return marker; 
			}  
				
			function onEachFeature(feature, layer) {
				// does this feature have a property named popupContent?
				if (feature.properties) {
					console.log("jow");
					layer.bindPopup("Measurement ID: "+feature.properties.measurement_id);
				}
			}

			
            function loadAllParcels() {
                $.getJSON("rest/index.php/current_parcels/", function (json) {
					for(i = 0; i < json.parcels.length; i++){
						$.getJSON("rest/index.php/latest_parcel/"+json.parcels[i].parcel_process, function (latest_parcels){
							L.geoJson(latest_parcels, {
								onEachFeature: onEachFeature
							}).addTo(map);
							
						});
					
					}
				   
				   // L.geoJson(json).addTo(map);
                });
            }

            $(document).ready(function () {
                initMap();
                //fetchGeoJson(1);
                loadAllParcels();
            });

        </script>
		
		<br>
	
      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2012 ifgi</p>
      </footer>

    </div><!-- /.container -->



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery-1.8.3.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
  </body>
</html>
