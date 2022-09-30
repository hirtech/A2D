<?php
//echo "<pre>";print_r($_REQUEST);exit();
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Service Request", 'List');
$access_group_var_delete = per_hasModuleAccess("Service Request", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Service Request", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Service Request", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Service Request", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("Service Request", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Service Request", 'CSV', 'N');
$access_group_var_Respond = per_hasModuleAccess("Service Request", 'Respond', 'N');
# ----------- Access Rule Condition -----------

include_once($site_path . "scripts/session_valid.php");
include_once($controller_path . "sr.inc.php");


$SRObj = new SR();

# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '1');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
//echo "<pre>";print_r($access_group_var_delete);exit();


if($mode == "List"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }
    //echo "<pre>";print_r($_REQUEST);
    if($_REQUEST['srId'] != ""){
        $arr_param['srId'] = $_REQUEST['srId'];
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
    if($_REQUEST['vState'] != ""){
        $arr_param['vState'] = $_REQUEST['vState'];
        $arr_param['StateFilterOpDD'] = $_REQUEST['StateFilterOpDD'];  
    } 
    if($_REQUEST['vCounty'] != ""){
        $arr_param['vCounty'] = $_REQUEST['vCounty'];
        $arr_param['CountyFilterOpDD'] = $_REQUEST['CountyFilterOpDD'];  
    }
    if($_REQUEST['assignTo'] != ""){
        $arr_param['assignTo'] = $_REQUEST['assignTo'];
        $arr_param['AssignToFilterOpDD'] = $_REQUEST['AssignToFilterOpDD'];  
    }
    if($_REQUEST['srreqType'] != ""){
        $arr_param['srreqType'] = $_REQUEST['srreqType'];  
    }
    if($_REQUEST['status'] != ""){
        $arr_param['status'] = $_REQUEST['status'];  
    }
    
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;
    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    //echo "<pre>";print_r($arr_param);exit();
    $API_URL = $site_api_url."sr_list.json";
    //echo $API_URL;exit;
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
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'sr/edit&mode=Update&iSRId=' . $rs_sr[$i]['iSRId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if($access_group_var_delete == "1"){
                $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_sr[$i]['iSRId'].');"><i class="fa fa-trash"></i></a>';
            }

            $entry[] = array(
                "checkbox" => '<input type="checkbox" class="list" value="'.$rs_sr[$i]['iSRId'].'"/>',
                "iSRId" => $rs_sr[$i]['iSRId'],
                "vContactName" => $rs_sr[$i]['vContactName'],
                "vAddress" => $rs_sr[$i]['vAddress'],
                "vCity" => $rs_sr[$i]['vCity'],
                "vState" => $rs_sr[$i]['vState'],
                "vCounty" => $rs_sr[$i]['vCounty'],
                "vAssignTo" => $rs_sr[$i]['vAssignTo'],
                "vRequestType" => $rs_sr[$i]['vRequestType'],
                "vStatus" => $rs_sr[$i]['vStatus'],
                "actions" => ($action!="")?$action:"---"
            );
        }
    }
    
    # Return jSON data.
    # -----------------------------------
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
} else if ($mode == "get_zone_from_latlong") {
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
} else if ($mode == "check_city_state") {
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
} else if ($mode == "get_state") {
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
} else if ($mode == "get_zipcode") {

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
} else if ($mode == "get_city") {
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
} else if($mode == "searchContact"){
    //echo "<pre>";print_r($_REQUEST);exit();
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
} else if($mode == "Add"){
    $arr_param = array();
    $iLoginUserId = $_SESSION["sess_iUserId".$admin_panel_session_suffix];
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit;

        $arr_param = array(
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
            "bMosquitoService"      => $_POST['bMosquitoService'],
            "bCarcassService"       => $_POST['bCarcassService'],
            "iUserId"               => $_POST['iUserId'],
            "bInspectPermission"    => $_POST['bInspectPermission'],
            "bAccessPermission"     => $_POST['bAccessPermission'],
            "bPets"                 => $_POST['bPets'],
            "iStatus"               => $_POST['iStatus'],
            "tProblems"             => $_POST['tProblems'],
            "tInternalNotes"        => $_POST['tInternalNotes'],
            "tRequestorNotes"       => $_POST['tRequestorNotes'],
            "iLoginUserId"          => $iLoginUserId,
            "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        );
        //echo "<pre>";print_r(json_encode($arr_param));exit();

        $API_URL = $site_api_url."sr_add.json";
        //echo "<pre>";print_r($API_URL);exit();
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

        $iSRId = curl_exec($ch);
        //echo "<pre>";print_r($iSRId);exit();  
        curl_close($ch);  

        if($iSRId){
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
} else if($mode == "Update"){
    $arr_param = array();
    $iLoginUserId = $_SESSION["sess_iUserId".$admin_panel_session_suffix];
    if (isset($_POST) && count($_POST) > 0) {
        $arr_param = array(
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iSRId"             => $_POST['iSRId'],
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
            "bMosquitoService"      => $_POST['bMosquitoService'],
            "bCarcassService"       => $_POST['bCarcassService'],
            "iUserId"     => $_POST['iUserId'],
            "bInspectPermission"    => $_POST['bInspectPermission'],
            "bAccessPermission"     => $_POST['bAccessPermission'],
            "bPets"                 => $_POST['bPets'],
            "iStatus"               => $_POST['iStatus'],
            "iOldStatus"            => $_POST['iOldStatus'],
            "tProblems"             => $_POST['tProblems'],
            "tInternalNotes"        => $_POST['tInternalNotes'],
            "tRequestorNotes"       => $_POST['tRequestorNotes'],
            "iLoginUserId"          => $iLoginUserId,
        );
        //echo "<pre>";print_r(json_encode($arr_param));//exit();

        $API_URL = $site_api_url."sr_edit.json";
        //echo "<pre>";print_r($API_URL);exit();
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
        //echo "<pre>";print_r($rs);exit();  
        curl_close($ch);  

        if($rs){
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
} else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iSRId = $_REQUEST['iSRId'];
    
    $arr_param['iSRId'] = $iSRId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."sr_delete.json";
        //echo "<pre>";print_r($API_URL);exit();
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
}



$module_name = "Service Request List";
$module_title = "Service Request";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);

$smarty->assign("access_group_var_add", $access_group_var_add);

?>