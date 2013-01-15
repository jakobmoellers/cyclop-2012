<?
require 'sumo/sumo.php';
require 'includes/db_func.php';
if (isset($_POST['sumo_user'])) {
	$avoidPostback = true;
}
$user_id = $SUMO['user']['id'];
$user_group = $SUMO['user']['group'][0];
$parcel_id = $_GET['pid'];

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
    <title>POTWECH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }

      html, body{
		width:100%;
		height:100%;
		margin:0;
	  }
	  
	  #map {
          width: 100%;
          height: 20%;
          margin-bottom: 50px;
          
      }
	  
	  #slider{
		margin-right:10px;
		margin-top:10px;
	  }
	  
      img {max-width:none}
	  
	.leaflet-control-zoom-fullscreen { background-image: url(../includes/leaflet/leaflet-fullscreen/icon-fullscreen.png); }
	.leaflet-control-zoom-fullscreen.last { margin-top: 5px }
	#map:-webkit-full-screen { width: 100% !important; height: 100% !important; }
	#map:-moz-full-screen { width: 100% !important; height: 100% !important; }
	#map:full-screen { width: 100% !important; height: 100% !important; }
		
    </style>
	<link rel="stylesheet" href="../includes/leaflet/leaflet.css" />
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="../includes/leaflet/leaflet.ie.css" />
    <![endif]-->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"type="text/css">
    <script src="../includes/jquery/jquery-1.8.3.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>
  
   <script src="../includes/leaflet/leaflet-src.js"></script>
   <script src="../includes/leaflet/leaflet-fullscreen/Control.FullScreen.js"></script>
    <script>
            var marker = new Array();
			var maxValue = 0;

            function initMap() {
                map = L.map('map').setView([51.966667, 7.633333], 6);

                L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
                }).addTo(map);
				var fullScreen = new L.Control.FullScreen(); 
				map.addControl(fullScreen);
				
				var SliderControl = L.Control.extend({
					options: {
						position: 'topright'
					},

					onAdd: function (map) {
						// create the control container with a particular class name
						var container = L.DomUtil.create('div', 'slider');
						$(container).append('<div id="slider" style="width:200px"><div class="ui-slider-handle"></div><div id="slider-timestamp" style="width:200px; margin-top:10px;background-color:#FFFFFF"></div></div>');
						//$(container).append(<
						$(container).mousedown(function() {map.dragging.disable(); });
						$(document).mouseup(function() {
							map.dragging.enable();
							$('#slider-timestamp').html('');
							
						});

						// ... initialize other DOM elements, add listeners, etc.

						return container;
					}
				});

				map.addControl(new SliderControl());
				
				$("#slider").hide();
             }

            function fetchGeoJson(parcelid) {
                //$.ajax({url: "rest/index.php/parcel_events/"+parcelid})
                $.getJSON("../rest/index.php/distinct_parcel_measurements/" + parcelid, function (json) {
                    //L.geoJson(json).addTo(map)
					
					if(json.features.length > 0){
						//var geojsonMarker = L.geoJson(json.features[0],{pointToLayer: callback}).addTo(map);
						var geojsonMarker = L.geoJson(json.features[json.features.length-1]).addTo(map);
						map.setView([json.features[json.features.length-1].geometry.coordinates[1],json.features[json.features.length-1].geometry.coordinates[0]], 10);
						
						setParcelOverview(json.features[json.features.length-1]);
						
						geojsonMarker.bindPopup("Measurement ID: "+json.features[json.features.length-1].properties.measurement_id+"<br>Time: "+json.features[json.features.length-1].properties.time);
						maxValue = json.features.length-1;
						$("#slider").show();
						$("#slider").slider({
							value: json.features.length-1,
							min:0,
							max:json.features.length-1,
							step:1,
							slide: function (e, ui){ 
								$('#slider-timestamp').html(json.features[ui.value].properties.time.substr(0,19));
								
								if(ui.value < maxValue){
									marker[ui.value] = L.geoJson(json.features[ui.value]);
									marker[ui.value].bindPopup("Measurement ID: "+json.features[ui.value].properties.measurement_id+"<br>Time: "+json.features[ui.value].properties.time);
									map.addLayer(marker[ui.value]);
									maxValue--;
								}
								
								
								if(ui.value > maxValue){
									map.removeLayer(marker[maxValue]);
									maxValue++;
								}

							}
						})
					}
                });
            }
			
			function callback(feature, latlng){    
				var myIcon = L.icon({ 
					iconUrl: 'images/parcel_kleiner.png'
				});

				marker = L.marker(latlng, {icon: myIcon});
				return marker; 
			}  

			
            function loadAllParcels() {
                $.getJSON("../rest/index.php/current_parcels/", function (json) {
                    console.log(json.parcels[0].parcel_process);
                });
            }

            $(document).ready(function () {
                initMap();
				//Load the measurements for a parcel id
                fetchGeoJson($_GET(['pid']));
				
            });
			
			//Displays the last measurements (humidity, temperature) and Events (Light, Shock) as overviews
			function setParcelOverview(feature){
				$('#tempHumForm').text(feature.properties.temperature+' °C / '+feature.properties.humidity+' %');
				$('#batteryForm').text("  "+feature.properties.battery+' %');
				
				$.getJSON("../rest/index.php/maxValue/"+$_GET(['pid'])+"/light", function (json) {
					if(json.properties != null){
						$('#lightForm').text('Opening detected!');
					}else{
						$('#light_button').addClass("disabled");
						$('#light_button').click(function(Event) {
							Event.preventDefault();
						});
					}
                });				
				$.getJSON("../rest/index.php/maxValue/"+$_GET(['pid'])+"/acceleration", function (json) {
					if(json.properties != null){
						$('#shockForm').text("  "+json.properties.maxacceleration+" g");
					}else{
						$('#acc_button').addClass("disabled");
						$('#acc_button').click(function(Event) {
							Event.preventDefault();
						});
					}
                });

			}
			
			//Javascript function to receive GET variables (Syntax: $_GET(['variableName']) ) 
			(function(){
				var s = window.location.search.substring(1).split('&');
				if(!s.length) return;
				var c = {};
				for(var i  = 0; i < s.length; i++)  {
					var parts = s[i].split('=');
					c[unescape(parts[0])] = unescape(parts[1]);
				}
				window.$_GET = function(name){return name ? c[name] : c;}
			}())
    </script>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="../index.php">POTWECH</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="javascript:history.go(-1)">Back</a></li>
			  <?
				if($user_group == "private_customers"){
					echo '<li><a href="notifications.php?pid='.$parcel_id.'">Alert Settings</a></li>';
					echo '<li><a href="initializePotwech.php">New POTWECH</a></li>';
				}
			  ?>
			  <li><a href="?sumo_action=logout">Logout</a></li>
