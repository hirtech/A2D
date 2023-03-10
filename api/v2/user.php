<?php
include_once($controller_path . "user.inc.php");
include_once($controller_path . "login_history.inc.php");
include_once($function_path."image.inc.php");

if($request_type == "user_list"){
	$UserObj = new User();
	$where_arr = array();
    if(!empty($RES_PARA)){
        $vSName				= trim($RES_PARA['vSName']);
        $vSUsername			= trim($RES_PARA['vSUsername']);
        $vSEmail			= trim($RES_PARA['vSEmail']);
        $iStatus			= trim($RES_PARA['iStatus']);

        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];

        $vName				= $RES_PARA['vName'];
        $vNameDD			= $RES_PARA['vNameDD'];
        $vEmail				= $RES_PARA['vEmail'];
        $vEmailDD			= $RES_PARA['vEmailDD'];
        $vUsername			= $RES_PARA['vUsername'];
        $vUsernameDD		= $RES_PARA['vUsernameDD'];
        $iDepartmentId		= $RES_PARA['iDepartmentId'];
        $iAGroupId			= $RES_PARA['iAGroupId'];
        $iCompanyId          = $RES_PARA['iCompanyId'];
    } 
    if ($vSName != "") {
       $where_arr[] = "((concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '".$vSName."%') OR (user_mas.\"vFirstName\" LIKE '".$vSName."%') OR (user_mas.\"vLastName\" LIKE '".$vSName."%'))";
    }
    if ($vSCompanyName != "") {
        $where_arr[] = "user_details.\"vCompanyName\"='" . $vSCompanyName . "'";
    }
    if ($vSUsername != "") {
        $where_arr[] = "user_mas.\"vUsername\"='" . $vSUsername . "'";
    }
    if ($vSUsername != "") {
        $where_arr[] = "user_mas.\"vUsername\"='" . $vSUsername . "'";
    }
    if ($vSEmail != "") {
        $where_arr[] = "user_mas.\"vEmail\"='" . $vSEmail. "'";
    }
    if ($iStatus != "") {
        if(strtolower($iStatus) == "active"){
            $where_arr[] = "user_mas.\"iStatus\" = '1'";
        }
        else if(strtolower($iStatus) == "inactive"){
            $where_arr[] = "user_mas.\"iStatus\" = '0'";
        }
    }
    if ($vName != "") {
        if ($vNameDD != "") {
            if ($vNameDD == "Begins") {
                $where_arr[] = "user_mas.\"vFirstName\" LIKE '".$vName."%'";
            } else if ($vNameDD == "Ends") {
                $where_arr[] = "user_mas.\"vLastName\" LIKE '%".$vName."'";
            } else if ($vNameDD == "Contains") {
                $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '%".$vName."%'";
            } else if ($vNameDD == "Exactly") {
                $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") = '".$vName."'";
            }
        } else {
            $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '".$vName."%'";
        }
    }
    if ($vEmail != "") {
        if ($vEmailDD != "") {
            if ($vEmailDD == "Begins") {
                $where_arr[] = 'user_mas."vEmail" LIKE \''.$vEmail.'%\'';
            } else if ($vEmailDD == "Ends") {
                $where_arr[] = 'user_mas."vEmail" LIKE \'%'.$vEmail.'\'';
            } else if ($vEmailDD == "Contains") {
                $where_arr[] = 'user_mas."vEmail" LIKE \'%'.$vEmail.'%\'';
            } else if ($vEmailDD == "Exactly") {
                $where_arr[] = 'user_mas."vEmail" = \''.$vEmail.'\'';
            }
        } else {
            $where_arr[] = 'user_mas."vEmail" LIKE \''.$vEmail.'%\'';
        }
    }

    if ($vUsername != "") {
        if ($vUsernameDD != "") {
            if ($vUsernameDD == "Begins") {
                $where_arr[] = 'user_mas."vUsername" LIKE \''.$vUsername.'%\'';
            } else if ($vUsernameDD == "Ends") {
                $where_arr[] = 'user_mas."vUsername" LIKE \'%'.$vUsername.'\'';
            } else if ($vUsernameDD == "Contains") {
                $where_arr[] = 'user_mas."vUsername" LIKE \'%'.$vUsername.'%\'';
            } else if ($vUsernameDD == "Exactly") {
                $where_arr[] = 'user_mas."vUsername" = \''.$vUsername.'\'';
            }
        } else {
            $where_arr[] = 'user_mas."vUsername" LIKE \''.$vUsername.'%\'';
        }
    }

    if ($iDepartmentId != "") {
       	$where_arr[] = 'user_mas."iUserId" IN (SELECT user_department."iUserId" FROM user_department WHERE user_department."iDepartmentId" = '.$iDepartmentId.')';
    }

    if ($iAGroupId != "") {
        $where_arr[] = "user_mas.\"iAGroupId\"='".$iAGroupId."'";
    }

    if ($iCompanyId != "") {
        $where_arr[] = "user_details.\"iCompanyId\"='".$iCompanyId."'";
    }

    switch ($display_order) {
        case "0":
        	$sortname = "user_mas.\"iUserId\"";
        break;
        case "1":
        	$sortname = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\" )";
        break;
        case "2":
        	$sortname = "user_mas.\"vEmail\"";
        break;
        case "3":
        	$sortname = "user_mas.\"vUsername\"";
        break;
        case "5":
        	$sortname = "access_group_mas.\"vAccessGroup\"";
        break;
        case "7":
        	$sortname = "user_mas.\"dDate\"";
        break;
        case "8":
        	$sortname = "user_mas.\"iStatus\"";
        break;
        default:
        	$sortname = 'user_mas."iUserId"';
        break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = "access_group_mas.\"vAccessGroup\"";
    $join_fieds_arr[] = "company_mas.\"vCompanyName\"";
    $join_arr = array();
    $join_arr[] = "LEFT JOIN access_group_mas ON user_mas.\"iAGroupId\" = access_group_mas.\"iAGroupId\"";
    $join_arr[] = "LEFT JOIN user_details ON user_mas.\"iUserId\" = user_details.\"iUserId\"";
    $join_arr[] = "LEFT JOIN company_mas ON user_details.\"iCompanyId\" = company_mas.\"iCompanyId\"";
    $UserObj->join_field = $join_fieds_arr;
    $UserObj->join = $join_arr;
    $UserObj->where = $where_arr;
    $UserObj->param['order_by'] = $sortname . " " . $dir;
    $UserObj->param['limit'] = $limit;
    $UserObj->setClause();
    $UserObj->debug_query = false;
    $rs_user = $UserObj->recordset_list();
    // Paging Total Records
    $total = $UserObj->recordset_total();
    $data = array();
	$ni = count($rs_user);

	if($ni > 0){
		for($i=0;$i<$ni;$i++){
			$UserObj->user_clear_variable();
            $where_arr = array();
            $join_arr = array();
            $join_fieds_arr = array();
            $join_fieds_arr[] = 'department_mas."vDepartment"';
            $join_arr[] = 'INNER JOIN department_mas ON user_department."iDepartmentId" = department_mas."iDepartmentId"';
            $where_arr[] = 'user_department."iUserId" = ' . $rs_user[$i]['iUserId'];
            $UserObj->join_field = $join_fieds_arr;
            $UserObj->join = $join_arr;
            $UserObj->where = $where_arr;
            $UserObj->param['limit'] = 0;
            $UserObj->setClause();
            $rs_user_dept = $UserObj->user_department_list();
            //echo "<pre>";print_r($rs_user_dept);exit;
            $pi = count($rs_user_dept);
            $user_department = '';
            if ($pi > 0) {
                for ($p = 0; $p < $pi; $p++) {
                    $user_department .= $rs_user_dept[$p]['vDepartment'] . ' | ';					
                }
                $user_department = substr($user_department, 0, -2);
            }
            $user_department = wordwrap($user_department,40,"<br>\n");
            //echo $user_department;exit;
			$data[] = array(
                "iUserId" 			=> $rs_user[$i]['iUserId'],
                "vFirstName" 		=> $rs_user[$i]['vFirstName'],
                "vLastName" 		=> $rs_user[$i]['vLastName'],
                "vFirstName" 		=> $rs_user[$i]['vFirstName'],
                "vUsername" 		=> $rs_user[$i]['vUsername'],
                "vAccessGroup" 		=> $rs_user[$i]['vAccessGroup'],
                "vCompanyName" 		=> $rs_user[$i]['vCompanyName'],
                "iStatus" 			=> $rs_user[$i]['iStatus'],
                "dDate" 			=> $rs_user[$i]['dDate'],
                "vEmail"            => $rs_user[$i]['vEmail'],
                'vDepartment' 		=> $user_department,
            );
		}
	}
	$result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "check_duplicate_user"){
	$UserObj = new User();
	$vUsername = $RES_PARA['vUsername'];
	$duplicate_check_tot = 0;
	if($vUsername != '') {
	    $where_arr = array();
	    $where_arr[] = "user_mas.\"vUsername\" = '" . $vUsername . "'";
	    $UserObj->where = $where_arr;
	    $UserObj->param['limit'] = " LIMIT 1";
	    $UserObj->setClause();
	    $rs_user = $UserObj->recordset_list();
	    $duplicate_check_tot = count($rs_user);
    }
    $response_data = array("Code" => 200, "duplicate_check_tot" => $duplicate_check_tot);
}else if($request_type == "getUserDetailsFromUserId"){
    $UserObj = new User();
    $iUserId = $RES_PARA['iUserId'];
    $rs_user = array();
    if($iUserId != '') {
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();
        $where_arr[] = "user_mas.\"iUserId\" = '" . $iUserId . "'";
        $join_fieds_arr[] = "access_group_mas.\"vAccessGroup\"";
        $join_fieds_arr[] = "user_details.\"vCompanyName\"";
        $join_arr[] = "LEFT JOIN access_group_mas ON user_mas.\"iAGroupId\" = access_group_mas.\"iAGroupId\"";
        $join_arr[] = "LEFT JOIN user_details ON user_mas.\"iUserId\" = user_details.\"iUserId\"";
        $UserObj->join_field = $join_fieds_arr;
        $UserObj->join = $join_arr;
        $UserObj->where = $where_arr;

        $UserObj->param['limit'] = " LIMIT 1";
        $UserObj->setClause();
        $rs_user = $UserObj->recordset_list();
    }
    $response_data = array("Code" => 200, "result" => $rs_user);
}else if($request_type == "user_add"){
	$UserObj = new User();
	$result = array();
	$iUserId = $filename = "";
	$vUsername 			= $RES_PARA['vUsername'];
	$iAGroupId 			= $RES_PARA['iAGroupId'];
	$iDepartmentId 		= $RES_PARA['iDepartmentId'];
	$vFirstName 		= $RES_PARA['vFirstName'];
	$vLastName 			= $RES_PARA['vLastName'];
	$vPassword 			= $RES_PARA['vPassword'];
	$vEmail 			= $RES_PARA['vEmail'];
	$iZoneId 			= $RES_PARA['iZoneId'];
	$iStatus 			= $RES_PARA['iStatus'];
	$iType 				= $RES_PARA['iType'];
	$iCompanyId 		= $RES_PARA['iCompanyId'];
	$vAddress1 			= $RES_PARA['vAddress1'];
	$vAddress2 			= $RES_PARA['vAddress2'];
	$vStreet 			= $RES_PARA['vStreet'];
	$vCrossStreet 		= $RES_PARA['vCrossStreet'];
	$iZipcode 			= $RES_PARA['iZipcode'];
	$iStateId 			= $RES_PARA['iStateId'];
	$iCountyId 			= $RES_PARA['iCountyId'];
	$iCityId 			= $RES_PARA['iCityId'];
	$vLatitude 			= $RES_PARA['vLatitude'];
	$vLongitude 		= $RES_PARA['vLongitude'];
	$vPhone 			= $RES_PARA['vPhone'];
	$vCell 				= $RES_PARA['vCell'];
	$vNickName 			= $RES_PARA['vNickName'];
	$vFax 				= $RES_PARA['vFax'];
	$sSalt 				= $RES_PARA['sSalt'];
	$networkId_arr 		= $RES_PARA['networkId_arr'];
	$vImage 		= $RES_PARA['vImage'];

   	$insert_array = array(
        "iAGroupId" 		=> $iAGroupId,
        "iDepartmentId" 	=> $iDepartmentId,
        "iZoneId" 			=> $iZoneId,
        "vFirstName" 		=> addslashes($vFirstName),
        "vLastName" 		=> addslashes($vLastName),
        "vUsername" 		=> addslashes($vUsername),
        "vPassword" 		=> $vPassword,
        "vEmail" 			=> addslashes($vEmail),
        "vFromIP" 			=> $ip,
        "iStatus" 			=> $iStatus,
        "iType" 			=> $iType,
        "dDate" 			=> date_getSystemDateTime(),
        "iCompanyId" 		=> $iCompanyId,
        "vAddress1"  		=> addslashes($vAddress1),
        "vAddress2"  		=> addslashes($vAddress2),
        "vStreet"    		=> addslashes($vStreet),
        "vCrossStreet" 		=> addslashes($vCrossStreet),
        "iZipcode"       	=> $iZipcode,
        "iStateId"      	=> $iStateId,
        "iCountyId"       	=> $iCountyId,
        "iCityId"        	=> $iCityId,
        "iZoneId"       	=> $iZoneId,
        "vLatitude"     	=> $vLatitude,
        "vLongitude"    	=> $vLongitude,
        "vPhone" 			=> addslashes($vPhone),
        "vCell" 			=> addslashes($vCell),
        "vNickName" 		=> "",
        "vFax" 				=> addslashes($vFax),
        "vADPFileNumber" 	=> addslashes($vADPFileNumber),
        "sSalt" 			=> $sSalt,
        "vImage" 			=> $vImage,
        "networkId_arr"     => $networkId_arr
    );
   	//print_r($insert_array);exit;
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
}else if($request_type == "user_edit"){
   	$UserObj = new User();
  	$result = array();
   	$iUserId 			= $RES_PARA['iUserId'];
   	$vUsername 			= $RES_PARA['vUsername'];
	$iAGroupId 			= $RES_PARA['iAGroupId'];
	$iDepartmentId 		= $RES_PARA['iDepartmentId'];
	$vFirstName 		= $RES_PARA['vFirstName'];
	$vLastName 			= $RES_PARA['vLastName'];
	$vPassword 			= $RES_PARA['vPassword'];
	$vEmail 			= $RES_PARA['vEmail'];
	$iZoneId 			= $RES_PARA['iZoneId'];
	$iStatus 			= $RES_PARA['iStatus'];
	$iType 				= $RES_PARA['iType'];
	$iCompanyId 		= $RES_PARA['iCompanyId'];
	$vAddress1 			= $RES_PARA['vAddress1'];
	$vAddress2 			= $RES_PARA['vAddress2'];
	$vStreet 			= $RES_PARA['vStreet'];
	$vCrossStreet 		= $RES_PARA['vCrossStreet'];
	$iZipcode 			= $RES_PARA['iZipcode'];
	$iStateId 			= $RES_PARA['iStateId'];
	$iCountyId 			= $RES_PARA['iCountyId'];
	$iCityId 			= $RES_PARA['iCityId'];
	$vLatitude 			= $RES_PARA['vLatitude'];
	$vLongitude 		= $RES_PARA['vLongitude'];
	$vPhone 			= $RES_PARA['vPhone'];
	$vCell 				= $RES_PARA['vCell'];
	$vNickName 			= $RES_PARA['vNickName'];
	$vFax 				= $RES_PARA['vFax'];
	$networkId_arr 		= $RES_PARA['networkId_arr'];
	$vImage 			= $RES_PARA['vImage'];
	$update_arr = array(
        "iUserId" 			=> $iUserId,
        "iAGroupId" 		=> $iAGroupId,
        "iDepartmentId" 	=> $iDepartmentId,
        "iZoneId" 			=> $iZoneId,
        "vFirstName" 		=> addslashes($vFirstName),
        "vLastName" 		=> addslashes($vLastName),
        "vUsername" 		=> addslashes($vUsername),
        "vEmail" 			=> addslashes($vEmail),
        "vFromIP" 			=> $ip,
        "iStatus" 			=> $iStatus,
        "iType" 			=> $iType,
        "dDate" 			=> date_getSystemDateTime(),
        "iCompanyId" 		=> ($iCompanyId),
        "vAddress1"  		=> addslashes($vAddress1),
        "vAddress2"  		=> addslashes($vAddress2),
        "vStreet"    		=> addslashes($vStreet),
        "vCrossStreet" 		=> addslashes($vCrossStreet),
        "iZipcode"       	=> $iZipcode,
        "iStateId"      	=> $iStateId,
        "iCountyId"       	=> $iCountyId,
        "iCityId"        	=> $iCityId,
        "iZoneId"       	=> $iZoneId,
        "vLatitude"     	=> $vLatitude,
        "vLongitude"    	=> $vLongitude,
        "vPhone" 			=> addslashes($vPhone),
        "vCell" 			=> addslashes($vCell),
        "vNickName" 		=> "",
        "vImage" 			=> $vImage,
        "vFax" 				=> addslashes($vFax),
        "vADPFileNumber" 	=> addslashes($vADPFileNumber),
        "networkId_arr" 	=> $networkId_arr
    );

    if($vPassword != ""){
        $update_arr['vPassword'] =$vPassword;
    }

    $UserObj->update_arr = $update_arr;
    $UserObj->setClause();
    $rs_update = $UserObj->update_records();

    if($rs_update){
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iUserId" => $iUserId);
    }else{
        $r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "user_delete"){
    $iUserId = $RES_PARA['iUserId'];
    $UserObj = new User();
    $rs_db = $UserObj->delete_single_record($iUserId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iUserId" => $iUserId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "edit_profile"){
	$result = array();
	$iUserId 			= $RES_PARA['iUserId'];
	$vFirstName 		= $RES_PARA['vFirstName'];
	$vLastName 			= $RES_PARA['vLastName'];
	$vPassword 			= $RES_PARA['vPassword'];
	$vConfirmPassword 	= $RES_PARA['vConfirmPassword'];
	$update_array = array(
		"iUserId" => $iUserId,
		"vFirstName" => addslashes($vFirstName),
		"vLastName" => addslashes($vLastName),
		"vPassword" => $vPassword
	);

	if($vPassword != ""){
	 	$encryptedPassword = encrypt_password($vPassword);
	    $update_arr['vPassword'] = $encryptedPassword['encryptedPassword'];
	    $update_arr['sSalt'] = addslashes($encryptedPassword['salt']);
	}

	$UserObj = new User();
	$UserObj->update_arr = $update_array;
	$rs_db = $UserObj->update_user();

	if($rs_db){
	 $rh = HTTPStatus(200);
	 $code = 2000;
	 $message = api_getMessage($req_ext, constant($code));
	 $response_data = array("Code" => 200, "Message" => "Profile edited successfully", "iUserId" => $iUserId);
	}else{
	 $r = HTTPStatus(500);
	 $response_data = array("Code" => 500 , "Message" => 'Error - in Profile Update.');
	} 
}else if($request_type == "getUserDropdown"){ 

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
}else if($request_type == "login_history_list"){
	$Login_HistoryObj = new Login_History();
	$where_arr = array();
    if(!empty($RES_PARA)){
        $iUserId			= trim($RES_PARA['iUserId']);
        $vIP				= trim($RES_PARA['vIP']);
        $vUsername			= trim($RES_PARA['vUsername']);

        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
    }

    if ($iUserId != '') {
        $where_arr[] = "user_mas.\"iUserId\" = '".$iUserId."'";
    } 
    if ($vIP != '') {
        $where_arr[] = "user_mas.\"vIP\" LIKE '%" . $Keyword . "%'";
    } 
    if ($vUsername != '' ) {
        $where_arr[] = "user_mas.\"vUsername\" LIKE '" . $Keyword . "%'";
    } 

    switch ($display_order) {
        case "0":
            $sortname = "login_logs_mas.\"iLLogsId\"";
            break;
        case "1":
            $sortname = "user_mas.\"vUsername\"";
            break;
        case "2":
            $sortname = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\" )";
            break;
        case "3":
            $sortname = "login_logs_mas.\"vIP\"";
            break;
        case "4":
            $sortname = "login_logs_mas.\"dLoginDate\"";
            break;
        case "5":
            $sortname = "login_logs_mas.\"dLogoutDate\"";
            break;
        default:
            $sortname = "login_logs_mas.\"iLLogsId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = "user_mas.\"vUsername\", user_mas.\"vFirstName\", user_mas.\"vLastName\"";
    $join_fieds_arr[] = "access_group_mas.\"vAccessGroup\"";
    $join_arr  = array();
    $join_arr[] = "LEFT JOIN user_mas ON login_logs_mas.\"iID\" = user_mas.\"iUserId\"";
    $join_arr[] = "LEFT JOIN access_group_mas ON user_mas.\"iAGroupId\" = access_group_mas.\"iAGroupId\"";
    
    $Login_HistoryObj->join_field = $join_fieds_arr;
    $Login_HistoryObj->join = $join_arr;
    $Login_HistoryObj->where = $where_arr;
    $Login_HistoryObj->param['order_by'] = $sortname . " " . $dir;
    $Login_HistoryObj->param['limit'] = $limit;
    $Login_HistoryObj->setClause();
    $Login_HistoryObj->debug_query = false;
    $rs_login_history = $Login_HistoryObj->recordset_list();
    // Paging Total Records
    $total = $Login_HistoryObj->recordset_total();
    $ni = count($rs_login_history);
    //echo  "<pre>";print_r($rs_login_history);exit;
    if($ni > 0){
		for($i=0;$i<$ni;$i++){
			$data[] = array(
                "iLLogsId" 			=> $rs_login_history[$i]['iLLogsId'],
                "iID" 				=> $rs_login_history[$i]['iID'],
                "vIP" 				=> $rs_login_history[$i]['vIP'],
                "dLoginDate" 		=> $rs_login_history[$i]['dLoginDate'],
                "dLogoutDate" 		=> $rs_login_history[$i]['dLogoutDate'],
                "vUsername" 		=> $rs_login_history[$i]['vUsername'],
                "vFirstName" 		=> $rs_login_history[$i]['vFirstName'],
                "vLastName" 		=> $rs_login_history[$i]['vLastName'],
                "vAccessGroup" 		=> $rs_login_history[$i]['vAccessGroup'],
            );
		}
	}
	//echo  "<pre>";print_r($data);exit;
	$result = array('data' => $data , 'total_record' => $total);
	$rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>