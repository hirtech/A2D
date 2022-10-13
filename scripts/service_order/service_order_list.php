<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Service Order", 'List');
$access_group_var_delete = per_hasModuleAccess("Service Order", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Service Order", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Service Order", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Service Order", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("Service Order", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Service Order", 'CSV', 'N');
$access_group_var_Respond = per_hasModuleAccess("Service Order", 'Respond', 'N');
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
if($mode == "List") {
    //print_r($_REQUEST);exit();
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

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."service_order_list.json";
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

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_order = $result_arr['result']['data'];
    //echo "<pre>";print_r($rs_order);
    $ni = count($rs_order);
    $entry =array();
    if ($ni > 0) {
        for ($i = 0; $i < $ni; $i++) {
            $action = '';
            if($access_group_var_edit == "1"){
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'service_order/edit&mode=Update&iServiceOrderId=' . $rs_order[$i]['iServiceOrderId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if($access_group_var_delete == "1"){
                $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_order[$i]['iServiceOrderId'].');"><i class="fa fa-trash"></i></a>';
            }

            $vSalesRepName = '';
            if($rs_order[$i]['vSalesRepName'] != ""){
                $vSalesRepName .= "<strong>Name:</strong> ".$rs_order[$i]['vSalesRepName']."<br/>";
            }
            if($rs_order[$i]['vSalesRepEmail'] != ""){
                $vSalesRepName .= "<strong>Email:</strong> ".$rs_order[$i]['vSalesRepEmail']."<br/>";
            }

            $vServiceDetails = '';
            if($rs_order[$i]['vServiceType1'] != ""){
                $vServiceDetails .= "<strong>Service 1:</strong> ".$rs_order[$i]['vServiceType1']."<br/>";
            }
            if($rs_order[$i]['vServiceType2'] != ""){
                $vServiceDetails .= "<strong>Service 2:</strong> ".$rs_order[$i]['vServiceType2']."<br/>";
            }
            if($rs_order[$i]['vServiceType3'] != ""){
                $vServiceDetails .= "<strong>Service 3:</strong> ".$rs_order[$i]['vServiceType3']."<br/>";
            }
            $vPremise = $rs_order[$i]['iPremiseId']." (".$rs_order[$i]['vPremiseName']."; ".$rs_order[$i]['vPremiseType'].")";

            $entry[] = array(
                "iServiceOrderId"       => $rs_order[$i]['iServiceOrderId'],
                "vMasterMSA"            => $rs_order[$i]['vMasterMSA'],
                "vServiceOrder"         => $rs_order[$i]['vServiceOrder'],
                "iCarrierID"            => $rs_order[$i]['vCompanyName'],
                "vSalesRepName"         => $vSalesRepName,
                "iPremiseId"            => $vPremise,
                "iConnectionTypeId"     => $rs_order[$i]['vConnectionTypeName'],
                "vConnectionTypeName"   => $rs_order[$i]['vConnectionTypeName'],
                "iServiceDetails"       => $vServiceDetails,
                "tComments"             => $rs_order[$i]['tComments'],
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
        "iServiceOrderId"       => $_POST['iServiceOrderId'],
        "vMasterMSA"            => trim($_POST['vMasterMSA']),
        "vServiceOrder"         => trim($_POST['vServiceOrder']),
        "iCarrierID"            => $_POST['iCarrierID'],
        "vSalesRepName"         => trim($_POST['vSalesRepName']),
        "vSalesRepEmail"        => trim($_POST['vSalesRepEmail']),
        "iPremiseId"            => $_POST['search_iPremiseId'],
        "iConnectionTypeId"     => $_POST['iConnectionTypeId'],
        "iService1"             => $_POST['iService1'],
        "iService2"             => $_POST['iService2'],
        "iService3"             => $_POST['iService3'],
        "tComments"             => trim($_POST['tComments']),
        "iUserModifiedBy"       => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
        "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."service_order_edit.json";
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
    if(isset($result_arr['iServiceOrderId'])){
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
    $arr_param = array(
        "vMasterMSA"            => trim($_POST['vMasterMSA']),
        "vServiceOrder"         => trim($_POST['vServiceOrder']),
        "iCarrierID"            => $_POST['iCarrierID'],
        "vSalesRepName"         => trim($_POST['vSalesRepName']),
        "vSalesRepEmail"        => trim($_POST['vSalesRepEmail']),
        "iPremiseId"            => $_POST['search_iPremiseId'],
        "iConnectionTypeId"     => $_POST['iConnectionTypeId'],
        "iService1"             => $_POST['iService1'],
        "iService2"             => $_POST['iService2'],
        "iService3"             => $_POST['iService3'],
        "tComments"             => trim($_POST['tComments']),
        "iUserCreatedBy"        => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
        "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."service_order_add.json";
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
    if(isset($result_arr['iServiceOrderId'])){
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
    $iServiceOrderId = $_REQUEST['iServiceOrderId'];
    $result = array();
    $arr_param = array();
    $arr_param['iServiceOrderId']   = $iServiceOrderId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."service_order_delete.json";
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
}else if($mode== "Excel"){
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

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."service_order_list.json";
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

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_export = $result_arr['result']['data'];
    $cnt_export = count($rs_export);
    //  echo "<pre>";print_r($rs_export);exit();
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    // // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $file_name = "service_order_".time().".xlsx";

    if($cnt_export >0) {
        $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A1', 'Id')
                 ->setCellValue('B1', 'Master MSA #')
                 ->setCellValue('C1', 'Service Order')
                 ->setCellValue('D1', 'Carrier')
                 ->setCellValue('E1', 'SalesRep Name')
                 ->setCellValue('F1', 'SalesRep Email')
                 ->setCellValue('G1', 'Premise Name')
                 ->setCellValue('H1', 'Connection Type')
                 ->setCellValue('I1', 'Service1')
                 ->setCellValue('J1', 'Service2')
                 ->setCellValue('K1', 'Service3')
                 ->setCellValue('L1', 'Comments');
    
        for($e=0; $e<$cnt_export; $e++) {
            $vPremise = $rs_export[$e]['iPremiseId']." (".$rs_export[$e]['vPremiseName']."; ".$rs_export[$e]['vPremiseType'].")";

            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iServiceOrderId'])
            ->setCellValue('B'.($e+2), $rs_export[$e]['vMasterMSA'])
            ->setCellValue('C'.($e+2), $rs_export[$e]['vServiceOrder'])
            ->setCellValue('D'.($e+2), $rs_export[$e]['vCompanyName'])
            ->setCellValue('E'.($e+2), $rs_export[$e]['vSalesRepName'])
            ->setCellValue('F'.($e+2), $rs_export[$e]['vSalesRepEmail'])
            ->setCellValue('G'.($e+2), $vPremise)
            ->setCellValue('H'.($e+2), $rs_export[$e]['vConnectionTypeName'])
            ->setCellValue('I'.($e+2), $rs_export[$e]['vServiceType1'])
            ->setCellValue('J'.($e+2), $rs_export[$e]['vServiceType2'])
            ->setCellValue('K'.($e+2), $rs_export[$e]['vServiceType3'])
            ->setCellValue('L'.($e+2), nl2br($rs_export[$e]['tComments']));
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        
        /* Set Font to Bold for each comlumn */
        $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
        

        /* Set Alignment of Selected Columns */
        $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Service Order');

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

$module_name = "Service Order List";
$module_title = "Service Order";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("iAGroupId", $_GET['iAGroupId']);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);

?>