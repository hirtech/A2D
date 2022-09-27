<?php
ob_start();
set_time_limit(0);
// echo '12'; 
//date_default_timezone_set('America/Los_Angeles');
ini_set('memory_limit', '-1');
ini_set('url_rewriter.tags', '');
error_reporting(1);
session_start();
if(file_exists("../config/setting/settings.php")){
	include_once("../config/setting/settings.php");
}
else{
	include_once("../../config/setting/settings.php");
}

include_once("../config/setting/settings.php");
$time_zone = file_get_contents($time_zone_path."timezone.txt");
date_default_timezone_set($time_zone);
# -----------------------
$magic_quotes_gpc = ini_get("magic_quotes_gpc");
if(!$magic_quotes_gpc){
	include_once($serverdir_path."globals_slashes.php");
}
# -----------------------
$reg_globals = ini_get("register_globals");
if (!$reg_globals) {
	include_once($serverdir_path."globals_register.php");
}
# -----------------------
include_once($setting_path."db_config.php");
include($lib_path.'adodb5/adodb.inc.php');
include_once($class_path."Global/Database.class.php");
include_once($function_path."general.inc.php");
include_once($function_path."date.inc.php");

$dbdriver="postgres";
$sqlObj = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
$sqlObj->debug = false;
$sqlObj->Connect($server, $user_name, $password, $database);
$sqlObj->SetCharSet('utf8');
$sqlObj->SetFetchMode(ADODB_FETCH_ASSOC);


$dbObj = new Global_Database();
$dbObj->getSettingVariables();
?>