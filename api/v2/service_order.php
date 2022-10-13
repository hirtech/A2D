<?php
include_once($controller_path . "service_order.inc.php");
$ServiceOrderObj = new ServiceOrder();
if($request_type == "service_order_list"){
    $ServiceOrderObj->clear_variable();
    $where_arr = array();
    if(!empty($RES_PARA)){
        $iServiceOrderId        = $RES_PARA['iServiceOrderId'];
        $vMasterMSA             = $RES_PARA['vMasterMSA'];
        $vServiceOrder          = $RES_PARA['vServiceOrder'];
        $vSalesRepName          = $RES_PARA['vSalesRepName'];
        $vSalesRepEmail         = $RES_PARA['vSalesRepEmail'];

        $page_length            = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start                  = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho                  = $RES_PARA['sEcho'];
        $display_order          = $RES_PARA['display_order'];
        $dir                    = $RES_PARA['dir'];       
    }

    if ($iServiceOrderId != "") {
        $where_arr[] = 'service_order."iServiceOrderId"='.$iServiceOrderId ;
    }

    if ($vMasterMSA != "") {
        $where_arr[] = "service_order.\"vMasterMSA\" = '".$vMasterMSA."'";
    }

    if ($vServiceOrder != "") {
        $where_arr[] = "service_order.\"vServiceOrder\" = '".$vServiceOrder."'";
    }

    if ($vSalesRepName != "") {
        $where_arr[] = "service_order.\"vSalesRepName\" = '".$vSalesRepName."'";
    }

    if ($vSalesRepEmail != "") {
        $where_arr[] = "service_order.\"vSalesRepEmail\" = '".$vSalesRepEmail."'";
    }

    switch ($display_order) {
        case "0":
            $sortname = "service_order.\"iServiceOrderId\"";
            break;
        case "1":
            $sortname = "service_order.\"vMasterMSA\"";
            break;
        case "2":
            $sortname = "service_order.\"vServiceOrder\"";
            break;
        case "3":
            $sortname = "cm.\"vCompanyName\"";
            break;
        case "4":
            $sortname = "service_order.\"vSalesRepName\"";
            break;
        case "5":
            $sortname = "s.\"vName\"";
            break;
        case "6":
            $sortname = "c.\"vConnectionTypeName\"";
            break;
        default:
            $sortname = "service_order.\"iServiceOrderId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
	$join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
    $join_fieds_arr[] = 'cm."vCompanyName"';
    $join_fieds_arr[] = 'c."vConnectionTypeName"';
    $join_fieds_arr[] = 'st1."vServiceType" as "vServiceType1"';
    $join_fieds_arr[] = 'st2."vServiceType" as "vServiceType2"';
    $join_fieds_arr[] = 'st3."vServiceType" as "vServiceType3"';
    
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN site_mas s on service_order."iPremiseId" = s."iSiteId"';
	$join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = 'LEFT JOIN company_mas cm on service_order."iCarrierID" = cm."iCompanyId"';
    $join_arr[] = 'LEFT JOIN connection_type_mas c on service_order."iConnectionTypeId" = c."iConnectionTypeId"';
    $join_arr[] = 'LEFT JOIN service_type_mas st1 on service_order."iService1" = st1."iServiceTypeId"';
    $join_arr[] = 'LEFT JOIN service_type_mas st2 on service_order."iService2" = st2."iServiceTypeId"';
    $join_arr[] = 'LEFT JOIN service_type_mas st3 on service_order."iService3" = st3."iServiceTypeId"';
    $ServiceOrderObj->join_field = $join_fieds_arr;
    $ServiceOrderObj->join = $join_arr;
    $ServiceOrderObj->where = $where_arr;
    $ServiceOrderObj->param['order_by'] = $sortname . " " . $dir;
    $ServiceOrderObj->param['limit'] = $limit;
    $ServiceOrderObj->setClause();
    $ServiceOrderObj->debug_query = false;
    $rs_sorder = $ServiceOrderObj->recordset_list();

    // Paging Total Records
    $total = $ServiceOrderObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_sorder);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            
            $data[] = array(
                "iServiceOrderId"       => $rs_sorder[$i]['iServiceOrderId'],
                "vMasterMSA"            => $rs_sorder[$i]['vMasterMSA'],
                "vServiceOrder"         => $rs_sorder[$i]['vServiceOrder'],
                "iCarrierID"            => $rs_sorder[$i]['iCarrierID'],
                "vCompanyName"          => $rs_sorder[$i]['vCompanyName'],
                "vSalesRepName"         => $rs_sorder[$i]['vSalesRepName'],
                "vSalesRepEmail"        => $rs_sorder[$i]['vSalesRepEmail'],
                "iPremiseId"            => $rs_sorder[$i]['iPremiseId'],
                "vPremiseName"          => $rs_sorder[$i]['vPremiseName'],
                "vPremiseType"          => $rs_sorder[$i]['vPremiseType'],
                "iConnectionTypeId"     => $rs_sorder[$i]['iConnectionTypeId'],
                "vConnectionTypeName"   => $rs_sorder[$i]['vConnectionTypeName'],
                "iService1"             => $rs_sorder[$i]['iService1'],
                "iService2"             => $rs_sorder[$i]['iService2'],
                "iService3"             => $rs_sorder[$i]['iService3'],
                "vServiceType1"         => $rs_sorder[$i]['vServiceType1'],
                "vServiceType2"         => $rs_sorder[$i]['vServiceType2'],
                "vServiceType3"         => $rs_sorder[$i]['vServiceType3'],
                "tComments"             => $rs_sorder[$i]['tComments'],
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "service_order_edit"){
	$ServiceOrderObj->clear_variable();
   	$update_arr = array(
        "iServiceOrderId"       => $RES_PARA['iServiceOrderId'],
        "vMasterMSA"            => $RES_PARA['vMasterMSA'],
        "vServiceOrder"         => $RES_PARA['vServiceOrder'],
        "iCarrierID"            => $RES_PARA['iCarrierID'],
        "vSalesRepName"         => $RES_PARA['vSalesRepName'],
        "vSalesRepEmail"        => $RES_PARA['vSalesRepEmail'],
        "iPremiseId"            => $RES_PARA['iPremiseId'],
        "iConnectionTypeId"     => $RES_PARA['iConnectionTypeId'],
        "iService1"             => $RES_PARA['iService1'],
        "iService2"             => $RES_PARA['iService2'],
        "iService3"             => $RES_PARA['iService3'],
        "tComments"             => $RES_PARA['tComments'],
        "iUserModifiedBy"       => $RES_PARA['iUserModifiedBy'],
    );

   $ServiceOrderObj->update_arr = $update_arr;
   $ServiceOrderObj->setClause();
   $rs_db = $ServiceOrderObj->update_records();
   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iServiceOrderId" => $RES_PARA['iServiceOrderId']);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   }
}else if($request_type == "service_order_delete"){
   	$iServiceOrderId = $RES_PARA['iServiceOrderId'];
	$ServiceOrderObj->clear_variable();
    $rs_db = $ServiceOrderObj->delete_records($iServiceOrderId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iServiceOrderId" => $iServiceOrderId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "service_order_add") {
    $ServiceOrderObj->clear_variable();
    $insert_arr = array(
        "vMasterMSA"            => $RES_PARA['vMasterMSA'],
        "vServiceOrder"         => $RES_PARA['vServiceOrder'],
        "iCarrierID"            => $RES_PARA['iCarrierID'],
        "vSalesRepName"         => $RES_PARA['vSalesRepName'],
        "vSalesRepEmail"        => $RES_PARA['vSalesRepEmail'],
        "iPremiseId"            => $RES_PARA['iPremiseId'],
        "iConnectionTypeId"     => $RES_PARA['iConnectionTypeId'],
        "iService1"             => $RES_PARA['iService1'],
        "iService2"             => $RES_PARA['iService2'],
        "iService3"             => $RES_PARA['iService3'],
        "tComments"             => $RES_PARA['tComments'],
        "iUserCreatedBy"		=> $RES_PARA['iUserCreatedBy'],
    );

    $ServiceOrderObj->insert_arr = $insert_arr;
    $ServiceOrderObj->setClause();
    $iServiceOrderId = $ServiceOrderObj->add_records();
    if($iServiceOrderId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iServiceOrderId" => $iServiceOrderId);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400 , "Message" => $message);
}
?>