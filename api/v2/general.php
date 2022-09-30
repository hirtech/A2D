<?php
include_once($controller_path . "sr.inc.php");
include_once($controller_path . "premise_type.inc.php");
include_once($controller_path . "premise_attribute.inc.php");
include_once($controller_path . "premise_sub_type.inc.php");
include_once($controller_path . "zone.inc.php");
include_once($controller_path . "city.inc.php");
include_once($controller_path . "state.inc.php");
include_once($controller_path . "mosquito_species.inc.php");
include_once($controller_path . "treatment_product.inc.php");
include_once($controller_path . "task_type.inc.php");
include_once($controller_path . "trap_type.inc.php");
include_once($controller_path . "county.inc.php");
include_once($controller_path . "zipcode.inc.php");
include_once($controller_path . "agent_mosquito.inc.php");
include_once($controller_path . "test_method_mosquito.inc.php");
include_once($controller_path . "result.inc.php");

if($request_type == "get_county_zone"){
    $iZoneId = trim($RES_PARA['iZoneId']);
    $vZoneName = trim($RES_PARA['vZoneName']);
    $iStatus = trim($RES_PARA['iStatus']);
    $from_date = trim($RES_PARA['from_date']);
    $to_date = trim($RES_PARA['to_date']);
    $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"";
    $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"";

    $where_arr =array();
    $Zoneobj = new Zone();
    $Zoneobj->clear_variable();

    if($iZoneId != ""){
        $where_arr[] = ' "iZoneId" = '.$iZoneId.' '; 
    }

    if($vZoneName != ""){
        $where_arr[] = ' "vZoneName" ILIKE \''.$vZoneName.'%\' '; 
    }

    if($iStatus != ""){
        $where_arr[] = ' "iStatus" = '.$iStatus.' '; 
    }

    if((isset($from_date) && $from_date != "") && (isset($to_date) && $to_date != "")){
        $where_arr[] = " (( DATE(\"dAddedDate\") >= '" . $from_date . "' AND DATE(\"dAddedDate\") <= '" . $to_date. "')  OR (DATE(\"dModifiedDate\") >= '" . $from_date . "' AND DATE(\"dModifiedDate\") <= '" . $to_date. "' ))";
    }else {
        if((isset($from_date) && $from_date != "")){
            $where_arr[] =  " (DATE(\"dAddedDate\") >= '" . $from_date. "' OR  DATE(\"dModifiedDate\") >= '" . $from_date . "' ) ";
        }
        if((isset($to_date) && $to_date != "")){
            $where_arr[] =  " ( DATE(\"dAddedDate\") <= '" . $to_date. "' OR DATE(\"dModifiedDate\") <= '" . $to_date . "' ) ";
        }
    }
    if($start != "" && $page_length != ""){
       // $Zoneobj->param['limit'] = " LIMIT $start,  $page_length";
        $Zoneobj->param['limit'] = " LIMIT $page_length OFFSET $start";
    }else if($page_length != ""){
        $Zoneobj->param['limit'] = " LIMIT $page_length";
    }

    $Zoneobj->join_field = array();
    $Zoneobj->join = array();
    $Zoneobj->where = $where_arr;
    $Zoneobj->param['order_by'] = ' zone."vZoneName" asc ';
    $Zoneobj->setClause();
    $zone_data = $Zoneobj->getZoneWithCoordinate();
    
    $total_record = $Zoneobj->recordset_total();

    $result = array('data' =>$zone_data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "get_county_city"){
    $iCityId = trim($RES_PARA['iCityId']);
    $vCity = trim($RES_PARA['vCity']);
    $to_date = trim($RES_PARA['to_date']);
    $from_date = trim($RES_PARA['from_date']);
    $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"";
    $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"";

    $where_arr = array();
    $Cityobj = new City();
    $Cityobj->clear_variable();

    if($iCityId != ""){
        $where_arr[] = " city_mas.\"iCityId\" = " . $iCityId;
    }
    if($vCity != ""){
       $where_arr[] = " city_mas.\"vCity\" ILIKE '" . trim($vCity) . "%'";
    }
    if((isset($from_date) && $from_date != "") && (isset($to_date) && $to_date != "")){
        $where_arr[] = " (( DATE(city_mas.\"dAddedDate\") >= '" . $from_date . "' AND DATE(city_mas.\"dAddedDate\") <= '" . $to_date. "')  OR (DATE(city_mas.\"dModifiedDate\") >= '" . $from_date . "' AND DATE(city_mas.\"dModifiedDate\") <= '" . $to_date. "' ))";
    }else {
        if((isset($from_date) && $from_date != "")){
            $where_arr[] =  " (DATE(city_mas.\"dAddedDate\") >= '" . $from_date. "' OR  DATE(city_mas.\"dModifiedDate\") >= '" . $from_date . "' ) ";
        }
        if((isset($to_date) && $to_date != "")){
            $where_arr[] =  " ( DATE(city_mas.\"dAddedDate\") <= '" . $to_date. "' OR DATE(city_mas.\"dModifiedDate\") <= '" . $to_date . "' ) ";
        }
    }
    if($start != "" && $page_length != ""){
       // $Cityobj->param['limit'] = " LIMIT $start,  $page_length";
        $Cityobj->param['limit'] = " LIMIT $page_length OFFSET $start";
    }else if($page_length != ""){
        $Cityobj->param['limit'] = " LIMIT $page_length";
    }

    $Cityobj->join_field = array();
    $Cityobj->join = array();
    $Cityobj->where = $where_arr;
    $Cityobj->param['order_by'] = ' city_mas."vCity" asc ';
    $Cityobj->setClause();
    $city_data = $Cityobj->recordset_list();

    $total_record = $Cityobj->recordset_total();
    $result = array('data' =>$city_data , 'total_record' => $total_record);
    
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "get_county_state"){
    $iStateId = trim($RES_PARA['iStateId']);
    $vStateCode = trim($RES_PARA['vStateCode']);
    $vState = trim($RES_PARA['vState']);
    $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"";
    $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"";

    $where_arr =array();
    $Stateobj = new State();
    $Stateobj->clear_variable();
    if($iStateId != ""){
        $where_arr[] = ' "iStateId" = '.$iStateId.' '; 
    }

    if($vStateCode != ""){
        $where_arr[] = ' state_mas."vStateCode" ILIKE \'%'.$vStateCode.'%\' '; 
    }

    if($vState != ""){
        $where_arr[] = ' state_mas."vState" = ILIKE \'%'.$vStateCode.'%\' '; 
    }

    
    if($start != "" && $page_length != ""){
        $Stateobj->param['limit'] = " LIMIT $page_length OFFSET $start";
    }else if($page_length != ""){
        $Stateobj->param['limit'] = " LIMIT $page_length";
    }

    $Stateobj->join_field = array();
    $Stateobj->join = array();
    $Stateobj->where = $where_arr;
    $Stateobj->param['order_by'] = ' state_mas."vState" asc ';
    $Stateobj->setClause();
    $state_data = $Stateobj->getStateWithCoordinate();
    
    
    $total_record = $Stateobj->recordset_total();

    $result = array('data' =>$state_data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "get_mosquito_species_data"){
    
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = '"iStatus" =  1';

    $MosquitoSpeciesObj = new MosquitoSpecies();
    $MosquitoSpeciesObj->join_field = $join_fieds_arr;
    $MosquitoSpeciesObj->join = $join_arr;
    $MosquitoSpeciesObj->where = $where_arr;
    $MosquitoSpeciesObj->setClause();
    $rs_species = $MosquitoSpeciesObj->recordset_list();

    $total_record = count($rs_species);
    $result = array('total_record' => $total_record, 'data' => $rs_species );
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $result);
}else if($request_type == "get_insta_treat_data"){
    $sql = 'SELECT * FROM  setting_mas WHERE  "vName" IN (\'ENABLE_INSTA_TREATMENT\', \'INSTA_TREATMENT_PRODUCT_ID\', \'INSTA_TREATMENT_AREA\', \'INSTA_TREATMENT_AREA_TREATED\', \'INSTA_TREATMENT_AMOUNT_APPLIED\', \'INSTA_TREATMENT_UNIT_ID\') ';
    //echo $sql;
    $rs_db = $sqlObj->GetAll($sql);
    $data = array();
    //echo "<pre>";print_r($rs_db);exit;
    $ENABLE_INSTA_TREATMENT = $iTPId = $vArea = $vAreaTreated =$vAmountApplied =$iUId = "";
    if(!empty($rs_db)){
        foreach($rs_db as $k => $val){
            if($val['vName']=='ENABLE_INSTA_TREATMENT'){
                $ENABLE_INSTA_TREATMENT = ($val['vValue'] == 'Y')?"Yes":"No";
            }
            if($val['vName']=='INSTA_TREATMENT_PRODUCT_ID'){
                $iTPId = $val['vValue'];
            }
            if($val['vName']=='INSTA_TREATMENT_AREA'){
               $vArea = $val['vValue'];
            }
            if($val['vName']=='INSTA_TREATMENT_AREA_TREATED'){
                $vAreaTreated = $val['vValue'];
            }
            if($val['vName']=='INSTA_TREATMENT_AMOUNT_APPLIED'){
                $vAmountApplied = $val['vValue'];
            }
            if($val['vName']=='INSTA_TREATMENT_UNIT_ID'){
                $iUId = $val['vValue'];
            }
        }
    }
    $data = array(
            'insta_treatment_enable' => $ENABLE_INSTA_TREATMENT,
            'iTPId' => $iTPId,
            "vArea" =>  $vArea,
            "vAreaTreated" =>  $vAreaTreated,
            "vAmountApplied" => $vAmountApplied,
            "iUId" => $iUId
        );
    $result = array('data' => $data );
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $result);
    
}else if($request_type == "get_county_list"){
    $iCountyId = trim($RES_PARA['iCountyId']);
    $vCounty = trim($RES_PARA['vCounty']);
    $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"";
    $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"";

    $where_arr =array();
    $Countyobj = new County();
    if($iCountyId != ""){
        $where_arr[] = ' "iCountyId" = '.$iCountyId.' '; 
    }

    if($vCounty != ""){
        $where_arr[] = ' "vCounty" ILIKE \'%'.$vCounty.'%\' '; 
    }

    
    if($start != "" && $page_length != ""){
        $Countyobj->param['limit'] = " LIMIT $page_length OFFSET $start";
    }else if($page_length != ""){
        $Countyobj->param['limit'] = " LIMIT $page_length";
    }

    $Countyobj->join_field = array();
    $Countyobj->join = array();
    $Countyobj->where = $where_arr;
    $Countyobj->param['order_by'] = ' "vCounty" asc ';
    $Countyobj->setClause();
    $county_data = $Countyobj->recordset_list();
    
    $total_record = count($county_data);

    $result = array('data' =>$county_data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "get_sync_unit_data"){
    
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    $iUId = trim($RES_PARA['iUId']);
    $iParentId = trim($RES_PARA['iParentId']);
    $vUnit = trim($RES_PARA['vUnit']);
    $vDescription = trim($RES_PARA['vDescription']);
    $rStdUnitFactor = trim($RES_PARA['rStdUnitFactor']);
    $last_sync_date = isset($RES_PARA['last_sync_date'])?trim($RES_PARA['last_sync_date']):"";
    $current_date = Date('Y-m-d');

    if($iUId != ""){
        $where_arr[] = ' "iUId" = '.$iUId.' '; 
    }

    if($iParentId != ""){
        $where_arr[] = ' "iParentId" = \''.$iParentId.'\' '; 
    }

    if($vUnit != ""){
        $where_arr[] = ' "vUnit" ILIKE \'%'.$vUnit.'%\' '; 
    }

    if($vDescription != ""){
        $where_arr[] = ' "vDescription" ILIKE \'%'.$vDescription.'%\' '; 
    }

    if($rStdUnitFactor != ""){
        $where_arr[] = ' "rStdUnitFactor" = '.$rStdUnitFactor.' '; 
    }
    if($last_sync_date != ""){
      $where_arr[] = " (( DATE(\"dAddedDate\") >= '" . $last_sync_date . "' AND DATE(\"dAddedDate\") <= '" . $current_date. "')  OR (DATE(\"dModifiedDate\") >= '" . $last_sync_date . "' AND DATE(\"dModifiedDate\") <= '" . $current_date. "' ))";
    }

    $TProdObj = new TreatmentProduct();
    $TProdObj->join_field = $join_fieds_arr;
    $TProdObj->join = $join_arr;
    $TProdObj->where = $where_arr;
    $TProdObj->setClause();
    $TProdObj->debug_query = false;
    $rs_unit = $TProdObj->unit_data();

    $data=array();
    if(!empty($rs_unit)){
        $data = $rs_unit;
    }
    $total_record = count($data);
    $result = array('total_record' => $total_record, 'data' => $data );
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $result);
}else if($request_type == "get_county_zipcode"){
    $iZipcode = trim($RES_PARA['iZipcode']);
    $vZipcode = trim($RES_PARA['vZipcode']);
    $to_date = trim($RES_PARA['to_date']);
    $from_date = trim($RES_PARA['from_date']);
    $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"";
    $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"";

    $where_arr = array();
    $Zipobj = new Zipcode();
    $Zipobj->clear_variable();

    if($iZipcode != ""){
        $where_arr[] = " zipcode_mas.\"iZipcode\" = " . $iZipcode;
    }
    if($vZipcode != ""){
       $where_arr[] = " zipcode_mas.\"vZipcode\" ILIKE '" . trim($vZipcode) . "%'";
    }
    if((isset($from_date) && $from_date != "") && (isset($to_date) && $to_date != "")){
        $where_arr[] = " (( DATE(zipcode_mas.\"dAddedDate\") >= '" . $from_date . "' AND DATE(zipcode_mas.\"dAddedDate\") <= '" . $to_date. "')  OR (DATE(zipcode_mas.\"dModifiedDate\") >= '" . $from_date . "' AND DATE(zipcode_mas.\"dModifiedDate\") <= '" . $to_date. "' ))";
    }else {
        if((isset($from_date) && $from_date != "")){
            $where_arr[] =  " (DATE(zipcode_mas.\"dAddedDate\") >= '" . $from_date. "' OR  DATE(zipcode_mas.\"dModifiedDate\") >= '" . $from_date . "' ) ";
        }
        if((isset($to_date) && $to_date != "")){
            $where_arr[] =  " ( DATE(zipcode_mas.\"dAddedDate\") <= '" . $to_date. "' OR DATE(zipcode_mas.\"dModifiedDate\") <= '" . $to_date . "' ) ";
        }
    }
    if($start != "" && $page_length != ""){
        $Zipobj->param['limit'] = " LIMIT $page_length OFFSET $start";
    }else if($page_length != ""){
        $Zipobj->param['limit'] = " LIMIT $page_length";
    }

    $Zipobj->join_field = array();
    $Zipobj->join = array();
    $Zipobj->where = $where_arr;
    $Zipobj->param['order_by'] = ' zipcode_mas."vZipcode" asc ';
    $Zipobj->setClause();
    $zip_data = $Zipobj->recordset_list();

    $total_record = $Zipobj->recordset_total();
    $result = array('data' =>$zip_data , 'total_record' => $total_record);
    
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else if($request_type == "task_type_dropdown"){
    
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    
    $TaskTypeObj = new TaskType();
    
    $where_arr[] = '"iStatus" =  1';
    $TaskTypeObj->join_field = $join_fieds_arr;
    $TaskTypeObj->join = $join_arr;
    $TaskTypeObj->where = $where_arr;
    $TaskOtherObj->param['order_by'] = '"vTypeName"';
    $TaskTypeObj->setClause();
    $rs_db = $TaskTypeObj->recordset_list();

    if($rs_db){
        $response_data = array("Code" => 200, "result" => $rs_db, "total_record" => count($rs_db));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "treatment_product_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $TProdObj = new TreatmentProduct();
    $TProdObj->join_field = $join_fieds_arr;
    $TProdObj->join = $join_arr;
    $TProdObj->where = $where_arr;
    $rs_db = $TProdObj->recordset_list();

    if($rs_db){
      $response_data = array("Code" => 200, "result" => $rs_db, "total_record" => count($rs_db));
    }else{
      $response_data = array("Code" => 500);
    }
} else if($request_type == "search_treatment_product"){
    //treatment prodcut with unit data
    
    $rs_arr  = array();

    $vTreatmentProduct = $RES_PARA['trProduct'];
    $TProdObj = new TreatmentProduct();  
    $TProdObj->clear_variable();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = 'unit_mas."vUnit"';
    $join_fieds_arr[] = 'unit_mas."iParentId"';
    $where_arr[] = 'treatment_product."vName" ILIKE \''.$vTreatmentProduct.'%\' ';
    
    $join_arr[] = 'LEFT JOIN unit_mas  on unit_mas."iUId" = treatment_product."iUId"';
    $TProdObj->join_field = $join_fieds_arr;
    $TProdObj->join = $join_arr;
    $TProdObj->where = $where_arr;
    $TProdObj->param['limit'] = "0";
    $TProdObj->param['order_by'] = 'treatment_product."iTPId" DESC';
    
    $TProdObj->setClause();
    $rs_data = $TProdObj->recordset_list();
   // echo "<pre>";print_r($rs_data);exit();
    $n = count($rs_data);
    for ($i = 0; $i < $n; $i++) {
        $rs_arr[] =array(
            'display' => $rs_data[$i]['vName'],
            'iTPId' => $rs_data[$i]['iTPId'],
            'unitName' => $rs_data[$i]['vUnit'],
            'vAppRate' => $rs_data[$i]['vAppRate'],
            'vTragetAppRate' => $rs_data[$i]['vTragetAppRate'],
            'vMinAppRate' => $rs_data[$i]['vMinAppRate'],
            'vMaxAppRate' => $rs_data[$i]['vMaxAppRate'],
            'iUId' => $rs_data[$i]['iUId'],
            'iParentId' => $rs_data[$i]['iParentId']
        );
    }

    $response_data = array("Code" => 200, "result" => $rs_arr);
}else if($request_type == "unit_multi_dropdown"){
    //unit dropdown  (parent unit name as key and sub child unit data in value)
    $temp_unit_arr = array();
    $temp_unit_par_arr = array();
    $unit_arr = array();

    /* unit array*/
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    $TProdObj = new TreatmentProduct();
    $TProdObj->join_field = $join_fieds_arr;
    $TProdObj->join = $join_arr;
    $TProdObj->where = $where_arr;
    $TProdObj->param['order_by'] = "";
    $TProdObj->param['limit'] = "";
    $TProdObj->setClause();
    $TProdObj->debug_query = false;
    $rs_unit = $TProdObj->unit_data();
    $ui = count($rs_unit);

    if($ui > 0){
        for($u=0;$u<$ui;$u++){
            if($rs_unit[$u]['iParentId'] != 0){
                $temp_unit_arr[$rs_unit[$u]['iParentId']][] = $rs_unit[$u];
            }else{
                $temp_unit_par_arr[$rs_unit[$u]['iUId']] = $rs_unit[$u]['vUnit'];
            }
        }
        if(count($temp_unit_arr) > 0){
            foreach($temp_unit_arr as $key=>$val){
                $unit_arr[$temp_unit_par_arr[$key]] = $val;
            }
        }
    }
    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $unit_arr);
}
else if($request_type == "agent_mosquito_dropdown"){

    $AgentMosquitoObj = new AgentMosquito();
    $res_arr = array();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = '"iStatus" =  1';
    $AgentMosquitoObj->join_field = $join_fieds_arr;
    $AgentMosquitoObj->join = $join_arr;
    $AgentMosquitoObj->where = $where_arr;
    $AgentMosquitoObj->setClause();
    $rs_data = $AgentMosquitoObj->recordset_list();
    $n = count($rs_data);

    $res_arr[] = array("iAMId" => "", "vTitle" => "");
    if($n > 0){
        for ($i=0; $i <$n ; $i++) { 
            $res_arr[] = array("iAMId" => $rs_data[$i]['iAMId'], "vTitle" => $rs_data[$i]['vTitle']);
        }
    }

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $res_arr);
}
else if($request_type == "test_method_mosquito_dropdown"){
    
    $TestMetodMosquitoObj = new TestMetodMosquito();
    $res_arr = array();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = '"iStatus" =  1';
    $TestMetodMosquitoObj->join_field = $join_fieds_arr;
    $TestMetodMosquitoObj->join = $join_arr;
    $TestMetodMosquitoObj->where = $where_arr;
    $TestMetodMosquitoObj->setClause();
    $rs_data = $TestMetodMosquitoObj->recordset_list();
    $n = count($rs_data);

    $res_arr[] = array("iTMMId" => "", "vMethodTitle" => "");
    if($n > 0){
        for ($i=0; $i <$n ; $i++) { 
            $res_arr[] = array("iTMMId" => $rs_data[$i]['iTMMId'], "vMethodTitle" => $rs_data[$i]['vMethodTitle']);
        }
    }

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $res_arr);
}
else if($request_type == "result_dropdown"){

    $ResultObj = new Result();
    $res_arr = array();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = '"iStatus" =  1';
    $ResultObj->join_field = $join_fieds_arr;
    $ResultObj->join = $join_arr;
    $ResultObj->where = $where_arr;
    $ResultObj->setClause();
    $rs_data = $ResultObj->recordset_list();
    $n = count($rs_data);

    $res_arr[] = array("iResultId" => "", "vResult" => "");
    if($n > 0){
        for ($i=0; $i <$n ; $i++) { 
            $res_arr[] = array("iResultId" => $rs_data[$i]['iResultId'], "vResult" => $rs_data[$i]['vResult']);
        }
    }

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $res_arr);
}
 else if($request_type == "autoGoogleZoneFromLatlong"){
    $lat = $RES_PARA['lat'];
    $long = $RES_PARA['long'];
    //echo"<pre>";print_r($RES_PARA);exit;
    $iZoneId = 0;
    $jsonData = array();
    if ($lat != "" && $long != "") {
        $sql_zone = "SELECT zone.\"iZoneId\" FROM zone WHERE  St_Within(ST_GeometryFromText('POINT(".$long." ".$lat.")', 4326)::geometry, (zone.\"PShape\")::geometry)='t'"; 
        $rs = $sqlObj->GetAll($sql_zone);
        
        if($rs){
            $iZoneId = $rs[0]['iZoneId'];
        }
        
        $jsonData = array('iZoneId' => $iZoneId, "lat"=>$lat, "long"=>$long); 
    } 

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $jsonData);
} else if($request_type == "autoGoogleCheckCityState"){
    $state_code = $RES_PARA['state_code'];
    $city = $RES_PARA['city'];
    $jsonData = array();

    $sql_state = "SELECT \"vStateCode\" FROM state_mas WHERE \"vStateCode\" = '" . $state_code . "'";
    $rs_state = $sqlObj->GetAll($sql_state);

    $sql_city = "SELECT \"vCity\" FROM city_mas WHERE \"vCity\" = '" . $city . "'";
    $rs_city = $sqlObj->GetAll($sql_city);
    
    $check = 0;
    //echo $rs_state[0]['vStateCode']. " === ". $state_code ." && ".$city ." == ".$rs_city[0]['vCity'];exit;
    if ($rs_state[0]['vStateCode'] == $state_code && $city == $rs_city[0]['vCity']) {
        $check = 1;
    }else {
        if(empty($rs_city)){
            $cityObj = new City();
            $cityObj->insert_arr = array( "vCity" => $city);
            $cityObj->setClause();
            $iCityId = $cityObj->add_records();
            if($iCityId){
                $check = 1;
            }
        }        
    }
    $jsonData = array('check' => $check);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $jsonData);
} else if($request_type == "autoGooglegetState"){
    $vStateCode = $RES_PARA['vStateCode'];
    $jsonData = array();

    $sql_state = "SELECT \"iStateId\" FROM state_mas WHERE \"vStateCode\" = '" . $vStateCode . "' Limit 1";
    $rs_state = $sqlObj->GetAll($sql_state);

    $iStateId = 0;
    if($rs_state){
        $iStateId = $rs_state[0]['iStateId'];
    }

    $jsonData = array('iStateId' => $iStateId);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $jsonData);
} else if($request_type == "autoGooglegetZipcode"){
    $vZipcode = $RES_PARA['vZipcode'];
    $jsonData = array();

    $sql = 'SELECT "iZipcode" FROM zipcode_mas WHERE zipcode_mas."vZipcode"=' . gen_allow_null_char($vZipcode) . ' LIMIT 1';
    $rs = $sqlObj->GetAll($sql);

    $iZipcode = 0;
    if($rs){
        $iZipcode = $rs[0]['iZipcode'];
    }
    
    $jsonData = array('iZipcode' => $iZipcode);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $jsonData);
} else if($request_type == "autoGooglegetCity"){
    $vCity = $RES_PARA['vCity'];
    $vCounty = $RES_PARA['vCounty'];
    $jsonData = array();

    $sql_city = "SELECT \"iCityId\" FROM city_mas WHERE \"vCity\" = '" . $vCity . "' Limit 1";
    $rs_city = $sqlObj->GetAll($sql_city);

    $iCityId = 0;
    $iCountyId = 0;
    if($rs_city){
        $iCityId = $rs_city[0]['iCityId'];
    }/*else {
        $sql = 'INSERT INTO city_mas ("vCity") VALUES (' . gen_allow_null_char($vCity) . ')';
        $sqlObj->Execute($sql);
        $iCityId = $sqlObj->Insert_ID();
    }*/

    $sql_county = "SELECT \"iCountyId\" FROM county_mas WHERE \"vCounty\" = '" . $vCounty . "' Limit 1";
    $rs_county = $sqlObj->GetAll($sql_county);

    if($rs_county){
        $iCountyId = $rs_county[0]['iCountyId'];
    }/*else {
        $sql = 'INSERT INTO county_mas ("vCounty") VALUES (' . gen_allow_null_char($vCounty) . ')';
        $sqlObj->Execute($sql);
        $iCountyId = $sqlObj->Insert_ID();
    }*/

    $jsonData = array('iCityId' => $iCityId, 'iCountyId' => $iCountyId);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $jsonData);
} else if ($request_type == "premise_type_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    
    $SiteTypeObj = new SiteType();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "site_type_mas.\"iStatus\"='".$iStatus."'";
    }
    $SiteTypeObj->where = $where_arr;
    $SiteTypeObj->param['order_by'] = "site_type_mas.\"vTypeName\"";
    $SiteTypeObj->setClause();
    $rs_sitetype = $SiteTypeObj->recordset_list();
    if($rs_sitetype){
        $response_data = array("Code" => 200, "result" => $rs_sitetype, "total_record" => count($rs_sitetype));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "premise_sub_type_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();

    $SiteSubTypeObj = new SiteSubType();
    $iStatus = $RES_PARA['iStatus'];
    $iSTypeId = $RES_PARA['iSTypeId'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "site_sub_type_mas.\"iStatus\"='".$iStatus."'";
    }
    if($iSTypeId != ''){
        $where_arr[] = "site_sub_type_mas.\"iSTypeId\"='".$iSTypeId."'";
    }
    $SiteSubTypeObj->where = $where_arr;
    $SiteSubTypeObj->param['order_by'] = "site_sub_type_mas.\"vSubTypeName\"";
    $SiteSubTypeObj->setClause();
    $rs_sstype = $SiteSubTypeObj->recordset_list();
    if($rs_sstype){
        $response_data = array("Code" => 200, "result" => $rs_sstype, "total_record" => count($rs_sstype));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "premise_attribute_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $SiteAttribute = new SiteAttribute();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "site_attribute_mas.\"iStatus\"='".$iStatus."'";
    }
    $SiteAttribute->where = $where_arr;
    $SiteAttribute->param['order_by'] = "site_attribute_mas.\"vAttribute\"";
    $SiteAttribute->setClause();
    $rs_sitetype = $SiteAttribute->recordset_list();
    if($rs_sitetype){
        $response_data = array("Code" => 200, "result" => $rs_sitetype, "total_record" => count($rs_sitetype));
    }else{
        $response_data = array("Code" => 500);
    }
}
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400 , "Message" => $message);
}

?>