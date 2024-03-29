<?php
//echo "<pre>";print_r($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Network", 'List');
$access_group_var_delete = per_hasModuleAccess("Network", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Network", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Network", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Network", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Network", 'CSV', 'N');
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

include_once($controller_path . "network.inc.php");

$NetworkObj = new Network();
//echo "<pre>";print_r($mode);exit;
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

    $API_URL = $site_api_url."network_list.json";
    //echo "<pre>";print_r($API_URL);exit;
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
               $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'network/network_edit&mode=Update&iNetworkId=' . $rs_list[$i]['iNetworkId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_list[$i]['iNetworkId'].');"><i class="fa fa-trash"></i></a>';
            }
            $status = "";
            if($rs_list[$i]['iStatus'] == 0){
                $status = '<span title="Inactive" class="btn btn-danger">Inactive</span>';
            }else if($rs_list[$i]['iStatus'] == 1){
                $status = '<span title="Active" class="btn btn-success">Active</span>';
            }else if($rs_list[$i]['iStatus'] == 2){
                $status = '<span title="Created" class="btn btn-prmiary">Created</span>';
            }else if($rs_list[$i]['iStatus'] == 3){
                $status = '<span title="Planning" class="btn btn-secondary">Planning</span>';
            }

            $entry[] = array(
                "iNetworkId" => $rs_list[$i]['iNetworkId'],
                "checkbox" => '<input type="checkbox" class="list" value="'.$rs_list[$i]['iNetworkId'].'"/>',
                "vName" => $rs_list[$i]['vName'],
                "iStatus" => $status,
                "actions" => ($action!="")?$action:"---"       
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
    $iNetworkId = $_POST['iNetworkId'];

    $arr_param['iNetworkId'] = $iNetworkId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."network_delete.json";
   
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

    $files = "";
    //echo "<pre>";print_r($_FILES);exit;
    if(isset($_FILES["vFile"])){
        $tmpfile = $_FILES["vFile"]['tmp_name'];
        $filename = basename($_FILES["vFile"]['name']);
        $files =  curl_file_create($tmpfile, $_FILES["vFile"]['type'], $filename);
    }

    $arr_param = array(
        "vName"     => $_POST['vName'],
        "vFile"     => $files,
        "iStatus"   => $_POST['iStatus'],
        "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."network_add.json";
    //echo $API_URL;
    //echo "<pre>";print_r(($arr_param));exit;
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
    if(isset($result_arr['iNetworkId'])){
        $result['msg'] = MSG_ADD;
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
    //echo "<pre>";print_r($_POST);exit();
    $file_name = $_POST['vFile_old'];
	$arr_param = array(
		'iNetworkId'  	=> $_POST['iNetworkId'],
        "vName"     => $_POST['vName'],
        "vFile"         => $file_name,
        "iStatus"   => $_POST['iStatus'],
        "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );
    //echo "<pre>";print_r(json_encode($arr_param));

    $API_URL = $site_api_url."network_edit.json";
    //echo "<pre>";print_r($API_URL);exit();
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

    curl_close($ch);  

    if($rs){
        $result['msg'] = MSG_UPDATE;
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
}else if($mode == "network_map"){
    //echo "<pre>";print_r($_REQUEST);exit();

    $iNetworkId = $_POST['iNetworkId']; 
    $arr_param["sessionId"] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['iNetworkId'] = $iNetworkId;
    //echo "<pre>";print_r($arr_param);
    $API_URL = $site_api_url."network_map_data.json";
   
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
    // echo "<pre>";print_r(json_encode($arr_param));exit();
    $response = curl_exec($ch);
    curl_close($ch);  
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);
    //echo "<pre>";print_r($response);exit();

    # Return jSON data.
    # -----------------------------------
    echo json_encode($result_arr['result']);
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

    $API_URL = $site_api_url."network_list.json";
    //echo "<pre>";print_r($API_URL);exit;
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
    // // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $file_name = "network_".time().".xlsx";

    if($cnt_export >0) {
        
        $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A1', 'Id')
                 ->setCellValue('B1', 'Name')
                 ->setCellValue('C1', 'Status');

        for($e=0; $e<$cnt_export; $e++) {

            $status = "";
            if($rs_export[$e]['iStatus'] == 0){
                $status = 'Inactive';
            }else if($rs_export[$e]['iStatus'] == 1){
                $status = 'Active';
            }else if($rs_export[$e]['iStatus'] == 2){
                $status = 'Created';
            }else if($rs_export[$e]['iStatus'] == 3){
                $status = 'Planning';
            }

            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iNetworkId'])
            ->setCellValue('B'.($e+2), $rs_export[$e]['vName'])
            ->setCellValue('C'.($e+2), $status);
         }
                        
        /* Set Auto width of each comlumn */
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        
        /* Set Font to Bold for each comlumn */
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

        /* Set Alignment of Selected Columns */
        $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Network');

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

$module_name = "Network List";
$module_title = "Network";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);

?>