<!--
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
-->
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div id="map">
	</div>
	

    <div class="container marketing">
	
	<div class="container marketing">
      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class="span4 offset2">
          <img class="img-rounded" src="../assets/img/temperature.png" style="width:140px; height:140px;"><h3 style="display:inline;" id="tempHumForm"></h3>
          <h2>Temperature/ Humidity (cur.)</h2>
          <!-- <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</p> -->
          <p><a class="btn" href='graphs.php?temperature=1&humidity=1&pid=<?php echo $_GET['pid']?>'>View details &raquo;</a></p>
        </div><!-- /.span4 -->
		
		<div class="span4 offset1">
          <img class="img-rounded" src="../assets/img/battery.svg" style="width:140px; height:140px;"><h3 style="display:inline;" id="batteryForm"></h3>
          <h2>Battery</h2>
          <p><a class="btn" href='graphs.php?battery=1&pid=<?php echo $_GET['pid']?>'>View details &raquo;</a></p>
        </div><!-- /.span4 -->
		</div>
	<div class="row">
        <div class="span4 offset2">
          <img class="img-rounded" src="../assets/img/shake.png" style="width:140px; height:140px;"><h3 style="display:inline;" id="shockForm">  No alert</h3>
          <h2>Acceleration-Detection (max)</h2>
          <!--<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>-->
          <p><a class="btn" id="acc_button" href='acceleration_events.php?pid=<?php echo $_GET['pid']?>'>View details &raquo;</a></p>
        </div><!-- /.span4 -->
   
        <div class="span4 offset1">
          <img class="img-rounded" src="../assets/img/sun.svg" style="width:140px; height:140px;"><h3 style="display:inline;" id="lightForm">No alert</h3>
          <h2>Light-Detection</h2>
          <!--<p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>-->
          <p><a class="btn" id="light_button" href='light_events.php?pid=<?php echo $_GET['pid']?>'>View details &raquo;</a></p>
        </div><!-- /.span4 -->
		

		   </div><!-- /.row -->

		
           <!-- FOOTER -->
         <footer style="margin-top:100px">
           <p class="pull-right"><a href="#">Back to top</a></p>
           <p>&copy; 2012 ifgi</p>
   		<!-- Morin: COPYRIGHT-HINWEISE BITTE NICHT ENTFERNEN !-->
   		<p><a href="http://thenounproject.com/noun/temperature/#icon-No7810" target="_blank">Temperature</a> designed by <a href="http://thenounproject.com/asher84" target="_blank">Ashley Reinke</a> from The Noun Project</p>
   		<p><a href="http://thenounproject.com/noun/temperature/#icon-No7810" target="_blank">Shock</a> designed by <a href="http://www.unocha.org/" target="_blank">http://www.unocha.org/</a> from The Noun Project</p>		
   		<p><a href="http://thenounproject.com/noun/sun/#icon-No2660" target="_blank">Sun</a> designed by <a href="http://thenounproject.com/adamwhitcroft" target="_blank">Adam Whitcroft</a> from The Noun Project</p>
         </footer>
      </div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script>

  </body>
</html>
