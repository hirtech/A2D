<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Service Pricing", 'List');
$access_group_var_delete = per_hasModuleAccess("Service Pricing", 'Delete', 'N');
$access_group_var_add = per_hasModuleAccess("Service Pricing", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Service Pricing", 'Edit', 'N');
# ----------- Access Rule Condition -----------
# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$page = $_REQUEST['page'];
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '0');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
include_once($function_path."image.inc.php");

if($mode == "List"){
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));

    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."service_pricing_list.json";
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
    // echo "<pre>"; print_r($result_arr);exit();

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_service_pricing = $result_arr['result']['data'];
    $ni = count($rs_service_pricing);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_service_pricing[$i]['iServicePricingId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
                $action .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_service_pricing[$i]['iServicePricingId'].');"><i class="fa fa-trash"></i></a>';
            }

            $site_vFile = "";
            $site_vFile_image = "";
            if($rs_service_pricing[$i]['vFile'] !=""  && file_exists($service_pricing_path."".$rs_service_pricing[$i]['vFile'])){
                $site_vFile_image = $service_pricing_url."".$rs_service_pricing[$i]['vFile'];
                $site_vFile = '<img src="'.$site_vFile_image.'" alt="" class="img-fluid rounded-circle">';
            }

            $vFile_d = "";
            $vFile_url = "";
            if($rs_service_pricing[$i]['vFile'] !=""  && file_exists($service_pricing_path.$rs_service_pricing[$i]['vFile'])){
                $vFile_url = $service_pricing_url.$rs_service_pricing[$i]['vFile'];
                $vFile_d = '<a href="'.$vFile_url.'" title="Download"><i class="fa fa-download"></i></a>';
            }

            $iServiceLevel = "";
            if($rs_service_pricing[$i]['iServiceLevel'] == 1){
                $iServiceLevel = "Best Effort";  
            }else if($rs_service_pricing[$i]['iServiceLevel'] == 2){
                $iServiceLevel = "Business Class";  
            }else if($rs_service_pricing[$i]['iServiceLevel'] == 3){
                $iServiceLevel = "SLA";  
            }else if($rs_service_pricing[$i]['iServiceLevel'] == 4){
                $iServiceLevel = "High Availability";  
            }

            $entry[] = array(                        
                "checkbox"          => $rs_service_pricing[$i]['iServicePricingId'].'<input type="hidden" id="service_pricing_id_'.$rs_service_pricing[$i]['iServicePricingId'].'" value="'.$rs_service_pricing[$i]['iServicePricingId'].'">',
                "iCarrierId"        => gen_strip_slash($rs_service_pricing[$i]['vCompanyName']).'<input type="hidden" id="iCarrierId_'.$rs_service_pricing[$i]['iServicePricingId'].'" value="'.$rs_service_pricing[$i]['iCarrierId'].'">',
                "iNetworkId"        => gen_strip_slash($rs_service_pricing[$i]['vNetwork']).'<input type="hidden" id="iNetworkId_'.$rs_service_pricing[$i]['iServicePricingId'].'" value="'.$rs_service_pricing[$i]['iNetworkId'].'">',
                "iConnectionTypeId" => gen_strip_slash($rs_service_pricing[$i]['vConnectionTypeName']).'<input type="hidden" id="iConnectionTypeId_'.$rs_service_pricing[$i]['iServicePricingId'].'" value="'.$rs_service_pricing[$i]['iConnectionTypeId'].'">',
                "iServiceTypeId"    => gen_strip_slash($rs_service_pricing[$i]['vServiceType']).'<input type="hidden" id="iServiceTypeId_'.$rs_service_pricing[$i]['iServicePricingId'].'" value="'.$rs_service_pricing[$i]['iServiceTypeId'].'">',
                "iServiceLevel"      => $iServiceLevel.'<input type="hidden" id="iServiceLevel_'.$rs_service_pricing[$i]['iServicePricingId'].'" value="'.$rs_service_pricing[$i]['iServiceLevel'].'">',
                "iNRCVariable"      => $rs_service_pricing[$i]['iNRCVariable'].'<input type="hidden" id="iNRCVariable_'.$rs_service_pricing[$i]['iServicePricingId'].'" value="'.$rs_service_pricing[$i]['iNRCVariable'].'">',
                "iMRCFixed"         => $rs_service_pricing[$i]['iMRCFixed'].'<input type="hidden" id="iMRCFixed_'.$rs_service_pricing[$i]['iServicePricingId'].'" value="'.$rs_service_pricing[$i]['iMRCFixed'].'">',
                "vFile"             => $vFile_d.'<input type="hidden" id="vFile_'.$rs_service_pricing[$i]['iServicePricingId'].'" value="'.$vFile_url.'">',
                "actions"           => ($action == "")?"---":$action
            );
        }
    }
    $jsonData['aaData'] = $entry;
    //echo "<pre>";print_r($jsonData);exit();
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iServicePricingId = $_POST['iServicePricingId'];

    $arr_param['iServicePricingId']    = $iServicePricingId; 
    $arr_param['sessionId']                   = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."service_pricing_delete.json";
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
}else if($mode == "Update"){
    $arr_param = array();
    //echo "<pre>";print_r($_FILES);exit;
    if(isset($_FILES["vFile"])){
        $file_arr = img_fileUpload("vFile", $service_pricing_path, '', $valid_ext = array('docx', 'doc', 'pdf'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    } else {
        $file_name = $_POST['vFile_old'];
    }

    $arr_param = array(
        "iServicePricingId"         => $_POST['iServicePricingId'],
        "iCarrierId"                => $_POST['iCarrierId'],
        "iNetworkId"                => $_POST['iNetworkId'],
        "iConnectionTypeId"         => $_POST['iConnectionTypeId'],
        "iServiceTypeId"            => $_POST['iServiceTypeId'],
        "iServiceLevel"             => $_POST['iServiceLevel'],
        "iNRCVariable"              => $_POST['iNRCVariable'],
        "iMRCFixed"                 => $_POST['iMRCFixed'],
        "vFile"                     => $file_name,
        "sessionId"                 => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."service_pricing_edit.json";
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
    if(isset($result_arr['iServicePricingId'])){
        $result['iServicePricingId']  = $result_arr['iServicePricingId'];
        $result['msg']      = $result_arr['Message'];
        $result['error']    = 0 ;
    }else{
        $result['msg']      = $result_arr['Message'];
        $result['error']    = 1 ;
    } 
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # ----------------------------------- 
}else if($mode == "Add"){
    $arr_param = array();
    $files = "";
    //echo "<pre>";print_r($_FILES);exit;
    if(isset($_FILES["vFile"])){
        $file_arr = img_fileUpload("vFile", $service_pricing_path, '', $valid_ext = array('docx', 'doc', 'pdf'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    }

    $arr_param = array(
        "iCarrierId"                => $_POST['iCarrierId'],
        "iNetworkId"                => $_POST['iNetworkId'],
        "iConnectionTypeId"         => $_POST['iConnectionTypeId'],
        "iServiceTypeId"            => $_POST['iServiceTypeId'],
        "iServiceLevel"             => $_POST['iServiceLevel'],
        "iNRCVariable"              => $_POST['iNRCVariable'],
        "iMRCFixed"                 => $_POST['iMRCFixed'],
        "vFile"                     => $file_name,
        "sessionId"                 => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."service_pricing_add.json";
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
    if(isset($result_arr['iServicePricingId'])){
        $result['iServicePricingId']    = $result_arr['iServicePricingId'];
        $result['msg']                  = $result_arr['Message'];
        $result['error']                = 0 ;
    }else{
        $result['msg']      = $result_arr['Message'];
        $result['error']    = 1 ;
    }
    
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
    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."service_pricing_list.json";
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

    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    //include_once($class_path.'PHPExcel-1.8/PHPExcel.php'); 
    // // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $file_name = "service_pricing_".time().".xlsx";

    if($cnt_export >0) {

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Id')
                ->setCellValue('B1', 'Carrier')
                ->setCellValue('C1', 'Network')
                ->setCellValue('D1', 'Connection Type')
                ->setCellValue('E1', 'Service Type')
                ->setCellValue('F1', 'Service Level')
                ->setCellValue('G1', 'NRC - Variable')
                ->setCellValue('H1', 'MRC - Fixed')
                ->setCellValue('I1', 'Document URL');
    
        for($e=0; $e<$cnt_export; $e++) {

            $vServiceLevel = "";
            if($rs_export[$e]['iServiceLevel'] == 1){
                $vServiceLevel = "Best Effort";  
            }else if($rs_export[$e]['iServiceLevel'] == 2){
                $vServiceLevel = "Business Class";  
            }else if($rs_export[$e]['iServiceLevel'] == 3){
                $vServiceLevel = "SLA";  
            }else if($rs_export[$e]['iServiceLevel'] == 4){
                $vServiceLevel = "High Availability";  
            }

            $vDocumentURL = "";
            if($rs_export[$e]['vFile'] !=""  && file_exists($service_pricing_path.$rs_export[$e]['vFile'])){
                $vDocumentURL = $service_pricing_url.$rs_export[$e]['vFile'];
            }

            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iServicePricingId'])
            ->setCellValue('B'.($e+2), $rs_export[$e]['vCompanyName'])
            ->setCellValue('C'.($e+2), $rs_export[$e]['vNetwork'])
            ->setCellValue('D'.($e+2), $rs_export[$e]['vConnectionTypeName'])
            ->setCellValue('E'.($e+2), $rs_export[$e]['vServiceType'])
            ->setCellValue('F'.($e+2), $vServiceLevel)
            ->setCellValue('G'.($e+2), $rs_export[$e]['iNRCVariable'])
            ->setCellValue('H'.($e+2), $rs_export[$e]['iMRCFixed'])
            ->setCellValue('I'.($e+2), $vDocumentURL);
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
        
        /* Set Font to Bold for each comlumn */
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
        

        /* Set Alignment of Selected Columns */
        $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Service Pricing');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
            
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $result_arr  = array();
    //  $objWriter  =   new PHPExcel_Writer_Excel2007($objPHPExcel);
    //  //$objWriter->save('php://output');
        
    //save in file 
    $objWriter->save($temp_gallery.$file_name);
    $result_arr['isError'] = 0;
    $result_arr['file_path'] = base64_encode($temp_gallery.$file_name);
    $result_arr['file_url'] = base64_encode($temp_gallery_url.$file_name);
    # -------------------------------------

    echo json_encode($result_arr);
    exit;
}

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
//echo "<pre>";print_r($rs_carrier);exit;

/*-------------------------- Network -------------------------- */
$ntwork_arr_param = array();
$ntwork_arr_param['iStatus']   = 1;
$ntwork_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$ntwork_API_URL = $site_api_url."network_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ntwork_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ntwork_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_ntwork = curl_exec($ch);
curl_close($ch);  
$res_ntwork = json_decode($response_ntwork, true);
$smarty->assign("rs_ntwork", $res_ntwork['result']);
/*-------------------------- Network -------------------------- */

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

/*-------------------------- Connection Type -------------------------- */
$ctype_arr_param = array();
$ctype_arr_param['iStatus']   = 1;
$ctype_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$ctype_API_URL = $site_api_url."connection_type_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ctype_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ctype_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_ctype = curl_exec($ch);
curl_close($ch);  
$res_ctype = json_decode($response_ctype, true);
$smarty->assign("rs_ctype", $res_ctype['result']);
/*-------------------------- Connection Type -------------------------- */

$module_name = "Service Pricing List";
$module_title = "Service Pricing";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("access_group_var_add",$access_group_var_add);