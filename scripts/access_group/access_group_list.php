<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Access Group", 'List');
$access_group_var_delete = per_hasModuleAccess("Access Group", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Access Group", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Access Group", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Access Group", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Access Group", 'CSV', 'N');
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

if($mode == "List"){
    $where_arr = array();
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
    $API_URL = $site_api_url."access_group_list.json";
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
    //
    $response = curl_exec($ch);
    curl_close($ch);  
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);
    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_data = $result_arr['result']['data'];
    // Paging Total Records
    $ni = count($rs_data);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_data[$i]['iAGroupId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1' && $rs_data[$i]['iDefault'] != 1) {
                $action .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_data[$i]['iAGroupId'].');"><i class="fa fa-trash"></i></a>';
            }

            $checkbox = '';
            if($rs_data[$i]['iDefault'] != 1){
               $checkbox = '<input type="checkbox" class="list" value="'.$rs_data[$i]['iAGroupId'].'"/><input type="hidden" name="" value="'.$rs_data[$i]['iAGroupId'].'">';
           
            }

            $vAccessType = '';

            if($rs_data[$i]['iAccessType'] == 1){
                $vAccessType = "Technician";
            }else if($rs_data[$i]['iAccessType'] == 2){
                $vAccessType = "Exec";
            }else if($rs_data[$i]['iAccessType'] == 3){
                $vAccessType = "Admin";
            }else if($rs_data[$i]['iAccessType'] == 4){
                $vAccessType = "Management";
            }else if($rs_data[$i]['iAccessType'] == 5){
                $vAccessType = "Gov't";
            }else if($rs_data[$i]['iAccessType'] == 6){
                $vAccessType = "Sales";
            }else if($rs_data[$i]['iAccessType'] == 7){
                $vAccessType = "Investor";
            }else if($rs_data[$i]['iAccessType'] == 8){
                $vAccessType = "Partner";
            }else if($rs_data[$i]['iAccessType'] == 9){
                $vAccessType = "Customer";
            }else if($rs_data[$i]['iAccessType'] == 10){
                $vAccessType = "Other";
            }


            $entry[] = array(
               // "checkbox" => $checkbox,
                "checkbox" =>$rs_data[$i]['iAGroupId'].'<input type="hidden" id="ag_id_'.$rs_data[$i]['iAGroupId'].'" value="'.$rs_data[$i]['iAGroupId'].'">',
                "vAccessGroup" =>gen_strip_slash($rs_data[$i]['vAccessGroup']).'<input type="hidden" id="ag_name_'.$rs_data[$i]['iAGroupId'].'" value="'.$rs_data[$i]['vAccessGroup'].'">',
                "iAccessType" =>gen_strip_slash($vAccessType).'<input type="hidden" id="ag_iAccessType_'.$rs_data[$i]['iAGroupId'].'" value="'.$rs_data[$i]['iAccessType'].'">',
                "tDescription" =>gen_strip_slash($rs_data[$i]['tDescription']).'<input type="hidden" id="ag_tdesc_'.$rs_data[$i]['iAGroupId'].'" value="'.$rs_data[$i]['tDescription'].'">',
                'vManage'=>'<a class="btn btn-outline-primary link-view" href="'.$site_url.'access_group/access_group_add&mode=Manage&iAGroupId='.$rs_data[$i]['iAGroupId'].'">Manage</a>',
                'iStatus'=>'<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_data[$i]['iStatus']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_data[$i]['iStatus'])].'">&nbsp;</span><input type="hidden" id="ag_status_'.$rs_data[$i]['iAGroupId'].'" value="'.$rs_data[$i]['iStatus'].'">',
                "actions" => ($action == "")?"---":$action
            );
        }
        
    }
    $jsonData['aaData'] = $entry;
    // echo "<pre>";print_r($jsonData);exit();
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}else if($mode == "Delete"){
    $result = array();
    $iAGroupId = $_POST['iAGroupId'];
    $arr_param = array();
    $arr_param['iAGroupId'] = $iAGroupId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."access_group_delete.json";
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
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_DELETE_ERROR;
        $result['error']= 1 ;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # ----------------------------------- 
}else if($mode == "Add"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        $arr_param = array(
            "vAccessGroup"  => trim($_POST['vAccessGroup']),
            "iAccessType"   => trim($_POST['iAccessType']),
            "tDescription"  => trim($_POST['tDescription']),
            "iDefault"      => 0,            
            "iStatus"       => isset($_POST['iStatus'])?$_POST['iStatus']:"0",            
            "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        );
        $API_URL = $site_api_url."access_group_add.json";
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
        $response = curl_exec($ch);
        curl_close($ch); 
        $result_arr = json_decode($response, true); 
        if(!empty($result_arr)){
            $result['iAGroupId'] = $result_arr['iAGroupId'];
            $result['msg'] = MSG_ADD;
            $result['error']= 0 ;
        }else{
            $result['msg'] = MSG_ADD_ERROR;
            $result['error']= 1 ;
        }
    }else {
        $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------  
}else if($mode == "Update"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        $arr_param = array(
            "iAGroupId"     => trim($_POST['iAGroupId']),
            "vAccessGroup"  => trim($_POST['vAccessGroup']),
            "iAccessType"   => trim($_POST['iAccessType']),
            "tDescription"  => trim($_POST['tDescription']),            
            "iDefault"      => 0,            
            "iStatus"       => isset($_POST['iStatus'])?$_POST['iStatus']:"0",            
            "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        );
        $API_URL = $site_api_url."access_group_edit.json";
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
        $response = curl_exec($ch);
        curl_close($ch); 
        $result_arr = json_decode($response, true); 
        if(!empty($result_arr)){
            $result['iAGroupId'] = $result_arr['iAGroupId'];
            $result['msg'] = MSG_UPDATE;
            $result['error']= 0 ;
        }else{
            $result['msg'] = MSG_UPDATE_ERROR;
            $result['error']= 1 ;
        }
    }else {
        $result['msg'] = MSG_UPDATE_ERROR;
        $result['error']= 1 ;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}else if($mode == "Manage_Role"){
    //echo "<pre>";print_r($_POST['eList']);exit;
    $arr_param = array();
    $arr_param = array(
        "iAGroupId"     => $_POST['iAGroupId'],
        "eList"         => $_POST['eList'],
        "eAdd"          => $_POST['eAdd'],
        "eEdit"         => $_POST['eEdit'],
        "eDelete"       => $_POST['eDelete'],
        "eStatus"       => $_POST['eStatus'],
        "eRespond"      => $_POST['eRespond'],
        "eCSV"          => $_POST['eCSV'],
        "ePDF"          => $_POST['ePDF'],
        "eCalsurv"      => $_POST['eCalsurv'],
        "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
    );
    $API_URL = $site_api_url."access_group_manage_role.json";
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
    $response = curl_exec($ch);
    curl_close($ch); 
    $result_arr = json_decode($response, true); 
    //echo "<pre>";print_r($result_arr['result']);exit;
    if(!empty($result_arr)){
        $result['msg']      = $result_arr['result']['msg'];
        $result['error']    = $result_arr['result']['error'] ;
    }else{
        $result['msg'] = MSG_ALL_CHANGES_APPLIED_SUCCESSFULLY_ERROR;
        $result['error']= 1 ;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
}else if($mode== "Excel"){
    $where_arr = array();
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
    $API_URL = $site_api_url."access_group_list.json";
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
    //
    $response = curl_exec($ch);
    curl_close($ch);  
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);

    $rs_export = $result_arr['result']['data'];
    $cnt_export = count($rs_export);
      
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
        // // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $file_name = "access_group_".time().".xlsx";

        if($cnt_export >0) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                     ->setCellValue('B1', 'Access Group')
                     ->setCellValue('C1', 'Access Type')
                     ->setCellValue('D1', 'Description')
                     ->setCellValue('E1', 'Status');

            for($e=0; $e<$cnt_export; $e++) {

               $vAccessType = '';
                if($rs_export[$e]['iAccessType'] == 1){
                    $vAccessType = "Sales";
                }else if($rs_export[$e]['iAccessType'] == 2){
                    $vAccessType = "Technician";
                }else if($rs_export[$e]['iAccessType'] == 3){
                    $vAccessType = "Carrier";
                }
               
                $objPHPExcel->getActiveSheet()
                        ->setCellValue('A'.($e+2), $rs_export[$e]['iAGroupId'])
                        ->setCellValue('B'.($e+2), $rs_export[$e]['vAccessGroup'])
                        ->setCellValue('C'.($e+2), $vAccessType)
                        ->setCellValue('D'.($e+2), $rs_export[$e]['tDescription'])
                        ->setCellValue('E'.($e+2), gen_status($rs_export[$e]['iStatus']));

            }
                            
            /* Set Auto width of each comlumn */
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            
            
            /* Set Font to Bold for each comlumn */
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
            

            /* Set Alignment of Selected Columns */
            $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('AccessGroup');

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

$module_name = "Access Group List";
$module_title = "Access Group";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);