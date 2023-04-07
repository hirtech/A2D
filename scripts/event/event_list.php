<?php
//echo "<pre>";print_r($_POST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Event", 'List');
$access_group_var_delete = per_hasModuleAccess("Event", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Event", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Event", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Event", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Event", 'CSV', 'N');
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

$iEventId = $_POST['iEventId'];

if($mode == "List"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }
    
    $arr_param['iSCampaignBy']      = $_POST['iSCampaignBy'];
    $arr_param['iSPremiseId']       = $_POST['iSPremiseId'];
    $arr_param['vSPremiseNameDD']   = $_POST['vSPremiseNameDD'];
    $arr_param['vSPremiseName']     = trim($_POST['vSPremiseName']);
    $arr_param['iSZoneId']          = $_POST['iSZoneId'];
    $arr_param['vSZipcodeDD']       = $_POST['vSZipcodeDD'];
    $arr_param['vSZipcode']         = trim($_POST['vSZipcode']);
    $arr_param['vSCityDD']          = $_POST['vSCityDD'];
    $arr_param['vSCity']            = trim($_POST['vSCity']);
    $arr_param['iSNetworkId']       = $_POST['iSNetworkId'];
    $arr_param['iSStatus']          = $_POST['iSStatus'];
    $arr_param['dSCompletedDate']   = $_POST['dSCompletedDate'];

    $arr_param['page_length']       = $page_length;
    $arr_param['start']             = $start;
    $arr_param['sEcho']             = $sEcho;
    $arr_param['display_order']     = $display_order;
    $arr_param['dir']               = $dir;

    $arr_param['access_group_var_edit']     = $access_group_var_edit;
    $arr_param['access_group_var_delete']   = $access_group_var_delete;

    $arr_param['sessionId']     = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    $API_URL = $site_api_url."event_list.json";
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
    //
    $response = curl_exec($ch);
    curl_close($ch);  
    $result_arr = json_decode($response, true);

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_event = $result_arr['result']['data'];
    //echo "<pre>";print_r($result_arr);exit;
	$ni = count($rs_event);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if($access_group_var_edit == "1"){
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'event/event_add&mode=Update&iEventId=' . $rs_event[$i]['iEventId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_event[$i]['iEventId'].');"><i class="fa fa-trash"></i></a>';
            }

            $iStatus = '---';

            if($rs_event[$i]['iStatus'] == 1){
               $iStatus = "Not Started"; 
            }else if($rs_event[$i]['iStatus'] == 2){
               $iStatus = "In Progress"; 
            }else if($rs_event[$i]['iStatus'] == 3){
               $iStatus = "Completed"; 
            }

            $entry[] = array(
                "iEventId"          => $rs_event[$i]['iEventId'],
                "vEventType"        => $rs_event[$i]['vEventType'],
                "vCampaignBy"       => $EVENT_CAMPAIGN_BY_ARR[$rs_event[$i]['iCampaignBy']],
                "vCampaignCoverage" => $rs_event[$i]['vCampaignCoverage'],
                "iStatus"           => '<span class="btn btn-'.$status_color[$iStatus].'">'.$iStatus.'<span>',
                "dCompletedDate"    => date_getDateTimeDDMMYYYY($rs_event[$i]['dCompletedDate']),
                "actions"           => ($action != "") ? $action : "---"
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
    $iEventId = $_POST['iEventId'];
    
    $arr_param['iEventId']      = $iEventId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."event_delete.json";
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

    $rs_tot = curl_exec($ch);
    //echo "<pre>";print_r($rs);exit();  
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
}else if($mode == "Update"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        $arr_param = array(
            "iEventId"          => $_POST['iEventId'],
            "iEventTypeId"      => $_POST['iEventTypeId'],
            "iCampaignBy"       => $_POST['iCampaignBy'],
            "iStatus"           => $_POST['iStatus'],
            "dCompletedDate"    => $_POST['dCompletedDate'],
            "iPremiseId"        => $_POST['iPremiseId'],
            "iZoneId"           => $_POST['iZoneId'],
            "iZipcode"          => $_POST['iZipcode'],
            "iCityId"           => $_POST['iCityId'],
            "iNetworkId"        => $_POST['iNetworkId'],
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );
        $API_URL = $site_api_url."event_edit.json";
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

        $rs = curl_exec($ch);
       //echo "<pre>";print_r($rs);exit();  
        curl_close($ch);  

        if($rs){
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
     //echo "<pre>";print_r($result);exit(); 

    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------  
}else if($mode == "Add"){
    $arr_param = array();
    //echo "<pre>";print_r($_POST);exit;
    if (isset($_POST) && count($_POST) > 0) {
        $arr_param = array(
            "iEventTypeId"      => $_POST['iEventTypeId'],
            "iCampaignBy"       => $_POST['iCampaignBy'],
            "iStatus"           => $_POST['iStatus'],
            "dCompletedDate"    => $_POST['dCompletedDate'],
            "iPremiseId"        => $_POST['iPremiseId'],
            "iZoneId"           => $_POST['iZoneId'],
            "iZipcode"          => $_POST['iZipcode'],
            "iCityId"           => $_POST['iCityId'],
            "iNetworkId"        => $_POST['iNetworkId'],
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );

        $API_URL = $site_api_url."event_add.json";
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

        $iTLSId = curl_exec($ch);
        //echo "<pre>";print_r($iTLSId);exit();  
        curl_close($ch);  

        if($iTLSId){
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
    
    $arr_param['iSCampaignBy']      = $_POST['iSCampaignBy'];
    $arr_param['iSPremiseId']       = $_POST['iSPremiseId'];
    $arr_param['vSPremiseNameDD']   = $_POST['vSPremiseNameDD'];
    $arr_param['vSPremiseName']     = trim($_POST['vSPremiseName']);
    $arr_param['iSZoneId']          = $_POST['iSZoneId'];
    $arr_param['vSZipcodeDD']       = $_POST['vSZipcodeDD'];
    $arr_param['vSZipcode']         = trim($_POST['vSZipcode']);
    $arr_param['vSCityDD']          = $_POST['vSCityDD'];
    $arr_param['vSCity']            = trim($_POST['vSCity']);
    $arr_param['iSNetworkId']       = $_POST['iSNetworkId'];
    $arr_param['iSStatus']          = $_POST['iSStatus'];
    $arr_param['dSCompletedDate']   = $_POST['dSCompletedDate'];

    $arr_param['page_length']       = $page_length;
    $arr_param['start']             = $start;
    $arr_param['sEcho']             = $sEcho;
    $arr_param['display_order']     = $display_order;
    $arr_param['dir']               = $dir;

    $arr_param['sessionId']     = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    $API_URL = $site_api_url."event_list.json";
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
    //
    $response = curl_exec($ch);
    curl_close($ch);  
    $result_arr = json_decode($response, true);
    
    $rs_export = $result_arr['result']['data'];
    $cnt_export = count($rs_export);
      
    include_once($class_path.'PHPExcel/PHPExcel.php'); 
    // // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $file_name = "event_".time().".xlsx";

    if($cnt_export >0) {
        $iStatus = '---';
        if($rs_event[$i]['iStatus'] == 1){
           $iStatus = "Not Started"; 
        }else if($rs_event[$i]['iStatus'] == 2){
           $iStatus = "In Progress"; 
        }else if($rs_event[$i]['iStatus'] == 3){
           $iStatus = "Completed"; 
        }

        $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A1', 'Id')
                 ->setCellValue('B1', 'Event Type')
                 ->setCellValue('C1', 'Campaign By')
                 ->setCellValue('D1', 'Campaign Coverage')
                 ->setCellValue('E1', 'Status')
                 ->setCellValue('F1', 'Date Completed');

        for($e=0; $e<$cnt_export; $e++) {

            $vCampaignCoverage = str_replace("<br />", "\n", $rs_export[$e]['vCampaignCoverage']);

            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iEventId'])
            ->setCellValue('B'.($e+2), $rs_export[$e]['vEventType'])
            ->setCellValue('C'.($e+2), $EVENT_CAMPAIGN_BY_ARR[$rs_export[$e]['iCampaignBy']])
            ->setCellValue('D'.($e+2), ($vCampaignCoverage))
            ->setCellValue('E'.($e+2), $rs_export[$e]['iStatus'])
            ->setCellValue('F'.($e+2), date_getDateTimeDDMMYYYY($rs_export[$e]['dCompletedDate']));   
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
        $objPHPExcel->getActiveSheet()->setTitle('Event');

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

/*-------------------------- Zone -------------------------- */
$zone_arr_param = array();
$zone_arr_param['iStatus']   = 1;
$zone_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$ZONE_API_URL = $site_api_url."zone_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ZONE_API_URL);
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
$res_zone = json_decode($response_zone, true);
$smarty->assign("rs_zone", $res_zone['result']);
//echo "<pre>";print_r($res_zone['result']);exit;
/*-------------------------- Zone -------------------------- */

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

$module_name = "Event List";
$module_title = "Event";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("EVENT_CAMPAIGN_BY_ARR", $EVENT_CAMPAIGN_BY_ARR);
?>