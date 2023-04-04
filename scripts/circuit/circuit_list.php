<?php
//echo "<pre>";print_r($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Circuit", 'List');
$access_group_var_delete = per_hasModuleAccess("Circuit", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Circuit", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Circuit", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Circuit", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Circuit", 'CSV', 'N');
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
include_once($function_path."image.inc.php");

if($mode == "List"){
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "vCircuitType"){
        $searchId = $_REQUEST['circuitTypeId'];
    }else if($vOptions == "vNetwork"){
        $searchId = $_REQUEST['networkId'];
    }

    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."circuit_list.json";
    // echo $API_URL." ".json_encode($arr_param);exit;
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
    $rs_list = $result_arr['result']['data'];
    $ni = count($rs_list);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if($access_group_var_edit == "1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'circuit/circuit_edit&mode=Update&iCircuitId=' . $rs_list[$i]['iCircuitId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_list[$i]['iCircuitId'].');"><i class="fa fa-trash"></i></a>';
            }

            $entry[] = array(
                "iCircuitId"    => $rs_list[$i]['iCircuitId'],
                "vCircuitType"  => $rs_list[$i]['vCircuitType'],
                "vNetwork"      => $rs_list[$i]['vNetwork'],
                "vCircuitName"  => $rs_list[$i]['vCircuitName'],
                "vName"         => $rs_list[$i]['vName'],
                "tComments"     => nl2br($rs_list[$i]['tComments']),
                "actions"       => ($action!="")?$action:"---"       
            );
        }
    }

    # Return jSON data.
    # -----------------------------------
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iCircuitId = $_POST['iCircuitId'];

    $arr_param['iCircuitId'] = $iCircuitId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."circuit_delete.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
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
}else if($mode == "Add"){
    $arr_param = array();

    $file_msg = "";
    if(isset($_FILES["vFile"])){
        $file_arr = img_fileUpload("vFile", $circuit_path, '', $valid_ext = array('docx', 'doc','xlsx', 'xls', 'pdf'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    } 

    $arr_param = array(
        "iCircuitTypeId"    => $_POST['iCircuitTypeId'],
        "iNetworkId"        => $_POST['iNetworkId'],
        "vCircuitName"      => trim($_POST['vCircuitName']),
        "vName"             => trim($_POST['vName']),
        "tComments"         => trim($_POST['tComments']),
        "vFile"             => $file_name,
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."circuit_add.json";
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
    //echo "<pre>";print_r($result_arr);exit();
    if(isset($result_arr['iCircuitId'])){       
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
}else if($mode == "Update"){
    $result =array();
    $file_msg = "";
    if(isset($_FILES["vFile"])){
        $file_arr = img_fileUpload("vFile", $circuit_path, '', $valid_ext = array('docx', 'doc','xlsx', 'xls', 'pdf'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    } else {
        $file_name = $_POST['vFile_old'];
    }

    $arr_param = array(
        'iCircuitId'        => $_POST['iCircuitId'],
        "iCircuitTypeId"    => $_POST['iCircuitTypeId'],
        "iNetworkId"        => $_POST['iNetworkId'],
        "vCircuitName"      => trim($_POST['vCircuitName']),
        "vName"             => trim($_POST['vName']),
        "tComments"         => trim($_POST['tComments']),
        "vFile"             => $file_name,
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );
    
    $API_URL = $site_api_url."circuit_edit.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
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
    $result = json_decode($response, true); 
    if($result){
        $result['msg'] = MSG_UPDATE." ".$file_msg;
        $result['error']= 0 ;
    }else{
        $result['msg'] = MSG_UPDATE_ERROR;
        $result['error']= 1 ;
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

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."circuit_list.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
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
    $file_name = "circuit_".time().".xlsx";

    if($cnt_export >0) {
        $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A1', 'Id')
                 ->setCellValue('B1', 'Circuit Type')
                 ->setCellValue('C1', 'Network')
                 ->setCellValue('D1', 'Circuit Name')
                 ->setCellValue('E1', 'Name')
                 ->setCellValue('F1', 'Comments');
    
        for($e=0; $e<$cnt_export; $e++) {

            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iCircuitId'])
            ->setCellValue('B'.($e+2), $rs_export[$e]['vCircuitType'])
            ->setCellValue('C'.($e+2), $rs_export[$e]['vNetwork'])
            ->setCellValue('D'.($e+2), $rs_export[$e]['vCircuitName'])
            ->setCellValue('E'.($e+2), $rs_export[$e]['vName'])
            ->setCellValue('F'.($e+2), $rs_export[$e]['tComments']);
         }
                        
        /* Set Auto width of each comlumn */
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        
        /* Set Font to Bold for each comlumn */
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        

        /* Set Alignment of Selected Columns */
        $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Circuit');

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
}else if($mode == "delete_document"){
    $result = array();
    $arr_param = array();
    $iCircuitId = $_POST['iCircuitId'];

    $arr_param['iCircuitId'] = $iCircuitId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."circuit_delete_document.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
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
    if(!empty($result_arr)){
        $result['msg'] = $result_arr['Message'];
        $result['error'] = $result_arr['error']; ;
        $result['iCircuitId'] = $iCircuitId;
    }else{
        $result['msg'] = "ERROR - in file delete.";
        $result['error'] = 1 ;
        $result['iCircuitId'] = $iCircuitId;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # ----------------------------------- 
}
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
/*************** Circuit Type Dropdown ***************/
$ctype_param = array();
$ctype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$ctypeAPI_URL = $site_api_url."circuit_type_dropdown.json";
//echo $ctypeAPI_URL." ".json_encode($ctype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ctypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ctype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_ctype = $res['result'];
$smarty->assign("rs_ctype", $rs_ctype);
// echo"<pre>";print_r($rs_ctype);exit;
## --------------------------------

$module_name = "Circuit List";
$module_title = "Circuit";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);
?>