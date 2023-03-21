<?php
include_once($controller_path . "user.inc.php");
use \Firebase\JWT\JWT;

if ($request_type == "user_login")
{	
    $vUsername = trim($RES_PARA['username']);
    $vPassword = trim($RES_PARA['password']);
    $ip = trim($RES_PARA['ip']);
    $sessionId = trim($RES_PARA['sessionId']);
    $login_flag = $RES_PARA["login_flag"];

	$jsonData =array();
    $message = array();

    if($vUsername == ""){
        $code = 1002;
        $message[]= api_getMessage($req_ext, sprintf(constant($code), 'user name'));
    }
    if($vPassword == ""){
        $code = 1002;
        $message[]= api_getMessage($req_ext, sprintf(constant($code), 'password'));
    }

    if(count($message) > 0){
        $rh = HTTPStatus(400);
        $response_data = array("Code" => 400, "Message" =>implode(" <br> ",$message) , "result" => $jsonData);
    }else{
        $message ="";
		$jsonData =array();
        //midnight time find logic start
        $todayStart = new DateTime("now");
        $currentTime = $todayStart->format("Y-m-d H:i:s");
        //$todayStart1 = new DateTime('tomorrow midnight');
        $todayStart1 = new DateTime('+1 month');
        //Expiration time
        $expirationTime = $todayStart1->format("Y-m-d H:i:s");
        $interval = date_diff(date_create($currentTime), date_create($expirationTime));
        $seconds = ($interval->h * 3600) + ($interval->i * 60) + $interval->s + ($interval->m * 3600 * 24 * 30);
        $time = time();
        $dDate = date_getSystemDateTime();
        //midnight time find logic end
        // DEFINE our cipher
        define('AES_256_CBC', 'aes-256-cbc');

        //random number for encrtyption(salt)
        $salt = bin2hex(openssl_random_pseudo_bytes(8));
        $iv = $salt; //cipher length

        $UserObj = new User();
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();
        $where_arr[] = "user_mas.\"vUsername\"='" . $vUsername . "'";

        $join_fieds_arr[] = "access_group_mas.\"vAccessGroup\"";
        $join_fieds_arr[] = "access_group_mas.\"iAccessType\"";
        $join_fieds_arr[] = "user_details.\"iCompanyId\"";
        $join_fieds_arr[] = "company_mas.\"vCompanyName\"";
        $join_fieds_arr[] = "company_mas.\"vAccessType\" as \"vCompanyAccessType\"";
        $join_arr[] = "LEFT JOIN user_details ON user_mas.\"iUserId\" = user_details.\"iUserId\"";
        $join_arr[] = "LEFT JOIN company_mas ON user_details.\"iCompanyId\" = company_mas.\"iCompanyId\"";
        $join_arr[] = "LEFT JOIN access_group_mas ON user_mas.\"iAGroupId\" = access_group_mas.\"iAGroupId\"";
        $UserObj->join_field = $join_fieds_arr;
        $UserObj->join = $join_arr;
        $UserObj->where = $where_arr;
        $UserObj->param['limit'] = "LIMIT 1";
        $UserObj->setClause();
        $result = $UserObj->recordset_list();
        
        $encryptedPassword = $result[0]["vPassword"];
        $descryptedPassword = decrypt_password($result);
        
        if ($result && ($result[0]["iStatus"] == '1')) {
            if ($descryptedPassword == $vPassword) {
                $user_id = $result[0]["iUserId"];
                $first_name = $result[0]["vFirstName"];
                $last_name = $result[0]["vLastName"];
                $iCompanyId = $result[0]["iCompanyId"];
                $vCompanyName = $result[0]["vCompanyName"];
                $vCompanyAccessType = $result[0]["vCompanyAccessType"];
                $vImage = $result[0]["vImage"];
                $iAGroupId = $result[0]["iAGroupId"];
                $iAccessType = $result[0]["iAccessType"];
                $vAccessGroup = gen_strip_slash($result[0]["vAccessGroup"]);
                $vName = gen_strip_slash($first_name) . " " . gen_strip_slash($last_name);
                $vUsername = $result[0]["vUsername"];
                $iStatus = $result[0]["iStatus"];
                //generate user sessionid for user online 
                $user_online_session_id = base64_encode($user_id.":".$vCountyName);
               
                #=========================INSERT INTO SESSION MAS=================================

                if($login_flag == 1){ //web

                    //ecrypted user sessionid
                    $encryptedSession = openssl_encrypt($sessionId, AES_256_CBC, $salt, 0, $iv);
                    
                    $sql = 'SELECT "vSessId" FROM session_mas WHERE "iUserId"=' . $user_id . ' LIMIT 1';
                    $rs_uro = $sqlObj->GetAll($sql);

                    if ($rs_uro[0]['vSessId']) {
                        $sql = "update session_mas set \"vSessId\" ='" . $encryptedSession . "' , \"dAddedDate\" ='" . $dDate . "' , \"sSalt\" ='" . $salt . "' , \"sExpireTime\" ='" . $expirationTime . "' where \"iUserId\"='" . $user_id . "'";
                        $db_sql = $sqlObj->Execute($sql);
                    } else {
                        $session_mas = "insert into session_mas (\"iUserId\", \"vSessId\",\"tData\", \"dAddedDate\", \"sSalt\", \"sExpireTime\") values ('" . $user_id . "'," . "'$encryptedSession'". "," . "''" . ", '" . $dDate . "', '" . $salt . "', '" . $expirationTime . "')";
                        $sqlObj->Execute($session_mas);
                    }
                    
                    $sql = 'SELECT "iURO" FROM user_recent_online WHERE "iUserId"=' . gen_allow_null_int($user_id) . ' AND "iLogin" = 1 ORDER BY "iURO" DESC LIMIT 1';
                    $rs_uro = $sqlObj->GetAll($sql);

                    if ($rs_uro[0]['iURO'] != "") {
                        $sql = 'UPDATE user_recent_online SET "vSessionId"=' . gen_allow_null_char($sessionId) . ', "vIP"=' . gen_allow_null_char($ip) . ', "vTimeEntry"=' . gen_allow_null_char($time) . ' WHERE "iURO" = ' . gen_allow_null_int($rs_uro[0]['iURO']) .'  AND "iLogin" = 1 ' ;
                        $sqlObj->Execute($sql);
                    }else{
                        $sql = 'INSERT INTO user_recent_online("iUserId", "vSessionId", "vIP", "vTimeEntry", "iLogin", "vLoginUserName") values (' . gen_allow_null_int($user_id) . ', ' . gen_allow_null_char($sessionId) . ', ' . gen_allow_null_char($ip) . ', ' . gen_allow_null_char($time) . ', 1, ' . gen_allow_null_char($vName) . ')';
                        $sqlObj->Execute($sql);
                    }
                }
                else {

                    //ecrypted user sessionid by userid
                    $encryptedSession = openssl_encrypt($user_id, AES_256_CBC, $salt, 0, $iv);
                    
                    $sql = 'SELECT "iURO" FROM user_recent_online WHERE "iUserId"=' . gen_allow_null_int($user_id) . ' AND "iLogin" = 2 ORDER BY "iURO" DESC LIMIT 1';
                    $rs_uro = $sqlObj->GetAll($sql);

                    if ($rs_uro[0]['iURO'] != "") {
                        $sql = 'UPDATE user_recent_online SET "vSessionId"=' . gen_allow_null_char($user_online_session_id) . ', "vIP"=' . gen_allow_null_char($ip) . ', "vTimeEntry"=' . gen_allow_null_char($time) . ' WHERE "iURO" = ' . gen_allow_null_int($rs_uro[0]['iURO']) .'  AND "iLogin" = 2 ' ;
                        $sqlObj->Execute($sql);
                    }else{
                        $sql = 'INSERT INTO user_recent_online("iUserId", "vSessionId", "vIP", "vTimeEntry", "iLogin", "vLoginUserName") values (' . gen_allow_null_int($user_id) . ', ' . gen_allow_null_char($user_online_session_id) . ', ' . gen_allow_null_char($ip) . ', ' . gen_allow_null_char($time) . ', 2, ' . gen_allow_null_char($vName) . ')';
                        $sqlObj->Execute($sql);
                    }
                }

                # FOR Last Login Details.
                $sql = "update user_mas set \"dLastAccess\" ='" . $dDate . "' where \"iUserId\"='" . $user_id . "'";
                $db_sql = $sqlObj->Execute($sql);
                
                # Query for Inserting Login Details.....
                $sql_logs = "insert into login_logs_mas (\"iID\", \"vIP\", \"dLoginDate\") values ('" . $user_id . "', '" . $ip . "', '" . $dDate . "')";
                $db_logs = $sqlObj->Execute($sql_logs);
                $id_log = $sqlObj->Insert_ID();
                                       
                if(file_exists($user_url.$iCountySaasId."/".$vImage)){
                    $vImage_url = $user_url.$iCountySaasId."/".$vImage;
                }else{
                    $vImage_url = $site_url."images/user.png";
                }
                /***JWT Token******* */
                    $token = array(
                        "iat" => $jwt_issued_at,
                        "exp" => null,
                        "iss" => $jwt_issuer,
                        "data" => array(
                            "id" => $user_id,
                            "county" => $vCountyName,
                            "username" => $vUsername
                        )
                    );
                    $jwt_resp = JWT::encode($token, $jwt_key);
                /***JWT Token******* */
                $jsonData = array(
                    "iUserId" => $user_id,
                    "iAGroupId" => $iAGroupId,
                    "vAccessGroup" => $vAccessGroup,
                    "iAccessType" => $iAccessType,
                    "iCompanyId" => $iCompanyId,
                    "vCompanyName" => $vCompanyName,
                    "vCompanyAccessType" => $vCompanyAccessType,
                    "vName" => $vName,
                    "vUsername" => $vUsername,
                    "iStatus" => $iStatus,
                    "vImage" => $vImage,
                    "vImage_url" => $vImage_url,
                    "id_log" => $id_log,
                    'sessionId' => $jwt_resp,
                    'encryptedSession' => $encryptedSession
                );
              
                $rh = HTTPStatus(200);
                $code = 2000;
                $message = api_getMessage($req_ext, constant($code));
                $response_data = array("Code" => 200, "Message" =>$message, "result" => $jsonData); 
            } else {
                $jsonData = '';
                $rh = HTTPStatus(400);
                $code = 2017;
                $message = api_getMessage($req_ext, constant($code));
                $response_data = array("Code" => 400, "Message" =>$message, "result" => $jsonData);
            }
        }else{
            $rh = HTTPStatus(400);
            $code = 2045;
            $message = api_getMessage($req_ext, constant($code));
            $response_data = array("Code" => 400, "Message" =>$message, "result" => $jsonData);
        }
    }
}else {
    $r = HTTPStatus(400);
    $code = 1001;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 400, "Message" =>$message);
}
?>