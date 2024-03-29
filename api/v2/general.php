<?php
include_once($controller_path . "fiber_inquiry.inc.php");
include_once($controller_path . "premise_type.inc.php");
include_once($controller_path . "premise_attribute.inc.php");
include_once($controller_path . "premise_sub_type.inc.php");
include_once($controller_path . "premise.inc.php");
include_once($controller_path . "zone.inc.php");
include_once($controller_path . "city.inc.php");
include_once($controller_path . "state.inc.php");
include_once($controller_path . "task_type.inc.php");
include_once($controller_path . "trap_type.inc.php");
include_once($controller_path . "county.inc.php");
include_once($controller_path . "zipcode.inc.php");
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

    $sql_state = "SELECT * FROM state_mas WHERE \"vStateCode\" = '" . $vStateCode . "' Limit 1";
    $rs_state = $sqlObj->GetAll($sql_state);

    $iStateId = 0;
    $vState = "---";
    if($rs_state){
        $iStateId = $rs_state[0]['iStateId'];
        $vState = $rs_state[0]['vState'];
    }

    $jsonData = array('iStateId' => $iStateId, 'vState' => $vState);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $jsonData);
}else if($request_type == "autoGooglegetZipcode"){
    $vZipcode = $RES_PARA['vZipcode'];
    $jsonData = array();

    $sql = 'SELECT "iZipcode", "vZipcode" FROM zipcode_mas WHERE zipcode_mas."vZipcode"=' . gen_allow_null_char($vZipcode) . ' LIMIT 1';
    $rs = $sqlObj->GetAll($sql);

    $iZipcode = 0;
    $vZipcode = "---";
    if($rs){
        $iZipcode = $rs[0]['iZipcode'];
        $vZipcode = $rs[0]['vZipcode'];
    }
    
    $jsonData = array('iZipcode' => $iZipcode, 'vZipcode' => $vZipcode);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $jsonData);
}else if($request_type == "autoGooglegetCity"){
    $vCity = $RES_PARA['vCity'];
    $vCounty = $RES_PARA['vCounty'];
    $jsonData = array();

    $sql_city = "SELECT * FROM city_mas WHERE \"vCity\" = '" . $vCity . "' Limit 1";
    $rs_city = $sqlObj->GetAll($sql_city);

    $iCityId = 0;
    $vCity = "---";
    $iCountyId = 0;
    $vCounty = "---";
    if($rs_city){
        $iCityId = $rs_city[0]['iCityId'];
        $vCity = $rs_city[0]['vCity'];
    }/*else {
        $sql = 'INSERT INTO city_mas ("vCity") VALUES (' . gen_allow_null_char($vCity) . ')';
        $sqlObj->Execute($sql);
        $iCityId = $sqlObj->Insert_ID();
    }*/

    $sql_county = "SELECT * FROM county_mas WHERE \"vCounty\" = '" . $vCounty . "' Limit 1";
    $rs_county = $sqlObj->GetAll($sql_county);

    if($rs_county){
        $iCountyId = $rs_county[0]['iCountyId'];
        $vCounty = $rs_county[0]['vCounty'];
    }/*else {
        $sql = 'INSERT INTO county_mas ("vCounty") VALUES (' . gen_allow_null_char($vCounty) . ')';
        $sqlObj->Execute($sql);
        $iCountyId = $sqlObj->Insert_ID();
    }*/

    $jsonData = array('iCityId' => $iCityId, 'iCountyId' => $iCountyId, 'vCity' => $vCity, 'vCounty' => $vCounty);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => $code, "Message" => $message, "result" => $jsonData);
}else if($request_type == "premise_type_dropdown"){
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
    $sess_iCompanyId = $RES_PARA['sess_iCompanyId'];
    $sess_vCompanyAccessType = $RES_PARA['sess_vCompanyAccessType'];

    $where_arr = array();
    if($iStatus != ''){
        $where_arr[] = "company_mas.\"iStatus\"='".$iStatus."'";
    }
    if($sess_iCompanyId != '' && $sess_vCompanyAccessType == "Carrier"){
        $where_arr[] = "company_mas.\"iCompanyId\"='".$sess_iCompanyId."'";
        $where_arr[] = "company_mas.\"vAccessType\"='".$sess_vCompanyAccessType."'";
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
}else if($request_type == "audience_type_dropdown"){
    $sql = 'SELECT * FROM audience_type_mas WHERE "iStatus" = 1 ORDER BY "iAudienceTypeId"';
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
}else if($request_type == "premise_circuit_dropdown"){

    $rs_data = array();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $ServiceOrderObj = new ServiceOrder();

    $iPremiseId = $RES_PARA['iPremiseId'];

    $where_arr[] = "service_order.\"iPremiseId\"='".gen_add_slash($iPremiseId)."'";

    $join_fieds_arr[] = 'w."iWOId"';
    //$join_fieds_arr[] = 'wt."vType" as "vWorkOrderType"';
    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
    $join_fieds_arr[] = 'pc."iPremiseCircuitId"';
    $join_fieds_arr[] = 'c."vCircuitName"';
    
    $join_arr[] = " LEFT JOIN workorder w ON service_order.\"iServiceOrderId\" = w.\"iServiceOrderId\" and service_order.\"iPremiseId\" = w.\"iPremiseId\"";
    //$join_arr[] = " LEFT JOIN workorder_type_mas wt ON w.\"iWOTId\" = wt.\"iWOTId\"";
    $join_arr[] = " LEFT JOIN premise_mas s ON service_order.\"iPremiseId\" = s.\"iPremiseId\"";
    $join_arr[] = " LEFT JOIN site_type_mas st ON s.\"iSTypeId\" = st.\"iSTypeId\"";
    $join_arr[] = " LEFT JOIN premise_circuit pc ON w.\"iWOId\" = pc.\"iWOId\"";
    $join_arr[] = " LEFT JOIN circuit c ON pc.\"iCircuitId\" = c.\"iCircuitId\"";
    $ServiceOrderObj->join_field = $join_fieds_arr;
    $ServiceOrderObj->join = $join_arr;
    $ServiceOrderObj->where = $where_arr;
    $ServiceOrderObj->param['limit'] = "LIMIT 1";
    $ServiceOrderObj->setClause();
    $ServiceOrderObj->debug_query = false;
    $rs = $ServiceOrderObj->recordset_list();
    //echo "<pre>";print_r($rs);exit;
    $ni = count($rs);
    if($ni > 0){
        for($i=0; $i<$ni; $i++){
            if($rs[$i]['iPremiseCircuitId'] > 0){
                //$vPremiseDisplay = " Workorder ID#".$rs[$i]['iWOId']." (".$rs[$i]['vWorkOrderType']."; Premise ID# ".$rs[$i]['iPremiseId'].";".$rs[$i]['vPremiseName'].";".$rs[$i]['vTypeName'].")";
                $vPremiseDisplay = " Premise Circuit ID#".$rs[$i]['iPremiseCircuitId']." (".$rs[$i]['vCircuitName']."; Premise ID# ".$rs[$i]['iPremiseId'].";".$rs[$i]['vPremiseName'].";".$rs[$i]['vTypeName'].")";

                $rs_data[$i]['iPremiseCircuitId'] = $rs[$i]['iPremiseCircuitId'];
                $rs_data[$i]['vPremiseDisplay'] = $vPremiseDisplay;
            }
        }
    }
    //echo "<pre>";print_r($rs_data);exit();
    if($rs_data){
        $response_data = array("Code" => 200, "result" => $rs_data, "total_record" => count($rs_data));
    }else{
        $response_data = array("Code" => 500, "result" => $rs_data);
    }
}else if($request_type == "premise_dropdown"){
    $sql = 'SELECT s."iPremiseId", s."vName", st."vTypeName" FROM premise_mas s LEFT JOIN site_type_mas st ON st."iSTypeId" = s."iSTypeId" WHERE s."iStatus" = 1 ORDER BY s."vName"';
    $rs = $sqlObj->GetAll($sql);
	$site_data = array();
    if($rs){
		$ni = count($rs);
		for($i=0; $i<$ni; $i++) {
			$site_data[$i]['iPremiseId'] = $rs[$i]['iPremiseId'];
			$site_data[$i]['vName'] = $rs[$i]['iPremiseId']."(".$rs[$i]['vName'].";".$rs[$i]['vTypeName'].")";
		}
        $response_data = array("Code" => 200, "result" => $site_data, "total_record" => count($site_data));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "get_premise_name_from_id"){
    $iPremiseId = $RES_PARA['iPremiseId'];
    $vPremiseName = '';
    if($iPremiseId > 0){
        $sql = 'SELECT s."iPremiseId", s."vName", st."vTypeName" FROM premise_mas s LEFT JOIN site_type_mas st ON st."iSTypeId" = s."iSTypeId" WHERE s."iPremiseId" = '.$iPremiseId.' LIMIT 1';
        $rs = $sqlObj->GetAll($sql);
        //echo "<pre>";print_r($rs);exit;
        
        if($rs){
            $vPremiseName = $rs[0]['vName']."; ".$rs[0]['vTypeName'];
            $response_data = array("Code" => 200, "result" => $vPremiseName);
        }
    }else{
        $response_data = array("Code" => 500, "result" => $vPremiseName);
    }
}
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400 , "Message" => $message);
}

?>