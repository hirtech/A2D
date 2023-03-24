<?php
//echo "<pre>";print_r($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Fiber Zone", 'List');
$access_group_var_delete = per_hasModuleAccess("Fiber Zone", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Fiber Zone", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Fiber Zone", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Fiber Zone", 'Edit', 'N');
$access_group_var_CSV = per_hasModuleAccess("Fiber Zone", 'CSV', 'N');

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
include_once($controller_path . "zone.inc.php");

$ZoneObj = new Zone();
//echo "<pre>";print_r($mode);exit;
if($mode == "List"){
    
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "iNetworkId"){
        $searchId = $_REQUEST['networkId'];
    }else if($vOptions == "iStatus"){
        $searchId = $_REQUEST['status'];
    }

    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }

    $arr_param['iZoneId']               = $_REQUEST['iZoneId'];
    $arr_param['vZoneName']             = $_REQUEST['vZoneName'];
    $arr_param['vZoneNameFilterOpDD']   = $_REQUEST['vZoneNameFilterOpDD'];
    $arr_param['isFile']                = $_REQUEST['isFile'];

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."zone_list.json";
    //echo "<pre>";print_r(json_encode($arr_param));exit;
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
               $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'zone/zone_edit&mode=Update&iZoneId=' . $rs_list[$i]['iZoneId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == "1") {
               $action .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_record('.$rs_list[$i]['iZoneId'].');"><i class="fa fa-trash"></i></a>';
            }

            $vStatus = "---";
            if($rs_list[$i]['iStatus'] == 1){
                $vStatus = "Near Net";
            }else if($rs_list[$i]['iStatus'] == 2){
                $vStatus = "Off Net";
            }else if($rs_list[$i]['iStatus'] == 3){
                $vStatus = "Created";
            }

            $entry[] = array(
                "iZoneId" => $rs_list[$i]['iZoneId'],
                "checkbox" => '<input type="checkbox" class="list" value="'.$rs_list[$i]['iZoneId'].'"/>',
                "vZoneName" => $rs_list[$i]['vZoneName'],
                "vNetwork" => $rs_list[$i]['vNetwork'],
                "iStatus" => $vStatus,
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
    $iZoneId = $_POST['iZoneId'];

    $arr_param['iZoneId'] = $iZoneId; 
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $API_URL = $site_api_url."zone_delete.json";
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
        "vZoneName"     => $_POST['vZoneName'],
        "iNetworkId"    => $_POST['iNetworkId'],
        "vFile"         => $files,
        "iStatus"       => $_POST['iStatus'],
        "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."zone_add.json";
    //echo $API_URL;
    //echo json_encode($arr_param);exit;
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
    if(isset($result_arr['iZoneId'])){       
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
    $file_msg = "";

    if(isset($_FILES["vFile"]['name']) && $_FILES["vFile"]['name'] != ''){
        $file_arr = img_fileUpload("vFile", $zone_path, '', $valid_ext = array('kml', 'kmz'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
    } else {
        $file_name = $_POST['vFile_old'];
    }
    
    $arr_param = array(
        'iZoneId'       => $_POST['iZoneId'],
        "vZoneName"     => $_POST['vZoneName'],
        "iNetworkId"    => $_POST['iNetworkId'],
        "vFile"         => $file_name,
        "iStatus"       => $_POST['iStatus'],
        "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );
    //echo "<pre>";print_r(json_encode($arr_param));exit;

    $API_URL = $site_api_url."zone_edit.json";
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
        $result['msg'] = MSG_UPDATE. " " .$file_msg;
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
}else if($mode == "zone_map"){
    //echo "<pre>";print_r($_REQUEST);exit();

    $iZoneId = $_POST['iZoneId']; 
    $arr_param["sessionId"] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['iZoneId'] = $iZoneId;
    //echo "<pre>";print_r($arr_param);
    $API_URL = $site_api_url."zone_map_data.json";
   
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
}else if($mode == "Excel"){
    $arr_param = array();

    $vOptions = $_REQUEST['vOptions'];
    if($vOptions == "iNetworkId"){
        $searchId = $_REQUEST['networkId'];
    }else if($vOptions == "iStatus"){
        $searchId = $_REQUEST['status'];
    }

    if ($searchId != "") {
        $arr_param[$vOptions] = $searchId;
    }

    $arr_param['iZoneId']               = $_REQUEST['iZoneId'];
    $arr_param['vZoneName']             = $_REQUEST['vZoneName'];
    $arr_param['vZoneNameFilterOpDD']   = $_REQUEST['vZoneNameFilterOpDD'];
    $arr_param['isFile']                = $_REQUEST['isFile'];

    $arr_param['page_length'] = $page_length;
    $arr_param['start'] = $start;
    $arr_param['sEcho'] = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir'] = $dir;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."zone_list.json";
    //echo "<pre>";print_r(json_encode($arr_param));exit;
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
    $file_name = "fiber_zone".time().".xlsx";

    if($cnt_export >0) {
        
        $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A1', 'Id')
                 ->setCellValue('B1', 'Fiber Zone')
                 ->setCellValue('C1', 'Network')
                 ->setCellValue('D1', 'Status');

        for($e=0; $e<$cnt_export; $e++) {

            $vStatus = "---";
            if($rs_export[$e]['iStatus'] == 1){
                $vStatus = "Near Net";
            }else if($rs_export[$e]['iStatus'] == 2){
                $vStatus = "Off Net";
            }else if($rs_export[$e]['iStatus'] == 3){
                $vStatus = "Created";
            }

            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.($e+2), $rs_export[$e]['iZoneId'])
            ->setCellValue('B'.($e+2), $rs_export[$e]['vZoneName'])
            ->setCellValue('C'.($e+2), $rs_export[$e]['vNetwork'])
            ->setCellValue('D'.($e+2), $vStatus);
         }
                        
        /* Set Auto width of each comlumn */
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        
        /* Set Font to Bold for each comlumn */
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

        /* Set Alignment of Selected Columns */
        $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('FiberZone');

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
}else if($mode == "update_zone_id_in_premise"){
    $sql = "SELECT * FROM premise_mas order by \"iPremiseId\""; 
    $rs = $sqlObj->GetAll($sql);
    $ni = count($rs);
    //echo"<pre>";print_r($rs);exit;
    if($ni > 0){
        for($i=0; $i<$ni; $i++) {
            if($rs[$i]['vLongitude'] != '' && $rs[$i]['vLatitude'] != ''){
                $long = number_format($rs[$i]['vLongitude'],6);
                $lat = number_format($rs[$i]['vLatitude'],6);

                $sql_zone = "SELECT zone.\"iZoneId\", zone.\"vZoneName\" FROM zone WHERE  St_Within(ST_GeometryFromText('POINT(".$long." ".$lat.")', 4326)::geometry, (zone.\"PShape\")::geometry)='t' ORDER BY zone.\"iZoneId\" DESC LIMIT 1"; 
                $rs_zone = $sqlObj->GetAll($sql_zone);
                $iZoneId = 0;
                if($rs){
                    $iZoneId = $rs_zone[0]['iZoneId'];
                }

                //echo "Premise Id === ".$rs[$i]['iPremiseId']."<br/>";
                //echo "Zone Id === ".$iZoneId."<br/>";
                
                if($iZoneId > 0){
                    $sql_update = 'UPDATE premise_mas set "iZoneId" =  '.gen_allow_null_int($iZoneId).' WHERE "iPremiseId" = '.$rs[$i]['iPremiseId'];
                    //echo $sql_update."<hr/>";
                    $sqlObj->Execute($sql_update);
                }
            }
        }
    }

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
//echo "<pre>";print_r($rs_ntwork);exit;
$smarty->assign("rs_ntwork", $rs_ntwork);
## --------------------------------

$module_name = "Fiber Zone List";
$module_title = "Fiber Zone";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("access_group_var_add", $access_group_var_add);
?>