<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Work Order", 'List');
$access_group_var_delete = per_hasModuleAccess("Work Order", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Work Order", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Work Order", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Work Order", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Work Order", 'CSV', 'N');
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
$iPremiseId = $_REQUEST['iPremiseId'];
if($mode == "List") {
    //print_r($_REQUEST);exit();
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "vNetwork"){
        $searchId = $_REQUEST['iSNetworkId'];
    }else if($vOptions == "vFiberZone"){
        $searchId = $_REQUEST['iSZoneId'];
    }else if($vOptions == "vWOType"){
        $searchId = $_REQUEST['iSWOTId'];
    }else if($vOptions == "vRequestor"){
        $searchId = $_REQUEST['iSRequestorId'];
    }else if($vOptions == "vAssignedTo"){
        $searchId = $_REQUEST['iSAssignedToId'];
    }else if($vOptions == "vStatus"){
        $searchId = $_REQUEST['iSWOSId'];
    }
    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }

    $arr_param['page_length']   = $page_length;
    $arr_param['start']         = $start;
    $arr_param['sEcho']         = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir']           = $dir;

    $arr_param['iFieldmapPremiseId']    = $iPremiseId;

    $arr_param['vSPremiseNameDD']       = trim($_REQUEST['vSPremiseNameDD']);
    $arr_param['vSPremiseName']         = trim($_REQUEST['vSPremiseName']);
    $arr_param['vSAddressFilterOpDD']   = trim($_REQUEST['vSAddressFilterOpDD']);
    $arr_param['vSAddress']             = trim($_REQUEST['vSAddress']);
    $arr_param['vSCityFilterOpDD']      = trim($_REQUEST['vSCityFilterOpDD']);
    $arr_param['vSCity']                = trim($_REQUEST['vSCity']);
    $arr_param['vSStateFilterOpDD']     = trim($_REQUEST['vSStateFilterOpDD']);
    $arr_param['vSState']               = trim($_REQUEST['vSState']);
    $arr_param['vSZipCode']             = trim($_REQUEST['vSZipCode']);
    $arr_param['iSServiceOrderId']      = trim($_REQUEST['iSServiceOrderId']);
    $arr_param['vSWOProjectDD']         = trim($_REQUEST['vSWOProjectDD']);
    $arr_param['vSWOProject']           = trim($_REQUEST['vSWOProject']);
    $arr_param['iWOTId']                = trim($_REQUEST['iWOTId']);
    $arr_param['iPremiseId']            = trim($_REQUEST['iPremiseId']);
    $arr_param['iServiceOrderId']       = trim($_REQUEST['iServiceOrderId']);

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."workorder_list.json";
    // echo $API_URL. " ".json_encode($arr_param);exit;
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

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_order = $result_arr['result']['data'];
    //echo "<pre>";print_r($rs_order);exit();
    $ni = count($rs_order);
    $entry =array();
    if ($ni > 0) {
        for ($i = 0; $i < $ni; $i++) {
            $action = '';
            if($access_group_var_edit == "1"){
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'service_order/workorder_add&mode=Update&iWOId=' . $rs_order[$i]['iWOId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if($access_group_var_delete == "1"){
                $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_order[$i]['iWOId'].');"><i class="fa fa-trash"></i></a>';
            }
            
            $premise_url = $site_url."premise/edit&mode=Update&iPremiseId=".$rs_order[$i]['iPremiseId'];
            $vPremise = "<a href='".$premise_url."' target='_blank' class='text-primary'>".$rs_order[$i]['iPremiseId']." (".$rs_order[$i]['vPremiseName']."; ".$rs_order[$i]['vPremiseType'].")</a>";

            $vServiceDetails = '';
            $so_url = $site_url."service_order/edit&mode=Update&iServiceOrderId=".$rs_order[$i]['iServiceOrderId'];
            if($rs_order[$i]['iServiceOrderId'] != ""){
                $vServiceDetails .= "<a href='".$so_url."' target='_blank' class='text-primary'>SO #".$rs_order[$i]['iServiceOrderId'].": ".$rs_order[$i]['vServiceOrder']."</a>";
            }

            $vStatus = '---';
            if($vStatus != ""){
                $vStatus = '<span class="btn btn-'.$status_color[$rs_order[$i]['vStatus']].'">'.$rs_order[$i]['vStatus'].'<span>';
            }
            

            $entry[] = array(
                "checkbox"              => '<input type="checkbox" class="list" value="'.$rs_order[$i]['iWOId'].'"/>',
                "iWOId"                 => $rs_order[$i]['iWOId'],
                "vPremise"              => $vPremise,
                "vServiceDetails"       => $vServiceDetails,
                "vRequestor"            => $rs_order[$i]['vRequestor'],
                "vWOProject"            => $rs_order[$i]['vWOProject'],
                "vType"                 => $rs_order[$i]['vType'],
                "vAssignedTo"           => $rs_order[$i]['vAssignedTo'],
                "vStatus"               => $vStatus,
                "actions"               => ($action!="")?$action:"---"
            );
        }
    }
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}else if($mode == "Update"){   
    //echo "<pre>";print_r($_POST);exit;
    $arr_param = array(
        "iWOId"                     => $_POST['iWOId'],
        "iPremiseId"                => $_POST['search_iPremiseId'],
        "iServiceOrderId"           => $_POST['search_iServiceOrderId'],
        "iRequestorId"              => $_POST['iRequestorId'],
        "vWOProject"                => trim($_POST['vWOProject']),
        "iWOTId"                    => $_POST['iWOTId'],
        "tDescription"              => trim($_POST['tDescription']),
        "iAssignedToId"             => $_POST['iAssignedToId'],
        "iWOSId"                    => $_POST['iWOSId'],
        "sessionId"                 => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."workorder_edit.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
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
    //echo "<pre>;";print_r($result_arr);exit;
    if(isset($result_arr['iWOId'])){
        $result['error'] = 0 ;
        $result['msg'] = MSG_UPDATE;
    }else{
        $result['error'] = 1 ;
        $result['msg'] = MSG_UPDATE_ERROR;
    }

    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}else if($mode == "Add") {
    //echo "<pre>";print_r($_POST);exit;
    $arr_param = array(
        "iPremiseId"                => $_POST['search_iPremiseId'],
        "iServiceOrderId"           => $_POST['search_iServiceOrderId'],
        "iRequestorId"              => $_POST['iRequestorId'],
        "vWOProject"                => trim($_POST['vWOProject']),
        "iWOTId"                    => $_POST['iWOTId'],
        "tDescription"              => trim($_POST['tDescription']),
        "iAssignedToId"             => $_POST['iAssignedToId'],
        "iWOSId"                    => $_POST['iWOSId'],
        "sessionId"                 => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."workorder_add.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
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
    //echo "<pre>;";print_r($result_arr);exit;
    if(isset($result_arr['iWOId'])){
        $result['msg'] = MSG_ADD;
        $result['error']= 0 ;
    }else{
        $result['msg'] = $result_arr['Message'];
        $result['error']= 1 ;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}else if($mode == "Delete") {
    $iWOId = $_REQUEST['iWOId'];
    $result = array();
    $arr_param = array();
    $arr_param['iWOId']   = $iWOId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."workorder_delete.json";
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

    $rs_tot = curl_exec($ch);
    curl_close($ch);

    if($rs_tot){
        $result['msg'] = MSG_DELETE;
        $result['error'] = 0 ;
    }else{
         $result['msg'] = MSG_DELETE_ERROR;
        $result['error'] = 1 ;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
} else if($mode == "change_status"){
    $result = array();
    $arr_param = array();
    $status = $_POST['status'];
    $iWOIds = $_POST['iWOIds'];
    
    $arr_param['status']      = $status; 
    $arr_param['iWOIds']      = $iWOIds; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."workorder_change_status.json";
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
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}
else if($mode== "Excel"){
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));

    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }

    $arr_param['page_length']   = $page_length;
    $arr_param['start']         = $start;
    $arr_param['sEcho']         = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir']           = $dir;

    $arr_param['iFieldmapPremiseId']    = $iPremiseId;

    $arr_param['vSPremiseNameDD']       = trim($_REQUEST['vSPremiseNameDD']);
    $arr_param['vSPremiseName']         = trim($_REQUEST['vSPremiseName']);
    $arr_param['vSAddressFilterOpDD']   = trim($_REQUEST['vSAddressFilterOpDD']);
    $arr_param['vSAddress']             = trim($_REQUEST['vSAddress']);
    $arr_param['vSCityFilterOpDD']      = trim($_REQUEST['vSCityFilterOpDD']);
    $arr_param['vSCity']                = trim($_REQUEST['vSCity']);
    $arr_param['vSStateFilterOpDD']     = trim($_REQUEST['vSStateFilterOpDD']);
    $arr_param['vSState']               = trim($_REQUEST['vSState']);
    $arr_param['vSZipCode']             = trim($_REQUEST['vSZipCode']);
    $arr_param['iSZoneId']              = trim($_REQUEST['iSZoneId']);
    $arr_param['iSServiceOrderId']      = trim($_REQUEST['iSServiceOrderId']);
    $arr_param['vSWOProjectDD']         = trim($_REQUEST['vSWOProjectDD']);
    $arr_param['vSWOProject']           = trim($_REQUEST['vSWOProject']);
    $arr_param['iSRequestorId']         = trim($_REQUEST['iSRequestorId']);
    $arr_param['iSAssignedToId']        = trim($_REQUEST['iSAssignedToId']);
    $arr_param['iSWOSId']               = trim($_REQUEST['iSWOSId']);

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."workorder_list.json";
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
    $response = curl_exec($ch);
    curl_close($ch);  
    $result_arr = json_decode($response, true);
    $rs_export = $result_arr['result']['data'];
    $cnt_export = count($rs_export);
    //  echo "<pre>";print_r($rs_export);exit();
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    // // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $file_name = "workorder".time().".xlsx";

    if($cnt_export >0) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Id')
            ->setCellValue('B1', 'Premise')
            ->setCellValue('C1', 'Service Order')
            ->setCellValue('D1', 'Requestor')
            ->setCellValue('E1', 'Work Order Project')
            ->setCellValue('F1', 'Work Order Type')
            ->setCellValue('G1', 'Assigned To')
            ->setCellValue('H1', 'Status');

        for($e=0; $e<$cnt_export; $e++) {

            $vPremise = $rs_export[$e]['iPremiseId']." (".$rs_export[$e]['vPremiseName']."; ".$rs_export[$e]['vPremiseType'].")";

            $vServiceDetails = '';
            if($rs_export[$e]['iServiceOrderId'] != ""){
                $vServiceDetails .= "SO #".$rs_export[$e]['iServiceOrderId'].": ".$rs_export[$e]['vServiceOrder'];
            }

            $vPremise = $rs_export[$e]['iPremiseId']." (".$rs_export[$e]['vPremiseName']."; ".$rs_export[$e]['vPremiseType'].")";

            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iWOId'])
            ->setCellValue('B'.($e+2), $vPremise)
            ->setCellValue('C'.($e+2), $vServiceDetails)
            ->setCellValue('D'.($e+2), $rs_export[$e]['vRequestor'])
            ->setCellValue('E'.($e+2), $rs_export[$e]['vWOProject'])
            ->setCellValue('F'.($e+2), $rs_export[$e]['vType'])
            ->setCellValue('G'.($e+2), $rs_export[$e]['vAssignedTo'])
            ->setCellValue('H'.($e+2), $rs_export[$e]['vStatus']);
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
        $objPHPExcel->getActiveSheet()->setTitle('WorkOrder');

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
}

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

## --------------------------------
# Zone Dropdown
$zone_arr_param = array();
$zone_arr_param = array(
    "iStatus"        => 1,
    "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$zone_API_URL = $site_api_url."zone_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $zone_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($zone_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_zone = curl_exec($ch);
curl_close($ch); 
$rs_zone1 = json_decode($response_zone, true); 
$rs_zone = $rs_zone1['result'];
$smarty->assign("rs_zone", $rs_zone);
## --------------------------------
## --------------------------------
# User Dropdown
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
## --------------------------------
## --------------------------------
# Network Dropdown
$network_arr_param = array();
$network_arr_param = array(
    "iStatus"        => 1,
    "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$network_API_URL = $site_api_url."network_dropdown.json";
//echo $network_API_URL." ".json_encode($network_arr_param);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $network_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($network_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_network = curl_exec($ch);
curl_close($ch); 
$rs_network = json_decode($response_network, true); 
$rs_ntwork = $rs_network['result'];
$smarty->assign("rs_ntwork", $rs_ntwork);
## --------------------------------
/*-------------------------- WorkOrder Type -------------------------- */
$wotype_param = array();
$wotype_param['iStatus'] = '1';
$wotype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$wotypeAPI_URL = $site_api_url."workorder_type_dropdown.json";
//echo $wotypeAPI_URL." ".json_encode($wotype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $wotypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($wotype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_wotype = $res['result'];
$smarty->assign("rs_wotype", $rs_wotype);
//echo "<pre>";print_r($rs_carrier);exit;
/*-------------------------- WorkOrder Type -------------------------- */
# Status Dropdown
$status_arr_param = array();
$status_arr_param = array(
    "iStatus"       => 1,
    "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$status_API_URL = $site_api_url."workorder_status_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $status_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($status_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_status = curl_exec($ch);
curl_close($ch); 
$rs_status1 = json_decode($response_status, true); 
$rs_status = $rs_status1['result'];
$smarty->assign("rs_status", $rs_status);
//echo "<pre>";print_r($rs_status);exit;
## --------------------------------
$module_name = "Work Order List";
$module_title = "Work Order";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);
$smarty->assign("iPremiseId", $iPremiseId);
?>