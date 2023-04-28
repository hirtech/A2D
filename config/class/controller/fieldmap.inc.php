<?php
include_once("security_audit_log.inc.php");

class Fieldmap {

    function Fieldmap() {
        $this->SALObj = new Security_audit_log();
    }

    public function getZones(){
        global $sqlObj;

        $sql = 'SELECT "iZoneId", "vZoneName" , "iStatus" FROM "zone" WHERE "iStatus" = 1 ORDER BY "vZoneName" asc';
        $skZones = $sqlObj->GetAll($sql);
        return $skZones;
    }

    public function getPremiseType(){
        global $sqlObj;
        $siteTypeSql = 'SELECT "iSTypeId", "vTypeName" FROM "site_type_mas" WHERE "iStatus" = 1 ORDER BY "vTypeName" asc';
        $sType = $sqlObj->GetAll($siteTypeSql);
        $sTypeArr = array();
        foreach($sType as $k => $row){
            $sSTypeSql = 'SELECT "iSSTypeId", "vSubTypeName" FROM "site_sub_type_mas" WHERE "iSTypeId" = '.$row['iSTypeId'].' AND "iStatus" = 1 ORDER BY "vSubTypeName"';
            $sSType = $sqlObj->GetAll($sSTypeSql);
            $sTypeArr[$k] = $row;
            $sTypeArr[$k]['premise_sub_types'] = $sSType;
        }
        return $sTypeArr;
    }

    public function getAttributes(){
        global $sqlObj;

        $sql = 'SELECT "iSAttributeId", "vAttribute", "iStatus" FROM "site_attribute_mas"  WHERE "iStatus" = 1  ORDER BY "vAttribute" asc';
        $sAttr = $sqlObj->GetAll($sql);
        return $sAttr;
    }

    public function getNetworks(){
        global $sqlObj;

        $sql = 'SELECT "iNetworkId", "vName" , "iStatus" FROM "network" WHERE "iStatus" = 1 ORDER BY "vName" asc';
        $networkArr = $sqlObj->GetAll($sql);
        return $networkArr;
    }

    public function getCities(){
        global $sqlObj;        

        $sql = 'SELECT "iCityId", "vCity" FROM "city_mas"  ORDER BY "vCity" asc';
        $cities = $sqlObj->GetAll($sql);
        
        return $cities;
    }

    public function getZipCodes(){
        global $sqlObj;        

        $sql = 'SELECT "iZipcode", "vZipcode" FROM "zipcode_mas"  ORDER BY "vZipcode" asc';
        $zipcodes = $sqlObj->GetAll($sql);
        
        return $zipcodes;
    }

    public function getZonesData($zones){
        global $sqlObj;
        $geoArr = array();
        $zoneSql = 'SELECT st_astext("PShape") as geotxt, "iZoneId" FROM zone WHERE "iZoneId" IN('.$zones.')'; 
        $data['zones'] = $sqlObj->GetAll($zoneSql);
        if(isset($data['zones']) && $data['zones'] != ''){
            foreach($data['zones'] as $key => $zone){
                $polygon = str_replace("POLYGON((", '', $zone['geotxt']);
                $polygon = str_replace("))", '', $polygon);
                //print_r($polygon);
                $polyArr = explode(",", $polygon);
                //print_r($polyArr);
                foreach($polyArr as $latlng){
                    $latLngArr = explode(" ", $latlng);
                    //print_r($latLngArr);
                    $geoArr[$zone['iZoneId']][] = array(
                        'lat' => (float) $latLngArr[1],
                        'lng' => (float) $latLngArr[0]
                    );
                    $i++;
                }
            }
            //print_r($geoArr);
        }

        return $geoArr;
    }

    public function getData($param, $site_url = ''){
        global $sqlObj;
        $data = array();
        
        $sql_attr = 'SELECT sa."iPremiseId", sa."iSAttributeId" FROM "site_attribute" sa INNER JOIN premise_mas s ON sa."iPremiseId" = s."iPremiseId"  ORDER BY sa."iPremiseId"';
        $rs_sql_attr = $sqlObj->GetAll($sql_attr);
        $ai = count($rs_sql_attr);
        $attr_arr = [];
        for($a=0; $a<$ai; $a++) {
            $attr_arr[$rs_sql_attr[$a]['iPremiseId']][] = $rs_sql_attr[$a]['iSAttributeId'];
        }
        //echo "<pre>";print_r($attr_arr);exit;

        $filterSql = 'SELECT s."iPremiseId" as premiseid, s."iSTypeId" as sTypeId, s."iSSTypeId" as sSTypeId, s."iCityId", s."iZoneId", z."iNetworkId", s."iZipcode", s."iStatus", st_astext(s."vPointLatLong") as point FROM premise_mas s LEFT JOIN zone z ON s."iZoneId" = z."iZoneId"  ORDER BY premiseid';
        $data['sites'] = $sqlObj->GetAll($filterSql);
        //echo "<pre>";print_r($data['sites']);exit;
        if(!empty($data['sites'])) {
            $ni = count($data['sites']);
            for($i=0; $i<$ni; $i++){
                $data['sites'][$i]['sattributeid'] = implode(",", $attr_arr[$data['sites'][$i]['premiseid']]) ;
                //echo "<pre>";print_r($data['sites']);exit;
            }
        }
        //echo "<pre>";print_r($data);exit;
        return $data;
    }

    public function getJson($param, $site_url){
        //echo "<pre>";print_r($param);exit();
        global $sqlObj, $field_map_json_url;

        $tmp_siteArr = array();
        $siteJsonUrl = $field_map_json_url."/premise-data.json";
        $site = array();
        $response = array();
        $siteData = json_decode(file_get_contents($siteJsonUrl), true);
        $siteArr = $siteData['sites'];
      
        if(isset($param['siteFilter']) && $param['siteFilter'] != ''){
            $siteFilterArr = explode(",", $param['siteFilter']);
            $siteFilter_data = $this->multi_array_search($siteArr,$siteFilterArr);
            if(!empty($response['sites'])){
                $response['sites'] = $response['sites'] + $siteFilter_data;
            }else{
                $response['sites'] = $siteFilter_data;
            }
        }

        if(isset($param['srFilter']) && $param['srFilter'] != ''){
            $srFilterArr = explode(",", $param['srFilter']);
            $srJsonUrl = $field_map_json_url."fiberInquiry-data.json";
            $srData = json_decode(file_get_contents($srJsonUrl), true);
            $srArr = $srData['sites'];
            $srFilter_data = $this->multi_array_search($srArr,$srFilterArr);
            if(!empty($response['sites'])){
                $response['sites'] = $response['sites'] + $srFilter_data;
            }else{
                $response['sites'] = $srFilter_data;
            }
        }

        if(isset($param['network']) && $param['network'] != ''){
            $networks = $this->getNetworkKMLData($param['network']);
            $response['networkFilter'] = $networks;
			$selectedNetwork = explode(",", $param['network']);
			foreach($siteArr as $_key => $site){
                if(!in_array($site['networkid'], $selectedNetwork) ){
                    unset($siteArr[$_key]);
                }
            }
        }
		//echo "<pre>";print_r($siteArr);exit;
        if(isset($param['zone']) && $param['zone'] != ''){
            $zones = $this->getZonesData($param['zone']);
            $response['polyZone'] = $zones;
			$selectedZones = explode(",", $param['zone']);
			foreach($siteArr as $_key => $site){
                if(!in_array($site['zoneid'], $selectedZones) ){
                    unset($siteArr[$_key]);
                }
            }
        }

        if(isset($param['city']) && $param['city'] != ''){
            $selectedCity = explode(",", $param['city']);
            
            foreach($siteArr as $_key => $site){
                if(!in_array($site['cityid'], $selectedCity) ){
                    unset($siteArr[$_key]);
                }
            }
           
            $response['sites'] = $siteArr;
        }

        if(isset($param['zipcode']) && $param['zipcode'] != ''){
            $selectedZipcode = explode(",", $param['zipcode']);
			
            foreach($siteArr as $_key => $site){
                if(!in_array($site['zipcode'], $selectedZipcode) ){
                    unset($siteArr[$_key]);
                }
            }
            $response['sites'] = $siteArr;
        }
	
        if(isset($param['zoneLayer']) && $param['zoneLayer'] != ''){
            $zolyrArr = explode(",", $param['zoneLayer']);
            $zoneLayerJsonUrl = $field_map_json_url."zoneLayer.json";
            $layerData = json_decode(file_get_contents($zoneLayerJsonUrl), true);
            $zlayerArr = $layerData['polyZone'];
			//echo "<pre>";print_r($zlayerArr);exit;
            $zlayerFilter_data = $this->multi_array_search($zlayerArr,$zolyrArr);
			//echo "<pre>";print_r($zlayerFilter_data);exit;
            $response['zoneLayer'] = $zlayerFilter_data;
        }

        if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
            $networkLayerArr = explode(",", $param['networkLayer']);
            $networkLayerJsonUrl = $field_map_json_url."networkLayer.json";
            $ntworklayerData = json_decode(file_get_contents($networkLayerJsonUrl), true);
            $nlayerArr = $ntworklayerData['networklayer'];
            $ntworklayerFilter_data = $this->multi_array_search($nlayerArr,$networkLayerArr);
            $response['networkLayer'] = $ntworklayerFilter_data;
        }

