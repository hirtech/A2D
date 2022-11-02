<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Equipment", 'List');
$access_group_var_delete = per_hasModuleAccess("Equipment", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Equipment", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Equipment", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Equipment", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("Equipment", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Equipment", 'CSV', 'N');
$access_group_var_Respond = per_hasModuleAccess("Equipment", 'Respond', 'N');
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
    $Keyword = addslashes(trim($_REQUEST['Keyword']));

    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }

    $arr_param['page_length']   = $page_length;
    $arr_param['start']         = $start;
    $arr_param['sEcho']         = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir']           = $dir;

    $arr_param['iSEquipmentModelId']        = $_REQUEST['iSEquipmentModelId'];
    $arr_param['iSMaterialId']              = $_REQUEST['iSMaterialId'];
    $arr_param['iSPowerId']                 = $_REQUEST['iSPowerId'];
    $arr_param['iSGrounded']                = $_REQUEST['iSGrounded'];
    $arr_param['iSPremiseId']               = $_REQUEST['iSPremiseId'];
    $arr_param['PremiseFilterOpDD']         = $_REQUEST['PremiseFilterOpDD'];
    $arr_param['vPremiseName']              = $_REQUEST['vPremiseName'];
    $arr_param['iSInstallTypeId']           = $_REQUEST['iSInstallTypeId'];
    $arr_param['iSLinkTypeId']              = $_REQUEST['iSLinkTypeId'];
    $arr_param['iSOperationalStatusId']     = $_REQUEST['iSOperationalStatusId'];
    

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."equipment_list.json";
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
    $rs_equipment = $result_arr['result']['data'];
    //echo "<pre>";print_r($rs_equipment);
    $ni = count($rs_equipment);
    $entry =array();
    if ($ni > 0) {
        for ($i = 0; $i < $ni; $i++) {
            $action = '';
            if($access_group_var_edit == "1"){
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'service_order/equipment_add&mode=Update&iEquipmentId=' . $rs_equipment[$i]['iEquipmentId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if($access_group_var_delete == "1"){
                $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_equipment[$i]['iEquipmentId'].');"><i class="fa fa-trash"></i></a>';
            }

            $vPremise = $rs_equipment[$i]['iPremiseId']." (".$rs_equipment[$i]['vPremiseName']."; ".$rs_equipment[$i]['vPremiseType'].")";

            $entry[] = array(
                "iEquipmentId"          => $rs_equipment[$i]['iEquipmentId'],
                "vModelName"            => $rs_equipment[$i]['vModelName'],
                "vSerialNumber"         => $rs_equipment[$i]['vSerialNumber'],
                "vMACAddress"           => $rs_equipment[$i]['vMACAddress'],
                "dInstallByDate"        => $rs_equipment[$i]['dInstallByDate'],
                "dInstalledDate"        => $rs_equipment[$i]['dInstalledDate'],
                "dPurchaseDate"         => $rs_equipment[$i]['dPurchaseDate'],
                "dWarrantyExpiration"   => $rs_equipment[$i]['dWarrantyExpiration'],
                "vPremise"              => $vPremise,
                "dProvisionDate"        => $rs_equipment[$i]['dProvisionDate'],
                "vOperationalStatus"    => $rs_equipment[$i]['vOperationalStatus'],
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
        "iEquipmentId"          => $_POST['iEquipmentId'],
        "iEquipmentModelId"     => $_POST['iEquipmentModelId'],
        "vSerialNumber"         => trim($_POST['vSerialNumber']),
        "vMACAddress"           => trim($_POST['vMACAddress']),
        "vIPAddress"            => trim($_POST['vIPAddress']),
        "vSize"                 => trim($_POST['vSize']),
        "vWeight"               => trim($_POST['vWeight']),
        "iMaterialId"           => $_POST['iMaterialId'],
        "iPowerId"              => $_POST['iPowerId'],
        "iGrounded"             => $_POST['iGrounded'],
        "dInstallByDate"        => $_POST['dInstallByDate'],
        "dInstalledDate"        => $_POST['dInstalledDate'],
        "vPurchaseCost"         => $_POST['vPurchaseCost'],
        "dPurchaseDate"         => $_POST['dPurchaseDate'],
        "dWarrantyExpiration"   => $_POST['dWarrantyExpiration'],
        "vWarrantyCost"         => $_POST['vWarrantyCost'],
        "iPremiseId"            => $_POST['search_iPremiseId'],
        "iInstallTypeId"        => $_POST['iInstallTypeId'],
        "iPrimaryCircuitId"     => $_POST['iPrimaryCircuitId'],
        "iLinkTypeId"           => $_POST['iLinkTypeId'],
        "dProvisionDate"        => $_POST['dProvisionDate'],
        "iOperationalStatusId"  => $_POST['iOperationalStatusId'],
        "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."equipment_edit.json";
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
    if(isset($result_arr['iEquipmentId'])){
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
        "iEquipmentModelId"     => $_POST['iEquipmentModelId'],
        "vSerialNumber"         => trim($_POST['vSerialNumber']),
        "vMACAddress"           => trim($_POST['vMACAddress']),
        "vIPAddress"            => trim($_POST['vIPAddress']),
        "vSize"                 => trim($_POST['vSize']),
        "vWeight"               => trim($_POST['vWeight']),
        "iMaterialId"           => $_POST['iMaterialId'],
        "iPowerId"              => $_POST['iPowerId'],
        "iGrounded"             => $_POST['iGrounded'],
        "dInstallByDate"        => $_POST['dInstallByDate'],
        "dInstalledDate"        => $_POST['dInstalledDate'],
        "vPurchaseCost"         => $_POST['vPurchaseCost'],
        "dPurchaseDate"         => $_POST['dPurchaseDate'],
        "dWarrantyExpiration"   => $_POST['dWarrantyExpiration'],
        "vWarrantyCost"         => $_POST['vWarrantyCost'],
        "iPremiseId"            => $_POST['search_iPremiseId'],
        "iInstallTypeId"        => $_POST['iInstallTypeId'],
        "iPrimaryCircuitId"     => $_POST['iPrimaryCircuitId'],
        "iLinkTypeId"           => $_POST['iLinkTypeId'],
        "dProvisionDate"        => $_POST['dProvisionDate'],
        "iOperationalStatusId"  => $_POST['iOperationalStatusId'],
        "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."equipment_add.json";
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
    if(isset($result_arr['iEquipmentId'])){
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
    $iEquipmentId = $_REQUEST['iEquipmentId'];
    $result = array();
    $arr_param = array();
    $arr_param['iEquipmentId']   = $iEquipmentId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."equipment_delete.json";
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

    $API_URL = $site_api_url."equipment_list.json";
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
    $file_name = "equipment_".time().".xlsx";

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
            ->setCellValue('A'.($e+2), $rs_export[$e]['iEquipmentId'])
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

/*************** Equipment Model Dropdown ***************/
$model_param = array();
$model_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$modelAPI_URL = $site_api_url."equipment_model_dropdown.json";
//echo $modelAPI_URL." ".json_encode($model_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $modelAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($model_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_model = $res['result'];
$smarty->assign("rs_model", $rs_model);
/*************** Equipment Model Dropdown ***************/

/*************** Material Dropdown ***************/
$material_param = array();
$material_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$materialAPI_URL = $site_api_url."material_dropdown.json";
//echo $materialAPI_URL." ".json_encode($material_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $materialAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($material_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_material = $res['result'];
$smarty->assign("rs_material", $rs_material);
/*************** Power Dropdown ***************/

/*************** Power Dropdown ***************/
$power_param = array();
$power_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$powerAPI_URL = $site_api_url."power_dropdown.json";
//echo $powerAPI_URL." ".json_encode($power_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $powerAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($power_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_power = $res['result'];
$smarty->assign("rs_power", $rs_power);
/*************** Power Dropdown ***************/

/*************** Install Type Dropdown ***************/
$itype_param = array();
$itype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$itypeAPI_URL = $site_api_url."install_type_dropdown.json";
//echo $itypeAPI_URL." ".json_encode($itype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $itypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($itype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_itype = $res['result'];
$smarty->assign("rs_itype", $rs_itype);
/*************** Install Type Dropdown ***************/

/*************** Link Type Dropdown ***************/
$ltype_param = array();
$ltype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$ltypeAPI_URL = $site_api_url."link_type_dropdown.json";
//echo $ltypeAPI_URL." ".json_encode($ltype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ltypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ltype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_ltype = $res['result'];
$smarty->assign("rs_ltype", $rs_ltype);
//echo"<pre>";print_r($rs_ltype);exit;
/*************** Link Type Dropdown ***************/


/*************** Operational Status Dropdown ***************/
$otype_param = array();
$otype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$otypeAPI_URL = $site_api_url."operational_status_dropdown.json";
//echo $otypeAPI_URL." ".json_encode($otype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $otypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($otype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_ostatus = $res['result'];
$smarty->assign("rs_ostatus", $rs_ostatus);
//echo"<pre>";print_r($rs_ostatus);exit;
/*************** Operational Status Dropdown ***************/

$module_name = "Equipment List";
$module_title = "Equipment";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);
$smarty->assign("iPremiseId", $iPremiseId);
?>