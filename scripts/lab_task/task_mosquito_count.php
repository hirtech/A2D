<?php

include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Mosquito Count", 'List');
$access_group_var_delete = per_hasModuleAccess("Mosquito Count", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Mosquito Count", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Mosquito Count", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Mosquito Count", 'Edit', 'N');
$mosq_pool_access_group_var_add = per_hasModuleAccess("Mosquito Pool", 'Add', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "task_trap.inc.php");

# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : '10';
$page_index = isset($_REQUEST['pageIndex']) ? $_REQUEST['pageIndex'] : '1';
$display_order = (isset($_REQUEST["sortField"]) ? $_REQUEST["sortField"] : 'iTMCId');
$dir = (isset($_REQUEST["sortOrder"]) ? $_REQUEST["sortOrder"] : 'asc');
# ------------------------------------------------------------
$iTTId = $_REQUEST['iTTId'];

$TaskTrapObj = new TaskTrap();


if($mode == "List"){
	//echo "<pre>";print_r($_REQUEST);exit();
    $arr_param = array();

    $iMSpeciesId = $_REQUEST['Species'];
    $iMaleCount = $_REQUEST['Male'];
    $iFemaleCount = $_REQUEST['Female'];

    if ($iTTId != "") {
        $arr_param['iTTId'] = $iTTId;
    }

    if ($iMSpeciesId != "") {
        $arr_param['iMSpeciesId'] = $iMSpeciesId;
    }

    if ($iMaleCount != "") {
        $arr_param['iMaleCount'] = $iMaleCount;
    }

    if ($iFemaleCount != "") {
        $arr_param['iFemaleCount'] = $iFemaleCount;
    }

   
    $start = intval($page_length)*intval($page_index-1);
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    //echo "<pre>";print_r(json_encode($arr_param));exit;
    $API_URL = $site_api_url."task_mosquito_count_list.json";
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
    //$res = json_decode( $result_arr['result'],true);
    # Return jSON data.
    # -----------------------------------
    //echo json_encode($res);
    echo json_encode($result_arr['result']);
    hc_exit();
    # -----------------------------------
 
}
else if($mode == "Add"){
	//echo "<pre>";print_r($_REQUEST);exit();

	$arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit(); 
       $total = intval( $_POST['Male'])+intval($_POST['Female']);
        $arr_param = array(
        	"iTTId"  =>$_REQUEST['iTTId'],
            "iMSpeciesId" => $_POST['Species'],
            "iMaleCount"   => $_POST['Male'],
            "iFemaleCount"  => $_POST['Female'],
            "iTotalCount" =>$total,
        	"iUserId"  => $_SESSION["sess_iUserId".$admin_panel_session_suffix]
        );
            
        $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
       //echo "<pre>";print_r($arr_param);exit();

        $API_URL = $site_api_url."task_mosquito_count_add.json";
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
            // echo "<pre>";print_r($result_arr['result']['aaData']);exit();
         $iTMCId = json_decode( $result_arr['result'],true);
 
        if($iTMCId){
            $result['msg'] = MSG_ADD;
            $result['error']= 0 ;
            $result['iTMCId']= $iTMCId ;
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
        $total = intval( $_POST['Male'])+intval($_POST['Female']);
        $arr_param = array(
            "iTMCId" =>$_REQUEST['iTMCId'],
            "iTTId"  =>$_REQUEST['iTTId'],
            "iMSpeciesId" => $_POST['iMSpeciesId'],
            "iMaleCount"   => $_POST['Male'],
            "iFemaleCount"  => $_POST['Female'],
            "iTotalCount" =>$total,
            "iUserId"  => $_SESSION["sess_iUserId".$admin_panel_session_suffix]
        );
        $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

        if($_REQUEST['iTMCId'] > 0){
            //echo "<pre>";print_r(json_encode($arr_param));exit();
            $API_URL = $site_api_url."task_mosquito_count_edit.json";
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
        }else{
            $API_URL = $site_api_url."task_mosquito_count_add.json";
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
                // echo "<pre>";print_r($result_arr['result']['aaData']);exit();
            $iTMCId = json_decode( $result_arr['result'],true);
     
            if($iTMCId){
                $result['msg'] = MSG_ADD;
                $result['error']= 0 ;
                $result['iTMCId']= $iTMCId ;
            }else{
                $result['msg'] = MSG_ADD_ERROR;
                $result['error']= 1 ;
            }
        }
       
    }else {
        $result['msg'] = "Something went wrong";
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
    $iTMCId = $_POST['iTMCId'];
    
    $arr_param['iTMCId'] = $iTMCId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."task_mosquito_count_delete.json";
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

        $bLabWorkComplete = ($rs_taskTrap[0]['bLabWorkComplete']=='t')?'1':'0';

        $trap_data = array(
                "iTTId" => $rs_taskTrap[0]['iTTId'],
                "iPremiseId" => $rs_taskTrap[0]['iPremiseId'],
                "vSiteName" => gen_strip_slash($vSite),
                "vSiteAddress" => $vAddress,
                "dTrapPlaced" => date_getDateTimeDDMMYYYY($rs_taskTrap[0]['dTrapPlaced']),
                "dTrapCollected" => date_getDateTimeDDMMYYYY($rs_taskTrap[0]['dTrapCollected']),
                "vTrapName" => $rs_taskTrap[0]['vTrapName'],   
                "bLabWorkComplete" => $bLabWorkComplete,   
            );
    }
  
}

$smarty->assign("trap_data", $trap_data);

//Mosquito Species array
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
$res= json_decode($response, true);
$rs_species = $res['result']['data'];
$ni = count($rs_species);
$mosquito_species_arr = array();

if($ni >0){
     $mosquito_species_arr[] = array("iMSpeciesId" => "" , "tDescription" => "" );
    for($m =0;$m<$ni;$m++){
        $mosquito_species_arr[] = array("iMSpeciesId" => $rs_species[$m]['iMSpeciesId'] , "tDescription" => $rs_species[$m]['tDescription'] );
    }
}
$mosquito_species_arr =json_encode($mosquito_species_arr);
$smarty->assign("db_species", $mosquito_species_arr);



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
//echo "<pre>"; print_r( $res['result']);exit();
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


$module_name = "Mosquito Count List";
$module_title = "Mosquito Count List";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
//$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);

$smarty->assign("iTTId", $iTTId);
$smarty->assign("access_group_var_edit", $access_group_var_edit);
$smarty->assign("access_group_var_delete", $access_group_var_delete);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("mosq_pool_access_group_var_add", $mosq_pool_access_group_var_add);



?>