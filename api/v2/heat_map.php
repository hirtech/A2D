<?php

if($request_type == "create_heat_map") {

    global $sqlObj;

    $vLayer = $RES_PARA['vLayer'];
    $dFromDate = $RES_PARA['dFromDate'];
    $dToDate   = $RES_PARA['dToDate'];

    $where_arr = array();

    if($vLayer == 1) { 
        $where_arr = array();

        if($dFromDate != ''){
            $where_arr[] = "a.\"dAddedDate\" >= '" . $dFromDate . "'";
        }

        if($dToDate != ''){
            $where_arr[] = "a.\"dAddedDate\" <= '" . $dToDate. "'";
        }

        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }

        $sql = 'SELECT c."vLatitude",c."vLongitude",case a."iStatus" when \'1\' then 0.4 when \'2\' then 0.5 when \'3\' then 0.6 when \'4\' then 0.9 when \'5\' then 1 when \'6\' then 0.1 when \'7\' then 0.2 else 0 end FROM "public"."premise_circuit" as a join "public"."workorder" as b on a."iWOId"=b."iWOId" join "public"."premise_mas" as c on b."iPremiseId"=c."iPremiseId"'.$where_clause.'';
        // echo $sql;exit();
        $rs = $sqlObj->GetAll($sql);
    }else if($vLayer == 2){
        $where_arr = array();
        if($dFromDate != ''){
            $where_arr[] = "\"dAddedDate\" >= '" . $dFromDate . "'";
        }

        if($dToDate != ''){
            $where_arr[] = "\"dAddedDate\" <= '" . $dToDate. "'";
        }

        $where_clause = '';
        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }

        $sql = 'SELECT "vLatitude","vLongitude",case "iStatus" when \'0\' then 0 when \'2\' then 0.5 when \'1\' then 1 else 0 end FROM "public"."premise_mas"'.$where_clause.''; 
        // echo $sql;exit();
        $rs = $sqlObj->GetAll($sql);
    }else if($vLayer == 3){
        $where_arr = array();

        if($dFromDate != '') {
            $where_arr[] = "a.\"dAddedDate\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "a.\"dAddedDate\" <= '" . $dToDate. "'";
        }
        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT a."vLatitude",a."vLongitude",b."iNetworkId" FROM "public"."fiberinquiry_details" as a join "public"."zone" as b on  a."iZoneId"=b."iZoneId"'.$where_clause.''; 
        // echo $sql;exit();
        $rs = $sqlObj->GetAll($sql);
    }else if($vLayer == 4){
        $where_arr = array();

        if($dFromDate != '') {
            $where_arr[] = "a.\"dAddedDate\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "a.\"dAddedDate\" <= '" . $dToDate. "'";
        }
        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT a."vLatitude",a."vLongitude",a."iZoneId" FROM "public"."fiberinquiry_details" as a'.$where_clause.'';
        // echo $sql;exit();
        $rs = $sqlObj->GetAll($sql);
    }
    else if($vLayer == 5){
        $where_arr = array();

        if($dFromDate != '') {
            $where_arr[] = "a.\"dAddedDate\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "a.\"dAddedDate\" <= '" . $dToDate. "'";
        }

        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT b."vLatitude",b."vLongitude",c."iNetworkId" FROM "public"."service_order" as a join "public"."premise_mas" as b on a."iPremiseId"=b."iPremiseId" join "public"."zone" as c on b."iZoneId"=c."iZoneId"'.$where_clause.'';
        // echo $sql;exit();
        $rs = $sqlObj->GetAll($sql);
    }else if($vLayer == 6){
        $where_arr = array();

        if($dFromDate != '') {
            $where_arr[] = "a.\"dAddedDate\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "a.\"dAddedDate\" <= '" . $dToDate. "'";
        }

        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT b."vLatitude",b."vLongitude",c."iNetworkId" FROM "public"."trouble_ticket" as d join "public"."service_order" as a on d."iServiceOrderId"=a."iServiceOrderId" join "public"."premise_mas" as b on a."iPremiseId"=b."iPremiseId" join "public"."zone" as c on b."iZoneId"=c."iZoneId"'.$where_clause.''; 
        // echo $sql;exit();
        $rs = $sqlObj->GetAll($sql);
    }else if($vLayer == 7){
        $where_arr = array();
        if($dFromDate != '') {
            $where_arr[] = "a.\"dAddedDate\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "a.\"dAddedDate\" <= '" . $dToDate. "'";
        }

        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT b."vLatitude",b."vLongitude",c."iNetworkId" FROM "public"."maintenance_ticket" as d join "public"."service_order" as a on d."iServiceOrderId"=a."iServiceOrderId" join "public"."premise_mas" as b on a."iPremiseId"=b."iPremiseId" join "public"."zone" as c on b."iZoneId"=c."iZoneId"'.$where_clause.'';
        // echo $sql;exit();
        $rs = $sqlObj->GetAll($sql);
    }
    
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $rs);
}
else{
	$r = HTTPStatus(400);
    $code = 1001;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 400, "Message" => $message);
}
?>