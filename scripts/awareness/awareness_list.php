<?php
//echo "<pre>";print_r($_POST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Awareness", 'List');
$access_group_var_delete = per_hasModuleAccess("Awareness", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Awareness", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Awareness", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Awareness", 'Edit', 'N');
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
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '0');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------

$SiteObj = new Site();

$iAId = $_POST['iAId'];
$iSiteId = $_POST['iSiteId'];

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
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    //echo "<pre>";print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."get_task_awareness_list.json";
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
    $rs_taskadult = $result_arr['result']['data'];
	$ni = count($rs_taskadult);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $action = '';

            if($access_group_var_edit == "1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit" onclick="addEditDataTaskawareness('.$rs_taskadult[$i]['iAId'].',\'edit\',0)"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_taskadult[$i]['iAId'].');"><i class="fa fa-trash"></i></a>';
            }

            $hidden_fields = '<input type="hidden" id="iAId_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['iAId'].'"><input type="hidden" id="vSiteName_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['vName'].'"><input type="hidden" id="iSiteId_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['iSiteId'].'"><input type="hidden" id="dDate_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['dDate'].'"><input type="hidden" id="dDate_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['dDate'].'"><input type="hidden" id="dStartDate_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['dStartDate'].'"><input type="hidden" id="dStartTime_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['dStartTime'].'"><input type="hidden" id="dEndDate_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['dEndDate'].'"><input type="hidden" id="dEndTime_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['dEndTime'].'"><input type="hidden" id="iEngagementId_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['iEngagementId'].'"><input type="hidden" id="tNotes_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['tNotes'].'"><input type="hidden" id="srdisplay_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['sr'].'"><input type="hidden" id="iSRId_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['iSRId'].'"><input type="hidden" id="iTechnicianId_'.$rs_taskadult[$i]['iAId'].'" value="'.$rs_taskadult[$i]['iTechnicianId'].'">';

            $entry[] = array(
                "iAId" => $rs_taskadult[$i]['iAId'],
                "vName" => $rs_taskadult[$i]['vName'].$hidden_fields,
                "vAddress" => trim($rs_taskadult[$i]['vAddress']),
                "sr" => trim($rs_taskadult[$i]['sr']),
                "dDate" => date_getDateTimeDDMMYYYY($rs_taskadult[$i]['dDate']),
                "dStartDate" => date_getTimeFromDate($rs_taskadult[$i]['dStartDate']),
                "dEndDate" => date_getTimeFromDate($rs_taskadult[$i]['dEndDate']),
                "vTypeName" => $rs_taskadult[$i]['vTypeName'],
                "tNotes" => trim($rs_taskadult[$i]['tNotes']),
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
    $iAId = $_POST['iAId'];
    
    $arr_param['iAId'] = $iAId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."task_awareness_delete.json";
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
else if($mode == "Update"){
    //echo "<pre>";print_r($_REQUEST);exit();
    $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        $dStartDate = $_POST['modal_dDate_awareness']." 00:00:00";
        if($_POST['dStartTime_awareness'] != ""){
            $dStartDate = $_POST['modal_dDate_awareness']." ".$_POST['dStartTime_awareness'];
        }

        $dEndDate = "";
        if($_POST['dEndTime_awareness'] != ""){
            $dEndDate = $_POST['modal_dDate_awareness']." ".$_POST['dEndTime_awareness'];
        }

        $arr_param = array(
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iAId"             => $_POST['modal_iAId'],
            "iSiteId"           => $_POST['serach_iSiteId_awareness'],
            "iSRId"             => $_POST['serach_iSRId_awareness'],
            "dDate"             => $_POST['modal_dDate_awareness'],
            "dStartDate"        => $dStartDate,
            "dEndDate"          => $dEndDate,
            "iEngagementId"       => $_POST['iEngagementId'],
            "tNotes"            => $_POST['tNotes_awareness'],
            "iLoginUserId"           => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "iTechnicianId"     => $_POST['technician_id']
        );
        //echo "<pre>";print_r(json_encode($arr_param));exit();

        $API_URL = $site_api_url."awareness_edit.json";
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
    //echo "<pre>";print_r($_POST);exit;
    if (isset($_POST) && count($_POST) > 0) {
        $dStartDate = $_POST['modal_dDate_awareness']." 00:00:00";
        if($_POST['dStartTime_awareness'] != ""){
            $dStartDate = $_POST['modal_dDate_awareness']." ".$_POST['dStartTime_awareness'];
        }

        $dEndDate = "";
        if($_POST['dEndTime_awareness'] != ""){
            $dEndDate = $_POST['modal_dDate_awareness']." ".$_POST['dEndTime_awareness'];
        }

        $arr_param = array(
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iPremiseId"        => $_POST['serach_iSiteId_awareness'],
            "iFiberInquiryId"   => $_POST['serach_iSRId_awareness'],
            "dDate"             => $_POST['modal_dDate_awareness'],
            "dStartDate"        => $dStartDate,
            "dEndDate"          => $dEndDate,
            "iEngagementId"     => $_POST['iEngagementId'],
            "tNotes"            => $_POST['tNotes_awareness'],
            "iLoginUserId"      => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "iTechnicianId"     => $_POST['technician_id']
        );

        $API_URL = $site_api_url."awareness_add.json";
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

        $iTLSId = curl_exec($ch);
        //echo "<pre>";print_r($iTLSId);exit();  
        curl_close($ch);  

        if($iTLSId){
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
}else if($mode == "search_site"){
    /*Search site api*/
    $arr_param = array();
    $vSiteName_awareness = trim($_REQUEST['vSiteName_awareness']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['siteName'] = $vSiteName_awareness;
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
}else if($mode == "search_fiber_inquiry"){
    /*Search site api*/
    $arr_param = array();
    $srId = trim($_REQUEST['vSR_awareness']);
    
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['srId'] = $srId;
    
    $API_URL = $site_api_url."search_fiber_inquiry.json";
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

$module_name = "Awareness List";
$module_title = "Awareness";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("iSiteId", $iSiteId);

$smarty->assign("access_group_var_add", $access_group_var_add);

$smarty->assign("dDate", date('Y-m-d'));
$smarty->assign("dStartTime", date("H:i", time()));
$smarty->assign("dEndTime", date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes")));
?>