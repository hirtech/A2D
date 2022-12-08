<?php

if($request_type == "create_heat_map") {

    global $sqlObj;

    $vLayer = $RES_PARA['vLayer'];
    $dFromDate = $RES_PARA['dFromDate'];
    $dToDate   = $RES_PARA['dToDate'];

    $where_arr = array();

    if($dFromDate != '') {
        $where_arr[] = "\"dAddedDate\" >= '" . $dFromDate . "'";
    }

    if($dToDate != '') {
        $where_arr[] = "\"dAddedDate\" <= '" . $dToDate. "'";
    }

    $where_clause = '';

    if(count($where_arr) > 0){
        $where_clause = " WHERE ".implode(" AND ", $where_arr); 
    }

    if($vLayer == 1)
    { 
        $sql = 'SELECT "vLatitude","vLongitude" FROM "public"."sr_details"'.$where_clause.' ORDER BY "dAddedDate" desc'; 
        $rs = $sqlObj->GetAll($sql);
    }

    else if($vLayer == 2){

        $where_arr = array();

        if($dFromDate != ''){
        $where_arr[] = "a.\"dDate\" >= '" . $dFromDate . "'";
    }

    if($dToDate != ''){
        $where_arr[] = "a.\"dDate\" <= '" . $dToDate. "'";
    }

    $where_clause = '';

    if(count($where_arr) > 0){
        $where_clause = " WHERE ".implode(" AND ", $where_arr); 
    }

    $sql = 'SELECT b."vLatitude",b."vLongitude",case a."vMaxLandingRate" when \'0-Found\' then 0 when \'1-5\' then 0.2 when \'1-5\' then 0.4 when \'11-20\' then 0.6 when \'21-50\' then 0.8 when \'50-100\' then 0.9 when \'50-100\' then 1 else 0 end FROM task_landing_rate as a join premise_mas as b on a."iPremiseId"=b."iPremiseId"'.$where_clause.'';
    $rs = $sqlObj->GetAll($sql);
    }
    else if($vLayer == 3)
    {
        $where_arr = array();

        if($dFromDate != '') {
            $where_arr[] = "a.\"dDate\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "a.\"dDate\" <= '" . $dToDate. "'";
        }
        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT b."vLatitude",b."vLongitude",a."rAvgLarvel" FROM task_larval_surveillance as a join premise_mas as b on a."iPremiseId"=b."iPremiseId"'.$where_clause.''; 
        $rs = $sqlObj->GetAll($sql);
    }
    else if($vLayer == 4)
    {
        $where_arr = array();

        if($dFromDate != '') {
            $where_arr[] = "a.\"dDate\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "a.\"dDate\" <= '" . $dToDate. "'";
        }
        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT b."vLatitude",b."vLongitude",1 FROM task_treatment as a join premise_mas as b on a."iPremiseId"=b."iPremiseId"'.$where_clause.''; 
        $rs = $sqlObj->GetAll($sql);
    }
    else if($vLayer == 5)
    {
        $where_arr = array();

        if($dFromDate != '') {
            $where_arr[] = "a.\"dDate\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "a.\"dDate\" <= '" . $dToDate. "'";
        }

        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT b."vLatitude",b."vLongitude",1 FROM task_other as a join premise_mas as b on a."iPremiseId"=b."iPremiseId"'.$where_clause.''; 
        $rs = $sqlObj->GetAll($sql);
    }
    else if($vLayer == 6)
    {
        $where_arr = array();

        if($dFromDate != '') {
            $where_arr[] = "a.\"dTrapCollected\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "a.\"dTrapCollected\" <= '" . $dToDate. "'";
        }

        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT b."vLatitude",b."vLongitude", 1 FROM task_trap as a join premise_mas as b on a."iPremiseId"=b."iPremiseId"'.$where_clause.''; 
        $rs = $sqlObj->GetAll($sql);
    }
    else if($vLayer == 7)
    {
        $where_arr = array();
        if($dFromDate != '') {
            $where_arr[] = "b.\"dTrapCollected\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "b.\"dTrapCollected\" <= '" . $dToDate. "'";
        }

        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT c."vLatitude",c."vLongitude", sum(a."iTotalCount") FROM task_mosquito_count as a join task_trap as b on a."iTTId"=b."iTTId" join premise_mas as c on b."iPremiseId"=c."iPremiseId"'.$where_clause.' group by c."vLatitude",c."vLongitude"'; 
        $rs = $sqlObj->GetAll($sql);
    }
    else if($vLayer == 8)
    {
        $where_arr = array();

        if($dFromDate != '') {
            $where_arr[] = "c.\"dTrapCollected\" >= '" . $dFromDate . "'";
        }

        if($dToDate != '') {
            $where_arr[] = "c.\"dTrapCollected\" <= '" . $dToDate. "'";
        }
        $where_arr[] = "\"iResultId\" = 3";
        $where_clause = '';

        if(count($where_arr) > 0){
            $where_clause = " WHERE ".implode(" AND ", $where_arr); 
        }
        $sql = 'SELECT d."vLatitude",d."vLongitude", 1 FROM task_mosquito_pool_result as a join task_mosquito_pool as b on a."iTMPId"=b."iTMPId" join task_trap as c on b."iTTId"=c."iTTId" join premise_mas as d on c."iPremiseId"=d."iPremiseId"'.$where_clause.''; 
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