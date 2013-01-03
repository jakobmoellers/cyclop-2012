<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>POTWECH — Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content=""The Parcel on the Web Challenge" a course at ifgi 2012">
    <meta name="author" content="Dennis, Gerald, Jakob, Jan, Maurin, Morin, Niels">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

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

  <body {{GET:OnLoad}}>

    <div class="container">
		<form class="form-signin" name="SumoRegistration" method="POST" action="?sumo_action=confirmreg" 
			  onsubmit="document.SumoRegistration.reg_password.value=hex_sha1(document.SumoRegistration.reg_password.value);document.SumoRegistration.rep_reg_password.value=hex_sha1(document.SumoRegistration.rep_reg_password.value);">
		  <h2 class="form-signin-heading">Register</h2>
		  
		  {{LANG:RegistrationInfo}}		  
		  {{GET:Message}}
		  
		  <input class="input-block-level" placeholder="User name" type="text" size="16" name="reg_user" value="" /><input type="hidden" name="reg_group" value="Array" />
		  <input class="input-block-level" placeholder="E-Mail" type="text" size="16" name="reg_email" value="" />
		  <input class="input-block-level" placeholder="Password" type="password" size="16" name="reg_password" autocomplete="off" />
		  <input class="input-block-level" placeholder="Password (repeat)" type="password" size="16" name="rep_reg_password" autocomplete="off" />
		  <input class="btn btn-large btn-primary" type="submit" class="button" value="Submit" />		  
		  <a class="btn btn-large" onclick="javascript:history.go(-1);">Back</a>
		</form>	 
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery-1.8.3.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
	
	{{GET:ScriptNoRightClick}}
	{{GET:ScriptLoginFocus}}

  </body>
</html>
