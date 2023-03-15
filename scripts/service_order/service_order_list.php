<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Service Order", 'List');
$access_group_var_delete = per_hasModuleAccess("Service Order", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Service Order", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Service Order", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Service Order", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Service Order", 'CSV', 'N');
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
    // print_r($_REQUEST);exit();
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "vNetwork"){
        $searchId = $_REQUEST['iSNetworkId'];
    }else if($vOptions == "vCarrier"){
        $searchId = $_REQUEST['iSCarrierId'];
    }else if($vOptions == "vConnectionType"){
        $searchId = $_REQUEST['iConnectionTypeId'];
    }else if($vOptions == "vServiceType"){
        $searchId = $_REQUEST['iSServiceType'];
    }else if($vOptions == "iSOStatus"){
        $searchId = $_REQUEST['iSOStatus'];
    }else if($vOptions == "iCStatus"){
        $searchId = $_REQUEST['iCStatus'];
    }else if($vOptions == "iSStatus"){
        $searchId = $_REQUEST['iSStatus'];
    }
    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }

    $arr_param['page_length']               = $page_length;
    $arr_param['start']                     = $start;
    $arr_param['sEcho']                     = $sEcho;
    $arr_param['display_order']             = $display_order;
    $arr_param['dir']                       = $dir;

    $arr_param['iFieldmapPremiseId']        = $iPremiseId;

    $arr_param['vSContactNameDD']           = trim($_REQUEST['vSContactNameDD']);
    $arr_param['vSContactName']             = trim($_REQUEST['vSContactName']);
    $arr_param['vSAddressFilterOpDD']       = trim($_REQUEST['vSAddressFilterOpDD']);
    $arr_param['vSAddress']                 = trim($_REQUEST['vSAddress']);
    $arr_param['vSCityFilterOpDD']          = trim($_REQUEST['vSCityFilterOpDD']);
    $arr_param['vSCity']                    = trim($_REQUEST['vSCity']);
    $arr_param['vSStateFilterOpDD']         = trim($_REQUEST['vSStateFilterOpDD']);
    $arr_param['vSState']                   = trim($_REQUEST['vSState']);
    $arr_param['vSZipCode']                 = trim($_REQUEST['vSZipCode']);
    $arr_param['iSZoneId']                  = trim($_REQUEST['iSZoneId']);
    $arr_param['iServiceOrderId']           = trim($_REQUEST['iServiceOrderId']);
    $arr_param['vMasterMSA']                = trim($_REQUEST['vMasterMSA']);
    $arr_param['vSSalesRepNameDD']          = trim($_REQUEST['vSSalesRepNameDD']);
    $arr_param['vSSalesRepName']            = trim($_REQUEST['vSSalesRepName']);
    $arr_param['vSSalesRepEmailDD']         = trim($_REQUEST['vSSalesRepEmailDD']);
    $arr_param['vSSalesRepEmail']           = trim($_REQUEST['vSSalesRepEmail']);
    $arr_param['vServiceOrder']             = trim($_REQUEST['vServiceOrder']);
    $arr_param['sess_iCompanyId']            = $_SESSION["sess_iCompanyId" . $admin_panel_session_suffix];

    $arr_param['A2D_COMPANY_ID']     = $A2D_COMPANY_ID;
    $arr_param['access_group_var_edit']     = $access_group_var_edit;
    $arr_param['access_group_var_delete']   = $access_group_var_delete;

    $arr_param['sessionId']                 = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."service_order_list.json";
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
    //echo "<pre>";print_r($rs_order);
    $ni = count($rs_order);
    $entry =array();
    if ($ni > 0) {
        for ($i = 0; $i < $ni; $i++) {
            $action = '';
            if($access_group_var_edit == "1" && $rs_order[$i]['iSOStatus']  != 3){ 
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

            $premise_url = $site_url."premise/edit&mode=Update&iPremiseId=".$rs_order[$i]['iPremiseId'];
            $vPremise = "<a href='".$premise_url."' target='_blank' class='text-primary'>".$rs_order[$i]['iPremiseId']." (".$rs_order[$i]['vPremiseName']."; ".$rs_order[$i]['vPremiseType'].")</a>";

            $iSOStatus = "";
            if($rs_order[$i]['iSOStatus'] == 1){
                $iSOStatus = '<span class="btn btn-warning">Created</span>';
            }else if($rs_order[$i]['iSOStatus'] == 2){
                $iSOStatus = '<span class="btn btn-info">Review</span>';
            }else if($rs_order[$i]['iSOStatus'] == 3){
                $iSOStatus = '<span class="btn btn-success">Approved</span>';
            }

            $iSStatus = "";
            if($rs_order[$i]['iSStatus'] == 0){
                $iSStatus = '<span class="btn btn-info">Pending</span>';
            }else if($rs_order[$i]['iSStatus'] == 1){
                $iSStatus = '<span class="btn btn-success">Active</span>';
            }else if($rs_order[$i]['iSStatus'] == 2){
                $iSStatus = '<span class="btn btn-warning">Suspended</span>';
            }else if($rs_order[$i]['iSStatus'] == 3){
                $iSStatus = '<span class="btn btn-dark">Trouble</span>';
            }else if($rs_order[$i]['iSStatus'] == 4){
                $iSStatus ='<span class="btn btn-danger">Disconnected</span>';
            }

            $entry[] = array(
                "checkbox"              => '<input type="checkbox" class="list" value="'.$rs_order[$i]['iServiceOrderId'].'"/>',
                "iServiceOrderId"       => $rs_order[$i]['iServiceOrderId'],
                "vMasterMSA"            => $rs_order[$i]['vMasterMSA'],
                "vServiceOrder"         => $rs_order[$i]['vServiceOrder'],
                "iCarrierID"            => $rs_order[$i]['vCompanyName'],
                "vSalesRepName"         => $vSalesRepName,
                "iPremiseId"            => $vPremise,
                "iConnectionTypeId"     => $rs_order[$i]['vConnectionTypeName'],
                "vConnectionTypeName"   => $rs_order[$i]['vConnectionTypeName'],
                "iServiceDetails"       => $rs_order[$i]['vServiceType1'],
                "tComments"             => $rs_order[$i]['tComments'],
                "iSOStatus"             => $iSOStatus,
                "iSStatus"              => $iSStatus,
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
        "vNameId"               => trim($_POST['vNameId']),
        "iCarrierID"            => $_POST['iCarrierID'],
        "iSalesRepId"           => trim($_POST['iSalesRepId']),
        "vSalesRepEmail"        => trim($_POST['vSalesRepEmail']),
        "iPremiseId"            => $_POST['search_iPremiseId'],
        "iConnectionTypeId"     => $_POST['iConnectionTypeId'],
        "iService1"             => $_POST['iService1'],
        "iOldSOStatus"          => $_POST['iOldSOStatus'],
        "iSOStatus"             => $_POST['iSOStatus'],
        "iCStatus"              => $_POST['iCStatus'],
        "iSStatus"              => $_POST['iSStatus'],
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
        "vNameId"               => trim($_POST['vNameId']),
        "iCarrierID"            => $_POST['iCarrierID'],
        "iSalesRepId"           => trim($_POST['iSalesRepId']),
        "vSalesRepEmail"        => trim($_POST['vSalesRepEmail']),
        "iPremiseId"            => $_POST['search_iPremiseId'],
        "iConnectionTypeId"     => $_POST['iConnectionTypeId'],
        "iService1"             => $_POST['iService1'],
        "iSOStatus"             => $_POST['iSOStatus'],
        "iCStatus"              => $_POST['iCStatus'],
        "iSStatus"              => $_POST['iSStatus'],
        "tComments"             => trim($_POST['tComments']),
        "iUserCreatedBy"        => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
        "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."service_order_add.json";
    // echo $API_URL." ".json_encode($arr_param);exit;
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
}else if($mode == "change_status"){
    $result = array();
    $arr_param = array();
    $status_field = $_POST['status_field'];
    $status = $_POST['status'];
    $iServiceOrderIds = $_POST['iServiceOrderIds'];
    
    $arr_param['status_field']      = $status_field; 
    $arr_param['status']            = $status; 
    $arr_param['iServiceOrderIds']  = $iServiceOrderIds; 
    $arr_param['iUserId']           = $_SESSION["sess_iUserId".$admin_panel_session_suffix];
    $arr_param['sessionId']         = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."service_order_change_status.json";
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
}else if($mode == "Excel"){
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

    $arr_param['vSContactNameDD']       = trim($_REQUEST['vSContactNameDD']);
    $arr_param['vSContactName']         = trim($_REQUEST['vSContactName']);
    $arr_param['vSAddressFilterOpDD']   = trim($_REQUEST['vSAddressFilterOpDD']);
    $arr_param['vSAddress']             = trim($_REQUEST['vSAddress']);
    $arr_param['vSCityFilterOpDD']      = trim($_REQUEST['vSCityFilterOpDD']);
    $arr_param['vSCity']                = trim($_REQUEST['vSCity']);
    $arr_param['vSStateFilterOpDD']     = trim($_REQUEST['vSStateFilterOpDD']);
    $arr_param['vSState']               = trim($_REQUEST['vSState']);
    $arr_param['vSZipCode']             = trim($_REQUEST['vSZipCode']);
    $arr_param['iSZoneId']              = trim($_REQUEST['iSZoneId']);
    $arr_param['iSNetworkId']           = trim($_REQUEST['iSNetworkId']);
    $arr_param['iSCarrierId']           = trim($_REQUEST['iSCarrierId']);
    $arr_param['vSSalesRepNameDD']      = trim($_REQUEST['vSSalesRepNameDD']);
    $arr_param['vSSalesRepName']        = trim($_REQUEST['vSSalesRepName']);
    $arr_param['vSSalesRepEmailDD']     = trim($_REQUEST['vSSalesRepEmailDD']);
    $arr_param['vSSalesRepEmail']       = trim($_REQUEST['vSSalesRepEmail']);
    $arr_param['iSServiceType']         = trim($_REQUEST['iSServiceType']);

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
                 ->setCellValue('I1', 'Service Type')
                 ->setCellValue('J1', 'Comments');
    
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
            ->setCellValue('J'.($e+2), nl2br($rs_export[$e]['tComments']));
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
        
        /* Set Font to Bold for each comlumn */
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
        

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
}else if($mode == "get_master_msa_from_carrier"){
    $arr_param = array(
        "iCompanyId"    => $_POST['iCarrierId'],
        "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."get_company_data_from_id.json";
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
    //echo "<pre>;";print_r($result_arr['result']);exit;
    $vMSANum = '';
    $vNameId = '';
    if(isset($result_arr['result'])){
        $vMSANum = $result_arr['result']['vMSANum'];
        $vNameId = $result_arr['result']['vNameId'];
        $result['vMSANum'] = $vMSANum ;
        $result['vNameId'] = $vNameId ;
        $result['error'] = 0 ;
    }else{
        $result['vNameId'] = $vNameId ;
        $result['error'] = 1 ;
    }

    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------  
}else if($mode == "get_user_details_from_carrier"){
    $arr_param = array(
        "iCompanyId"    => $_POST['iCarrierId'],
        "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."get_user_details_from_company_id.json";
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
    //echo "<pre>;";print_r($result_arr['result']);exit;
    if(isset($result_arr['result']) && count($result_arr['result']) > 0){
        $result['user_data'] = $result_arr['result'];
        $result['error'] = 0;
    }else{
        $result['user_data'] = [];
        $result['error'] = 1;
    }

    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------  
}else if($mode == "get_user_details_from_user"){
    $arr_param = array(
        "iUserId"    => $_POST['iUserId'],
        "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."getUserDetailsFromUserId.json";
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
    //echo "<pre>";print_r($result_arr['result']);exit;
    $vEmail = '';
    if(isset($result_arr['result'][0]['vEmail'])){
        $vEmail = $result_arr['result'][0]['vEmail'];
        $result['vEmail'] = $vEmail ;
        $result['error'] = 0 ;
    }else{
        $result['vEmail'] = $vEmail ;
        $result['error'] = 1 ;
    }

    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------  
} 

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

//Service Type Dropdown
$stype_param = array();
$stype_param['iStatus'] = '1';
$stype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$stypeAPI_URL = $site_api_url."service_type_dropdown.json";
//echo $stypeAPI_URL." ".json_encode($stype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $stypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($stype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_stype = curl_exec($ch);
curl_close($ch);  
$res_stype = json_decode($response_stype, true);
$rs_stype = $res_stype['result'];
$smarty->assign("rs_stype", $rs_stype);
//echo "<pre>";print_r($rs_stype);exit;

//Connection Type Dropdown
$cntype_param = array();
$cntype_param['iStatus'] = '1';
$cntype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$cntypeAPI_URL = $site_api_url."connection_type_dropdown.json";
//echo $cntypeAPI_URL." ".json_encode($cntype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $cntypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($cntype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_cntype = curl_exec($ch);
curl_close($ch);  
$res_cntype = json_decode($response_cntype, true);
$rs_cntype = $res_cntype['result'];
$smarty->assign("rs_cntype", $rs_cntype);
//echo "<pre>";print_r($rs_cntype);exit;

$module_name = "Service Order List";
$module_title = "Service Order";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);
$smarty->assign("iPremiseId", $iPremiseId);
$smarty->assign("sess_iCompanyId", $_SESSION["sess_iCompanyId" . $admin_panel_session_suffix]);
?>