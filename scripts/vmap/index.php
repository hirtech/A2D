<?php

include_once($site_path . "scripts/session_valid.php");
include_once($controller_path . "premise.inc.php");
include_once($controller_path . "premise_attribute.inc.php");
include_once($controller_path . "mosquito_species.inc.php");
include_once($controller_path . "trap_type.inc.php");
include_once($controller_path . "task_type.inc.php");
include_once($controller_path . "task_trap.inc.php");
include_once($controller_path . "task_mosquito_pool_result.inc.php");
include_once($controller_path . "custom_layer.inc.php");
include_once($controller_path . "user.inc.php");
include_once($controller_path . "service_order.inc.php");


$SiteObj = new Site();
$SiteAttribute = new SiteAttribute();
$MosquitoSpeciesObj = new MosquitoSpecies();
$TrapTypeObj = new TrapType();
$TaskTypeObj = new TaskType();
$TaskTrapObj = new TaskTrap();
$TaskMosquitoPoolResultObj = new TaskMosquitoPoolResult();
$CustomLayerObj = new CustomLayer();
$ServiceOrderObj = new ServiceOrder();

/*Get Map Filter data*/
$API_URL = $site_api_url."get_map_filter_data.json";
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
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

$result = json_decode($response, true);
$result_filter_data= $result['result'];
$sk_zones =  $result_filter_data['zone'];
$sTypes = $result_filter_data['site_type'];
$sAttrubutes = $result_filter_data['site_attribute'];
$cityArr = $result_filter_data['city'];
/*Get Map Filter data*/

/*Get Map Cluster layer*/
$API_URL = $site_api_url."get_map_cluster_layers.json";
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
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

