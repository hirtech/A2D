<?php
include_once("security_audit_log.inc.php");

class Fieldmap {

    function Fieldmap() {
        $this->SALObj = new Security_audit_log();
    }

    public function getSiteTypeIcon($premiseId){
        global $sqlObj, $premise_type_icon_url, $premise_type_icon_path, $site_url;
        $siteTypeSql = 'SELECT "icon" FROM "site_type_mas" WHERE "iSTypeId" = '.$premiseId.' AND "iStatus" = 1 ORDER BY "vTypeName" asc';
        $sType = $sqlObj->GetAll($siteTypeSql);
        //print_r($sType); die;
        $vIcon = $site_url."images/black_icon.png";
        if(!empty($sType)) {
            if($sType[0]['icon'] != '' && file_exists($premise_type_icon_path.$sType[0]['icon'])) {
                $vIcon = $premise_type_icon_url.$sType[0]['icon'];
            }
        }
        return $vIcon;
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
        $where = array();
        $where1 = array();

        $attrSql = 'SELECT "iSTypeId" FROM site_type_mas'; 
        $premiseIds = $sqlObj->GetAll($attrSql);
        $sIdsArr = array();
        foreach ($premiseIds as $key => $value) {
           $sIdsArr[$key] = $value['iSTypeId'];
        }
        $sIds = implode(",", $sIdsArr);
        $where[] = 's."iSTypeId" IN('.$sIds.')';

        //$where[] ='s."iStatus" = 1';

        $whereQuery = implode(" AND ", $where);

        $where1[] = 's."iSTypeId" IN('.$sIds.')';

        //$where1[] ='s."iStatus" = 1';

        $whereQuery1 = implode(" AND ", $where1);

        $sql_attr = 'SELECT sa."iPremiseId", sa."iSAttributeId" FROM "site_attribute" sa INNER JOIN premise_mas s ON sa."iPremiseId" = s."iPremiseId" WHERE '.$whereQuery1.' ORDER BY sa."iPremiseId"';
        $rs_sql_attr = $sqlObj->GetAll($sql_attr);
        $ai = count($rs_sql_attr);
        $attr_arr = [];
        for($a=0; $a<$ai; $a++) {
            $attr_arr[$rs_sql_attr[$a]['iPremiseId']][] = $rs_sql_attr[$a]['iSAttributeId'];
        }
        //echo "<pre>";print_r($attr_arr);exit;

        $filterSql = 'SELECT s."iPremiseId" as premiseid, s."iSTypeId" as sTypeId, s."iSSTypeId" as sSTypeId, s."iCityId", s."iZoneId", z."iNetworkId", s."iZipcode", s."iStatus", st_astext(s."vPointLatLong") as point FROM premise_mas s LEFT JOIN zone z ON s."iZoneId" = z."iZoneId"  WHERE '.$whereQuery.' ORDER BY premiseid';
        $data['sites'] = $sqlObj->GetAll($filterSql);
        //echo "<pre>";print_r($data['sites']);exit;
        if(!empty($data['sites'])) {
            $ni = count($data['sites']);
            for($i=0; $i<$ni; $i++){
                $data['sites'][$i]['sattributeid'] = implode(",", $attr_arr[$data['sites'][$i]['premiseid']]) ;
                //echo "<pre>";print_r($data['sites']);exit;
            }
        }
        //echo "<pre>";print_r($data['sites']);exit;
        return $data;
    }

