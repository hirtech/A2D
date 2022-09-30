<?php
//echo "<pre>";print_r($_REQUEST);exit();
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Task Larval Surveillance", 'List');
$access_group_var_delete = per_hasModuleAccess("Task Larval Surveillance", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Task Larval Surveillance", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Task Larval Surveillance", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Task Larval Surveillance", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("Task Larval Surveillance", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Task Larval Surveillance", 'CSV', 'N');
$access_group_var_Respond = per_hasModuleAccess("Task Larval Surveillance", 'Respond', 'N');
# ----------- Access Rule Condition -----------

include_once($site_path . "scripts/session_valid.php");

include_once($controller_path . "premise.inc.php");
include_once($controller_path . "user.inc.php");

$SiteObj = new Site();

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
$iSiteId = $_REQUEST['iSiteId'];

if($mode == "List"){
    $arr_param = array();

   switch ($display_order) {
      case "0":
         $sortname = "iTLSId";
         break;
      case "1":
         $sortname = "vName";
         break;
      case "4":
         $sortname = "dDate";
         break;
      default:
         $sortname = "iTLSId";
         break;
   }

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
    $arr_param['display_order'] = $sortname;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    //echo "<pre>";print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."get_task_larval_surveillance_list.json";
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
    //echo "<pre>";print_r(($result_arr['result']));exit();
    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_tasklarval = $result_arr['result']['data'];
	$ni = count($rs_tasklarval);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $action = '';

            if($access_group_var_edit == "1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit" onclick="addEditDataTaskLarval('.$rs_tasklarval[$i]['iTLSId'].',\'edit\',0)"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_tasklarval[$i]['iTLSId'].');"><i class="fa fa-trash"></i></a>';
            }

            $hidden_fields = '<input type="hidden" id="iTLSId_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['iTLSId'].'"><input type="hidden" id="vSiteName_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['vName'].'"><input type="hidden" id="iSiteId_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['iSiteId'].'"><input type="hidden" id="iDips_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['iDips'].'"><input type="hidden" id="dDate_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['dDate'].'"><input type="hidden" id="dDate_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['dDate'].'"><input type="hidden" id="dStartDate_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['dStartDate'].'"><input type="hidden" id="dStartTime_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['dStartTime'].'"><input type="hidden" id="dEndDate_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['dEndDate'].'"><input type="hidden" id="dEndTime_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['dEndTime'].'"><input type="hidden" id="iGenus_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['iGenus'].'"><input type="hidden" id="iCount_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['iCount'].'"><input type="hidden" id="bEggs_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bEggs'].'"><input type="hidden" id="bInstar1_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bInstar1'].'"><input type="hidden" id="bInstar2_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bInstar2'].'"><input type="hidden" id="bInstar3_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bInstar3'].'"><input type="hidden" id="bInstar4_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bInstar4'].'"><input type="hidden" id="iGenus2_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['iGenus2'].'"><input type="hidden" id="iCount2_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['iCount2'].'"><input type="hidden" id="bEggs2_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bEggs2'].'"><input type="hidden" id="bInstar12_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bInstar12'].'"><input type="hidden" id="bInstar22_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bInstar22'].'"><input type="hidden" id="bInstar32_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bInstar32'].'"><input type="hidden" id="bInstar42_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bInstar42'].'"><input type="hidden" id="bPupae_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bPupae'].'"><input type="hidden" id="bAdult_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bAdult'].'"><input type="hidden" id="bPupae2_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bPupae2'].'"><input type="hidden" id="bAdult2_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['bAdult2'].'"><input type="hidden" id="tNotes_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['tNotes'].'"><input type="hidden" id="srdisplay_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['sr'].'"><input type="hidden" id="iSRId_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['iSRId'].'"><input type="hidden" id="iTechnicianId_'.$rs_tasklarval[$i]['iTLSId'].'" value="'.$rs_tasklarval[$i]['iTechnicianId'].'">';

            $entry[] = array(
                "iTLSId" => $rs_tasklarval[$i]['iTLSId'],
                "vName" => $rs_tasklarval[$i]['vName'].$hidden_fields,
                "vAddress" => $rs_tasklarval[$i]['vAddress'],
                "sr" => $rs_tasklarval[$i]['sr'],
                "dDate" => date_getDateTimeDDMMYYYY($rs_tasklarval[$i]['dDate']),
                "dStartDate" => date_getTimeFromDate($rs_tasklarval[$i]['dStartDate']),
                "dEndDate" => date_getTimeFromDate($rs_tasklarval[$i]['dEndDate']),
                "Summary" => $rs_tasklarval[$i]['Summary'],
                "tNotes" => $rs_tasklarval[$i]['tNotes'],
                "actions" => $action
            );
        }
    }

    # Return jSON data.
    # -----------------------------------
    //echo $result_arr['result'];
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}else if($mode == "Add"){
    //echo "<pre>";print_r($_REQUEST);exit();
    $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        $dStartDate = $_POST['modal_dDate']." 00:00:00";
        if($_POST['dStartTime'] != ""){
            $dStartDate = $_POST['modal_dDate']." ".$_POST['dStartTime'];
        }

        $dEndDate = "";
        if($_POST['dEndTime'] != ""){
            $dEndDate = $_POST['modal_dDate']." ".$_POST['dEndTime'];
        }

        $arr_param = array(
            "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iSiteId"       => $_POST['serach_iSiteId_larval'],
            "iSRId"         => $_POST['iSRId'],
            "dDate"         => $_POST['modal_dDate'],
            "dStartDate"    => $dStartDate,
            "dEndDate"      => $dEndDate,
            "iDips"         => $_POST['iDips'],
            "iCount"        => $_POST['iCount'],
            "iCount2"       => $_POST['iCount2'],
            "rAvgLarvel"    => $_POST['rAvgLarvel'],
            "iGenus"        => $_POST['iGenus'],
            "bEggs"         => $_POST['bEggs'],
            "bInstar1"      => $_POST['bInstar1'],
            "bInstar2"      => $_POST['bInstar2'],
            "bInstar3"      => $_POST['bInstar3'],
            "bInstar4"      => $_POST['bInstar4'],
            "bPupae"        => $_POST['bPupae'],
            "bAdult"        => $_POST['bAdult'],
            "iGenus2"       => $_POST['iGenus2'],
            "bEggs2"        => $_POST['bEggs2'],
            "bInstar12"     => $_POST['bInstar12'],
            "bInstar22"     => $_POST['bInstar22'],
            "bInstar32"     => $_POST['bInstar32'],
            "bInstar42"     => $_POST['bInstar42'],
            "bPupae2"       => $_POST['bPupae2'],
            "bAdult2"       => $_POST['bAdult2'],
            "tNotes"        => $_POST['tNotes'],
            "iUserId"        => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "iTechnicianId" => $_POST['technician_id']
        );

        $API_URL = $site_api_url."task_larval_surveillance_add.json";
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

        $iTLSId = curl_exec($ch);
        //echo "<pre>";print_r($iTLSId);//exit();  
        curl_close($ch);  

        //$iTLSId = json_decode($response, true);
        //echo "<pre>";print_r($iTLSId);//exit();   
        //echo "<pre>";print_r(json_encode($result_arr['result']));exit();
        if($iTLSId){
            //echo "<pre>";print_r($iTLSId);
            $result['msg'] = MSG_ADD;
            $result['error']= 0 ;
        }else{
            //echo "<pre>";print_r(233);
            $result['msg'] = MSG_ADD_ERROR;
            $result['error']= 1 ;
        }
    }else {
        //echo "<pre>";print_r(2333333333333);
        $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;
    }
     //echo "<pre>";print_r($result);exit(); 

    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}else if($mode == "Update"){
    //echo "<pre>";print_r($_REQUEST);exit();
    $arr_param = array();
    
    if (isset($_POST) && count($_POST) > 0) {
        $dStartDate = $_POST['modal_dDate']." 00:00:00";
        if($_POST['dStartTime'] != ""){
            $dStartDate = $_POST['modal_dDate']." ".$_POST['dStartTime'];
        }

        $dEndDate = "";
        if($_POST['dEndTime'] != ""){
            $dEndDate = $_POST['modal_dDate']." ".$_POST['dEndTime'];
        }

        $arr_param = array(
            "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iTLSId"        => $_POST['modal_iTLSId'],
            "iSiteId"       => $_POST['serach_iSiteId_larval'],
            "iSRId"         => $_POST['iSRId'],
            "dDate"         => $_POST['modal_dDate'],
            "dStartDate"    => $dStartDate,
            "dEndDate"      => $dEndDate,
            "iDips"         => $_POST['iDips'],
            "iCount"        => $_POST['iCount'],
            "iCount2"       => $_POST['iCount2'],
            "rAvgLarvel"    => $_POST['rAvgLarvel'],
            "iGenus"        => $_POST['iGenus'],
            "bEggs"         => $_POST['bEggs'],
            "bInstar1"      => $_POST['bInstar1'],
            "bInstar2"      => $_POST['bInstar2'],
            "bInstar3"      => $_POST['bInstar3'],
            "bInstar4"      => $_POST['bInstar4'],
            "bPupae"        => $_POST['bPupae'],
            "bAdult"        => $_POST['bAdult'],
            "iGenus2"       => $_POST['iGenus2'],
            "bEggs2"        => $_POST['bEggs2'],
            "bInstar12"     => $_POST['bInstar12'],
            "bInstar22"     => $_POST['bInstar22'],
            "bInstar32"     => $_POST['bInstar32'],
            "bInstar42"     => $_POST['bInstar42'],
            "bPupae2"       => $_POST['bPupae2'],
            "bAdult2"       => $_POST['bAdult2'],
            "tNotes"        => $_POST['tNotes'],
            "iUserId"       => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "iTechnicianId" => $_POST['technician_id']
        );
        //echo "<pre>";print_r(json_encode($arr_param));exit();

        $API_URL = $site_api_url."task_larval_surveillance_edit.json";
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

        $response = json_decode($rs, true);

        //$iTLSId = json_decode($response, true);
        //echo "<pre>";print_r($response);//exit();   
        if($response['Code'] == 200){
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
    $iTLSId = $_REQUEST['iTLSId'];
    $arr_param['iTLSId'] = $iTLSId;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."task_larval_surveillance_delete.json";
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
    //echo "<pre>";print_r($rs_tot);exit();
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
}else if($mode == "search_site"){
    /*Search site api*/
    $arr_param = array();
    $vSiteName = trim($_REQUEST['vSiteName']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['siteName'] = $vSiteName;
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

$module_name = "Task Larval Surveillance List";
$module_title = "Task Larval Surveillance";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("iSiteId", $iSiteId);

$smarty->assign("access_group_var_add", $access_group_var_add);

$smarty->assign("dDate", date('Y-m-d'));
$smarty->assign("dStartTime", date("H:i", time()));
$smarty->assign("dEndTime", date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes")));
?>