$result = json_decode($response, true);
$result_layer_data= $result['result'];
$custLayers = $result_layer_data['custLayers'];
/*Get Map Filter data*/

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
//echo print_r($_REQUEST);exit();
if($mode == "site_map")
{
    $iSiteId = $_REQUEST['iSiteId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "s.\"iSiteId\"='".gen_add_slash($iSiteId)."'";
	
    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_fieds_arr[] = 'sbt."vSubTypeName"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on st."iSTypeId" = s."iSTypeId"';
    $join_arr[] = 'LEFT JOIN site_sub_type_mas sbt on sbt."iSSTypeId" = s."iSSTypeId"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
	
    $SiteObj->join_field = $join_fieds_arr;
    $SiteObj->join = $join_arr;
    $SiteObj->where = $where_arr;
    $SiteObj->param['limit'] = "LIMIT 1";
    $SiteObj->setClause();
	$SiteObj->debug_query = false;
    $rs_site['site'] = $SiteObj->recordset_list();
	//echo "<pre>";print_r($rs_site);exit;
    $rs_site['site'][0]['ServiceOrderCount'] = 0;
    if(!empty($rs_site['site'])){
        $ServiceOrderObj->clear_variable();
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr  = array();
        $where_arr[] = "\"iPremiseId\"='".gen_add_slash($iSiteId)."'";
        $ServiceOrderObj->join_field = $join_fieds_arr;
        $ServiceOrderObj->join = $join_arr;
        $ServiceOrderObj->where = $where_arr;
        $ServiceOrderObj->setClause();
        $rs_sorder = $ServiceOrderObj->recordset_list();
        $rs_site['site'][0]['ServiceOrderCount'] = count($rs_sorder);
    }
    //echo "<pre>";print_r($rs_site);exit;


    $SiteObj->clear_variable();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "\"iSiteId\"='".gen_add_slash($iSiteId)."'";
    $join_fieds_arr[] = '"iSAttributeId"';
    $join_fieds_arr[] = 'sam."vAttribute"';
    $join_arr[] = 'LEFT JOIN site_attribute sa on sa."iSAttributeId" = site_attribute_mas."iSAttributeId"';
    $SiteAttribute->join_field = $join_fieds_arr;
    $SiteAttribute->join = $join_arr;
    $SiteAttribute->where = $where_arr;
    $SiteAttribute->setClause();
    $site_attribute = $SiteAttribute->recordset_list();
    $ar_count=count($site_attribute);
    $att_array = array();
    for($i=0;$i<$ar_count;$i++)
    {
        $att_array[] = $site_attribute[$i]['vAttribute'];
    }
	$rs_site['site_attribute'] = '';
	if(!empty($att_array)) {
		$rs_site['site_attribute'] = implode(',', $att_array);
	}

    $arr_param['iPremiseId'] = $iSiteId;
    $arr_param['page_type'] = "site_info_window";
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    $API_URL = $site_api_url."premise_history.json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
     "Content-Type: application/json",
     ));
    $response = curl_exec($ch);
    curl_close($ch);
    $result_arr = json_decode($response, true);
    //echo "<pre>";print_r($result_arr);exit();  

    //$result = json_decode($result_arr['result']);

    $rs_site['site_history'] = setSiteHistory($result_arr['result']);;
    echo json_encode($rs_site);
    hc_exit();

}
if($mode == "site_map_landing_rate")
{
    $iSiteId = $_REQUEST['iSiteId'];
    
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "s.\"iSiteId\"='".gen_add_slash($iSiteId)."'";
    //$where_arr[] = "tlr.\"dDate\"='".date('Y-m-d')."'";

    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_fieds_arr[] = 'sbt."vSubTypeName"';
    $join_fieds_arr[] = 'tlr."dDate"';
    $join_fieds_arr[] = 'tlr."iSRId"';
    $join_fieds_arr[] = 'tlr."tNotes"';
    $join_fieds_arr[] = 'tlr."vMaxLandingRate"';  
    $join_arr[] = 'LEFT JOIN site_type_mas st on st."iSTypeId" = s."iSTypeId"';
    $join_arr[] = 'LEFT JOIN site_sub_type_mas sbt on sbt."iSSTypeId" = s."iSSTypeId"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN task_landing_rate tlr on s."iSiteId" = tlr."iSiteId"';

    $SiteObj->join_field = $join_fieds_arr;
    $SiteObj->join = $join_arr;
    $SiteObj->where = $where_arr;
    $SiteObj->setClause();
    $SiteObj->debug_query = false;
    $rs_site['site'] = $SiteObj->recordset_list();
    //echo "<pre>";print_r($rs_site);exit;

    $SiteObj->clear_variable();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "\"iSiteId\"='".gen_add_slash($iSiteId)."'";
    $join_fieds_arr[] = '"iSAttributeId"';
    $join_fieds_arr[] = 'sam."vAttribute"';
    $join_arr[] = 'LEFT JOIN site_attribute sa on sa."iSAttributeId" = site_attribute_mas."iSAttributeId"';
    $SiteAttribute->join_field = $join_fieds_arr;
    $SiteAttribute->join = $join_arr;
    $SiteAttribute->where = $where_arr;
    $SiteAttribute->setClause();
    $site_attribute = $SiteAttribute->recordset_list();
    $ar_count=count($site_attribute);
    $att_array = array();
    for($i=0;$i<$ar_count;$i++)
    {
        $att_array[] = $site_attribute[$i]['vAttribute'];
    }
    $rs_site['site_attribute'] = '';
    if(!empty($att_array)) {
        $rs_site['site_attribute'] = implode(',', $att_array);
    }

    
    echo json_encode($rs_site);
    hc_exit();

}
else if($mode == "site_map_larval")
{
    $iSiteId = $_REQUEST['iSiteId'];
    
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "s.\"iSiteId\"='".gen_add_slash($iSiteId)."'";
   // $where_arr[] = "tls.\"dDate\"='".date('Y-m-d')."'";
    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_fieds_arr[] = 'sbt."vSubTypeName"';
    $join_fieds_arr[] = 'tls."dDate"';
    $join_fieds_arr[] = 'tls."iSRId"';
    $join_fieds_arr[] = 'tls."tNotes"';
    $join_fieds_arr[] = 'tls."iGenus"';
    $join_fieds_arr[] = 'tls."iGenus2"';
    $join_fieds_arr[] = 'tls."bEggs"';
    $join_fieds_arr[] = 'tls."bEggs2"';

    $join_fieds_arr[] = 'tls."iCount"';
    $join_fieds_arr[] = 'tls."bInstar1"';
    $join_fieds_arr[] = 'tls."bInstar2"';
    $join_fieds_arr[] = 'tls."bInstar3"';
    $join_fieds_arr[] = 'tls."bInstar4"';
    $join_fieds_arr[] = 'tls."bPupae"';
    $join_fieds_arr[] = 'tls."bAdult"';
    $join_fieds_arr[] = 'tls."iCount2"';
    $join_fieds_arr[] = 'tls."bInstar12"';
    $join_fieds_arr[] = 'tls."bInstar22"';
    $join_fieds_arr[] = 'tls."bInstar32"';
    $join_fieds_arr[] = 'tls."bInstar42"';
    $join_fieds_arr[] = 'tls."bPupae2"';
    $join_fieds_arr[] = 'tls."bAdult2"';
    $join_fieds_arr[] = 'tls."iDips"';
    $join_fieds_arr[] = 'tls."rAvgLarvel"';


    $join_arr[] = 'LEFT JOIN site_type_mas st on st."iSTypeId" = s."iSTypeId"';
    $join_arr[] = 'LEFT JOIN site_sub_type_mas sbt on sbt."iSSTypeId" = s."iSSTypeId"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN task_larval_surveillance tls on s."iSiteId" = tls."iSiteId"';

    $SiteObj->join_field = $join_fieds_arr;
    $SiteObj->join = $join_arr;
    $SiteObj->where = $where_arr;
    $SiteObj->setClause();
    $SiteObj->debug_query = false;
    $rs_site['site'] = $SiteObj->recordset_list();
    //echo "<pre>";print_r($rs_site);exit;

    $SiteObj->clear_variable();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "\"iSiteId\"='".gen_add_slash($iSiteId)."'";
    $join_fieds_arr[] = '"iSAttributeId"';
    $join_fieds_arr[] = 'sam."vAttribute"';
    $join_arr[] = 'LEFT JOIN site_attribute sa on sa."iSAttributeId" = site_attribute_mas."iSAttributeId"';
    $SiteAttribute->join_field = $join_fieds_arr;
    $SiteAttribute->join = $join_arr;
    $SiteAttribute->where = $where_arr;
    $SiteAttribute->setClause();
    $site_attribute = $SiteAttribute->recordset_list();
    $ar_count=count($site_attribute);
    $att_array = array();
    for($i=0;$i<$ar_count;$i++)
    {
        $att_array[] = $site_attribute[$i]['vAttribute'];
    }
    $rs_site['site_attribute'] = '';
    if(!empty($att_array)) {
        $rs_site['site_attribute'] = implode(',', $att_array);
    }

    
    echo json_encode($rs_site);
    hc_exit();

}
else if ($mode == "site_map_positive") {
    $trap_data = array();
    if($_REQUEST['iTTId']){
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr  = array();
        $join_fieds_arr = array();
        // if($_REQUEST['iTMPId'] != ""){
        //     $where_arr[] = 'tmp."iTMPId" = '.$_REQUEST['iTMPId'];
        // }

        $join_fieds_arr[] = 's."vName"';
        $join_fieds_arr[] = 's."vAddress1"';
        $join_fieds_arr[] = 's."vStreet"';
        $join_fieds_arr[] = 'st."vTypeName"';
        $join_fieds_arr[] = 'c."vCounty"';
        $join_fieds_arr[] = 'sm."vState"';
        $join_fieds_arr[] = 'cm."vCity"';
        $join_fieds_arr[] = 'tt."vTrapName"';
        $join_fieds_arr[] = 'tmp."vPool"';
        $join_fieds_arr[] = 'tmp."iNumberinPool"';
        $join_fieds_arr[] = 'tmp."iCountMosqperpool"';
        $join_fieds_arr[] = 'tmp."iTMPId"';
        $join_fieds_arr[] = 'tmp."bLabWorkComplete" as poollabworkcomplete';
        $join_arr = array();
        $join_arr[] = 'LEFT JOIN trap_type_mas tt on tt."iTrapTypeId" = task_trap."iTrapTypeId"';
        $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = task_trap."iSiteId"';
        $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
        $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
        $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
        $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
        $join_arr[] = 'LEFT JOIN task_mosquito_pool tmp on tmp."iTTId" = task_trap."iTTId"';

        $where_arr[] = 'task_trap."iTTId" = '.$_REQUEST['iTTId'];
        //$where_arr[] = 'tmp."iTMPId" = '.$_REQUEST['iTMPId'];
        $TaskTrapObj->join_field = $join_fieds_arr;
        $TaskTrapObj->join = $join_arr;
        $TaskTrapObj->where = $where_arr;
        $TaskTrapObj->param['limit'] = 1;
        $TaskTrapObj->setClause();
        $rs_taskTrap = $TaskTrapObj->recordset_list();
       // echo "<pre>";print_r($rs_taskTrap);exit();
        $nt = count($rs_taskTrap);
        if($nt > 0){
            $vSite = $rs_taskTrap[0]['vName']."- PremiseID#".$rs_taskTrap[0]['iSiteId'];
            $vAddress =  $rs_taskTrap[0]['vAddress1'].' '.$rs_taskTrap[0]['vStreet'].' '.$rs_taskTrap[0]['vCity'].', '.$rs_taskTrap[0]['vState'].' '.$rs_taskTrap[0]['vCounty'];

            $vSiteName = $rs_taskTrap[0]['iSiteId']." (".$rs_taskTrap[0]['vName']."; ".$rs_taskTrap[0]['vTypeName'].")";

            $poolbLabWorkComplete = ($rs_taskTrap[0]['poollabworkcomplete']=='t')?'1':'0';

            $trap_data = array(
                    "iTTId" => $rs_taskTrap[0]['iTTId'],
                    "iSiteId" => $rs_taskTrap[0]['iSiteId'],
                    "vSiteName" => gen_strip_slash($vSite),
                    "vSiteAddress" => $vAddress,
                    "dTrapPlaced" => date_getDateTimeDDMMYYYY($rs_taskTrap[0]['dTrapPlaced']),
                    "dTrapCollected" => date_getDateTimeDDMMYYYY($rs_taskTrap[0]['dTrapCollected']),
                    "vTrapName" => $rs_taskTrap[0]['vTrapName']    ,   
                    "vPool" => $rs_taskTrap[0]['vPool']    ,   
                    "iNumberinPool" => $rs_taskTrap[0]['iNumberinPool']    ,   
                    "iTMPId" => $rs_taskTrap[0]['iTMPId'] ,   
                    "bLabWorkComplete" => $poolbLabWorkComplete,  
                );

            $TaskTrapObj->clear_variable();
                $where_arr = array();
                $join_fieds_arr = array();          
                $join_arr = array();
                $join_fieds_arr[] = 'result."vResult"';
                $join_fieds_arr[] = 'agent_mosquito."vTitle"';
                $join_arr[] = 'LEFT JOIN agent_mosquito on agent_mosquito."iAMId" = task_mosquito_pool_result."iAMId"';
                $join_arr[] = 'LEFT JOIN result on result."iResultId" = task_mosquito_pool_result."iResultId"';
                $TaskMosquitoPoolResultObj->join_field = $join_fieds_arr;
                $TaskMosquitoPoolResultObj->join = $join_arr;
                $where_arr[] = 'task_mosquito_pool_result."iTMPId"='.$rs_taskTrap[0]['iTMPId'];
                $where_arr[] = 'result."vResult"=\'Positive\'';

                $TaskMosquitoPoolResultObj->where = $where_arr;
                $TaskMosquitoPoolResultObj->param['order_by'] = "task_mosquito_pool_result.\"iTMPRId\" DESC";
                $TaskMosquitoPoolResultObj->param['limit'] = 0;
                $TaskMosquitoPoolResultObj->setClause();
                $trap_data['result'] = $TaskMosquitoPoolResultObj->recordset_list();

            echo json_encode($trap_data);
            hc_exit();
        }
    }
}
else if($mode == "search_site"){
    # -----------------------------------
    /*Search site api*/
    $arr_param = array();
    $vName = trim($_REQUEST['vName']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['siteName'] = $vName;
    $API_URL = $site_api_url."search_premise.json";
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
    
    # -----------------------------------
    # Return data.
    # -----------------------------------
    echo  json_encode($result_arr['result']['data']);
    hc_exit();
    # -----------------------------------
}
else if($mode == "serach_iSiteId"){
    $vLatitude = trim($_REQUEST['vLatitude']);
    $vLongitude = trim($_REQUEST['vLongitude']);
    $where_arr = array();
    $where_arr[] = 's."vLatitude"='.$vLatitude;
    $where_arr[] = 's."vLongitude"='.$vLongitude;
 
    $SiteObj->join_field = '';
    $SiteObj->join = '';
    $SiteObj->where = $where_arr;
    $SiteObj->param['limit'] = "";
    $SiteObj->setClause();
    $SiteObj->debug_query = false;
    $rs_site['site'] = $SiteObj->recordset_list();
    $site_list = array();
    $site_list_id ='';
    foreach ($rs_site['site'] as $key => $site_rows) {
        # code...
        $site_list[] = $site_rows['iSiteId'];
    }
    $site_list_id = implode(',', $site_list);
    echo $site_list_id;
    hc_exit();
}
else if($mode == "AddInstaTreat"){
   // echo "<pre>";print_r($_REQUEST);exit();
    $result =array();
   
    $dDate = date('Y-m-d');
    $dStartDate = date('Y-m-d H:i:s');
    $dEndDate = date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s')." +10 minutes"));

    $arr_param = array(
        "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "iSiteId"        => $_POST['siteId'],
        "dDate"          => $dDate,
        "vType"          => 'Spot Treatment',
        "dStartDate"     => $dStartDate,
        "dEndDate"       => $dEndDate,
        "iTPId"          => $INSTA_TREATMENT_PRODUCT_ID,
        "vArea"          => $INSTA_TREATMENT_AREA,
        "vAreaTreated"   => $INSTA_TREATMENT_AREA_TREATED,
        "vAmountApplied" => $INSTA_TREATMENT_AMOUNT_APPLIED,
        "iUId"           => $INSTA_TREATMENT_UNIT_ID,
        "iUserId"        => $_SESSION["sess_iUserId".$admin_panel_session_suffix]
    );
   //echo "<pre>";print_r(json_encode($arr_param));exit();

    $API_URL = $site_api_url."task_treatment_add.json";
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

    $res = curl_exec($ch);  
    curl_close($ch);  
    $result_arr = json_decode($res,true);

    if($result_arr['result']['iTreatmentId']){
        $result['msg'] = MSG_ADD;
        $result['error']= 0 ;
    }else{
        $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;
    }
    
    echo json_encode($result);
    hc_exit(); 
}
else if($mode == "getCurrentLocation"){
    //echo "<pre>";print_r($_SERVER);exit();
    //echo $_SERVER['HTTP_X_FORWARDED_FOR']."=>".$_SERVER['HTTP_CLIENT_IP'];exit();

    $ip =$_SERVER['REMOTE_ADDR'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, "https://api.ipify.org?format=json");
    $res = curl_exec($ch);
    curl_close($ch);
    
    $cruldata =json_decode($res,true);
    $local_ip = (isset($cruldata['ip']))?$cruldata['ip']:$ip;
    
    //echo  "https://api.ipgeolocationapi.com/geolocate/" . $local_ip;exit();
 
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_URL, "https://api.ipgeolocationapi.com/geolocate/" . $local_ip);

    $returnData = curl_exec($curl);
    curl_close($curl);

    echo $returnData;
    hc_exit(); 
}

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
/*--------------------------------------------------------*/
function setSiteHistory($data)
{
    $site_history_data = array();
        $site_data = $data['data'];
    if(!empty($site_data)){
        if(!empty($site_data)){
            foreach($site_data as $k => $val){
                $operation_type_data = $val['operation_type_data'];
                $hidden_fields = "" ;
                $hidden_arr = array();
                
                if($val['Type'] == "Awareness") {
                    $hidden_fields = '<input type="hidden" id="iAId_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['iAId'] . '"><input type="hidden" id="vSiteName_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['vSiteName'] . '"><input type="hidden" id="iSiteId_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['iPremiseId'] . '"><input type="hidden" id="dDate_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['dDate'] . '"><input type="hidden" id="dStartDate_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['dStartDate'] . '"><input type="hidden" id="dStartTime_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['dStartTime'] . '"><input type="hidden" id="dEndDate_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['dEndDate'] . '"><input type="hidden" id="dEndTime_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['dEndTime'] . '"><input type="hidden" id="iEngagementId_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['iEngagementId'] . '"><input type="hidden" id="tNotes_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['tNotes'] . '"><input type="hidden" id="srdisplay_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['srdisplay'] . '"><input type="hidden" id="iSRId_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['iFiberInquiryId'] . '"><input type="hidden" id="iTechnicianId_' . $operation_type_data['iAId'] . '" value="' . $operation_type_data['iTechnicianId'] . '">';
                    $hidden_arr = array(
                            "iAId"          => $operation_type_data['iAId'],
                            "vSiteName"     => $operation_type_data['vSiteName'],
                            "iSiteId"       => $operation_type_data['iPremiseId'],
                            "dDate"         => $operation_type_data['dDate'],
                            "dStartDate"    => $operation_type_data['dStartDate'],
                            "dStartTime"    => $operation_type_data['dStartTime'],
                            "dEndDate"      => $operation_type_data['dEndDate'],
                            "dEndTime"      => $operation_type_data['dEndTime'],
                            "iEngagementId" => $operation_type_data['iEngagementId'],
                            "tNotes"        => $operation_type_data['tNotes'],
                            "srdisplay"     => $operation_type_data['srdisplay'],
                            "iSRId"         => $operation_type_data['iFiberInquiryId'],
                            "iTechnicianId" => $operation_type_data['iTechnicianId'],
                        );;
                }
                else if($val['Type'] == "SR") {
                    $hidden_fields = null;
                    $hidden_arr = null;
                }
               $site_history_data[] = array(
                     'Date' => $val['Date'],
                     'Name' => $val['Name'],
                     'Description' => $val['Description'],
                     'Type' => $val['Type'],
                     'id' => $val['id'],                     
                     'hidden_fields' => $hidden_fields,
                     'hidden_arr' => $hidden_arr,
               );
            }
        }
    }
    
    return $site_history_data;
}
/*--------------------------------------------------------*/
/*
echo "<pre>";print_r($sk_zones);exit;*/


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
//echo "<pre>";print_r($rs_sitetype);exit();

