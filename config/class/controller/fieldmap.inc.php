<?php
include_once("security_audit_log.inc.php");

class Fieldmap {

    function Fieldmap() {
        $this->SALObj = new Security_audit_log();
    }

    public function getSiteTypeIcon($siteId){
        global $sqlObj, $premise_type_icon_url, $premise_type_icon_path, $site_url;
        $siteTypeSql = 'SELECT "icon" FROM "site_type_mas" WHERE "iSTypeId" = '.$siteId.' AND "iStatus" = 1 ORDER BY "vTypeName" asc';
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

    public function getAttributes(){
        global $sqlObj;

        $sql = 'SELECT "iSAttributeId", "vAttribute", "iStatus" FROM "site_attribute_mas"  WHERE "iStatus" = 1  ORDER BY "vAttribute" asc';
        $sAttr = $sqlObj->GetAll($sql);
        return $sAttr;
    }

    public function getZones(){
        global $sqlObj;

        $sql = 'SELECT "iZoneId", "vZoneName" , "iStatus" FROM "zone" WHERE "iStatus" = 1 ORDER BY "vZoneName" asc';
        $skZones = $sqlObj->GetAll($sql);
        return $skZones;
    }

    public function getSiteType(){
        global $sqlObj;
        $siteTypeSql = 'SELECT "iSTypeId", "vTypeName" FROM "site_type_mas" WHERE "iStatus" = 1 ORDER BY "vTypeName" asc';
        $sType = $sqlObj->GetAll($siteTypeSql);
        $sTypeArr = array();
        foreach($sType as $k => $row){
            $sSTypeSql = 'SELECT "iSSTypeId", "vSubTypeName" FROM "site_sub_type_mas" WHERE "iSTypeId" = '.$row['iSTypeId'].' AND "iStatus" = 1 ORDER BY "vSubTypeName"';
            $sSType = $sqlObj->GetAll($sSTypeSql);
            $sTypeArr[$k] = $row;
            $sTypeArr[$k]['site_sub_types'] = $sSType;
        }
        return $sTypeArr;
    }

    public function getCities(){
        global $sqlObj;        

        $sql = 'SELECT "iCityId", "vCity" FROM "city_mas"  ORDER BY "vCity" asc';
        $cities = $sqlObj->GetAll($sql);
        
        return $cities;
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
        $siteIds = $sqlObj->GetAll($attrSql);
        $sIdsArr = array();
        foreach ($siteIds as $key => $value) {
           $sIdsArr[$key] = $value['iSTypeId'];
        }
        $sIds = implode(",", $sIdsArr);
        $where[] = '"iSTypeId" IN('.$sIds.')';

        $where[] ='"iStatus" = 1';

        $whereQuery = implode(" AND ", $where);

        $where1[] = 's."iSTypeId" IN('.$sIds.')';

        $where1[] ='s."iStatus" = 1';

        $whereQuery1 = implode(" AND ", $where1);

        $sql_attr = 'SELECT sa."iSiteId", sa."iSAttributeId" FROM "site_attribute" sa INNER JOIN site_mas s ON sa."iSiteId" = s."iSiteId" WHERE '.$whereQuery1.' ORDER BY sa."iSiteId"';
        $rs_sql_attr = $sqlObj->GetAll($sql_attr);
        $ai = count($rs_sql_attr);
        $attr_arr = [];
        for($a=0; $a<$ai; $a++) {
            $attr_arr[$rs_sql_attr[$a]['iSiteId']][] = $rs_sql_attr[$a]['iSAttributeId'];
        }
        //echo "<pre>";print_r($attr_arr);exit;

        $filterSql = 'SELECT "iSiteId" as siteid, "iSTypeId" as sTypeId, "iSSTypeId" as sSTypeId, "iCityId", "iZoneId", st_astext(ST_Centroid("vPolygonLatLong")) as polyCenter, st_astext("vPolygonLatLong") as polygon, st_astext("vPointLatLong") as point, st_astext("vPolyLineLatLong") as poly_line FROM "site_mas" WHERE '.$whereQuery.' ORDER BY siteid';
        $data['sites'] = $sqlObj->GetAll($filterSql);
        //echo "<pre>";print_r($data['sites']);exit;
        if(!empty($data['sites'])) {
            $ni = count($data['sites']);
            for($i=0; $i<$ni; $i++){
                $data['sites'][$i]['sattributeid'] = implode(",", $attr_arr[$data['sites'][$i]['siteid']]) ;
                //echo "<pre>";print_r($data['sites']);exit;
            }
        }
        //echo "<pre>";print_r($data['sites']);exit;
        return $data;
    }


    public function getJson($param, $site_url){
       // print_r($param);exit();
        global $sqlObj, $field_map_json_url;
        include_once($function_path."image.inc.php");

        $field_map_json_path = $field_map_json_url;
        $tmp_siteArr = array();

        $siteJsonUrl = $field_map_json_path."/site-type.json";
        $site = array();
        $finalArr = array();
        $_finalArr = array();
        $response = array();
        $siteData = json_decode(file_get_contents($siteJsonUrl), true);
        $siteArr = $siteData['sites'];
      
        //print_r($siteArr); die('ok');
        if(isset($param['sr']) && $param['sr'] != ''){
            $srJsonUrl = $field_map_json_path."/sr.json";
            $srData = json_decode(file_get_contents($srJsonUrl), true);
            //$response['sites'] = $srData['sites'];
            $response['sr'] = $srData['sites'];
        }

        if(isset($param['larvalfieldtask']) && $param['larvalfieldtask'] != ''){
            $fieldtask = $param['larvalfieldtask'];
            $fieldtaskJsonUrl = $field_map_json_path.'/'.$fieldtask.".json";
            $fieldtaskData = json_decode(file_get_contents($fieldtaskJsonUrl), true);
           
            $response['larval'] = $fieldtaskData['sites'];
        }
        if(isset($param['landingfieldtask']) && $param['landingfieldtask'] != ''){
            $fieldtask = $param['landingfieldtask'];
            $fieldtaskJsonUrl = $field_map_json_path.'/'.$fieldtask.".json";
            $fieldtaskData = json_decode(file_get_contents($fieldtaskJsonUrl), true);
            
            $response['landing_rate'] = $fieldtaskData['sites'];
        }

        if(isset($param['positive']) && $param['positive'] != ''){
            $positive = $param['positive'];
            $positiveJsonUrl = $field_map_json_path."/positive.json";
            $positiveData = json_decode(file_get_contents($positiveJsonUrl), true);
            
            $response['positive'] = $positiveData['sites'];
        }

        if(isset($param['sAttr']) && $param['sAttr'] != ''){
            $finalArr = array();
            $siteAttrArr = explode(",", $param['sAttr']);
            //echo "<pre>";print_r($siteAttrArr);exit;
            foreach($siteAttrArr as $attr){
                $site = $this->searchFor($attr, $siteArr, 'sattributeid');
                if(empty($finalArr)){
                    //echo $sT;
                    $finalArr = $site;
                } else {
                    //print_r($site); die;
                    $finalArr = $finalArr + $site;
                }
            }
            //$response['sites'] = $finalArr;
            if(!empty($response['sites'])){
                //$response['sites'] = array_replace($response['sites'],$finalArr);
                $response['sites'] = $response['sites'] + $finalArr;
            }else{
                $response['sites'] = $finalArr;
            }
        }

        if(isset($param['siteType']) && $param['siteType'] != ''){
            $finalArr = array();
            $siteTypeArr = explode(",", $param['siteType']);
            //print_r($_finalArr); die('ok');
            foreach($siteTypeArr as $sT){
                // if(isset($param['sAttr']) && $param['sAttr'] != ''){
                //     $site = $this->searchFor($sT, $_finalArr, 'stypeid');
                // } else {
                    $site = $this->searchFor($sT, $siteArr, 'stypeid');
                //}
                if(empty($finalArr)){
                    //echo $sT;
                    $finalArr = $site;
                } else {
                    //print_r($site); die;
                    $finalArr = $finalArr + $site;
                }
               
            }
            //$response['sites'] = $finalArr;
            if(!empty($response['sites'])){
                //$response['sites'] = array_replace($response['sites'],$finalArr);
                $response['sites'] = $response['sites'] + $finalArr;
            }else{
                $response['sites'] = $finalArr;
            }
        }

        if(isset($param['siteSubTypes']) && $param['siteSubTypes'] != ''){
            $finalArr = array();
            $siteSubTypesArr = explode(",", $param['siteSubTypes']);
            //echo "<pre>";print_r($siteSubTypesArr); die('ok');
            foreach($siteSubTypesArr as $sTArr){
                $sTArr1 = explode("|||", $sTArr);
                $sT = $sTArr1[0];
                $sT1 = $sTArr1[1];
                // if(isset($param['sAttr']) && $param['sAttr'] != ''){
                //     $site = $this->searchFor($sT1, $_finalArr, 'sstypeid');
                // } else {
                    $site = $this->searchFor($sT1, $siteArr, 'sstypeid');
                //}
                if(empty($finalArr)){
                    //echo $sT;
                    $finalArr = $site;
                } else {
                    //print_r($site); die;
                    $finalArr = $finalArr + $site;
                }
            }
            //$response['sites'] = $finalArr;
            if(!empty($response['sites'])){
                //$response['sites'] = array_replace($response['sites'],$finalArr);
                $response['sites'] = $response['sites'] + $finalArr;
            }else{
                $response['sites'] = $finalArr;
            }
            
        }
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
            $srJsonUrl = $field_map_json_path."/sr.json";
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
        if(isset($param['custlayer']) && $param['custlayer'] != ''){
            $culyrArr = explode(",", $param['custlayer']);
            $custlayerJsonUrl = $field_map_json_path."/customlayer.json";
            $layerData = json_decode(file_get_contents($custlayerJsonUrl), true);
            $layerArr = $layerData['customlayer'];
            $clayerFilter_data = $this->multi_array_search($layerArr,$culyrArr);
            $response['customlayer'] = $clayerFilter_data;
        }
        //print_r($layerArr); die;
        
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

    public function getsrData($param, $site_url = ''){

        global $sqlObj;
        $data = array();
        $SrData = 'SELECT fiberinquiry_details.*,contact_mas."vFirstName" FROM "public"."fiberinquiry_details" left join contact_mas on "contact_mas"."iCId" = "fiberinquiry_details"."iCId" LEFT JOIN state_mas  on "state_mas"."iStateId" = "fiberinquiry_details"."iStateId" LEFT JOIN "city_mas" on "city_mas"."iCityId" = "fiberinquiry_details"."iCityId" WHERE fiberinquiry_details."iStatus" != 4 ORDER BY fiberinquiry_details."iFiberInquiryId"';
        $data['sites'] = $sqlObj->GetAll($SrData);
        return $data;
    } 

    public function getlandingrateData($param, $site_url = ''){
        global $sqlObj;
        $data = array();
        $LandingData = 'SELECT DISTINCT ON (site_mas."iSiteId")site_mas."iSiteId"  as siteid,count(task_landing_rate."iSiteId"), site_mas."iSTypeId" as sTypeId, site_mas."iSSTypeId" as sSTypeId ,site_mas."iCityId", site_mas."iZoneId",st_astext(ST_Centroid("vPolygonLatLong")) as polyCenter, st_astext("vPolygonLatLong") as polygon, st_astext("vPointLatLong") as point, st_astext("vPolyLineLatLong") as poly_line FROM task_landing_rate left join site_mas on site_mas."iSiteId" = task_landing_rate."iSiteId" GROUP BY "site_mas"."iSiteId"';
        $data['sites'] = $sqlObj->GetAll($LandingData);
        return $data;
    }

    public function getlarvalData($param, $site_url = ''){
        global $sqlObj;
        $data = array();
        $LarvalData = 'SELECT DISTINCT ON (site_mas."iSiteId")site_mas."iSiteId" as siteid,count(site_mas."iSiteId"), site_mas."iSTypeId" as sTypeId, site_mas."iSSTypeId" as sSTypeId, site_mas."iCityId", site_mas."iZoneId",st_astext(ST_Centroid("vPolygonLatLong")) as polyCenter, st_astext("vPolygonLatLong") as polygon, st_astext("vPointLatLong") as point, st_astext("vPolyLineLatLong") as poly_line FROM "public"."task_larval_surveillance" left join site_mas on "site_mas"."iSiteId" = "task_larval_surveillance"."iSiteId" GROUP BY "site_mas"."iSiteId"';
        //echo $LarvalData;
        //exit;
        $data['sites'] = $sqlObj->GetAll($LarvalData);
        return $data;
    }
    public function getpositiveData($param, $site_url = ''){
        global $sqlObj;
        $data = array();
         $PositiveData = 'SELECT DISTINCT ON (s."iSiteId")s."iSiteId" as siteid,task_mosquito_pool."iTTId",task_mosquito_pool."iTMPId", s."iSTypeId" as sTypeId, s."iSSTypeId" as sSTypeId, s."iCityId", s."iZoneId",st_astext(ST_Centroid("vPolygonLatLong")) as polyCenter, st_astext("vPolygonLatLong") as polygon, st_astext("vPointLatLong") as point, st_astext("vPolyLineLatLong") as poly_line FROM "public"."task_mosquito_pool"  LEFT JOIN task_trap  on task_trap."iTTId" = task_mosquito_pool."iTTId" LEFT JOIN trap_type_mas tt on tt."iTrapTypeId" = task_trap."iTrapTypeId" LEFT JOIN site_mas s on s."iSiteId" = task_trap."iSiteId" LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId" LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId" LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId" LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId" LEFT JOIN task_mosquito_pool_result on task_mosquito_pool_result."iTMPId" = task_mosquito_pool."iTMPId" LEFT JOIN agent_mosquito on agent_mosquito."iAMId" = task_mosquito_pool_result."iAMId" LEFT JOIN result on result."iResultId" = task_mosquito_pool_result."iResultId" where task_mosquito_pool_result."iResultId" = 3';
        //$PositiveData = 'SELECT task_mosquito_pool."iTTId",task_mosquito_pool."iTMPId",site_mas."iSiteId" as siteid, site_mas."iSTypeId" as sTypeId, site_mas."iCityId", site_mas."iZoneId",st_astext("vPolygonLatLong") as polygon, st_astext("vPointLatLong") as point, st_astext("vPolyLineLatLong") as poly_line FROM "public"."task_mosquito_pool"  left join task_mosquito_count on "task_mosquito_count"."iTMCId" = "task_mosquito_pool"."iTMCId" left join task_mosquito_pool_result on "task_mosquito_pool_result"."iTMPId" = "task_mosquito_pool"."iTMPId" left join task_trap on "task_trap"."iTTId" = "task_mosquito_count"."iTTId"  left join site_mas on "site_mas"."iSiteId" = "task_trap"."iSiteId"';
        $data['sites'] = $sqlObj->GetAll($PositiveData);
        return $data;
    }

    public function getSerachSiteData($param){
        global $sqlObj;
        $data = array();
        $where = array();
        $siteId= $param['siteId'];
        
        if($siteId != ""){
           $where[] = ' "iSiteId" IN ('.$siteId.')'; 
        }
        
        $where[] ='"iStatus" = 1';

        $whereQuery = implode(" AND ", $where);
        $siteSql = 'SELECT "iSiteId" as siteid, "iSTypeId" as sTypeId, "iSSTypeId" as sSTypeId, "iCityId", "iZoneId", st_astext(ST_Centroid("vPolygonLatLong")) as polyCenter, st_astext("vPolygonLatLong") as polygon, st_astext("vPointLatLong") as point, st_astext("vPolyLineLatLong") as poly_line FROM site_mas WHERE '.$whereQuery;
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


    public function getSiteSRFilterData($param){
        global $sqlObj;
        $data = array();

        $siteId= $param['siteId'];
        $srId= $param['srId'];
        if($siteId != ""){
            $sitewhere = array();
        
            $sitewhere[] = ' "iSiteId" IN ('.$siteId.')'; 
            
            $sitewhere[] ='"iStatus" = 1';

            $sitewhereQuery = implode(" AND ", $sitewhere);
            $siteSql = 'SELECT "iSiteId" as siteid, "iSTypeId" as sTypeId, "iSSTypeId" as sSTypeId, "iCityId", "iZoneId", st_astext(ST_Centroid("vPolygonLatLong")) as polyCenter, st_astext("vPolygonLatLong") as polygon, st_astext("vPointLatLong") as point, st_astext("vPolyLineLatLong") as poly_line FROM "site_mas" WHERE '.$sitewhereQuery;
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

    public function getNearBySite($param){
        global $sqlObj;
        $data = array();
        $long = $param['long'];
        $lat = $param['lat'];
        $meter = $param['meter'];

        //$sql = "SELECT s.* FROM site_mas as s where ST_DWithin(ST_SetSRID(ST_MakePoint(s.\"vLongitude\",s.\"vLatitude\"), 4326)::geography, ST_MakePoint(". $long . ", " . $lat . ")::geography, ".$meter.") Order by s.\"iSiteId\" DESC ";
        $sql = "SELECT \"iSiteId\" as siteid, \"iSTypeId\" as sTypeId, \"iSSTypeId\" as sSTypeId \"iCityId\", \"iZoneId\", st_astext(ST_Centroid(\"vPolygonLatLong\")) as polyCenter, st_astext(\"vPolygonLatLong\") as polygon, st_astext(\"vPointLatLong\") as point, st_astext(\"vPolyLineLatLong\") as poly_line FROM site_mas as s where ST_DWithin(ST_SetSRID(ST_MakePoint(s.\"vLongitude\",s.\"vLatitude\"), 4326)::geography, ST_MakePoint(". $long . ", " . $lat . ")::geography, ".$meter.") Order by s.\"iSiteId\" DESC ";
        //echo $sql;exit();
        $rs_db = $sqlObj->GetAll($sql);

        $data['siteData'] = $rs_db;
        //print_r($data);exit();
        return $data;
    }

    /*
     * Function for get site data using last synchronize date (for app)
    */
    public function getSiteDataBySyncDate($param){
        global $sqlObj;
        $data = array();
        $where_arr = array();
        $last_sync_date = trim($param['last_sync_date']);
        $current_date = trim($param['current_date']);

        $attrSql = 'SELECT "iSTypeId" FROM site_type_mas'; 
        $siteIds = $sqlObj->GetAll($attrSql);
        $sIdsArr = array();
        foreach ($siteIds as $key => $value) {
           $sIdsArr[$key] = $value['iSTypeId'];
        }
        $sIds = implode(",", $sIdsArr);
        
        $where_arr[] = ' site_mas."iSTypeId" IN('.$sIds.')';
        $where_arr[] ='  site_mas."iStatus" = 1';
        if((isset($last_sync_date) && $last_sync_date != "")){
            $where_arr[] = " (( DATE(site_mas.\"dAddedDate\") >= '" . $last_sync_date . "' AND DATE(site_mas.\"dAddedDate\") <= '" . $current_date. "')  OR (DATE(site_mas.\"dModifiedDate\") >= '" . $last_sync_date . "' AND DATE(site_mas.\"dModifiedDate\") <= '" . $current_date. "' ))";
        }

        $whereQuery = (!empty($where_arr))?' WHERE '.implode(" AND ", $where_arr):'';

        $sql_attr = 'SELECT sa."iSiteId", sa."iSAttributeId" FROM "site_attribute" sa INNER JOIN site_mas  ON sa."iSiteId" = site_mas."iSiteId"  '.$whereQuery.' ORDER BY sa."iSiteId"';
        $rs_sql_attr = $sqlObj->GetAll($sql_attr);
        $ai = count($rs_sql_attr);
        $attr_arr = [];
        for($a=0; $a<$ai; $a++) {
            $attr_arr[$rs_sql_attr[$a]['iSiteId']][] = $rs_sql_attr[$a]['iSAttributeId'];
        }


        $filterSql = 'SELECT site_mas."iSiteId", site_mas."vName",site_mas."iSTypeId",site_mas."iSSTypeId", site_mas."vAddress1", site_mas."vAddress2", site_mas."vStreet", site_mas."vCrossStreet", site_mas."iZipcode", site_mas."iStateId", site_mas."iCountyId", site_mas."iCityId", site_mas."iGeometryType", site_mas."iZoneId", site_mas."dAddedDate",  site_mas."dModifiedDate",  site_mas."iStatus",  ST_AsGeoJSON(site_mas."vPointLatLong") as vPointLatLong,  ST_AsGeoJSON(site_mas."vPolygonLatLong") as vPolygonLatLong,  ST_AsGeoJSON(site_mas."vPolyLineLatLong") as vPolyLineLatLong, site_type_mas."icon" FROM "site_mas" Left Join site_type_mas on site_mas."iSTypeId" = site_type_mas."iSTypeId" '.$whereQuery.' ORDER BY site_mas."iSiteId" ';
        $site_data = $sqlObj->GetAll($filterSql);

        $data['sites'] = $site_data;
        $data['site_atrribute'] = $attr_arr;

        return $data;
    }

    /*
     * Function for get landing rate data using last synchronize date (for app)
    */
    public function getLandingRateBySyncDate($param = array()){
        global $sqlObj;
        $data = array();
        $where_arr = array();
        $last_sync_date = trim($param['last_sync_date']);
        $current_date = trim($param['current_date']);

        $where_arr[] ='  site_mas."iStatus" = 1';
        if((isset($last_sync_date) && $last_sync_date != "")){
            $where_arr[] = " (( DATE(task_landing_rate.\"dAddedDate\") >= '" . $last_sync_date . "' AND DATE(task_landing_rate.\"dAddedDate\") <= '" . $current_date. "')  OR (DATE(task_landing_rate.\"dModifiedDate\") >= '" . $last_sync_date . "' AND DATE(task_landing_rate.\"dModifiedDate\") <= '" . $current_date. "' ))";
        }

        $whereQuery = (!empty($where_arr))?' WHERE '.implode(" AND ", $where_arr):'';

        $LandingData = 'SELECT DISTINCT ON (site_mas."iSiteId") site_mas."iSiteId",count(task_landing_rate."iSiteId"), site_mas."iSTypeId" , site_mas."vName", site_mas."iSSTypeId" ,  site_mas."vAddress1", site_mas."vAddress2", site_mas."vStreet", site_mas."vCrossStreet", site_mas."iZipcode", site_mas."iStateId", site_mas."iCountyId", site_mas."iCityId", site_mas."iGeometryType", site_mas."iZoneId",ST_AsGeoJSON("vPolygonLatLong") as vPolygonLatLong, ST_AsGeoJSON("vPointLatLong") as vPointLatLong, ST_AsGeoJSON("vPolyLineLatLong") as vPolyLineLatLong,task_landing_rate."dAddedDate",  task_landing_rate."dModifiedDate"  FROM "task_landing_rate" left join site_mas on site_mas."iSiteId" = "task_landing_rate"."iSiteId" '.$whereQuery.' GROUP BY "site_mas"."iSiteId",task_landing_rate."dAddedDate",  task_landing_rate."dModifiedDate"';
        $data['land_rate_sites'] = $sqlObj->GetAll($LandingData);
        
        return $data;
    }

    /*
     * Function for get larval Survillance data using last synchronize date (for app)
    */
    public function getLarvalBySyncDate($param = array()){
        global $sqlObj;
        $data = array();
        $where_arr = array();
        $last_sync_date = trim($param['last_sync_date']);
        $current_date = trim($param['current_date']);

        $where_arr[] ='  site_mas."iStatus" = 1';
        if((isset($last_sync_date) && $last_sync_date != "")){
            $where_arr[] = " (( DATE(task_larval_surveillance.\"dAddedDate\") >= '" . $last_sync_date . "' AND DATE(task_larval_surveillance.\"dAddedDate\") <= '" . $current_date. "')  OR (DATE(task_larval_surveillance.\"dModifiedDate\") >= '" . $last_sync_date . "' AND DATE(task_larval_surveillance.\"dModifiedDate\") <= '" . $current_date. "' ))";
        }

        $whereQuery = (!empty($where_arr))?' WHERE '.implode(" AND ", $where_arr):'';

        $LarvalData = 'SELECT DISTINCT ON (site_mas."iSiteId")site_mas."iSiteId" ,count(site_mas."iSiteId"), site_mas."vName", site_mas."iSTypeId", site_mas."iSSTypeId", site_mas."vAddress1", site_mas."vAddress2", site_mas."vStreet", site_mas."vCrossStreet", site_mas."iZipcode", site_mas."iStateId", site_mas."iCountyId", site_mas."iCityId", site_mas."iGeometryType", site_mas."iZoneId", ST_AsGeoJSON("vPolygonLatLong") as polygon, ST_AsGeoJSON("vPointLatLong") as point, ST_AsGeoJSON("vPolyLineLatLong") as poly_line, task_larval_surveillance."dAddedDate",  task_larval_surveillance."dModifiedDate"  FROM "public"."task_larval_surveillance" left join site_mas on "site_mas"."iSiteId" = "task_larval_surveillance"."iSiteId" '.$whereQuery.' GROUP BY "site_mas"."iSiteId", task_larval_surveillance."dAddedDate",  task_larval_surveillance."dModifiedDate" ';
        
        $data = $sqlObj->GetAll($LarvalData);
        return $data;
    }

    /*
     * Function for get Positive data using last synchronize date (for app)
    */
    public function getPositiveBySyncDate($param = array()){
        global $sqlObj;
        $where_arr = array();
        $last_sync_date = trim($param['last_sync_date']);
        $current_date = trim($param['current_date']);
        $where_arr[] ='  site_mas."iStatus" = 1';
        if((isset($last_sync_date) && $last_sync_date != "")){
            $where_arr[] = " (( DATE(site_mas.\"dAddedDate\") >= '" . $last_sync_date . "' AND DATE(site_mas.\"dAddedDate\") <= '" . $current_date. "')  OR (DATE(site_mas.\"dModifiedDate\") >= '" . $last_sync_date . "' AND DATE(site_mas.\"dModifiedDate\") <= '" . $current_date. "' ))";
        }
        $where_arr[] = ' task_mosquito_pool_result."iResultId" = 3';

        $whereQuery = (!empty($where_arr))?' WHERE '.implode(" AND ", $where_arr):'';

       $sql_qry = 'SELECT DISTINCT ON (site_mas."iSiteId")site_mas."iSiteId" ,task_mosquito_pool."iTTId",task_mosquito_pool."iTMPId", task_mosquito_pool."dAddedDate", task_mosquito_pool."dModifiedDate", site_mas."vName", site_mas."iSTypeId", site_mas."iSSTypeId", site_mas."vAddress1", site_mas."vAddress2", site_mas."vStreet", site_mas."vCrossStreet", site_mas."iZipcode", site_mas."iStateId", site_mas."iCountyId", site_mas."iCityId", site_mas."iGeometryType", site_mas."iZoneId", ST_AsGeoJSON("vPolygonLatLong") as vPolygonLatLong, ST_AsGeoJSON("vPointLatLong") as vPointLatLong, ST_AsGeoJSON("vPolyLineLatLong") as vPolyLineLatLong FROM "public"."task_mosquito_pool"  LEFT JOIN task_trap  on task_trap."iTTId" = task_mosquito_pool."iTTId" LEFT JOIN trap_type_mas tt on tt."iTrapTypeId" = task_trap."iTrapTypeId" LEFT JOIN site_mas  on site_mas."iSiteId" = task_trap."iSiteId" LEFT JOIN county_mas c on site_mas."iCountyId" = c."iCountyId" LEFT JOIN state_mas sm on site_mas."iStateId" = sm."iStateId" LEFT JOIN city_mas cm on site_mas."iCityId" = cm."iCityId" LEFT JOIN site_type_mas st on site_mas."iSTypeId" = st."iSTypeId" LEFT JOIN task_mosquito_pool_result on task_mosquito_pool_result."iTMPId" = task_mosquito_pool."iTMPId" LEFT JOIN agent_mosquito on agent_mosquito."iAMId" = task_mosquito_pool_result."iAMId" LEFT JOIN result on result."iResultId" = task_mosquito_pool_result."iResultId" '.$whereQuery;
       $data = $sqlObj->GetAll($sql_qry);

        return $data;
    }
    
}
?>