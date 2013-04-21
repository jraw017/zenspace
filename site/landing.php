<?php
session_start();
define('IN_FILE', TRUE);
include('./inc/php/functions.php');
include('./inc/php/dbconfig.php');
require_once('./inc/php/facebook.php');
/**
 * Facebook Login Script
 * By James Rawlings
 * Copyright 2011 Facebook, Inc.
 */

 
 // Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '337427283046680',
  'secret' => '4d6ebc94c1ce1e4bd6ae6ceffb82e986',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
	$token = $facebook->getAccessToken();
    $user_profile = $facebook->api('/me?access_token='.$token.'');
	
	// check user is registered
	$check_if_registered = mysql_query("SELECT user_fbid FROM zen_user WHERE user_fbid = '".$user_profile['id']."' LIMIT 1");
	$isRegistered = mysql_num_rows($check_if_registered);
	
	if($isRegistered < 1){
		$facebook->destroySession();
		session_destroy();
		header('location: index.php');
		exit();
	}		
		
	// set logged in session
	$_SESSION['logged_in'] = "Yessir";
		
	// set session variables
	$_SESSION['name'] = $user_profile['name'];
	$_SESSION['email'] = $user_profile['email'];
	$_SESSION['bday'] = $user_profile['birthday'];
	$_SESSION['fb_id'] = $user_profile['id'];
	
	// redirect to start page
	header("location: submit.php");
		
  } catch (FacebookApiException $e) {
    //error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl(array('scope' => 'user_birthday, user_education_history, user_location'));
}

// logout user
if($_GET['act'] =='logout'){

    $facebook->destroySession();
	session_destroy();
	header('location: landing.php');
	exit();
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

    <div class="container">

      <div class="masthead">
        <h3 class="muted"><img src="inc/img/ZenSpace_logo.png" alt="ZenSpace" width="150" height="150"></h3>
      </div>
		
      <div class="well">
      <!-- Example row of columns -->
      <div class="row-fluid">
        <div class="span12">
          <h2 class="text-center">Been here before?</h2>
          <div style="text-align: center; margin-left: auto; margin-right: auto;">
          <a href="<?php echo $loginUrl; ?>"><img src="./inc/img/fb_login.png"></a>&nbsp;<a href="./index.php"><img src="./inc/img/fb_register.png"></a>
          <br><br><a class="text-success" href="./data.php">View Data</a>
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