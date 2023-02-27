<?php
//echo "<pre>";print_r($_REQUEST);exit();
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Fiber Inquiry", 'List');
$access_group_var_delete = per_hasModuleAccess("Fiber Inquiry", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Fiber Inquiry", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Fiber Inquiry", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Fiber Inquiry", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Fiber Inquiry", 'CSV', 'N');
# ----------- Access Rule Condition -----------

include_once($site_path . "scripts/session_valid.php");
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '0');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
//echo "<pre>";print_r($access_group_var_delete);exit();

if($mode == "List"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "vNetwork"){
        $searchId = $_REQUEST['iSNetworkId'];
    }else if($vOptions == "vFiberZone"){
        $searchId = $_REQUEST['iSZoneId'];
    }else if($vOptions == "vStatus"){
        $searchId = $_REQUEST['iStatus'];
    }else if($vOptions == "vInquiryType"){
        $searchId = $_REQUEST['iInquiryType'];
    }
    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }

    //echo "<pre>";print_r($_REQUEST);
    if($_REQUEST['fiberInquiryId'] != ""){
        $arr_param['fiberInquiryId'] = $_REQUEST['fiberInquiryId'];
    }
    if($_REQUEST['contactName'] != ""){
        $arr_param['contactName'] = $_REQUEST['contactName'];
        $arr_param['contactNameFilterOpDD'] = $_REQUEST['contactNameFilterOpDD'];
    }
    if($_REQUEST['vAddress'] != ""){
        $arr_param['vAddress'] = $_REQUEST['vAddress'];
        $arr_param['AddressFilterOpDD'] = $_REQUEST['AddressFilterOpDD']; 
    }
    if($_REQUEST['vCity'] != ""){
        $arr_param['vCity'] = $_REQUEST['vCity'];
        $arr_param['CityFilterOpDD'] = $_REQUEST['CityFilterOpDD'];  
    }
    if($_REQUEST['vCounty'] != ""){
        $arr_param['vCounty'] = $_REQUEST['vCounty'];
        $arr_param['CountyFilterOpDD'] = $_REQUEST['CountyFilterOpDD'];  
    }

    if($_REQUEST['vZipcode'] != ""){
        $arr_param['vZipcode'] = $_REQUEST['vZipcode'];
        $arr_param['ZipcodeFilterOpDD'] = $_REQUEST['ZipcodeFilterOpDD'];  
    }
    if($_REQUEST['zoneName'] != ""){
        $arr_param['zoneName'] = $_REQUEST['zoneName'];
        $arr_param['ZoneNameFilterOpDD'] = $_REQUEST['ZoneNameFilterOpDD'];  
    }
    if($_REQUEST['networkName'] != ""){
        $arr_param['networkName'] = $_REQUEST['networkName'];
        $arr_param['NetworkFilterOpDD'] = $_REQUEST['NetworkFilterOpDD'];  
    }
    
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;
    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."fiber_inquiry_list.json";
    // echo $API_URL." ".json_encode($arr_param);exit;
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
    //
    $response = curl_exec($ch);
    curl_close($ch);  
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);
    //echo "<pre>";print_r(json_encode($result_arr['result']));exit();

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_sr = $result_arr['result']['data'];
    $ni = count($rs_sr);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $action = '';
            if($access_group_var_edit == "1"){
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'fiber_inquiry/edit&mode=Update&iFiberInquiryId=' . $rs_sr[$i]['iFiberInquiryId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if($access_group_var_delete == "1"){
                $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_sr[$i]['iFiberInquiryId'].');"><i class="fa fa-trash"></i></a>';
            }

            $vStatus = '';
            if($rs_sr[$i]['iStatus'] == 1) { // Draft
                $vStatus = '<span class="btn btn-primary">'.$rs_sr[$i]['vStatus'].'<span>';
            }else if($rs_sr[$i]['iStatus'] == 2) { // Assigned
                $vStatus = '<span class="btn btn-secondary">'.$rs_sr[$i]['vStatus'].'<span>';
            }else if($rs_sr[$i]['iStatus'] == 3) { // Review
                $vStatus = '<span class="btn btn-info">'.$rs_sr[$i]['vStatus'].'<span>';
            }else if($rs_sr[$i]['iStatus'] == 4) { // Complete
                $vStatus = '<span class="btn btn-success">'.$rs_sr[$i]['vStatus'].'<span>';
            }

            $entry[] = array(
                "checkbox"           => '<input type="checkbox" class="list" value="'.$rs_sr[$i]['iFiberInquiryId'].'"/>',
                "iFiberInquiryId"    => $rs_sr[$i]['iFiberInquiryId'],
                "vContactName"       => $rs_sr[$i]['vContactName'],
                "vAddress"           => $rs_sr[$i]['vAddress'],
                "vCity"              => $rs_sr[$i]['vCity'],
                "vCounty"            => $rs_sr[$i]['vCounty'],
                "vZipcode"           => $rs_sr[$i]['vZipcode'],
                "vZoneName"          => $rs_sr[$i]['vZoneName'],
                "vNetwork"           => $rs_sr[$i]['vNetwork'],
                "vStatus"            => $vStatus,
                "iInquiryType"       => $rs_sr[$i]['vInquiryType'],
                "dAddedDate"         => date_display_report_date($rs_sr[$i]['dAddedDate']),
                "actions"            => ($action!="")?$action:"---"
            );
        }
    }
    
    # Return jSON data.
    # -----------------------------------
    $jsonData['aaData'] = $entry;
    //echo "<pre>";print_r($jsonData);exit();
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}else if ($mode == "get_zone_from_latlong") {
    //echo"<pre>";print_r($_REQUEST);exit;
    $lat = number_format($_REQUEST['lat'],6);
    $long = number_format($_REQUEST['long'],6);
    $jsonData = array();

    $arr_param = array(
        "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "lat"       => $lat,
        "long"      => $long,
    );
    //echo"<pre>";print_r(json_encode($arr_param));exit;
    $API_URL = $site_api_url."autoGoogleZoneFromLatlong.json";
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
    $res= json_decode($response, true);
    //echo"<pre>";print_r($res);exit;
    $jsonData =$res['result'];
    
    echo json_encode($jsonData);
    hc_exit();
}else if ($mode == "check_city_state") {
    $state_code = $_REQUEST['state_code'];
    $city = $_REQUEST['city'];

    $jsonData = array();

    $arr_param = array(
        'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "state_code"    => $state_code,
        "city"          => $city,
    );
    //echo"<pre>";print_r(json_encode($arr_param));exit;
    //echo"<pre>";print_r(json_encode($arr_param));exit;
    $API_URL = $site_api_url."autoGoogleCheckCityState.json";
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
    $res= json_decode($response, true);
    //echo"<pre>";print_r($res);exit;
    $jsonData =$res['result'];
    
    echo json_encode($jsonData);
    hc_exit();
}else if ($mode == "get_state") {
    $jsonData = array();
    $vStateCode = trim($_REQUEST['vStateCode']);

    $arr_param = array(
        'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "vStateCode"    => $vStateCode,
    );
    //echo"<pre>";print_r(json_encode($arr_param));exit;
    $API_URL = $site_api_url."autoGooglegetState.json";
    
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
    $res= json_decode($response, true);
    //echo"<pre>";print_r($res);exit;
    $jsonData =$res['result'];
    
    echo json_encode($jsonData);
    hc_exit();
}else if ($mode == "get_zipcode") {

    $jsonData = array();
    $vZipcode = trim($_REQUEST['vZipcode']);

    $arr_param = array(
        'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "vZipcode"    => $vZipcode,
    );
    
    $API_URL = $site_api_url."autoGooglegetZipcode.json";
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
    $res= json_decode($response, true);
    //echo"<pre>";print_r($res);exit;
    $jsonData =$res['result'];

    echo json_encode($jsonData);
    hc_exit();
}else if ($mode == "get_city") {
    $vCity = trim($_REQUEST['city']);
    $vCounty = trim($_REQUEST['county']);

    $arr_param = array(
        'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "vCity"    => $vCity,
        "vCounty"    => $vCounty,
    );
    //echo"<pre>";print_r(json_encode($arr_param));exit;
    $API_URL = $site_api_url."autoGooglegetCity.json";
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
    $res= json_decode($response, true);
    //echo"<pre>";print_r($res);exit;
    $jsonData =$res['result'];
    echo json_encode($jsonData);
    hc_exit();
}else if($mode == "searchContact"){
    $iCId = trim($_REQUEST['iCId']);
    $vContactName = trim($_REQUEST['vContactName']);
    $arr_param = array(
        'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "iCId"              => $iCId,
        "vContactName"      => $vContactName,
    );
    //echo"<pre>";print_r(json_encode($arr_param));exit;
    $API_URL = $site_api_url."autoSearchContact.json";
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
    $res= json_decode($response, true);
    //echo"<pre>";print_r($res);exit;
    $jsonData =$res['result'];
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
}else if($mode == "Add"){
    $arr_param = array();
    $iLoginUserId = $_SESSION["sess_iUserId".$admin_panel_session_suffix];
    if (isset($_POST) && count($_POST) > 0) {
        $arr_param = array(
            "sessionId"             => $_SESSION["we_api_session_id".$admin_panel_session_suffix],
            "vAddress1"             => $_POST['vAddress1'],
            "vAddress2"             => $_POST['vAddress2'],
            "vStreet"               => $_POST['vStreet'],
            "vCrossStreet"          => $_POST['vCrossStreet'],
            "iZipcode"              => $_POST['iZipcode'],
            "iStateId"              => $_POST['iStateId'],
            "iCountyId"             => $_POST['iCountyId'],
            "iCityId"               => $_POST['iCityId'],
            "iZoneId"               => $_POST['iZoneId'],
            "vLatitude"             => $_POST['vLatitude'],
            "vLongitude"            => $_POST['vLongitude'],
            "iCId"                  => $_POST['iCId'],
            "iStatus"               => $_POST['iStatus'],
            "iOldStatus"            => $_POST['iOldStatus'],
            "iPremiseSubTypeId"     => $_POST['iPremiseSubTypeId'],
            "iEngagementId"         => $_POST['iEngagementId'],
            "iInquiryType"          => $_POST['iInquiryType'],
            "tNotes"                => $_POST['tNotes'],
            "iLoginUserId"          => $iLoginUserId,
        );
        $API_URL = $site_api_url."fiber_inquiry_add.json";
        //echo $API_URL." ".json_encode($arr_param);exit();
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
        $rs = curl_exec($ch);
        curl_close($ch);  
        $res = json_decode($rs, true);
        //echo "<pre>";print_r($res);exit();
        if($res){
            $result['iMatchingPremiseId'] = $res['iMatchingPremiseId'];
            $result['iFiberInquiryId'] = $res['iFiberInquiryId'];
            $result['msg'] = MSG_ADD;
            $result['error']= 0 ;
        }else{
            $result['msg'] = MSG_ADD_ERROR;
            $result['error']= 1 ;
        }
    }else {
        $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;
    }
     //echo "<pre>";print_r($result);exit(); 

    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # ----------------------------------- 
}else if($mode == "Update"){
    $arr_param = array();
    $iLoginUserId = $_SESSION["sess_iUserId".$admin_panel_session_suffix];
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit;
        $arr_param = array(
            "sessionId"             => $_SESSION["we_api_session_id".$admin_panel_session_suffix],
            "iFiberInquiryId"       => $_POST['iFiberInquiryId'],
            "vAddress1"             => $_POST['vAddress1'],
            "vAddress2"             => $_POST['vAddress2'],
            "vStreet"               => $_POST['vStreet'],
            "vCrossStreet"          => $_POST['vCrossStreet'],
            "iZipcode"              => $_POST['iZipcode'],
            "iStateId"              => $_POST['iStateId'],
            "iCountyId"             => $_POST['iCountyId'],
            "iCityId"               => $_POST['iCityId'],
            "iZoneId"               => $_POST['iZoneId'],
            "vLatitude"             => $_POST['vLatitude'],
            "vLongitude"            => $_POST['vLongitude'],
            "iCId"                  => $_POST['iCId'],
            "iStatus"               => $_POST['iStatus'],
            "iOldStatus"            => $_POST['iOldStatus'],
            "iPremiseSubTypeId"     => $_POST['iPremiseSubTypeId'],
            "iEngagementId"         => $_POST['iEngagementId'],
            "iInquiryType"          => $_POST['iInquiryType'],
            "tNotes"                => $_POST['tNotes'],
            "iLoginUserId"          => $iLoginUserId,
        );

        $API_URL = $site_api_url."fiber_inquiry_edit.json";
        //echo $API_URL." ".json_encode($arr_param);exit;
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

        $rs = curl_exec($ch);
        curl_close($ch);  
        $res = json_decode($rs, true);
        //echo "<pre>";print_r($res);exit();
        if($res){
            $result['iMatchingPremiseId'] = $res['iMatchingPremiseId'];
            $result['iFiberInquiryId'] = $res['iFiberInquiryId'];
            $result['msg'] = MSG_UPDATE;
            $result['error']= 0 ;
        }else{
            $result['msg'] = MSG_UPDATE_ERROR;
            $result['error']= 1 ;
        }
    }else {
        $result['msg'] = MSG_UPDATE_ERROR;
        $result['error']= 1 ;
    }
    //echo "<pre>";print_r($result);exit(); 

    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # ----------------------------------- 
}else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iFiberInquiryId = $_REQUEST['iFiberInquiryId'];
    $arr_param['iFiberInquiryId'] = $iFiberInquiryId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."fiber_inquiry_delete.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
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

    $rs_tot = curl_exec($ch);
    //echo "<pre>";print_r($rs);exit();  
    curl_close($ch); 
    if($rs_tot){
        $result['msg'] = MSG_DELETE;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_DELETE_ERROR;
        $result['error']= 1 ;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}else if($mode== "Excel"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "vNetwork"){
        $searchId = $_REQUEST['iSNetworkId'];
    }else if($vOptions == "vFiberZone"){
        $searchId = $_REQUEST['iSZoneId'];
    }else if($vOptions == "vStatus"){
        $searchId = $_REQUEST['iStatus'];
    }else if($vOptions == "vInquiryType"){
        $searchId = $_REQUEST['iInquiryType'];
    }
    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }

    //echo "<pre>";print_r($_REQUEST);
    if($_REQUEST['fiberInquiryId'] != ""){
        $arr_param['fiberInquiryId'] = $_REQUEST['fiberInquiryId'];
    }
    if($_REQUEST['contactName'] != ""){
        $arr_param['contactName'] = $_REQUEST['contactName'];
        $arr_param['contactNameFilterOpDD'] = $_REQUEST['contactNameFilterOpDD'];
    }
    if($_REQUEST['vAddress'] != ""){
        $arr_param['vAddress'] = $_REQUEST['vAddress'];
        $arr_param['AddressFilterOpDD'] = $_REQUEST['AddressFilterOpDD']; 
    }
    if($_REQUEST['vCity'] != ""){
        $arr_param['vCity'] = $_REQUEST['vCity'];
        $arr_param['CityFilterOpDD'] = $_REQUEST['CityFilterOpDD'];  
    }
    if($_REQUEST['vCounty'] != ""){
        $arr_param['vCounty'] = $_REQUEST['vCounty'];
        $arr_param['CountyFilterOpDD'] = $_REQUEST['CountyFilterOpDD'];  
    }

    if($_REQUEST['vZipcode'] != ""){
        $arr_param['vZipcode'] = $_REQUEST['vZipcode'];
        $arr_param['ZipcodeFilterOpDD'] = $_REQUEST['ZipcodeFilterOpDD'];  
    }
    if($_REQUEST['zoneName'] != ""){
        $arr_param['zoneName'] = $_REQUEST['zoneName'];
        $arr_param['ZoneNameFilterOpDD'] = $_REQUEST['ZoneNameFilterOpDD'];  
    }
    if($_REQUEST['networkName'] != ""){
        $arr_param['networkName'] = $_REQUEST['networkName'];
        $arr_param['NetworkFilterOpDD'] = $_REQUEST['NetworkFilterOpDD'];  
    }
    
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;
    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."fiber_inquiry_list.json";
    // echo $API_URL." ".json_encode($arr_param);exit;
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
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);
    $rs_export = $result_arr['result']['data'];
    $cnt_export = count($rs_export);
    //  echo "<pre>";print_r($rs_export);exit();
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    // // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $file_name = "fiberinquiry".time().".xlsx";

    if($cnt_export >0) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Id')
            ->setCellValue('B1', 'Name')
            ->setCellValue('C1', 'Address')
            ->setCellValue('D1', 'City')
            ->setCellValue('E1', 'County')
            ->setCellValue('F1', 'Zipcode')
            ->setCellValue('G1', 'Zone')
            ->setCellValue('H1', 'Network')
            ->setCellValue('I1', 'Status')
            ->setCellValue('J1', 'Inquiry Type')
            ->setCellValue('K1', 'Created Date');

        for($e=0; $e<$cnt_export; $e++) {

            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iFiberInquiryId'])
            ->setCellValue('B'.($e+2), $rs_export[$e]['vContactName'])
            ->setCellValue('C'.($e+2), $rs_export[$e]['vAddress'])
            ->setCellValue('D'.($e+2), $rs_export[$e]['vCity'])
            ->setCellValue('E'.($e+2), $rs_export[$e]['vCounty'])
            ->setCellValue('F'.($e+2), $rs_export[$e]['vZipcode'])
            ->setCellValue('G'.($e+2), $rs_export[$e]['vZoneName'])
            ->setCellValue('H'.($e+2), $rs_export[$e]['vNetwork'])
            ->setCellValue('I'.($e+2), $rs_export[$e]['vStatus'])
            ->setCellValue('J'.($e+2), $rs_export[$e]['vInquiryType'])
            ->setCellValue('K'.($e+2), date_display_report_date($rs_export[$e]['dAddedDate']));
         }
                        
        /* Set Auto width of each comlumn */
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        
        /* Set Font to Bold for each comlumn */
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
        

        /* Set Alignment of Selected Columns */
        $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('FiberInquiry');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $result_arr  = array();
   
     //save in file 
    $objWriter->save($temp_gallery.$file_name);
    $result_arr['isError'] = 0;
    $result_arr['file_path'] = base64_encode($temp_gallery.$file_name);
    $result_arr['file_url'] = base64_encode($temp_gallery_url.$file_name);
    # -------------------------------------

   echo json_encode($result_arr);
   exit;
}else if($mode == "change_status"){
    $result = array();
    $arr_param = array();
    $status = $_POST['status'];
    $iFiberInquiryIds = $_POST['iFiberInquiryIds'];
    
    $arr_param['status']            = $status; 
    $arr_param['iFiberInquiryIds']  = $iFiberInquiryIds; 
    $arr_param['sessionId']         = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."fiber_inquiry_change_status.json";
    //echo $API_URL." ".json_encode($arr_param);exit();
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

    $rs = curl_exec($ch);
    $res = json_decode($rs, true);
    curl_close($ch);   
    if($res['error'] == 0){
        $result['msg'] = $res['Message'];
        $result['error']= $res['error'] ;
    }else{
        $result['msg'] = $res['Message'];
        $result['error']= $res['error'];
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}

# Network Dropdown
$network_arr_param = array();
$network_arr_param = array(
    "iStatus"        => 1,
    "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$network_API_URL = $site_api_url."network_dropdown.json";
//echo $network_API_URL." ".json_encode($network_arr_param);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $network_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($network_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_network = curl_exec($ch);
curl_close($ch); 
$rs_network = json_decode($response_network, true); 
$rs_ntwork = $rs_network['result'];
$smarty->assign("rs_ntwork", $rs_ntwork);
## --------------------------------
## --------------------------------
# Zone Dropdown
$zone_arr_param = array();
$zone_arr_param = array(
    "iStatus"        => 1,
    "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$zone_API_URL = $site_api_url."zone_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $zone_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($zone_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_zone = curl_exec($ch);
curl_close($ch); 
$rs_zone1 = json_decode($response_zone, true); 
$rs_zone = $rs_zone1['result'];
$smarty->assign("rs_zone", $rs_zone);
## --------------------------------

$module_name = "Fiber Inquiry List";
$module_title = "Fiber Inquiry";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);
?>