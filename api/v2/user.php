<?php
include_once($controller_path . "user.inc.php");
include_once($function_path."image.inc.php");

if($request_type == "get_sync_user_data"){
   $last_sync_date = isset($RES_PARA['last_sync_date'])?trim($RES_PARA['last_sync_date']):"";
   $current_date = Date('Y-m-d');

   $where_arr = array();
   $join_arr = array();
   $join_fieds_arr = array();

   $userObj = new User();
   $userObj->user_clear_variable();
   if($last_sync_date != ""){
      $where_arr[] = " (( DATE(\"dAddedDate\") >= '" . $last_sync_date . "' AND DATE(\"dAddedDate\") <= '" . $current_date. "')  OR (DATE(\"dModifiedDate\") >= '" . $last_sync_date . "' AND DATE(\"dModifiedDate\") <= '" . $current_date. "' ))";
   }
   $userObj->join_field = $join_fieds_arr;
   $userObj->join = $join_arr;
   $userObj->where = $where_arr;
   $userObj->setClause();
   $rs_data = $userObj->recordset_list();
   $data = array();
   if(!empty($rs_data)){
        $data = $rs_data;
    }
    $total_record = count($data);
    $result = array('total_record' => $total_record, 'data' => $data );
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);  
}else if($request_type == "user_add"){
   $result = array();
   $iUserId = $filename = "";
   $vUsername = isset($RES_PARA['vUsername'])?trim($RES_PARA['vUsername']):"";
   $iAGroupId = isset($RES_PARA['iAGroupId'])?trim($RES_PARA['iAGroupId']):"";
   $iDepartmentId = isset($RES_PARA['iDepartmentId'])?trim($RES_PARA['iDepartmentId']):"";
   $vFirstName = isset($RES_PARA['vFirstName'])?trim($RES_PARA['vFirstName']):"";
   $vLastName = isset($RES_PARA['vLastName'])?trim($RES_PARA['vLastName']):"";
   $vPassword = isset($RES_PARA['vPassword'])?trim($RES_PARA['vPassword']):"";
   $vEmail = isset($RES_PARA['vEmail'])?trim($RES_PARA['vEmail']):"";
   $ip = isset($RES_PARA['ip'])?trim($RES_PARA['ip']):"";
   $iZoneId = isset($RES_PARA['iZoneId'])?trim($RES_PARA['iZoneId']):"";
   $iStatus = isset($RES_PARA['iStatus'])?trim($RES_PARA['iStatus']):"0";
   $iType = isset($RES_PARA['iType'])?trim($RES_PARA['iType']):"1";
   $vCompanyName = isset($RES_PARA['vCompanyName'])?trim($RES_PARA['vCompanyName']):"";
   $vCompanyNickName = isset($RES_PARA['vCompanyNickName'])?trim($RES_PARA['vCompanyNickName']):"";
   $vAddress1 = isset($RES_PARA['vAddress1'])?trim($RES_PARA['vAddress1']):"";
   $vAddress2 = isset($RES_PARA['vAddress2'])?trim($RES_PARA['vAddress2']):"";
   $vStreet = isset($RES_PARA['vStreet'])?trim($RES_PARA['vStreet']):"";
   $vCrossStreet = isset($RES_PARA['vCrossStreet'])?trim($RES_PARA['vCrossStreet']):"";
   $iZipcode = isset($RES_PARA['iZipcode'])?trim($RES_PARA['iZipcode']):"";
   $iStateId = isset($RES_PARA['iStateId'])?trim($RES_PARA['iStateId']):"";
   $iCountyId = isset($RES_PARA['iCountyId'])?trim($RES_PARA['iCountyId']):"";
   $iCityId = isset($RES_PARA['iCityId'])?trim($RES_PARA['iCityId']):"";
   $vLatitude = isset($RES_PARA['vLatitude'])?trim($RES_PARA['vLatitude']):"";
   $vLongitude = isset($RES_PARA['vLongitude'])?trim($RES_PARA['vLongitude']):"";
   $vPhone = isset($RES_PARA['vPhone'])?trim($RES_PARA['vPhone']):"";
   $vCell = isset($RES_PARA['vCell'])?trim($RES_PARA['vCell']):"";
   $vNickName = isset($RES_PARA['vNickName'])?trim($RES_PARA['vNickName']):"";
   $vFax = isset($RES_PARA['vFax'])?trim($RES_PARA['vFax']):"";
   $vADPFileNumber = isset($RES_PARA['vADPFileNumber'])?trim($RES_PARA['vADPFileNumber']):"";
   $zoneId_arr = isset($RES_PARA['zoneId'])?trim($RES_PARA['zoneId']):"";

 
   $err_msg = array();
   if($iAGroupId == ""){
      $err_msg[] = " Access group can not be blank";
   }
   if($vFirstName == ""){
      $err_msg[] = " First Name can not be blank";
   }
   if($vLastName == ""){
      $err_msg[] = " Last Name can not be blank";
   }
   if($vUsername == ""){
      $err_msg[] = " User Name can not be blank";
   }
   if($vPassword == ""){
      $err_msg[] = " Password can not be blank";
   }

  //echo "ddd<pre>";print_r($err_msg)exit;
   if(empty($err_msg)){
       $where_arr = array();
       $where_arr[] = "user_mas.\"vUsername\" = '" . $vUsername . "'";

       //echo "ddd";exit;
       // echo "Df";exit;
       $UserObj = new User();
       // //$UserObj->user_clear_variable();
       $UserObj->where = $where_arr;
       $UserObj->param['limit'] = " LIMIT 1";
       $UserObj->setClause();
       $rs_user = $UserObj->recordset_list();
       
      if (count($rs_user) != 0) {
         $r = HTTPStatus(500);
         $response_data = array("Code" => 500 , "Message" => " Username already exist");
      }else{

         $encryptedPassword = encrypt_password($vPassword);

         $insert_array = array(
            "iAGroupId" => $iAGroupId,
            "iDepartmentId" => ($iDepartmentId!="")?explode(',',$iDepartmentId):"",
            "iZoneId" => $iZoneId,
            "vFirstName" => addslashes($vFirstName),
            "vLastName" => addslashes($vLastName),
            "vUsername" => addslashes($vUsername),
            "vPassword" => $encryptedPassword['encryptedPassword'],
            "vEmail" => addslashes($vEmail),
            "vFromIP" => $ip,
            "iStatus" => $iStatus,
            "iType" => $iType,
            "dDate" => date_getSystemDateTime(),
            "vCompanyName" => addslashes($vCompanyName),
            "vCompanyNickName" => addslashes($vCompanyNickName),
            "vAddress1"  => addslashes($vAddress1),
            "vAddress2"  => addslashes($vAddress2),
            "vStreet"    => addslashes($vStreet),
            "vCrossStreet" => addslashes($vCrossStreet),
            "iZipcode"       => $iZipcode,
            "iStateId"      => $iStateId,
            "iCountyId"       => $iCountyId,
            "iCityId"        => $iCityId,
            "iZoneId"       => $iZoneId,
            "vLatitude"     => $vLatitude,
            "vLongitude"    => $vLongitude,
            "vPhone" => addslashes($vPhone),
            "vCell" => addslashes($vCell),
            "vNickName" => "",
            "vFax" => addslashes($vFax),
            "vADPFileNumber" => addslashes($vADPFileNumber),
            "sSalt" => addslashes($encryptedPassword['salt']),
            "vImage" => $file_name,
            "zoneId_arr" => ($zoneId_arr!="")?explode(',',$zoneId_arr):""
         );

         if($FILES_PARA["vImage"]['name'] != ""){
               $folder = create_image_folder($sess_iCountySaasId,$user_path);

               $file_arr = img_fileUpload("vImage", $folder, '', $valid_ext = array('jpg','jpeg','gif','png'));

               $file_name = $file_arr[0];
               $file_msg =  $file_arr[1];

               $insert_array["vImage"] = $file_name;
         } 
         

         $UserObj->user_clear_variable();
         $UserObj->insert_arr = $insert_array;
         $UserObj->setClause();
         $iUserId = $UserObj->add_records();

         
         if($iUserId){
            $rh = HTTPStatus(200);
            $code = 2000;
            $message = api_getMessage($req_ext, constant($code));
            $response_data = array("Code" => 200, "Message" => MSG_ADD, "iUserId" => $iUserId);
         }else{
            $r = HTTPStatus(500);
            $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
         }
      }
   }else{
       $r = HTTPStatus(500);
       $response_data = array("Code" => 500 , "Message" => implode("\n",$err_msg));
   }
}else if($request_type == "user_edit"){
   
   $result = array();
   $iUserId = isset($RES_PARA['iUserId'])?trim($RES_PARA['iUserId']):"0";
   $vUsername = isset($RES_PARA['vUsername'])?trim($RES_PARA['vUsername']):"";
   $iAGroupId = isset($RES_PARA['iAGroupId'])?trim($RES_PARA['iAGroupId']):"";
   $iDepartmentId = isset($RES_PARA['iDepartmentId'])?trim($RES_PARA['iDepartmentId']):"";
   $vFirstName = isset($RES_PARA['vFirstName'])?trim($RES_PARA['vFirstName']):"";
   $vLastName = isset($RES_PARA['vLastName'])?trim($RES_PARA['vLastName']):"";
   $vPassword = isset($RES_PARA['vPassword'])?trim($RES_PARA['vPassword']):"";
   $vEmail = isset($RES_PARA['vEmail'])?trim($RES_PARA['vEmail']):"";
   $ip = isset($RES_PARA['ip'])?trim($RES_PARA['ip']):"";
   $iZoneId = isset($RES_PARA['iZoneId'])?trim($RES_PARA['iZoneId']):"";
   $iStatus = isset($RES_PARA['iStatus'])?trim($RES_PARA['iStatus']):"0";
   $iType = isset($RES_PARA['iType'])?trim($RES_PARA['iType']):"1";
   $vCompanyName = isset($RES_PARA['vCompanyName'])?trim($RES_PARA['vCompanyName']):"";
   $vCompanyNickName = isset($RES_PARA['vCompanyNickName'])?trim($RES_PARA['vCompanyNickName']):"";
   $vAddress1 = isset($RES_PARA['vAddress1'])?trim($RES_PARA['vAddress1']):"";
   $vAddress2 = isset($RES_PARA['vAddress2'])?trim($RES_PARA['vAddress2']):"";
   $vStreet = isset($RES_PARA['vStreet'])?trim($RES_PARA['vStreet']):"";
   $vCrossStreet = isset($RES_PARA['vCrossStreet'])?trim($RES_PARA['vCrossStreet']):"";
   $iZipcode = isset($RES_PARA['iZipcode'])?trim($RES_PARA['iZipcode']):"";
   $iStateId = isset($RES_PARA['iStateId'])?trim($RES_PARA['iStateId']):"";
   $iCountyId = isset($RES_PARA['iCountyId'])?trim($RES_PARA['iCountyId']):"";
   $iCityId = isset($RES_PARA['iCityId'])?trim($RES_PARA['iCityId']):"";
   $vLatitude = isset($RES_PARA['vLatitude'])?trim($RES_PARA['vLatitude']):"";
   $vLongitude = isset($RES_PARA['vLongitude'])?trim($RES_PARA['vLongitude']):"";
   $vPhone = isset($RES_PARA['vPhone'])?trim($RES_PARA['vPhone']):"";
   $vCell = isset($RES_PARA['vCell'])?trim($RES_PARA['vCell']):"";
   $vNickName = isset($RES_PARA['vNickName'])?trim($RES_PARA['vNickName']):"";
   $vFax = isset($RES_PARA['vFax'])?trim($RES_PARA['vFax']):"";
   $vADPFileNumber = isset($RES_PARA['vADPFileNumber'])?trim($RES_PARA['vADPFileNumber']):"";
   $zoneId_arr = isset($RES_PARA['zoneId'])?trim($RES_PARA['zoneId']):"";

   $err_msg = array();
   
   if($iUserId == "0" || $iUserId == ""){
      $err_msg[] = "  User id is missing";
   }
   if($vFirstName == ""){
      $err_msg[] = " First Name can not be blank";
   }
   if($vLastName == ""){
      $err_msg[] = " Last Name can not be blank";
   }
   if($iAGroupId == ""){
      $err_msg[] = " Access group can not be blank";
   }

  //echo "ddd<pre>";print_r($err_msg)exit;
   if(empty($err_msg)){
       $where_arr = array();
       $where_arr[] = "user_mas.\"vUsername\" = '" . $vUsername . "'";
       $where_arr[] = "user_mas.\"iUserId\" != '" . $iUserId . "'";

       $UserObj = new User();
       // //$UserObj->user_clear_variable();
       $UserObj->where = $where_arr;
       $UserObj->param['limit'] = " LIMIT 1";
       $UserObj->setClause();
       $rs_user = $UserObj->recordset_list();
       
      if (count($rs_user) != 0) {
         $r = HTTPStatus(500);
         $response_data = array("Code" => 500 , "Message" => " Username already exist");
      }else{
      
         $file_name = $file_msg = "";

         $update_arr = array(
            "iUserId" => $iUserId,
            "iAGroupId" => $iAGroupId,
            "iDepartmentId" => ($iDepartmentId!="")?explode(',',$iDepartmentId):"",
            "iZoneId" => $iZoneId,
            "vFirstName" => addslashes($vFirstName),
            "vLastName" => addslashes($vLastName),
            "vUsername" => addslashes($vUsername),
            "vEmail" => addslashes($vEmail),
            "vFromIP" => $ip,
            "iStatus" => $iStatus,
            "iType" => $iType,
            "dDate" => date_getSystemDateTime(),
            "vCompanyName" => addslashes($vCompanyName),
            "vCompanyNickName" => addslashes($vCompanyNickName),
            "vAddress1"  => addslashes($vAddress1),
            "vAddress2"  => addslashes($vAddress2),
            "vStreet"    => addslashes($vStreet),
            "vCrossStreet" => addslashes($vCrossStreet),
            "iZipcode"       => $iZipcode,
            "iStateId"      => $iStateId,
            "iCountyId"       => $iCountyId,
            "iCityId"        => $iCityId,
            "iZoneId"       => $iZoneId,
            "vLatitude"     => $vLatitude,
            "vLongitude"    => $vLongitude,
            "vPhone" => addslashes($vPhone),
            "vCell" => addslashes($vCell),
            "vNickName" => "",
            "vFax" => addslashes($vFax),
            "vADPFileNumber" => addslashes($vADPFileNumber),
            "zoneId_arr" => ($zoneId_arr!="")?explode(',',$zoneId_arr):""
            );

         if($vPassword != ""){
            /*$encryptedPassword = encrypt_password($vPassword);
            $update_arr['vPassword'] = $encryptedPassword['encryptedPassword'];
            $update_arr['sSalt'] = addslashes($encryptedPassword['salt']);*/
            $update_arr['vPassword'] =$vPassword;
         }

         if($FILES_PARA["vImage"]['name'] != ""){
               $folder = create_image_folder($sess_iCountySaasId,$user_path);
               $file_arr = img_fileUpload("vImage", $folder, '', $valid_ext = array('jpg','jpeg','gif','png'));

               $file_name = $file_arr[0];
               $file_msg =  $file_arr[1];
               $update_arr["vImage"] = $file_name;
         } 

         //echo "<pre>";print_r($update_arr);exit;
         //$UserObj->user_clear_variable();
         $UserObj->update_arr = $update_arr;
         $UserObj->setClause();
         $rs_update = $UserObj->update_records();

         
         if($rs_update){
            $rh = HTTPStatus(200);
            $code = 2000;
            $message = api_getMessage($req_ext, constant($code));
            $response_data = array("Code" => 200, "Message" => MSG_UPDATE);
         }else{
            $r = HTTPStatus(500);
            $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
         }
      }
   }else{
       $r = HTTPStatus(500);
       $response_data = array("Code" => 500 , "Message" => implode("\n",$err_msg));
   }
} else if($request_type == "edit_profile"){


   $result = array();
   $iUserId = isset($RES_PARA['iUserId'])?trim($RES_PARA['iUserId']):"0";
   $vFirstName = isset($RES_PARA['vFirstName'])?trim($RES_PARA['vFirstName']):"";
   $vLastName = isset($RES_PARA['vLastName'])?trim($RES_PARA['vLastName']):"";
   $vPassword = isset($RES_PARA['vPassword'])?trim($RES_PARA['vPassword']):"";
   $vConfPassword = isset($RES_PARA['vConfirmPassword'])?trim($RES_PARA['vConfirmPassword']):"";
   $err_msg = array();

   if($iUserId == "0" || $iUserId == ""){
      $err_msg[] = "User id is missing";
   }
   if($vFirstName == ""){
      $err_msg[] = "First Name can not be blank";
   }
   if($vLastName == ""){
      $err_msg[] = "Last Name can not be blank";
   }

   if($vPassword == "" && $vConfPassword != ""){
      $err_msg[] = "Password can not be blank";
   }else if($vPassword != "" && $vConfPassword == ""){
      $err_msg[] = "Confirm Password can not be blank";
   }else if($vPassword != "" && $vConfPassword != ""){
         if($vPassword != $vConfPassword){
            $err_msg[] = "Confirm password doesn't match with password";
         }
   }

   if (empty($err_msg)){

      $update_array = array(
         "iUserId" => $iUserId,
        "vFirstName" => addslashes($vFirstName),
        "vLastName" => addslashes($vLastName),
        "vPassword" => $vPassword
      );

      if($vPassword != ""){
         /*$encryptedPassword = encrypt_password($vPassword);
            $update_arr['vPassword'] = $encryptedPassword['encryptedPassword'];
            $update_arr['sSalt'] = addslashes($encryptedPassword['salt']);*/
      }

      $UserObj = new User();
      $UserObj->update_arr = $update_array;
      $rs_db = $UserObj->update_user();

      if($rs_db){
         $rh = HTTPStatus(200);
         $code = 2000;
         $message = api_getMessage($req_ext, constant($code));
         $response_data = array("Code" => 200, "Message" => "Profile edited successfully");
      }else{
         $r = HTTPStatus(500);
         $response_data = array("Code" => 500 , "Message" => 'Error - in Profile Update.');
      }
   }else{
       $r = HTTPStatus(500);
       $response_data = array("Code" => 500 , "Message" => implode("\n",$err_msg));
   }   
} else if($request_type == "getUserDropdown"){ 

   $user_arr = array();
   $where_arr = array();
   $join_fieds_arr = array();
   $join_arr = array();
   $where_arr[] = " user_mas.\"iStatus\" = 1 ";
   $UserObj = new User();
   $UserObj->join_field = $join_fieds_arr;
   $UserObj->join = $join_arr;
   $UserObj->where = $where_arr;
   $UserObj->param['limit'] = "";
   $UserObj->param['order_by'] = '"vFirstName"';
   $UserObj->setClause();
   $UserObj->debug_query = false;
   $rs_user = $UserObj->recordset_list();
   $ui = count($rs_user);

   if($ui > 0){
      for($u=0;$u<$ui;$u++){
         $user_arr[] = array('iUserId' => $rs_user[$u]['iUserId'], 'vName' =>  $rs_user[$u]['vFirstName']." ".$rs_user[$u]['vLastName']);
      }
   }

   $rh = HTTPStatus(200);
   $code = 2000;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 200, "Message" => $message, "result" => $user_arr);  
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>