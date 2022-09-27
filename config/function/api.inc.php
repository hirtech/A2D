<?php
# Created by KV as on 7 April 2017
# Function will return API key from curl header options....

function api_displayErrorjson($err_code, $file_name)
{
	//$err_msg = constant("E_".$err_code);
	$err_msg = constant($err_code);
	$json_arr = array('Code' => $err_code, 'Message' => $err_msg);
	return $json_arr;
}

# req_format = xml | json
# err_code = Error Code
function api_displayError($req_format, $err_code, $file_name) {
	if($req_format == "xml") {
		$error_arr = api_displayErrorXML($err_code, $file_name);
	}
	else {
		$error_arr = api_displayErrorjson($err_code, $file_name);
	}
	return $error_arr;
}

function api_getDateFormat($text) {
	if($text=="" || $text=="0000-00-00") return "";
	else return date("Y-m-d", strtotime($text));
}

function api_getDateTimeFormat($text) {
	if($text=="" || $text=="0000-00-00 00:00:00") return "";
	else return date("Y-m-d H:i:s", strtotime($text));
}

function api_validDateFormat($date)
{
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
    {
        return true;
    }else{
        return false;
    }
}

function api_validDateTimeFormat($date)
{
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (0[0-9]|1[0-9]|2[0-4]):([0-5][0-9])$/",$date))
    {
        return true;
    }else{
        return false;
    }
}

function api_validEmailCheck($email)
{
	return preg_match("/^[a-zA-Z0-9\=._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/", $email); 
}

function api_isEmpty($field){
	return ((isset($field) && empty($field))?true:false);
}

function api_validPagingParams($min_size=10, $max_size=1000)
{
	global $RES_PARA;
	//echo $min_size, " === ", $max_size;exit;
	$code = $message = "";
	if(isset($RES_PARA['Page']) && !preg_match('/^[0-9]+$/', $RES_PARA['Page'])) {
		$code = 1009;
		$message = constant($code);
	}
	else if(isset($RES_PARA['PageSize'])) {
		if(!preg_match('/^[0-9]+$/', $RES_PARA['PageSize'])) {
			$code = 1010;
			$message = sprintf(constant($code), $min_size, $max_size);
		}
		else {
			$RES_PARA['PageSize'] = (int)$RES_PARA['PageSize'];
			if($RES_PARA['PageSize']<$min_size || $RES_PARA['PageSize']>$max_size) {
				$code = 1010;
				$message = sprintf(constant($code), $min_size, $max_size);
			}
		}
	}
	return array("code"=>$code, "message"=>$message);
}

# Function will return extension of passed argument File.....
function api_getFileExtension($filename) {
	if($filename != "") {
		return strtolower(substr($filename, strripos($filename, ".")+1));
	}
	else return "";
}

function api_isValidBoolean($val){
	return ($val==1 || $val=="" || $val=="true" || $val=="false");
}

function api_getBoolean($v, $res_type='json'){
	return $v;
}

function HTTPStatus($num) {    
	$http_protocol = "HTTP/1.0";
	if(isset($_SERVER['SERVER_PROTOCOL']) && stripos($_SERVER['SERVER_PROTOCOL'],"HTTP") >= 0){        
		$http_protocol = $_SERVER['SERVER_PROTOCOL'];     
	}    
	$http = array(
		100 => $http_protocol . ' 100 Continue',        
		101 => $http_protocol . ' 101 Switching Protocols',        
		200 => $http_protocol . ' 200 OK',        
		201 => $http_protocol . ' 201 Created',        
		202 => $http_protocol . ' 202 Accepted',        
		203 => $http_protocol . ' 203 Non-Authoritative Information',        
		204 => $http_protocol . ' 204 No Content',        
		205 => $http_protocol . ' 205 Reset Content',        
		206 => $http_protocol . ' 206 Partial Content',        
		300 => $http_protocol . ' 300 Multiple Choices',        
		301 => $http_protocol . ' 301 Moved Permanently',        
		302 => $http_protocol . ' 302 Found',        
		303 => $http_protocol . ' 303 See Other',        
		304 => $http_protocol . ' 304 Not Modified',        
		305 => $http_protocol . ' 305 Use Proxy',        
		307 => $http_protocol . ' 307 Temporary Redirect',        
		400 => $http_protocol . ' 400 Bad Request',        
		401 => $http_protocol . ' 401 Unauthorized',        
		402 => $http_protocol . ' 402 Payment Required',        
		403 => $http_protocol . ' 403 Forbidden',        
		404 => $http_protocol . ' 404 Not Found',        
		405 => $http_protocol . ' 405 Method Not Allowed',        
		406 => $http_protocol . ' 406 Not Acceptable',        
		407 => $http_protocol . ' 407 Proxy Authentication Required',        
		408 => $http_protocol . ' 408 Request Time-out',        
		409 => $http_protocol . ' 409 Conflict',        
		410 => $http_protocol . ' 410 Gone',        
		411 => $http_protocol . ' 411 Length Required',        
		412 => $http_protocol . ' 412 Precondition Failed',        
		413 => $http_protocol . ' 413 Request Entity Too Large',        
		414 => $http_protocol . ' 414 Request-URI Too Large',        
		415 => $http_protocol . ' 415 Unsupported Media Type',        
		416 => $http_protocol . ' 416 Requested Range Not Satisfiable',        
		417 => $http_protocol . ' 417 Expectation Failed',        
		500 => $http_protocol . ' 500 Internal Server Error',        
		501 => $http_protocol . ' 501 Not Implemented',        
		502 => $http_protocol . ' 502 Bad Gateway',        
		503 => $http_protocol . ' 503 Service Unavailable',        
		504 => $http_protocol . ' 504 Gateway Time-out',        
		505 => $http_protocol . ' 505 HTTP Version Not Supported'
	);    
	header($http[$num]);
	global $res_header;
	$res_header = array('code' => $num, 'error' => $http[$num]);
	return array(
		'code' => $num,            
		'error' => $http[$num],        
	);
}

