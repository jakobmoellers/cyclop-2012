<?
require 'sumo/sumo.php';
require 'includes/db_func.php';
if (isset($_POST['sumo_user'])) {
	$avoidPostback = true;
}
$user_id = $SUMO['user']['id'];

echo '<script>var uid='.$user_id.'</script>';

$user_group = $SUMO['user']['group'][0];


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

		
    </style>

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"type="text/css">
    <script src="../includes/jquery/jquery-1.8.3.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	
	<script>
	
		function save(){
			if($('#mid_form').val() != "" && $('#pid_form').val() != ""){
				$.post('http://potwech.uni-muenster.de/rest/index.php/initParcel', 
		data='uid='+uid+'&mobile_device='+$('#mid_form').val()+'&parcel_number='+$('#pid_form').val()).error(function() {
			alert('Invalid input! Please insert only numerical values.'); 
			}).success(function(){
				alert('New POTWECH successfully initialized');
				setTimeout(function(){window.location = "http://potwech.uni-muenster.de/private_customer.php"}, 0);

			});
			
			}else{
				alert('Invalid input');
			}
			
		
		}
	
	</script>

  </head>


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
			  <li><a href="?sumo_action=logout">Logout</a></li>
			  
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container marketing">
      	<h3>Start new POTWECH process</h3>
		
		<label>Mobile Device ID:</label>
		<input type="text" id="mid_form" placeholder="Insert your mobile device id...">
		<label>Parcel Number:</label>
		<input type="text" id="pid_form" placeholder="Insert your parcel number...">
		
		<div class="form-actions">
		  <button type="submit" class="btn btn-primary" onclick="javascript:save()">Submit</button>
		  <button type="button" class="btn" onclick="history.go(-1);">Cancel</button>
		</div>

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
  
        <!-- FOOTER -->
      <footer style="margin-top:100px">
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2012 ifgi</p>

      </footer>
</html>
