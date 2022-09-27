<?php
include_once("security_audit_log.inc.php");

class PwaApi {

    function PwaApi() {
        $this->SALObj = new Security_audit_log();
    }

    public function user_login($param){
        //print_r($param); die;
        $country = $param['country'];
        $username = $param['email'];
        $password = $param['password'];

        $token = base64_encode(json_encode($param));

        $user = array(
            'status' => 200,
            'data' => [
                'email' => $param['email'],
                'token' => $token
            ]
        );

        return $user;
    }
}