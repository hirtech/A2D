<?php
include_once($function_path."image.inc.php");
include_once($controller_path . "fieldmap.inc.php");

$mapObj = new Fieldmap();
if(isset($_POST) &&  !in_array($_POST['action'],array("getSerachSiteData", "getSerachFiberInquiryData", "getSerachServiceOrderData", "getSerachWorkOrderData", "getSerachTroubleTicketData", "getSerachMaintenanceTicketData", "getSerachAwarenessTaskData", "getSerachEquipmentData", "getSerachPremiseCircuitData", "getPremiseFiberInquiryFilterData")) ){

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
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/question_red.png";
                    }else if($site['iStatus'] == 2){
                        $vStatus = 'Assigned';
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/question_black.png";
                    }else if($site['iStatus'] == 3){
                        $vStatus = 'Review';
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/question_yellow.png";
                    }else if($site['iStatus'] == 4){
                        $vStatus = 'Complete';
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/question_green.png";
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
                        $geoArr['sites'][$site['iServiceOrderId']]['icon'] = $site_url."images/shopping_cart_green.png";
                    }else if($site['iSStatus'] == 2){
                        $vStatus = 'Suspended';
                        $geoArr['sites'][$site['iServiceOrderId']]['icon'] = $site_url."images/shopping_cart_red.png";
                    }else if($site['iSStatus'] == 3){
                        $vStatus = 'Trouble';
                        $geoArr['sites'][$site['iServiceOrderId']]['icon'] = $site_url."images/shopping_cart_orange.png";
                    }else if($site['iSStatus'] == 4){
                        $vStatus = 'Disconnected';
                        $geoArr['sites'][$site['iServiceOrderId']]['icon'] = $site_url."images/shopping_cart_black.png";
                    }else {
                        $vStatus = 'Pending';
                        $geoArr['sites'][$site['iServiceOrderId']]['icon'] = $site_url."images/shopping_cart_yellow.png";
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
                        $geoArr['sites'][$site['iWOId']]['icon'] = $site_url."images/user_helmet_orange.png";
                    }else if($site['iWOSId'] == 2){
                        $geoArr['sites'][$site['iWOId']]['icon'] = $site_url."images/user_helmet_green.png";
                    }else if($site['iWOSId'] == 3){
                        $geoArr['sites'][$site['iWOId']]['icon'] = $site_url."images/user_helmet_red.png";
                    }else if($site['iWOSId'] == 4){
                        $geoArr['sites'][$site['iWOId']]['icon'] = $site_url."images/user_helmet_yellow.png";
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
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_yellow.png";
                    }else if($site['iStatus'] == 2){
                        $vStatus = 'In Progress';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_yellow.png";
                    }else if($site['iStatus'] == 3){
                        $vStatus = 'Delayed';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_yellow.png";
                    }else if($site['iStatus'] == 4){
                        $vStatus = 'Connected';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_green.png";
                    }else if($site['iStatus'] == 5){
                        $vStatus = 'Active';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_green.png";
                    }else if($site['iStatus'] == 6){
                        $vStatus = 'Suspended';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_red.png";
                    }else if($site['iStatus'] == 7){
                        $vStatus = 'Trouble';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_orange.png";
                    }else if($site['iStatus'] == 8){
                        $vStatus = 'Disconnected';
                        $geoArr['sites'][$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_black.png";
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
        if(isset($site['point']) && $site['point'] != ''){
            $point = str_replace("POINT(", '', $site['point']);
            $point = str_replace(")", '', $point);
            $pointArr = explode(" ", $point);
            //print_r($latLngArr);
            $siteData[$site['premiseid']]['point'][] =  array(
                'lat' => (float) $pointArr[1],
                'lng' => (float) $pointArr[0]
                );
            $i++;

            $siteData[$site['premiseid']]['premiseid'] = $site['premiseid'];
            $vStatus = "";
            if($site['iStatus'] == 1) { 
                $vStatus = "On-Net";
                if($site['stypeid'] == 1) { //Residential
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/home-green.png";
                }else if($site['stypeid'] == 2) { //Business
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/business-green.png";
                }else if($site['stypeid'] == 3) { //Government
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/government-green.png";
                }else if($site['stypeid'] == 4) { //Other
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/other-green.png";
                }else if($site['stypeid'] == 5) { //Utility
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/utility-green.png";
                }else if($site['stypeid'] == 6) { //Cell Site   
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/tower-green.png";
                }else {
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/green_icon.png";
                }
            }
            else if($site['iStatus'] == 2) {
                $vStatus = "Near-Net";
                if($site['stypeid'] == 1) { //Residential
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/home-orange.png";
                }else if($site['stypeid'] == 2) { //Business
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/business-orange.png";
                }else if($site['stypeid'] == 3) { //Government
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/government-orange.png";
                }else if($site['stypeid'] == 4) { //Other
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/other-orange.png";
                }else if($site['stypeid'] == 5) { //Utility
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/utility-orange.png";
                }else if($site['stypeid'] == 6) { //Cell Site   
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/tower-orange.png";
                }else {
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/orange_icon.png";
                }
            }else { 
                $vStatus = "Off-Net";
                if($site['stypeid'] == 1) { //Residential
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/home-red.png";
                }else if($site['stypeid'] == 2) { //Business
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/business-red.png";
                }else if($site['stypeid'] == 3) { //Government
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/government-red.png";
                }else if($site['stypeid'] == 4) { //Other
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/other-red.png";
                }else if($site['stypeid'] == 5) { //Utility
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/utility-red.png";
                }else if($site['stypeid'] == 6) { //Cell Site   
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/tower-red.png";
                }else {
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/red_icon.png";
                }
            }
            $siteData[$site['premiseid']]['iStatus'] = $site['iStatus'];
            $siteData[$site['premiseid']]['vStatus'] = $vStatus;
            $siteData[$site['premiseid']]['cityid'] = $site['iCityId'];
            $siteData[$site['premiseid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['premiseid']]['zipcode'] = $site['iZipcode'];
            $siteData[$site['premiseid']]['networkid'] = $site['iNetworkId'];
            $siteData[$site['premiseid']]['stypeid'] = $site['stypeid'];
            $siteData[$site['premiseid']]['sstypeid'] = $site['sstypeid'];
            $siteData[$site['premiseid']]['sattributeid'] = explode(",", $site['sattributeid']) ;
        }
    }
    // print_r($siteData);exit();
    echo json_encode($siteData);
    die;
}else if(isset($_POST) && $_POST['action'] == "getSerachFiberInquiryData") {
    $action = $_POST['action'];
    $data = $mapObj->$action($_POST); 
    $fInquiryData = array();
    $fInquiryData_arr = $data['fInquiryData'];
    foreach($fInquiryData_arr as $site){
        if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
            $vLatitude = $site['vLatitude'];
            $vLongitude = $site['vLongitude'];
            $fInquiryData[$site['iFiberInquiryId']]['point'][] =  array(
                'lat' => (float) $vLatitude,
                'lng' => (float) $vLongitude
                );

            $fInquiryData[$site['iFiberInquiryId']]['icon'] = $site_url."images/black_icon.png";

            $vFirstName = ($site['vFirstName'] ? $site['vFirstName'] : '');
            $vLastName = ($site['vLastName'] ? $site['vLastName'] : '');
            $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
            $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
            $vCity = ($site['vCity'] ? $site['vCity'] : '');
            $vState = ($site['vState'] ? $site['vState'] : '');

            $vStatus = '';
            if($site['iStatus'] == 1){
                $vStatus = 'Draft';
                $fInquiryData[$site['iFiberInquiryId']]['icon'] = $site_url."images/question_red.png";
            }else if($site['iStatus'] == 2){
                $vStatus = 'Assigned';
                $fInquiryData[$site['iFiberInquiryId']]['icon'] = $site_url."images/question_black.png";
            }else if($site['iStatus'] == 3){
                $vStatus = 'Review';
                $fInquiryData[$site['iFiberInquiryId']]['icon'] = $site_url."images/question_yellow.png";
            }else if($site['iStatus'] == 4){
                $vStatus = 'Complete';
                $fInquiryData[$site['iFiberInquiryId']]['icon'] = $site_url."images/question_green.png";
            }
            
            $fInquiryData[$site['iFiberInquiryId']]['vName'] = $vFirstName. ' '.$vLastName;
            $fInquiryData[$site['iFiberInquiryId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState ;
            $fInquiryData[$site['iFiberInquiryId']]['fiberInquiryId'] = $site['iFiberInquiryId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['cityid'] = $site['iCityId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['stateid'] = $site['iStateId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['countyid'] = $site['iCountyId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['countyid'] = $site['iCountyId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['zipcode'] = $site['iZipcode'] ;
            $fInquiryData[$site['iFiberInquiryId']]['zoneid'] = $site['iZoneId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vZoneName'] = $site['vZoneName'] ;
            $fInquiryData[$site['iFiberInquiryId']]['networkid'] = $site['iNetworkId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vNetwork'] = $site['vNetwork'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vEngagement'] = $site['vEngagement'] ;
            $fInquiryData[$site['iFiberInquiryId']]['premiseid'] = $site['iMatchingPremiseId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vPremiseSubType'] = $site['vPremiseSubType'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vPremiseName'] = $site['vPremiseName'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vStatus'] = $vStatus;
        }
    }
    echo json_encode($fInquiryData);
    die;
}else if(isset($_POST) && $_POST['action'] == "getSerachServiceOrderData") {
    $action = $_POST['action'];

    $data = $mapObj->$action($_POST); 
    
    $serviceOrderData = array();
    $serviceOrderData_arr = $data['serviceOrderData'];
    foreach($serviceOrderData_arr as $site){
        if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
            $vLatitude = $site['vLatitude'];
            $vLongitude = $site['vLongitude'];
            $serviceOrderData[$site['iServiceOrderId']]['point'][] =  array(
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
                $serviceOrderData[$site['iServiceOrderId']]['icon'] = $site_url."images/shopping_cart_green.png";
            }else if($site['iSStatus'] == 2){
                $vStatus = 'Suspended';
                $serviceOrderData[$site['iServiceOrderId']]['icon'] = $site_url."images/shopping_cart_red.png";
            }else if($site['iSStatus'] == 3){
                $vStatus = 'Trouble';
                $serviceOrderData[$site['iServiceOrderId']]['icon'] = $site_url."images/shopping_cart_orange.png";
            }else if($site['iSStatus'] == 4){
                $vStatus = 'Disconnected';
                $serviceOrderData[$site['iServiceOrderId']]['icon'] = $site_url."images/shopping_cart_black.png";
            }else {
                $vStatus = 'Pending';
                $serviceOrderData[$site['iServiceOrderId']]['icon'] = $site_url."images/shopping_cart_yellow.png";
            }
            
            $serviceOrderData[$site['iServiceOrderId']]['vMasterMSA'] = $site['vMasterMSA'];
            $serviceOrderData[$site['iServiceOrderId']]['vServiceOrder'] = $site['vServiceOrder'];
            $serviceOrderData[$site['iServiceOrderId']]['vSalesRepName'] = $site['vSalesRepName'];
            $serviceOrderData[$site['iServiceOrderId']]['vSalesRepEmail'] = $site['vSalesRepEmail'];
            $serviceOrderData[$site['iServiceOrderId']]['premiseid'] = $site['iPremiseId'];
            $serviceOrderData[$site['iServiceOrderId']]['vPremiseName'] = $site['vPremiseName'];
            $serviceOrderData[$site['iServiceOrderId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState;
            $serviceOrderData[$site['iServiceOrderId']]['cityid'] = $site['iCityId'];
            $serviceOrderData[$site['iServiceOrderId']]['stateid'] = $site['iStateId'];
            $serviceOrderData[$site['iServiceOrderId']]['countyid'] = $site['iCountyId'];
            $serviceOrderData[$site['iServiceOrderId']]['zipcode'] = $site['iZipcode'];
            $serviceOrderData[$site['iServiceOrderId']]['zoneid'] = $site['iZoneId'];
            $serviceOrderData[$site['iServiceOrderId']]['vZoneName'] = $site['vZoneName'];
            $serviceOrderData[$site['iServiceOrderId']]['networkid'] = $site['iNetworkId'];
            $serviceOrderData[$site['iServiceOrderId']]['vNetwork'] = $site['vNetwork'];
            $serviceOrderData[$site['iServiceOrderId']]['vPremiseType'] = $site['vPremiseType'];
            $serviceOrderData[$site['iServiceOrderId']]['vCompanyName'] = $site['vCompanyName'];
            $serviceOrderData[$site['iServiceOrderId']]['vConnectionTypeName'] = $site['vConnectionTypeName'];
            $serviceOrderData[$site['iServiceOrderId']]['vServiceType1'] = $site['vServiceType1'];
            $serviceOrderData[$site['iServiceOrderId']]['vStatus'] = $vStatus;
        }
    }
    echo json_encode($serviceOrderData);
    die; 
}else if(isset($_POST) && $_POST['action'] == "getSerachWorkOrderData") {
    $action = $_POST['action'];

    $data = $mapObj->$action($_POST); 
    
    $workOrderData = array();
    $workOrderData_arr = $data['workOrderData'];
    foreach($workOrderData_arr as $site){
        if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
            $vLatitude = $site['vLatitude'];
            $vLongitude = $site['vLongitude'];
            $workOrderData[$site['iWOId']]['point'][] =  array(
                'lat' => (float) $vLatitude,
                'lng' => (float) $vLongitude
            );

            $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
            $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
            $vCity = ($site['vCity'] ? $site['vCity'] : '');
            $vState = ($site['vState'] ? $site['vState'] : '');

            if($site['iWOSId'] == 1){
                $workOrderData[$site['iWOId']]['icon'] = $site_url."images/user_helmet_orange.png";
            }else if($site['iWOSId'] == 2){
                $workOrderData[$site['iWOId']]['icon'] = $site_url."images/user_helmet_green.png";
            }else if($site['iWOSId'] == 3){
                $workOrderData[$site['iWOId']]['icon'] = $site_url."images/user_helmet_red.png";
            }else if($site['iWOSId'] == 4){
                $workOrderData[$site['iWOId']]['icon'] = $site_url."images/user_helmet_yellow.png";
            }
                
            $vServiceOrder = 'ID#'.$site['iServiceOrderId'].' | '.$site['vMasterMSA'].' | '.$site['vServiceOrder'];

            $workOrderData[$site['iWOId']]['premiseid'] = $site['iPremiseId'];
            $workOrderData[$site['iWOId']]['vPremiseName'] = $site['vPremiseName'];
            $workOrderData[$site['iWOId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState;
            $workOrderData[$site['iWOId']]['cityid'] = $site['iCityId'];
            $workOrderData[$site['iWOId']]['stateid'] = $site['iStateId'];
            $workOrderData[$site['iWOId']]['countyid'] = $site['iCountyId'];
            $workOrderData[$site['iWOId']]['zipcode'] = $site['iZipcode'];
            $workOrderData[$site['iWOId']]['zoneid'] = $site['iZoneId'];
            $workOrderData[$site['iWOId']]['vZoneName'] = $site['vZoneName'];
            $workOrderData[$site['iWOId']]['networkid'] = $site['iNetworkId'];
            $workOrderData[$site['iWOId']]['vNetwork'] = $site['vNetwork'];
            $workOrderData[$site['iWOId']]['vPremiseType'] = $site['vPremiseType'];
            $workOrderData[$site['iWOId']]['vServiceOrder'] = $vServiceOrder;
            $workOrderData[$site['iWOId']]['vWOProject'] = $site['vWOProject'];
            $workOrderData[$site['iWOId']]['vType'] = $site['vType'];
            $workOrderData[$site['iWOId']]['vRequestor'] = $site['vRequestor'];
            $workOrderData[$site['iWOId']]['vAssignedTo'] = $site['vAssignedTo'];
            
            $workOrderData[$site['iWOId']]['vStatus'] = $site['vStatus'];
        }
    }
    echo json_encode($workOrderData);
    die; 
}else if(isset($_POST) && $_POST['action'] == "getSerachTroubleTicketData"){
    $action = $_POST['action'];
    $data = $mapObj->$action($_POST); 
    
    $troubleTicketData = array();
    $troubleTicketData_arr = $data['troubleTicketData'];
    foreach($troubleTicketData_arr as $site){
        if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
            $vLatitude = $site['vLatitude'];
            $vLongitude = $site['vLongitude'];
            $troubleTicketData[$site['iPremiseId']]['point'][] =  array(
                'lat' => (float) $vLatitude,
                'lng' => (float) $vLongitude
            );
            
            $troubleTicketData[$site['iPremiseId']]['iTroubleTicketId'] = $site['iTroubleTicketId'];
            $troubleTicketData[$site['iPremiseId']]['iSeverity'] = $site['iSeverity'];
            $troubleTicketData[$site['iPremiseId']]['iStatus'] = $site['iStatus'];
            $troubleTicketData[$site['iPremiseId']]['vServiceOrder'] = $site['vServiceOrder'];
            $troubleTicketData[$site['iPremiseId']]['iPremiseId'] = $site['iPremiseId'];
            $troubleTicketData[$site['iPremiseId']]['vPremiseName'] = $site['vPremiseName'];
            $troubleTicketData[$site['iPremiseId']]['vPremiseType'] = $site['vPremiseType'];
            $troubleTicketData[$site['iPremiseId']]['dTroubleStartDate'] = $site['dTroubleStartDate'];
            $troubleTicketData[$site['iPremiseId']]['vAddress'] = $site['vAddress'];
            $troubleTicketData[$site['iPremiseId']]['icon'] = $site['vIcon'];
        }
    }
    echo json_encode($troubleTicketData);
    die;
}else if(isset($_POST) && $_POST['action'] == "getSerachMaintenanceTicketData"){
    $action = $_POST['action'];
    $data = $mapObj->$action($_POST); 
    
    $maintenanceTicketData = array();
    $maintenanceTicketData_arr = $data['maintenanceTicketData'];
    foreach($maintenanceTicketData_arr as $site){
        if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
            $vLatitude = $site['vLatitude'];
            $vLongitude = $site['vLongitude'];
            $maintenanceTicketData[$site['iPremiseId']]['point'][] =  array(
                'lat' => (float) $vLatitude,
                'lng' => (float) $vLongitude
            );
            
            $maintenanceTicketData[$site['iPremiseId']]['iMaintenanceTicketId'] = $site['iMaintenanceTicketId'];
            $maintenanceTicketData[$site['iPremiseId']]['iSeverity'] = $site['iSeverity'];
            $maintenanceTicketData[$site['iPremiseId']]['iStatus'] = $site['iStatus'];
            $maintenanceTicketData[$site['iPremiseId']]['vServiceOrder'] = $site['vServiceOrder'];
            $maintenanceTicketData[$site['iPremiseId']]['iPremiseId'] = $site['iPremiseId'];
            $maintenanceTicketData[$site['iPremiseId']]['vPremiseName'] = $site['vPremiseName'];
            $maintenanceTicketData[$site['iPremiseId']]['vPremiseType'] = $site['vPremiseType'];
            $maintenanceTicketData[$site['iPremiseId']]['dMaintenanceStartDate'] = $site['dMaintenanceStartDate'];
            $maintenanceTicketData[$site['iPremiseId']]['vAddress'] = $site['vAddress'];
            $maintenanceTicketData[$site['iPremiseId']]['icon'] = $site['vIcon'];
        }
    }
    echo json_encode($maintenanceTicketData);
    die;
}else if(isset($_POST) && $_POST['action'] == "getSerachAwarenessTaskData"){
    $action = $_POST['action'];
    $data = $mapObj->$action($_POST); 
    
    $awarenessTaskData = array();
    $awarenessTaskData_arr = $data['awarenessTaskData'];
    foreach($awarenessTaskData_arr as $site){
        if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
            $vLatitude = $site['vLatitude'];
            $vLongitude = $site['vLongitude'];
            $awarenessTaskData[$site['iAId']]['point'][] =  array(
                'lat' => (float) $vLatitude,
                'lng' => (float) $vLongitude
            );
            
            $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
            $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
            $vCity = ($site['vCity'] ? $site['vCity'] : '');
            $vState = ($site['vState'] ? $site['vState'] : '');

            $awarenessTaskData[$site['iAId']]['icon'] = $site_url."images/awareness_task.png";

            $vFiberInquiry = '';
            if($site['iFiberInquiryId'] != ''){
                $vFiberInquiry = "#".$site['iFiberInquiryId']." (".$site['vContactName'].")";
            }

            if($site['dStartDate'] != ''){
                $site['dStartTime'] = date("H:i", strtotime($site['dStartDate']));
            }
            else{
                $site['dStartTime'] = date("H:i", time());
            }

            if($site['dEndDate'] != ''){
                $site['dEndTime'] = date("H:i", strtotime($site['dEndDate']));
            }
            else{
                $site['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s') . " +10 minutes"));
            }
            $hidden_arr = array(
                "iAId"          => $site['iAId'],
                "vSiteName"     => $site['iPremiseId']." (".$site['vPremiseName']."; ".$site['vPremiseType'].")",
                "iPremiseId"    => $site['iPremiseId'],
                "dDate"         => $site['dDate'],
                "dStartDate"    => $site['dStartDate'],
                "dStartTime"    => $site['dStartTime'],
                "dEndDate"      => $site['dEndDate'],
                "dEndTime"      => $site['dEndTime'],
                "iEngagementId" => $site['iEngagementId'],
                "tNotes"        => $site['tNotes'],
                "srdisplay"     => $vFiberInquiry,
                "iSRId"         => $site['iFiberInquiryId'],
                "iTechnicianId" => $site['iTechnicianId'],
            );
            $awarenessTaskData[$site['iAId']]['iPremiseId'] = $site['iPremiseId'];
            $awarenessTaskData[$site['iAId']]['vPremiseName'] = $site['vPremiseName'];
            $awarenessTaskData[$site['iAId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState;
            $awarenessTaskData[$site['iAId']]['cityid'] = $site['iCityId'];
            $awarenessTaskData[$site['iAId']]['stateid'] = $site['iStateId'];
            $awarenessTaskData[$site['iAId']]['countyid'] = $site['iCountyId'];
            $awarenessTaskData[$site['iAId']]['zipcode'] = $site['iZipcode'];
            $awarenessTaskData[$site['iAId']]['zoneid'] = $site['iZoneId'];
            $awarenessTaskData[$site['iAId']]['vZoneName'] = $site['vZoneName'];
            $awarenessTaskData[$site['iAId']]['networkid'] = $site['iNetworkId'];
            $awarenessTaskData[$site['iAId']]['vNetwork'] = $site['vNetwork'];
            $awarenessTaskData[$site['iAId']]['vPremiseType'] = $site['vPremiseType'];
            $awarenessTaskData[$site['iAId']]['vFiberInquiry'] = $vFiberInquiry;
            $awarenessTaskData[$site['iAId']]['vEngagement'] = $site['vEngagement'];
            $awarenessTaskData[$site['iAId']]['tNotes'] = $site['tNotes'];
            $awarenessTaskData[$site['iAId']]['dStartTime'] = ($site['dStartDate']);
            $awarenessTaskData[$site['iAId']]['dEndTime'] = ($site['dEndDate']);
            $awarenessTaskData[$site['iAId']]['dDate'] = date_getDateTimeDDMMYYYY($site['dDate']);
            $awarenessTaskData[$site['iAId']]['vTechnicianName'] = $site['vTechnicianName'];
            $awarenessTaskData[$site['iAId']]['hidden_arr'] = $hidden_arr;
            
        }
    }
    echo json_encode($awarenessTaskData);
    die;
}else if(isset($_POST) && $_POST['action'] == "getSerachEquipmentData") {
    $action = $_POST['action'];
    $data = $mapObj->$action($_POST); 
    
    $equipmentData = array();
    $equipmentData_arr = $data['equipmentData'];
    foreach($equipmentData_arr as $site){
        if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
            $vLatitude = $site['vLatitude'];
            $vLongitude = $site['vLongitude'];
            $equipmentData[$site['iEquipmentId']]['point'][] =  array(
                'lat' => (float) $vLatitude,
                'lng' => (float) $vLongitude
            );

            $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
            $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
            $vCity = ($site['vCity'] ? $site['vCity'] : '');
            $vState = ($site['vState'] ? $site['vState'] : '');

            $vPremiseCircuitData = '';
            if($site['iPremiseCircuitId'] > 0)
                $vPremiseCircuitData = "Premise Circuit ID #".$site['iPremiseCircuitId']." (".$site['vCircuitName'].")";
            $equipmentData[$site['iEquipmentId']]['iPremiseId'] = $site['iPremiseId'];
            $equipmentData[$site['iEquipmentId']]['vPremiseName'] = $site['vPremiseName'];
            $equipmentData[$site['iEquipmentId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState;
            $equipmentData[$site['iEquipmentId']]['cityid'] = $site['iCityId'];
            $equipmentData[$site['iEquipmentId']]['stateid'] = $site['iStateId'];
            $equipmentData[$site['iEquipmentId']]['countyid'] = $site['iCountyId'];
            $equipmentData[$site['iEquipmentId']]['zipcode'] = $site['iZipcode'];
            $equipmentData[$site['iEquipmentId']]['zoneid'] = $site['iZoneId'];
            $equipmentData[$site['iEquipmentId']]['vZoneName'] = $site['vZoneName'];
            $equipmentData[$site['iEquipmentId']]['networkid'] = $site['iNetworkId'];
            $equipmentData[$site['iEquipmentId']]['vNetwork'] = $site['vNetwork'];
            $equipmentData[$site['iEquipmentId']]['vPremiseType'] = $site['vPremiseType'];
            $equipmentData[$site['iEquipmentId']]['vModelName'] = $site['vModelName'];
            $equipmentData[$site['iEquipmentId']]['vSerialNumber'] = $site['vSerialNumber'];
            $equipmentData[$site['iEquipmentId']]['vMACAddress'] = $site['vMACAddress'];
            $equipmentData[$site['iEquipmentId']]['vSize'] = $site['vSize'];
            $equipmentData[$site['iEquipmentId']]['vWeight'] = $site['vWeight'];
            $equipmentData[$site['iEquipmentId']]['vMaterial'] = $site['vMaterial'];
            $equipmentData[$site['iEquipmentId']]['vPower'] = $site['vPower'];
            $equipmentData[$site['iEquipmentId']]['dInstallByDate'] = date_display_report_date($site['dInstallByDate']);
            $equipmentData[$site['iEquipmentId']]['dInstalledDate'] = date_display_report_date($site['dInstalledDate']);
            $equipmentData[$site['iEquipmentId']]['vPurchaseCost'] = $site['vPurchaseCost'];
            $equipmentData[$site['iEquipmentId']]['dPurchaseDate'] = date_display_report_date($site['dPurchaseDate']);
            $equipmentData[$site['iEquipmentId']]['dWarrantyExpiration'] = $site['dWarrantyExpiration'];
            $equipmentData[$site['iEquipmentId']]['vWarrantyCost'] = $site['vWarrantyCost'];
            $equipmentData[$site['iEquipmentId']]['vInstallType'] = $site['vInstallType'];
            $equipmentData[$site['iEquipmentId']]['vLinkType'] = $site['vLinkType'];
            $equipmentData[$site['iEquipmentId']]['dProvisionDate'] = date_display_report_date($site['dProvisionDate']);
            $equipmentData[$site['iEquipmentId']]['vOperationalStatus'] = $site['vOperationalStatus'];
            $equipmentData[$site['iEquipmentId']]['vPremiseCircuitData'] = $vPremiseCircuitData;
            $equipmentData[$site['iEquipmentId']]['icon'] = $site_url."images/equipment.png";

        }
    }
    echo json_encode($equipmentData);
    die;
}else if(isset($_POST) && $_POST['action'] == "getSerachPremiseCircuitData"){
    $action = $_POST['action'];
    $data = $mapObj->$action($_POST); 
    
    $premiseCircuitData = array();
    $premiseCircuitData_arr = $data['premiseCircuitData'];
    foreach($premiseCircuitData_arr as $site){
        if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
            $vLatitude = $site['vLatitude'];
            $vLongitude = $site['vLongitude'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['point'][] =  array(
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
                $premiseCircuitData[$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_yellow.png";
            }else if($site['iStatus'] == 2){
                $vStatus = 'In Progress';
                $premiseCircuitData[$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_yellow.png";
            }else if($site['iStatus'] == 3){
                $vStatus = 'Delayed';
                $premiseCircuitData[$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_yellow.png";
            }else if($site['iStatus'] == 4){
                $vStatus = 'Connected';
                $premiseCircuitData[$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_green.png";
            }else if($site['iStatus'] == 5){
                $vStatus = 'Active';
                $premiseCircuitData[$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_green.png";
            }else if($site['iStatus'] == 6){
                $vStatus = 'Suspended';
                $premiseCircuitData[$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_red.png";
            }else if($site['iStatus'] == 7){
                $vStatus = 'Trouble';
                $premiseCircuitData[$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_orange.png";
            }else if($site['iStatus'] == 8){
                $vStatus = 'Disconnected';
                $premiseCircuitData[$site['iPremiseCircuitId']]['icon'] = $site_url."images/computer_classic_black.png";
            }
                
            $vWorkOrder = 'ID#'.$site['iWOId'].' ('.$site['vWorkOrderType'].')';

            $premiseCircuitData[$site['iPremiseCircuitId']]['premisecircuitid'] = $site['iPremiseCircuitId'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['premiseid'] = $site['iPremiseId'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['vPremiseName'] = $site['vPremiseName'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState;
            $premiseCircuitData[$site['iPremiseCircuitId']]['cityid'] = $site['iCityId'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['stateid'] = $site['iStateId'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['countyid'] = $site['iCountyId'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['zipcode'] = $site['iZipcode'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['zoneid'] = $site['iZoneId'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['vZoneName'] = $site['vZoneName'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['networkid'] = $site['iNetworkId'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['vNetwork'] = $site['vNetwork'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['vPremiseType'] = $site['vPremiseType'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['vWorkOrder'] = $vWorkOrder;
            $premiseCircuitData[$site['iPremiseCircuitId']]['circuitid'] = $site['iCircuitId'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['vCircuitName'] = $site['vCircuitName'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['connectiontypeid'] = $site['iConnectionTypeId'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['vConnectionTypeName'] = $site['vConnectionTypeName'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['iStatus'] = $site['iStatus'];
            $premiseCircuitData[$site['iPremiseCircuitId']]['vStatus'] = $vStatus;
            
        }
    }
    echo json_encode($premiseCircuitData);
    die;
}else if(isset($_POST) && $_POST['action'] == "getPremiseFiberInquiryFilterData"){
    $action = $_POST['action'];

    $data = $mapObj->$action($_POST); 

    $siteData = array();
    $fInquiryData = array();
    $res =array();

    $siteData_arr = $data['siteData'];
    //print_r($data); 

    $fiberinquiryData_arr = $data['fiberinquiryData'];
           
    $i = 0;
    foreach($siteData_arr as $site){
        if(isset($site['point']) && $site['point'] != ''){
            $point = str_replace("POINT(", '', $site['point']);
            $point = str_replace(")", '', $point);
            $pointArr = explode(" ", $point);
            //print_r($latLngArr);
            $siteData[$site['premiseid']]['point'][] =  array(
                'lat' => (float) $pointArr[1],
                'lng' => (float) $pointArr[0]
                );
            $i++;

            $siteData[$site['premiseid']]['premiseid'] = $site['premiseid'];
            $vStatus = "";
            if($site['iStatus'] == 1) { 
                $vStatus = "On-Net";
                if($site['stypeid'] == 1) { //Residential
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/home-green.png";
                }else if($site['stypeid'] == 2) { //Business
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/business-green.png";
                }else if($site['stypeid'] == 3) { //Government
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/government-green.png";
                }else if($site['stypeid'] == 4) { //Other
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/other-green.png";
                }else if($site['stypeid'] == 5) { //Utility
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/utility-green.png";
                }else if($site['stypeid'] == 6) { //Cell Site   
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/tower-green.png";
                }else {
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/green_icon.png";
                }
            }
            else if($site['iStatus'] == 2) {
                $vStatus = "Near-Net";
                if($site['stypeid'] == 1) { //Residential
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/home-orange.png";
                }else if($site['stypeid'] == 2) { //Business
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/business-orange.png";
                }else if($site['stypeid'] == 3) { //Government
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/government-orange.png";
                }else if($site['stypeid'] == 4) { //Other
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/other-orange.png";
                }else if($site['stypeid'] == 5) { //Utility
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/utility-orange.png";
                }else if($site['stypeid'] == 6) { //Cell Site   
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/tower-orange.png";
                }else {
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/orange_icon.png";
                }
            }else { 
                $vStatus = "Off-Net";
                if($site['stypeid'] == 1) { //Residential
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/home-red.png";
                }else if($site['stypeid'] == 2) { //Business
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/business-red.png";
                }else if($site['stypeid'] == 3) { //Government
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/government-red.png";
                }else if($site['stypeid'] == 4) { //Other
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/other-red.png";
                }else if($site['stypeid'] == 5) { //Utility
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/utility-red.png";
                }else if($site['stypeid'] == 6) { //Cell Site   
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/tower-red.png";
                }else {
                    $siteData[$site['premiseid']]['icon'] = $site_url."images/red_icon.png";
                }
            }
            $siteData[$site['premiseid']]['iStatus'] = $site['iStatus'];
            $siteData[$site['premiseid']]['vStatus'] = $vStatus;
            $siteData[$site['premiseid']]['cityid'] = $site['iCityId'];
            $siteData[$site['premiseid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['premiseid']]['zipcode'] = $site['iZipcode'];
            $siteData[$site['premiseid']]['networkid'] = $site['iNetworkId'];
            $siteData[$site['premiseid']]['stypeid'] = $site['stypeid'];
            $siteData[$site['premiseid']]['sstypeid'] = $site['sstypeid'];
            $siteData[$site['premiseid']]['sattributeid'] = explode(",", $site['sattributeid']) ;
        }
    }

    $res['site'] = $siteData;

    /***************************/
    foreach($fiberinquiryData_arr as $site){
        if(isset($site['vLatitude']) && $site['vLongitude'] != ''){
            $vLatitude = $site['vLatitude'];
            $vLongitude = $site['vLongitude'];
            $fInquiryData[$site['iFiberInquiryId']]['point'][] =  array(
                'lat' => (float) $vLatitude,
                'lng' => (float) $vLongitude
                );

            $fInquiryData[$site['iFiberInquiryId']]['icon'] = $site_url."images/black_icon.png";

            $vFirstName = ($site['vFirstName'] ? $site['vFirstName'] : '');
            $vLastName = ($site['vLastName'] ? $site['vLastName'] : '');
            $vAddress1 = ($site['vAddress1'] ? $site['vAddress1'] : '');
            $vStreet = ($site['vStreet'] ? $site['vStreet'] : '');
            $vCity = ($site['vCity'] ? $site['vCity'] : '');
            $vState = ($site['vState'] ? $site['vState'] : '');

            $vStatus = '';
            if($site['iStatus'] == 1){
                $vStatus = 'Draft';
                $fInquiryData[$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_red.png";
            }else if($site['iStatus'] == 2){
                $vStatus = 'Assigned';
                $fInquiryData[$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_black.png";
            }else if($site['iStatus'] == 3){
                $vStatus = 'Review';
                $fInquiryData[$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_yellow.png";
            }else if($site['iStatus'] == 4){
                $vStatus = 'Complete';
                $fInquiryData[$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_green.png";
            }
            
            $fInquiryData[$site['iFiberInquiryId']]['vName'] = $vFirstName. ' '.$vLastName;
            $fInquiryData[$site['iFiberInquiryId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState ;
            $fInquiryData[$site['iFiberInquiryId']]['fiberInquiryId'] = $site['iFiberInquiryId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['cityid'] = $site['iCityId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['stateid'] = $site['iStateId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['countyid'] = $site['iCountyId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['countyid'] = $site['iCountyId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['zipcode'] = $site['iZipcode'] ;
            $fInquiryData[$site['iFiberInquiryId']]['zoneid'] = $site['iZoneId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vZoneName'] = $site['vZoneName'] ;
            $fInquiryData[$site['iFiberInquiryId']]['networkid'] = $site['iNetworkId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vNetwork'] = $site['vNetwork'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vEngagement'] = $site['vEngagement'] ;
            $fInquiryData[$site['iFiberInquiryId']]['premiseid'] = $site['iMatchingPremiseId'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vPremiseSubType'] = $site['vPremiseSubType'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vPremiseName'] = $site['vPremiseName'] ;
            $fInquiryData[$site['iFiberInquiryId']]['vStatus'] = $vStatus;
        }
    }

    $res['fInquiry'] = $fInquiryData;

    echo json_encode($res);die;
}

