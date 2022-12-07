<?php

include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Mosquito Pool", 'List');
$access_group_var_delete = per_hasModuleAccess("Mosquito Pool", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Mosquito Pool", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Mosquito Pool", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Mosquito Pool", 'Edit', 'N');
# ----------- Access Rule Condition -----------

# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '7');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
if($mode == "List"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }
    
    if ($iPremiseId != "") {
        $arr_param['iPremiseId'] = $iPremiseId;
    }
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    //echo "<pre>";print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."task_mosquito_pool_list.json";
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
    //echo "<pre>";print_r($result_arr);exit();

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_data = $result_arr['result']['data'];
	$ni = count($rs_data);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $action = '';

            if($access_group_var_edit == "1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'lab_task/manage_mosquito_pool_result&mode=list&iTTId='.$rs_data[$i]['iTTId'].'&iTMPId='.$rs_data[$i]['iTMPId'].'"><i class="fa fa-edit"></i></a>';
            }

            $entry[] = array(
                "iTMPId" => "<span ".$class.">".$rs_data[$i]['iTMPId']."</span>",
                "vName" => "<span ".$class.">".$rs_data[$i]['vName']."</span>",
                "vAddress" => "<span ".$class.">".$rs_data[$i]['vAddress']."</span>",
                "dTrapPlaced" => "<span ".$class.">".date_getDateTimeDDMMYYYY($rs_data[$i]['dTrapPlaced'])."</span>",
                "dTrapCollected" => "<span ".$class.">".date_getDateTimeDDMMYYYY($rs_data[$i]['dTrapCollected'])."</span>",
                "vTrapName" => "<span ".$class.">".$rs_data[$i]['vTrapName']."</span>",
                "tNotes" => "<span ".$class.">".$rs_data[$i]['tNotes']."</span>",
                "vPool" =>  "<span ".$class.">".$rs_data[$i]['vPool']."</span>",
                "result" => "<span ".$class.">".$rs_data[$i]['result']."</span>",
                "actions" => ($action != "") ? $action : "---"       
            );
        }
    }

    # Return jSON data.
    # -----------------------------------
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
 
}else if($mode == "Add"){

	$arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        //check mosquito pool count by pool
        $arr_param =array(
            "iTMCId" => $_POST['iTMCId'],
            "vPool"   => $_POST['vPool'],
            "iCountMosqperpool" => $_POST['iCountMosqperpool'],
            'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );
        
        $API_URL = $site_api_url."task_mosquito_pool_checkCountByPool.json";

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
   
        $res =  $result_arr['result'];
        
        if(!empty($res) && $res['remaining_pool_count'] == 0){
            $result['msg'] = $res['message'];
            $result['mosquitocount_error']= 1 ;
        }else{
            $pool_agenttest_arr = array();
            if(isset($_POST['pool_agent_test_arr'])){
                $pool_agenttest_data = json_decode(stripslashes($_POST['pool_agent_test_arr']));
                $pool_agenttest_arr=  json_decode(json_encode($pool_agenttest_data), true);
            }
            $arr_param = array(
            	"iTTId"  =>$_POST['iTTId'],
                "iTMCId" => $_POST['iTMCId'],
                "vPool"   => $_POST['vPool'],
                "iCountMosqperpool"  => $_POST['iCountMosqperpool'],
                "iNumberinPool" =>$_POST['iNumberinPool'],
                "poolgridchk" => (isset($_POST['poolgridchk']))?$_POST['poolgridchk']:'0',
                "pool_agenttest_arr" => $pool_agenttest_arr,
                'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
            );
            //echo "<pre>";print_r(json_encode($arr_param));exit();

            $API_URL = $site_api_url."task_mosquito_pool_add.json";
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

            //$iTMPId = curl_exec($ch);
            $response = curl_exec($ch);
         
            curl_close($ch);  

            $result_arr = json_decode($response, true);
             
            $iTMPId =  $result_arr['result'];
     
            if($iTMPId){
                $result['msg'] = MSG_ADD;
                $result['error']= 0 ;
            }else{
                $result['msg'] = MSG_ADD_ERROR;
                $result['error']= 1 ;
            }
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
}else if($mode == "setLabWorkCount"){
    $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit(); 
       $bLabWorkComplete = ($_POST['bLabWorkComplete']!='')?$_POST['bLabWorkComplete']:'0';
        $arr_param = array(
            "iTMPId"           => $_POST['iTMPId'],
            "bLabWorkComplete"  => $bLabWorkComplete,
            'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );
        //echo "<pre>";print_r(json_encode($arr_param));exit();

        $API_URL = $site_api_url."task_mosquito_pool_setLabWorkCount.json";
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
        curl_close($ch);  
        $res = json_decode($rs,true);
        if($rs['result']){
            $result['msg'] =str_replace('%s', 'Pool Lab Work Completed' , MSG_GENERAL_UPDATE) ;
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
}
?>