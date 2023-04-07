<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise", 'List');
$access_group_var_delete = per_hasModuleAccess("Premise", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Premise", 'CSV', 'N');
# ----------- Access Rule Condition -----------

# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$page = $_REQUEST['page'];
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '1');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------

$sess_iCompanyId = $_SESSION["sess_iCompanyId" . $admin_panel_session_suffix];

if($mode == "List"){
    // echo "<pre>"; print_r($_REQUEST);exit();
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "vTypeName"){
        $searchId = $_REQUEST['typeId'];
    }else if($vOptions == "vSubTypeName"){
        $searchId = $_REQUEST['sTypeId'];
    }else if($vOptions == "vNetwork"){
        $searchId = $_REQUEST['networkId'];
    }else if($vOptions == "vFiberZone"){
        $searchId = $_REQUEST['zoneId'];
    }
    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }
    if ($_REQUEST['premiseId'] != "") {
        $arr_param['premiseId'] = $_REQUEST['premiseId'];
    }
    if ($_REQUEST['iSTypeId'] != "") {
        $arr_param['iSTypeId'] = $_REQUEST['iSTypeId'];
    }
    if ($_REQUEST['iSSTypeId'] != "") {
        $arr_param['iSSTypeId'] = $_REQUEST['iSSTypeId'];
    }

    if ($_REQUEST['iGeometryType'] != "") {
        $arr_param['iGeometryType'] = $_REQUEST['iGeometryType'];
    }

    if ($_REQUEST['status'] != "") {
        $arr_param['status'] = $_REQUEST['status'];
    }

    if($_REQUEST['siteName'] != ""){
        $arr_param['siteName'] = $_REQUEST['siteName'];
        $arr_param['SiteFilterOpDD'] = $_REQUEST['SiteFilterOpDD'];
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

    if($_REQUEST['iZoneId'] != ""){
        $arr_param['iZoneId'] = $_REQUEST['iZoneId'];
    }

    if($_REQUEST['iNetworkId'] != ""){
        $arr_param['iNetworkId'] = $_REQUEST['iNetworkId'];
    }
    
    // if ($_REQUEST['status'] != "") {
    //     $arr_param['status'] = $_REQUEST['status'];
    // }

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;
    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    //print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."premise_list.json";
    // echo $API_URL. " ".json_encode($arr_param);exit;
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
    // echo "<pre>"; print_r($response);exit();
    curl_close($ch);  
    $result_arr = json_decode($response, true);
    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_site = $result_arr['result']['data'];
    // echo "<pre>"; print_r($rs_site);exit();
    $ni = count($rs_site);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = $status = '';
            if($access_group_var_edit == '1'){
               $action .= '<a class="btn btn-outline-secondary" title="Edit"  href="'.$site_url.'premise/edit&mode=Update&iPremiseId=' . $rs_site[$i]['iPremiseId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_site[$i]['iPremiseId'].');"><i class="fa fa-trash"></i></a>';
            }
            
            $action .= ' <a class="btn btn-outline-warning" title="Premise History" target="_blank" href="'.$site_url.'premise/history&iPremiseId=' . $rs_site[$i]['iPremiseId'] . '&vName=' . $rs_site[$i]['vName'] . '"><i class="fas fa-history"></i></a>'; 

            if($rs_site[$i]['iStatus'] == 0){
                $status = '<span title="Off-Net" class="btn btn-danger">Off-Net</span>';
            }else if($rs_site[$i]['iStatus'] == 1){
                $status = '<span title="On-Net" class="btn btn-success">On-Net</span>';
            }else if($rs_site[$i]['iStatus'] == 2){
                $status = '<span title="Near-Net" class="btn btn-warning">Near-Net</span>';
            }

			
			$awareness_var_list = per_hasModuleAccess("Task Awareness", 'List', 'N');
			$premise_var_list = per_hasModuleAccess("Premise", 'List', 'N');
			$so_var_list = per_hasModuleAccess("Service Order", 'List', 'N');
			$so_var_add = per_hasModuleAccess("Service Order", 'Add', 'N');
			$wo_var_list = per_hasModuleAccess("Work Order", 'List', 'N');
			$wo_var_add = per_hasModuleAccess("Work Order", 'Add', 'N');
			$equipemnt_var_list = per_hasModuleAccess("Equipment", 'List', 'N');
			$pcircuit_var_list = per_hasModuleAccess("Premise Circuit", 'Add', 'N');
            
            if($awareness_var_list == 1 || $premise_var_list == 1 || $so_var_list == 1 || $wo_var_list == 1 || $equipemnt_var_list == 1 || $so_var_add == 1 || $wo_var_add == 1 || $pcircuit_var_list == 1){
                $action .= ' <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tasks</button>
                    <div class="dropdown-menu p-0">';
                    
                    if($so_var_list == 1){
                        $vSOURL = $site_url."service_order/list&iPremiseId=".$rs_site[$i]['iPremiseId'];
                        $action .= '<a class="dropdown-item" title="View Service Orders" target="_blank" href="'.$vSOURL.'">View Service Orders</a>';
                    }
                    if($wo_var_list == 1){
                        $vWOURL = $site_url."service_order/workorder_list&iPremiseId=".$rs_site[$i]['iPremiseId'];
                        $action .= '<a class="dropdown-item" title="View Work Orders" target="_blank" href="'.$vWOURL.'">View Work Orders</a>';
                    }
                    if($equipemnt_var_list == 1){
                        $vEqupment_url = $site_url."service_order/equipment_list&iPremiseId=".$rs_site[$i]['iPremiseId'];
                        $action .= '<a class="dropdown-item" title="View Equipment" target="_blank" href="'.$vEqupment_url.'">View Equipment</a>';
                    }
                    $action .= '<div class="dropdown-divider"></div>';
                    if($so_var_add == 1){
                        $vSOAURL = $site_url."service_order/add&iPremiseId=".$rs_site[$i]['iPremiseId'];
                        $action .= '<a class="dropdown-item" title="Setup Service Order" target="_blank" href="'.$vSOAURL.'">Setup Service Order</a>';
                    }
                    if($wo_var_add == 1){
                        $vWOAURL = $site_url."service_order/workorder_add&iPremiseId=".$rs_site[$i]['iPremiseId'];
                        $action .= '<a class="dropdown-item" title="Setup Work Order" target="_blank" href="'.$vWOAURL.'">Setup Work Order</a>';
                    }
                    if($pcircuit_var_list == 1){
                        $vPCAURL = $site_url."premise_circuit/premise_circuit_add&iPremiseId=".$rs_site[$i]['iPremiseId'];
                        $action .= '<a class="dropdown-item" title="Setup Premise Circuit" target="_blank" href="'.$vPCAURL.'">Setup Premise Circuit</a>';
                    }

                    if($sess_iCompanyId == $A2D_COMPANY_ID){
                        $action .= '<a class="dropdown-item" title="Setup Premise Services"   onclick="setupPremiseService('.$rs_site[$i]['iPremiseId'].', '.$rs_site[$i]['premice_circuit_count'].')">Setup Premise Services</a>';
                    }

                    $action .= '<div class="dropdown-divider"></div>';
                    if($awareness_var_list == 1){
                        $action .= '<a class="dropdown-item" title="Awareness"  onclick="addEditDataAwareness(0,\'add\','.$rs_site[$i]['iPremiseId'].')">Awareness</a>';
                    }
                    $action .= '</div>';
            }
            $vAddress = $rs_site[$i]['vAddress1'].' '.$rs_site[$i]['vStreet'];
            $entry[] = array(
                "checkbox"      => '<input type="checkbox" class="list" value="'.$rs_site[$i]['iPremiseId'].'"/>',
                "iPremiseId"    => $rs_site[$i]['iPremiseId'],
                "vName"         => $rs_site[$i]['vName'],
                "vSiteType"     => $rs_site[$i]['vTypeName'],
                "vSiteSubType"  => $rs_site[$i]['vSubTypeName'],
                "vAddress"      => $rs_site[$i]['vAddress'],
                'vCity'         => $rs_site[$i]['vCity'],
                'vState'        => $rs_site[$i]['vState'],
                'vZoneName'     => $rs_site[$i]['vZoneName'],
                'vNetwork'      => $rs_site[$i]['vNetwork'],
                'vCounty'       => $rs_site[$i]['vCounty'],
                "vCircuitName"  => $rs_site[$i]['vCircuitName'],
                "iStatus"       => $status,
                "actions"       => ($action!="")?$action:"---"
       
            );
        }
    }
   // echo "<pre>";print_r($entry);
    $jsonData['aaData'] = $entry;
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}else if($mode == "Add"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        $arr_param = array(
            "vName" => addslashes($_POST['vName']),
            "iSTypeId" => addslashes($_POST['iSTypeId']),
            "iSSTypeId" => addslashes($_POST['iSSTypeId']),
            "vAddress1" => addslashes($_POST['vAddress1']),
            "vAddress2" => addslashes($_POST['vAddress2']),
            "vStreet" => addslashes($_POST['vStreet']),
            "vCrossStreet" => addslashes($_POST['vCrossStreet']),
            "iZipcode" => addslashes($_POST['iZipcode']),
            "iStateId" => addslashes($_POST['iStateId']),
            "iCountyId" => addslashes($_POST['iCountyId']),
            "iCityId" => addslashes($_POST['iCityId']),
            "iZoneId" => addslashes($_POST['iZoneId']),
            "iGeometryType" => addslashes($_POST['iGeometryType']),
            "vLatitude" => addslashes($_POST['vLatitude']),
            "vLongitude" => addslashes($_POST['vLongitude']),
            "vNewLatitude" => addslashes($_POST['vNewLatitude']),
            "vNewLongitude" => addslashes($_POST['vNewLongitude']),
            "iSTypeId" => addslashes($_POST['iSTypeId']),
            "iSSTypeId" => addslashes($_POST['iSSTypeId']),
            "iStatus" => $_POST['iStatus'],
            "vPolygonLatLong"=>$_POST['vPolygonLatLong'],
            "vPolyLineLatLong"=>$_POST['vPolyLineLatLong'],
            "iSAttributeId"=>$_POST['iSAttributeId'],
            "vLoginUserName"=>$_SESSION["sess_vName".$admin_panel_session_suffix],
            "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        );
        $API_URL = $site_api_url."premise_add.json";
        //echo "<pre>";print_r($API_URL);exit();
        //echo json_encode($arr_param);exit;
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

        $response_site = curl_exec($ch);
        curl_close($ch); 
        $result_site_arr = json_decode($response_site, true); 

        if(!empty($result_site_arr)){
            $result['iPremiseId'] = $result_site_arr['iPremiseId'];
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
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}else if($mode == "Update"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit();
        $arr_param = array(
            "iPremiseId" => addslashes($_POST['iPremiseId']),
            "vName" => addslashes($_POST['vName']),
            "iSTypeId" => addslashes($_POST['iSTypeId']),
            "iSSTypeId" => addslashes($_POST['iSSTypeId']),
            "vAddress1" => addslashes($_POST['vAddress1']),
            "vAddress2" => addslashes($_POST['vAddress2']),
            "vStreet" => addslashes($_POST['vStreet']),
            "vCrossStreet" => addslashes($_POST['vCrossStreet']),
            "iZipcode" => addslashes($_POST['iZipcode']),
            "iStateId" => addslashes($_POST['iStateId']),
            "iCountyId" => addslashes($_POST['iCountyId']),
            "iCityId" => addslashes($_POST['iCityId']),
            "iZoneId" => addslashes($_POST['iZoneId']),
            "iGeometryType" => addslashes($_POST['iGeometryType']),
            "vLatitude" => addslashes($_POST['vLatitude']),
            "vLongitude" => addslashes($_POST['vLongitude']),
            "vNewLatitude" => addslashes($_POST['vNewLatitude']),
            "vNewLongitude" => addslashes($_POST['vNewLongitude']),
            "iStatus" => $_POST['iStatus'],
            "iCId" => $_POST['iCId'],
            "vPolygonLatLong"=>$_POST['vPolygonLatLong'],
            "vPolyLineLatLong"=>$_POST['vPolyLineLatLong'],
            "iSAttributeId"=>$_POST['iSAttributeId'],
            "vLoginUserName"=>$_SESSION["sess_vName".$admin_panel_session_suffix],
            "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        );
        //echo "<pre>";print_r($site_arr);exit();
        $API_URL = $site_api_url."premise_edit.json";
        //echo "<pre>";print_r($API_URL);exit();
        //echo json_encode($arr_param);exit;
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

        $response_site = curl_exec($ch);
        curl_close($ch); 
        $result_site_arr = json_decode($response_site, true); 

        if(!empty($result_site_arr)){
            $result['iPremiseId'] = $_POST['iPremiseId'];
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
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}else if($mode == "Delete"){

    $result = array();
    $arr_param = array();
    $iPremiseId = $_POST['iPremiseId'];
    
    $arr_param['iPremiseId'] = $iPremiseId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."premise_delete.json";
    //echo "<pre>";print_r($API_URL);exit();
    //echo json_encode($arr_param);exit;
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
}else if ($mode == "get_zone_from_latlong") {
    //echo"<pre>";print_r($_REQUEST);exit;

    $lat = number_format($_REQUEST['lat'],6);
    $long = number_format($_REQUEST['long'],6);
    $jsonData = array();

    $arr_param = array(
        'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
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
    //echo"<pre>";print_r(json_encode($arr_param));exit;
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
}else if($mode== "Excel"){
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "vTypeName"){
        $searchId = $_REQUEST['typeId'];
    }else if($vOptions == "vSubTypeName"){
        $searchId = $_REQUEST['sTypeId'];
    }else if($vOptions == "vNetwork"){
        $searchId = $_REQUEST['networkId'];
    }else if($vOptions == "vFiberZone"){
        $searchId = $_REQUEST['zoneId'];
    }
    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }
    if ($_REQUEST['premiseId'] != "") {
        $arr_param['premiseId'] = $_REQUEST['premiseId'];
    }
    if ($_REQUEST['iSTypeId'] != "") {
        $arr_param['iSTypeId'] = $_REQUEST['iSTypeId'];
    }
    if ($_REQUEST['iSSTypeId'] != "") {
        $arr_param['iSSTypeId'] = $_REQUEST['iSSTypeId'];
    }

    if ($_REQUEST['iGeometryType'] != "") {
        $arr_param['iGeometryType'] = $_REQUEST['iGeometryType'];
    }

    if ($_REQUEST['status'] != "") {
        $arr_param['status'] = $_REQUEST['status'];
    }

    if($_REQUEST['siteName'] != ""){
        $arr_param['siteName'] = $_REQUEST['siteName'];
        $arr_param['SiteFilterOpDD'] = $_REQUEST['SiteFilterOpDD'];
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

    if($_REQUEST['iZoneId'] != ""){
        $arr_param['iZoneId'] = $_REQUEST['iZoneId'];
    }

    if($_REQUEST['iNetworkId'] != ""){
        $arr_param['iNetworkId'] = $_REQUEST['iNetworkId'];
    }
    
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    //print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."premise_list.json";
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
    $cnt_export = $result_arr['result']['total_record'];
    $rs_export = $result_arr['result']['data'];
     //  echo "<pre>";print_r($rs_export);exit();
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    // // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $file_name = "premise_".time().".xlsx";

    if($cnt_export >0) {

        $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A1', 'Id')
                 ->setCellValue('B1', 'Premise Name')
                 ->setCellValue('C1', 'Premise Type')
                 ->setCellValue('D1', 'Premise Sub Type')
                 ->setCellValue('E1', 'Address')
                 ->setCellValue('F1', 'City')
                 ->setCellValue('G1', 'State')
                 ->setCellValue('H1', 'County');
    
        for($e=0; $e<$cnt_export; $e++) {
            $vAddress = gen_strip_slash($rs_export[$e]['vAddress1'].' '.$rs_export[$e]['vStreet']);

            $name = gen_strip_slash($rs_export[$e]['vName']) ;
       
            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iPremiseId'])
            ->setCellValue('B'.($e+2), $name)
            ->setCellValue('C'.($e+2), $rs_export[$e]['vTypeName'])
            ->setCellValue('D'.($e+2), $rs_export[$e]['vSubTypeName'])
            ->setCellValue('E'.($e+2), $vAddress)
            ->setCellValue('F'.($e+2), $rs_export[$e]['vCity'])
            ->setCellValue('G'.($e+2), $rs_export[$e]['vState'])
            ->setCellValue('H'.($e+2), $rs_export[$e]['vCounty']);
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
        
        /* Set Font to Bold for each comlumn */
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
        

        /* Set Alignment of Selected Columns */
        $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Premise');

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
}else if($mode == "upload_document"){
    $arr_param = array();
    $files = "";
    if(isset($_FILES["vFile"])){
        $tmpfile = $_FILES["vFile"]['tmp_name'];
        $filename = basename($_FILES["vFile"]['name']);
        $files =  curl_file_create($tmpfile, $_FILES["vFile"]['type'], $filename);
    }

    $arr_param = array(
        "iPremiseId"           => $_POST['iPremiseId'],
        "vTitle"            => $_POST['vTitle'],
        "vFile"             => $files,
        "vLoginUserName"    =>$_SESSION["sess_vName".$admin_panel_session_suffix],
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."add_premise_document.json";
    //echo $API_URL."<br/>";
    //echo json_encode($arr_param);exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_param);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: multipart/form-data",
    ));
    $response = curl_exec($ch);
    curl_close($ch); 
    $result_arr = json_decode($response, true); 
    $result = $result_arr['result'];
    //echo "<pre>;";print_r($result);exit;
    
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # ----------------------------------- 
}else if ($mode == "delete_site_docoument") {
    //echo "<pre>";print_r($_POST);exit();
    $result = array();
    $arr_param = array();
    $arr_param = array(
        "iSDId"             => $_POST['iSDId'],
        "iPremiseId"           => $_POST['iPremiseId'],
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."delete_premise_document.json";
    //echo $API_URL."<br/>";
    //echo json_encode($arr_param);exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_param);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: multipart/form-data",
    ));
    $response = curl_exec($ch);
    curl_close($ch); 
    $result_arr = json_decode($response, true); 
    $result = $result_arr['result'];
    echo json_encode($result);
    hc_exit();
}else if($mode == "Kml"){
    $premiseId=$_REQUEST['premiseId'];
    $arr_param = array(
        "iPremiseId"=> $premiseId,
        'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );
    //echo"<pre>";print_r(json_encode($arr_param));exit;
    $API_URL = $site_api_url."exportkml.json";
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
    $result=$res['result']['result'];

$kml = '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2">
    <Document>
        <Style id="linestyle">
            <LineStyle>
                <color>7f0000ff</color>
                <width>4</width>
                <gx:labelVisibility>1</gx:labelVisibility>
            </LineStyle>
        </Style>
        <Style id="polystyle">
            <LineStyle>
                <color>7f0000ff</color>
                <width>4</width>
                <gx:labelVisibility>1</gx:labelVisibility>
            </LineStyle>
            <PolyStyle>
                <fill>0</fill>
            </PolyStyle>
        </Style>';
$draw='';
$kml_count = count($result);
for($k=0; $k<$kml_count; $k++){
$iPremiseId=($result[$k]["iPremiseId"] ? $result[$k]["iPremiseId"] :'');
$vName=($result[$k]["vName"] ? $result[$k]["vName"] :'');
$vTypeName=($result[$k]["vTypeName"] ? $result[$k]["vTypeName"] :'');
$vSubTypeName=($result[$k]["vSubTypeName"] ? '('.$result[$k]["vSubTypeName"].')' :'');
$Attributes=($result[$k]["string_agg"] ? $result[$k]["string_agg"] :'');
$vAddress=($result[$k]["vAddress"] ? $result[$k]["vAddress"] :'');
$vZoneName=($result[$k]["vZoneName"] ? $result[$k]["vZoneName"] :'');

if($result[$k]["iGeometryType"]==1)
{
    $draw=$result[$k]["vPointKML"];
}

$kml.=
    '<Placemark>
        <name>Premise ID: '.$iPremiseId.'</name>
        <description>
            <![CDATA[
              <h1>Name: '.$vName.'</h1>
              <p><font color="red">Type: '.$vTypeName.' '.$vSubTypeName.'</font></p>
              <p>Attributes: '.$Attributes.'</p>
              <p>Address: '.$vAddress.'</p>
              <p>Zone: '.$vZoneName.'</p>
            ]]>
        </description>
        '.$draw.'
    </Placemark>
';

}

// End XML file
$kml .= ' </Document>';
$kml .= '</kml>';
//echo $kml;exit;
    ob_clean();
    $file = time().".kml";
    header("Pragma: public");
    header("Expires: 0"); // set expiration time
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header('Content-Disposition: attachment; filename=' . $file);
    echo $kml;
    /*file_put_contents($cache_file_path.$file, $kml);

    $file_path = $cache_file_path.$file;
    $file_url = $cache_file_url.$file;
    if(file_exists($file_path) && $file != '')
    {   
        ob_clean();
        # -------------------------------------
        header("Pragma: public");
        header("Expires: 0"); // set expiration time
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".basename($file_path).";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($file_path));
        
        @readfile($file_url);
        unlink($file_path);
    }*/
    hc_exit();
}else if($mode == "multiple_batch_premises"){
	//echo "<pre>";print_r($_POST);exit;
	$arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        $arr_param = array(
            "batch_latlong"		=> $_POST['batch_latlong'],
            "iSMapTypeId"		=> $_POST['iSMapTypeId'],
            "iSSMapTypeId"		=> $_POST['iSSMapTypeId'],
            "iSMapAttributeId"	=> $_POST['iSMapAttributeId'],
			"vLoginUserName"	=>$_SESSION["sess_vName".$admin_panel_session_suffix],
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        );
        $API_URL = $site_api_url."premise_batch_multiple_add.json";
        //echo $API_URL. " ". json_encode($arr_param);exit;
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

        $response_site = curl_exec($ch);
        curl_close($ch); 
        $result_site_arr = json_decode($response_site, true); 

        if(!empty($result_site_arr)){
            $result['sites'] = @implode(",", $result_site_arr['site_arr']);
            $result['msg'] = $result_site_arr['Message'];
            $result['error']= 0 ;
        }else{
            $result['msg'] = $result_site_arr['Message'];
            $result['error']= 1 ;
        }
    }else {
        $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    
}else if($mode == "edit_premises_single_batch"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit();
        $arr_param = array(
            "iPremiseId"        => addslashes($_POST['iPremiseId']),
            "iSTypeId"          => addslashes($_POST['iSTypeId1']),
            "iSSTypeId"         => addslashes($_POST['iSSTypeId1']),
            "iStatus"           => $_POST['iStatus'],
            "vLoginUserName"    =>$_SESSION["sess_vName".$admin_panel_session_suffix],
            "sessionId"         => $_SESSION["we_api_session_id".$admin_panel_session_suffix],
        );
        //echo "<pre>";print_r($site_arr);exit();
        $API_URL = $site_api_url."premise_batch_edit.json";
        // echo $API_URL. " ".json_encode($arr_param);exit;
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

        $response_site = curl_exec($ch);
        // echo "<pre>"; print_r($response_site);exit();
        curl_close($ch); 
        $result_site_arr = json_decode($response_site, true); 

        if(!empty($result_site_arr)){
            $result['iPremiseId'] = $_POST['iPremiseId'];
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
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}

/*-------------------------- Premise Type -------------------------- */
$premise_type_arr_param = array();
$premise_type_arr_param = array(
    "iStatus"    => 1,
    "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$premise_type_API_URL = $site_api_url."premise_type_dropdown.json";
//echo json_encode($premise_type_arr_param);exit();
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $premise_type_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_type_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_sitetype = curl_exec($ch);
curl_close($ch); 
$rs_sitetype1 = json_decode($response_sitetype, true); 
$rs_sitetype = $rs_sitetype1['result'];
$smarty->assign("rs_site_type", $rs_sitetype);
//echo "<pre>";print_r($rs_sitetype);exit;
/*-------------------------- Premise Type -------------------------- */

/*-------------------------- Premise Sub Type -------------------------- */
$psubtype_param = array();
$psubtype_param['iStatus'] = '1';
$psubtype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$psubtypeAPI_URL = $site_api_url."premise_sub_type_dropdown.json";
//echo $psubtypeAPI_URL." ".json_encode($psubtype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $psubtypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($psubtype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_psubtype = curl_exec($ch);
curl_close($ch);  
$res_psubtype= json_decode($response_psubtype, true);
$rs_premise_sub_type = $res_psubtype['result'];
$smarty->assign("rs_site_sub_type", $rs_premise_sub_type);
/*-------------------------- Premise Sub Type -------------------------- */

## --------------------------------
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
/*-------------------------- Engagement -------------------------- */
$arr_param = array();
$arr_param['iStatus']   = 1;
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."engagement_dropdown.json";
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
$res = json_decode($response, true);
$smarty->assign("rs_engagement", $res['result']);
/*-------------------------- Engagement -------------------------- */

/*-------------------------- User -------------------------- */
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."getUserDropdown.json";
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
$res = json_decode($response, true);
$smarty->assign("technician_user_arr", $res['result']);
//echo "<pre>";print_r($res['result']);exit;
/*-------------------------- User -------------------------- */
/***********************************/
$module_name = "Premise List";
$module_title = "Premise";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);
$smarty->assign("dDate", date('Y-m-d'));
$smarty->assign("dStartTime", date("H:i", time()));
$smarty->assign("dEndTime", date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes")));
?>