<?php
session_start();
define('IN_FILE', TRUE);
include('./inc/php/functions.php');
include('./inc/php/dbconfig.php');
require_once('./inc/php/facebook.php');

// check login
if($_SESSION['logged_in'] != "Yessir"){
	header('location: ./landing.php');
	ob_flush();	
}

// no direct access
if(!isset($_POST['emotions'])){
	die("Access Violation!");
	exit();
}

// prepare variables
$emotions = strip_tags($_POST['emotions']);
$comments = strip_tags($_POST['comments']);

$emotions = str_replace('[','',$emotions);
$emotions = str_replace('"','',$emotions);
$emotions = str_replace('\\','',$emotions);
$emotions = str_replace(']','',$emotions);

$emotions_a = array_filter(explode(",", $emotions));

foreach($emotions_a as $emotion){
	$emotions_s .= $emotion . ",";
	// check tags db
	$check_tag_q = mysql_query("SELECT tag_value FROM zen_tags WHERE tag_value = '".$emotion."'");
	$check_tag = mysql_num_rows($check_tag_q);
	
	if($check_tag < 1){
		// new tag, add to db
		mysql_query("INSERT INTO zen_tags (tag_value) VALUES ('".$emotion."')") or die(mysql_error());
	} else {
		// tag found, increment count.
		mysql_query("UPDATE zen_tags SET tag_count = tag_count + 1 WHERE tag_value = '".$emotion."'") or die(mysql_error());
	}
}

// insert into database
mysql_query("INSERT INTO zen_feedback (user_fbid, feedback_emotions, feedback_comments) VALUES ('".$_SESSION['fb_id']."','".$emotions_s."','".$comments."')") or die(mysql_error());

header('location: submitted.php');
ob_flush();

?>