<?php
# By Zinal as on September 2022
#-------------------------------------------------------------------------------------
# Include necessary files.
#-------------------------------------------------------------------------------------
/*error_reporting(E_ALL);
ini_set('display_errors', 0);*/
include_once("server.php");
include_once($setting_path."api_messages.php");
include_once($setting_path."settings_api.php");
include_once($setting_path."settings.php");
include_once($function_path."api.inc.php");
include_once($function_path."app.inc.php");
include_once($function_path."date.inc.php");
include_once($function_path."general.inc.php");
#-------------------------------------------------------------------------------------
include_once($class_path."php-jwt/BeforeValidException.php");
include_once($class_path."php-jwt/ExpiredException.php");
include_once($class_path."php-jwt/SignatureInvalidException.php");
include_once($class_path."php-jwt/JWT.php");
#-------------------------------------------------------------------------------------
$res_header = array();

$version = $_REQUEST['version'];

$sitefolder_api = $site_path . "api/";
$site_api_path = $site_path."api/".$version."/";

$local_host = $_SERVER['SERVER_ADDR'];
$path = str_replace($sitefolder_api,"",$_SERVER['REQUEST_URI']);

//echo $path."\n";
if($local_host == "192.168.32.139") {
	$path = str_replace("/api/".$version."/","",$path);
}else {
	$path = str_replace("/eCommunityfiber/api/".$version."/","",$path);
}
//echo $path;exit;

if(strstr($path, '?'))
	$path = substr($path, 0 ,strpos($path, '?'));

$param = explode("/", $path);
/*echo"<pre>";print_r($_SERVER);exit;*/

//echo"<pre>";print_r($_FILES);exit;
if(strstr($_SERVER['CONTENT_TYPE'], 'application/json')){	
	$req_data = file_get_contents('php://input');
	
	$RES_PARA = json_decode($req_data, true);//convert JSON into array

	$FILES_PARA = $_FILES;
}
	
if(strstr($_SERVER['CONTENT_TYPE'], 'multipart/form-data')){	 
	$req_data = file_get_contents('php://input');
	$RES_PARA = json_decode($req_data, true);//convert JSON into array
	$FILES_PARA = $_FILES;
	
}
$FILES_PARA = $_FILES;
if(empty($RES_PARA) && isset($_POST)){
	$RES_PARA =$_POST;
}
$req_ext = strtolower(substr($path, strrpos($path, ".")+1, strlen($path)));		// xml | json

$iCountySaasId_erp= '';
//echo"<pre>";print_r($RES_PARA);exit;

if($req_ext != "json"){
	header("Content-Type: application/json; charset=utf-8");
	echo json_encode( array('Code'=> 500 , "Message" =>"Invalid Request Type" ) );
	hc_exit();
}
$ver	= trim($RES_PARA['ver']);

/*********************************************/
##Connect County Database
$api_case = str_replace('.'.$req_ext,'',$path);

# FUNCTION TO GET API KEY FROM CURLOPT_HTTPHEADER...