// Premise Attribute dropdown
$premise_attr_arr_param = array();
$premise_attr_arr_param = array(
    "iStatus"    => 1,
    "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$premise_attr_API_URL = $site_api_url."premise_attribute_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $premise_attr_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_attr_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_attr = curl_exec($ch);
curl_close($ch); 
$rs_siteattr1 = json_decode($response_attr, true); 
$rs_siteattr = $rs_siteattr1['result'];
$smarty->assign("rs_sitetype", $rs_sitetype);
$smarty->assign("rs_siteattr", $rs_siteattr);


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


$smarty->assign("cityArr", $cityArr);
$smarty->assign("sAttrubutes", $sAttrubutes);
$smarty->assign("skSites", $sTypes);
$smarty->assign("skZones", $sk_zones);
$smarty->assign("custLayers", $custLayers);

$module_name = "Vmap List";
$module_title = "Vmap";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("dDate", date('Y-m-d'));
$smarty->assign("dStartTime", date("H:i", time()));
$smarty->assign("dEndTime", date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes")));

$smarty->assign("tmpmode",$mode);

$smarty->assign("ENABLE_INSTA_TREATMENT",$ENABLE_INSTA_TREATMENT);
$smarty->assign("MAP_LATITUDE",$MAP_LATITUDE);
$smarty->assign("MAP_LONGITUDE",$MAP_LONGITUDE);

?>