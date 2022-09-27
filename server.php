<?php
ob_start();
set_time_limit(0);
// echo '12'; 
//date_default_timezone_set('America/Los_Angeles');
//ini_set('file_uploads', 'On');
ini_set('memory_limit', '-1');
ini_set('url_rewriter.tags', '');
ini_set('session.cookie_lifetime', 0);
//ini_set('session.use_only_cookies', 0);
//ini_set('session.remember_login_cookie_lifetime', 0);

//error_reporting(0);
error_reporting(E_ALL);
session_start();
include_once("config/setting/settings.php");
//$time_zone = file_get_contents($time_zone_path."timezone.txt");
//date_default_timezone_set($time_zone);
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
include_once($setting_path."messages.php");
//include($lib_path.'adodb5/adodb.inc.php');
include_once($lib_path.'adodb5/adodb.inc.php');
include_once($class_path."Global/Database.class.php");
include_once($function_path."general.inc.php");
include_once($function_path."date.inc.php");
include_once($function_path."validUser.inc.php");
include_once("smarty.php");

$dbdriver="postgres";

$sqlObj = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
$sqlObj->debug = false;
//$sqlObj->PConnect($server, $user_name, $password, $database);
$sqlObj->Connect($server, $user_name, $password, $database);
$sqlObj->SetCharSet('utf8');
$sqlObj->SetFetchMode(ADODB_FETCH_ASSOC);

//Add second object for traccar db queries
/*$sqlObjTrack = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
$sqlObjTrack->debug = false;
//$sqlObjTrack->PConnect($server, $user_name, $password, 'traccar');
$sqlObjTrack->Connect($server, $user_name, $password, 'traccar');
$sqlObjTrack->SetCharSet('utf8');
$sqlObjTrack->SetFetchMode(ADODB_FETCH_ASSOC);
*/
$dbObj = new Global_Database();
$dbObj->getSettingVariables();
//$COMPANY_COPYRIGHTS;exit();

date_default_timezone_set($SITE_TIMEZONE);

include_once($class_path."Global/Mobile_device_detect.class.php");
$detect = new Mobile_Detect();

$device_detect = 0;
if($detect->isMobile() || $detect->isIpad() || $detect->isAndroidtablet() || $detect->isBlackberrytablet()){
	$device_detect = 1;
}
?>