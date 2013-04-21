<?php
session_start();
define('IN_FILE', TRUE);
include('./inc/php/functions.php');
include('./inc/php/dbconfig.php');
require_once('./inc/php/facebook.php');

$config = array();
$config['appId'] = '337427283046680';
$config['secret'] = '4d6ebc94c1ce1e4bd6ae6ceffb82e986';
$config['fileUpload'] = false; // optional
$config['redirect_uri'] = "http://dev.csau.org.nz/zenspace/index.php";

$facebook = new Facebook($config);

// Facebook Login
$code = $_REQUEST["code"];

if(empty($code)) {
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
    $dialog_url = "https://www.facebook.com/dialog/oauth?client_id="
    . $config['appId'] . "&redirect_uri=" . urlencode($config['redirect_uri']) . "&state="
    . $_SESSION['state'] . "&scope=user_birthday,user_education_history,user_location";

    header("Location: " . $dialog_url);
}

if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
    $token_url = "https://graph.facebook.com/oauth/access_token?"
    . "client_id=" . $config['appId'] . "&redirect_uri=" . urlencode($config['redirect_uri'])
    . "&client_secret=" . $config['secret'] . "&code=" . $code;

    $response = file_get_contents_curl($token_url);
   	$params = null;
    parse_str($response, $params);

    $_SESSION['access_token'] = $params['access_token'];

    $graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];

    $user = json_decode(file_get_contents_curl($graph_url));
    $_SESSION['logged_in'] = "yessir"; // Logged in successfully 

    } else {

    	echo("The state does not match. You may be a victim of CSRF.");

    }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ZenSpace &raquo; Landing Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="./inc/css/bootstrap.css" rel="stylesheet">
    <link href="./inc/css/style.css" rel="stylesheet">
    <link href="./inc/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="./inc/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="./inc/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="./inc/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="./inc/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="./inc/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="./inc/ico/favicon.ico">
  </head>

  <body>
  <!-- Facebook SDK -->
	<div id="fb-root"></div>
	<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '337427283046680',                        // App ID from the app dashboard
      channelUrl : '//dev.csau.org.nz/zenspace/inc/php/channel.php', // Channel file for x-domain comms
      status     : true,                                 // Check Facebook Login status
      xfbml      : true                                  // Look for social plugins on the page
    });

    // Additional initialization code such as adding Event Listeners goes here
  };

  // Load the SDK asynchronously
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
	</script>
	<!--/ Facebook SDK -->

    <div class="container">

      <div class="masthead">
        <h3 class="muted"><a href="./landing.php"><img src="inc/img/ZenSpace_logo.png" alt="ZenSpace" width="150" height="150" border="0"></a></h3>
      </div>
		
      <div class="well">
      <!-- Example row of columns -->
      <div class="row-fluid">
        <div class="span12">
          <h2 class="text-center">Register for ZenSpace</h2>
         </div>
         <div class="span12">
          <?php
			// Is user logged in?
			if($_SESSION['logged_in'] != "yessir"){
				echo "<p class='text-center text-error'>You must be logged in to Facebook to register for ZenSpace.</p>";
			} else {
		   ?>

	<div id="registration">
		<fb:registration
			fields="[
 				{'name':'name'},
 				{'name':'email'},
 				{'name':'location'},
 				{'name':'gender'},
 				{'name':'birthday'},
 				{'name':'college',	'description':'College/University', 'type':'typeahead', 'categories':['university']},
 				{'name':'college_level',       'description':'Academic Level', 'type':'select',  'options':{'cert':'Certificate','dip':'Diploma','assoc':'Associate Degree','bachelor':'Bachelor Degree','masters':'Masters Degree','doc':'Doctorate / PhD','other':'Other / Not a Student'}},
 				{'name':'college_major', 'description':'Field of Study', 'type':'text'},
 				{'name':'captcha'}
			]"
			redirect-uri="http://dev.csau.org.nz/zenspace/register.php"
			fb_only="true"
            width="900">
		</fb:registration>
	</div>

		<?php } ?>
        </div>
        
        </div>
        </div>
        </div>      
      </div> <!-- </container> -->

      <div class="footer">
      	<a href="http://www.spaceappschallenge.org" target="_blank"><img src="inc/img/nasa_logo.png" style="border-width:0" alt="NASA"></a>
        <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB" target="_blank">
        <img alt="Creative Commons Licence" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" /></a>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="./inc/js/bootstrap.js"></script>
  </body>
</html>