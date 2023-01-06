<?php
//error_reporting(1);
include_once($function_path."image.inc.php");
include_once($controller_path . "fieldmap.inc.php");

$field_map_json_path = $field_map_json_url;

$mapObj = new Fieldmap();
if(isset($_POST) &&  !in_array($_POST['action'],array("getSerachSiteData","getSerachSRData","getSiteSRFilterData")) ){

    $action = $_POST['action'];

    $data = $mapObj->$action($_POST, $site_url); 
    //echo"<pre>";print_r($_POST);exit;
    switch($action){
        case 'getJson':
            if(count($data)>0){
                echo json_encode($data);
                die;
            } else {
                echo false;
                die;
            }
        break;
        case 'getData':
            $siteData = $data['sites'];
                //print_r($data); 
            $geoArr = array();
            $i = 0;
            foreach($siteData as $site){
                if(isset($site['point']) && $site['point'] != ''){
                    $point = str_replace("POINT(", '', $site['point']);
                    $point = str_replace(")", '', $point);
                    $pointArr = explode(" ", $point);
                    //print_r($latLngArr);

                    $geoArr['sites'][$site['premiseid']]['point'][] =  array(
                        'lat' => (float) $pointArr[1],
                        'lng' => (float) $pointArr[0]
                        );
                    $i++;

                    $geoArr['sites'][$site['premiseid']]['premiseid'] = $site['premiseid'];
                    $vStatus = "";
                    if($site['iStatus'] == 1) { 
                        $vStatus = "On-Net";
                        if($site['stypeid'] == 1) { //Residential
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/home-green.png";
                        }else if($site['stypeid'] == 2) { //Business
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/business-green.png";
                        }else if($site['stypeid'] == 3) { //Government
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/government-green.png";
                        }else if($site['stypeid'] == 4) { //Other
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/other-green.png";
                        }else if($site['stypeid'] == 5) { //Utility
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/utility-green.png";
                        }else if($site['stypeid'] == 6) { //Cell Site   
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/tower-green.png";
                        }else {
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/green_icon.png";
                        }
                    }
                    else if($site['iStatus'] == 2) {
                        $vStatus = "Near-Net";
                        if($site['stypeid'] == 1) { //Residential
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/home-orange.png";
                        }else if($site['stypeid'] == 2) { //Business
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/business-orange.png";
                        }else if($site['stypeid'] == 3) { //Government
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/government-orange.png";
                        }else if($site['stypeid'] == 4) { //Other
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/other-orange.png";
                        }else if($site['stypeid'] == 5) { //Utility
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/utility-orange.png";
                        }else if($site['stypeid'] == 6) { //Cell Site   
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/tower-orange.png";
                        }else {
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/orange_icon.png";
                        }
                    }else { 
                        $vStatus = "Off-Net";
                        if($site['stypeid'] == 1) { //Residential
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/home-red.png";
                        }else if($site['stypeid'] == 2) { //Business
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/business-red.png";
                        }else if($site['stypeid'] == 3) { //Government
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/government-red.png";
                        }else if($site['stypeid'] == 4) { //Other
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/other-red.png";
                        }else if($site['stypeid'] == 5) { //Utility
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/utility-red.png";
                        }else if($site['stypeid'] == 6) { //Cell Site   
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/tower-red.png";
                        }else {
                            $geoArr['sites'][$site['premiseid']]['icon'] = $site_url."images/red_icon.png";
                        }
                    }
                    //echo "<pre>";print_r($site['sattributeid']);exit;
                    
                    $geoArr['sites'][$site['premiseid']]['iStatus'] = $site['iStatus'];
                    $geoArr['sites'][$site['premiseid']]['vStatus'] = $vStatus;
                    $geoArr['sites'][$site['premiseid']]['cityid'] = $site['iCityId'];
                    $geoArr['sites'][$site['premiseid']]['zoneid'] = $site['iZoneId'];
                    $geoArr['sites'][$site['premiseid']]['zipcode'] = $site['iZipcode'];
                    $geoArr['sites'][$site['premiseid']]['networkid'] = $site['iNetworkId'];
                    $geoArr['sites'][$site['premiseid']]['stypeid'] = $site['stypeid'];
                    $geoArr['sites'][$site['premiseid']]['sstypeid'] = $site['sstypeid'];
                    $geoArr['sites'][$site['premiseid']]['sattributeid'] = explode(",", $site['sattributeid']) ;
                } 
            }

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
                        $geoArr['polyZone'][$zone['iZoneId']][] = array(
                            'lat' => (float) $latLngArr[1],
                            'lng' => (float) $latLngArr[0]
                            );
                        $i++;
                    }
                }
                //print_r($geoArr);
            }
          
            $fp = fopen($field_map_json_path.'premise-data.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;
        break;
        case 'getnetworkLayerData':
            $networklayerData = $data['networklayer'];
            $geoArr = array();
            foreach($networklayerData as $data){
                if($data['vFile'] != "" && file_exists($network_path.$data['vFile'])){
                    $geoArr['networklayer'][$data['iNetworkId']]['file_url'] = $network_url.$data['vFile'];;
                }
                $geoArr['networklayer'][$data['iNetworkId']]['vName'] =  $data['vName'];
            }
            $fp = fopen($field_map_json_path.'networkLayer.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";die;
        break;
        case 'getZoneLayerData':
            $zonelayerData = $data['zonelayer'];
            $geoArr = array();
            foreach($zonelayerData as $data){
                if($data['vFile'] != "" && file_exists($zone_path.$data['vFile'])){
                    $geoArr['zonelayer'][$data['iZoneId']]['file_url'] = $zone_url.$data['vFile'];;
                }
                $geoArr['zonelayer'][$data['iZoneId']]['vZoneName'] =  $data['vZoneName'];
            }
            $fp = fopen($field_map_json_path.'zoneLayer.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";die;
        break;
        case 'getCustomLayerData':
            $custlayerData = $data['custlayer'];
            //print_r($data['custlayer']); 
            $geoArr = array();
            $i = 0;
            foreach($custlayerData as $data){
                if($data['vFile'] != ""){
                    $filepath = $custom_layer_path;
                    if(file_exists($filepath.$data['vFile'])){
                        $file_url = $custom_layer_url.$data['vFile'];
                       
                       
                        $geoArr['customlayer'][$data['iCLId']]['file_url'] = $file_url;
                    }
                }
                $geoArr['customlayer'][$data['iCLId']]['vName'] =  $data['vName'];
            }
            //echo json_encode($geoArr);die;

            $fp = fopen($field_map_json_path.'customlayer.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;
        break;
        case 'getFiberInquiryData':
            $siteData = $data['sites'];
            $geoArr = array();
            //print_r($siteData);
            foreach($siteData as $site){
                if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
                    $vLatitude = $site['vLatitude'];
                    $vLongitude = $site['vLongitude'];
                    $geoArr['sites'][$site['iFiberInquiryId']]['point'][] =  array(
                        'lat' => (float) $vLatitude,
                        'lng' => (float) $vLongitude
                        );

                    $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/black_icon.png";

                    $vFirstName = ($site['vFirstName'] ? $site['vFirstName'] : '');
                    $vLastName = ($site['vLastName'] ? $site['vLastName'] : '');
                    $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
                    $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
                    $vCity = ($site['vCity'] ? $site['vCity'] : '');
                    $vState = ($site['vState'] ? $site['vState'] : '');

                    $vStatus = '';
                    if($site['iStatus'] == 1){
                        $vStatus = 'Draft';
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_red.png";
                    }else if($site['iStatus'] == 2){
                        $vStatus = 'Assigned';
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_black.png";
                    }else if($site['iStatus'] == 3){
                        $vStatus = 'Review';
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_yellow.png";
                    }else if($site['iStatus'] == 4){
                        $vStatus = 'Complete';
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_green.png";
                    }
                    

                    $geoArr['sites'][$site['iFiberInquiryId']]['vName'] = $vFirstName. ' '.$vLastName;
                    $geoArr['sites'][$site['iFiberInquiryId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['cityid'] = $site['iCityId'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['stateid'] = $site['iStateId'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['countyid'] = $site['iCountyId'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['countyid'] = $site['iCountyId'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['zipcode'] = $site['iZipcode'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['zoneid'] = $site['iZoneId'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['vZoneName'] = $site['vZoneName'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['networkid'] = $site['iNetworkId'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['vNetwork'] = $site['vNetwork'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['vEngagement'] = $site['vEngagement'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['vPremiseSubType'] = $site['vPremiseSubType'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['vPremiseName'] = $site['vPremiseName'] ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['vStatus'] = $vStatus;

                }
            }
            // echo json_encode($geoArr); die;
            $fp = fopen($field_map_json_path.'fiberInquiry-data.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;
        break;
        case 'getServiceOrderData':
            $siteData = $data['sites'];
            $geoArr = array();
            //print_r($siteData);
            foreach($siteData as $site){
                if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
                    $vLatitude = $site['vLatitude'];
                    $vLongitude = $site['vLongitude'];
                    $geoArr['sites'][$site['iServiceOrderId']]['point'][] =  array(
                        'lat' => (float) $vLatitude,
                        'lng' => (float) $vLongitude
                    );
                    
                    $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
                    $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
                    $vCity = ($site['vCity'] ? $site['vCity'] : '');
                    $vState = ($site['vState'] ? $site['vState'] : '');

                    $vStatus = '';
                    if($site['iSStatus'] == 1){
                        $vStatus = 'Active';
                        $geoArr['sites'][$site['iServiceOrderId']]['icon'] = $site_url."images/wrench-green.png";
                    }else if($site['iSStatus'] == 2){
                        $vStatus = 'Suspended';
                        $geoArr['sites'][$site['iServiceOrderId']]['icon'] = $site_url."images/wrench-red.png";
                    }else if($site['iSStatus'] == 3){
                        $vStatus = 'Trouble';
                        $geoArr['sites'][$site['iServiceOrderId']]['icon'] = $site_url."images/wrench-orange.png";
                    }else if($site['iSStatus'] == 4){
                        $vStatus = 'Disconnected';
                        $geoArr['sites'][$site['iServiceOrderId']]['icon'] = $site_url."images/wrench-black.png";
                    }else {
                        $vStatus = 'Pending';
                        $geoArr['sites'][$site['iServiceOrderId']]['icon'] = $site_url."images/wrench-yellow.png";
                    }
                    
                    $geoArr['sites'][$site['iServiceOrderId']]['vMasterMSA'] = $site['vMasterMSA'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vServiceOrder'] = $site['vServiceOrder'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vSalesRepName'] = $site['vSalesRepName'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vSalesRepEmail'] = $site['vSalesRepEmail'];
                    $geoArr['sites'][$site['iServiceOrderId']]['premiseid'] = $site['iPremiseId'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vPremiseName'] = $site['vPremiseName'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState;
                    $geoArr['sites'][$site['iServiceOrderId']]['cityid'] = $site['iCityId'];
                    $geoArr['sites'][$site['iServiceOrderId']]['stateid'] = $site['iStateId'];
                    $geoArr['sites'][$site['iServiceOrderId']]['countyid'] = $site['iCountyId'];
                    $geoArr['sites'][$site['iServiceOrderId']]['zipcode'] = $site['iZipcode'];
                    $geoArr['sites'][$site['iServiceOrderId']]['zoneid'] = $site['iZoneId'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vZoneName'] = $site['vZoneName'];
                    $geoArr['sites'][$site['iServiceOrderId']]['networkid'] = $site['iNetworkId'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vNetwork'] = $site['vNetwork'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vPremiseType'] = $site['vPremiseType'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vCompanyName'] = $site['vCompanyName'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vConnectionTypeName'] = $site['vConnectionTypeName'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vServiceType1'] = $site['vServiceType1'];
                    $geoArr['sites'][$site['iServiceOrderId']]['vStatus'] = $vStatus;
                }
            }
            // echo json_encode($geoArr); die;
            $fp = fopen($field_map_json_path.'serviceorder-data.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;
        break;
        case 'getWorkOrderData':
            $siteData = $data['sites'];
            $geoArr = array();
            //print_r($siteData);
            foreach($siteData as $site){
                if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
                    $vLatitude = $site['vLatitude'];
                    $vLongitude = $site['vLongitude'];
                    $geoArr['sites'][$site['iWOId']]['point'][] =  array(
                        'lat' => (float) $vLatitude,
                        'lng' => (float) $vLongitude
                    );

                    $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
                    $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
                    $vCity = ($site['vCity'] ? $site['vCity'] : '');
                    $vState = ($site['vState'] ? $site['vState'] : '');

                    if($site['iWOSId'] == 1){
                        $geoArr['sites'][$site['iWOId']]['icon'] = $site_url."images/user-wrench-orange.png";
                    }else if($site['iWOSId'] == 2){
                        $geoArr['sites'][$site['iWOId']]['icon'] = $site_url."images/user-wrench-green.png";
                    }else if($site['iWOSId'] == 3){
                        $geoArr['sites'][$site['iWOId']]['icon'] = $site_url."images/user-wrench-red.png";
                    }else if($site['iWOSId'] == 4){
                        $geoArr['sites'][$site['iWOId']]['icon'] = $site_url."images/user-wrench-yellow.png";
                    }
                        
                    $vServiceOrder = 'ID#'.$site['iServiceOrderId'].' | '.$site['vMasterMSA'].' | '.$site['vServiceOrder'];

                    $geoArr['sites'][$site['iWOId']]['premiseid'] = $site['iPremiseId'];
                    $geoArr['sites'][$site['iWOId']]['vPremiseName'] = $site['vPremiseName'];
                    $geoArr['sites'][$site['iWOId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState;
                    $geoArr['sites'][$site['iWOId']]['cityid'] = $site['iCityId'];
                    $geoArr['sites'][$site['iWOId']]['stateid'] = $site['iStateId'];
                    $geoArr['sites'][$site['iWOId']]['countyid'] = $site['iCountyId'];
                    $geoArr['sites'][$site['iWOId']]['zipcode'] = $site['iZipcode'];
                    $geoArr['sites'][$site['iWOId']]['zoneid'] = $site['iZoneId'];
                    $geoArr['sites'][$site['iWOId']]['vZoneName'] = $site['vZoneName'];
                    $geoArr['sites'][$site['iWOId']]['networkid'] = $site['iNetworkId'];
                    $geoArr['sites'][$site['iWOId']]['vNetwork'] = $site['vNetwork'];
                    $geoArr['sites'][$site['iWOId']]['vPremiseType'] = $site['vPremiseType'];
                    $geoArr['sites'][$site['iWOId']]['vServiceOrder'] = $vServiceOrder;
                    $geoArr['sites'][$site['iWOId']]['vWOProject'] = $site['vWOProject'];
                    $geoArr['sites'][$site['iWOId']]['vType'] = $site['vType'];
                    $geoArr['sites'][$site['iWOId']]['vRequestor'] = $site['vRequestor'];
                    $geoArr['sites'][$site['iWOId']]['vAssignedTo'] = $site['vAssignedTo'];
                    
                    $geoArr['sites'][$site['iWOId']]['vStatus'] = $site['vStatus'];
                }
            }
            // echo json_encode($geoArr); die;
            $fp = fopen($field_map_json_path.'workorder-data.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;
        break;
        case 'getPremiseCircuitData':
            $siteData = $data['sites'];
            $geoArr = array();
            //print_r($siteData);
            foreach($siteData as $site){
                if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
                    $vLatitude = $site['vLatitude'];
                    $vLongitude = $site['vLongitude'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['point'][] =  array(
                        'lat' => (float) $vLatitude,
                        'lng' => (float) $vLongitude
                    );

                    $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
                    $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
                    $vCity = ($site['vCity'] ? $site['vCity'] : '');
                    $vState = ($site['vState'] ? $site['vState'] : '');

                    //1:Created | 2:In Progress | 3:Delayed | 4:Connected | 5:Active | 6:Suspended | 7:Trouble | 8:Disconnected
                    $vStatus = '';
                    if($site['iStatus'] == 1){
                        $vStatus = 'Created';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/circuit-yellow.png";
                    }else if($site['iStatus'] == 2){
                        $vStatus = 'In Progress';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/circuit-yellow.png";
                    }else if($site['iStatus'] == 3){
                        $vStatus = 'Delayed';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/circuit-yellow.png";
                    }else if($site['iStatus'] == 4){
                        $vStatus = 'Connected';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/circuit-green.png";
                    }else if($site['iStatus'] == 5){
                        $vStatus = 'Active';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/circuit-green.png";
                    }else if($site['iStatus'] == 6){
                        $vStatus = 'Suspended';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/circuit-red.png";
                    }else if($site['iStatus'] == 7){
                        $vStatus = 'Trouble';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/circuit-orange.png";
                    }else if($site['iStatus'] == 8){
                        $vStatus = 'Disconnected';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/circuit-black.png";
                    }
                        
                    $vWorkOrder = 'ID#'.$site['iWOId'].' ('.$site['vWorkOrderType'].')';

                    $geoArr['sites'][$site['iPremiseCircuitId']]['premisecircuitid'] = $site['iPremiseCircuitId'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['premiseid'] = $site['iPremiseId'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['vPremiseName'] = $site['vPremiseName'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState;
                    $geoArr['sites'][$site['iPremiseCircuitId']]['cityid'] = $site['iCityId'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['stateid'] = $site['iStateId'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['countyid'] = $site['iCountyId'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['zipcode'] = $site['iZipcode'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['zoneid'] = $site['iZoneId'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['vZoneName'] = $site['vZoneName'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['networkid'] = $site['iNetworkId'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['vNetwork'] = $site['vNetwork'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['vPremiseType'] = $site['vPremiseType'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['vWorkOrder'] = $vWorkOrder;
                    $geoArr['sites'][$site['iPremiseCircuitId']]['circuitid'] = $site['iCircuitId'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['vCircuitName'] = $site['vCircuitName'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['connectiontypeid'] = $site['iConnectionTypeId'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['vConnectionTypeName'] = $site['vConnectionTypeName'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['iStatus'] = $site['iStatus'];
                    $geoArr['sites'][$site['iPremiseCircuitId']]['vStatus'] = $vStatus;
                }
            }
            // echo json_encode($geoArr); die;
            $fp = fopen($field_map_json_path.'premiseCircuit-data.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;
        break;
        default:
            echo json_encode($data);
            die;
        break;
    }
}else if(isset($_POST) && ( $_POST['action'] == "getSerachSiteData")) {
    $action = $_POST['action'];
    $siteData = array();

    $data = $mapObj->$action($_POST); 
	//echo "111";print_r($data);exit();
    $siteData_arr = $data['siteData'];
    //print_r($data); 
           
    $i = 0;
    foreach($siteData_arr as $site){
        if(isset($site['polygon']) && $site['polygon'] != ''){

            $polygon = str_replace("POLYGON((", '', $site['polygon']);
            $polygon = str_replace("))", '', $polygon);

            $polyArr = explode(",", $polygon);

            foreach($polyArr as $latlng){
                $latLngArr = explode(" ", $latlng);

                $siteData[$site['premiseid']]['polygon'][] = array(
                    'lat' => (float) $latLngArr[1],
                    'lng' => (float) $latLngArr[0]
                    );
                $i++;
            }
            if(isset($site['polycenter']) && $site['polycenter'] != ''){
                $center = str_replace("POINT(", '', $site['polycenter']);
                $polyCenter = str_replace(")", '', $center);
                $polyCenterArr = explode(" ", $polyCenter);

                $siteData[$site['premiseid']]['polyCenter'] = array(
                    'lat' => (float) $polyCenterArr[1],
                    'lng' => (float) $polyCenterArr[0]
                    );
            }
            $siteData[$site['premiseid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['premiseid']]['cityid'] = $site['iCityId'];
            $siteData[$site['premiseid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['premiseid']]['stypeid'] = $site['stypeid'];
        } else if(isset($site['point']) && $site['point'] != ''){

            $point = str_replace("POINT(", '', $site['point']);
            $point = str_replace(")", '', $point);

            $pointArr = explode(" ", $point);

                //print_r($latLngArr);

            $siteData[$site['premiseid']]['point'][] =  array(
                'lat' => (float) $pointArr[1],
                'lng' => (float) $pointArr[0]
                );
            $i++;
            $siteData[$site['premiseid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['premiseid']]['cityid'] = $site['iCityId'];
            $siteData[$site['premiseid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['premiseid']]['stypeid'] = $site['stypeid'];
        } else if(isset($site['poly_line']) && $site['poly_line'] != ''){
            $polyLine = str_replace("LINESTRING(", '', $site['poly_line']);
            $polyLine = str_replace(")", '', $polyLine);

                //print_r($polygon);

            $polyLineArr = explode(",", $polyLine);

                //print_r($polyArr);

            foreach($polyLineArr as $latlng){
                $polyLineLatLngArr = explode(" ", $latlng);

                    //print_r($latLngArr);
                $siteData[$site['premiseid']]['poly_line'][] = array(
                    'lat' => (float) $polyLineLatLngArr[1],
                    'lng' => (float) $polyLineLatLngArr[0]
                    );
                $i++;
            }

            $siteData[$site['premiseid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['premiseid']]['cityid'] = $site['iCityId'];
            $siteData[$site['premiseid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['premiseid']]['stypeid'] = $site['stypeid'];
        } 
    }
   // print_r($siteData);exit();
    echo json_encode($siteData);
    die;

}else if(isset($_POST) && $_POST['action'] == "getSerachSRData") {
    $action = $_POST['action'];

    $data = $mapObj->$action($_POST); 
    
    $srData = array();
    $srData_arr = $data['srData'];

        foreach($srData_arr as $site){
           
            if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
                $vLatitude = $site['vLatitude'];
                $vLongitude = $site['vLongitude'];
                $srData[$site['iSRId']]['point'][] =  array(
                    'lat' => (float) $vLatitude,
                    'lng' => (float) $vLongitude
                    );

                $srData[$site['iSRId']]['icon'] = $site_url."images/black_icon.png";

                $vFirstName = ($site['vFirstName'] ? $site['vFirstName'] : '');
                $vLastName = ($site['vLastName'] ? $site['vLastName'] : '');
                $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
                $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
                $vCity = ($site['vCity'] ? $site['vCity'] : '');
                $vState = ($site['vState'] ? $site['vState'] : '');

                $vRequestType = '';
                if($site['bMosquitoService'] == 't' && $site['bCarcassService'] != 't') {
                    $vRequestType = 'Mosquito Inspection/Treatment';
                }else if($site['bMosquitoService'] != 't' && $site['bCarcassService'] == 't') {
                    $vRequestType = 'Carcass Removal';
                }else if($site['bMosquitoService'] == 't' && $site['bCarcassService'] == 't') {
                    $vRequestType = 'Mosquito Inspection/Treatment | Carcass Removal';
                }

                $vStatus = '';
                if($site['iStatus'] == 1){
                    $vStatus = 'Draft';
                    $srData[$site['iSRId']]['icon'] = $site_url."images/sr_red.png";
                }else if($site['iStatus'] == 2){
                    $vStatus = 'Assigned';
                    $srData[$site['iSRId']]['icon'] = $site_url."images/sr_yellow.png";
                }else if($site['iStatus'] == 3){
                    $vStatus = 'Review';
                    $srData[$site['iSRId']]['icon'] = $site_url."images/sr_green.png";
                }else if($site['iStatus'] == 4){
                    $vStatus = 'Complete';
                    $srData[$site['iSRId']]['icon'] = $site_url."images/sr_black.png";
                }
                

                $vAssignTo = ($site['vAssignTo'] ? $site['vAssignTo'] :'');
                $srData[$site['iSRId']]['vName'] = $vFirstName. ' '.$vLastName;
                $srData[$site['iSRId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState ;
                $srData[$site['iSRId']]['vRequestType'] = $vRequestType;
                $srData[$site['iSRId']]['vStatus'] = $vStatus;
                $srData[$site['iSRId']]['vAssignTo'] = $vAssignTo;

            }
        }

        echo json_encode($srData);
        die;

}else if(isset($_POST) && $_POST['action'] == "getSiteSRFilterData"){
    $action = $_POST['action'];

    $data = $mapObj->$action($_POST); 

    $siteData = array();
    $srData = array();
    $res =array();

    $siteData_arr = $data['siteData'];
    //print_r($data); 

    $srData_arr = $data['srData'];
           
    $i = 0;
    foreach($siteData_arr as $site){
        if(isset($site['polygon']) && $site['polygon'] != ''){

            $polygon = str_replace("POLYGON((", '', $site['polygon']);
            $polygon = str_replace("))", '', $polygon);

            $polyArr = explode(",", $polygon);

            foreach($polyArr as $latlng){
                $latLngArr = explode(" ", $latlng);

                $siteData[$site['premiseid']]['polygon'][] = array(
                    'lat' => (float) $latLngArr[1],
                    'lng' => (float) $latLngArr[0]
                    );
                $i++;
            }
            if(isset($site['polycenter']) && $site['polycenter'] != ''){
                $center = str_replace("POINT(", '', $site['polycenter']);
                $polyCenter = str_replace(")", '', $center);
                $polyCenterArr = explode(" ", $polyCenter);

                $siteData[$site['premiseid']]['polyCenter'] = array(
                    'lat' => (float) $polyCenterArr[1],
                    'lng' => (float) $polyCenterArr[0]
                    );
            }
            $siteData[$site['premiseid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['premiseid']]['cityid'] = $site['iCityId'];
            $siteData[$site['premiseid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['premiseid']]['stypeid'] = $site['stypeid'];
        } else if(isset($site['point']) && $site['point'] != ''){

            $point = str_replace("POINT(", '', $site['point']);
            $point = str_replace(")", '', $point);

            $pointArr = explode(" ", $point);

                //print_r($latLngArr);

            $siteData[$site['premiseid']]['point'][] =  array(
                'lat' => (float) $pointArr[1],
                'lng' => (float) $pointArr[0]
                );
            $i++;
            $siteData[$site['premiseid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['premiseid']]['cityid'] = $site['iCityId'];
            $siteData[$site['premiseid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['premiseid']]['stypeid'] = $site['stypeid'];
        } else if(isset($site['poly_line']) && $site['poly_line'] != ''){
            $polyLine = str_replace("LINESTRING(", '', $site['poly_line']);
            $polyLine = str_replace(")", '', $polyLine);

                //print_r($polygon);

            $polyLineArr = explode(",", $polyLine);

                //print_r($polyArr);

            foreach($polyLineArr as $latlng){
                $polyLineLatLngArr = explode(" ", $latlng);

                    //print_r($latLngArr);
                $siteData[$site['premiseid']]['poly_line'][] = array(
                    'lat' => (float) $polyLineLatLngArr[1],
                    'lng' => (float) $polyLineLatLngArr[0]
                    );
                $i++;
            }

            $siteData[$site['premiseid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['premiseid']]['cityid'] = $site['iCityId'];
            $siteData[$site['premiseid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['premiseid']]['stypeid'] = $site['stypeid'];
        } 
    }

    $res['site'] = $siteData;

    /***************************/
    foreach($srData_arr as $site){
       
        if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
            $vLatitude = $site['vLatitude'];
            $vLongitude = $site['vLongitude'];
            $srData[$site['iFiberInquiryId']]['point'][] =  array(
                'lat' => (float) $vLatitude,
                'lng' => (float) $vLongitude
                );

            $srData[$site['iFiberInquiryId']]['icon'] = $site_url."images/black_icon.png";

            $vFirstName = ($site['vFirstName'] ? $site['vFirstName'] : '');
            $vLastName = ($site['vLastName'] ? $site['vLastName'] : '');
            $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
            $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
            $vCity = ($site['vCity'] ? $site['vCity'] : '');
            $vState = ($site['vState'] ? $site['vState'] : '');

            $vStatus = '';
            if($site['iStatus'] == 1){
                $vStatus = 'Draft';
                $srData[$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_red.png";
            }else if($site['iStatus'] == 2){
                $vStatus = 'Assigned';
                $srData[$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_yellow.png";
            }else if($site['iStatus'] == 3){
                $vStatus = 'Review';
                $srData[$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_green.png";
            }else if($site['iStatus'] == 4){
                $vStatus = 'Complete';
                $srData[$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_black.png";
            }

            $srData[$site['iFiberInquiryId']]['vName'] = $vFirstName. ' '.$vLastName;
            $srData[$site['iFiberInquiryId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState ;
            $srData[$site['iFiberInquiryId']]['vRequestType'] = $vRequestType;
            $srData[$site['iFiberInquiryId']]['vStatus'] = $vStatus;
        }
    }

    $res['sr'] = $srData;

    echo json_encode($res);die;
}