    public function getJson($param, $site_url){
        //echo "<pre>";print_r($param);exit();
        global $sqlObj, $field_map_json_url;
        include_once($function_path."image.inc.php");

        $field_map_json_path = $field_map_json_url;
        $tmp_siteArr = array();

        $siteJsonUrl = $field_map_json_path."/premise-data.json";
        $site = array();
        $finalArr = array();
        $_finalArr = array();
        $response = array();
        $siteData = json_decode(file_get_contents($siteJsonUrl), true);
        $siteArr = $siteData['sites'];
      
        //print_r($siteArr); die('ok');
        if(isset($param['siteFilter']) && $param['siteFilter'] != ''){
            $siteFilterArr = explode(",", $param['siteFilter']);
            $siteFilter_data = $this->multi_array_search($siteArr,$siteFilterArr);
            //$response['sites'] = $siteFilter_data;
            if(!empty($response['sites'])){
                //$response['sites'] = array_replace($response['sites'],$siteFilter_data);
                $response['sites'] = $response['sites'] + $siteFilter_data;
            }else{
                $response['sites'] = $siteFilter_data;
            }
        }
        if(isset($param['srFilter']) && $param['srFilter'] != ''){
            $srFilterArr = explode(",", $param['srFilter']);
            $srJsonUrl = $field_map_json_path."fiberInquiry-data.json";
            $srData = json_decode(file_get_contents($srJsonUrl), true);
            $srArr = $srData['sites'];
            $srFilter_data = $this->multi_array_search($srArr,$srFilterArr);
            //$response['sites'] = $srFilter_data;
            if(!empty($response['sites'])){
                //$response['sites'] = array_replace($response['sites'],$srFilter_data);
                $response['sites'] = $response['sites'] + $srFilter_data;
            }else{
                $response['sites'] = $srFilter_data;
            }
        }
        if(isset($param['city']) && $param['city'] != ''){
            $finalArr = array();
            $selectedCity = explode(",", $param['city']);
            //echo "<pre>";print_r($selectedCity);exit();
            if(empty($finalArr)){
                $finalArr = $siteArr;
            }
            foreach($finalArr as $_key => $site){
                if(!in_array($site['cityid'], $selectedCity) ){
                    unset($finalArr[$_key]);
                }
            }
            //$response['sites'] = $finalArr;
            if(!empty($response['sites'])){
                //$response['sites'] = array_replace($response['sites'],$srFilter_data);
                $response['sites'] = $response['sites'] + $finalArr;
            }else{
                $response['sites'] = $finalArr;
            }
        }

        if(isset($param['network']) && $param['network'] != ''){
            $finalArr = array();
            $selectedNetwork = explode(",", $param['network']);
            //echo "<pre>";print_r($selectedNetwork);exit();
            if(empty($finalArr)){
                $finalArr = $siteArr;
            }
            foreach($finalArr as $_key => $site){
                if(!in_array($site['networkid'], $selectedNetwork) ){
                    unset($finalArr[$_key]);
                }
            }
            //$response['sites'] = $finalArr;
            if(!empty($response['sites'])){
                //$response['sites'] = array_replace($response['sites'],$srFilter_data);
                $response['sites'] = $response['sites'] + $finalArr;
            }else{
                $response['sites'] = $finalArr;
            }
        }
        if(isset($param['zipcode']) && $param['zipcode'] != ''){
            $finalArr = array();
            $selectedZipcode = explode(",", $param['zipcode']);
            //echo "<pre>";print_r($selectedZipcode);exit();
            if(empty($finalArr)){
                $finalArr = $siteArr;
            }
            foreach($finalArr as $_key => $site){
                if(!in_array($site['zipcode'], $selectedZipcode) ){
                    unset($finalArr[$_key]);
                }
            }
            //$response['sites'] = $finalArr;
            if(!empty($response['sites'])){
                //$response['sites'] = array_replace($response['sites'],$srFilter_data);
                $response['sites'] = $response['sites'] + $finalArr;
            }else{
                $response['sites'] = $finalArr;
            }
        }

        if(isset($param['zone']) && $param['zone'] != ''){
            $finalArr = array();
             
            $zones = $this->getZonesData($param['zone']);
            $selectedZones = explode(",", $param['zone']);

            if(empty($finalArr)){
                $finalArr = $siteArr;
            }
            
            foreach($finalArr as $_key => $site){
                //print_r($finalArr[$_key]); die;
                if(!in_array($site['zoneid'], $selectedZones) ){
                    unset($finalArr[$_key]);
                }
            }
            //$response['sites'] = $finalArr;
            if(!empty($response['sites'])){
                //$response['sites'] = array_replace($response['sites'],$srFilter_data);
                //$response['sites'] = $response['sites'] + $finalArr;
                $response['sites'] =array_intersect_key($response['sites'],$finalArr);
            }/*else{
                $response['sites'] = $finalArr;
            }*/
            $response['polyZone'] = $zones;
             //print_r($response); die;
        }

        if(isset($param['networkLayer']) && $param['networkLayer'] != ''){
            $networkLayerArr = explode(",", $param['networkLayer']);
            $networkLayerJsonUrl = $field_map_json_path."networkLayer.json";
            $ntworklayerData = json_decode(file_get_contents($networkLayerJsonUrl), true);
            $nlayerArr = $ntworklayerData['networklayer'];
            $ntworklayerFilter_data = $this->multi_array_search($nlayerArr,$networkLayerArr);
            $response['networkLayer'] = $ntworklayerFilter_data;
        }

        if(isset($param['zoneLayer']) && $param['zoneLayer'] != ''){
            $zolyrArr = explode(",", $param['zoneLayer']);
            $zoneLayerJsonUrl = $field_map_json_path."zoneLayer.json";
            $layerData = json_decode(file_get_contents($zoneLayerJsonUrl), true);
            $zlayerArr = $layerData['zoneLayer'];
            $zlayerFilter_data = $this->multi_array_search($zlayerArr,$zolyrArr);
            $response['zoneLayer'] = $zlayerFilter_data;
        }

        if(isset($param['custlayer']) && $param['custlayer'] != ''){
            $culyrArr = explode(",", $param['custlayer']);
            $custlayerJsonUrl = $field_map_json_path."/customlayer.json";
            $layerData = json_decode(file_get_contents($custlayerJsonUrl), true);
            $layerArr = $layerData['customlayer'];
            $clayerFilter_data = $this->multi_array_search($layerArr,$culyrArr);
            $response['customlayer'] = $clayerFilter_data;
        }

        if(isset($param['fiberInquiryLayer']) && $param['fiberInquiryLayer'] != ''){
            $fiberInquiryJsonUrl = $field_map_json_path."fiberInquiry-data.json";
            $fiberInquiryData = json_decode(file_get_contents($fiberInquiryJsonUrl), true);
            $fiberInquiryArr = $fiberInquiryData['sites'];

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
            $serviceOrderJsonUrl = $field_map_json_path."serviceorder-data.json";
            $serviceOrderData = json_decode(file_get_contents($serviceOrderJsonUrl), true);
            $serviceOrderArr = $serviceOrderData['sites'];

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
            $workOrderJsonUrl = $field_map_json_path."workorder-data.json";
            $workOrderData = json_decode(file_get_contents($workOrderJsonUrl), true);
            $workOrderArr = $workOrderData['sites'];

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
            $pCircuitStatusJsonUrl = $field_map_json_path."premiseCircuit-data.json";
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
            $pCircuitcTypeJsonUrl = $field_map_json_path."premiseCircuit-data.json";
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
            $premiseStatusJsonUrl = $field_map_json_path."premise-data.json";
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
            $premiseAttrJsonUrl = $field_map_json_path."premise-data.json";
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
            }

            if(isset($param['premiseStatusLayer']) && $param['premiseStatusLayer'] != ''){
                $pslyrArr = explode(",", $param['premiseStatusLayer']);
                foreach ($premiseAttributeArr as $key => $lblVal) {
                    if(!in_array($lblVal['iStatus'], $pslyrArr) ){
                        unset($premiseAttributeArr[$key]);
                    }
                }
            }

            if(isset($param['premiseTypeLayer']) && $param['premiseTypeLayer'] != ''){
                $ptlyrArr = explode(",", $param['premiseTypeLayer']);
                foreach ($premiseAttributeArr as $key => $lblVal) {
                    if(!in_array($lblVal['stypeid'], $ptlyrArr) ){
                        unset($premiseAttributeArr[$key]);
                    }
                }
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
            $premiseTypeJsonUrl = $field_map_json_path."premise-data.json";
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
            }

            if(isset($param['premiseAttribute']) && $param['premiseAttribute'] != ''){
                $palyrArr = explode(",", $param['premiseAttribute']);
                foreach($palyrArr as $attr){
                    $site = $this->searchFor($attr, $premiseTypeLayerArr, 'sattributeid');
                    $premiseTypeLayerArr = $premiseTypeLayerArr + $site;
                }
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
            $premisesubTypeJsonUrl = $field_map_json_path."premise-data.json";
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
            }

            if(isset($param['premiseAttribute']) && $param['premiseAttribute'] != ''){
                $palyrArr = explode(",", $param['premiseAttribute']);
                foreach($palyrArr as $attr){
                    $site = $this->searchFor($attr, $premisesTypeLayerArr, 'sattributeid');
                    $premisesTypeLayerArr = $premisesTypeLayerArr + $site;
                }
            }

            if(isset($param['premiseTypeLayer']) && $param['premiseTypeLayer'] != ''){
                $ptlyrArr = explode(",", $param['premiseTypeLayer']);
                foreach ($premisesTypeLayerArr as $key => $lblVal) {
                    if(!in_array($lblVal['stypeid'], $ptlyrArr) ){
                        unset($premisesTypeLayerArr[$key]);
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
        //echo "<pre>";print_r($premiseAttributeArr);exit();
        //echo "<pre>";print_r($premisesubTypeLayerArr);exit;
        //echo "<pre>";print_r($premiseStatusArr);
        //echo "<pre>";print_r($premiseTypeLayerArr);
        if(!empty($premiseStatusArr) && empty($premiseAttributeArr) && empty($premiseTypeLayerArr) && empty($premisesubTypeLayerArr)){
            $response['sites'] = $premiseStatusArr;
        }else if(empty($premiseStatusArr) && !empty($premiseAttributeArr) && empty($premiseTypeLayerArr) && empty($premisesubTypeLayerArr)){
            $response['sites'] = $premiseAttributeArr;
        }else if(empty($premiseStatusArr) && empty($premiseAttributeArr) && !empty($premiseTypeLayerArr) && empty($premisesubTypeLayerArr)){
            $response['sites'] = $premiseTypeLayerArr;
        }else if(empty($premiseStatusArr) && empty($premiseAttributeArr) && empty($premiseTypeLayerArr) && !empty($premisesubTypeLayerArr)){
            $response['sites'] = $premisesubTypeLayerArr;
        }else {
            $newPremiseArr = array_merge($premiseStatusArr, $premiseAttributeArr, $premiseTypeLayerArr, $premisesubTypeLayerArr);
            $premiseArr = array_map("unserialize", array_unique(array_map("serialize", $newPremiseArr)));
            $response['sites'] = $premiseArr;
        }
        //echo "<pre>";print_r($response['sites']);exit();
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
        $sOrderData = 'SELECT service_order.* , s."vName" as "vPremiseName", s."vAddress1",
            s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude", st."vTypeName" as "vPremiseType", cm."vCompanyName", z."vZoneName", z."iNetworkId", n."vName" as "vNetwork", ct."vConnectionTypeName", st1."vServiceType" as "vServiceType1", c."vCity", sm."vState"  
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
            ORDER BY service_order."iServiceOrderId" desc';
        $data['sites'] = $sqlObj->GetAll($sOrderData);
        return $data;
    } 

    public function getWorkOrderData($param){
        global $sqlObj;
        $data = array();
        $wOrderData = 'SELECT workorder.* , s."vName" as "vPremiseName", s."vAddress1", s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude",  st."vTypeName" as "vPremiseType", z."vZoneName", z."iNetworkId", n."vName" as "vNetwork", so."vMasterMSA", so."vServiceOrder", ws."vStatus", wt."vType", concat(u."vFirstName",\' \', u."vLastName") as "vRequestor", concat(u1."vFirstName", \' \', u1."vLastName") as "vAssignedTo", c."vCity", sm."vState" 
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
        $pCircuitData = 'SELECT premise_circuit.* , wt."vType" as "vWorkOrderType", s."vName" as "vPremiseName", s."vAddress1", s."iCityId", s."iCountyId", s."iStateId", s."iZoneId", s."iZipcode", s."vLatitude", s."vLongitude",  st."vTypeName" as "vPremiseType", z."vZoneName", z."iNetworkId", n."vName" as "vNetwork", circuit."vCircuitName", connection_type_mas."vConnectionTypeName", z."iNetworkId", c."vCity", sm."vState" 
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
        
        $where[] ='"iStatus" = 1';

        $whereQuery = implode(" AND ", $where);
        $siteSql = 'SELECT "iPremiseId" as premiseid, "iSTypeId" as sTypeId, "iSSTypeId" as sSTypeId, "iCityId", "iZoneId", st_astext(ST_Centroid("vPolygonLatLong")) as polyCenter, st_astext("vPolygonLatLong") as polygon, st_astext("vPointLatLong") as point, st_astext("vPolyLineLatLong") as poly_line FROM premise_mas WHERE '.$whereQuery;
        //echo $siteSql;exit();
        $data['siteData'] = $sqlObj->GetAll($siteSql);
        // print_r($data);exit();
        return $data;
    }

    public function getSerachSRData($param){
       global $sqlObj;
        $data = array();

        $where = array();
        $srId= $param['srId'];
        
        if($srId != ""){
           $where[] = ' fiberinquiry_details."iFiberInquiryId" IN ('.$srId.')'; 
        }
        $where[] = ' fiberinquiry_details."iStatus" != 4 ';
        $whereQuery = implode(" AND ", $where);
        
        $SrData = 'SELECT fiberinquiry_details.*,contact_mas."vFirstName" FROM "public"."fiberinquiry_details" left join contact_mas on "contact_mas"."iCId" = "fiberinquiry_details"."iCId" LEFT JOIN state_mas  on "state_mas"."iStateId" = "fiberinquiry_details"."iStateId" LEFT JOIN "city_mas" on "city_mas"."iCityId" = "fiberinquiry_details"."iCityId" WHERE  '.$whereQuery.' ORDER BY fiberinquiry_details."iFiberInquiryId"';
        $data['srData'] = $sqlObj->GetAll($SrData);
      
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


    public function getSiteSRFilterData($param){
        global $sqlObj;
        $data = array();
        $premiseId= $param['premiseid'];
        $srId= $param['srId'];
        if($premiseId != ""){
            $sitewhere = array();
        
            $sitewhere[] = ' "iPremiseId" IN ('.$premiseId.')'; 
            
            $sitewhere[] ='"iStatus" = 1';

            $sitewhereQuery = implode(" AND ", $sitewhere);
            $siteSql = 'SELECT "iPremiseId" as premiseid, "iSTypeId" as sTypeId, "iSSTypeId" as sSTypeId, "iCityId", "iZoneId", st_astext(ST_Centroid("vPolygonLatLong")) as polyCenter, st_astext("vPolygonLatLong") as polygon, st_astext("vPointLatLong") as point, st_astext("vPolyLineLatLong") as poly_line FROM "premise_mas" WHERE '.$sitewhereQuery;
            //echo $siteSql;exit();
            $data['siteData'] = $sqlObj->GetAll($siteSql);
        }

        $srwhere = array();
        
        if($srId != ""){
        
            $srwhere[] = ' fiberinquiry_details."iFiberInquiryId" IN ('.$srId.')'; 
        
            $srwhereQuery = implode(" AND ", $srwhere);
            
            $SrData = 'SELECT fiberinquiry_details.*, contact_mas."vFirstName" FROM "public"."fiberinquiry_details" left join contact_mas on "contact_mas"."iCId" = "fiberinquiry_details"."iCId" LEFT JOIN state_mas  on "state_mas"."iStateId" = "fiberinquiry_details"."iStateId" LEFT JOIN "city_mas" on "city_mas"."iCityId" = "fiberinquiry_details"."iCityId" WHERE  '.$srwhereQuery.' ORDER BY fiberinquiry_details."iFiberInquiryId"';
			//echo $SrData;exit;
            $data['srData'] = $sqlObj->GetAll($SrData);
        }
      
        return $data;
    }


    /** Function for get site data using last synchronize date (for app)**/
    public function getSiteDataBySyncDate($param){
        global $sqlObj;
        $data = array();
        $where_arr = array();
        $last_sync_date = trim($param['last_sync_date']);
        $current_date = trim($param['current_date']);

        $attrSql = 'SELECT "iSTypeId" FROM site_type_mas'; 
        $premiseIds = $sqlObj->GetAll($attrSql);
        $sIdsArr = array();
        foreach ($premiseIds as $key => $value) {
           $sIdsArr[$key] = $value['iSTypeId'];
        }
        $sIds = implode(",", $sIdsArr);
        
        $where_arr[] = ' premise_mas."iSTypeId" IN('.$sIds.')';
        $where_arr[] ='  premise_mas."iStatus" = 1';
        if((isset($last_sync_date) && $last_sync_date != "")){
            $where_arr[] = " (( DATE(premise_mas.\"dAddedDate\") >= '" . $last_sync_date . "' AND DATE(premise_mas.\"dAddedDate\") <= '" . $current_date. "')  OR (DATE(premise_mas.\"dModifiedDate\") >= '" . $last_sync_date . "' AND DATE(premise_mas.\"dModifiedDate\") <= '" . $current_date. "' ))";
        }

        $whereQuery = (!empty($where_arr))?' WHERE '.implode(" AND ", $where_arr):'';

        $sql_attr = 'SELECT sa."iPremiseId", sa."iSAttributeId" FROM "site_attribute" sa INNER JOIN premise_mas  ON sa."iPremiseId" = premise_mas."iPremiseId"  '.$whereQuery.' ORDER BY sa."iPremiseId"';
        $rs_sql_attr = $sqlObj->GetAll($sql_attr);
        $ai = count($rs_sql_attr);
        $attr_arr = [];
        for($a=0; $a<$ai; $a++) {
            $attr_arr[$rs_sql_attr[$a]['iPremiseId']][] = $rs_sql_attr[$a]['iSAttributeId'];
        }


        $filterSql = 'SELECT premise_mas."iPremiseId", premise_mas."vName",premise_mas."iSTypeId",premise_mas."iSSTypeId", premise_mas."vAddress1", premise_mas."vAddress2", premise_mas."vStreet", premise_mas."vCrossStreet", premise_mas."iZipcode", premise_mas."iStateId", premise_mas."iCountyId", premise_mas."iCityId", premise_mas."iGeometryType", premise_mas."iZoneId", premise_mas."dAddedDate",  premise_mas."dModifiedDate",  premise_mas."iStatus",  ST_AsGeoJSON(premise_mas."vPointLatLong") as vPointLatLong,  ST_AsGeoJSON(premise_mas."vPolygonLatLong") as vPolygonLatLong,  ST_AsGeoJSON(premise_mas."vPolyLineLatLong") as vPolyLineLatLong, site_type_mas."icon" FROM "premise_mas" Left Join site_type_mas on premise_mas."iSTypeId" = site_type_mas."iSTypeId" '.$whereQuery.' ORDER BY premise_mas."iPremiseId" ';
        $site_data = $sqlObj->GetAll($filterSql);

        $data['sites'] = $site_data;
        $data['site_atrribute'] = $attr_arr;

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
        $data = array();
         $sqlData = 'SELECT "iZoneId", "vZoneName", "vFile" FROM "zone" where "iStatus" = 1';
        $data['zonelayer'] = $sqlObj->GetAll($sqlData);
        return $data;
    }

    public function getConnectionTypes(){
        global $sqlObj;
        $data = array();
         $sqlData = 'SELECT "iConnectionTypeId", "vConnectionTypeName", "iStatus" FROM "connection_type_mas" where "iStatus" = 1';
        $data = $sqlObj->GetAll($sqlData);
        return $data;
    }
    
}
?>