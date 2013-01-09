<?
//Redirects to a page without POST data to avoid annoying confirm resending data messages of your browser.
if (isset($_POST['sumo_user'])) {
	$avoidPostback = true;
}
require 'sumo/sumo.php';
require 'includes/db_func.php';
$user_id = $SUMO['user']['id'];
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
    <title>POTWECH — Private Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="'The Parcel on the Web Challenge' a course at ifgi 2012">
    <meta name="author" content="Dennis, Gerald, Jakob, Jan, Maurin, Morin, Niels">

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

    footer{
		margin-top: 100px;
	}
	
	.parcel_list
	{
		line-height: 120%;
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
			  <li><a href="initializePotwech.php">New POTWECH</a></li>
			  <li><a href="?sumo_action=logout">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
		
		<div class="row">
        <div class="span4">
		<h2>Active Parcels</h2>

	
		<?php
			//active parcels
			$sql = 'SELECT * FROM parcel_processes WHERE user_id_ref = '.$user_id.' AND end_time is null';
			$result = query($sql);
			$numrows = pg_numrows($result);
			if($numrows > 0 ){
				echo $numrows.' active parcel process(es): <br>';
				echo '<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" data-toggle="collapse" href="#parcelList">
        Parcels</a></div><div id="parcelList" class="accordion-body collapse"><div class="accordion-inner">';
				echo '<div style="overflow: auto; height: 200px">';
				echo '<div class="btn-group btn-group-vertical">';
				while ($data = pg_fetch_assoc($result)) {
					echo '<a class="btn" href="parcel_overview.php?pid='.$data['parcel_process_id'].'">Package ID: '.$data['package_number'].' &raquo;</a>';
				}				
				echo '</div></div></div></div></div>';

			}else{
				echo 'There are no active parcels';
			}			
		?>
		</div>
		
		        <div class="span4">
		<h2>Historic Parcels</h2>
		<?php
			//active parcels
			$sql = 'SELECT * FROM parcel_processes WHERE user_id_ref = '.$user_id.' AND end_time != null';
			$result = query($sql);
			$numrows = pg_numrows($result);
			if($numrows > 0 ){
				echo $numrows.' active parcel process(es): <br>';
				echo '<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" data-toggle="collapse" href="#parcelList">
        Parcels</a></div><div id="parcelList" class="accordion-body collapse"><div class="accordion-inner">';
				echo '<div style="overflow: auto; height: 200px">';
				echo '<div class="btn-group btn-group-vertical">';
				while ($data = pg_fetch_assoc($result)) {
					echo '<a class="btn" href="parcel_overview.php?pid='.$data['parcel_process_id'].'">Package ID: '.$data['package_number'].' &raquo;</a>';
				}				
				echo '</div></div></div></div></div>';

			}else{
				echo 'There are no historic parcels';
			}			
		?>
		</div>
		
		<div class="span4">
          <h2>Parcels with problems</h2>
			<p id="parcelproblems">
			<?php
			//active parcels
			$sql = 'select distinct parcel_process_id,package_number  from current_parcel_processes inner join problematic_parcels on (current_parcel_processes.parcel_process_id = problematic_parcels.parcel_process) where user_id_ref = '.$user_id;
			$result = query($sql);
			$numrows = pg_numrows($result);
			if($numrows > 0 ){
				echo $numrows.' parcel process(es) with possible problems: <br>';
				echo '<div class="accordion-group"><div class="accordion-heading"><a class="accordion-toggle" data-toggle="collapse" href="#problemList">
        Parcels</a></div><div id="problemList" class="accordion-body collapse"><div class="accordion-inner">';
				echo '<div style="overflow: auto; height: 200px">';
				echo '<div class="btn-group btn-group-vertical">';
				while ($data = pg_fetch_assoc($result)) {
					echo '<a class="btn" href="parcel_overview.php?pid='.$data['parcel_process_id'].'">Package ID: '.$data['package_number'].' &raquo;</a>';
				}				
				echo '</div></div></div></div></div>';

			}else{
				echo 'There are no problematic parcels';
			}
			?>
			
			</p>
        </div>
		</div>
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