function api_invalidRequestMode($mode){
	if($mode=="DELETE" || $mode=="PUT" || $mode=="POST" || $mode=="GET"){
		$r = HTTPStatus(405);
		$code = 405;
		$message = constant($code);
		$response_data = array("Code" => $code, "Message" => $message);
		return $response_data;
	}
}

# ----------------------------------------------------------
# function will get the period
# ----------------------------------------------------------
function api_getSegmentPeriod($iPeriod) {
	switch($iPeriod) {
		case "-1":
			$period_nm = "Custom";
		break;
		case "0":
			$period_nm = "All Time";
		break;
		case "1":
			$period_nm = "Today";
		break;
		case "2":
			$period_nm = "Yesterday";
		break;
		case "3":
			$period_nm = "This Week";
		break;
		case "4":
			$period_nm = "Last Week";
		break;
		case "5":
			$period_nm = "Last 7 Days";
		break;
		case "6":
			$period_nm = "This Month";
		break;
		case "7":
			$period_nm = "Previous Month";
		break;
		default:
			$period_nm = "";
		break;
	}
	return $period_nm;
}

# -------------------------------------------------------------
# function will get the message using displayAjaxData() in xml
# -------------------------------------------------------------
function api_getMessage($type, $msg) {
	return ($type == "xml") ? displayAjaxData($msg, 0) : $msg;
}


# -------------------------------------------------------------
# function will check the input JSON string is valid or not....
# -------------------------------------------------------------
/*
Note: Implementation of this function is still remaining. Not able to try it bcoz developer is working on the functionality.
0 = JSON_ERROR_NONE
1 = JSON_ERROR_DEPTH
2 = JSON_ERROR_STATE_MISMATCH
3 = JSON_ERROR_CTRL_CHAR
4 = JSON_ERROR_SYNTAX
5 = JSON_ERROR_UTF8
*/
function api_validateJSONString($json) {
	$error_code=0;
	$msg="";
	foreach ($json as $string) {
		$error_code = json_decode($string);
		if($error_code) {
			switch (json_last_error()) {
				/*case 0: //JSON_ERROR_NONE:
					echo ' - No errors';
				break;*/
				case 1: //JSON_ERROR_DEPTH:
					$msg = 'Maximum stack depth exceeded';
				break;
				case 2: //JSON_ERROR_STATE_MISMATCH:
					$msg = 'Underflow or the modes mismatch';
				break;
				case 3: //JSON_ERROR_CTRL_CHAR:
					$msg = 'Unexpected control character found';
				break;
				case 4: //JSON_ERROR_SYNTAX:
					$msg = 'Syntax error, malformed JSON';
				break;
				case 5: //JSON_ERROR_UTF8:
					$msg = 'Malformed UTF-8 characters, possibly incorrectly encoded';
				break;
				default:
					$msg = 'Unknown error';
				break;
			}
		}
	}
	return array($error_code, $msg);
}

# Function will return Member id and primary id of used API key if it is match with our database records else return false with proper error message...
function api_getCustomerIdsFromAPIKey($api_key) {
	$api_key = trim($api_key);
	if($api_key == "") {
		$ret_arr = array(0, "Invaid API key or API key not found.", 1000);
	}
	else {
		global $sqlObj;
		$s = "SELECT iAAId, iCustomerId, iCallLimit, iCallLimitUsed, iStatus FROM api_access_mgmt WHERE vAPIKey = '".$api_key."' LIMIT 1";
		$r = $sqlObj->select($s);
		//print_r($r);exit;
		if(count($r)==0) {
			$ret_arr=array(0, "Invaid API key or API key not found.", 1000);
		}
		else {
			$iStatus 		= $r[0]['iStatus'];
			$iCallLimit 	= $r[0]['iCallLimit'];
			$iCallLimitUsed = $r[0]['iCallLimitUsed'];
			if($iStatus != 1) {
				$ret_arr=array(0, "Request could not be submitted as API key is disabled.", 1001);
			}else if($iCallLimit <= $iCallLimitUsed) {
				$ret_arr=array(0, "Your API call could not be submitted due to the max limit.", 1002);
			}
			else {
				$iCustomerId = $r[0]['iCustomerId'];
				$iAAId = $r[0]['iAAId'];
				$s_mem = "SELECT iCustomerId FROM customer WHERE iCustomerId = '".$iCustomerId."' AND iStatus=1 LIMIT 1";
				$r_mem = $sqlObj->select($s_mem);
				//echo "<pre>"; print_r($r_mem);
				if(count($r_mem)==0) {
					$ret_arr = array(0, "You can not access API as account has been inactivated.", 1003);
				}
				else {
					$dLastCall 		= date_getSystemDateTime();
					$vLastCallIP 	= getIP();
					$q = "UPDATE api_access_mgmt SET iCallLimitUsed=iCallLimitUsed+1, dLastCall='".$dLastCall."', vLastCallIP='".$vLastCallIP."' WHERE iAAId = '".$iAAId."' LIMIT 1";
					$db = $sqlObj->execute($q);
					// Update API Call Informations
					$ret_arr = array(1, $iCustomerId, $iAAId);
				}
			}
		}
	}

	//echo "<pre>"; print_r($ret_arr);
	return $ret_arr;
}
?>

