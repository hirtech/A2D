<?php
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("User", 'List');
$access_group_var_delete = per_hasModuleAccess("User", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("User", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("User", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("User", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("User", 'CSV', 'N');
# ----------- Access Rule Condition -----------
include_once($function_path."mail.inc.php");
include_once($function_path."image.inc.php");
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
if($mode == "List") {
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));

    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }

    $arr_param['vName']         = trim($_REQUEST['vName']);
    $arr_param['vNameDD']       = trim($_REQUEST['vNameDD']);
    $arr_param['vEmail']        = trim($_REQUEST['vEmail']);
    $arr_param['vEmailDD']      = trim($_REQUEST['vEmailDD']);
    $arr_param['vUsername']     = trim($_REQUEST['vUsername']);
    $arr_param['vUsernameDD']   = trim($_REQUEST['vUsernameDD']);
    $arr_param['iDepartmentId'] = $_REQUEST['iDepartmentId'];
    $arr_param['iAGroupId']     = $_REQUEST['iAGroupId'];

    $arr_param['page_length']   = $page_length;
    $arr_param['start']         = $start;
    $arr_param['sEcho']         = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir']           = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."user_list.json";
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
    $rs_user = $result_arr['result']['data'];
    $ni = count($rs_user);
    $entry =array();
    if ($ni > 0) {
        for ($i = 0; $i < $ni; $i++) {
            $actions = $delete = "";
            if($access_group_var_edit == "1"){
                $actions .= '<a class="btn btn-outline-secondary" title="Edit" href="' . $site_url . 'user/edit&mode=Update&iUserId=' . $rs_user[$i]['iUserId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == 1) {
                $actions .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_user[$i]['iUserId'].');"><i class="fa fa-trash"></i></a>';
            }
            $entry[] = array(
                "checkbox"       => $rs_user[$i]['iUserId'],
                "name"           => gen_strip_slash($rs_user[$i]['vFirstName']) . " " . gen_strip_slash($rs_user[$i]['vLastName']),
                "vEmail"         => $rs_user[$i]['vEmail'],
                'vUsername'      => gen_strip_slash($rs_user[$i]['vUsername']),
                'vDepartment'    => $user_department,
                'vAccessGroup'   => gen_strip_slash($rs_user[$i]['vAccessGroup']),
                'vLoginHistory'  => '<a class="btn btn-outline-primary" title="View" href="' . $site_url . 'login_history/list&iUserId=' . $rs_user[$i]['iUserId'] . '"  target="_blank"><i class="fa fa-eye"></i></a>',
                'dDate'          =>  date_getDateTime($rs_user[$i]['dDate']),
                'iStatus'        =>'<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_user[$i]['iStatus']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_user[$i]['iStatus'])].'">&nbsp;</span>',
                "actions"        => ($actions == "")?"---":$actions
            );
        }
    }
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}else if($mode == "DuplicateUsernameCheck") {
    $result = array();
    $dupli_arr_param = array();
    $dupli_arr_param['vUsername'] = $_POST['vUsername'];
    $dupli_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $dupli_API_URL = $site_api_url."check_duplicate_user.json";
    //echo $dupli_API_URL. " ".json_encode($dupli_arr_param);exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $dupli_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dupli_arr_param));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));
    $response = curl_exec($ch);
    curl_close($ch);  
    $result_arr = json_decode($response, true);
    $duplicate_check_tot = $result_arr['duplicate_check_tot'];


    $jsonData = array('total' => $duplicate_check_tot);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------   
}else if($mode == "Update"){   
    $file_name = $file_msg ="";
    if($_FILES['vImage']['name'] != ""){
        $file_arr = img_fileUpload("vImage", $user_path, '', $valid_ext = array('jpg','jpeg','gif','png'));
        $file_name = $file_arr[0];
        $file_msg = $file_arr[1];
    }else{
       $file_name = $_POST['vImage_old'];
    }

    $arr_param = array(
        "iUserId"           => $_POST['iUserId'],
        "iAGroupId"         => $_POST['iAGroupId'],
        "iDepartmentId"     => $_POST['iDepartmentId'],
        "iZoneId"           => $_POST['iZoneId'],
        "vFirstName"        => addslashes($_POST['vFirstName']),
        "vLastName"         => addslashes($_POST['vLastName']),
        "vUsername"         => addslashes($_POST['vUsername']),
        "vPassword"         => $_POST['vPassword'],
        "vEmail"            =>  $_POST['vEmail'],
        "vFromIP"           => getIP(),
        "iStatus"           => $_POST['iStatus'],
        "iType"             => $_POST['iType'],
        "dDate"             => date_getSystemDateTime(),
        "vCompanyName"      => addslashes($_POST['vCompanyName']),
        "vCompanyNickName"  => addslashes($_POST['vCompanyNickName']),
        "vAddress1"         => addslashes($_POST['vAddress1']),
        "vAddress2"         => addslashes($_POST['vAddress2']),
        "vStreet"           => addslashes($_POST['vStreet']),
        "vCrossStreet"      => addslashes($_POST['vCrossStreet']),
        "iZipcode"          => $_POST['iZipcode'],
        "iStateId"          => $_POST['iStateId'],
        "iCountyId"         => $_POST['iCountyId'],
        "iCityId"           => $_POST['iCityId'],
        "iZoneId"           => $_POST['iZoneId'],
        "vLatitude"         => $_POST['vLatitude'],
        "vLongitude"        => $_POST['vLongitude'],
        "vPhone"            => addslashes($_POST['vPhone']),
        "vCell"             => addslashes($_POST['vCell']),
        "vNickName"         => "",
        "vFax"              => addslashes($_POST['vFax']),
        "vADPFileNumber"    => addslashes($_POST['vADPFileNumber']),
        "sSalt"             => addslashes($encryptedPassword['salt']),
        "vImage"            => $file_name,
        "networkId_arr"     => $_POST['networkId_arr'],
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."user_edit.json";
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
    if(isset($result_arr['iUserId'])){
        if($_SESSION["sess_iUserId" . $admin_panel_session_suffix] == $_POST['iUserId']) {
            $_SESSION["sess_vImage_url" . $admin_panel_session_suffix] = $user_url.$file_name;
        }
        $result['error'] = 0 ;
        $result['msg'] = MSG_UPDATE.$file_msg;
    }else{
        $result['error'] = 1 ;
        $result['msg'] = MSG_UPDATE_ERROR.$file_msg;
    }

    echo json_encode($result);
    hc_exit();
    # -----------------------------------
}else if($mode == "Add") {
    $result = array();
    $dupli_arr_param = array();
    $dupli_arr_param['vUsername'] = $_POST['vUsername'];
    $dupli_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $dupli_API_URL = $site_api_url."check_duplicate_user.json";
    //echo $dupli_API_URL. " ".json_encode($dupli_arr_param);exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $dupli_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dupli_arr_param));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));
    $response = curl_exec($ch);
    curl_close($ch);  
    $result_arr = json_decode($response, true);
    $duplicate_check_tot = $result_arr['duplicate_check_tot'];

    if ($duplicate_check_tot == 0) {
        $file_name = $file_msg = "";
        if($_FILES['vImage']['name'] != ""){
            $file_arr = img_fileUpload("vImage", $user_path, '', $valid_ext = array('jpg','jpeg','gif','png'));
            $file_msg = $file_arr[1];
        }
        $encryptedPassword = encrypt_password($_POST['vPassword']);
        //echo "<pre>";print_r($_POST);exit;
        $arr_param = array(
            "iAGroupId"         => $_POST['iAGroupId'],
            "iDepartmentId"     => $_POST['iDepartmentId'],
            "iZoneId"           => $_POST['iZoneId'],
            "vFirstName"        => addslashes($_POST['vFirstName']),
            "vLastName"         => addslashes($_POST['vLastName']),
            "vUsername"         => addslashes($_POST['vUsername']),
            "vPassword"         => $encryptedPassword['encryptedPassword'],
            "vEmail"            => $_POST['vEmail'],
            "vFromIP"           => getIP(),
            "iStatus"           => $_POST['iStatus'],
            "iType"             => $_POST['iType'],
            "dDate"             => date_getSystemDateTime(),
            "vCompanyName"      => addslashes($_POST['vCompanyName']),
            "vCompanyNickName"  => addslashes($_POST['vCompanyNickName']),
            "vAddress1"         => addslashes($_POST['vAddress1']),
            "vAddress2"         => addslashes($_POST['vAddress2']),
            "vStreet"           => addslashes($_POST['vStreet']),
            "vCrossStreet"      => addslashes($_POST['vCrossStreet']),
            "iZipcode"          => $_POST['iZipcode'],
            "iStateId"          => $_POST['iStateId'],
            "iCountyId"         => $_POST['iCountyId'],
            "iCityId"           => $_POST['iCityId'],
            "iZoneId"           => $_POST['iZoneId'],
            "vLatitude"         => $_POST['vLatitude'],
            "vLongitude"        => $_POST['vLongitude'],
            "vPhone"            => addslashes($_POST['vPhone']),
            "vCell"             => addslashes($_POST['vCell']),
            "vNickName"         => "",
            "vFax"              => addslashes($_POST['vFax']),
            "vADPFileNumber"    => addslashes($_POST['vADPFileNumber']),
            "sSalt"             => addslashes($encryptedPassword['salt']),
            "vImage"            => $file_name,
            "networkId_arr"     => $_POST['networkId_arr'],
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );

        $API_URL = $site_api_url."user_add.json";
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
        if(isset($result_arr['iUserId'])){
            if ($result_arr['iUserId'] != "" && $_POST['notify'] == 1) {
                sendSystemMail("User", "Registration", $result_arr['iUserId']);
            }
            $result['msg'] = MSG_ADD." ".$file_msg;
            $result['error']= 0 ;
        }else{
            //$result['msg'] = MSG_ADD_ERROR;
            $result['msg'] = $result_arr['Message'];
            $result['error']= 1 ;
        }
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}else if($mode == "Delete") {
    $iUserId = $_POST['iUserId'];
    $result = array();
    $arr_param = array();
    $arr_param['iUserId']   = $iUserId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."user_delete.json";
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
}else if($mode == "Excel"){
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));

    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }

    $arr_param['vName']         = trim($_REQUEST['vName']);
    $arr_param['vNameDD']       = trim($_REQUEST['vNameDD']);
    $arr_param['vEmail']        = trim($_REQUEST['vEmail']);
    $arr_param['vEmailDD']      = trim($_REQUEST['vEmailDD']);
    $arr_param['vUsername']     = trim($_REQUEST['vUsername']);
    $arr_param['vUsernameDD']   = trim($_REQUEST['vUsernameDD']);
    $arr_param['iDepartmentId'] = $_REQUEST['iDepartmentId'];
    $arr_param['iAGroupId']     = $_REQUEST['iAGroupId'];

    $arr_param['page_length']   = $page_length;
    $arr_param['start']         = $start;
    $arr_param['sEcho']         = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir']           = $dir;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."user_list.json";
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
    // echo "<pre>";print_r($rs_export);exit();
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    //include_once($class_path.'PHPExcel-1.8/PHPExcel.php'); 
    // // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $file_name = "user_".time().".xlsx";

    if($cnt_export >0) {

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Id')
        ->setCellValue('B1', 'Name')
        ->setCellValue('C1', 'Email')
        ->setCellValue('D1', 'User Name')
        ->setCellValue('E1', 'Department')
        ->setCellValue('F1', 'AccessGroup')
        ->setCellValue('G1', 'Company')
        ->setCellValue('H1', 'Address')
        ->setCellValue('I1', 'Area')
        ->setCellValue('J1', 'State')
        ->setCellValue('K1', 'County')
        ->setCellValue('L1', 'City')
        ->setCellValue('M1', 'Zip Code')
        ->setCellValue('N1', 'Phone')
        ->setCellValue('O1', 'Cell')
        ->setCellValue('P1', 'Fax')
        ->setCellValue('Q1', 'Last Login');

    for($e=0; $e<$cnt_export; $e++) {

        $name = gen_strip_slash($rs_export[$e]['vFirstName']) . ' ' . gen_strip_slash($rs_export[$e]['vLastName']);
        $dLastAccess = date_getDateTime($rs_export[$e]['dLastAccess']);

        $objPHPExcel->getActiveSheet()
        ->setCellValue('A'.($e+2), $rs_export[$e]['iUserId'])
        ->setCellValue('B'.($e+2), $name)
        ->setCellValue('C'.($e+2), $rs_export[$e]['vEmail'])
        ->setCellValue('D'.($e+2), $rs_export[$e]['vUsername'])
        ->setCellValue('E'.($e+2), $rs_export[$e]['user_department'])
        ->setCellValue('F'.($e+2), $rs_export[$e]['vAccessGroup'])
        ->setCellValue('G'.($e+2), $rs_export[$e]['vCompanyName'])
        ->setCellValue('H'.($e+2), $rs_export[$e]['vAddress'])
        ->setCellValue('I'.($e+2), $rs_export[$e]['vArea'])
        ->setCellValue('J'.($e+2), $rs_export[$e]['vState'])
        ->setCellValue('K'.($e+2), $rs_export[$e]['vCountry'])
        ->setCellValue('L'.($e+2), $rs_export[$e]['vCity'])
        ->setCellValue('M'.($e+2), $rs_export[$e]['vZipCode'])
        ->setCellValue('N'.($e+2), $rs_export[$e]['vPhone'])
        ->setCellValue('O'.($e+2), $rs_export[$e]['vCell'])
        ->setCellValue('P'.($e+2), $rs_export[$e]['vFax'])
        ->setCellValue('Q'.($e+2), $dLastAccess);
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
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

    /* Set Font to Bold for each comlumn */
    $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->setBold(true);


    /* Set Alignment of Selected Columns */
    $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('User');

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

// access group dropdown
$access_group_arr_param = array();
$access_group_arr_param = array(
    "iState"    => 1,
    "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$access_group_API_URL = $site_api_url."access_group_dropdown.json";
//echo $access_group_API_URL." ".json_encode($access_group_arr_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $access_group_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($access_group_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_access_group = curl_exec($ch);
curl_close($ch); 
$rs_access_group1 = json_decode($response_access_group, true); 
$rs_agroup = $rs_access_group1['result'];
$smarty->assign("rs_agroup", $rs_agroup);

// department dropdown
$department_arr_param = array();
$department_arr_param = array(
    "iState"    => 1,
    "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$department_API_URL = $site_api_url."department_dropdown.json";
//echo $department_API_URL." ".json_encode($department_arr_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $department_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($department_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_department = curl_exec($ch);
curl_close($ch); 
$rs_department1 = json_decode($response_department, true); 
$rs_department = $rs_department1['result'];
$smarty->assign("rs_department", $rs_department);


$module_name = "User List";
$module_title = "User";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("iAGroupId", $_GET['iAGroupId']);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);

?>