<?php
//echo "<pre>";print_r($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise Circuit", 'List');
$access_group_var_delete = per_hasModuleAccess("Premise Circuit", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise Circuit", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise Circuit", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise Circuit", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Premise Circuit", 'CSV', 'N');
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

if($mode == "List"){
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    
    if($vOptions == "iNetworkId"){
        $searchId = $_REQUEST['networkId'];
    }else if($vOptions == "iConnectionTypeId"){
        $searchId = $_REQUEST['ConnectionTypeId'];
    }else if($vOptions == "vStatus"){
        $searchId = $_REQUEST['iStatus'];
    }
    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }

    if ($_REQUEST['premiseCircuitId'] != "") {
        $arr_param['premiseCircuitId'] = $_REQUEST['premiseCircuitId'];
    }

    if ($_REQUEST['premiseId'] != "") {
        $arr_param['premiseId'] = $_REQUEST['premiseId'];
    }

    if($_REQUEST['siteName'] != ""){
        $arr_param['siteName'] = $_REQUEST['siteName'];
        $arr_param['SiteFilterOpDD'] = $_REQUEST['SiteFilterOpDD'];
    }

    if ($_REQUEST['workorderId'] != "") {
        $arr_param['workorderId'] = $_REQUEST['workorderId'];
    }

    if ($_REQUEST['workorderTypeId'] != "") {
        $arr_param['workorderTypeId'] = $_REQUEST['workorderTypeId'];
    }

    if ($_REQUEST['circuitId'] != "") {
        $arr_param['circuitId'] = $_REQUEST['circuitId'];
    }

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."premise_circuit_list.json";
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
    // echo "<pre>"; print_r($result_arr);exit();

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_list = $result_arr['result']['data'];
    $ni = count($rs_list);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){ 
            $action = '';
            if($access_group_var_edit == "1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'premise_circuit/premise_circuit_edit&mode=Update&iPremiseCircuitId=' . $rs_list[$i]['iPremiseCircuitId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_list[$i]['iPremiseCircuitId'].');"><i class="fa fa-trash"></i></a>';
            }

            $vPremise = '';
            if($rs_list[$i]['iPremiseId'] > 0){
                $vPremiseURL = $site_url."premise/edit&mode=Update&iPremiseId=".$rs_list[$i]['iPremiseId'];
                $vPremise = "<a class='text-primary' href='".$vPremiseURL."' target='_blank'>".$rs_list[$i]['iPremiseId']." (".$rs_list[$i]['vPremiseName']."; ".$rs_list[$i]['vPremiseType'].")</a>";
            }

            $vWorkOrder = '';
            if($rs_list[$i]['iWOId'] > 0){
                $vWoURL = $site_url."service_order/workorder_add&mode=Update&iWOId=".$rs_list[$i]['iWOId'];

                $vWorkOrder = "<a class='text-primary' href='".$vWoURL."' target='_blank'>".$rs_list[$i]['iWOId']." (".$rs_list[$i]['vWorkOrderType'].")</a>";
            }
            

            $vStatus = '---';
            if($rs_list[$i]['iStatus'] == 1){
                $vStatus = '<span title="Created" class="btn btn-primary">Created</span>';
            }else if($rs_list[$i]['iStatus'] == 2){
                $vStatus = '<span title="In Progress" class="btn btn-secondary">In Progress</span>';
            }else if($rs_list[$i]['iStatus'] == 3){
                $vStatus = '<span title="Delayed" class="btn btn-warning">Delayed</span>';
            }else if($rs_list[$i]['iStatus'] == 4){
                $vStatus = '<span title="Connected" class="btn btn-success">Connected</span>';
            }else if($rs_list[$i]['iStatus'] == 5){
                $vStatus = '<span title="Active" class="btn btn-info">Active</span>';
            }else if($rs_list[$i]['iStatus'] == 6){
                $vStatus = '<span title="Suspended" class="btn btn-danger">Suspended</span>';
            }else if($rs_list[$i]['iStatus'] == 7){
                $vStatus = '<span title="Trouble" class="btn btn-dark">Trouble</span>';
            }else if($rs_list[$i]['iStatus'] == 8){
                $vStatus = '<span title="Disconnected" class="btn btn-danger">Disconnected</span>';
            }

            $entry[] = array(
                "iPremiseCircuitId"     => $rs_list[$i]['iPremiseCircuitId'],
                "vPremise"              => $vPremise,
                "vWorkOrder"            => $vWorkOrder,
                "vCircuitName"          => $rs_list[$i]['vCircuitName'],
                "vConnectionTypeName"   => $rs_list[$i]['vConnectionTypeName'],
                "vCarrierServices"      => $rs_list[$i]['vCarrierServices'],
                "vEquipment"            => $rs_list[$i]['vEquipment'],
                "iStatus"               => $vStatus,
                "actions"               => ($action!="")?$action:"---"       
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
    $iPremiseCircuitId = $_POST['iPremiseCircuitId'];

    $arr_param['iPremiseCircuitId'] = $iPremiseCircuitId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."premise_circuit_delete.json";
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
    //echo "<pre>";print_r($_POST);exit;
    $arr_param = array(
        "iPremiseId"        => $_POST['search_iPremiseId'],
        "iWOId"             => $_POST['search_iWOId'],
        "iCircuitId"        => $_POST['iCircuitId'],
        "iConnectionTypeId" => $_POST['iConnectionTypeId'],
        "iStatus"           => $_POST['iStatus'],
        "iLoginUserId"      => $_SESSION['sess_iUserId' . $admin_panel_session_suffix],
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."premise_circuit_add.json";
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
    if(isset($result_arr['iPremiseCircuitId'])){    
		$result['iPremiseId'] = $result_arr['iPremiseId'];
        $result['msg'] = MSG_ADD;
        $result['error'] = 0 ;
        $result['matching_network'] = $result_arr['matching_network'];
    }else{
        //$result['msg'] = MSG_ADD_ERROR;
		$result['iPremiseId'] = $result_arr['iPremiseId'];
        $result['msg'] = $result_arr['Message'];
        $result['error']= 1 ;
        $result['matching_network'] = $result_arr['matching_network'];
    }
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------  
}else if($mode == "Update"){
    $result =array();

    $arr_param = array(
        'iPremiseCircuitId' => $_POST['iPremiseCircuitId'],
        "iPremiseId"        => $_POST['search_iPremiseId'],
        "iWOId"             => $_POST['search_iWOId'],
        "iCircuitId"        => $_POST['iCircuitId'],
        "iConnectionTypeId" => $_POST['iConnectionTypeId'],
        "iStatus"           => $_POST['iStatus'],
        "iLoginUserId"      => $_SESSION['sess_iUserId' . $admin_panel_session_suffix],
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );
    
    $API_URL = $site_api_url."premise_circuit_edit.json";
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
    if($result_arr && $result_arr['matching_network'] == 1){
        $result['iPremiseId'] = $result_arr['iPremiseId'];
        $result['msg'] = MSG_UPDATE;
        $result['error']= 0 ;
        $result['matching_network'] = $result_arr['matching_network'];
    }else{
		$result['iPremiseId'] = $result_arr['iPremiseId'];
        $result['msg'] = MSG_UPDATE_ERROR;
        $result['error']= 1 ;
        $result['matching_network'] = $result_arr['matching_network'];
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

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."premise_circuit_list.json";
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
    $file_name = "premise_circuit_".time().".xlsx";

    if($cnt_export >0) {
        $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A1', 'Id')
                 ->setCellValue('B1', 'Premise')
                 ->setCellValue('C1', 'WorkOrder')
                 ->setCellValue('D1', 'Circuit Name')
                 ->setCellValue('E1', 'Connetion Type')
                 ->setCellValue('F1', 'Carrier Services')
                 ->setCellValue('G1', 'Equipment')
                 ->setCellValue('H1', 'Status');

    
        for($e=0; $e<$cnt_export; $e++) {
            $vPremise = $rs_export[$e]['iPremiseId']." (".$rs_export[$e]['vPremiseName']."; ".$rs_export[$e]['vPremiseType'].")";
            $vWorkOrder = $rs_export[$e]['iWOId']." (".$rs_export[$e]['vWorkOrderType'].")";

            $vStatus = '---';
            if($rs_export[$e]['iStatus'] == 1){
                $vStatus = 'Created';
            }else if($rs_export[$e]['iStatus'] == 2){
                $vStatus = 'In Progress';
            }else if($rs_export[$e]['iStatus'] == 3){
                $vStatus = 'Delayed';
            }else if($rs_export[$e]['iStatus'] == 4){
                $vStatus = 'Connected';
            }else if($rs_export[$e]['iStatus'] == 5){
                $vStatus = 'Active';
            }else if($rs_export[$e]['iStatus'] == 6){
                $vStatus = 'Suspended';
            }else if($rs_export[$e]['iStatus'] == 7){
                $vStatus = 'Trouble';
            }else if($rs_export[$e]['iStatus'] == 8){
                $vStatus = 'Disconnected';
            }

            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iPremiseCircuitId'])
            ->setCellValue('B'.($e+2), $vPremise)
            ->setCellValue('C'.($e+2), $vWorkOrder)
            ->setCellValue('D'.($e+2), $rs_export[$e]['vCircuitName'])
            ->setCellValue('E'.($e+2), $rs_export[$e]['vConnectionTypeName'])
            ->setCellValue('F'.($e+2), $rs_export[$e]['vCarrierServices'])
            ->setCellValue('G'.($e+2), $rs_export[$e]['vEquipmentExcel'])
            ->setCellValue('H'.($e+2), $vStatus);
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
        $objPHPExcel->getActiveSheet()->setTitle('PremiseCircuit');

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
## --------------------------------

/*-------------------------- WorkOrder Type -------------------------- */
$wotype_param = array();
$wotype_param['iStatus'] = '1';
$wotype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$wotypeAPI_URL = $site_api_url."workorder_type_dropdown.json";
//echo $wotypeAPI_URL." ".json_encode($wotype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $wotypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($wotype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_wotype = $res['result'];
$smarty->assign("rs_wotype", $rs_wotype);
//echo "<pre>";print_r($rs_carrier);exit;
/*-------------------------- WorkOrder Type -------------------------- */

/*-------------------------- WorkOrder Type -------------------------- */
$circuit_param = array();
$circuit_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$circuitAPI_URL = $site_api_url."circuit_dropdown.json";
//echo $circuitAPI_URL." ".json_encode($circuit_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $circuitAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($circuit_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$responsecircuit = curl_exec($ch);
curl_close($ch);  
$rescircuit = json_decode($responsecircuit, true);
$rs_circuit = $rescircuit['result'];
$smarty->assign("rs_circuit", $rs_circuit);
//echo "<pre>";print_r($rs_circuit);exit;
/*-------------------------- WorkOrder Type -------------------------- */
$module_name = "Premise Circuit List";
$module_title = "Premise Circuit";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);
?>