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
    <title>POTWECH — Notifications</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content=""The Parcel on the Web Challenge" a course at ifgi 2012">
    <meta name="author" content="Dennis, Gerald, Jakob, Jan, Maurin, Morin, Niels">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../assets/css/jquery.jqplot.min.css" rel="stylesheet">


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
			   <li><a href="initializePotwech.php">New POTWECH</a></li>
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
		<h1>Notifications</h1>
		<h2>Optimize your settings</h2>
		<p>Here, you can define your individual notification levels for package #<span id="pid_1"></span>.</p>
		
		<h3>Temperature</h3>
		<div class="row">
		<div class="span2 input-prepend input-append">
		  <div class="btn-group">
		    <button class="btn dropdown-toggle" data-toggle="dropdown">min
			  <span class="caret"></span>
		    </button>
		    <ul class="dropdown-menu">
			  <li><a href="#" onclick="setDefaults('temperature_min'); return false;">set to default</a></li>
		    </ul>
		  </div>
		  <input class="span1" id="temperature_min" type="text">
		  <span class="add-on">&deg;C</span>
		</div>
		<div class="span2 input-prepend input-append">
  		  <div class="btn-group">
  		    <button class="btn dropdown-toggle" data-toggle="dropdown">max
  			  <span class="caret"></span>
  		    </button>
  		    <ul class="dropdown-menu">
  			  <li><a href="#" onclick="setDefaults('temperature_max'); return false;">set to default</a></li>
  		    </ul>
  		  </div>
		  <input class="span1" id="temperature_max" type="text">
		  <span class="add-on">&deg;C</span>
		</div>
		</div>
		
		
		<h3>Humidity</h3>
		<div class="row">
		<div class="span2 input-prepend input-append">
		  <div class="btn-group">
		    <button class="btn dropdown-toggle" data-toggle="dropdown">min
			  <span class="caret"></span>
		    </button>
		    <ul class="dropdown-menu">
			  <li><a href="#" onclick="setDefaults('humidity_min'); return false;">set to default</a></li>
		    </ul>
		  </div>
		  <input class="span1" id="humidity_min" type="text">
		  <span class="add-on">%</span>
		</div>
		<div class="span2 input-prepend input-append">
  		  <div class="btn-group">
  		    <button class="btn dropdown-toggle" data-toggle="dropdown">max
  			  <span class="caret"></span>
  		    </button>
  		    <ul class="dropdown-menu">
  			  <li><a href="#" onclick="setDefaults('humidity_max'); return false;">set to default</a></li>
  		    </ul>
  		  </div>
		  <input class="span1" id="humidity_max" type="text">
		  <span class="add-on">%</span>
		</div>
		</div>

		
		<h3>Acceleration</h3>
		<div class="row">
		<div class="span2 input-prepend input-append">
		  <div class="btn-group">
		    <button class="btn dropdown-toggle" data-toggle="dropdown">min
			  <span class="caret"></span>
		    </button>
		    <ul class="dropdown-menu">
			  <li><a href="#" onclick="setDefaults('acceleration_min'); return false;">set to default</a></li>
		    </ul>
		  </div>
		  <input class="span1" id="acceleration_min" type="text">
		  <span class="add-on">g</span>
		</div>
		<div class="span2 input-prepend input-append">
  		  <div class="btn-group">
  		    <button class="btn dropdown-toggle" data-toggle="dropdown">max
  			  <span class="caret"></span>
  		    </button>
  		    <ul class="dropdown-menu">
  			  <li><a href="#" onclick="setDefaults('acceleration_max'); return false;">set to default</a></li>
  		    </ul>
  		  </div>
		  <input class="span1" id="acceleration_max" type="text">
		  <span class="add-on">g</span>
		</div>
		</div>
		
		
		<h3>Light</h3>
		<div class="row">
		<div class="span2 input-prepend input-append">
		  <div class="btn-group">
		    <button class="btn dropdown-toggle" data-toggle="dropdown">min
			  <span class="caret"></span>
		    </button>
		    <ul class="dropdown-menu">
			  <li><a href="#" onclick="setDefaults('light_min'); return false;">set to default</a></li>
		    </ul>
		  </div>
		  <input class="span1" id="light_min" type="text">
		  <span class="add-on">%</span>
		</div>
		<div class="span2 input-prepend input-append">
  		  <div class="btn-group">
  		    <button class="btn dropdown-toggle" data-toggle="dropdown">max
  			  <span class="caret"></span>
  		    </button>
  		    <ul class="dropdown-menu">
  			  <li><a href="#" onclick="setDefaults('light_max'); return false;">set to default</a></li>
  		    </ul>
  		  </div>
		  <input class="span1" id="light_max" type="text">
		  <span class="add-on">%</span>
		</div>
		</div>
	
		
		<h3>Battery</h3>
		<div class="row">
		<div class="span2 input-prepend input-append">
		  <div class="btn-group">
		    <button class="btn dropdown-toggle" data-toggle="dropdown">min
			  <span class="caret"></span>
		    </button>
		    <ul class="dropdown-menu">
			  <li><a href="#" onclick="setDefaults('battery_min'); return false;">set to default</a></li>
		    </ul>
		  </div>
		  <input class="span1" id="battery_min" type="text">
		  <span class="add-on">%</span>
		</div>
		<div class="span2 input-prepend input-append">
  		  <div class="btn-group">
  		    <button class="btn dropdown-toggle" data-toggle="dropdown">max
  			  <span class="caret"></span>
  		    </button>
  		    <ul class="dropdown-menu">
  			  <li><a href="#" onclick="setDefaults('battery_max'); return false;">set to default</a></li>
  		    </ul>
  		  </div>
		  <input class="span1" id="battery_max" type="text">
		  <span class="add-on">%</span>
		</div>
		</div>
		
		<div class="form-actions">
		  <button type="submit" class="btn btn-primary" onclick="save();">Save changes</button>
		  <button type="button" class="btn" onclick="history.go(-1);">Cancel</button>
		</div>
		
		<hr />
	  
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
	function init() {
		$("#pid_1").text($_GET('pid'));
		
		//First, load default thresholds
		setDefaults('temperature_min');
		setDefaults('temperature_max');
		
		setDefaults('humidity_min');
		setDefaults('humidity_max');
		
		setDefaults('acceleration_min');
		setDefaults('acceleration_max');
		
		setDefaults('light_min');
		setDefaults('light_max');
		
		setDefaults('battery_min');
		setDefaults('battery_max');
		
		//Then, overwrite the default thresholds with user defined thresholds (if any)
		$.getJSON('http://potwech.uni-muenster.de/rest/index.php/notifications_settings/'+$_GET('pid'), function(data) {
		  $.each(data, function(key, val) {
		    $('#'+key+'_min').val(data[key]['min']);
			$('#'+key+'_max').val(data[key]['max']);
		  });
		});
	}
	
	function setDefaults(field) {
		value = 0.0;
		switch(field) {
			case 'temperature_min': value = '10.0'; break;
			case 'temperature_max': value = '30.0'; break;
			
			case 'humidity_min': value = '10.0'; break;
			case 'humidity_max': value = '80.0'; break;
			
			case 'acceleration_min': value = '1'; break;
			case 'acceleration_max': value = '2'; break;
			
			case 'light_min': value = '1.0'; break;
			case 'light_max': value = '1.0'; break;
			
			case 'battery_min': value = '0.1'; break;
			case 'battery_max': value = '1.0'; break;
		}
		$('#'+field).val(value);
	}
	
	function save() {
		$.post('http://potwech.uni-muenster.de/rest/index.php/notifications_settings', 
		data='data={"pid": '+$_GET('pid')+',"settings":  {		  "temperature": {"min": '+$('#temperature_min').val()+', "max": '+$('#temperature_max').val()+'},"humidity": {"min": '+$('#humidity_min').val()+', "max": '+$('#humidity_max').val()+'},"acceleration": {"min": '+$('#acceleration_min').val()+', "max": '+$('#acceleration_max').val()+'},"light": {"min": '+$('#light_min').val()+', "max": '+$('#light_max').val()+'},"battery": {"min": '+$('#battery_min').val()+', "max": '+$('#battery_max').val()+'}}}').error(function() {
			alert('Invalid input! Please insert only numerical values.'); 
			}).success(function(){
				alert('Notification settings saved');
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
	<!-- /Morin -->
  </body>
</html>
