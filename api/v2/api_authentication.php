<?php
use \Firebase\JWT\JWT;

$req_sessionId = trim($RES_PARA['sessionId']);
if($request_type != 'county_user_login'){
    // if decode succeed, show user details
    try {
        //echo $_SESSION["sess_HostvCountyName".$admin_panel_session_suffix];exit;

        // decode jwt
        $decoded_jwt = JWT::decode($req_sessionId, $jwt_key, array('HS256'));
        //if($decoded_jwt->data->county == $_SESSION["sess_HostvCountyName".$admin_panel_session_suffix]){
            // set response code Access granted
            //http_response_code(200);
            $Logginusername = $decoded_jwt->data->username;
            $Logginuserid = $decoded_jwt->data->id;
            //echo  $Loggedusername;exit;
            
             
            $sql = 'select "vFirstName","vLastName" from user_mas WHERE "iUserId"='.gen_allow_null_int($Logginuserid).' LIMIT 1';
            $rs_recent = $sqlObj->GetAll($sql);
            //echo "<pre>";print_r($rs_recent);exit;
            $Logginfullname = $rs_recent[0]['vFirstName']." ".$rs_recent[0]['vLastName'];
            $sess_iCountySaasId = $_SESSION["sess_iCountySaasId".$admin_panel_session_suffix];
            include_once($site_api_path.$api_file_name);
            /* 
            $token_user_id = $decoded_jwt->data->id;
            $token_county_name = $decoded_jwt->data->county;
            $encode_rec_onine_token = base64_encode($token_user_id.":".$token_county_name);
            $sql = 'select "vSessionId" from user_recent_online WHERE "iUserId"='.gen_allow_null_int($token_user_id).' AND "iLogin" = 2 ORDER BY "iURO" DESC LIMIT 1';
            $rs_recent = $sqlObj->GetAll($sql);
            if(!empty($rs_recent) && $rs_recent[0]['vSessionId'] != ""){
                if($encode_rec_onine_token == $rs_recent[0]['vSessionId']){
                    include_once($site_api_path.$api_file_name);
                }else{
                    $code = 401;
                    $resp = HTTPStatus(401);
                    $response_data = array("Code" => 401, "Message" => $resp['error']);
                }
            }else{
                $code = 401;
                $resp = HTTPStatus(401);
                $response_data = array("Code" => 401, "Message" => $resp['error']);
            }*/
        /*}else{
            $code = 401;
            $resp = HTTPStatus(401);
            $code = 7501;
            $message = api_getMessage($req_ext, constant($code));
            $response_data = array("Code" => 401, "Message" => $message);
        } */     

    }catch(Exception $e){
        // tell the user access denied  & show error message
        $response_data = array("Code" => 401, "Message" =>$e->getMessage());  
    }
}else{
    include_once($site_api_path.$api_file_name);
}
     



?>