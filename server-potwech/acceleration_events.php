<?
require 'sumo/sumo.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>POTWECH - Acceleration Detection</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content=""The Parcel on the Web Challenge" a course at ifgi 2012">
    <meta name="author" content="Dennis, Gerald, Jakob, Jan, Maurin, Morin, Niels">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../assets/css/jquery.jqplot.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.css" />
    <script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js"></script>
    
    <style>
		
	#map {
          width: 100%;
          height: 600px;
      }
	  
    /* GLOBAL STYLES
    -------------------------------------------------- */
    /* Padding below the footer and lighter body text */

    body {
      padding-bottom: 40px;
      color: #5a5a5a;
    }



    /* CUSTOMIZE THE NAVBAR
    -------------------------------------------------- */

    /* Special class on .container surrounding .navbar, used for positioning it into place. */
    .navbar-wrapper {
      position: relative;
      z-index: 10;
      margin-top: 20px;
      /*margin-bottom: -90px; /* Negative margin to pull up carousel. 90px is roughly margins and height of navbar. */
    }

    /* Remove border and change up box shadow for more contrast */
    .navbar .navbar-inner {
      border: 0;
      -webkit-box-shadow: 0 2px 10px rgba(0,0,0,.25);
         -moz-box-shadow: 0 2px 10px rgba(0,0,0,.25);
              box-shadow: 0 2px 10px rgba(0,0,0,.25);
    }

    /* Downsize the brand/project name a bit */
    .navbar .brand {
      padding: 14px 20px 16px; /* Increase vertical padding to match navbar links */
      font-size: 16px;
      font-weight: bold;
      text-shadow: 0 -1px 0 rgba(0,0,0,.5);
    }

    /* Navbar links: increase padding for taller navbar */
    .navbar .nav > li > a {
      padding: 15px 20px;
    }

    /* Offset the responsive button for proper vertical alignment */
    .navbar .btn-navbar {
      margin-top: 10px;
    }



    /* CUSTOMIZE THE NAVBAR
    -------------------------------------------------- */

    /* Carousel base class */
    .carousel {
      margin-bottom: 60px;
    }

    .carousel .container {
      position: absolute;
      right: 0;
      bottom: 0;
      left: 0;
    }

    .carousel-control {
      background-color: transparent;
      border: 0;
      font-size: 120px;
      margin-top: 0;
      text-shadow: 0 1px 1px rgba(0,0,0,.4);
    }

    .carousel .item {
      height: 500px;
    }
    .carousel img {
      min-width: 100%;
      height: 500px;
    }

    .carousel-caption {
      background: rgba(0, 0, 0, 0.5);
      position: static;
      max-width: 550px;
      padding: 0 20px;
      margin-bottom: 100px;
    }
    .carousel-caption h1,
    .carousel-caption .lead {
      margin: 0;
      line-height: 1.25;
      color: #fff;
      text-shadow: 0 1px 1px rgba(0,0,0,.4);
    }
    .carousel-caption .btn {
      margin-top: 10px;
    }



    /* MARKETING CONTENT
    -------------------------------------------------- */

    /* Center align the text within the three columns below the carousel */
    .marketing .span4 {
      text-align: center;
    }
    .marketing h2 {
      font-weight: normal;
    }
    .marketing .span4 p {
      margin-left: 10px;
      margin-right: 10px;
    }


    /* Featurettes
    ------------------------- */

    .featurette-divider {
      margin: 80px 0; /* Space out the Bootstrap <hr> more */
    }
    .featurette {
      padding-top: 120px; /* Vertically center images part 1: add padding above and below text. */
      overflow: hidden; /* Vertically center images part 2: clear their floats. */
    }
    .featurette-image {
      margin-top: -120px; /* Vertically center images part 3: negative margin up the image the same amount of the padding to center it. */
    }

    /* Give some space on the sides of the floated elements so text doesn"t run right into it. */
    .featurette-image.pull-left {
      margin-right: 40px;
    }
    .featurette-image.pull-right {
      margin-left: 40px;
    }

    /* Thin out the marketing headings */
    .featurette-heading {
      font-size: 50px;
      font-weight: 300;
      line-height: 1;
      letter-spacing: -1px;
    }



    /* RESPONSIVE CSS
    -------------------------------------------------- */

    @media (max-width: 979px) {

      .container.navbar-wrapper {
        margin-bottom: 0;
        width: auto;
      }
      .navbar-inner {
        border-radius: 0;
        margin: -20px 0;
      }

      .carousel .item {
        height: 500px;
      }
      .carousel img {
        width: auto;
        height: 500px;
      }

      .featurette {
        height: auto;
        padding: 0;
      }
      .featurette-image.pull-left,
      .featurette-image.pull-right {
        display: block;
        float: none;
        max-width: 40%;
        margin: 0 auto 20px;
      }
    }


    @media (max-width: 767px) {

      .navbar-inner {
        margin: -20px;
      }

      .carousel {
        margin-left: -20px;
        margin-right: -20px;
      }
      .carousel .container {

      }
      .carousel .item {
        height: 300px;
      }
      .carousel img {
        height: 300px;
      }
      .carousel-caption {
        width: 65%;
        padding: 0 70px;
        margin-bottom: 40px;
      }
      .carousel-caption h1 {
        font-size: 30px;
      }
      .carousel-caption .lead,
      .carousel-caption .btn {
        font-size: 18px;
      }

      .marketing .span4 + .span4 {
        margin-top: 40px;
      }

      .featurette-heading {
        font-size: 30px;
      }
      .featurette .lead {
        font-size: 18px;
        line-height: 1.5;
      }

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

  <body onLoad="init()">



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
          <a class="brand" href="#">POTWECH</a>
          <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="?sumo_action=logout">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!-- /.navbar-inner -->
      </div><!-- /.navbar -->
    </div><!-- /.container -->

    <div class="container">
	  <!-- <form class="form-search" action="#">
		<div class="input-append">
			<input type="text" class="span2 search-query" id="parcelId" placeholder="Select Your Parcel #">
			<button type="submit" class="btn" onclick="updateGraphs($('#parcelId').val()); return false;">Update</button>
		</div>
	  </form>-->
	  <!--<div id="chart" style="height:400px;width:800px; "></div>-->
	   <table>
			<colgroup>
			<col width="450">
				<col width="650">
			</colgroup>
		   <tr>
			   <td valign="top"><img src="./assets/img/shake.png"><h3>Acceleration detection</h3><p id="data"></p></td>
			   <td><div id="map"></div></td>
		   </tr>
	   </table>
	   
	   
	  
	  
	  
      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2012 ifgi</p>
		<!-- Morin: COPYRIGHT-HINWEISE BITTE NICHT ENTFERNEN !-->
		<p><a href="http://thenounproject.com/noun/people/#icon-No2300" target="_blank">People</a> designed by <a href="http://thenounproject.com/rjsokolov" target="_blank">Roman J. Sokolov</a> from The Noun Project</p>
		<p><a href="http://thenounproject.com/noun/businessperson/#icon-No1057" target="_blank">Businessperson</a> designed by <a href="http://thenounproject.com/devochkina" target="_blank">Devochkina Oxana</a> from The Noun Project</p>
		<p><a href="http://thenounproject.com/noun/temperature/#icon-No7810" target="_blank">Temperature</a> designed by <a href="http://thenounproject.com/asher84" target="_blank">Ashley Reinke</a> from The Noun Project</p>
      </footer>
    </div><!-- /.container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery-1.8.3.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>


	<!-- Morin -->
    <script src="../assets/js/jquery.jqplot.min.js"></script>
    <script src="../assets/js/jqplot.dateAxisRenderer.min.js"></script>
    <script src="../assets/js/jqplot.json2.min.js"></script>
    <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="../assets/js/excanvas.min.js"></script><![endif]-->
    <script>
	var map;
	
	function getLightEvents(){
		$.getJSON("../rest/index.php/parcel_acceleration-events/"+$_GET(['pid']), function (json) {
					if(json.features[0].properties.acceleration){
						var res='<p>Occurences when the package was dropped or shaken:</p>';
						res=res+'<table border="1">';
						res=res+'<colgroup><col width="50"><col width="250"><col width="100"></colgroup>';
						res=res+'<tr><td>Occurrence</td><td>Time of occurrence</td><td>Acceleration intensity</td></tr>';
						for (var i=0;i<json.features.length;i++){
							//console.log(json.features[i].properties.light);
							//console.log(json.features[i].geometry.coordinates[0]);
							var timestamp = new Date(json.features[i].properties.time);
							//Ugly indexing...
							res=res+'<tr>'+'<td>'+(i+1)+'</td>'+'<td>'+timestamp.toGMTString()+'</td>'+'<td>'+json.features[i].properties.acceleration+'</td>'+'</tr>';
							var marker = L.marker([json.features[i].geometry.coordinates[1], json.features[i].geometry.coordinates[0]]).addTo(map);
							marker.bindPopup('Occurrence Number </b>'+(i+1)).openPopup();
						}
						res=res+'</table>';
						
						$('#data').html(res);
					}
					
         });		
	}
	
	function initMap() {
			// set up the map
			map = new L.Map('map');

			 L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
                }).addTo(map);	

			map.setView(new L.LatLng(51.966667, 7.633333),9);
	}

	function init(){
		initMap();
		getLightEvents();
		updateGraphs($_GET(['pid']));
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
	
	function updateGraphs(parcelId) {
		/* Diese Funktion holt die in "dataRendererOptions" angegebenen Sensordaten via AJAX
		   und baut ein jqPlot konformes Array. Außerdem werden der min/max Bereich des Plots justiert.
		 */
		var ajaxDataRenderer = function(url, plot, options) {
		    var ret = null;
		    $.ajax({
		      async: false,
		      url: url,
		      dataType: "json",
		      success: function(data) {
				  ret = new Array();
				  for (var sensor in options["sensors"]) {
					  ret[sensor] = new Array();
				  }
			  
				  minDate = Number.MAX_VALUE;
				  maxDate = Number.MIN_VALUE;
				  dateString = null;
				  date = null;			  		  
				  features = data["features"];
			  
				  for (var feature in features) {
					  dateString = (features[feature]["properties"]["time"].substring(0, features[feature]["properties"]["time"].indexOf("."))).replace(/-/g, "/");
					  date = Date.parse(dateString);
					  for (var sensor in options["sensors"]) {
						  ret[sensor].push([date, parseInt(features[feature]["properties"][options["sensors"][sensor]])]);
					  }
					  if (date < minDate) minDate = date;
					  if (date > maxDate) maxDate = date;
				  }
			  
				  plot["axes"]["xaxis"]["min"] = minDate;
				  plot["axes"]["xaxis"]["max"] = maxDate;
		      }
		    });
			return ret;
		};
		
		$("#chart").empty();
		$.jqplot("chart", "http://potwech.uni-muenster.de/rest/index.php/parcel_measurements/"+parcelId, {
		    title: "Your Parcel's Health",
			legend: {
				show: true,
				placement: "outsideGrid"
			},
		    dataRenderer: ajaxDataRenderer,
			dataRendererOptions: {
				sensors: [
					"temperature"
				]
			},
			axes: {
				xaxis: {
					renderer: $.jqplot.DateAxisRenderer,
					tickOptions: {
						formatString: "%H:%M <br /> %Y-%m-%d"
					}
				}
		 	},
			series: [
				{
					color: "#FFFF00",
					label: "Light",
					showMarker: false,
					rendererOptions: {
						smooth: true
					}
				},
			]
		  });		
	}
    </script>
	<!-- /Morin -->
  </body>
</html>
