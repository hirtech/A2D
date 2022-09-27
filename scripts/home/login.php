<?php

include_once($lib_path.'adodb5/adodb.inc.php');
include_once($class_path."Global/Database.class.php");
if ($_POST) {
    if(isset($_POST['login_flag']) && $_POST['login_flag'] == 1){
        $sessionId = session_id();
        $ip = getIP();
        $arr_param = array(
            "login_flag" => $_POST["login_flag"],
            "username" => $_POST["vUsername"],
            "password" => $_POST['vPassword'],
            "sessionId" => $sessionId,
            'ip' => $ip
        );
        $API_URL = $site_api_url."user_login.json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           "Content-Type: application/json",
        ));
        $response = curl_exec($ch);
        curl_close($ch);  
        $result_arr = json_decode($response, true);
        //$result_data = json_decode( $result_arr['result'],true);
        $result_data =  $result_arr['result'];

        if($result_arr['Code'] == 200 && !empty($result_data)){
            $_SESSION["sess_iUserId" . $admin_panel_session_suffix] = $result_data["iUserId"];
              
            $_SESSION["sess_iAGroupId" . $admin_panel_session_suffix] = $result_data["iAGroupId"];
            $_SESSION["sess_vAccessGroup" . $admin_panel_session_suffix] = $result_data["vAccessGroup"];
            $_SESSION["sess_vName" . $admin_panel_session_suffix] = gen_strip_slash($result_data['vName']);
            $_SESSION["sess_vUsername" . $admin_panel_session_suffix] = gen_strip_slash($result_data["vUsername"]);
            $_SESSION["sess_iStatus" . $admin_panel_session_suffix] = $result_data["iStatus"];
            $_SESSION["sess_vImage" . $admin_panel_session_suffix] = gen_strip_slash($result_data["vImage"]);
            $_SESSION['sess_id_log' . $admin_panel_session_suffix] = $result_data['id_log'];
              
            $_SESSION["sess_vImage_url" . $admin_panel_session_suffix] = gen_strip_slash($result_data['vImage_url']);
            $_SESSION["we_api_session_id" . $admin_panel_session_suffix] = $result_data['sessionId'];
            
            $encryptedSession  = $result_data['encryptedSession'];
              
            if (!isset($_COOKIE["sessionid"])) {
                  setcookie("sessionid", $encryptedSession, strtotime("+$seconds seconds"), "/", "", "", TRUE);
            }

            if (!isset($_COOKIE["userid"])) {
                setcookie("userid", $result_data["iUserId"], strtotime("+$seconds seconds"), "/", "", "", TRUE);
            }
            $jsonData = array('login' => 1 , 'error_msg'=>$result_arr['Message']);
        }else{
          $jsonData = array('login' => 0 , 'error_msg'=>$result_arr['Message']);
        }
        echo json_encode($jsonData);
        hc_exit();    
    }
}
?>
