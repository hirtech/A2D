<?php
//echo "<pre>";print_r($_POST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Task Awareness", 'List');
$access_group_var_delete = per_hasModuleAccess("Task Awareness", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Task Awareness", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Task Awareness", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Task Awareness", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Task Awareness", 'CSV', 'N');
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

$iAId = $_POST['iAId'];

if($mode == "List"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "iNetworkId"){
        $searchId = $_REQUEST['networkId'];
    }else if($vOptions == "iEngagementId"){
        $searchId = $_REQUEST['engagementId'];
    }else if($vOptions == "iTechnicianId"){
        $searchId = $_REQUEST['technicianId'];
    }
    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }

    if ($_REQUEST['aId'] != "") {
        $arr_param['aId'] = $_REQUEST['aId'];
    }

    if ($_REQUEST['premiseId'] != "") {
        $arr_param['premiseId'] = $_REQUEST['premiseId'];
    }

    if($_REQUEST['siteName'] != ""){
        $arr_param['siteName'] = $_REQUEST['siteName'];
        $arr_param['SiteFilterOpDD'] = $_REQUEST['SiteFilterOpDD'];
    }

    if($_REQUEST['vAddress'] != ""){
        $arr_param['vAddress'] = $_REQUEST['vAddress'];
        $arr_param['AddressFilterOpDD'] = $_REQUEST['AddressFilterOpDD'];
    }

    if ($_REQUEST['fiberInquiryId'] != "") {
        $arr_param['fiberInquiryId'] = $_REQUEST['fiberInquiryId'];
    }
    
    $arr_param['page_length']   = $page_length;
    $arr_param['start']         = $start;
    $arr_param['sEcho']         = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir']           = $dir;

    $arr_param['access_group_var_edit']     = $access_group_var_edit;
    $arr_param['access_group_var_delete']   = $access_group_var_delete;

    $arr_param['sessionId']     = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    $API_URL = $site_api_url."task_awareness_list.json";
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
    $rs_awareness = $result_arr['result']['data'];
	$ni = count($rs_awareness);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if($access_group_var_edit == "1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit" onclick="addEditDataAwareness('.$rs_awareness[$i]['iAId'].',\'edit\',0)"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_awareness[$i]['iAId'].');"><i class="fa fa-trash"></i></a>';
            }

            $hidden_fields = '<input type="hidden" id="iAId_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['iAId'].'"><input type="hidden" id="vSiteName_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['vName'].'"><input type="hidden" id="iPremiseId_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['iPremiseId'].'"><input type="hidden" id="dDate_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['dDate'].'"><input type="hidden" id="dStartDate_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['dStartDate'].'"><input type="hidden" id="dStartTime_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['dStartTime'].'"><input type="hidden" id="dEndDate_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['dEndDate'].'"><input type="hidden" id="dEndTime_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['dEndTime'].'"><input type="hidden" id="iEngagementId_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['iEngagementId'].'"><input type="hidden" id="tNotes_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['tNotes'].'"><input type="hidden" id="srdisplay_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['vFiberInquiry'].'"><input type="hidden" id="iSRId_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['iFiberInquiryId'].'"><input type="hidden" id="iTechnicianId_'.$rs_awareness[$i]['iAId'].'" value="'.$rs_awareness[$i]['iTechnicianId'].'">';

            $premise_url = $site_url."premise/edit&mode=Update&iPremiseId=".$rs_awareness[$i]['iPremiseId'];
            $vPremise = "<a href='".$premise_url."' target='_blank' class='text-primary'>".$rs_awareness[$i]['vName']."</a>";

            $entry[] = array(
                "iAId"          => $rs_awareness[$i]['iAId'],
                "vName"         => $vPremise.$hidden_fields,
                "vAddress"      => trim($rs_awareness[$i]['vAddress']),
                "vFiberInquiry" => trim($rs_awareness[$i]['vFiberInquiry']),
                "dDate"         => date_getDateTimeDDMMYYYY($rs_awareness[$i]['dDate']),
                "dStartDate"    => date_getTimeFromDate($rs_awareness[$i]['dStartDate']),
                "dEndDate"      => date_getTimeFromDate($rs_awareness[$i]['dEndDate']),
                "vEngagement"   => $rs_awareness[$i]['vEngagement'],
                "tNotes"        => trim($rs_awareness[$i]['tNotes']),
                "actions"       => ($action != "") ? $action : "---"
            );
        }
    }
    # Return jSON data.
    # -----------------------------------
    $jsonData['aaData'] = $entry;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
} else if($mode == "Delete"){
    $result = array();
    $arr_param = array();
    $iAId = $_POST['iAId'];
    
    $arr_param['iAId']      = $iAId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."task_awareness_delete.json";
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
} else if($mode == "Update"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        $dStartDate = $_POST['modal_dDate_awareness']." 00:00:00";
        if($_POST['dStartTime_awareness'] != ""){
            $dStartDate = $_POST['modal_dDate_awareness']." ".$_POST['dStartTime_awareness'];
        }
        $dEndDate = "";
        if($_POST['dEndTime_awareness'] != ""){
            $dEndDate = $_POST['modal_dDate_awareness']." ".$_POST['dEndTime_awareness'];
        }

        $arr_param = array(
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iAId"              => $_POST['modal_iAId'],
            "iPremiseId"        => $_POST['serach_iPremiseId_awareness'],
            "iFiberInquiryId"   => $_POST['serach_iSRId_awareness'],
            "dDate"             => $_POST['modal_dDate_awareness'],
            "dStartDate"        => $dStartDate,
            "dEndDate"          => $dEndDate,
            "iEngagementId"     => $_POST['iEngagementId'],
            "tNotes"            => $_POST['tNotes_awareness'],
            "iLoginUserId"      => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "iTechnicianId"     => $_POST['technician_id']
        );
        $API_URL = $site_api_url."task_awareness_edit.json";
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
} else if($mode == "Add"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        $dStartDate = $_POST['modal_dDate_awareness']." 00:00:00";
        if($_POST['dStartTime_awareness'] != ""){
            $dStartDate = $_POST['modal_dDate_awareness']." ".$_POST['dStartTime_awareness'];
        }

        $dEndDate = "";
        if($_POST['dEndTime_awareness'] != ""){
            $dEndDate = $_POST['modal_dDate_awareness']." ".$_POST['dEndTime_awareness'];
        }

        $arr_param = array(
            "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
            "iPremiseId"        => $_POST['serach_iPremiseId_awareness'],
            "iFiberInquiryId"   => $_POST['serach_iSRId_awareness'],
            "dDate"             => $_POST['modal_dDate_awareness'],
            "dStartDate"        => $dStartDate,
            "dEndDate"          => $dEndDate,
            "iEngagementId"     => $_POST['iEngagementId'],
            "tNotes"            => $_POST['tNotes_awareness'],
            "iLoginUserId"      => $_SESSION["sess_iUserId".$admin_panel_session_suffix],
            "iTechnicianId"     => $_POST['technician_id']
        );

        $API_URL = $site_api_url."task_awareness_add.json";
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
} else if($mode == "search_site"){
    /*Search site api*/
    $arr_param = array();
    $vSiteName_awareness = trim($_REQUEST['vSiteName_awareness']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['siteName'] = $vSiteName_awareness;
    $API_URL = $site_api_url."search_premise.json";
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
    
    # -----------------------------------
    # Return data.
    # -----------------------------------
    echo  json_encode($result_arr['result']['data']);
    hc_exit();
    # -----------------------------------
} else if($mode == "search_fiber_inquiry"){
    /*Search site api*/
    $arr_param = array();
    $srId = trim($_REQUEST['vSR_awareness']);
    
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['srId'] = $srId;
    
    $API_URL = $site_api_url."search_fiber_inquiry.json";
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
    
    # -----------------------------------
    # Return data.
    # -----------------------------------
    echo  json_encode($result_arr['result']['data']);
    hc_exit();
    # -----------------------------------
} else if($mode== "Excel"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "iNetworkId"){
        $searchId = $_REQUEST['networkId'];
    }else if($vOptions == "iEngagementId"){
        $searchId = $_REQUEST['engagementId'];
    }else if($vOptions == "iTechnicianId"){
        $searchId = $_REQUEST['technicianId'];
    }
    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }

    if ($_REQUEST['aId'] != "") {
        $arr_param['aId'] = $_REQUEST['aId'];
    }

    if ($_REQUEST['premiseId'] != "") {
        $arr_param['premiseId'] = $_REQUEST['premiseId'];
    }

    if($_REQUEST['siteName'] != ""){
        $arr_param['siteName'] = $_REQUEST['siteName'];
        $arr_param['SiteFilterOpDD'] = $_REQUEST['SiteFilterOpDD'];
    }

    if($_REQUEST['vAddress'] != ""){
        $arr_param['vAddress'] = $_REQUEST['vAddress'];
        $arr_param['AddressFilterOpDD'] = $_REQUEST['AddressFilterOpDD'];
    }

    if ($_REQUEST['fiberInquiryId'] != "") {
        $arr_param['fiberInquiryId'] = $_REQUEST['fiberInquiryId'];
    }
    
    $arr_param['page_length']   = $page_length;
    $arr_param['start']         = $start;
    $arr_param['sEcho']         = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir']           = $dir;

    $arr_param['sessionId']     = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    
    $API_URL = $site_api_url."task_awareness_list.json";
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
        $file_name = "task_awareness_".time().".xlsx";

        if($cnt_export >0) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A1', 'Id')
                     ->setCellValue('B1', 'Premise Name')
                     ->setCellValue('C1', 'Address')
                     ->setCellValue('D1', 'Fiber Inquiry')
                     ->setCellValue('E1', 'Date')
                     ->setCellValue('F1', 'Start Time')
                     ->setCellValue('G1', 'End Time')
                     ->setCellValue('H1', 'Engagement')
                     ->setCellValue('I1', 'Notes');

            for($e=0; $e<$cnt_export; $e++) {

                $objPHPExcel->getActiveSheet()
                ->setCellValue('A'.($e+2), $rs_export[$e]['iAId'])
                ->setCellValue('B'.($e+2), $rs_export[$e]['vName'])
                ->setCellValue('C'.($e+2), $rs_export[$e]['vAddress'])
                ->setCellValue('D'.($e+2), $rs_export[$e]['vFiberInquiry'])
                ->setCellValue('E'.($e+2), date_getDateTimeDDMMYYYY($rs_export[$e]['dDate']))
                ->setCellValue('F'.($e+2), date_getTimeFromDate($rs_export[$e]['dStartDate']))
                ->setCellValue('G'.($e+2), date_getTimeFromDate($rs_export[$e]['dEndDate']))
                ->setCellValue('H'.($e+2), $rs_export[$e]['vEngagement'])
                ->setCellValue('I'.($e+2), trim($rs_export[$e]['tNotes']));   
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
            $objPHPExcel->getActiveSheet()->setTitle('TaskAwareness');

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

/*-------------------------- Engagement -------------------------- */
$arr_param = array();
$arr_param['iStatus']   = 1;
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."engagement_dropdown.json";
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
$smarty->assign("rs_engagement", $res['result']);
/*-------------------------- Engagement -------------------------- */

/*-------------------------- User -------------------------- */
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
$smarty->assign("technician_user_arr", $res['result']);
//echo "<pre>";print_r($res['result']);exit;
/*-------------------------- User -------------------------- */

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

$module_name = "Awareness List";
$module_title = "Task Awareness";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("iPremiseId", $iPremiseId);

$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);

$smarty->assign("dDate", date('Y-m-d'));
$smarty->assign("dStartTime", date("H:i", time()));
$smarty->assign("dEndTime", date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes")));
?>