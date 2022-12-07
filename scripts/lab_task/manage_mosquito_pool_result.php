<?php

include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Mosquito Pool", 'List');
$access_group_var_delete = per_hasModuleAccess("Mosquito Pool", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Mosquito Pool", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Mosquito Pool", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Mosquito Pool", 'Edit', 'N');
# ----------- Access Rule Condition -----------


include_once($controller_path . "task_trap.inc.php");

# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : '10';
$page_index = isset($_REQUEST['pageIndex']) ? $_REQUEST['pageIndex'] : '1';
$display_order = (isset($_REQUEST["sortField"]) ? $_REQUEST["sortField"] : 'iTMCId');
$dir = (isset($_REQUEST["sortOrder"]) ? $_REQUEST["sortOrder"] : 'desc');
# ------------------------------------------------------------
$iTTId = $_REQUEST['iTTId'];
$iTMPId = $_REQUEST['iTMPId'];

$TaskTrapObj = new TaskTrap();

if($mode == "List"){
	//echo "<pre>";print_r($_REQUEST);exit();
    $arr_param = array();
    $iTMPId = $_REQUEST['iTMPId'];
    $iAMId = $_REQUEST['Agent'];
    $iTMMId = $_REQUEST['Test'];
    $iValue = $_REQUEST['Value'];
    $iResultId = $_REQUEST['Result'];

    if ($iTMPId != "") {
        $arr_param['iTMPId'] = $iTMPId;
    }

    if ($iAMId != "") {
        $arr_param['iAMId'] = $iAMId;
    }

    if ($iTMMId != "") {
        $arr_param['iTMMId'] = $iTMMId;
    }

    if ($iValue != "") {
        $arr_param['iValue'] = $iValue;
    }

    if ($iResultId != "") {
        $arr_param['iResultId'] = $iResultId;
    }

   
    $start = intval($page_length)*intval($page_index-1);
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

	//echo "<pre>";print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."mosquito_pool_result_list.json";
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
   // echo "<pre>";print_r($result_arr['result']['aaData']);exit();
    $res = json_decode( $result_arr['result'],true);
    # Return jSON data.
    # -----------------------------------
    //echo json_encode($res);
    echo json_encode($result_arr['result']);
    hc_exit();
    # -----------------------------------
 
}else if($mode == "Add"){
	//echo "<pre>";print_r($_REQUEST);exit();

	$arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
   
        $arr_param = array(
	        "iTMPId"       => $_REQUEST['iTMPId'],
	        "iAMId"        => $_POST['Agent'],
	        "iTMMId"       => $_POST['Test'],
	        "iValue"       => $_POST['Value'],
	        "iResultId"    => $_POST['Result'],
            "sessionId"    => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );
       //echo "<pre>";print_r(json_encode($arr_param));exit();

        $API_URL = $site_api_url."mosquito_pool_result_add.json";
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

        $response = curl_exec($ch);
     
        curl_close($ch);  

        $result_arr = json_decode($response, true);
         //echo "<pre>";print_r($result_arr);exit();
        $iTMPRId = $result_arr['iTMPRId'];
 
        if($iTMPRId){
            $result['msg'] = MSG_ADD;
            $result['error']= 0 ;
            $result['iTMPRId']= $iTMPRId ;
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
    # ---------------------------------
}else if($mode == "Update"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
         
        $arr_param = array(
      		"iTMPRId"     => $_POST['iTMPRId'],
	        "iTMPId"         => $_REQUEST['iTMPId'],
	        "iAMId"    => $_POST['Agent'],
            "iTMMId"    => $_POST['Test'],
            "iValue"  => $_POST['Value'],
            "iResultId"   => $_POST['Result'],
            "sessionId"    => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );
      // echo "<pre>";print_r(json_encode($arr_param));exit();
        $API_URL = $site_api_url."mosquito_pool_result_edit.json";
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
}else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iTMPRId = $_POST['iTMPRId'];
    
    $arr_param['iTMPRId']= $iTMPRId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."mosquito_pool_result_delete.json";
      //  echo "<pre>";print_r($arr_param);exit();
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



$trap_data = array();

if($_REQUEST['iTTId']){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();

    $join_fieds_arr[] = 's."vName"';
    $join_fieds_arr[] = 's."vAddress1"';
    $join_fieds_arr[] = 's."vStreet"';
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 'tt."vTrapName"';
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN trap_type_mas tt on tt."iTrapTypeId" = task_trap."iTrapTypeId"';
    $join_arr[] = 'LEFT JOIN site_mas s on s."iPremiseId" = task_trap."iPremiseId"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $where_arr[] = 'task_trap."iTTId" = '.$_REQUEST['iTTId'];
    $TaskTrapObj->join_field = $join_fieds_arr;
    $TaskTrapObj->join = $join_arr;
    $TaskTrapObj->where = $where_arr;
    $TaskTrapObj->param['limit'] = 1;
    $TaskTrapObj->setClause();
    $rs_taskTrap = $TaskTrapObj->recordset_list();
    $nt = count($rs_taskTrap);
    if($nt > 0){
    	$vSite = $rs_taskTrap[0]['vName']."- PremiseID#".$rs_taskTrap[0]['iPremiseId'];
        $vAddress =  $rs_taskTrap[0]['vAddress1'].' '.$rs_taskTrap[0]['vStreet'].' '.$rs_taskTrap[0]['vCity'].', '.$rs_taskTrap[0]['vState'].' '.$rs_taskTrap[0]['vCounty'];

        $vSiteName = $rs_taskTrap[0]['iPremiseId']." (".$rs_taskTrap[0]['vName']."; ".$rs_taskTrap[0]['vTypeName'].")";

        $trap_data = array(
                "iTTId" => $rs_taskTrap[0]['iTTId'],
                "iPremiseId" => $rs_taskTrap[0]['iPremiseId'],
                "vSiteName" => gen_strip_slash($vSite),
                "vSiteAddress" => $vAddress,
                "dTrapPlaced" => date_getDateTimeDDMMYYYY($rs_taskTrap[0]['dTrapPlaced']),
                "dTrapCollected" => date_getDateTimeDDMMYYYY($rs_taskTrap[0]['dTrapCollected']),
                "vTrapName" => $rs_taskTrap[0]['vTrapName']       
            );
    }

  
}
$smarty->assign("trap_data", $trap_data);


if($_REQUEST['iTTId']){

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $join_fieds_arr = array();
    if($_REQUEST['iTMPId'] != ""){
    	$where_arr[] = 'tmp."iTMPId" = '.$_REQUEST['iTMPId'];
    }

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
    $join_arr[] = 'LEFT JOIN site_mas s on s."iPremiseId" = task_trap."iPremiseId"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = ' JOIN task_mosquito_pool tmp on tmp."iTTId" = task_trap."iTTId"';

    $where_arr[] = 'task_trap."iTTId" = '.$_REQUEST['iTTId'];
    $where_arr[] = 'tmp."iTMPId" = '.$_REQUEST['iTMPId'];
    $TaskTrapObj->join_field = $join_fieds_arr;
    $TaskTrapObj->join = $join_arr;
    $TaskTrapObj->where = $where_arr;
    $TaskTrapObj->param['limit'] = 1;
    $TaskTrapObj->setClause();
    $rs_taskTrap = $TaskTrapObj->recordset_list();
   // echo "<pre>";print_r($rs_taskTrap);exit();
    $nt = count($rs_taskTrap);
    if($nt > 0){
    	$vSite = $rs_taskTrap[0]['vName']."- PremiseID#".$rs_taskTrap[0]['iPremiseId'];
        $vAddress =  $rs_taskTrap[0]['vAddress1'].' '.$rs_taskTrap[0]['vStreet'].' '.$rs_taskTrap[0]['vCity'].', '.$rs_taskTrap[0]['vState'].' '.$rs_taskTrap[0]['vCounty'];

        $vSiteName = $rs_taskTrap[0]['iPremiseId']." (".$rs_taskTrap[0]['vName']."; ".$rs_taskTrap[0]['vTypeName'].")";

        $poolbLabWorkComplete = ($rs_taskTrap[0]['poollabworkcomplete']=='t')?'1':'0';

        $trap_data = array(
                "iTTId" => $rs_taskTrap[0]['iTTId'],
                "iPremiseId" => $rs_taskTrap[0]['iPremiseId'],
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
    }

  
}
$smarty->assign("trap_pool_data", $trap_data);

//Agent Mosquito  array
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."agent_mosquito_dropdown.json";
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
//print_r( $res['result']);exit();
$smarty->assign("agent_mosquito_arr", json_encode($res['result']));

//Test method mosquito array
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."test_method_mosquito_dropdown.json";
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
//print_r( $res['result']);exit();
$smarty->assign("test_method_arr", json_encode($res['result']));

//result array
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."result_dropdown.json";
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
//print_r( $res['result']);exit();
$smarty->assign("result_arr", json_encode($res['result']));

$module_name = "Mosquito Pool Result";
$module_title = "Mosquito Pool Result";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);

$smarty->assign("iTTId", $iTTId);
$smarty->assign("iTMPId", $iTMPId);
$smarty->assign("access_group_var_edit", $access_group_var_edit);
$smarty->assign("access_group_var_delete", $access_group_var_delete);
$smarty->assign("access_group_var_add", $access_group_var_add);

?>