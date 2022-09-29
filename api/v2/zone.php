<?php

include_once($controller_path . "zone.inc.php");
include_once($function_path."image.inc.php");

$ZoneObj = new Zone();

##Search Arary
$where_arr = array();
if($request_type == "zone_list"){
	$where_arr = array();

   if(!empty($RES_PARA)){

        $iZoneId            = $RES_PARA['iZoneId'];
        $vZoneName          = $RES_PARA['vZoneName'];
        $vNetwork           = $RES_PARA['vNetwork'];
        $iStatus            = $RES_PARA['iStatus'];
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
        $order_by           = $RES_PARA['order_by'];
    }
    
    if ($iZoneId != "") {
        $where_arr[] = '"iZoneId"='.$iZoneId ;
    }

    if ($vZoneName != "") {
        $where_arr[] = "\"vZoneName\" ILIKE '" . $vZoneName . "%'";
    }

    if ($vNetwork != "") {
        $where_arr[] = "network.\"vName\" ILIKE '" . $vNetwork . "%'";
    }

    if($iStatus != ""){
        if(strtolower($iStatus) == "active"){
            $where_arr[] = "\"iStatus\" = '1'";
        }
        else if(strtolower($iStatus) == "inactive"){
            $where_arr[] = "\"iStatus\" = '0'";
        }
    } 

    switch ($display_order) {
        case "0":
            $sortname = "zone.\"iZoneId\"";
            break;
        case "1":
            $sortname = "zone.\"vZoneName\"";
            break;
        case "2":
            $sortname = "network.\"vZoneName\"";
            break;
        case "3":
            $sortname = "zone.\"iStatus\"";
            break;
        default:
            $sortname = "zone.\"iZoneId\"";
            break;
    } 

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = 'network."vName" as "vNetwork"';
    $join_fieds_arr[] = 'network."iNetworkId"';
    $join_arr[] = " LEFT JOIN network ON zone.\"iNetworkId\" = network.\"iNetworkId\"";
    $ZoneObj->join_field = $join_fieds_arr;
    $ZoneObj->join = $join_arr;
    $ZoneObj->where = $where_arr;
    if ($order_by != "") {
        $ZoneObj->param['order_by'] = $order_by;
    }else {
        $ZoneObj->param['order_by'] = $sortname . " " . $dir;
    }
    $ZoneObj->param['limit'] = $limit;
    $ZoneObj->setClause();
    $ZoneObj->debug_query = false;
    $rs_list = $ZoneObj->recordset_list();

    // Paging Total Records
    $total_record = $ZoneObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_list);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
	        $data[] = array(
                "iZoneId" => $rs_list[$i]['iZoneId'],
                "vZoneName" => gen_strip_slash($rs_list[$i]['vZoneName']),
                "vNetwork" => gen_strip_slash($rs_list[$i]['vNetwork']),
                "iStatus" => $rs_list[$i]['iStatus']       
            );
        }
    }
    
   $result = array('data' => $data , 'total_record' => $total_record);

   $rh = HTTPStatus(200);
   $code = 2000;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 200, "Message" => $message, "result" => $result);  
   
}else if($request_type == "zone_delete"){

    $iZoneId = $RES_PARA['iZoneId'];
    $county_id = $RES_PARA['county_id'];

    //get data
    $ZoneObj->clear_variable();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr  = array();
    $where_arr[] = '"iZoneId" = '.$iZoneId.'';
    $ZoneObj->join_field = $join_fieds_arr;
    $ZoneObj->join = $join_arr;
    $ZoneObj->where = $where_arr;
    $ZoneObj->param['limit'] = ' LIMIT 1 ';
    $ZoneObj->setClause();
    $rs_data = $ZoneObj->recordset_list();

    //delete records
    $rs_tot = $ZoneObj->delete_records($iZoneId);
    
    if($rs_tot){

        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "result" => $iZoneId);
        //unlink(delete) file
        $filepath = $zone_path."/";
        if($rs_data[0]['vFile'] != "" && file_exists($filepath.$rs_data[0]['vFile']) ){
             @unlink($filepath.$rs_data[0]['vFile']);
        }
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }    
}
else if($request_type == "zone_add"){
    //echo "<pre>";print_r($FILES_PARA);exit;
    $file_msg = "File Not Uploaded.Please Check it.";
    if($FILES_PARA["vFile"]['name'] != ""){
        //$filepath = create_image_folder($sess_iCountySaasId,$zone_path);
        $filepath = $zone_path;
        $file_arr = img_fileUpload("vFile", $filepath, '', $valid_ext = array('kml','kmz'));
        $file_name = $file_arr[0];
        $file_msg =  $file_arr[1];
   }
   //echo $file_name;exit;
   if($file_name != ""){
        $insert_arr = array(
            "vZoneName"     => $RES_PARA['vZoneName'],
            "iNetworkId"    => $RES_PARA['iNetworkId'],
            "vFile"         => $file_name,
            "iStatus"       => $RES_PARA['iStatus'],
        );
        $ZoneObj->insert_arr = $insert_arr;
        $ZoneObj->setClause();
        $iZoneId = $ZoneObj->add_records();
        if($iZoneId){
            $response_data = array("Code" => 200, "Message" => MSG_ADD, "iZoneId" => $iZoneId);
        }
        else{
            $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
        }
    }else{
        $r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => $file_msg);
    }
}
else if($request_type == "zone_edit"){
    if($RES_PARA['iZoneId'] > 0 || $RES_PARA != ""){
        $update_arr = array(
            "iZoneId"       => $RES_PARA['iZoneId'],
            "vZoneName"     => $RES_PARA['vZoneName'],
            "iNetworkId"    => $RES_PARA['iNetworkId'],
            "iStatus"       => $RES_PARA['iStatus'],
        );

        $ZoneObj->update_arr = $update_arr;
        $ZoneObj->setClause();
        $rs = $ZoneObj->update_records();

        if($rs){
            $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iZoneId" => $RES_PARA['iZoneId']);
        }
        else{
            $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
        }
    }else{
         $response_data = array("Code" => 500 , "Message" => "iZoneId is missing");
    }
} else if($request_type == "zone_map_data"){
   
    $iZoneId = $RES_PARA['iZoneId']; 
    $country_id = $RES_PARA['country_id']; 

    $jsonData =array();
    $ZoneObj->clear_variable();
    

    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr  = array();

    $where_arr[] = '"iZoneId" = '.$iZoneId.'';

    $ZoneObj->join_field = $join_fieds_arr;
    $ZoneObj->join = $join_arr;
    $ZoneObj->where = $where_arr;
    $ZoneObj->setClause();
    $rs_data = $ZoneObj->recordset_list();
    
    $ki = count($rs_data);
   
    if($ki > 0){
        for($k=0;$k<$ki;$k++){
            $filepath = $zone_path."/";
            $file_url = "";
            if(file_exists($filepath.$rs_data[0]['vFile'])){
                $file_path = $zone_path."/".$rs_data[0]['vFile'];
                $file_url = $zone_url."/".$rs_data[0]['vFile'];
            }
            $rs_data[$k]['vFilePath'] = $file_url;
        }
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200 , "Message" => $message , "result" => $rs_data);
    }else{
        $code = 2104;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 500 , "Message" => $message , "result" => array());
    }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}

?>