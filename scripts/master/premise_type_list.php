<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise Type", 'List');
$access_group_var_delete = per_hasModuleAccess("Premise Type", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise Type", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise Type", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise Type", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "premise_type.inc.php");
include_once($function_path."image.inc.php");

$page = $_REQUEST['page'];
# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '7');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
$Site_TypeObj = new SiteType();
$iSTypeId = $_POST['iSTypeId'];

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

    $API_URL = $site_api_url."premise_type_list.json";
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
    $rs_type = $result_arr['result']['data'];

    $ni = count($rs_type);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_type[$i]['iSTypeId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1') {
                $action .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_type[$i]['iSTypeId'].');"><i class="fa fa-trash"></i></a>';
            }
           
            $site_icon = "";
            $site_icon_image = "";
            if($rs_type[$i]['icon'] !=""  && file_exists($premise_type_icon_path."".$rs_type[$i]['icon'])){
                $site_icon_image = $premise_type_icon_url."".$rs_type[$i]['icon'];
                $site_icon = '<img src="'.$site_icon_image.'" alt="" class="img-fluid rounded-circle">';
            }
           
            $entry[] = array(
                "checkbox" =>$rs_type[$i]['iSTypeId'].'<input type="hidden" id="stype_id_'.$rs_type[$i]['iSTypeId'].'" value="'.$rs_type[$i]['iSTypeId'].'">',
                "vTypeName" =>gen_strip_slash($rs_type[$i]['vTypeName']).'<input type="hidden" id="stype_name_'.$rs_type[$i]['iSTypeId'].'" value="'.$rs_type[$i]['vTypeName'].'"><input type="hidden" id="stype_icon_'.$rs_type[$i]['iSTypeId'].'" value="'.$rs_type[$i]['icon'].'">',
                "icon" =>$site_icon.'<input type="hidden" id="stype_icon_url_'.$rs_type[$i]['iSTypeId'].'" value="'.$site_icon_image.'">',
                "iStatus" => '<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_type[$i]['iStatus']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_type[$i]['iStatus'])].'">&nbsp;</span><input type="hidden" id="stype_status_'.$rs_type[$i]['iSTypeId'].'" value="'.gen_status($rs_type[$i]['iStatus']).'">',
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
}
else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iSTypeId = $_POST['iSTypeId'];

    $arr_param['iSTypeId'] = $iSTypeId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."premise_type_delete.json";
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
}
else if($mode == "Update"){
    //echo "<pre>";print_r($_FILES);exit;

    $arr_param = array();
    if(isset($_FILES["icon_url"])){
        // $tmpfile = $_FILES["icon_url"]['tmp_name'];
        // $filename = basename($_FILES["icon_url"]['name']);
        // $files =  curl_file_create($tmpfile, $_FILES["icon_url"]['type'], $filename);

        $file_arr = img_fileUpload("icon_url", $premise_type_icon_path, '', $valid_ext = array('jpg', 'jpeg', 'png'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    } else {
        $file_name = $_POST['icon_url_old'];
    }

    $arr_param = array(
        "iSTypeId"      => $_POST['iSTypeId'], 
        "vTypeName"     => $_POST['vTypeName'],
        "icon_url_old"  => $_POST['icon_url_old'],
        "iStatus"       => isset($_POST['iStatus'])?$_POST['iStatus']:"0",
        "icon"          => $file_name,
        //"icon"      => $files,
        "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."premise_type_edit.json";
    //echo $API_URL." ".json_encode($arr_param);exit();
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
    //echo "<pre>;";print_r($result_arr);exit;
    if(isset($result_arr['iSTypeId'])){
        $result['msg'] = MSG_UPDATE." ".$file_msg;
        $result['error']= 0 ;
    }else{
        //$result['msg'] = MSG_ADD_ERROR;
        $result['msg'] = $result_arr['Message'];
        $result['error']= 1 ;
    }
    
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------    
}
else if($mode == "Add"){
    $arr_param = array();
    $files = "";
    //echo "<pre>";print_r($_FILES);exit;
    if(isset($_FILES["icon_url"])){
        $tmpfile = $_FILES["icon_url"]['tmp_name'];
        $filename = basename($_FILES["icon_url"]['name']);
        $files =  curl_file_create($tmpfile, $_FILES["icon_url"]['type'], $filename);
        $file_arr = img_fileUpload("icon_url", $premise_type_icon_path, '', $valid_ext = array('jpg', 'jpeg', 'png'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    }

    $arr_param = array(
        "vTypeName" => $_POST['vTypeName'],
        "iStatus"   => isset($_POST['iStatus'])?$_POST['iStatus']:"0",
        //"icon"      => $files,
        "icon"          => $file_name,
        "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."premise_type_add.json";
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
    if(isset($result_arr['iSTypeId'])){
        $result['msg'] = MSG_ADD." ".$file_msg;
        $result['error']= 0 ;
    }else{
        //$result['msg'] = MSG_ADD_ERROR;
        $result['msg'] = $result_arr['Message'];
        $result['error']= 1 ;
    }
    
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

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;
    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."premise_type_list.json";
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
    //$file_name = "county_".time().".xls";
    $file_name = "premise_type_".time().".xlsx";
    if($cnt_export >0) {
        $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A1', 'Id')
                 ->setCellValue('B1', 'Premise Type')
                 ->setCellValue('C1', 'Status');
    
        for($e=0; $e<$cnt_export; $e++) {
            $status =gen_status($rs_export[$e]['iStatus']);
            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iSTypeId'])
            ->setCellValue('B'.($e+2), $rs_export[$e]['vTypeName'])
            ->setCellValue('C'.($e+2), $status);
         }
                        
        /* Set Auto width of each comlumn */
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        
        /* Set Font to Bold for each comlumn */
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        

        /* Set Alignment of Selected Columns */
        $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Premise Type');

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
$module_name = "Premise Type List";
$module_title = "Premise Type";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("access_group_var_add",$access_group_var_add);

?>