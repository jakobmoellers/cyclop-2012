<?
//Redirects to a page without POST data to avoid annoying confirm resending data messages of your browser.
if (isset($_POST['sumo_user'])) {
	$avoidPostback = true;
}
require 'sumo/sumo.php';
require 'includes/db_func.php';
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
    <title>POTWECH — Graphs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content=""The Parcel on the Web Challenge" a course at ifgi 2012">
    <meta name="author" content="Dennis, Gerald, Jakob, Jan, Maurin, Morin, Niels">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../assets/css/jquery.jqplot.min.css" rel="stylesheet">
	<style>
	 body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }

      html, body{
		width:100%;
		height:100%;
		margin:0;
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

      <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
		<div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="../index.php">POTWECH</a>
          <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
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
        </div><!-- /.navbar-inner -->
      </div><!-- /.navbar -->
	  </div>
   
    <div class="container">

<<<<<<< HEAD
	  <div id="chart" style="height:400px;width:800px; "></div>
=======
		<div id="chart" style="height: 400px; width: 1000px;"></div>
		<br />
		<a class="btn" href="#" onclick="resetZoom(); return false;">Reset Zoom</a>
		<br /><br /><br />
>>>>>>> Server Changes - final?
	  
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
	<script src="../assets/js/jqplot.cursor.min.js"></script>
    <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="../assets/js/excanvas.min.js"></script><![endif]-->
    <script>
	var sensors = new Array();
	var jqplot = null;
	var minDate = null;
	var maxDate = null;

	function init() {
		if ($_GET(['temperature'])) {
			sensors.push("temperature");
		}
		if ($_GET(['humidity'])) {
			sensors.push("humidity");
		}
		if ($_GET(['battery'])) {
			sensors.push("battery");
		}
		
		updateGraphs($_GET(['pid']), sensors);
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
	
	function toTitleCase(str) {
		if (!str) return "";
	    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	}
	
	function resetZoom() {
		updateGraphs($_GET(['pid']), sensors);
	}
	
	function updateGraphs(parcelId, sensors) {
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
					  dateString = (features[feature]["properties"]["time"].substring(0, features[feature]["properties"]["time"].indexOf("+"))).replace(/-/g, "/");
					  //dateString = features[feature]["properties"]["time"];
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
		jqplot = $.jqplot("chart", "http://potwech.uni-muenster.de/rest/index.php/parcel_measurements/"+parcelId, {
		    title: "Your Parcel's Health",
			legend: {
				show: true,
				placement: "outsideGrid"
			},
		    dataRenderer: ajaxDataRenderer,
			dataRendererOptions: {
				sensors: sensors
			},
			axes: {
				xaxis: {
					renderer: $.jqplot.DateAxisRenderer,
					numberTicks: 10,
					tickOptions: {
						formatString: "%H:%M <br /> %Y-%m-%d"
					}
				}
		 	},
			cursor: { 
			        show: true,
			        zoom: true, 
					looseZoom: true,
					clickReset: false,
					dblClickReset: false,
			        showTooltip: false
			},
			series: [
				{
					color: "#FF0000",
					label: toTitleCase(sensors[0]),
					showMarker: false,
					rendererOptions: {
						smooth: true
					}
				},
				{
					color: "#0066FF",
					label: toTitleCase(sensors[1]),
					showMarker: false,
					rendererOptions: {
						smooth: true
					}
				},
				{
					color: "#006600",
					label: toTitleCase(sensors[2]),
					showMarker: false,
					rendererOptions: {
						smooth: true
					}
				}
			]
		  });		
	}
    </script>
	<!-- /Morin -->
  </body>
</html>
