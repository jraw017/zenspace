<?php
// disallow direct file access
if(!defined('IN_FILE')){die('Direct access not premitted!');}

// default timezone
date_default_timezone_set("Pacific/Auckland");

// logout user
if(isset($_GET['act'])){
	$action = $_GET['act'];
	
	if($action =='logout'){
    	if(isset($facebook)){
			$facebook->destroySession();
		}
		session_destroy();
		header('location: ./landing.php');
	}
}

// db config
class db {
	public function connect() {
		
		$host = $_SERVER['HTTP_HOST'];
		if ($host == 'localhost') {
			$link = mysql_connect ( 'localhost', 'zenspace', 'Zen_Space123' );
			
			// too many connections, redirect to error page
			if (mysql_errno() == 1203) {
			// 1203 == ER_TOO_MANY_USER_CONNECTIONS (mysqld_error.h)
			header("Location: http://www.hkesa.org.nz/traffic.php");
			exit;
			}
			
			$db_selected = mysql_select_db ( 'zenspace', $link );
			mysql_query("SET NAMES 'utf8'");
		} else {
			$link = mysql_connect ( 'localhost', 'kedessa_zenspace', 'K*XuMc7r_s2d' );
			
			// too many connections, redirect to error page
			if (mysql_errno() == 1203) {
			// 1203 == ER_TOO_MANY_USER_CONNECTIONS (mysqld_error.h)
			header("Location: http://www.hkesa.org.nz/traffic.php");
			exit;
			}
			
			$db_selected = mysql_select_db ( 'kedessa_zenspace', $link );
			mysql_query("SET NAMES 'utf8'");
		}
	}
}
$db = new db();
$db->connect();

?>