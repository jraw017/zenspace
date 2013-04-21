<?php
session_start();
define('IN_FILE', TRUE);
include('./inc/php/functions.php');
include('./inc/php/dbconfig.php');
require_once('./inc/php/facebook.php');

// get male users
$male_users_q = mysql_query("SELECT COUNT(user_gender) FROM `zen_user` WHERE user_gender = 'male'");
$male_users = mysql_fetch_array($male_users_q);

// get female users
$female_users_q = mysql_query("SELECT COUNT(user_gender) FROM `zen_user` WHERE user_gender = 'female'");
$female_users = mysql_fetch_array($female_users_q);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

echo "gender,count\n";
echo "male," . $male_users[0] . "\n";
echo "female," . $female_users[0];

?>