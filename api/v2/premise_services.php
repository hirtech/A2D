<?php
include_once($controller_path . "service_type.inc.php");
include_once($controller_path . "premise_services.inc.php");
$PremiseServicesObj = new PremiseServices();
if($request_type == "premise_services_list"){
    $iPremiseId = $RES_PARA['iPremiseId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $ServiceTypeObj = new ServiceType();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    $where_arr[] = "service_type_mas.\"iStatus\"='1'";
   
    $ServiceTypeObj->where = $where_arr;
    $ServiceTypeObj->param['order_by'] = "service_type_mas.\"iServiceTypeId\"";
    $ServiceTypeObj->setClause();
    $rs_service_type = $ServiceTypeObj->recordset_list();
    //echo"<pre>";print_r($rs_service_type);exit;
    $ni = count($rs_service_type);
    $arr = array();
    if($ni > 0) {
        for ($i=0; $i < $ni; $i++) { 
            $iServiceTypeId = $rs_service_type[$i]['iServiceTypeId'];
            $vServiceType = $rs_service_type[$i]['vServiceType'];

            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr  = array();
            $where_arr = array();
            $join_fieds_arr[] = 'w."vWOProject"';
            $join_fieds_arr[] = 'so."vServiceOrder"';
            $join_fieds_arr[] = "concat(u.\"vFirstName\", ' ', u.\"vLastName\") as \"vUserName\"";
            $join_fieds_arr[] = 'cm."vCompanyName"';
            $join_fieds_arr[] = 'c."vCircuitName"';

            $join_arr[] = " LEFT JOIN workorder w ON premise_services.\"iWOId\" = w.\"iWOId\"";
            $join_arr[] = 'LEFT JOIN service_order so on premise_services."iServiceOrderId" = so."iServiceOrderId"';
            $join_arr[] = 'LEFT JOIN user_mas u on premise_services."iUserId" = u."iUserId"';
            $join_arr[] = 'LEFT JOIN company_mas cm on premise_services."iCarrierId" = cm."iCompanyId"';
            $join_arr[] = 'LEFT JOIN premise_circuit pc on premise_services."iPremiseCircuitId" = pc."iPremiseCircuitId"';
            $join_arr[] = 'LEFT JOIN circuit c on pc."iCircuitId" = c."iCircuitId"';

            $where_arr[] = "premise_services.\"iServiceTypeId\"='".$iServiceTypeId."'";
            $where_arr[] = "premise_services.\"iPremiseId\"='".$iPremiseId."'";

            $PremiseServicesObj->join_field = $join_fieds_arr;
            $PremiseServicesObj->join = $join_arr;
            $PremiseServicesObj->where = $where_arr;
            $PremiseServicesObj->param['limit'] = "1";
            $PremiseServicesObj->param['order_by'] = "premise_services.\"iPremiseServiceId\" DESC";
            $PremiseServicesObj->setClause();
            $rs_premise_services = $PremiseServicesObj->recordset_list();  
            //echo"<pre>";print_r($rs_premise_services);
            $vStatus = "Off / NA";
            if($rs_premise_services[0]['iStatus'] == 1){
                $vStatus = "On / Start";
            }else if($rs_premise_services[0]['iStatus'] == 2){
                $vStatus = "Suspended";
            }

            $arr[$i]['iServiceTypeId'] = $iServiceTypeId;
            $arr[$i]['vServiceType'] = $vServiceType;
            $arr[$i]['iPremiseServiceId'] = $rs_premise_services[0]['iPremiseServiceId'];
            $arr[$i]['iPremiseId'] = $rs_premise_services[0]['iPremiseId'];
            $arr[$i]['iStatus'] = $rs_premise_services[0]['iStatus'];
            $arr[$i]['vStatus'] = $vStatus;
            $arr[$i]['iWOId'] = $rs_premise_services[0]['iWOId'];
            $arr[$i]['vWorkOrder'] = '';
            if($rs_premise_services[0]['vWOProject'] != ''){
                $arr[$i]['vWorkOrder'] = "WO#".$rs_premise_services[0]['iWOId']." (".$rs_premise_services[0]['vWOProject'].")";
            }
            $arr[$i]['iServiceOrderId'] = $rs_premise_services[0]['iServiceOrderId'];
            $arr[$i]['vServiceOrder'] = '';
            if($rs_premise_services[0]['vServiceOrder'] != ''){
                $arr[$i]['vServiceOrder'] = "SO#".$rs_premise_services[0]['iServiceOrderId']." (".$rs_premise_services[0]['vServiceOrder'].")";
            }
            $arr[$i]['iUserId'] = $rs_premise_services[0]['iUserId'];
            $arr[$i]['vUserName'] = (isset($rs_premise_services[0]['vUserName']) && $rs_premise_services[0]['vUserName'] != '')?$rs_premise_services[0]['vUserName']:"";
            $arr[$i]['iCarrierId'] = $rs_premise_services[0]['iCarrierId'];
            $arr[$i]['vCarrier'] = (isset($rs_premise_services[0]['vCompanyName']) && $rs_premise_services[0]['vCompanyName'] != '')?$rs_premise_services[0]['vCompanyName']:"";

            $arr[$i]['iPremiseCircuitId'] = $rs_premise_services[0]['iPremiseCircuitId'];
            $arr[$i]['vCircuitName'] = (isset($rs_premise_services[0]['vCircuitName']) && $rs_premise_services[0]['vCircuitName'] != '')?$rs_premise_services[0]['vCircuitName']:"";

            $last_action = '';
            if($rs_premise_services[0]['iStatus'] == 1){
                $last_action .= "Started:".date_display_report_date($rs_premise_services[0]['dStartDate'])." | NRC: $".$rs_premise_services[0]['iNRCVariable']." | MRC: $".$rs_premise_services[0]['iMRCFixed'];
            }else if($rs_premise_services[0]['iStatus'] == 2){
                $last_action .= "Suspended:".date_display_report_date($rs_premise_services[0]['dSuspendDate']);
            }

            $arr[$i]['vLastAction'] = $last_action;
            
        }
    }
    //echo"<pre>";print_r($arr);exit;
    $result = array('data' => $arr , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "premise_services_start"){
    $insert_arr = array(
        "iPremiseId"                => $RES_PARA['iPremiseId'],
        "iServiceTypeId"            => $RES_PARA['iServiceTypeId'],
        "iWOId"                     => $RES_PARA['iWOId'],
        "iStatus"                   => 1,
        "iServiceOrderId"           => $RES_PARA['iServiceOrderId'],
        "iCarrierId"                => $RES_PARA['iCarrierId'],
        "iPremiseCircuitId"         => $RES_PARA['iPremiseCircuitId'],
        "iUserId"                   => $RES_PARA['iUserId'],
        "iNRCVariable"              => $RES_PARA['iNRCVariable'],
        "iMRCFixed"                 => $RES_PARA['iMRCFixed'],
        "dStartDate"                => $RES_PARA['dStartDate']
    );
    // echo "<pre>";print_r($insert_arr);exit;
    $PremiseServicesObj->insert_arr = $insert_arr;
    $PremiseServicesObj->setClause();
    $rs_db = $PremiseServicesObj->start_records();

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => "Premise Services are started", "iPremiseServiceId" => $rs_db, "iServiceTypeId" => $RES_PARA['iServiceTypeId'], "iPremiseId" => $RES_PARA['iPremiseId']);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => "ERROR - while staring Premise Services.", "iServiceTypeId" => $RES_PARA['iServiceTypeId'], "iPremiseId" => $RES_PARA['iPremiseId']);
    }
}else if($request_type == "premise_services_suspend"){
    $insert_arr = array(
        "iPremiseId"                => $RES_PARA['iPremiseId'],
        "iServiceTypeId"            => $RES_PARA['iServiceTypeId'],
        "iWOId"                     => $RES_PARA['iWOId'],
        "iStatus"                   => 2,
        "iServiceOrderId"           => $RES_PARA['iServiceOrderId'],
        "iCarrierId"                => $RES_PARA['iCarrierId'],
        "iPremiseCircuitId"         => $RES_PARA['iPremiseCircuitId'],
        "iUserId"                   => $RES_PARA['iUserId'],
        "dSuspendDate"               => $RES_PARA['dSuspendDate']
    );
    // echo "<pre>";print_r($insert_arr);exit;
    $PremiseServicesObj->insert_arr = $insert_arr;
    $PremiseServicesObj->setClause();
    $rs_db = $PremiseServicesObj->suspend_records();
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => "Premise Services are suspended", "iPremiseServiceId" => $rs_db, "iServiceTypeId" => $RES_PARA['iServiceTypeId'], "iPremiseId" => $RES_PARA['iPremiseId']);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => "ERROR - while suspending Premise Services.", "iServiceTypeId" => $RES_PARA['iServiceTypeId'], "iPremiseId" => $RES_PARA['iPremiseId']);
    }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>