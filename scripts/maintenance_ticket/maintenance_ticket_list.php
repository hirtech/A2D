<?php
//echo "<pre>";print_r($_POST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Maintenance Ticket", 'List');
$access_group_var_delete = per_hasModuleAccess("Maintenance Ticket", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Maintenance Ticket", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Maintenance Ticket", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Maintenance Ticket", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Maintenance Ticket", 'CSV', 'N');
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
    $arr_param['iSNetworkId']       = $_POST['iSNetworkId'];
    $arr_param['iSCarrierId']       = $_POST['iSCarrierId'];

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
                "checkbox"              => '<input type="checkbox" class="list" value="'.$rs_maintenance_ticket[$i]['iMaintenanceTicketId'].'"/>',
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
} else if($mode == "change_status"){
    $result = array();
    $arr_param = array();
    $status = $_POST['status'];
    $iMaintenanceTicketIds = $_POST['iMaintenanceTicketIds'];
    
    $arr_param['status']      = $status; 
    $arr_param['iMaintenanceTicketIds']      = $iMaintenanceTicketIds; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."maintenance_ticket_change_status.json";
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
} else if($mode== "Excel"){
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
    $arr_param['iSNetworkId']       = $_POST['iSNetworkId'];
    $arr_param['iSCarrierId']       = $_POST['iSCarrierId'];
   

    $arr_param['page_length']       = $page_length;
    $arr_param['start']             = $start;
    $arr_param['sEcho']             = $sEcho;
    $arr_param['display_order']     = $display_order;
    $arr_param['dir']               = $dir;

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

    $rs_export = $result_arr['result']['data'];
    $cnt_export = count($rs_export);
      
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
        // // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $file_name = "maintenance_ticket_".time().".xlsx";

        if($cnt_export >0) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                     ->setCellValue('B1', 'Assigned To')
                     ->setCellValue('C1', 'Service Order')
                     ->setCellValue('D1', 'Severity')
                     ->setCellValue('E1', 'Status')
                     ->setCellValue('F1', 'Completion Date')
                     ->setCellValue('G1', 'Description');

            for($e=0; $e<$cnt_export; $e++) {
                $iSeverity = '---';
                if($rs_export[$e]['iSeverity'] == 1){
                   $iSeverity = "Low"; 
                }else if($rs_export[$e]['iSeverity'] == 2){
                   $iSeverity = "Medium"; 
                }else if($rs_export[$e]['iSeverity'] == 3){
                   $iSeverity = "High"; 
                }else if($rs_export[$e]['iSeverity'] == 4){
                   $iSeverity = "Critical"; 
                }

                $iStatus = '---';
                if($rs_export[$e]['iStatus'] == 1){
                   $iStatus = "Not Started"; 
                }else if($rs_export[$e]['iStatus'] == 2){
                   $iStatus = "In Progress"; 
                }else if($rs_export[$e]['iStatus'] == 3){
                   $iStatus = "Completed"; 
                }

                $vServiceDetails = '';
                if($rs_export[$e]['iServiceOrderId'] != ""){
                    $vServiceDetails .= "SO #".$rs_export[$e]['iServiceOrderId'].": ".$rs_export[$e]['vServiceOrder'];
                }
                $objPHPExcel->getActiveSheet()
                        ->setCellValue('A'.($e+2), $rs_export[$e]['iMaintenanceTicketId'])
                        ->setCellValue('B'.($e+2), $rs_export[$e]['vAssignedTo'])
                        ->setCellValue('C'.($e+2), $vServiceDetails)
                        ->setCellValue('D'.($e+2), $iSeverity)
                        ->setCellValue('E'.($e+2), $iStatus)
                        ->setCellValue('F'.($e+2), date_getDateTimeDDMMYYYY($rs_export[$e]['dCompletionDate']))
                        ->setCellValue('G'.($e+2), nl2br($rs_export[$e]['tDescription']));

            }
                            
            /* Set Auto width of each comlumn */
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            
            /* Set Font to Bold for each comlumn */
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            

            /* Set Alignment of Selected Columns */
            $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('MaintenanceTicket');

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
$response_so = curl_exec($ch);
curl_close($ch); 
$rs_so1 = json_decode($response_so, true); 
$rs_so = $rs_so1['result'];
$smarty->assign("rs_so", $rs_so);
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
## --------------------------------
//Carrier (Company) Dropdown
$carrier_param = array();
$carrier_param['iStatus'] = '1';
$carrier_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$carrierAPI_URL = $site_api_url."company_dropdown.json";
//echo $carrierAPI_URL." ".json_encode($carrier_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $carrierAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($carrier_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_carrier = $res['result'];
$smarty->assign("rs_carrier", $rs_carrier);
## --------------------------------

$module_name = "Maintenance Ticket List";
$module_title = "Maintenance Ticket";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);
?>