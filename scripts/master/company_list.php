<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Company", 'List');
$access_group_var_delete = per_hasModuleAccess("Company", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Company", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Company", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Company", 'Edit', 'N');
# ----------- Access Rule Condition -----------
# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$page = $_REQUEST['page'];
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '1';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '1');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
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

    $API_URL = $site_api_url."company_list.json";
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
    $rs_company = $result_arr['result']['data'];
    $ni = count($rs_company);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_company[$i]['iCompanyId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
                $action .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_company[$i]['iCompanyId'].');"><i class="fa fa-trash"></i></a>';
            }

            $entry[] = array(                        
                "checkbox" =>$rs_company[$i]['iCompanyId'].'<input type="hidden" id="engagement_id_'.$rs_company[$i]['iCompanyId'].'" value="'.$rs_company[$i]['iCompanyId'].'">',
                "vCompanyType" =>gen_strip_slash($rs_company[$i]['vCompanyType']).'<input type="hidden" id="company_type_'.$rs_company[$i]['iCompanyId'].'" value="'.$rs_company[$i]['vCompanyType'].'">',
                "vCompanyName" =>gen_strip_slash($rs_company[$i]['vCompanyName']).'<input type="hidden" id="company_name_'.$rs_company[$i]['iCompanyId'].'" value="'.$rs_company[$i]['vCompanyName'].'">',
                "vNameId" =>gen_strip_slash($rs_company[$i]['vNameId']).'<input type="hidden" id="name_id_'.$rs_company[$i]['iCompanyId'].'" value="'.$rs_company[$i]['vNameId'].'">',
                "vAccessType" =>gen_strip_slash($rs_company[$i]['vAccessType']).'<input type="hidden" id="access_type_'.$rs_company[$i]['iCompanyId'].'" value="'.$rs_company[$i]['vAccessType'].'">',
                "vMSOYr" =>gen_strip_slash($rs_company[$i]['vMSOYr']).'<input type="hidden" id="vmsoyr_'.$rs_company[$i]['iCompanyId'].'" value="'.$rs_company[$i]['vMSOYr'].'">',
                "vMSANum" =>gen_strip_slash($rs_company[$i]['vMSANum']).'<input type="hidden" id="vmsanum_'.$rs_company[$i]['iCompanyId'].'" value="'.$rs_company[$i]['vMSANum'].'">',
                "iStatus" => '<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_company[$i]['iStatus']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_company[$i]['iStatus'])].'">&nbsp;</span><input type="hidden" id="company_status_'.$rs_company[$i]['iCompanyId'].'" value="'.gen_status($rs_company[$i]['iStatus']).'">',
                "actions" => ($action == "")?"---":$action
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
    $iCompanyId = $_POST['iCompanyId'];

    $arr_param['iCompanyId'] = $iCompanyId; 
    $arr_param['sessionId']         = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."company_delete.json";
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
}else if($mode == "Update"){
    $arr_param = array();
    $arr_param = array(
        "iCompanyId"        => $_POST['iCompanyId'],
        "vCompanyType"      => $_POST['vCompanyType'],
        "vCompanyName"      => $_POST['vCompanyName'],
        "vNameId"           => $_POST['vNameId'],
        "vAccessType"       => $_POST['vAccessType'],
        "vMSOYr"            => $_POST['vMSOYr'],
        "vMSANum"           => $_POST['vMSANum'],
        "iStatus"           => ($_POST['iStatus'] !='')?$_POST['iStatus']:"0",
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."company_edit.json";
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
    if(isset($result_arr['iCompanyId'])){
        $result['iCompanyId']  = $iCompanyId;
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
    $arr_param = array(
        "vCompanyType"      => $_POST['vCompanyType'],
        "vCompanyName"      => $_POST['vCompanyName'],
        "vNameId"           => $_POST['vNameId'],
        "vAccessType"       => $_POST['vAccessType'],
        "vMSOYr"            => $_POST['vMSOYr'],
        "vMSANum"           => $_POST['vMSANum'],
        "iStatus"           => ($_POST['iStatus'] !='')?$_POST['iStatus']:"0",
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."company_add.json";
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
    if(isset($result_arr['iCompanyId'])){
        $result['iCompanyId']    = $iCompanyId;
        $result['msg']              = $result_arr['Message'];
        $result['error']            = 0 ;
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

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."company_list.json";
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
    $file_name = "company_".time().".xlsx";

    if($cnt_export >0) {

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Id')
                ->setCellValue('B1', 'Company Type')
                ->setCellValue('C1', 'Company Name')
                ->setCellValue('D1', 'Name Id')
                ->setCellValue('E1', 'Access Type')
                ->setCellValue('F1', 'MSOYr')
                ->setCellValue('G1', 'MSANum')
                ->setCellValue('H1', 'Status');
    
        for($e=0; $e<$cnt_export; $e++) {
            $iStatus = ($rs_export[$e]['iStatus'] == "1")?"Active":"Inactive";
            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iCompanyId'])
            ->setCellValue('B'.($e+2), $rs_export[$e]['vCompanyType'])
            ->setCellValue('C'.($e+2), $rs_export[$e]['vCompanyName'])
            ->setCellValue('D'.($e+2), $rs_export[$e]['vNameId'])
            ->setCellValue('E'.($e+2), $rs_export[$e]['vAccessType'])
            ->setCellValue('F'.($e+2), $rs_export[$e]['vMSOYr'])
            ->setCellValue('G'.($e+2), $rs_export[$e]['vMSANum'])
            ->setCellValue('H'.($e+2), $iStatus);
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
        $objPHPExcel->getActiveSheet()->getStyle("A1:H".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Company');

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
    
$module_name = "Company Type List";
$module_title = "Company";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("mode", $mode);
$smarty->assign("access_group_var_add",$access_group_var_add);