const API_REQUEST_MODE_GET = "GET";
switch($path) {
	case "user_login." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "user_login";
			include_once($site_api_path."login.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "user_list.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "user_list";
			$api_file_name = "user.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "check_duplicate_user.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "check_duplicate_user";
			$api_file_name = "user.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "getUserDetailsFromUserId.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "getUserDetailsFromUserId";
			$api_file_name = "user.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "user_add.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "user_add";
			$api_file_name = "user.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "user_edit.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "user_edit";
			$api_file_name = "user.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "user_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "user_delete";
			$api_file_name = "user.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "access_group_list.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "access_group_list";
			$api_file_name = "access_group.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "access_group_add.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "access_group_add";
			$api_file_name = "access_group.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "access_group_edit.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "access_group_edit";
			$api_file_name = "access_group.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "access_group_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "access_group_delete";
			$api_file_name = "access_group.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "access_group_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "access_group_dropdown";
			$api_file_name = "access_group.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "access_group_manage_role." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "access_group_manage_role";
			$api_file_name = "access_group.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "department_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "department_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "edit_profile.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "edit_profile";
			$api_file_name = "user.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "login_history_list.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "login_history_list";
			$api_file_name = "user.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "city_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "city_list";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "city_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "city_add";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "city_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "city_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "city_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "city_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "state_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "state_list";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "state_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "state_add";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "state_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "state_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "state_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "state_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "county_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "county_list";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "county_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "county_add";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "county_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "county_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "county_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "county_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_type_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_type_list";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_type_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_type_add";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_type_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_type_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_type_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_type_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_sub_type_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_sub_type_list";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_sub_type_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_sub_type_add";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_sub_type_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_sub_type_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_sub_type_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_sub_type_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_attribute_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_attribute_list";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_attribute_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_attribute_add";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_attribute_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_attribute_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_attribute_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_attribute_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "treatment_product_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "treatment_product_list";
			$api_file_name = "masters.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "treatment_product_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "treatment_product_add";
			$api_file_name = "masters.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "treatment_product_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "treatment_product_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "treatment_product_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "treatment_product_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "engagement_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "engagement_list";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "engagement_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "engagement_add";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "engagement_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "engagement_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "engagement_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "engagement_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "engagement_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "engagement_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "connection_type_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "connection_type_list";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "connection_type_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "connection_type_add";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "connection_type_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "connection_type_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "connection_type_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "connection_type_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "connection_type_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "connection_type_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "company_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "company_list";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "company_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "company_add";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "company_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "company_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "company_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "company_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "company_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "company_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "service_type_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "service_type_list";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "service_type_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "service_type_add";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "service_type_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "service_type_edit";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "service_type_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "service_type_delete";
			$api_file_name = "masters.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "service_type_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "service_type_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_list";
			$api_file_name = "premise.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_add";
			$api_file_name = "premise.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_edit";
			$api_file_name = "premise.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_delete";
			$api_file_name = "premise.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_batch_multiple_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_batch_multiple_add";
			$api_file_name = "premise.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_type_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_type_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_sub_type_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_sub_type_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "premise_attribute_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "premise_attribute_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "getPremiseContactData." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "getPremiseContactData";
			$api_file_name = "premise.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "getPremiseDocumentData." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "getPremiseDocumentData";
			$api_file_name = "premise.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_premise_history." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_premise_history";
			$api_file_name = "premise_history.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_task_treatment_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_treatment_list";
			$api_file_name = "task_treatment.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_treatment_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_treatment_add";
			$api_file_name = "task_treatment.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_treatment_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_treatment_edit";
			$api_file_name = "task_treatment.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_treatment_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_treatment_delete";
			$api_file_name = "task_treatment.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_task_trap_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_trap_list";
			$api_file_name = "task_trap.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_task_other_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_other_list";
			$api_file_name = "task_other.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_task_landing_rate_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_landing_rate_list";
			$api_file_name = "landing_rate.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_task_larval_surveillance_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_larval_surveillance_list";
			$api_file_name = "larval_surveillance.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_mosquito_species_data." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
				$request_type = "get_mosquito_species_data";
			$api_file_name = "general.php";
				include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_insta_treat_default_data.".$req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_insta_treat_data";
			$api_file_name = "general.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_mosquito_pool_list." . $req_ext:
			if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
				$request_type = "task_mosquito_pool_list";
				$api_file_name = "lab_task.php";
				include_once($site_api_path . "api_authentication.php");
			}else {
				$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
			}
			break;
	case "add_premise_document." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "add_premise_document";
			$api_file_name = "premise.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "delete_premise_document." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "delete_premise_document";
			$api_file_name = "premise.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_sync_unit_data." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_sync_unit_data";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_task_landing_rate_species." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_task_landing_rate_species";
			$api_file_name = "landing_rate.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_larval_surveillance_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_larval_surveillance_add";
			$api_file_name = "larval_surveillance.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_larval_surveillance_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_larval_surveillance_edit";
			$api_file_name = "larval_surveillance.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_larval_surveillance_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_larval_surveillance_delete";
			$api_file_name = "larval_surveillance.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_landing_rate_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_landing_rate_add";
			$api_file_name = "landing_rate.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_landing_rate_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_landing_rate_edit";
			$api_file_name = "landing_rate.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_landing_rate_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_landing_rate_delete";
			$api_file_name = "landing_rate.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;		
	case "task_trap_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_trap_add";
			$api_file_name = "task_trap.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_trap_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_trap_edit";
			$api_file_name = "task_trap.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_trap_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_trap_delete";
			$api_file_name = "task_trap.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "fiber_inquiry_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "fiber_inquiry_list";
			$api_file_name = "fiber_inquiry.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "fiber_inquiry_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "fiber_inquiry_edit";
			$api_file_name = "fiber_inquiry.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "fiber_inquiry_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "fiber_inquiry_add";
			$api_file_name = "fiber_inquiry.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "fiber_inquiry_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "fiber_inquiry_delete";
			$api_file_name = "fiber_inquiry.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "service_order_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "service_order_list";
			$api_file_name = "service_order.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "service_order_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "service_order_add";
			$api_file_name = "service_order.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "service_order_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "service_order_edit";
			$api_file_name = "service_order.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "service_order_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "service_order_delete";
			$api_file_name = "service_order.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "trap_type_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "trap_type_dropdown";
			$api_file_name = "task_trap.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_other_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_other_add";
			$api_file_name = "task_other.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_other_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_other_edit";
			$api_file_name = "task_other.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_other_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_other_delete";
			$api_file_name = "task_other.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_awareness_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_awareness_list";
			$api_file_name = "task_awareness.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_awareness_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_awareness_add";
			$api_file_name = "task_awareness.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_awareness_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_awareness_edit";
			$api_file_name = "task_awareness.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_awareness_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_awareness_delete";
			$api_file_name = "task_awareness.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "contact_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "contact_add";
			$api_file_name = "contact.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "contact_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "contact_edit";
			$api_file_name = "contact.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "contact_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "contact_delete";
			$api_file_name = "contact.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "contact_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "contact_list";
			$api_file_name = "contact.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "contact_history." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "contact_history";
			$api_file_name = "contact.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_mosquito_count_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_mosquito_count_list";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "trap_mosquito_count_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "trap_mosquito_count_list";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "mosquito_pool_result_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "mosquito_pool_result_list";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "mosquito_pool_result_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "mosquito_pool_result_add";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "mosquito_pool_result_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "mosquito_pool_result_edit";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "mosquito_pool_result_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "mosquito_pool_result_delete";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "inventory_count_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "inventory_count_list";
			$api_file_name = "inventory.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "inventory_count_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "inventory_count_add";
			$api_file_name = "inventory.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "inventory_count_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "inventory_count_edit";
			$api_file_name = "inventory.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "inventory_purchase_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "inventory_purchase_add";
			$api_file_name = "inventory.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "inventory_purchase_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "inventory_purchase_edit";
			$api_file_name = "inventory.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "inventory_purchase_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "inventory_purchase_list";
			$api_file_name = "inventory.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "treatment_product_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "treatment_product_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_mosquito_pool_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_mosquito_pool_add";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_mosquito_count_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_mosquito_count_add";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_mosquito_count_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_mosquito_count_edit";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_mosquito_count_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_mosquito_count_delete";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "search_premise." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "search_premise";
			$api_file_name = "premise.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_type_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_type_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "search_fiber_inquiry." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "search_fiber_inquiry";
			$api_file_name = "fiber_inquiry.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "search_treatment_product." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "search_treatment_product";
			$api_file_name = "general.php";
			include_once($site_api_path."api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "unit_multi_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "unit_multi_dropdown";
			$api_file_name = "general.php";
			if($RES_PARA['Server_LoginType']  == "country_user"){
				//Admin panel checking
				include_once($site_api_path.$api_file_name);
			}else{
				include_once($site_api_path."api_authentication.php");
			}
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;	
	case "agent_mosquito_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "agent_mosquito_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "test_method_mosquito_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "test_method_mosquito_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "getUserDropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "getUserDropdown";
			include_once($site_api_path."user.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "result_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "result_dropdown";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "nearby_fiber_inquiry." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "nearby_fiber_inquiry";
			$api_file_name = "fiber_inquiry.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "autoGoogleZoneFromLatlong." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "autoGoogleZoneFromLatlong";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "autoGoogleCheckCityState." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "autoGoogleCheckCityState";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "autoGooglegetState." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "autoGooglegetState";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "autoGooglegetZipcode." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "autoGooglegetZipcode";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "autoGooglegetCity." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "autoGooglegetCity";
			$api_file_name = "general.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "custom_layer_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "custom_layer_list";
			$api_file_name = "custom_layer.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "custom_layer_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "custom_layer_delete";
			$api_file_name = "custom_layer.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "custom_layer_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "custom_layer_add";
			$api_file_name = "custom_layer.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "custom_layer_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "custom_layer_edit";
			$api_file_name = "custom_layer.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;	
	case "custom_layer_map_data." . $req_ext:
			if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
				$request_type = "custom_layer_map_data";
				$api_file_name = "custom_layer.php";
				include_once($site_api_path . "api_authentication.php");
			}else {
				$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
			}
			break;
	case "autoSearchContact." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "autoSearchContact";
			$api_file_name = "contact.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "getContactData." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "getContactData";
			$api_file_name = "contact.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "dashboard_glance." . $req_ext:
			if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
				$request_type = "dashboard_glance";
				$api_file_name = "dashboard.php";
				include_once($site_api_path . "api_authentication.php");
			}else {
				$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
			}
			break;	
	case "dashboard_timelinechart." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "dashboard_timelinechart";
			$api_file_name = "dashboard.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "help_headers." . $req_ext:
			if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
				$request_type = "help_headers";
				$api_file_name = "help.php";
				include_once($site_api_path . "api_authentication.php");
			}else {
				$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
			}
			break;
	case "help_sliders." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "help_sliders";
			$api_file_name = "help.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_trap_setLabWorkCount." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_trap_setLabWorkCount";
			$api_file_name = "task_trap.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_mosquito_pool_setLabWorkCount." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_mosquito_pool_setLabWorkCount";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "task_mosquito_pool_checkCountByPool." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "task_mosquito_pool_checkCountByPool";
			$api_file_name = "lab_task.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_cluster_chart_default_Xaxes." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_cluster_chart_default_Xaxes";
			$api_file_name = "cluster_charts.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "cluster_chart_default_Yaxes." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "cluster_chart_default_Yaxes";
			$api_file_name = "cluster_charts.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_cluster_chart_default_X1axes." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_cluster_chart_default_X1axes";
			$api_file_name = "cluster_charts.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "create_cluster_charts." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "create_cluster_charts";
			$api_file_name = "cluster_charts.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_cluster_chart_details_from_axes." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_cluster_chart_details_from_axes";
			$api_file_name = "cluster_charts.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "create_heat_map." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "create_heat_map";
			$api_file_name = "heat_map.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "pie_chart_default_Yaxes." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "pie_chart_default_Yaxes";
			$api_file_name = "pie_charts.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_pie_chart_default_Xaxes." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_pie_chart_default_Xaxes";
			$api_file_name = "pie_charts.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "create_pie_charts." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "create_pie_charts";
			$api_file_name = "pie_charts.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_pie_chart_details_from_axes." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_pie_chart_details_from_axes";
			$api_file_name = "pie_charts.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "exportkml." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "exportkml";
			$api_file_name = "site_kml.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_map_filter_data." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_map_filter_data";
			$api_file_name = "map.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "get_map_cluster_layers." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "get_map_cluster_layers";
			$api_file_name = "map.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
    case "notification." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "notification";
			$api_file_name = "notification.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "network_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "network_list";
			$api_file_name = "network.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "network_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "network_delete";
			$api_file_name = "network.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "network_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "network_add";
			$api_file_name = "network.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "network_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "network_edit";
			$api_file_name = "network.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;	
	case "network_map_data." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "network_map_data";
			$api_file_name = "network.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "network_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "network_dropdown";
			$api_file_name = "network.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "zone_list." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "zone_list";
			$api_file_name = "zone.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "zone_delete." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "zone_delete";
			$api_file_name = "zone.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "zone_add." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "zone_add";
			$api_file_name = "zone.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "zone_edit." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "zone_edit";
			$api_file_name = "zone.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;	
	case "zone_map_data." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "zone_map_data";
			$api_file_name = "zone.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	case "zone_dropdown." . $req_ext:
		if(strtoupper($_SERVER['REQUEST_METHOD'])==API_REQUEST_MODE_CREATE) {
			$request_type = "zone_dropdown";
			$api_file_name = "zone.php";
			include_once($site_api_path . "api_authentication.php");
		}else {
			$response_data = api_invalidRequestMode(API_REQUEST_MODE_GET);
		}
		break;
	default:
		$r = HTTPStatus(404);
		$code = 404;
		$message = api_getMessage($req_ext, constant($code));
	
		$response_data = array("Code" => $code, "Message" => $message);
		break;
}
if($req_ext == "json"){
	header("Content-Type: application/json; charset=utf-8");
	echo json_encode( $response_data );
}
hc_exit();
?>
