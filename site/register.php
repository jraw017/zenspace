<?php
session_start();
define('IN_FILE', TRUE);
include('./inc/php/functions.php');
include('./inc/php/dbconfig.php');
require_once('./inc/php/facebook.php');

define('FACEBOOK_APP_ID', '337427283046680');
define('FACEBOOK_SECRET', '4d6ebc94c1ce1e4bd6ae6ceffb82e986');

function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}

if ($_REQUEST) {
  $response = parse_signed_request($_REQUEST['signed_request'], 
                                   FACEBOOK_SECRET);

  // Prepare fields for database
  $user_fbid = strip_tags(mysql_real_escape_string($response['user_id']));
  $user_name = strip_tags(mysql_real_escape_string($response['registration']['name']));
  $user_birthday = strip_tags(mysql_real_escape_string($response['registration']['birthday']));
  $user_location = strip_tags(mysql_real_escape_string($response['registration']['location']['name']));
  $user_school = strip_tags(mysql_real_escape_string($response['registration']['college']['name']));
  $user_study = strip_tags(mysql_real_escape_string($response['registration']['college_major']));
  $user_position = strip_tags(mysql_real_escape_string($response['registration']['college_level']));
  $user_gender = strip_tags(mysql_real_escape_string($response['registration']['gender']));
  $user_email = strip_tags(mysql_real_escape_string($response['registration']['email']));
  $user_locale = strip_tags(mysql_real_escape_string($response['user']['locale']));

  // Add to database
  mysql_query("INSERT INTO zen_user (user_fbid, user_name, user_birthday, user_location, user_school, user_study, user_position, user_gender, user_email, user_locale) 
    VALUES ('".$user_fbid."','".$user_name."','".$user_birthday."','".$user_location."','".$user_school."','".$user_study."','".$user_position."','".$user_gender."','".$user_email."','".$user_locale."')");

  // Redirect to success page
  $_SESSION['logged_in'] = "Yessir";
  header('location: ./registered.php');
  ob_flush();

} else {
  echo '$_REQUEST is empty';
}
?>