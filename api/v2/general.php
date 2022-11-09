<?php
include_once($controller_path . "fiber_inquiry.inc.php");
include_once($controller_path . "premise_type.inc.php");
include_once($controller_path . "premise_attribute.inc.php");
include_once($controller_path . "premise_sub_type.inc.php");
include_once($controller_path . "premise.inc.php");
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
include_once($controller_path . "department.inc.php");
include_once($controller_path . "engagement.inc.php");
include_once($controller_path . "company.inc.php");
include_once($controller_path . "connection_type.inc.php");
include_once($controller_path . "service_type.inc.php");
include_once($controller_path . "workorder_type.inc.php");
include_once($controller_path . "contact.inc.php");
include_once($controller_path . "service_order.inc.php");
include_once($controller_path . "user.inc.php");
include_once($controller_path . "equipment_type.inc.php");
include_once($controller_path . "equipment_manufacturer.inc.php");
include_once($controller_path . "equipment_model.inc.php");
include_once($controller_path . "circuit_type.inc.php");
include_once($controller_path . "circuit.inc.php");

if($request_type == "department_dropdown") {
	$DepartmentObj = new Department();
	$where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "department_mas.\"iStatus\"='".$iStatus."'";
    }
	$DepartmentObj->where = $where_arr;
	$DepartmentObj->param['order_by'] = "department_mas.\"vDepartment\"";
	$DepartmentObj->setClause();
	$rs_department = $DepartmentObj->recordset_list();
	if($rs_department){
        $response_data = array("Code" => 200, "result" => $rs_department, "total_record" => count($rs_department));
    }else{
        $response_data = array("Code" => 500);
    }
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
}else if($request_type == "task_type_dropdown"){
    
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
}else if($request_type == "search_treatment_product"){
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
}else if($request_type == "agent_mosquito_dropdown"){

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
}else if($request_type == "test_method_mosquito_dropdown"){
    
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
}else if($request_type == "result_dropdown"){

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
}else if($request_type == "autoGoogleZoneFromLatlong"){
    $lat = $RES_PARA['lat'];
    $long = $RES_PARA['long'];
    //echo"<pre>";print_r($RES_PARA);exit;
    $iZoneId = $iNetworkId = 0;
    $vZoneName = $vNetwork = '';
    $jsonData = array();
    if ($lat != "" && $long != "") {
        $sql_zone = "SELECT zone.\"iZoneId\", zone.\"vZoneName\" FROM zone WHERE  St_Within(ST_GeometryFromText('POINT(".$long." ".$lat.")', 4326)::geometry, (zone.\"PShape\")::geometry)='t'"; 
        $rs = $sqlObj->GetAll($sql_zone);
        if($rs){
            $iZoneId = $rs[0]['iZoneId'];
            $vZoneName = $rs[0]['vZoneName'];
            $sql_ntwork = "SELECT \"iNetworkId\", \"vName\" FROM network WHERE \"iZoneId\" = '" . $iZoneId . "'";
            $rs_ntwork = $sqlObj->GetAll($sql_ntwork);
            if($rs_ntwork){
                $iNetworkId = $rs_ntwork[0]['iNetworkId'];
                $vNetwork = $rs_ntwork[0]['vName'];
            }
        }

        
        $jsonData = array('iZoneId' => $iZoneId, 'vZoneName' => $vZoneName, 'iNetworkId' => $iNetworkId, 'vNetwork' => $vNetwork, "lat"=>$lat, "long"=>$long); 
    } 

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $jsonData);
}else if($request_type == "autoGoogleCheckCityState"){
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
}else if($request_type == "autoGooglegetState"){
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
}else if($request_type == "autoGooglegetZipcode"){
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
}else if($request_type == "autoGooglegetCity"){
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
}else if ($request_type == "premise_type_dropdown"){
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
}else if($request_type == "engagement_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $EngagementObj = new Engagement();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "engagement_mas.\"iStatus\"='".$iStatus."'";
    }
    $EngagementObj->where = $where_arr;
    $EngagementObj->param['order_by'] = "engagement_mas.\"vEngagement\"";
    $EngagementObj->setClause();
    $rs_eng = $EngagementObj->recordset_list();
    if($rs_eng){
        $response_data = array("Code" => 200, "result" => $rs_eng, "total_record" => count($rs_eng));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "company_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $CompanyObj = new Company();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "company_mas.\"iStatus\"='".$iStatus."'";
    }
    $CompanyObj->where = $where_arr;
    $CompanyObj->param['order_by'] = "company_mas.\"vCompanyName\"";
    $CompanyObj->setClause();
    $rs_company = $CompanyObj->recordset_list();
    //print_r($rs_company);exit;
    if($rs_company){
        $response_data = array("Code" => 200, "result" => $rs_company, "total_record" => count($rs_company));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "connection_type_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $ConnectionTypeObj = new ConnectionType();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "connection_type_mas.\"iStatus\"='".$iStatus."'";
    }
    $ConnectionTypeObj->where = $where_arr;
    $ConnectionTypeObj->param['order_by'] = "connection_type_mas.\"vConnectionTypeName\"";
    $ConnectionTypeObj->setClause();
    $rs_connection_type = $ConnectionTypeObj->recordset_list();
    //print_r($rs_connection_type);exit;
    if($rs_connection_type){
        $response_data = array("Code" => 200, "result" => $rs_connection_type, "total_record" => count($rs_connection_type));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "service_type_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $ServiceTypeObj = new ServiceType();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "service_type_mas.\"iStatus\"='".$iStatus."'";
    }
    $ServiceTypeObj->where = $where_arr;
    $ServiceTypeObj->param['order_by'] = "service_type_mas.\"vServiceType\"";
    $ServiceTypeObj->setClause();
    $rs_service_type = $ServiceTypeObj->recordset_list();
    //print_r($rs_service_type);exit;
    if($rs_service_type){
        $response_data = array("Code" => 200, "result" => $rs_service_type, "total_record" => count($rs_service_type));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "workorder_type_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $WorkOrderTypeObj = new WorkOrderType();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "workorder_type_mas.\"iStatus\"='".$iStatus."'";
    }
    $WorkOrderTypeObj->where = $where_arr;
    $WorkOrderTypeObj->param['order_by'] = "workorder_type_mas.\"vType\"";
    $WorkOrderTypeObj->setClause();
    $rs_wo_type = $WorkOrderTypeObj->recordset_list();
    if($rs_wo_type){
        $response_data = array("Code" => 200, "result" => $rs_wo_type, "total_record" => count($rs_wo_type));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "contact_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $ContactObj = new Contact();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "contact_mas.\"iStatus\"='".$iStatus."'";
    }
    $ContactObj->where = $where_arr;
    $ContactObj->param['order_by'] = "contact_mas.\"vFirstName\"";
    $ContactObj->setClause();
    $rs_contact= $ContactObj->recordset_list();
    if($rs_contact){
        $ni = count($rs_contact);
        for($i=0; $i<$ni; $i++){
            $vName = $rs_contact[$i]['vFirstName']." ".$rs_contact[$i]['vLastName'];
            $vDisplay = $vName;
            if($rs_contact[$i]['vEmail'] != ""){
                $vDisplay .= " | ".$rs_contact[$i]['vEmail'];
            }
            if($rs_contact[$i]['vPhone'] != ""){
                $vDisplay .= " | ".$rs_contact[$i]['vPhone'];
            }
            $rs_contact[$i]['vName'] = $vName;
            $rs_contact[$i]['vDisplay'] = $vDisplay;
        }
        $response_data = array("Code" => 200, "result" => $rs_contact, "total_record" => count($rs_contact));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "workorder_status_dropdown"){
    $sql = 'SELECT * FROM workorder_status_mas WHERE "iStatus" = 1 ORDER BY "iWOSId"';
    $rs = $sqlObj->GetAll($sql);
    if($rs){
        $response_data = array("Code" => 200, "result" => $rs, "total_record" => count($rs));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "service_order_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $ServiceOrderObj = new ServiceOrder();
    $where_arr = array();
    $ServiceOrderObj->where = $where_arr;
    $ServiceOrderObj->param['order_by'] = "service_order.\"iServiceOrderId\"";
    $ServiceOrderObj->setClause();
    $rs_sorder= $ServiceOrderObj->recordset_list();
    if($rs_sorder){
        $ni = count($rs_sorder);
        for($i=0; $i<$ni; $i++){
            $vSODetails = "SO #".$rs_sorder[$i]['iServiceOrderId'].": ".$rs_sorder[$i]['vServiceOrder'];
            $rs_sorder[$i]['vSODetails'] = $vSODetails;
        }
        $response_data = array("Code" => 200, "result" => $rs_sorder, "total_record" => count($rs_contact));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "user_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $UserObj = new User();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "user_mas.\"iStatus\"='".$iStatus."'";
    }
	$join_fieds_arr[] = "ud.\"vPhone\"";
    $join_arr[] = 'LEFT JOIN user_details ud on user_mas."iUserId" = ud."iUserId"';
	$UserObj->join_field = $join_fieds_arr;
    $UserObj->join = $join_arr;
    $UserObj->where = $where_arr;
    $UserObj->param['order_by'] = "user_mas.\"vFirstName\"";
    $UserObj->setClause();
    $rs_contact= $UserObj->recordset_list();
	//echo "<pre>";print_r($rs_contact);exit;
    if($rs_contact){
        $ni = count($rs_contact);
        for($i=0; $i<$ni; $i++){
            $vName = $rs_contact[$i]['vFirstName']." ".$rs_contact[$i]['vLastName'];
            $vDisplay = $vName;
            if($rs_contact[$i]['vEmail'] != ""){
                $vDisplay .= " | ".$rs_contact[$i]['vEmail'];
            }
            if($rs_contact[$i]['vPhone'] != ""){
                $vDisplay .= " | ".$rs_contact[$i]['vPhone'];
            }
            $rs_contact[$i]['vName'] = $vName;
            $rs_contact[$i]['vDisplay'] = $vDisplay;
        }
        $response_data = array("Code" => 200, "result" => $rs_contact, "total_record" => count($rs_contact));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "equipment_type_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $EquipmentTypeObj = new EquipmentType();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "equipment_type_mas.\"iStatus\"='".$iStatus."'";
    }
    $EquipmentTypeObj->where = $where_arr;
    $EquipmentTypeObj->param['order_by'] = "equipment_type_mas.\"vEquipmentType\"";
    $EquipmentTypeObj->setClause();
    $rs_etype = $EquipmentTypeObj->recordset_list();
    if($rs_etype){
        $response_data = array("Code" => 200, "result" => $rs_etype, "total_record" => count($rs_etype));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "equipment_manufacturer_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $EquipmentManufacturerObj = new EquipmentManufacturer();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "equipment_manufacturer_mas.\"iStatus\"='".$iStatus."'";
    }
    $EquipmentManufacturerObj->where = $where_arr;
    $EquipmentManufacturerObj->param['order_by'] = "equipment_manufacturer_mas.\"vEquipmentManufacturer\"";
    $EquipmentManufacturerObj->setClause();
    $rs_emanu = $EquipmentManufacturerObj->recordset_list();
    if($rs_emanu){
        $response_data = array("Code" => 200, "result" => $rs_emanu, "total_record" => count($rs_emanu));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "equipment_model_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $EquipmentModelObj = new EquipmentModel();
    $where_arr = array();
    $EquipmentModelObj->where = $where_arr;
    $EquipmentModelObj->param['order_by'] = "equipment_model.\"vModelName\"";
    $EquipmentModelObj->setClause();
    $rs_model = $EquipmentModelObj->recordset_list();
    if($rs_model){
        $response_data = array("Code" => 200, "result" => $rs_model, "total_record" => count($rs_model));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "material_dropdown"){
    $sql = 'SELECT * FROM material_mas WHERE "iStatus" = 1 ORDER BY "vMaterial"';
    $rs = $sqlObj->GetAll($sql);
    if($rs){
        $response_data = array("Code" => 200, "result" => $rs, "total_record" => count($rs));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "power_dropdown"){
    $sql = 'SELECT * FROM power_mas WHERE "iStatus" = 1 ORDER BY "vPower"';
    $rs = $sqlObj->GetAll($sql);
    if($rs){
        $response_data = array("Code" => 200, "result" => $rs, "total_record" => count($rs));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "install_type_dropdown"){
    $sql = 'SELECT * FROM install_type_mas WHERE "iStatus" = 1 ORDER BY "vInstallType"';
    $rs = $sqlObj->GetAll($sql);
    if($rs){
        $response_data = array("Code" => 200, "result" => $rs, "total_record" => count($rs));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "link_type_dropdown"){
    $sql = 'SELECT * FROM link_type_mas WHERE "iStatus" = 1 ORDER BY "vLinkType"';
    $rs = $sqlObj->GetAll($sql);
    if($rs){
        $response_data = array("Code" => 200, "result" => $rs, "total_record" => count($rs));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "operational_status_dropdown"){
    $sql = 'SELECT * FROM operational_status_mas WHERE "iStatus" = 1 ORDER BY "vOperationalStatus"';
    $rs = $sqlObj->GetAll($sql);
    if($rs){
        $response_data = array("Code" => 200, "result" => $rs, "total_record" => count($rs));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "event_type_dropdown"){
    $sql = 'SELECT * FROM event_type_mas WHERE "iStatus" = 1 ORDER BY "vEventType"';
    $rs = $sqlObj->GetAll($sql);
    if($rs){
        $response_data = array("Code" => 200, "result" => $rs, "total_record" => count($rs));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "zipcode_dropdown"){
    $sql = 'SELECT * FROM zipcode_mas ORDER BY "vZipcode"';
    $rs = $sqlObj->GetAll($sql);
    if($rs){
        $response_data = array("Code" => 200, "result" => $rs, "total_record" => count($rs));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "city_dropdown"){
    $sql = 'SELECT * FROM city_mas ORDER BY "vCity"';
    $rs = $sqlObj->GetAll($sql);
    if($rs){
        $response_data = array("Code" => 200, "result" => $rs, "total_record" => count($rs));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "circuit_type_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $CircuitTypeObj = new CircuitType();
    $iStatus = $RES_PARA['iStatus'];
    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "circuit_type_mas.\"iStatus\"='".$iStatus."'";
    }
    $CircuitTypeObj->where = $where_arr;
    $CircuitTypeObj->param['order_by'] = "circuit_type_mas.\"vCircuitType\"";
    $CircuitTypeObj->setClause();
    $rs_ctype = $CircuitTypeObj->recordset_list();
    if($rs_ctype){
        $response_data = array("Code" => 200, "result" => $rs_ctype, "total_record" => count($rs_ctype));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "circuit_dropdown"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $CircuitObj = new Circuit();
    $where_arr = array();
    $CircuitObj->where = $where_arr;
    $CircuitObj->param['order_by'] = "circuit.\"vCircuitName\"";
    $CircuitObj->setClause();
    $rs_ctype = $CircuitObj->recordset_list();
    if($rs_ctype){
        $response_data = array("Code" => 200, "result" => $rs_ctype, "total_record" => count($rs_ctype));
    }else{
        $response_data = array("Code" => 500);
    }
}else if ($request_type == "premise_circuit_dropdown"){

    $rs_data = array();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $ServiceOrderObj = new ServiceOrder();

    $iPremiseId = $RES_PARA['iPremiseId'];

    $where_arr[] = "service_order.\"iPremiseId\"='".gen_add_slash($iPremiseId)."'";

    $join_fieds_arr[] = 'wt."vType" as "vWorkOrderType"';
    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
    $join_fieds_arr[] = 'pc."iPremiseCircuitId"';
    
    $join_arr[] = " LEFT JOIN workorder w ON service_order.\"iServiceOrderId\" = w.\"iServiceOrderId\"";
    $join_arr[] = " LEFT JOIN workorder_type_mas wt ON w.\"iWOTId\" = wt.\"iWOTId\"";
    $join_arr[] = " LEFT JOIN site_mas s ON service_order.\"iPremiseId\" = s.\"iSiteId\"";
    $join_arr[] = " LEFT JOIN site_type_mas st ON s.\"iSTypeId\" = st.\"iSTypeId\"";
    $join_arr[] = " LEFT JOIN premise_circuit pc ON w.\"iWOId\" = pc.\"iWOId\"";
    $ServiceOrderObj->join_field = $join_fieds_arr;
    $ServiceOrderObj->join = $join_arr;
    $ServiceOrderObj->where = $where_arr;
    $ServiceOrderObj->param['limit'] = "LIMIT 1";
    $ServiceOrderObj->setClause();
    $ServiceOrderObj->debug_query = false;
    $rs = $ServiceOrderObj->recordset_list();
    $ni = count($rs);
    if($ni > 0){
        for($i=0; $i<$ni; $i++){
            $vPremiseDisplay = " Workorder ID#".$rs[$i]['iWOId']." (".$rs[$i]['vWorkOrderType']."; Premise ID# ".$rs[$i]['iPremiseId'].";".$rs[$i]['vPremiseName'].";".$rs[$i]['vTypeName'].")";

            $rs_data[$i]['iPremiseCircuitId'] = $rs[$i]['iPremiseCircuitId'];
            $rs_data[$i]['vPremiseDisplay'] = $vPremiseDisplay;
        }
    }
    //echo "<pre>";print_r($rs_data);exit();
    if($rs_data){
        $response_data = array("Code" => 200, "result" => $rs_data, "total_record" => count($rs_data));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "premise_dropdown"){
    $sql = 'SELECT s."iSiteId", s."vName", st."vTypeName" FROM site_mas s LEFT JOIN site_type_mas st ON st."iSTypeId" = s."iSTypeId" WHERE s."iStatus" = 1 ORDER BY s."vName"';
    $rs = $sqlObj->GetAll($sql);
	$site_data = array();
    if($rs){
		$ni = count($rs);
		for($i=0; $i<$ni; $i++) {
			$site_data[$i]['iSiteId'] = $rs[$i]['iSiteId'];
			$site_data[$i]['vName'] = $rs[$i]['iSiteId']."(".$rs[$i]['vName'].";".$rs[$i]['vTypeName'].")";
		}
        $response_data = array("Code" => 200, "result" => $site_data, "total_record" => count($site_data));
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