        if(isset($param['custlayer']) && $param['custlayer'] != ''){
            $culyrArr = explode(",", $param['custlayer']);
            $custlayerJsonUrl = $field_map_json_url."/customlayer.json";
            $layerData = json_decode(file_get_contents($custlayerJsonUrl), true);
            $layerArr = $layerData['customlayer'];
            $clayerFilter_data = $this->multi_array_search($layerArr,$culyrArr);
            $response['customlayer'] = $clayerFilter_data;
        }

        if(isset($param['fiberInquiryLayer']) && $param['fiberInquiryLayer'] != ''){
            $fiberInquiryJsonUrl = $field_map_json_url."fiberInquiry-data.json";
            $fiberInquiryData = json_decode(file_get_contents($fiberInquiryJsonUrl), true);
            $fiberInquiryArr = $fiberInquiryData['sites'];

            if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
                $selectedNetworkLayer = explode(",", $param['networkLayer']);
                foreach($fiberInquiryArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetworkLayer) ){
                        unset($fiberInquiryArr[$_key]);
                    }
                }
            }

            if(isset($param['network']) && $param['network'] != ''){
                $selectedNetwork = explode(",", $param['network']);
                foreach($fiberInquiryArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetwork) ){
                        unset($fiberInquiryArr[$_key]);
                    }
                }
            } 

            if(isset($param['zone']) && $param['zone'] != ''){
                $selectedZones = explode(",", $param['zone']);
                foreach($fiberInquiryArr as $_key => $sites){
                    if(!in_array($sites['zoneid'], $selectedZones) ){
                        unset($fiberInquiryArr[$_key]);
                    }
                }
            }

            if(isset($param['city']) && $param['city'] != ''){
                $selectedCities = explode(",", $param['city']);
                foreach($fiberInquiryArr as $_key => $sites){
                    if(!in_array($sites['cityid'], $selectedCities) ){
                        unset($fiberInquiryArr[$_key]);
                    }
                }
            } 

            if(isset($param['zipcode']) && $param['zipcode'] != ''){
                $selectedzipcodes = explode(",", $param['zipcode']);
                foreach($fiberInquiryArr as $_key => $sites){
                    if(!in_array($sites['zipcode'], $selectedzipcodes) ){
                        unset($fiberInquiryArr[$_key]);
                    }
                }
            } 
            $response['fiberInquiry'] = $fiberInquiryArr;
        }
        
        if(isset($param['serviceOrderLayer']) && $param['serviceOrderLayer'] != ''){
            $serviceOrderJsonUrl = $field_map_json_url."serviceorder-data.json";
            $serviceOrderData = json_decode(file_get_contents($serviceOrderJsonUrl), true);
            $serviceOrderArr = $serviceOrderData['sites'];

            if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
                $selectedNetworkLayer = explode(",", $param['networkLayer']);
                foreach($serviceOrderArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetworkLayer) ){
                        unset($serviceOrderArr[$_key]);
                    }
                }
            }

            if(isset($param['network']) && $param['network'] != ''){
                $selectedNetwork = explode(",", $param['network']);
                foreach($serviceOrderArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetwork) ){
                        unset($serviceOrderArr[$_key]);
                    }
                }
            } 

            if(isset($param['zone']) && $param['zone'] != ''){
                $selectedZones = explode(",", $param['zone']);
                foreach($serviceOrderArr as $_key => $sites){
                    if(!in_array($sites['zoneid'], $selectedZones) ){
                        unset($serviceOrderArr[$_key]);
                    }
                }
            }

            if(isset($param['city']) && $param['city'] != ''){
                $selectedCities = explode(",", $param['city']);
                foreach($serviceOrderArr as $_key => $sites){
                    if(!in_array($sites['cityid'], $selectedCities) ){
                        unset($serviceOrderArr[$_key]);
                    }
                }
            } 

            if(isset($param['zipcode']) && $param['zipcode'] != ''){
                $selectedzipcodes = explode(",", $param['zipcode']);
                foreach($serviceOrderArr as $_key => $sites){
                    if(!in_array($sites['zipcode'], $selectedzipcodes) ){
                        unset($serviceOrderArr[$_key]);
                    }
                }
            } 
            $response['serviceOrder'] = $serviceOrderArr;
        }

        if(isset($param['workOrderLayer']) && $param['workOrderLayer'] != ''){
            $workOrderJsonUrl = $field_map_json_url."workorder-data.json";
            $workOrderData = json_decode(file_get_contents($workOrderJsonUrl), true);
            $workOrderArr = $workOrderData['sites'];

            if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
                $selectedNetworkLayer = explode(",", $param['networkLayer']);
                foreach($workOrderArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetworkLayer) ){
                        unset($workOrderArr[$_key]);
                    }
                }
            }

            if(isset($param['network']) && $param['network'] != ''){
                $selectedNetwork = explode(",", $param['network']);
                foreach($workOrderArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetwork) ){
                        unset($workOrderArr[$_key]);
                    }
                }
            } 

            if(isset($param['zone']) && $param['zone'] != ''){
                $selectedZones = explode(",", $param['zone']);
                foreach($workOrderArr as $_key => $sites){
                    if(!in_array($sites['zoneid'], $selectedZones) ){
                        unset($workOrderArr[$_key]);
                    }
                }
            }

            if(isset($param['city']) && $param['city'] != ''){
                $selectedCities = explode(",", $param['city']);
                foreach($workOrderArr as $_key => $sites){
                    if(!in_array($sites['cityid'], $selectedCities) ){
                        unset($workOrderArr[$_key]);
                    }
                }
            } 

            if(isset($param['zipcode']) && $param['zipcode'] != ''){
                $selectedzipcodes = explode(",", $param['zipcode']);
                foreach($workOrderArr as $_key => $sites){
                    if(!in_array($sites['zipcode'], $selectedzipcodes) ){
                        unset($workOrderArr[$_key]);
                    }
                }
            } 
            $response['workOrder'] = $workOrderArr;
        }

        $pCircuitStatusArr = [];
        if(isset($param['pCircuitStatusLayer']) && $param['pCircuitStatusLayer'] != ''){
            $pclyrArr = explode(",", $param['pCircuitStatusLayer']);
            $pCircuitStatusJsonUrl = $field_map_json_url."premiseCircuit-data.json";
            $layerData = json_decode(file_get_contents($pCircuitStatusJsonUrl), true);
            $pCircuitStatusArr = $layerData['sites'];
            //echo "<pre>";print_r($pclayerArr);exit;
            if(!empty($pCircuitStatusArr)){
                foreach ($pCircuitStatusArr as $key => $lblVal) {
                    if(!in_array($lblVal['iStatus'], $pclyrArr) ){
                        unset($pCircuitStatusArr[$key]);
                    }
                }
            }

            if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
                $selectedNetworkLayer = explode(",", $param['networkLayer']);
                foreach($pCircuitStatusArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetworkLayer) ){
                        unset($pCircuitStatusArr[$_key]);
                    }
                }
            }

            if(isset($param['network']) && $param['network'] != ''){
                $selectedNetwork = explode(",", $param['network']);
                foreach($pCircuitStatusArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetwork) ){
                        unset($pCircuitStatusArr[$_key]);
                    }
                }
            } 

            if(isset($param['zone']) && $param['zone'] != ''){
                $selectedZones = explode(",", $param['zone']);
                foreach($pCircuitStatusArr as $_key => $sites){
                    if(!in_array($sites['zoneid'], $selectedZones) ){
                        unset($pCircuitStatusArr[$_key]);
                    }
                }
            }

            if(isset($param['city']) && $param['city'] != ''){
                $selectedCities = explode(",", $param['city']);
                foreach($pCircuitStatusArr as $_key => $sites){
                    if(!in_array($sites['cityid'], $selectedCities) ){
                        unset($pCircuitStatusArr[$_key]);
                    }
                }
            } 

            if(isset($param['zipcode']) && $param['zipcode'] != ''){
                $selectedzipcodes = explode(",", $param['zipcode']);
                foreach($pCircuitStatusArr as $_key => $sites){
                    if(!in_array($sites['zipcode'], $selectedzipcodes) ){
                        unset($pCircuitStatusArr[$_key]);
                    }
                }
            } 
        }

        $pCircuitcTypeArr = [];
        if(isset($param['pCircuitcTypeLayer']) && $param['pCircuitcTypeLayer'] != ''){
            $pclyrArr = explode(",", $param['pCircuitcTypeLayer']);
            $pCircuitcTypeJsonUrl = $field_map_json_url."premiseCircuit-data.json";
            $layerData = json_decode(file_get_contents($pCircuitcTypeJsonUrl), true);
            $pCircuitcTypeArr = $layerData['sites'];
            //echo "<pre>";print_r($pCircuitcTypeArr);exit;
            if(!empty($pCircuitcTypeArr)){
                foreach ($pCircuitcTypeArr as $key => $lblVal) {
                    if(!in_array($lblVal['connectiontypeid'], $pclyrArr) ){
                        unset($pCircuitcTypeArr[$key]);
                    }
                }
            }

            if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
                $selectedNetworkLayer = explode(",", $param['networkLayer']);
                foreach($pCircuitcTypeArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetworkLayer) ){
                        unset($pCircuitcTypeArr[$_key]);
                    }
                }
            }

            if(isset($param['network']) && $param['network'] != ''){
                $selectedNetwork = explode(",", $param['network']);
                foreach($pCircuitcTypeArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetwork) ){
                        unset($pCircuitcTypeArr[$_key]);
                    }
                }
            } 

            if(isset($param['zone']) && $param['zone'] != ''){
                $selectedZones = explode(",", $param['zone']);
                foreach($pCircuitcTypeArr as $_key => $sites){
                    if(!in_array($sites['zoneid'], $selectedZones) ){
                        unset($pCircuitcTypeArr[$_key]);
                    }
                }
            }

            if(isset($param['city']) && $param['city'] != ''){
                $selectedCities = explode(",", $param['city']);
                foreach($pCircuitcTypeArr as $_key => $sites){
                    if(!in_array($sites['cityid'], $selectedCities) ){
                        unset($pCircuitcTypeArr[$_key]);
                    }
                }
            } 

            if(isset($param['zipcode']) && $param['zipcode'] != ''){
                $selectedzipcodes = explode(",", $param['zipcode']);
                foreach($pCircuitcTypeArr as $_key => $sites){
                    if(!in_array($sites['zipcode'], $selectedzipcodes) ){
                        unset($pCircuitcTypeArr[$_key]);
                    }
                }
            } 
        }
        //echo "<pre>";print_r($pCircuitcTypeArr);exit;
        if(!empty($pCircuitStatusArr) && empty($pCircuitcTypeArr)){
            $response['premiseCircuit'] = $pCircuitStatusArr;
        }else if(empty($pCircuitStatusArr) && !empty($pCircuitcTypeArr)){
            $response['premiseCircuit'] = $pCircuitcTypeArr;
        }else if(!empty($pCircuitStatusArr) && !empty($pCircuitcTypeArr)){
            $newPremiseCircuitArr = array_merge($pCircuitStatusArr, $pCircuitcTypeArr);
            $premiseCircuitArr = array_map("unserialize", array_unique(array_map("serialize", $newPremiseCircuitArr)));
            $response['premiseCircuit'] = $premiseCircuitArr;
        }else {
            $response['premiseCircuit'] = [];
        }

        $premiseStatusLayer = [];
        if(isset($param['premiseStatusLayer']) && $param['premiseStatusLayer'] != ''){
            $pslyrArr = explode(",", $param['premiseStatusLayer']);
            $premiseStatusJsonUrl = $field_map_json_url."premise-data.json";
            $layerData = json_decode(file_get_contents($premiseStatusJsonUrl), true);
            $premiseStatusArr = $layerData['sites'];
            //echo "<pre>";print_r($premiseStatusArr);exit;
            if(!empty($premiseStatusArr)){
                foreach ($premiseStatusArr as $key => $lblVal) {
                    if(!in_array($lblVal['iStatus'], $pslyrArr) ){
                        unset($premiseStatusArr[$key]);
                    }
                }
            }

            if(isset($param['premiseAttribute']) && $param['premiseAttribute'] != ''){
                $palyrArr = explode(",", $param['premiseAttribute']);
                foreach($palyrArr as $attr){
                    $site = $this->searchFor($attr, $premiseStatusArr, 'sattributeid');
                    $premiseStatusArr = $premiseStatusArr + $site;
                }
            }

            if(isset($param['premiseTypeLayer']) && $param['premiseTypeLayer'] != ''){
                $ptlyrArr = explode(",", $param['premiseTypeLayer']);
                foreach ($premiseStatusArr as $key => $lblVal) {
                    if(!in_array($lblVal['stypeid'], $ptlyrArr) ){
                        unset($premiseStatusArr[$key]);
                    }
                }
            }

            if(isset($param['premisesubTypeLayer']) && $param['premisesubTypeLayer'] != ''){
                $pstlyrArr = explode(",", $param['premisesubTypeLayer']);
                foreach($pstlyrArr as $sTArr){
                    $sTArr1 = explode("|||", $sTArr);
                    $sT = $sTArr1[0];
                    $sT1 = $sTArr1[1];
                    $site = $this->searchFor($sT1, $premiseStatusArr, 'sstypeid');
                    $premiseStatusArr = $premiseStatusArr + $site;
                }
            }

            if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
                $selectedNetworkLayer = explode(",", $param['networkLayer']);
                foreach($premiseStatusArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetworkLayer) ){
                        unset($premiseStatusArr[$_key]);
                    }
                }
            }

            if(isset($param['network']) && $param['network'] != ''){
                $selectedNetwork = explode(",", $param['network']);
                foreach($premiseStatusArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetwork) ){
                        unset($premiseStatusArr[$_key]);
                    }
                }
            } 

            if(isset($param['zone']) && $param['zone'] != ''){
                $selectedZones = explode(",", $param['zone']);
                foreach($premiseStatusArr as $_key => $sites){
                    if(!in_array($sites['zoneid'], $selectedZones) ){
                        unset($premiseStatusArr[$_key]);
                    }
                }
            }

            if(isset($param['city']) && $param['city'] != ''){
                $selectedCities = explode(",", $param['city']);
                foreach($premiseStatusArr as $_key => $sites){
                    if(!in_array($sites['cityid'], $selectedCities) ){
                        unset($premiseStatusArr[$_key]);
                    }
                }
            } 

            if(isset($param['zipcode']) && $param['zipcode'] != ''){
                $selectedzipcodes = explode(",", $param['zipcode']);
                foreach($premiseStatusArr as $_key => $sites){
                    if(!in_array($sites['zipcode'], $selectedzipcodes) ){
                        unset($premiseStatusArr[$_key]);
                    }
                }
            } 
        }

        $premiseAttributeArr = [];
        if(isset($param['premiseAttribute']) && $param['premiseAttribute'] != ''){
            $palyrArr = explode(",", $param['premiseAttribute']);
            $premiseAttrJsonUrl = $field_map_json_url."premise-data.json";
            $layerData = json_decode(file_get_contents($premiseAttrJsonUrl), true);
            $AttributeArr = $layerData['sites'];
            //echo "<pre>";print_r($palyrArr);exit;
            foreach($palyrArr as $attr){
                $site = $this->searchFor($attr, $AttributeArr, 'sattributeid');
                if(empty($premiseAttributeArr)){
                    //echo $sT;
                    $premiseAttributeArr = $site;
                } else {
                    //print_r($site); die;
                    $premiseAttributeArr = $premiseAttributeArr + $site;
                }
				$premiseAttributeArr = [];
            }

            if(isset($param['premiseStatusLayer']) && $param['premiseStatusLayer'] != ''){
                $pslyrArr = explode(",", $param['premiseStatusLayer']);
                foreach ($premiseAttributeArr as $key => $lblVal) {
                    if(!in_array($lblVal['iStatus'], $pslyrArr) ){
                        unset($premiseAttributeArr[$key]);
                    }
                }
				$premiseAttributeArr = [];
            }

            if(isset($param['premiseTypeLayer']) && $param['premiseTypeLayer'] != ''){
                $ptlyrArr = explode(",", $param['premiseTypeLayer']);
                foreach ($premiseAttributeArr as $key => $lblVal) {
                    if(!in_array($lblVal['stypeid'], $ptlyrArr) ){
                        unset($premiseAttributeArr[$key]);
                    }
                }
				$premiseTypeLayerArr = [];
            }

            if(isset($param['premisesubTypeLayer']) && $param['premisesubTypeLayer'] != ''){
                $pstlyrArr = explode(",", $param['premisesubTypeLayer']);
                foreach($pstlyrArr as $sTArr){
                    $sTArr1 = explode("|||", $sTArr);
                    $sT = $sTArr1[0];
                    $sT1 = $sTArr1[1];
                    $site = $this->searchFor($sT1, $premiseAttributeArr, 'sstypeid');
                    $premiseAttributeArr = $premiseAttributeArr + $site;
                }
				$premisesubTypeLayerArr = [];
            }

            if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
                $selectedNetworkLayer = explode(",", $param['networkLayer']);
                foreach($premiseAttributeArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetworkLayer) ){
                        unset($premiseAttributeArr[$_key]);
                    }
                }
            }

            if(isset($param['network']) && $param['network'] != ''){
                $selectedNetwork = explode(",", $param['network']);
                foreach($premiseAttributeArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetwork) ){
                        unset($premiseAttributeArr[$_key]);
                    }
                }
            } 

            if(isset($param['zone']) && $param['zone'] != ''){
                $selectedZones = explode(",", $param['zone']);
                foreach($premiseAttributeArr as $_key => $sites){
                    if(!in_array($sites['zoneid'], $selectedZones) ){
                        unset($premiseAttributeArr[$_key]);
                    }
                }
            }

            if(isset($param['city']) && $param['city'] != ''){
                $selectedCities = explode(",", $param['city']);
                foreach($premiseAttributeArr as $_key => $sites){
                    if(!in_array($sites['cityid'], $selectedCities) ){
                        unset($premiseAttributeArr[$_key]);
                    }
                }
            } 

            if(isset($param['zipcode']) && $param['zipcode'] != ''){
                $selectedzipcodes = explode(",", $param['zipcode']);
                foreach($premiseAttributeArr as $_key => $sites){
                    if(!in_array($sites['zipcode'], $selectedzipcodes) ){
                        unset($premiseAttributeArr[$_key]);
                    }
                }
            } 
        }

        $premiseTypeLayerArr = [];
        if(isset($param['premiseTypeLayer']) && $param['premiseTypeLayer'] != ''){
            $ptlyrArr = explode(",", $param['premiseTypeLayer']);
            $premiseTypeJsonUrl = $field_map_json_url."premise-data.json";
            $layerData = json_decode(file_get_contents($premiseTypeJsonUrl), true);
            $premiseTypeLayerArr = $layerData['sites'];
            //echo "<pre>";print_r($layerData);exit;
            if(!empty($premiseTypeLayerArr)){
                foreach ($premiseTypeLayerArr as $key => $lblVal) {
                    if(!in_array($lblVal['stypeid'], $ptlyrArr) ){
                        unset($premiseTypeLayerArr[$key]);
                    }
                }
            }

            if(isset($param['premiseStatusLayer']) && $param['premiseStatusLayer'] != ''){
                $pslyrArr = explode(",", $param['premiseStatusLayer']);
                foreach ($premiseTypeLayerArr as $key => $lblVal) {
                    if(!in_array($lblVal['iStatus'], $pslyrArr) ){
                        unset($premiseTypeLayerArr[$key]);
                    }
                }
				$premiseStatusArr = [];
            }

            if(isset($param['premiseAttribute']) && $param['premiseAttribute'] != ''){
                $palyrArr = explode(",", $param['premiseAttribute']);
                foreach($palyrArr as $attr){
                    $site = $this->searchFor($attr, $premiseTypeLayerArr, 'sattributeid');
                    $premiseTypeLayerArr = $premiseTypeLayerArr + $site;
                }
				$premiseAttributeArr = [];
            }

            if(isset($param['premisesubTypeLayer']) && $param['premisesubTypeLayer'] != ''){
                $pstlyrArr = explode(",", $param['premisesubTypeLayer']);
                foreach($pstlyrArr as $sTArr){
                    $sTArr1 = explode("|||", $sTArr);
                    $sT = $sTArr1[0];
                    $sT1 = $sTArr1[1];
                    $site = $this->searchFor($sT1, $premiseTypeLayerArr, 'sstypeid');
                    $premiseTypeLayerArr = $premiseTypeLayerArr + $site;
                }
				$premisesubTypeLayerArr = [];
            }

            if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
                $selectedNetworkLayer = explode(",", $param['networkLayer']);
                foreach($premiseTypeLayerArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetworkLayer) ){
                        unset($premiseTypeLayerArr[$_key]);
                    }
                }
            }

            if(isset($param['network']) && $param['network'] != ''){
                $selectedNetwork = explode(",", $param['network']);
                foreach($premiseTypeLayerArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetwork) ){
                        unset($premiseTypeLayerArr[$_key]);
                    }
                }
            } 

            if(isset($param['zone']) && $param['zone'] != ''){
                $selectedZones = explode(",", $param['zone']);
                foreach($premiseTypeLayerArr as $_key => $sites){
                    if(!in_array($sites['zoneid'], $selectedZones) ){
                        unset($premiseTypeLayerArr[$_key]);
                    }
                }
            }

            if(isset($param['city']) && $param['city'] != ''){
                $selectedCities = explode(",", $param['city']);
                foreach($premiseTypeLayerArr as $_key => $sites){
                    if(!in_array($sites['cityid'], $selectedCities) ){
                        unset($premiseTypeLayerArr[$_key]);
                    }
                }
            } 

            if(isset($param['zipcode']) && $param['zipcode'] != ''){
                $selectedzipcodes = explode(",", $param['zipcode']);
                foreach($premiseTypeLayerArr as $_key => $sites){
                    if(!in_array($sites['zipcode'], $selectedzipcodes) ){
                        unset($premiseTypeLayerArr[$_key]);
                    }
                }
            } 
        }

        $premisesubTypeLayerArr = [];
        if(isset($param['premisesubTypeLayer']) && $param['premisesubTypeLayer'] != ''){
            $pstlyrArr = explode(",", $param['premisesubTypeLayer']);
            $premisesubTypeJsonUrl = $field_map_json_url."premise-data.json";
            $layerData = json_decode(file_get_contents($premisesubTypeJsonUrl), true);
            $premisesTypeLayerArr = $layerData['sites'];

            foreach($pstlyrArr as $sTArr){
                $sTArr1 = explode("|||", $sTArr);
                $sT = $sTArr1[0];
                $sT1 = $sTArr1[1];
                $site = $this->searchFor($sT1, $premisesTypeLayerArr, 'sstypeid');

                if(empty($premisesubTypeLayerArr)){
                    $premisesubTypeLayerArr = $site;
                } else {
                    $premisesubTypeLayerArr = $premisesubTypeLayerArr + $site;
                }
            }

            if(isset($param['premiseStatusLayer']) && $param['premiseStatusLayer'] != ''){
                $pslyrArr = explode(",", $param['premiseStatusLayer']);
                foreach ($premisesTypeLayerArr as $key => $lblVal) {
                    if(!in_array($lblVal['iStatus'], $pslyrArr) ){
                        unset($premisesTypeLayerArr[$key]);
                    }
                }
				$premiseStatusArr = [];
            }

            if(isset($param['premiseAttribute']) && $param['premiseAttribute'] != ''){
                $palyrArr = explode(",", $param['premiseAttribute']);
                foreach($palyrArr as $attr){
                    $site = $this->searchFor($attr, $premisesTypeLayerArr, 'sattributeid');
                    $premisesTypeLayerArr = $premisesTypeLayerArr + $site;
                }
				$premiseAttributeArr = [];
            }

            if(isset($param['premiseTypeLayer']) && $param['premiseTypeLayer'] != ''){
                $ptlyrArr = explode(",", $param['premiseTypeLayer']);
                foreach ($premisesTypeLayerArr as $key => $lblVal) {
                    if(!in_array($lblVal['stypeid'], $ptlyrArr) ){
                        unset($premisesTypeLayerArr[$key]);
                    }
                }

				$premiseTypeLayerArr = [];
            }

            if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
                $selectedNetworkLayer = explode(",", $param['networkLayer']);
                foreach($premisesTypeLayerArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetworkLayer) ){
                        unset($premisesTypeLayerArr[$_key]);
                    }
                }
            }

            if(isset($param['network']) && $param['network'] != ''){
                $selectedNetwork = explode(",", $param['network']);
                foreach($premisesubTypeLayerArr as $_key => $sites){
                    if(!in_array($sites['networkid'], $selectedNetwork) ){
                        unset($premisesubTypeLayerArr[$_key]);
                    }
                }
            } 

            if(isset($param['zone']) && $param['zone'] != ''){
                $selectedZones = explode(",", $param['zone']);
                foreach($premisesubTypeLayerArr as $_key => $sites){
                    if(!in_array($sites['zoneid'], $selectedZones) ){
                        unset($premisesubTypeLayerArr[$_key]);
                    }
                }
            }

            if(isset($param['city']) && $param['city'] != ''){
                $selectedCities = explode(",", $param['city']);
                foreach($premisesubTypeLayerArr as $_key => $sites){
                    if(!in_array($sites['cityid'], $selectedCities) ){
                        unset($premisesubTypeLayerArr[$_key]);
                    }
                }
            } 

            if(isset($param['zipcode']) && $param['zipcode'] != ''){
                $selectedzipcodes = explode(",", $param['zipcode']);
                foreach($premisesubTypeLayerArr as $_key => $sites){
                    if(!in_array($sites['zipcode'], $selectedzipcodes) ){
                        unset($premisesubTypeLayerArr[$_key]);
                    }
                }
            } 
        }

		/*echo " premiseAttributeArr count " . count($premiseAttributeArr)."<hr/>";
		echo " premisesubTypeLayerArr count " . count($premisesubTypeLayerArr)."<hr/>";
		echo " premiseStatusArr count " . count($premiseStatusArr)."<hr/>";
		echo " premiseTypeLayerArr count " . count($premiseTypeLayerArr)."<hr/>";exit;*/
        //echo "<pre>";print_r($premiseAttributeArr);exit();
        //echo "<pre>";print_r($premisesubTypeLayerArr);exit;
        //echo "<pre>";print_r($premiseStatusArr);
        //echo "<pre>";print_r($premiseTypeLayerArr);
        if(!empty($premiseStatusArr) && empty($premiseAttributeArr) && empty($premiseTypeLayerArr) && empty($premisesubTypeLayerArr)){
			//echo "1111";
            $response['sites'] = $premiseStatusArr;
        }else if(empty($premiseStatusArr) && !empty($premiseAttributeArr) && empty($premiseTypeLayerArr) && empty($premisesubTypeLayerArr)){
			//echo "2222";
            $response['sites'] = $premiseAttributeArr;
        }else if(empty($premiseStatusArr) && empty($premiseAttributeArr) && !empty($premiseTypeLayerArr) && empty($premisesubTypeLayerArr)){
			//echo "3333";
            $response['sites'] = $premiseTypeLayerArr;
        }else if(empty($premiseStatusArr) && empty($premiseAttributeArr) && empty($premiseTypeLayerArr) && !empty($premisesubTypeLayerArr)){
			//echo "4444";
            $response['sites'] = $premisesubTypeLayerArr;
        }else if(!empty($premiseStatusArr) || !empty($premiseAttributeArr) || !empty($premiseTypeLayerArr) || !empty($premisesubTypeLayerArr)){
			//echo "5555";
            $newPremiseArr = array_merge($premiseStatusArr, $premiseAttributeArr, $premiseTypeLayerArr, $premisesubTypeLayerArr);
            $premiseArr = array_map("unserialize", array_unique(array_map("serialize", $newPremiseArr)));
            $response['sites'] = $premiseArr;
        }
        //echo "<pre>";print_r($response['sites']);exit();
		//$response['sites_cnt'] = count($response['sites']);
        //echo "<pre>";print_r($response);exit();
        return $response;
    }

    // Function to iteratively search for a given value 
    public function searchFor($search_value, $array, $withKey) { 
        //print_r($search_value); die("inf");
        $foundKey = array();
        // Iterating over main array 
        //echo "<pre>";print_r($array); die('found');    
        foreach ($array as $key1 => $val1) {
            //echo "<pre>";print_r($withKey);exit;   
            if(isset($val1[$withKey]) && $val1[$withKey] != ''){
                //print_r($withKey);exit;
                if($withKey == 'sattributeid') {
                    if(@in_array($search_value, $val1['sattributeid'])){
                        $foundKey[$key1] = $val1; 
                        //echo "<pre>11";print_r($foundKey[$key1]);exit;   
                    }
                }else {
                    if($val1[$withKey] == $search_value) $foundKey[$key1] = $val1; 
                }
            }
        } 
        //echo "here";print_r($foundKey); die('found');
        return $foundKey; 
    }

    public function multi_array_search($array, $search){
        $result = array();
        // Iterate over each array element
        foreach ($array as $key => $value){
        // Iterate over each search condition
            foreach ($search as $k => $v)
            {
            // If the array element does not meet the search condition then continue to the next element
                if (isset($key) && $key == $v){
                    $result[$key] = $array[$key];
                }
            }
        }
        // Return the result array
        return $result;
    }

    public function getFiberInquiryData($param){
        global $sqlObj;
        $data = array();
        $fInquiryData = 'SELECT fiberinquiry_details.*,contact_mas."vFirstName", contact_mas."vLastName", zone."vZoneName", zone."iNetworkId", network."vName" as "vNetwork", engagement_mas."vEngagement", sst."vSubTypeName" as "vPremiseSubType", s."vName" as "vPremiseName", city_mas."vCity", state_mas."vState" FROM "public"."fiberinquiry_details" left join contact_mas on "contact_mas"."iCId" = "fiberinquiry_details"."iCId" LEFT JOIN state_mas  on "state_mas"."iStateId" = "fiberinquiry_details"."iStateId" LEFT JOIN "city_mas" on "city_mas"."iCityId" = "fiberinquiry_details"."iCityId" LEFT JOIN "zone" on "zone"."iZoneId" = "fiberinquiry_details"."iZoneId" LEFT JOIN "network" on "network"."iNetworkId" = "zone"."iNetworkId" LEFT JOIN "engagement_mas" on "engagement_mas"."iEngagementId" = "fiberinquiry_details"."iEngagementId" LEFT JOIN "site_sub_type_mas" sst on sst."iSSTypeId" = "fiberinquiry_details"."iPremiseSubTypeId" LEFT JOIN "premise_mas" s on s."iPremiseId" = "fiberinquiry_details"."iMatchingPremiseId" ORDER BY fiberinquiry_details."iFiberInquiryId"';
        $data['sites'] = $sqlObj->GetAll($fInquiryData);
        return $data;
    } 

    public function getServiceOrderData($param){
        global $sqlObj;
        $data = array();
        $sOrderData = 'SELECT service_order.* , s."vName" as "vPremiseName", s."vAddress1", s."vStreet", s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude", st."vTypeName" as "vPremiseType", cm."vCompanyName", z."vZoneName", z."iNetworkId", n."vName" as "vNetwork", ct."vConnectionTypeName", st1."vServiceType" as "vServiceType1", c."vCity", sm."vState",  concat(user_mas."vFirstName", \' \', user_mas."vLastName" ) as "vSalesRepName"
            FROM service_order 
            LEFT JOIN user_mas on service_order."iSalesRepId" = user_mas."iUserId" 
            LEFT JOIN premise_mas s on service_order."iPremiseId" = s."iPremiseId" 
            LEFT JOIN zone z on s."iZoneId" = z."iZoneId" 
            LEFT JOIN city_mas c on s."iCityId" = c."iCityId" 
            LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" 
            LEFT JOIN network n on z."iNetworkId" = n."iNetworkId" 
            LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId" 
            LEFT JOIN company_mas cm on service_order."iCarrierID" = cm."iCompanyId" 
            LEFT JOIN connection_type_mas ct on service_order."iConnectionTypeId" = ct."iConnectionTypeId" 
            LEFT JOIN service_type_mas st1 on service_order."iService1" = st1."iServiceTypeId" 
            ORDER BY service_order."iServiceOrderId" desc';
        $data['sites'] = $sqlObj->GetAll($sOrderData);
        return $data;
    } 

    public function getWorkOrderData($param){
        global $sqlObj;
        $data = array();
        $wOrderData = 'SELECT workorder.* , s."vName" as "vPremiseName", s."vAddress1", s."vStreet", s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude",  st."vTypeName" as "vPremiseType", z."vZoneName", z."iNetworkId", n."vName" as "vNetwork", so."vMasterMSA", so."vServiceOrder", ws."vStatus", wt."vType", concat(u."vFirstName",\' \', u."vLastName") as "vRequestor", concat(u1."vFirstName", \' \', u1."vLastName") as "vAssignedTo", c."vCity", sm."vState" 
            FROM workorder 
            LEFT JOIN premise_mas s on workorder."iPremiseId" = s."iPremiseId" 
            LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId" 
            LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode" 
            LEFT JOIN zone z on s."iZoneId" = z."iZoneId" 
            LEFT JOIN network n on z."iNetworkId" = n."iNetworkId" 
            LEFT JOIN city_mas c on s."iCityId" = c."iCityId" 
            LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" 
            LEFT JOIN service_order so on workorder."iServiceOrderId" = so."iServiceOrderId" 
            LEFT JOIN user_mas u on workorder."iRequestorId" = u."iUserId" 
            LEFT JOIN user_mas u1 on workorder."iAssignedToId" = u1."iUserId"
            LEFT JOIN workorder_status_mas ws on workorder."iWOSId" = ws."iWOSId" 
            LEFT JOIN workorder_type_mas wt on workorder."iWOTId" = wt."iWOTId" ORDER BY workorder."iWOId" desc ';
        $data['sites'] = $sqlObj->GetAll($wOrderData);
        return $data;
    }

    public function getPremiseCircuitData($param){
        global $sqlObj;
        $data = array();
        $pCircuitData = 'SELECT premise_circuit.* , wt."vType" as "vWorkOrderType", s."vName" as "vPremiseName", s."vAddress1", s."vStreet", s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude",  st."vTypeName" as "vPremiseType", z."vZoneName", z."iNetworkId", n."vName" as "vNetwork", circuit."vCircuitName", connection_type_mas."vConnectionTypeName", z."iNetworkId", c."vCity", sm."vState" 
            FROM premise_circuit 
            LEFT JOIN workorder w ON premise_circuit."iWOId" = w."iWOId" 
            LEFT JOIN workorder_type_mas wt ON w."iWOTId" = wt."iWOTId" 
            LEFT JOIN service_order so ON w."iServiceOrderId" = so."iServiceOrderId" 
            LEFT JOIN premise_mas s ON premise_circuit."iPremiseId" = s."iPremiseId" 
            LEFT JOIN zone z ON s."iZoneId" = z."iZoneId"
            LEFT JOIN network n on z."iNetworkId" = n."iNetworkId" 
            LEFT JOIN city_mas c on s."iCityId" = c."iCityId" 
            LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" 
            LEFT JOIN site_type_mas st ON s."iSTypeId" = st."iSTypeId" 
            LEFT JOIN circuit ON premise_circuit."iCircuitId" = circuit."iCircuitId"
            LEFT JOIN connection_type_mas ON premise_circuit."iConnectionTypeId" = connection_type_mas."iConnectionTypeId" 
            ORDER BY premise_circuit."iPremiseCircuitId" Desc ';
        $data['sites'] = $sqlObj->GetAll($pCircuitData);
        return $data;
    }
    
    public function getSerachSiteData($param){
        global $sqlObj;
        $data = array();
        $where = array();
        $premiseId= $param['premiseId'];
        
        if($premiseId != ""){
           $where[] = ' "iPremiseId" IN ('.$premiseId.')'; 
        }
        
        $whereQuery = implode(" AND ", $where);
        $siteSql = 'SELECT "iPremiseId" as premiseid, "iSTypeId" as sTypeId, "iSSTypeId" as sSTypeId, "iCityId", "iZoneId", st_astext("vPointLatLong") as point FROM premise_mas WHERE '.$whereQuery;
        //echo $siteSql;exit();
        $data['siteData'] = $sqlObj->GetAll($siteSql);
        // print_r($data);exit();
        return $data;
    }

    public function getSerachFiberInquiryData($param){
       global $sqlObj;
        $data = array();

        $where = array();
        $fiberInquiryId= $param['fiberInquiryId'];
        
        if($fiberInquiryId != ""){
           $where[] = ' fiberinquiry_details."iFiberInquiryId" IN ('.$fiberInquiryId.')'; 
        }
        $whereQuery = implode(" AND ", $where);
        
        $fInquiryData = 'SELECT fiberinquiry_details.*,contact_mas."vFirstName", contact_mas."vLastName", zone."vZoneName", zone."iNetworkId", network."vName" as "vNetwork", engagement_mas."vEngagement", sst."vSubTypeName" as "vPremiseSubType", s."vName" as "vPremiseName", city_mas."vCity", state_mas."vState" FROM "public"."fiberinquiry_details" left join contact_mas on "contact_mas"."iCId" = "fiberinquiry_details"."iCId" LEFT JOIN state_mas  on "state_mas"."iStateId" = "fiberinquiry_details"."iStateId" LEFT JOIN "city_mas" on "city_mas"."iCityId" = "fiberinquiry_details"."iCityId" LEFT JOIN "zone" on "zone"."iZoneId" = "fiberinquiry_details"."iZoneId" LEFT JOIN "network" on "network"."iNetworkId" = "zone"."iNetworkId" LEFT JOIN "engagement_mas" on "engagement_mas"."iEngagementId" = "fiberinquiry_details"."iEngagementId" LEFT JOIN "site_sub_type_mas" sst on sst."iSSTypeId" = "fiberinquiry_details"."iPremiseSubTypeId" LEFT JOIN "premise_mas" s on s."iPremiseId" = "fiberinquiry_details"."iMatchingPremiseId" WHERE  '.$whereQuery.' ORDER BY fiberinquiry_details."iFiberInquiryId"';
        $data['fInquiryData'] = $sqlObj->GetAll($fInquiryData);
        //print_r($data);exit;
        return $data;
  
    }

    public function getSerachServiceOrderData($param){
        global $sqlObj;
        //echo "<pre>";print_r($param);exit;
        $data = array();

        $where = array();
        $iServiceOrderId= $param['serviceOrderId'];
        
        if($iServiceOrderId != ""){
           $where[] = ' service_order."iServiceOrderId" IN ('.$iServiceOrderId.')'; 
        }
        $whereQuery = implode(" AND ", $where);
        
        $sOrderData = 'SELECT service_order.* , s."vName" as "vPremiseName", s."vAddress1", s."vStreet", s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude", st."vTypeName" as "vPremiseType", cm."vCompanyName", z."vZoneName", z."iNetworkId", n."vName" as "vNetwork", ct."vConnectionTypeName", st1."vServiceType" as "vServiceType1", c."vCity", sm."vState"  
            FROM service_order 
            LEFT JOIN premise_mas s on service_order."iPremiseId" = s."iPremiseId" 
            LEFT JOIN zone z on s."iZoneId" = z."iZoneId" 
            LEFT JOIN city_mas c on s."iCityId" = c."iCityId" 
            LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" 
            LEFT JOIN network n on z."iNetworkId" = n."iNetworkId" 
            LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId" 
            LEFT JOIN company_mas cm on service_order."iCarrierID" = cm."iCompanyId" 
            LEFT JOIN connection_type_mas ct on service_order."iConnectionTypeId" = ct."iConnectionTypeId" 
            LEFT JOIN service_type_mas st1 on service_order."iService1" = st1."iServiceTypeId" 
            WHERE  '.$whereQuery.' ORDER BY service_order."iServiceOrderId" desc';
        $data['serviceOrderData'] = $sqlObj->GetAll($sOrderData);
        //print_r($data);exit;
        return $data;
    }

    public function getSerachWorkOrderData($param){
        global $sqlObj;
        //echo "<pre>";print_r($param);exit;
        $data = array();

        $where = array();
        $iWorkOrderId= $param['workOrderId'];
        
        if($iWorkOrderId != ""){
           $where[] = ' workorder."iWOId" IN ('.$iWorkOrderId.')'; 
        }
        $whereQuery = implode(" AND ", $where);

        $wOrderData = 'SELECT workorder.* , s."vName" as "vPremiseName", s."vAddress1", s."vStreet", s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude",  st."vTypeName" as "vPremiseType", z."vZoneName", z."iNetworkId", n."vName" as "vNetwork", so."vMasterMSA", so."vServiceOrder", ws."vStatus", wt."vType", concat(u."vFirstName",\' \', u."vLastName") as "vRequestor", concat(u1."vFirstName", \' \', u1."vLastName") as "vAssignedTo", c."vCity", sm."vState" 
            FROM workorder 
            LEFT JOIN premise_mas s on workorder."iPremiseId" = s."iPremiseId" 
            LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId" 
            LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode" 
            LEFT JOIN zone z on s."iZoneId" = z."iZoneId" 
            LEFT JOIN network n on z."iNetworkId" = n."iNetworkId" 
            LEFT JOIN city_mas c on s."iCityId" = c."iCityId" 
            LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" 
            LEFT JOIN service_order so on workorder."iServiceOrderId" = so."iServiceOrderId" 
            LEFT JOIN user_mas u on workorder."iRequestorId" = u."iUserId" 
            LEFT JOIN user_mas u1 on workorder."iAssignedToId" = u1."iUserId"
            LEFT JOIN workorder_status_mas ws on workorder."iWOSId" = ws."iWOSId" 
            LEFT JOIN workorder_type_mas wt on workorder."iWOTId" = wt."iWOTId" 
            WHERE '.$whereQuery.' ORDER BY workorder."iWOId" desc ';
        $data['workOrderData'] = $sqlObj->GetAll($wOrderData);
        //print_r($data);exit;
        return $data;
    }

    public function getSerachTroubleTicketData($param){
        global $sqlObj;
        //echo "<pre>";print_r($param);exit;
        $trouble_ticket_arr = array();
        $data = array();

        $where = array();
        $iTroubleTicketId= $param['troubleTicketId'];
        
        if($iTroubleTicketId != ""){
           $where[] = ' trouble_ticket."iTroubleTicketId" IN ('.$iTroubleTicketId.')'; 
        }
        $whereQuery = implode(" AND ", $where);

        $tTicketSql = 'SELECT trouble_ticket.* , so."vMasterMSA", so."vServiceOrder" FROM trouble_ticket LEFT JOIN service_order so on so."iServiceOrderId" = trouble_ticket."iServiceOrderId" WHERE '.$whereQuery.' ORDER BY trouble_ticket."iTroubleTicketId" DESC ';
        $tTicketData = $sqlObj->GetAll($tTicketSql);
        //echo $tTicketSql;
        //echo "<pre>";print_r($tTicketData);exit;
        $ni = count($tTicketData);
        //print_r($data);exit;
        if($ni > 0){
            for($i=0; $i<$ni; $i++) {
                $iSeverity = '---';
                if($tTicketData[$i]['iSeverity'] == 1){
                   $iSeverity = "Low"; 
                }else if($tTicketData[$i]['iSeverity'] == 2){
                   $iSeverity = "Medium"; 
                }else if($tTicketData[$i]['iSeverity'] == 3){
                   $iSeverity = "High"; 
                }else if($tTicketData[$i]['iSeverity'] == 4){
                   $iSeverity = "Critical"; 
                }

                $iStatus = '---';
                if($tTicketData[$i]['iStatus'] == 1){
                   $iStatus = "Not Started"; 
                }else if($tTicketData[$i]['iStatus'] == 2){
                   $iStatus = "In Progress"; 
                }else if($tTicketData[$i]['iStatus'] == 3){
                   $iStatus = "Completed"; 
                }

                $vServiceDetails = '';
                if($tTicketData[$i]['iServiceOrderId'] != ""){
                    $vServiceDetails .= $tTicketData[$i]['vMasterMSA']." | ".$tTicketData[$i]['vServiceOrder'];
                }

                $sql = 'SELECT trouble_ticket_premise.* , s."vName" as "vPremiseName", s."vAddress1", s."vStreet", s."vLatitude", s."vLongitude", st."vTypeName" as "vPremiseType", z."vZoneName", n."vName" as "vNetwork", c."vCity", sm."vState" FROM trouble_ticket_premise LEFT JOIN premise_mas s on trouble_ticket_premise."iPremiseId" = s."iPremiseId" LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId" LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode" LEFT JOIN zone z on s."iZoneId" = z."iZoneId" LEFT JOIN network n on z."iNetworkId" = n."iNetworkId" LEFT JOIN city_mas c on s."iCityId" = c."iCityId" LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" WHERE trouble_ticket_premise."iTroubleTicketId" = '.$tTicketData[$i]['iTroubleTicketId'].' ORDER BY trouble_ticket_premise."iPremiseId" DESC';
                //echo $sql;exit;
                $rs_tt_premise = $sqlObj->GetAll($sql);
                //echo "<pre>";print_r($rs_tt_premise);exit;
                $tti = count($rs_tt_premise);
                if($tti > 0){            
                    for($t=0; $t<$tti; $t++){
                        $vIcon = $site_url."images/diamond_exclamation.png";
                        $trouble_ticket_arr[$t]['iTroubleTicketId'] = $rs_tt_premise[$t]['iTroubleTicketId'];
                        $trouble_ticket_arr[$t]['iSeverity'] = $iSeverity;
                        $trouble_ticket_arr[$t]['iStatus'] = $iStatus;
                        $trouble_ticket_arr[$t]['vServiceOrder'] = $vServiceDetails;
                        $trouble_ticket_arr[$t]['iPremiseId'] = $rs_tt_premise[$t]['iPremiseId'];
                        $trouble_ticket_arr[$t]['vPremiseName'] = $rs_tt_premise[$t]['vPremiseName'];
                        $trouble_ticket_arr[$t]['vPremiseType'] = $rs_tt_premise[$t]['vPremiseType'];
                        $trouble_ticket_arr[$t]['vLatitude'] = $rs_tt_premise[$t]['vLatitude'];
                        $trouble_ticket_arr[$t]['vLongitude'] = $rs_tt_premise[$t]['vLongitude'];
                        $trouble_ticket_arr[$t]['dTroubleStartDate'] = date_display_report_date($rs_tt_premise[$t]['dTroubleStartDate']);

                        $trouble_ticket_arr[$t]['vAddress'] = $rs_tt_premise[$t]['vAddress1'].' '.$rs_tt_premise[$t]['vStreet'].' '.$rs_tt_premise[$t]['vCity'].' '.$rs_tt_premise[$t]['vState'];
                        $trouble_ticket_arr[$t]['vIcon'] = $vIcon;
                    }
                }
            }
        }

        $data['troubleTicketData'] = $trouble_ticket_arr;
        return $data;
    }

    public function getSerachMaintenanceTicketData($param){
        global $sqlObj;
        //echo "<pre>";print_r($param);exit;
        $maintenance_ticket_arr = array();
        $data = array();

        $where = array();
        $iMaintenanceTicketId= $param['maintenanceTicketId'];
        
        if($iMaintenanceTicketId != ""){
           $where[] = ' maintenance_ticket."iMaintenanceTicketId" IN ('.$iMaintenanceTicketId.')'; 
        }
        $whereQuery = implode(" AND ", $where);

        $tTicketSql = 'SELECT maintenance_ticket.* , so."vMasterMSA", so."vServiceOrder" FROM maintenance_ticket LEFT JOIN service_order so on so."iServiceOrderId" = maintenance_ticket."iServiceOrderId" WHERE '.$whereQuery.' ORDER BY maintenance_ticket."iMaintenanceTicketId" DESC ';
        $tTicketData = $sqlObj->GetAll($tTicketSql);
        //echo $tTicketSql;
        //echo "<pre>";print_r($tTicketData);exit;
        $ni = count($tTicketData);
        //print_r($data);exit;
        if($ni > 0){
            for($i=0; $i<$ni; $i++) {
                $iSeverity = '---';
                if($tTicketData[$i]['iSeverity'] == 1){
                   $iSeverity = "Low"; 
                }else if($tTicketData[$i]['iSeverity'] == 2){
                   $iSeverity = "Medium"; 
                }else if($tTicketData[$i]['iSeverity'] == 3){
                   $iSeverity = "High"; 
                }else if($tTicketData[$i]['iSeverity'] == 4){
                   $iSeverity = "Critical"; 
                }

                $iStatus = '---';
                if($tTicketData[$i]['iStatus'] == 1){
                   $iStatus = "Not Started"; 
                }else if($tTicketData[$i]['iStatus'] == 2){
                   $iStatus = "In Progress"; 
                }else if($tTicketData[$i]['iStatus'] == 3){
                   $iStatus = "Completed"; 
                }

                $vServiceDetails = '';
                if($tTicketData[$i]['iServiceOrderId'] != ""){
                    $vServiceDetails .= $tTicketData[$i]['vMasterMSA']." | ".$tTicketData[$i]['vServiceOrder'];
                }

                $sql = 'SELECT maintenance_ticket_premise.* , s."vName" as "vPremiseName", s."vAddress1", s."vStreet", s."vLatitude", s."vLongitude", st."vTypeName" as "vPremiseType", z."vZoneName", n."vName" as "vNetwork", c."vCity", sm."vState" FROM maintenance_ticket_premise LEFT JOIN premise_mas s on maintenance_ticket_premise."iPremiseId" = s."iPremiseId" LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId" LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode" LEFT JOIN zone z on s."iZoneId" = z."iZoneId" LEFT JOIN network n on z."iNetworkId" = n."iNetworkId" LEFT JOIN city_mas c on s."iCityId" = c."iCityId" LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" WHERE maintenance_ticket_premise."iMaintenanceTicketId" = '.$tTicketData[$i]['iMaintenanceTicketId'].' ORDER BY maintenance_ticket_premise."iPremiseId" DESC';
                //echo $sql;exit;
                $rs_mt_premise = $sqlObj->GetAll($sql);
                //echo "<pre>";print_r($rs_mt_premise);exit;
                $tti = count($rs_mt_premise);
                if($tti > 0){            
                    for($t=0; $t<$tti; $t++){
                        $vIcon = $site_url."images/screwdriver_wrench.png";
                        $maintenance_ticket_arr[$t]['iMaintenanceTicketId'] = $rs_mt_premise[$t]['iMaintenanceTicketId'];
                        $maintenance_ticket_arr[$t]['iSeverity'] = $iSeverity;
                        $maintenance_ticket_arr[$t]['iStatus'] = $iStatus;
                        $maintenance_ticket_arr[$t]['vServiceOrder'] = $vServiceDetails;
                        $maintenance_ticket_arr[$t]['iPremiseId'] = $rs_mt_premise[$t]['iPremiseId'];
                        $maintenance_ticket_arr[$t]['vPremiseName'] = $rs_mt_premise[$t]['vPremiseName'];
                        $maintenance_ticket_arr[$t]['vPremiseType'] = $rs_mt_premise[$t]['vPremiseType'];
                        $maintenance_ticket_arr[$t]['vLatitude'] = $rs_mt_premise[$t]['vLatitude'];
                        $maintenance_ticket_arr[$t]['vLongitude'] = $rs_mt_premise[$t]['vLongitude'];
                        $maintenance_ticket_arr[$t]['dMaintenanceStartDate'] = date_display_report_date($rs_mt_premise[$t]['dMaintenanceStartDate']);

                        $maintenance_ticket_arr[$t]['vAddress'] = $rs_mt_premise[$t]['vAddress1'].' '.$rs_mt_premise[$t]['vStreet'].' '.$rs_mt_premise[$t]['vCity'].' '.$rs_mt_premise[$t]['vState'];
                        $maintenance_ticket_arr[$t]['vIcon'] = $vIcon;
                    }
                }
            }
        }

        $data['maintenanceTicketData'] = $maintenance_ticket_arr;
        return $data;
    }

    public function getSerachAwarenessTaskData($param) {
        global $sqlObj;
        //echo "<pre>";print_r($param);exit;
        $data = array();

        $where = array();
        $iAId= $param['iAwarenessTaskId'];
        
        if($iAId != ""){
           $where[] = ' awareness."iAId" IN ('.$iAId.')'; 
        }
        $whereQuery = implode(" AND ", $where);
        $awarenessData = 'SELECT awareness.*, s."vName" as "vPremiseName", s."vAddress1", s."vStreet", s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude", st."vTypeName" as "vPremiseType", sm."vState", cm."vCity", e."vEngagement", z."iNetworkId", CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName") AS "vContactName", CONCAT(u."vFirstName", \' \', u."vLastName") AS "vTechnicianName" FROM awareness 
        LEFT JOIN engagement_mas e on e."iEngagementId" = awareness."iEngagementId" 
        LEFT JOIN premise_mas s on s."iPremiseId" = awareness."iPremiseId" 
        LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId" 
        LEFT JOIN zone z on s."iZoneId" = z."iZoneId" 
        LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" 
        LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"
        LEFT JOIN fiberinquiry_details sd on sd."iFiberInquiryId" = awareness."iFiberInquiryId" 
        LEFT JOIN user_mas u on u."iUserId" = awareness."iTechnicianId" 
        LEFT JOIN contact_mas on contact_mas."iCId"= sd."iCId" WHERE '.$whereQuery.' ORDER BY awareness."iAId" Desc';
        $data['awarenessTaskData'] = $sqlObj->GetAll($awarenessData);
        //print_r($data);exit;
        return $data;
    }

    public function getSerachEquipmentData($param) {
        global $sqlObj;
        //echo "<pre>";print_r($param);exit;
        $data = array();

        $where = array();
        $iEquipmentId= $param['iEquipmentId'];
        
        if($iEquipmentId != ""){
           $where[] = ' equipment."iEquipmentId" IN ('.$iEquipmentId.')'; 
        }
        $whereQuery = implode(" AND ", $where);
        $equipmentData = 'SELECT equipment.* , em."vModelName", m."vMaterial", p."vPower", s."vName" as "vPremiseName", s."vAddress1", s."vStreet", s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude", st."vTypeName" as "vPremiseType", it."vInstallType", lt."vLinkType", os."vOperationalStatus", zone."iNetworkId", circuit."vCircuitName", n."vName" as "vNetwork", c."vCity", sm."vState" 
        FROM equipment 
        LEFT JOIN equipment_model em on equipment."iEquipmentModelId" = em."iEquipmentModelId" 
        LEFT JOIN material_mas m on equipment."iMaterialId" = m."iMaterialId" 
        LEFT JOIN power_mas p on equipment."iPowerId" = p."iPowerId" 
        LEFT JOIN premise_mas s on equipment."iPremiseId" = s."iPremiseId" 
        LEFT JOIN zone on s."iZoneId" = zone."iZoneId" 
        LEFT JOIN network n on zone."iNetworkId" = n."iNetworkId" 
        LEFT JOIN city_mas c on s."iCityId" = c."iCityId" 
        LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" 
        LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId" 
        LEFT JOIN install_type_mas it on equipment."iInstallTypeId" = it."iInstallTypeId" 
        LEFT JOIN link_type_mas lt on equipment."iLinkTypeId" = lt."iLinkTypeId" 
        LEFT JOIN operational_status_mas os on equipment."iOperationalStatusId" = os."iOperationalStatusId" 
        LEFT JOIN premise_circuit on equipment."iPremiseCircuitId" = premise_circuit."iPremiseCircuitId" 
        LEFT JOIN circuit on premise_circuit."iCircuitId" = circuit."iCircuitId" WHERE '.$whereQuery.' ORDER BY equipment."iEquipmentId" desc';
        //echo $equipmentData;exit;
        $data['equipmentData'] = $sqlObj->GetAll($equipmentData);
        //print_r($data);exit;
        return $data;
    }

    public function getSerachPremiseCircuitData($param){
        global $sqlObj;
        //echo "<pre>";print_r($param);exit;
        $data = array();

        $where = array();
        $premiseCircuitId= $param['premiseCircuitId'];
        
        if($premiseCircuitId != ""){
           $where[] = ' premise_circuit."iPremiseCircuitId" IN ('.$premiseCircuitId.')'; 
        }
        $whereQuery = implode(" AND ", $where);

        $pCircuitData = 'SELECT premise_circuit.* , wt."vType" as "vWorkOrderType", s."vName" as "vPremiseName", s."vAddress1", s."vStreet", s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude",  st."vTypeName" as "vPremiseType", z."vZoneName", z."iNetworkId", n."vName" as "vNetwork", circuit."vCircuitName", connection_type_mas."vConnectionTypeName", z."iNetworkId", c."vCity", sm."vState" 
            FROM premise_circuit 
            LEFT JOIN workorder w ON premise_circuit."iWOId" = w."iWOId" 
            LEFT JOIN workorder_type_mas wt ON w."iWOTId" = wt."iWOTId" 
            LEFT JOIN service_order so ON w."iServiceOrderId" = so."iServiceOrderId" 
            LEFT JOIN premise_mas s ON premise_circuit."iPremiseId" = s."iPremiseId" 
            LEFT JOIN zone z ON s."iZoneId" = z."iZoneId"
            LEFT JOIN network n on z."iNetworkId" = n."iNetworkId" 
            LEFT JOIN city_mas c on s."iCityId" = c."iCityId" 
            LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" 
            LEFT JOIN site_type_mas st ON s."iSTypeId" = st."iSTypeId" 
            LEFT JOIN circuit ON premise_circuit."iCircuitId" = circuit."iCircuitId"
            LEFT JOIN connection_type_mas ON premise_circuit."iConnectionTypeId" = connection_type_mas."iConnectionTypeId" WHERE '.$whereQuery.'
            ORDER BY premise_circuit."iPremiseCircuitId" Desc ';
        $data['premiseCircuitData'] = $sqlObj->GetAll($pCircuitData);
        //print_r($data);exit;
        return $data;
    }

    public function getCustomLayers(){
        global $sqlObj;
        $sql = 'SELECT "iCLId", "vName" FROM "custom_layer" Where "iStatus" = \'1\' ORDER BY "vName" asc ';
        $rsdata = $sqlObj->GetAll($sql);
        return $rsdata;
    }

    public function getCustomLayerData($param, $site_url = ''){
        global $sqlObj;
        $data = array();
         $sqlData = 'SELECT "iCLId", "vName","vFile" FROM "custom_layer" where "iStatus" = 1';
        $data['custlayer'] = $sqlObj->GetAll($sqlData);
        return $data;
    }

    public function getZoneKMLFile(){
        global $sqlObj;

        $sql = 'SELECT "iZoneId", "vZoneName", "vFile", "iStatus" FROM "zone" WHERE "iStatus" = 1 AND "vFile" IS NOT NULL ORDER BY "vZoneName" asc';
        $zone_kml = $sqlObj->GetAll($sql);
        return $zone_kml;
    }


    public function getPremiseFiberInquiryFilterData($param){
        global $sqlObj;
        $data = array();
        $premiseId= $param['premiseid'];
        $fInquiryId= $param['fInquiryId'];
        if($premiseId != ""){
            $sitewhere = array();
            $sitewhere[] = ' "iPremiseId" IN ('.$premiseId.')'; 
            $sitewhereQuery = implode(" AND ", $sitewhere);
            $siteSql = 'SELECT s."iPremiseId" as premiseid, s."iSTypeId" as sTypeId, s."iSSTypeId" as sSTypeId, s."iCityId", s."iZoneId", z."iNetworkId", s."iZipcode", s."iStatus", st_astext(s."vPointLatLong") as point FROM premise_mas s LEFT JOIN zone z ON s."iZoneId" = z."iZoneId"  WHERE '.$sitewhereQuery.' ORDER BY premiseid';
            //echo $siteSql;exit();
            $data['siteData'] = $sqlObj->GetAll($siteSql);
        }

        $srwhere = array();
        
        if($fInquiryId != ""){
            $srwhere[] = ' fiberinquiry_details."iFiberInquiryId" IN ('.$fInquiryId.')'; 
            $srwhereQuery = implode(" AND ", $srwhere);
            
            $fInquiryData = 'SELECT fiberinquiry_details.*,contact_mas."vFirstName", contact_mas."vLastName", zone."vZoneName", zone."iNetworkId", network."vName" as "vNetwork", engagement_mas."vEngagement", sst."vSubTypeName" as "vPremiseSubType", s."vName" as "vPremiseName", city_mas."vCity", state_mas."vState" FROM "public"."fiberinquiry_details" left join contact_mas on "contact_mas"."iCId" = "fiberinquiry_details"."iCId" LEFT JOIN state_mas  on "state_mas"."iStateId" = "fiberinquiry_details"."iStateId" LEFT JOIN "city_mas" on "city_mas"."iCityId" = "fiberinquiry_details"."iCityId" LEFT JOIN "zone" on "zone"."iZoneId" = "fiberinquiry_details"."iZoneId" LEFT JOIN "network" on "network"."iNetworkId" = "zone"."iNetworkId" LEFT JOIN "engagement_mas" on "engagement_mas"."iEngagementId" = "fiberinquiry_details"."iEngagementId" LEFT JOIN "site_sub_type_mas" sst on sst."iSSTypeId" = "fiberinquiry_details"."iPremiseSubTypeId" LEFT JOIN "premise_mas" s on s."iPremiseId" = "fiberinquiry_details"."iMatchingPremiseId" WHERE  '.$srwhereQuery.' ORDER BY fiberinquiry_details."iFiberInquiryId"';
			//echo $fInquiryData;exit;
            $data['fiberinquiryData'] = $sqlObj->GetAll($fInquiryData);
        }
      
        return $data;
    }

    public function getNetworkLayerData($param){
        global $sqlObj;
        $data = array();
        $sqlData = 'SELECT "iNetworkId", "vName","vFile" FROM "network" where "iStatus" = 1';
        $data['networklayer'] = $sqlObj->GetAll($sqlData);
        return $data;
    }

    public function getZoneLayerData($param){
        global $sqlObj;
        /*$data = array();
         $sqlData = 'SELECT "iZoneId", "vZoneName", "vFile" FROM "zone" where "iStatus" = 1';
        $data['zonelayer'] = $sqlObj->GetAll($sqlData);
        return $data;*/
        $geoArr = array();
        $zoneSql = 'SELECT st_astext("PShape") as geotxt, "iZoneId" FROM zone WHERE "iStatus" = 1'; 
        $data['zonelayer'] = $sqlObj->GetAll($zoneSql);
        //echo "<pre>";print_r($geoArr);exit;
        return $data;
    }

    public function getConnectionTypes(){
        global $sqlObj;
        $data = array();
         $sqlData = 'SELECT "iConnectionTypeId", "vConnectionTypeName", "iStatus" FROM "connection_type_mas" where "iStatus" = 1';
        $data = $sqlObj->GetAll($sqlData);
        return $data;
    }

    public function getNetworkKMLData($iNetworkId = ''){
        global $sqlObj, $network_path, $network_url;
        $extra_str = '';
        if($iNetworkId != ''){
            $extra_str .= ' AND "iNetworkId" IN ('.$iNetworkId.')';
        }
        $geoArr = array();
        $zoneSql = 'SELECT st_astext("PShape") as geotxt, "iNetworkId", "vName" FROM network  where "iStatus" = 1'.$extra_str; 
        $data['networks'] = $sqlObj->GetAll($zoneSql);
        if(isset($data['networks']) && $data['networks'] != ''){
            foreach($data['networks'] as $key => $network){
                $polygon = str_replace("POLYGON((", '', $network['geotxt']);
                $polygon = str_replace("))", '', $polygon);
                //print_r($polygon);
                $polyArr = explode(",", $polygon);
                //print_r($polyArr);
                foreach($polyArr as $latlng){
                    $latLngArr = explode(" ", $latlng);
                    //print_r($latLngArr);
                    $geoArr[$network['iNetworkId']][] = array(
                        'lat' => (float) $latLngArr[1],
                        'lng' => (float) $latLngArr[0]
                    );
                }
            }
        }
        return $geoArr;
        /*global $sqlObj, $network_path, $network_url;
        $data = array();
        $extra_str = '';
        if($iNetworkId != ''){
            $extra_str .= ' AND "iNetworkId" IN ('.$iNetworkId.')';
        }
        $sqlData = 'SELECT "iNetworkId", "vName","vFile" FROM "network" where "iStatus" = 1'.$extra_str;
        $rs = $sqlObj->GetAll($sqlData);
        if($rs) {
            $ni = count($rs);
            for($i=0; $i<$ni; $i++){
                if($rs[$i]['vFile'] != "" && file_exists($network_path.$rs[$i]['vFile'])){
                    $rs[$i]['file_url'] = $network_url.$rs[$i]['vFile'];;
                }
            }
        }
        return $rs;*/
    }
    
}
?>