<?php
//echo "<pre>";print_r($_POST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Maintenance Ticket", 'List');
$access_group_var_delete = per_hasModuleAccess("Maintenance Ticket", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Maintenance Ticket", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Maintenance Ticket", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Maintenance Ticket", 'Edit', 'N');
# ----------- Access Rule Condition -----------
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

$iMaintenanceTicketId = $_POST['iMaintenanceTicketId'];

if($mode == "List"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }
    
    $arr_param['iSAssignedToId']    = $_POST['iSAssignedToId'];
    $arr_param['iSServiceOrderId']  = $_POST['iSServiceOrderId'];
    $arr_param['iSSeverity']        = $_POST['iSSeverity'];
    $arr_param['iSStatus']          = $_POST['iSStatus'];
    $arr_param['dSCompletionDate']  = trim($_POST['dSCompletionDate']);
    $arr_param['tSDescriptionDD']   = $_POST['tSDescriptionDD'];
    $arr_param['tSDescription']     = trim($_POST['tSDescription']);
    $arr_param['iSPremiseId']       = trim($_POST['iSPremiseId']);
    $arr_param['vSPremiseNameDD']   = $_POST['vSPremiseNameDD'];
    $arr_param['vSPremiseName']     = trim($_POST['vSPremiseName']);
    $arr_param['vSAddressDD']       = $_POST['vSAddressDD'];
    $arr_param['vSAddress']         = trim($_POST['vSAddress']);
   

    $arr_param['page_length']       = $page_length;
    $arr_param['start']             = $start;
    $arr_param['sEcho']             = $sEcho;
    $arr_param['display_order']     = $display_order;
    $arr_param['dir']               = $dir;

    $arr_param['access_group_var_edit']     = $access_group_var_edit;
    $arr_param['access_group_var_delete']   = $access_group_var_delete;

    $arr_param['sessionId']     = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    $API_URL = $site_api_url."maintenance_ticket_list.json";
    //echo $API_URL. " ".json_encode($arr_param);exit;
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
    $result_arr = json_decode($response, true);

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_maintenance_ticket = $result_arr['result']['data'];
    //echo "<pre>";print_r($result_arr);exit;
	$ni = count($rs_maintenance_ticket);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if($access_group_var_edit == "1"){
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'maintenance_ticket/maintenance_ticket_edit&mode=Update&iMaintenanceTicketId=' . $rs_maintenance_ticket[$i]['iMaintenanceTicketId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_maintenance_ticket[$i]['iMaintenanceTicketId'].');"><i class="fa fa-trash"></i></a>';
            }

            $iSeverity = '---';
            if($rs_maintenance_ticket[$i]['iSeverity'] == 1){
               $iSeverity = "Low"; 
            }else if($rs_maintenance_ticket[$i]['iSeverity'] == 2){
               $iSeverity = "Medium"; 
            }else if($rs_maintenance_ticket[$i]['iSeverity'] == 3){
               $iSeverity = "High"; 
            }else if($rs_maintenance_ticket[$i]['iSeverity'] == 4){
               $iSeverity = "Critical"; 
            }

            $iStatus = '---';
            if($rs_maintenance_ticket[$i]['iStatus'] == 1){
               $iStatus = "Not Started"; 
            }else if($rs_maintenance_ticket[$i]['iStatus'] == 2){
               $iStatus = "In Progress"; 
            }else if($rs_maintenance_ticket[$i]['iStatus'] == 3){
               $iStatus = "Completed"; 
            }

            $vServiceDetails = '';
            if($rs_maintenance_ticket[$i]['iServiceOrderId'] != ""){
                $vSOURL = $site_url."service_order/edit&mode=Update&iServiceOrderId=".$rs_maintenance_ticket[$i]['iServiceOrderId'];
                $vServiceDetails .= "<a href='".$vSOURL."' target='_blank' class='text-primary'>SO #".$rs_maintenance_ticket[$i]['iServiceOrderId'].": ".$rs_maintenance_ticket[$i]['vServiceOrder'].'</a>';
            }

            $entry[] = array(
                "iMaintenanceTicketId"  => $rs_maintenance_ticket[$i]['iMaintenanceTicketId'],
                "vAssignedTo"           => $rs_maintenance_ticket[$i]['vAssignedTo'],
                "vServiceOrder"         => $vServiceDetails,
                "iSeverity"             => $iSeverity,
                "iStatus"               => '<span class="btn btn-'.$status_color[$iStatus].'">'.$iStatus.'<span>',
                "dCompletionDate"       => date_getDateTimeDDMMYYYY($rs_maintenance_ticket[$i]['dCompletionDate']),
                "tDescription"          => nl2br($rs_maintenance_ticket[$i]['tDescription']),
                "actions"               => ($action != "") ? $action : "---"
            );
        }
    }
    # Return jSON data.
    # -----------------------------------
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
} else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iMaintenanceTicketId = $_POST['iMaintenanceTicketId'];
    
    $arr_param['iMaintenanceTicketId']      = $iMaintenanceTicketId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."maintenance_ticket_delete.json";
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
} else if($mode == "Update"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        $arr_param = array(
            "iMaintenanceTicketId"  => $_POST['iMaintenanceTicketId'],
            "iAssignedToId"         => $_POST['iAssignedToId'],
            "iServiceOrderId"       => $_POST['search_iServiceOrderId'],
            "iSeverity"             => $_POST['iSeverity'],
            "iStatus"               => $_POST['iStatus'],
            "dCompletionDate"       => $_POST['dCompletionDate'],
            "tDescription"          => $_POST['tDescription'],
            "premise_length"        => $_POST['premise_length'],
            "iPremiseId"            => $_POST['iPremiseId'],
            "dMaintenanceStartDate" => $_POST['dMaintenanceStartDate'],
            "dResolvedDate"         => $_POST['dResolvedDate'],
            "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );
        $API_URL = $site_api_url."maintenance_ticket_edit.json";
        //echo $API_URL. " ".json_encode($arr_param);exit;
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
        $res = json_decode($rs, true);
        curl_close($ch);  

        if($res['error'] == 0){
            $result['msg'] = $res['Message'];
            $result['error']= $res['error'] ;
        }else{
            $result['msg'] = $res['Message'];
            $result['error']= $res['error'];
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
} else if($mode == "Add"){
    $arr_param = array();

    if (isset($_POST) && count($_POST) > 0) {
        $arr_param = array(
            "iAssignedToId"         => $_POST['iAssignedToId'],
            "iServiceOrderId"       => $_POST['search_iServiceOrderId'],
            "iSeverity"             => $_POST['iSeverity'],
            "iStatus"               => $_POST['iStatus'],
            "dCompletionDate"       => $_POST['dCompletionDate'],
            "tDescription"          => $_POST['tDescription'],
            "premise_length"        => $_POST['premise_length'],
            "iPremiseId"            => $_POST['iPremiseId'],
            "dMaintenanceStartDate" => $_POST['dMaintenanceStartDate'],
            "dResolvedDate"         => $_POST['dResolvedDate'],
            "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );

        $API_URL = $site_api_url."maintenance_ticket_add.json";
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

        $rs = curl_exec($ch); 
        $res = json_decode($rs, true);
        curl_close($ch);  

        if($res['error'] == 0){
            $result['msg'] = $res['Message'];
            $result['error']= $res['error'] ;
        }else{
            $result['msg'] = $res['Message'];
            $result['error']= $res['error'];
        }
    }else {
        $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;
    }
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}

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
$smarty->assign("rs_user", $res['result']);
//echo "<pre>";print_r($res['result']);exit;
/*-------------------------- User -------------------------- */

## --------------------------------
# Service Order Dropdown
$sorder_arr_param = array();
$sorder_arr_param = array(
    "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$sorder_API_URL = $site_api_url."service_order_dropdown.json";
//echo $sorder_API_URL." ".json_encode($sorder_arr_param);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $sorder_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sorder_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_network = curl_exec($ch);
curl_close($ch); 
$rs_so1 = json_decode($response_network, true); 
$rs_so = $rs_so1['result'];
$smarty->assign("rs_so", $rs_so);
## --------------------------------

$module_name = "Maintenance Ticket List";
$module_title = "Maintenance Ticket";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
?>