<?php
session_start();
define('IN_FILE', TRUE);
include('./inc/php/functions.php');
include('./inc/php/dbconfig.php');
require_once('./inc/php/facebook.php');

// get male users
$happy_users_q = mysql_query("SELECT tag_count FROM `zen_tags` WHERE tag_value = 'happy'");
$happy_users = mysql_fetch_array($happy_users_q);

// get female users
$sad_users_q = mysql_query("SELECT tag_count FROM `zen_tags` WHERE tag_value = 'sad'");
$sad_users = mysql_fetch_array($sad_users_q);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

echo "emotion,frequency\n";
echo "happy," . $happy_users[0] . "\n";
echo "sad," . $sad_users[0];

?>