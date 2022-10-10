<?php
use \Firebase\JWT\JWT;

$req_sessionId = trim($RES_PARA['sessionId']);
try {
    // decode jwt
    $decoded_jwt = JWT::decode($req_sessionId, $jwt_key, array('HS256'));
    $Logginusername = $decoded_jwt->data->username;
    $Logginuserid = $decoded_jwt->data->id;
     
    $sql = 'select "vFirstName","vLastName" from user_mas WHERE "iUserId"='.gen_allow_null_int($Logginuserid).' LIMIT 1';
    $rs_recent = $sqlObj->GetAll($sql);
    //echo "<pre>";print_r($rs_recent);exit;
    if(!empty($rs_recent)){
        $Logginfullname = $rs_recent[0]['vFirstName']." ".$rs_recent[0]['vLastName'];
        include_once($site_api_path.$api_file_name);
    }else {
        $response_data = array("Code" => 401, "Message" =>"Login User not found!");
    }
}catch(Exception $e){
    // tell the user access denied  & show error message
    $response_data = array("Code" => 401, "Message" =>$e->getMessage());  
}
?>