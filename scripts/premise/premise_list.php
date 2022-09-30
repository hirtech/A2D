<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise", 'List');
$access_group_var_delete = per_hasModuleAccess("Premise", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("Premise", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Premise", 'CSV', 'N');
$access_group_var_Respond = per_hasModuleAccess("Premise", 'Respond', 'N');
# ----------- Access Rule Condition -----------


include_once($controller_path . "premise.inc.php");

include_once($controller_path . "premise_sub_type.inc.php");
include_once($controller_path . "premise_type.inc.php");
include_once($controller_path . "mosquito_species.inc.php");
include_once($controller_path . "trap_type.inc.php");
include_once($controller_path . "task_type.inc.php");
include_once($controller_path . "user.inc.php");

include_once($function_path."image.inc.php");

$MosquitoSpeciesObj = new MosquitoSpecies();
$TrapTypeObj = new TrapType();
$TaskTypeObj = new TaskType();

$page = $_REQUEST['page'];
# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '1');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
$SiteObj = new Site();
//echo "<pre>";print_r($access_group_var_delete);exit();
//echo $mode;exit();
if($mode == "List"){
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }
    if ($_REQUEST['siteId'] != "") {
        $arr_param['siteId'] = $_REQUEST['siteId'];
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

    if($_REQUEST['vCountry'] != ""){
        $arr_param['vCountry'] = $_REQUEST['vCountry'];
        $arr_param['CountryFilterOpDD'] = $_REQUEST['CountryFilterOpDD'];
    }

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
    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_site = $result_arr['result']['data'];
    $ni = count($rs_site);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if($access_group_var_edit == '1'){
               $action .= '<a class="btn btn-outline-secondary" title="Edit"  href="'.$site_url.'premise/edit&mode=Update&iSiteId=' . $rs_site[$i]['iSiteId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_site[$i]['iSiteId'].');"><i class="fa fa-trash"></i></a>';
            }
            
            $action .= ' <a class="btn btn-outline-warning" title="Premise History" target="_blank" href="'.$site_url.'premise/history&iSiteId=' . $rs_site[$i]['iSiteId'] . '&vName=' . $rs_site[$i]['vName'] . '"><i class="fas fa-history"></i></a>'; 
            
            if(per_hasModuleAccess("Task Larval Surveillance", 'List')){
                $action .= ' <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tasks</button>
                    <div class="dropdown-menu p-0">';
                    if(per_hasModuleAccess("Task Larval Surveillance", 'List')){
                        $action .= '<a class="dropdown-item" title="Larval Surveillance"  onclick="addEditDataTaskLarval(0,\'add\','.$rs_site[$i]['iSiteId'].')">Larval Surveillance</a>';
                    }
                    if(per_hasModuleAccess("Task Landing Rate", 'List')){
                        $action .= '<a class="dropdown-item" title="Landing Rate"  onclick="addEditDataTaskAdult(0,\'add\','.$rs_site[$i]['iSiteId'].')">Landing Rate</a>';
                    }
                    if(per_hasModuleAccess("Task Trap", 'List')){
                        $action .= '<a class="dropdown-item" title="Trap"  onclick="addEditDataTaskTrap(0,\'add\','.$rs_site[$i]['iSiteId'].')">Trap</a>';
                    }

                    if(per_hasModuleAccess("Task Treatment", 'List')){
                        $action .= '<div class="dropdown-divider"></div>';
                        $action .= '<a class="dropdown-item" title="Treatment Task"  onclick="addEditDataTaskTreatment(0,\'add\','.$rs_site[$i]['iSiteId'].')">Treatment</a>';
                    }

                    if(per_hasModuleAccess("Task Other", 'List')){
                        $action .= '<a class="dropdown-item" title="Other Task"  onclick="addEditDataTaskOther(0,\'add\','.$rs_site[$i]['iSiteId'].')">Other</a>';
                    }
                    $action .= '</div>';
            }
            $vAddress = $rs_site[$i]['vAddress1'].' '.$rs_site[$i]['vStreet'];
            $entry[] = array(
                    "checkbox" => '<input type="checkbox" class="list" value="'.$rs_site[$i]['iSiteId'].'"/>',
                    "iSiteId" => gen_strip_slash($rs_site[$i]['iSiteId']),
                    "vName" => gen_strip_slash($rs_site[$i]['vName']),
                    "vSiteType" => gen_strip_slash($rs_site[$i]['vTypeName']),
                    "vSiteSubType" => gen_strip_slash($rs_site[$i]['vSubTypeName']),
                    "vAddress" => $rs_site[$i]['vAddress'],
                    'vCity' => $rs_site[$i]['vCity'],
                    'vState' => $rs_site[$i]['vState'],
                    'vZoneName' => $rs_site[$i]['vZoneName'],
                    'vNetwork' => $rs_site[$i]['vNetwork'],
                    'vCounty' => $rs_site[$i]['vCounty'],
                    "iStatus" => '<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_site[$i]['iStatus']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_site[$i]['iStatus'])].'">&nbsp;</span>',
                    "actions" => ($action!="")?$action:"---"
           
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
}
else if($mode == "Add"){
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
            $result['iSiteId'] = $result_site_arr['iSiteId'];
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
            "iSiteId" => addslashes($_POST['iSiteId']),
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
            $result['iSiteId'] = $_POST['iSiteId'];
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
else if($mode == "Delete"){

    $result = array();
    $arr_param = array();
    $iSiteId = $_POST['iSiteId'];
    
    $arr_param['iSiteId'] = $iSiteId; 
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

    $iSiteId = $_POST['iSiteId'];

    $rs_tot = $SiteObj->delete_single_record($iSiteId);
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
else if ($mode == "get_zone_from_latlong") {
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
} else if($mode== "Excel"){
   $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }
    if ($_REQUEST['siteId'] != "") {
        $arr_param['siteId'] = $_REQUEST['siteId'];
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

    if($_REQUEST['vCountry'] != ""){
        $arr_param['vCountry'] = $_REQUEST['vCountry'];
        $arr_param['CountryFilterOpDD'] = $_REQUEST['CountryFilterOpDD'];
    }

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
            ->setCellValue('A'.($e+2), $rs_export[$e]['iSiteId'])
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
} else if($mode == "upload_document"){
    $arr_param = array();
    $files = "";
    if(isset($_FILES["vFile"])){
        $tmpfile = $_FILES["vFile"]['tmp_name'];
        $filename = basename($_FILES["vFile"]['name']);
        $files =  curl_file_create($tmpfile, $_FILES["vFile"]['type'], $filename);
    }

    $arr_param = array(
        "iSiteId"           => $_POST['iSiteId'],
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
        "iSiteId"           => $_POST['iSiteId'],
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
    $siteId=$_REQUEST['siteId'];
    $arr_param = array(
        "iSiteId"=> $siteId,
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
$iSiteId=($result[$k]["iSiteId"] ? $result[$k]["iSiteId"] :'');
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
        <name>Premise ID: '.$iSiteId.'</name>
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
}

# Premise Type Dropdown
$SiteTypeObj = new SiteType();
$where_arr = array();
$SiteTypeObj->where = $where_arr;
$SiteTypeObj->param['order_by'] = "site_type_mas.\"vTypeName\"";
$SiteTypeObj->setClause();
$rs_site_type = $SiteTypeObj->recordset_list();
//echo "<pre>";print_r($rs_site_type);exit;
$smarty->assign("rs_site_type", $rs_site_type);

## --------------------------------
# Premise Sub type Dropdown

$SiteSubTypeObj = new SiteSubType();
$where_arr = array();
$SiteSubTypeObj->where = $where_arr;
$SiteSubTypeObj->param['order_by'] = "site_sub_type_mas.\"vSubTypeName\"";
$SiteSubTypeObj->setClause();
$rs_site_sub_type = $SiteSubTypeObj->recordset_list();
$smarty->assign("rs_site_sub_type", $rs_site_sub_type);
## --------------------------------

/*-----------------Mosquito Species ---------------------------*/
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."get_mosquito_species_data.json";
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
$smarty->assign("rs_species", $res['result']['data']);
/*-----------------Mosquito Species ---------------------------*/



/*----------------- Task Type  ---------------------------*/
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."task_type_dropdown.json";
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
$smarty->assign("rs_type", $res['result']);
/*-----------------Task Type ---------------------------*/

/*-----------------Trap Type ---------------------------*/
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."trap_type_dropdown.json";
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
$smarty->assign("rs_trap_type", $res['result']);
/*--------------------------------------------------------*/

/*USer data*/
$UserObj = new User();
$where_arr = array();
$join_fieds_arr = array();
$join_arr  = array();
$UserObj->user_clear_variable();
$where_arr[] = "user_mas.\"iStatus\" = '1'";

$UserObj->join_field = $join_fieds_arr;
$UserObj->join = $join_arr;
$UserObj->where = $where_arr;
$UserObj->setClause();
$rs_user_data = $UserObj->recordset_list();
$smarty->assign("technician_user_arr", $rs_user_data);
/***********************************/
$module_name = "Premise List";
$module_title = "Premise";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);

$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);

$smarty->assign("dDate", date('Y-m-d'));
$smarty->assign("dStartTime", date("H:i", time()));
$smarty->assign("dEndTime", date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes")));
?>