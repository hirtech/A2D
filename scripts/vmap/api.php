<?php
//error_reporting(1);
include_once($function_path."image.inc.php");
include_once($controller_path . "fieldmap.inc.php");

$field_map_json_path = $field_map_json_url;

$mapObj = new Fieldmap();
if(isset($_POST) &&  !in_array($_POST['action'],array("getSerachSiteData","getSerachSRData","getSiteSRFilterData","getNearBySite")) ){

    $action = $_POST['action'];

    $data = $mapObj->$action($_POST, $site_url); 
    //print_r($_POST);
    // exit;
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
                if(isset($site['polygon']) && $site['polygon'] != ''){
                        //print_r($site);

                    $polygon = str_replace("POLYGON((", '', $site['polygon']);
                    $polygon = str_replace("))", '', $polygon);

                        //print_r($polygon);

                    $polyArr = explode(",", $polygon);

                        //print_r($polyArr);

                    foreach($polyArr as $latlng){
                        $latLngArr = explode(" ", $latlng);

                            //print_r($latLngArr);
                        $geoArr['sites'][$site['siteid']]['polygon'][] = array(
                            'lat' => (float) $latLngArr[1],
                            'lng' => (float) $latLngArr[0]
                            );
                        $i++;
                    }
                    if(isset($site['polycenter']) && $site['polycenter'] != ''){
                        $center = str_replace("POINT(", '', $site['polycenter']);
                        $polyCenter = str_replace(")", '', $center);
                        $polyCenterArr = explode(" ", $polyCenter);
                            //print_r($polyCenterArr); die;
                        $geoArr['sites'][$site['siteid']]['polyCenter'] = array(
                            'lat' => (float) $polyCenterArr[1],
                            'lng' => (float) $polyCenterArr[0]
                            );
                    }
                    $geoArr['sites'][$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
                    $geoArr['sites'][$site['siteid']]['cityid'] = $site['iCityId'];
                    $geoArr['sites'][$site['siteid']]['zoneid'] = $site['iZoneId'];
                    $geoArr['sites'][$site['siteid']]['stypeid'] = $site['stypeid'];
                    $geoArr['sites'][$site['siteid']]['sstypeid'] = $site['sstypeid'];

                    $geoArr['sites'][$site['siteid']]['sattributeid'] = $site['sattributeid'];
                } else if(isset($site['point']) && $site['point'] != ''){

                    $point = str_replace("POINT(", '', $site['point']);
                    $point = str_replace(")", '', $point);

                    $pointArr = explode(" ", $point);

                        //print_r($latLngArr);

                    $geoArr['sites'][$site['siteid']]['point'][] =  array(
                        'lat' => (float) $pointArr[1],
                        'lng' => (float) $pointArr[0]
                        );
                    $i++;
                    $geoArr['sites'][$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
                    $geoArr['sites'][$site['siteid']]['cityid'] = $site['iCityId'];
                    $geoArr['sites'][$site['siteid']]['zoneid'] = $site['iZoneId'];
                    $geoArr['sites'][$site['siteid']]['stypeid'] = $site['stypeid'];
                    $geoArr['sites'][$site['siteid']]['sstypeid'] = $site['sstypeid'];
                    $geoArr['sites'][$site['siteid']]['sattributeid'] =explode(",", $site['sattributeid']) ;
                } else if(isset($site['poly_line']) && $site['poly_line'] != ''){
                    $polyLine = str_replace("LINESTRING(", '', $site['poly_line']);
                    $polyLine = str_replace(")", '', $polyLine);

                        //print_r($polygon);

                    $polyLineArr = explode(",", $polyLine);

                        //print_r($polyArr);

                    foreach($polyLineArr as $latlng){
                        $polyLineLatLngArr = explode(" ", $latlng);

                            //print_r($latLngArr);
                        $geoArr['sites'][$site['siteid']]['poly_line'][] = array(
                            'lat' => (float) $polyLineLatLngArr[1],
                            'lng' => (float) $polyLineLatLngArr[0]
                            );
                        $i++;
                    }

                    $geoArr['sites'][$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
                    $geoArr['sites'][$site['siteid']]['cityid'] = $site['iCityId'];
                    $geoArr['sites'][$site['siteid']]['zoneid'] = $site['iZoneId'];
                    $geoArr['sites'][$site['siteid']]['stypeid'] = $site['stypeid'];
                    $geoArr['sites'][$site['siteid']]['sstypeid'] = $site['sstypeid'];
                    $geoArr['sites'][$site['siteid']]['sattributeid'] = $site['sattributeid'];
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
          
            $fp = fopen($field_map_json_path.'site-type.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;
        break;
        case 'getSrData':
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
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_yellow.png";
                    }else if($site['iStatus'] == 3){
                        $vStatus = 'Review';
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_green.png";
                    }else if($site['iStatus'] == 4){
                        $vStatus = 'Complete';
                        $geoArr['sites'][$site['iFiberInquiryId']]['icon'] = $site_url."images/sr_black.png";
                    }
                    

                    $geoArr['sites'][$site['iFiberInquiryId']]['vName'] = $vFirstName. ' '.$vLastName;
                    $geoArr['sites'][$site['iFiberInquiryId']]['vAddress'] = $vAddress1.' '.$vStreet.' '.$vCity.' '.$vState ;
                    $geoArr['sites'][$site['iFiberInquiryId']]['vStatus'] = $vStatus;

                }
            }
            // echo json_encode($geoArr); die;
        
            $fp = fopen($field_map_json_path.'sr.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;
        break;
        case 'getlandingrateData':
            $siteData = $data['sites'];
                //print_r($data); 
            $geoArr = array();
            $i = 0;
            foreach($siteData as $site){
                if(isset($site['polygon']) && $site['polygon'] != ''){
                        //print_r($site);

                    $polygon = str_replace("POLYGON((", '', $site['polygon']);
                    $polygon = str_replace("))", '', $polygon);

                    $polyArr = explode(",", $polygon);

                    foreach($polyArr as $latlng){
                        $latLngArr = explode(" ", $latlng);

                        $geoArr['sites'][$site['siteid']]['polygon'][] = array(
                            'lat' => (float) $latLngArr[1],
                            'lng' => (float) $latLngArr[0]
                            );
                        $i++;
                    }
                    if(isset($site['polycenter']) && $site['polycenter'] != ''){
                        $center = str_replace("POINT(", '', $site['polycenter']);
                        $polyCenter = str_replace(")", '', $center);
                        $polyCenterArr = explode(" ", $polyCenter);
                            //print_r($polyCenterArr); die;
                        $geoArr['sites'][$site['siteid']]['polyCenter'] = array(
                            'lat' => (float) $polyCenterArr[1],
                            'lng' => (float) $polyCenterArr[0]
                            );
                    }
                    $geoArr['sites'][$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
                } else if(isset($site['point']) && $site['point'] != ''){

                    $point = str_replace("POINT(", '', $site['point']);
                    $point = str_replace(")", '', $point);

                    $pointArr = explode(" ", $point);

                        //print_r($latLngArr);

                    $geoArr['sites'][$site['siteid']]['point'][] =  array(
                        'lat' => (float) $pointArr[1],
                        'lng' => (float) $pointArr[0]
                        );
                    $i++;
                    $geoArr['sites'][$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
                    $geoArr['sites'][$site['siteid']]['count'] = $site['count'];
                } else if(isset($site['poly_line']) && $site['poly_line'] != ''){
                    $polyLine = str_replace("LINESTRING(", '', $site['poly_line']);
                    $polyLine = str_replace(")", '', $polyLine);

                    $polyLineArr = explode(",", $polyLine);

                    foreach($polyLineArr as $latlng){
                        $polyLineLatLngArr = explode(" ", $latlng);

                        $geoArr['sites'][$site['siteid']]['poly_line'][] = array(
                            'lat' => (float) $polyLineLatLngArr[1],
                            'lng' => (float) $polyLineLatLngArr[0]
                            );
                        $i++;
                    }

                    $geoArr['sites'][$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
                } 

            }
            //echo json_encode($geoArr);die;
            $fp = fopen($field_map_json_path.'landing_rate.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;
        break;
        case 'getlarvalData':
            $siteData = $data['sites'];
            // print_r($data);die(); 
            $geoArr = array();
            $i = 0;
            foreach($siteData as $site){
                if(isset($site['polygon']) && $site['polygon'] != ''){
                        //print_r($site);

                    $polygon = str_replace("POLYGON((", '', $site['polygon']);
                    $polygon = str_replace("))", '', $polygon);

                    $polyArr = explode(",", $polygon);

                    foreach($polyArr as $latlng){
                        $latLngArr = explode(" ", $latlng);

                        $geoArr['sites'][$site['siteid']]['polygon'][] = array(
                            'lat' => (float) $latLngArr[1],
                            'lng' => (float) $latLngArr[0]
                            );
                        $i++;
                    }
                    if(isset($site['polycenter']) && $site['polycenter'] != ''){
                        $center = str_replace("POINT(", '', $site['polycenter']);
                        $polyCenter = str_replace(")", '', $center);
                        $polyCenterArr = explode(" ", $polyCenter);
                            //print_r($polyCenterArr); die;
                        $geoArr['sites'][$site['siteid']]['polyCenter'] = array(
                            'lat' => (float) $polyCenterArr[1],
                            'lng' => (float) $polyCenterArr[0]
                            );
                    }
                    $geoArr['sites'][$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
                } else if(isset($site['point']) && $site['point'] != ''){

                    $point = str_replace("POINT(", '', $site['point']);
                    $point = str_replace(")", '', $point);

                    $pointArr = explode(" ", $point);

                        //print_r($latLngArr);

                    $geoArr['sites'][$site['siteid']]['point'][] =  array(
                        'lat' => (float) $pointArr[1],
                        'lng' => (float) $pointArr[0]
                        );
                    $i++;
                    $geoArr['sites'][$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
                    $geoArr['sites'][$site['siteid']]['count'] = $site['count'];
                } else if(isset($site['poly_line']) && $site['poly_line'] != ''){
                    $polyLine = str_replace("LINESTRING(", '', $site['poly_line']);
                    $polyLine = str_replace(")", '', $polyLine);

                    $polyLineArr = explode(",", $polyLine);

                    foreach($polyLineArr as $latlng){
                        $polyLineLatLngArr = explode(" ", $latlng);

                        $geoArr['sites'][$site['siteid']]['poly_line'][] = array(
                            'lat' => (float) $polyLineLatLngArr[1],
                            'lng' => (float) $polyLineLatLngArr[0]
                            );
                        $i++;
                    }

                    $geoArr['sites'][$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
                    
                } 

            }
            //echo json_encode($geoArr);die;
            $fp = fopen($field_map_json_path.'larval.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;

        break;
        case 'getpositiveData':
            $siteData = $data['sites'];
            //print_r($data['sites']); 
            $geoArr = array();
            $i = 0;
            foreach($siteData as $site){
                if(isset($site['polygon']) && $site['polygon'] != ''){
                        //print_r($site);

                    $polygon = str_replace("POLYGON((", '', $site['polygon']);
                    $polygon = str_replace("))", '', $polygon);

                    $polyArr = explode(",", $polygon);

                    foreach($polyArr as $latlng){
                        $latLngArr = explode(" ", $latlng);

                        $geoArr['sites'][$site['siteid']]['polygon'][] = array(
                            'lat' => (float) $latLngArr[1],
                            'lng' => (float) $latLngArr[0]
                            );
                        $i++;
                    }
                    if(isset($site['polycenter']) && $site['polycenter'] != ''){
                        $center = str_replace("POINT(", '', $site['polycenter']);
                        $polyCenter = str_replace(")", '', $center);
                        $polyCenterArr = explode(" ", $polyCenter);
                            //print_r($polyCenterArr); die;
                        $geoArr['sites'][$site['siteid']]['polyCenter'] = array(
                            'lat' => (float) $polyCenterArr[1],
                            'lng' => (float) $polyCenterArr[0]
                            );
                    }
                    $geoArr['sites'][$site['siteid']]['icon'] = $site_url."images/red-dot-icon.png";
                    $geoArr['sites'][$site['siteid']]['iTTId'] = $site['iTTId'];
                    $geoArr['sites'][$site['siteid']]['iTMPId'] = $site['iTMPId'];
                } else if(isset($site['point']) && $site['point'] != ''){

                    $point = str_replace("POINT(", '', $site['point']);
                    $point = str_replace(")", '', $point);

                    $pointArr = explode(" ", $point);

                        //print_r($latLngArr);

                    $geoArr['sites'][$site['siteid']]['point'][] =  array(
                        'lat' => (float) $pointArr[1],
                        'lng' => (float) $pointArr[0]
                        );
                    $i++;
                    $geoArr['sites'][$site['siteid']]['icon'] = $site_url."images/red-dot-icon.png";
                    $geoArr['sites'][$site['siteid']]['iTTId'] = $site['iTTId'];
                    $geoArr['sites'][$site['siteid']]['iTMPId'] = $site['iTMPId'];
                } else if(isset($site['poly_line']) && $site['poly_line'] != ''){
                    $polyLine = str_replace("LINESTRING(", '', $site['poly_line']);
                    $polyLine = str_replace(")", '', $polyLine);

                    $polyLineArr = explode(",", $polyLine);
                    $geoArr['sites'][$site['siteid']]['iTTId'] = $site['iTTId'];
                    $geoArr['sites'][$site['siteid']]['iTMPId'] = $site['iTMPId'];
                    foreach($polyLineArr as $latlng){
                        $polyLineLatLngArr = explode(" ", $latlng);

                        $geoArr['sites'][$site['siteid']]['poly_line'][] = array(
                            'lat' => (float) $polyLineLatLngArr[1],
                            'lng' => (float) $polyLineLatLngArr[0]
                            );
                        $i++;
                    }

                    $geoArr['sites'][$site['siteid']]['icon'] = $site_url."images/red-dot-icon.png";
                    $geoArr['sites'][$site['siteid']]['iTTId'] = $site['iTTId'];
                    $geoArr['sites'][$site['siteid']]['iTMPId'] = $site['iTMPId'];
                } 

            }
            $fp = fopen($field_map_json_path.'positive.json', 'w');
            fwrite($fp, json_encode($geoArr));
            fclose($fp);
            echo "done";
            die;
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
        default:
            echo json_encode($data);
            die;
        break;
    }
}else if(isset($_POST) && ( $_POST['action'] == "getSerachSiteData" || $_POST['action'] == "getNearBySite" )) {
    $action = $_POST['action'];
    $siteData = array();

    $data = $mapObj->$action($_POST); 
  //   echo "111";print_r($data);exit();
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

                $siteData[$site['siteid']]['polygon'][] = array(
                    'lat' => (float) $latLngArr[1],
                    'lng' => (float) $latLngArr[0]
                    );
                $i++;
            }
            if(isset($site['polycenter']) && $site['polycenter'] != ''){
                $center = str_replace("POINT(", '', $site['polycenter']);
                $polyCenter = str_replace(")", '', $center);
                $polyCenterArr = explode(" ", $polyCenter);

                $siteData[$site['siteid']]['polyCenter'] = array(
                    'lat' => (float) $polyCenterArr[1],
                    'lng' => (float) $polyCenterArr[0]
                    );
            }
            $siteData[$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['siteid']]['cityid'] = $site['iCityId'];
            $siteData[$site['siteid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['siteid']]['stypeid'] = $site['stypeid'];
        } else if(isset($site['point']) && $site['point'] != ''){

            $point = str_replace("POINT(", '', $site['point']);
            $point = str_replace(")", '', $point);

            $pointArr = explode(" ", $point);

                //print_r($latLngArr);

            $siteData[$site['siteid']]['point'][] =  array(
                'lat' => (float) $pointArr[1],
                'lng' => (float) $pointArr[0]
                );
            $i++;
            $siteData[$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['siteid']]['cityid'] = $site['iCityId'];
            $siteData[$site['siteid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['siteid']]['stypeid'] = $site['stypeid'];
        } else if(isset($site['poly_line']) && $site['poly_line'] != ''){
            $polyLine = str_replace("LINESTRING(", '', $site['poly_line']);
            $polyLine = str_replace(")", '', $polyLine);

                //print_r($polygon);

            $polyLineArr = explode(",", $polyLine);

                //print_r($polyArr);

            foreach($polyLineArr as $latlng){
                $polyLineLatLngArr = explode(" ", $latlng);

                    //print_r($latLngArr);
                $siteData[$site['siteid']]['poly_line'][] = array(
                    'lat' => (float) $polyLineLatLngArr[1],
                    'lng' => (float) $polyLineLatLngArr[0]
                    );
                $i++;
            }

            $siteData[$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['siteid']]['cityid'] = $site['iCityId'];
            $siteData[$site['siteid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['siteid']]['stypeid'] = $site['stypeid'];
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

                $siteData[$site['siteid']]['polygon'][] = array(
                    'lat' => (float) $latLngArr[1],
                    'lng' => (float) $latLngArr[0]
                    );
                $i++;
            }
            if(isset($site['polycenter']) && $site['polycenter'] != ''){
                $center = str_replace("POINT(", '', $site['polycenter']);
                $polyCenter = str_replace(")", '', $center);
                $polyCenterArr = explode(" ", $polyCenter);

                $siteData[$site['siteid']]['polyCenter'] = array(
                    'lat' => (float) $polyCenterArr[1],
                    'lng' => (float) $polyCenterArr[0]
                    );
            }
            $siteData[$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['siteid']]['cityid'] = $site['iCityId'];
            $siteData[$site['siteid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['siteid']]['stypeid'] = $site['stypeid'];
        } else if(isset($site['point']) && $site['point'] != ''){

            $point = str_replace("POINT(", '', $site['point']);
            $point = str_replace(")", '', $point);

            $pointArr = explode(" ", $point);

                //print_r($latLngArr);

            $siteData[$site['siteid']]['point'][] =  array(
                'lat' => (float) $pointArr[1],
                'lng' => (float) $pointArr[0]
                );
            $i++;
            $siteData[$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['siteid']]['cityid'] = $site['iCityId'];
            $siteData[$site['siteid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['siteid']]['stypeid'] = $site['stypeid'];
        } else if(isset($site['poly_line']) && $site['poly_line'] != ''){
            $polyLine = str_replace("LINESTRING(", '', $site['poly_line']);
            $polyLine = str_replace(")", '', $polyLine);

                //print_r($polygon);

            $polyLineArr = explode(",", $polyLine);

                //print_r($polyArr);

            foreach($polyLineArr as $latlng){
                $polyLineLatLngArr = explode(" ", $latlng);

                    //print_r($latLngArr);
                $siteData[$site['siteid']]['poly_line'][] = array(
                    'lat' => (float) $polyLineLatLngArr[1],
                    'lng' => (float) $polyLineLatLngArr[0]
                    );
                $i++;
            }

            $siteData[$site['siteid']]['icon'] = $mapObj->getSiteTypeIcon($site['stypeid']);
            $siteData[$site['siteid']]['cityid'] = $site['iCityId'];
            $siteData[$site['siteid']]['zoneid'] = $site['iZoneId'];
            $siteData[$site['siteid']]['stypeid'] = $site['stypeid'];
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

        echo json_encode($res);
        die;
}

