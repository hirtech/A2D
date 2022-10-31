<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Equipment Model", 'List');
$access_group_var_delete = per_hasModuleAccess("Equipment Model", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Equipment Model", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Equipment Model", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Equipment Model", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("Equipment Model", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Equipment Model", 'CSV', 'N');
$access_group_var_Respond = per_hasModuleAccess("Equipment Model", 'Respond', 'N');
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

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."equipment_model_list.json";
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
    $rs_model = $result_arr['result']['data'];
    //echo "<pre>";print_r($rs_model);exit();
    $ni = count($rs_model);
    $entry =array();
    if ($ni > 0) {
        for ($i = 0; $i < $ni; $i++) {
            $action = '';
            if($access_group_var_edit == "1"){
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'service_order/equipment_model_add&mode=Update&iEquipmentModelId=' . $rs_model[$i]['iEquipmentModelId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if($access_group_var_delete == "1"){
                $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_model[$i]['iEquipmentModelId'].');"><i class="fa fa-trash"></i></a>';
            }

            $entry[] = array(
                "iEquipmentModelId"         => $rs_model[$i]['iEquipmentModelId'],
                "vModelName"                => $rs_model[$i]['vModelName'],
                "vModelNumber"              => $rs_model[$i]['vModelNumber'],
                "vPartNumber"               => $rs_model[$i]['vPartNumber'],
                "iUnitQuantity"             => $rs_model[$i]['iUnitQuantity'],
                "rUnitCost"                 => $rs_model[$i]['rUnitCost'],
                "vEquipmentType"            => $rs_model[$i]['vEquipmentType'],
                "vEquipmentManufacturer"    => $rs_model[$i]['vEquipmentManufacturer'],
                "actions"                   => ($action!="")?$action:"---"
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
        "iEquipmentModelId"          => $_POST['iEquipmentModelId'],
        "vModelName"                => trim($_POST['vModelName']),
        "vModelNumber"              => trim($_POST['vModelNumber']),
        "vPartNumber"               => trim($_POST['vPartNumber']),
        "tDescription"              => trim($_POST['tDescription']),
        "iUnitQuantity"             => trim($_POST['iUnitQuantity']),
        "rUnitCost"                 => trim($_POST['rUnitCost']),
        "iEquipmentTypeId"          => $_POST['iEquipmentTypeId'],
        "iEquipmentManufacturerId"  => $_POST['iEquipmentManufacturerId'],
        "sessionId"                 => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."equipment_model_edit.json";
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
    if(isset($result_arr['iEquipmentModelId'])){
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
        "vModelName"                => trim($_POST['vModelName']),
        "vModelNumber"              => trim($_POST['vModelNumber']),
        "vPartNumber"               => trim($_POST['vPartNumber']),
        "tDescription"              => trim($_POST['tDescription']),
        "iUnitQuantity"             => trim($_POST['iUnitQuantity']),
        "rUnitCost"                 => trim($_POST['rUnitCost']),
        "iEquipmentTypeId"          => $_POST['iEquipmentTypeId'],
        "iEquipmentManufacturerId"  => $_POST['iEquipmentManufacturerId'],
        "sessionId"                 => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."equipment_model_add.json";
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
    if(isset($result_arr['iEquipmentModelId'])){
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
    $iEquipmentModelId = $_REQUEST['iEquipmentModelId'];
    $result = array();
    $arr_param = array();
    $arr_param['iEquipmentModelId']   = $iEquipmentModelId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."equipment_model_delete.json";
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

    $API_URL = $site_api_url."equipment_model_list.json";
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
    $file_name = "equipment_model".time().".xlsx";

    if($cnt_export >0) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Id')
            ->setCellValue('B1', 'Model Name')
            ->setCellValue('C1', 'Model Number')
            ->setCellValue('D1', 'Part Number')
            ->setCellValue('E1', 'Unit Quantity')
            ->setCellValue('F1', 'Unit Cost')
            ->setCellValue('G1', 'Equipment Type')
            ->setCellValue('H1', 'Manufacturer');

        for($e=0; $e<$cnt_export; $e++) {

            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iEquipmentModelId'])
            ->setCellValue('B'.($e+2), $rs_export[$e]['vModelName'])
            ->setCellValue('C'.($e+2), $rs_export[$e]['vModelNumber'])
            ->setCellValue('D'.($e+2), $rs_export[$e]['vPartNumber'])
            ->setCellValue('E'.($e+2), $rs_export[$e]['iUnitQuantity'])
            ->setCellValue('F'.($e+2), $rs_export[$e]['rUnitCost'])
            ->setCellValue('G'.($e+2), $rs_export[$e]['vEquipmentType'])
            ->setCellValue('H'.($e+2), $rs_export[$e]['vEquipmentManufacturer']);
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
        $objPHPExcel->getActiveSheet()->setTitle('Equipment Model');

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
# Status Dropdown
$status_arr_param = array();
$status_arr_param = array(
    "iStatus"       => 1,
    "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$status_API_URL = $site_api_url."equipment_model_status_dropdown.json";
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
$module_name = "Equipment Model List";
$module_title = "Equipment Model";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);
$smarty->assign("iPremiseId", $iPremiseId);
?>