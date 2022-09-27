<?php
//echo "<pre>";print_r($_REQUEST);exit;

include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Task Trap", 'List');
$access_group_var_delete = per_hasModuleAccess("Task Trap", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Task Trap", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Task Trap", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Task Trap", 'Edit', 'N');
# ----------- Access Rule Condition -----------

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

$iTTId = $_REQUEST['iTTId'];
$iSiteId = $_REQUEST['iSiteId'];


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

    if ($iTTId != "") {
        $arr_param['iTTId'] = $iTTId;
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
    $API_URL = $site_api_url."get_task_trap_list.json";
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
    $rs_taskTrap = $result_arr['result']['data'];

    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $action = '';

            if($access_group_var_edit == "1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit" onclick="addEditDataTaskTrap('.$rs_taskTrap[$i]['iTTId'].',\'edit\',0)"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_taskTrap[$i]['iTTId'].');"><i class="fa fa-trash"></i></a>';
            }

            $hidden_fields = '<input type="hidden" id="iTTId_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['iTTId'].'"><input type="hidden" id="vSiteName_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['vName'].'"><input type="hidden" id="iSiteId_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['iSiteId'].'"><input type="hidden" id="iSRId_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['iSRId'].'"><input type="hidden" id="dTrapPlaced_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['dTrapPlaced'].'"><input type="hidden" id="dTrapCollected_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['dTrapCollected'].'"><input type="hidden" id="iTrapTypeId_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['iTrapTypeId'].'"><input type="hidden" id="bMalfunction_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['bMalfunction'].'"><input type="hidden" id="tNotes_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['tNotes'].'"><input type="hidden" id="srdisplay_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['sr'].'"><input type="hidden" id="iSRId_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['iSRId'].'"><input type="hidden" id="iTechnicianId_'.$rs_taskTrap[$i]['iTTId'].'" value="'.$rs_taskTrap[$i]['iTechnicianId'].'">';

            $entry[] = array(
                "iTTId" => $rs_taskTrap[$i]['iTTId'],
                "vName" => $rs_taskTrap[$i]['vName'].$hidden_fields,
                "vAddress" => trim($rs_taskTrap[$i]['vAddress']),
                "sr" => trim($rs_taskTrap[$i]['sr']),
                "dTrapPlaced" => date_getDateTimeDDMMYYYY($rs_taskTrap[$i]['dTrapPlaced']),
                "dTrapCollected" => date_getDateTimeDDMMYYYY($rs_taskTrap[$i]['dTrapCollected']),
                "vTrapName" => $rs_taskTrap[$i]['vTrapName'],
                "tNotes" => trim($rs_taskTrap[$i]['tNotes']),
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
 
}
else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iTTId = $_POST['iTTId'];

    $arr_param['iTTId'] = $iTTId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."task_trap_delete.json";
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
else if($mode == "Update"){
    //echo "<pre>";print_r($_REQUEST);exit();
    $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {

        $arr_param = array(
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iTTId"             => $_POST['modal_iTTId'],
            "iSiteId"           => $_POST['serach_iSiteId_tasktrap'],
            "iSRId"             => $_POST['serach_iSRId_tasktrap'],
            "dTrapPlaced"       => $_POST['dTrapPlaced_tasktrap'],
            "dTrapCollected"    => $_POST['dTrapCollected_tasktrap'],
            "iTrapTypeId"       => $_POST['iTrapTypeId'],
            "bMalfunction"      => $_POST['bMalfunction'],
            "tNotes"            => $_POST['tNotes_tasktrap'],
            "iUserId"           => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "iTechnicianId"     => $_POST['technician_id']
        );
        //echo "<pre>";print_r(json_encode($arr_param));exit();

        $API_URL = $site_api_url."task_trap_edit.json";
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
}
else if($mode == "Add"){
    $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit(); 
       
        $arr_param = array(
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iSiteId"           => $_POST['serach_iSiteId_tasktrap'],
            "iSRId"             => $_POST['serach_iSRId_tasktrap'],
            "dTrapPlaced"       => $_POST['dTrapPlaced_tasktrap'],
            "dTrapCollected"    => $_POST['dTrapCollected_tasktrap'],
            "iTrapTypeId"       => $_POST['iTrapTypeId'],
            "bMalfunction"      => $_POST['bMalfunction'],
            "tNotes"            => $_POST['tNotes_tasktrap'],
            "iUserId"           => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "iTechnicianId"     => $_POST['technician_id']
        );

        $API_URL = $site_api_url."task_trap_add.json";
        //echo "<pre>";print_r(json_encode($arr_param));
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

        $iTTId = curl_exec($ch);
        //echo "<pre>";print_r($iTTId);exit();  
        curl_close($ch);  

        if($iTTId){
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
}else if($mode == "search_site"){
    /*Search site api*/
    $arr_param = array();
    $vSiteName_tasktrap = trim($_REQUEST['vSiteName_tasktrap']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['siteName'] = $vSiteName_tasktrap;
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
    $result_arr = json_decode($response, true);
    
    # -----------------------------------
    # Return data.
    # -----------------------------------
    echo  json_encode($result_arr['result']['data']);
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
}else if($mode == "setLabWorkCount"){
    $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit(); 
       $bLabWorkComplete = ($_POST['bLabWorkComplete']!='')?$_POST['bLabWorkComplete']:'0';
        $arr_param = array(
            "iTTId"           => $_POST['iTTId'],
            "bLabWorkComplete"  => $bLabWorkComplete,
            'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );
        //echo "<pre>";print_r(json_encode($arr_param));exit();

        $API_URL = $site_api_url."task_trap_setLabWorkCount.json";
        /*echo $API_URL."<pre>";print_r(json_encode($arr_param));exit();*/
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
            $result['msg'] =str_replace('%s', 'Lab Work Completed' , MSG_GENERAL_UPDATE) ;
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

//Trap Type 
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
$res = json_decode($response, true);
$smarty->assign("rs_trap_type", $res['result']);

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

$module_name = "Task Trap List";
$module_title = "Task Trap";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("iSiteId", $iSiteId);
$smarty->assign("iTTId", $iTTId);

$smarty->assign("access_group_var_add", $access_group_var_add);

$smarty->assign("dDate", date('Y-m-d'));
?>