<?php
//echo "<pre>";print_r($_POST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Task Treatment", 'List');
$access_group_var_delete = per_hasModuleAccess("Task Treatment", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Task Treatment", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Task Treatment", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Task Treatment", 'Edit', 'N');
# ----------- Access Rule Condition -----------

$page = $_REQUEST['page'];

include_once($controller_path . "premise.inc.php");
include_once($controller_path . "user.inc.php");

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

$SiteObj = new Site();

$iSiteId = $_REQUEST['iSiteId'];
$iTreatmentId = $_REQUEST['iTreatmentId'];

if($mode == "List"){
	$arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }
    
    if ($iSiteId != "") {
        $arr_param['iSiteId'] = $iSiteId;
    } 

    if ($iTreatmentId != "") {
        $arr_param['iTreatmentId'] = $iTreatmentId;
    }

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    //echo "<pre>";print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."get_task_treatment_list.json";
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

    $ni = $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_data = $result_arr['result']['data'];

    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $action = '';

            if($access_group_var_edit == "1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit" onclick="addEditDataTaskTreatment('.$rs_data[$i]['iTreatmentId'].',\'edit\',0)"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_data[$i]['iTreatmentId'].');"><i class="fa fa-trash"></i></a>';
            }

            $hidden_fields = '<input type="hidden" id="tt_iTreatmentId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iTreatmentId'].'"><input type="hidden" id="tt_vSiteName_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vSiteName'].'"><input type="hidden" id="tt_iSiteId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iSiteId'].'"><input type="hidden" id="tt_dDate_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['dDate'].'"><input type="hidden" id="tt_dStartDate_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['dStartDate'].'"><input type="hidden" id="tt_dStartTime_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['dStartTime'].'"><input type="hidden" id="tt_dEndDate_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['dEndDate'].'"><input type="hidden" id="tt_dEndTime_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['dEndTime'].'"><input type="hidden" id="tt_vType_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vType'].'"><input type="hidden" id="tt_iTPId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iTPId'].'"><input type="hidden" id="tt_iTPName_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vTPName'].'"><input type="hidden" id="tt_vAppRate_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['ApplicationRate'].'"><input type="hidden" id="tt_vArea_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vArea'].'"><input type="hidden" id="tt_vAreaTreated_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vAreaTreated'].'"><input type="hidden" id="tt_vAmountApplied_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['vAmountApplied'].'"><input type="hidden" id="tt_iUId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iUId'].'"><input type="hidden" id="tt_iUParentId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iUnitParentId'].'"><input type="hidden" id="tt_srdisplay_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['sr'].'"><input type="hidden" id="tt_iSRId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iSRId'].'"><input type="hidden" id="iTechnicianId_'.$rs_data[$i]['iTreatmentId'].'" value="'.$rs_data[$i]['iTechnicianId'].'">';

            $entry[] = array(
                "iTreatmentId" => $rs_data[$i]['iTreatmentId'],
                "vSiteName" => $rs_data[$i]['vSiteName'].$hidden_fields,
                "sr" => trim($rs_data[$i]['sr']),
                "vType" =>$rs_data[$i]['vType'],
                "dDate" => date_getDateTimeDDMMYYYY($rs_data[$i]['dDate']),
                "dStartDate" => date_getTimeFromDate($rs_data[$i]['dStartDate']),
                "dEndDate" => date_getTimeFromDate($rs_data[$i]['dEndDate']),
                "vTProduct" => gen_strip_slash($rs_data[$i]['vTPName']),
                "vAmount" => $rs_data[$i]['vAmountApplied']." ".$rs_data[$i]['vUnit'],
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
}else if($mode == "search_site"){
    /*Search site api*/
    $arr_param = array();
    $vSiteName_other = trim($_REQUEST['vSiteName']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['siteName'] = $vSiteName_other;
    $API_URL = $site_api_url."premise_site.json";
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
    //echo "<pre>";print_r($response);exit;
    $result_arr = json_decode($response, true);
    
    # -----------------------------------
    # Return data.
    # -----------------------------------
    echo  json_encode($result_arr['result']['data']);
    hc_exit();
    # -----------------------------------
}else if($mode == "search_treatment_product"){
    /*Search treatment product api*/
    $arr_param = array();
    $trProductName = trim($_REQUEST['trProductName']);
    
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['trProduct'] = $trProductName;
    
    $API_URL = $site_api_url."search_treatment_product.json";
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
    echo json_encode($result_arr['result']);
    hc_exit();
    # -----------------------------------
}else if($mode == "getUnitDataById"){
	/*get Unit dropdown by ParentId*/ 
    $arr_param = array();
    $iUParentId = trim($_REQUEST['iUParentId']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['iParentId'] = $iUParentId;
    
    $API_URL = $site_api_url."get_sync_unit_data.json";

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
    echo json_encode($result_arr['result']['data']);
    hc_exit();
    # -----------------------------------
}else if($mode == "Add"){
    //echo "<pre>";print_r($_POST);exit();
     $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        $dStartDate = $_POST['dDate_treatment']." 00:00:00";
        if($_POST['dStartTime_treatment'] != ""){
            $dStartDate = $_POST['dDate_treatment']." ".$_POST['dStartTime_treatment'];
        }

        $dEndDate = "";
        if($_POST['dEndTime_treatment'] != ""){
            $dEndDate = $_POST['dDate_treatment']." ".$_POST['dEndTime_treatment'];
        }

        $arr_param = array(
            "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iSiteId"        => $_POST['serach_iSiteId_treatment'],
            "iSRId"          => $_POST['serach_iSRId_treatment'],
            "dDate"          => $_POST['dDate_treatment'],
            "vType"          => $_POST['vType_treatment'],
            "dStartDate"     => $dStartDate,
            "dEndDate"       => $dEndDate,
            "iTPId"          => $_POST['serach_iTPId_treatment'],
            "vArea"          => $_POST['vArea_treatment'],
            "vAreaTreated"   => $_POST['vAreaTreated_treatment'],
            "vAmountApplied" => $_POST['vAmountApplied_treatment'],
            "iUId"           => $_POST['iUId_treatment'],
            "iUserId"        => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "iTechnicianId" => $_POST['technician_id']
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
}else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iTreatmentId = $_POST['iTreatmentId'];
    
    $arr_param["sessionId"] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['iTreatmentId']= $iTreatmentId; 
    $API_URL = $site_api_url."task_treatment_delete.json";
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
}else if($mode == "Update"){
    //echo "<pre>";print_r($_REQUEST);exit();
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        $dStartDate = $_POST['dDate_treatment']." 00:00:00";
        if($_POST['dStartTime_treatment'] != ""){
            $dStartDate = $_POST['dDate_treatment']." ".$_POST['dStartTime_treatment'];
        }

        $dEndDate = "";
        if($_POST['dEndTime_treatment'] != ""){
            $dEndDate = $_POST['dDate_treatment']." ".$_POST['dEndTime_treatment'];
        }

        $arr_param = array(
            "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iTreatmentId"   => $_POST['modal_iTreatmentId'],
            "iSiteId"        => $_POST['serach_iSiteId_treatment'],
            "iSRId"          => $_POST['serach_iSRId_treatment'],
            "dDate"          => $_POST['dDate_treatment'],
            "vType"          => $_POST['vType_treatment'],
            "dStartDate"     => $dStartDate,
            "dEndDate"       => $dEndDate,
            "iTPId"          => $_POST['serach_iTPId_treatment'],
            "vArea"          => $_POST['vArea_treatment'],
            "vAreaTreated"   => $_POST['vAreaTreated_treatment'],
            "vAmountApplied" => $_POST['vAmountApplied_treatment'],
            "iUId"           => $_POST['iUId_treatment'],
            "iUserId"        => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "iTechnicianId" => $_POST['technician_id']
        );
       


        $API_URL = $site_api_url."task_treatment_edit.json";
    
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
}else if($mode == "search_sr"){
    /*Search site api*/
    $arr_param = array();
    $srId = trim($_REQUEST['vSR_search']);
    
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['srId'] = $srId;
    
    $API_URL = $site_api_url."search_sr.json";
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

if($_REQUEST['iSiteId']){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $SiteObj->clear_variable();
    $where_arr[] = 's."iSiteId" = '.$_REQUEST['iSiteId'];

    $SiteObj->join_field = $join_fieds_arr;
    $SiteObj->join = $join_arr;
    $SiteObj->where = $where_arr;
    $SiteObj->param['limit'] = 1;
    $SiteObj->setClause();
    $rs_site = $SiteObj->recordset_list();
    $smarty->assign("rs_site", $rs_site);
}

//Unit array
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."unit_multi_dropdown.json";
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
$unit_arr =$res['result'];
$smarty->assign("unit_arr", $unit_arr);

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


$module_name = "Task Treatment List";
$module_title = "Task Treatment";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("iSiteId", $iSiteId);
$smarty->assign("iTreatmentId", $iTreatmentId);

$smarty->assign("access_group_var_add", $access_group_var_add);

$smarty->assign("dDate", date('Y-m-d'));
$smarty->assign("dStartTime", date("H:i", time()));
$smarty->assign("dEndTime", date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes")));
?>