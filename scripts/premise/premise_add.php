<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");

# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise", 'List');
$access_group_var_delete = per_hasModuleAccess("Premise", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Premise", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Premise", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Premise", 'Edit', 'N');
# ----------- Access Rule Condition -----------


include_once($controller_path . "premise.inc.php");
include_once($controller_path ."premise_type.inc.php");
include_once($controller_path ."premise_sub_type.inc.php");
include_once($controller_path . "premise_attribute.inc.php");
include_once($controller_path."state.inc.php");
include_once($controller_path."zone.inc.php");
include_once($controller_path. "contact.inc.php");

$SiteObj = new Site();
$SiteTypeObj = new SiteType();
$SiteSubTypeObj = new SiteSubType();
$SiteAttribute = new SiteAttribute();
$StateObj = new State();
$ZoneObj = new Zone();
$ContactObj = new Contact();

$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
//include_once($site_path . "scripts/session_valid.php");
$rs_site = array();
$iSAttributeIdArr = array();
if($mode == "Update") {
    $iSiteId = $_REQUEST['iSiteId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "s.\"iSiteId\"='".gen_add_slash($iSiteId)."'";
    $join_fieds_arr[] = 'ST_AsText(s."vPolygonLatLong") as "vPolygonLatLong"';
    $join_fieds_arr[] = 'ST_AsText(s."vPolyLineLatLong") as "vPolyLineLatLong"';
    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $SiteObj->join_field = $join_fieds_arr;
    $SiteObj->join = $join_arr;
    $SiteObj->where = $where_arr;
    $SiteObj->param['limit'] = "LIMIT 1";
    $SiteObj->setClause();
    $rs_site = $SiteObj->recordset_list();
    if(!empty($rs_site)){
        // Premise Attribute dropdown
        $rs_site[0]['address'] = $rs_site[0]['vAddress1'].' '.$rs_site[0]['vStreet'].' '.$rs_site[0]['vCity'].', '.$rs_site[0]['vState'].' '.$rs_site[0]['vCounty'];
        $SiteObj->clear_variable();
        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr  = array();
        $where_arr[] = "site_attribute.\"iSiteId\"='".$iSiteId."'";
        $SiteObj->where = $where_arr;
        $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
        $SiteObj->setClause();
        $rs_site_attr = $SiteObj->site_attribute_list();
        //echo "<pre>";print_r($rs_site_attr);exit();
        if(!empty($rs_site_attr)) {
            $sai = count($rs_site_attr);
            for($sa=0; $sa<$sai; $sa++){
                $iSAttributeIdArr[$sa] = $rs_site_attr[$sa]['iSAttributeId'];
            }
        }

        //Site contact
        $premise_contact_arr_param = array(
            "iSiteId"       => $iSiteId,
            "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        );
        $PREMISE_CONTACT_API_URL = $site_api_url."getPremiseContactData.json";
        //echo $PREMISE_CONTACT_API_URL."\n";
        //echo json_encode($premise_contact_arr_param);exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $PREMISE_CONTACT_API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_contact_arr_param));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           "Content-Type: application/json",
        )); 
        $response_site_contact = curl_exec($ch);
        curl_close($ch);  
        $res_site_contact = json_decode($response_site_contact, true);
        $rs_site_contact = $res_site_contact['result'];
        //echo "<pre>";print_r($rs_site_contact);exit;

        //site document
        $premise_document_arr_param = array(
            "iSiteId"       => $iSiteId,
            "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        );
        $PREMISE_DOCUMENT_API_URL = $site_api_url."getPremiseDocumentData.json";
        //echo $PREMISE_DOCUMENT_API_URL."<br/>";
        //echo json_encode($premise_document_arr_param);exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $PREMISE_DOCUMENT_API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_document_arr_param));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           "Content-Type: application/json",
        )); 
        $response_site_doc = curl_exec($ch);
        curl_close($ch);  
        $res_site_doc = json_decode($response_site_doc, true);
        $rs_site_doc = $res_site_doc['result'];
        //echo "<pre>";print_r($res_site_doc);exit;
    }
}else if($mode == "getSiteSubType"){
	//echo "<pre>";print_r($_REQUEST);exit();
    $premise_subtype_arr_param = array();
    $premise_subtype_arr_param = array(
        "iStatus"        => 1,
        "iSTypeId"      => $_REQUEST['iSiteTypeId'],
        "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
    );
    $premise_subtype_API_URL = $site_api_url."premise_sub_type_dropdown.json";
    //echo $premise_subtype_API_URL;exit();
    //echo json_encode($premise_subtype_arr_param);exit();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $premise_subtype_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_subtype_arr_param));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));
    $response_subtype = curl_exec($ch);
    curl_close($ch); 
    $rs_sstype1 = json_decode($response_subtype, true); 
    $rs_sstype = $rs_sstype1['result'];

	echo json_encode($rs_sstype);
	hc_exit();
} else if($mode == "searchContact"){
    $iCId = trim($_REQUEST['iCId']);
    $vContactName = trim($_REQUEST['vContactName']);

    $arr_param = array(
        'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "iCId"              => $iCId,
        "vContactName"      => $vContactName,
    );
    //echo"<pre>";print_r(json_encode($arr_param));exit;
    $API_URL = $site_api_url."autoSearchContact.json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    )); 

    $response = curl_exec($ch);
    curl_close($ch);  
    $res= json_decode($response, true);
    //echo"<pre>";print_r($res);exit;
    $jsonData =$res['result'];
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
}else if($mode == "getContactData"){
    $iCId = trim($_REQUEST['iCId']);

    $arr_param = array(
        "iCId"              => $iCId,
        'sessionId'         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
    );
    //echo"<pre>";print_r(json_encode($arr_param));exit;
    $API_URL = $site_api_url."getContactData.json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    )); 

    $response = curl_exec($ch);
    curl_close($ch);  
    $res= json_decode($response, true);
    $jsonData =$res['result'];
    //echo"<pre>";print_r($jsonData);exit;
    echo json_encode($jsonData);
    hc_exit();
}


//-----------------  Add Site using lat & lng ----------------------
$GeoLocation = 0;
   // echo "<pre>";print_r($_GET);exit();
if($_GET['lat'] != ""){
    $rs_site[0]['vLatitude'] = $_GET['lat'];
}

if($_GET['lng'] != ""){
    $rs_site[0]['vLongitude'] = $_GET['lng'];
}
    
if($_GET['lat'] != "" && $_GET['lat'] != ""){
        
    $lat = $_GET['lat'];
    $lng = $_GET['lng'];
    $lat = number_format($lat,6);
    $lng = number_format($lng,6);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?key=$GOOGLE_GEOCODE_API_KEY&latlng=".$lat.",".$lng."&sensor=true";
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    curl_close($ch);
    $jsondata = json_decode($data,true);
        
    $address_arr = array();
   // echo "<pre>";print_r($jsondata);exit();
    if(is_array($jsondata) && $jsondata['status'] == "OK"){       
        $mapData =   $jsondata['results']['0'];             
        foreach($mapData['address_components'] as $element){             
            $address_arr[$element['types'][0]]['short_name'] = $element['short_name'];
            $address_arr[$element['types'][0]]['long_name'] = $element['long_name'];
        }

        $rs_site[0]['address']= $jsondata['results']['0']['formatted_address'];

        $vAddress1 = "";
        $vStreet = "";
        $vCrossStreet = "";
        $vCity = "";
        $vCounty = "";
        $vStateCode = "";
        $vState = "";
        $vCountry = "";
        $vZipcode = 0;

       //echo "<pre>";print_r($address_arr);exit();
        if(count($address_arr) > 0){
            
            $GeoLocation = 1;
                
            $rs_site[0]['vAddress1'] = $address_arr['street_number']['short_name'];
            $rs_site[0]['vStreet'] = $address_arr['route']['long_name'];
            $rs_site[0]['vCrossStreet'] = $address_arr['neighborhood']['long_name'];
              
            $vStateCode = trim($address_arr['administrative_area_level_1']['short_name']);
            $vCounty = trim(str_replace("County", "", $address_arr['administrative_area_level_2']['long_name']));
            $vCity = trim($address_arr['locality']['long_name']);
            $vZipcode = trim($address_arr['postal_code']['short_name']);
            
            //get zone id
            if($lat != "" && $lng!=""){
                $latt = number_format($lat,6);
                $long = number_format($lng,6);

                $jsonData = array();
                $arr_param =array();
                $arr_param = array(
                    'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
                    "lat"       => $latt,
                    "long"      => $long,
                );
                //echo"<pre>";print_r(json_encode($arr_param));exit;
                $API_URL = $site_api_url."autoGoogleZoneFromLatlong.json";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $API_URL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                   "Content-Type: application/json",
                )); 

                $response = curl_exec($ch);
                curl_close($ch);  
                $res= json_decode($response, true);
                $rs_zone =$res['result'];

                $rs_site[0]['iZoneId'] = $rs_zone['iZoneId'];
            }

            //get stateid
            if($vStateCode!=""){
                $jsonData = array();
                $arr_param =array();
               

                $arr_param = array(
                    'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
                    "vStateCode"    => $vStateCode,
                );
                //echo"<pre>";print_r(json_encode($arr_param));exit;
                $API_URL = $site_api_url."autoGooglegetState.json";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $API_URL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                   "Content-Type: application/json",
                )); 

                $response = curl_exec($ch);
                curl_close($ch);  
                $res= json_decode($response, true);
                $rs_state =$res['result'];

                $rs_site[0]['iStateId'] = $rs_state['iStateId'];
            }

            //get city & county
            if($vCity != "") {          
                $arr_param =array();
                $arr_param = array(
                    'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
                    "vCity"    => $vCity,
                    "vCounty"    => $vCounty,
                );
                $API_URL = $site_api_url."autoGooglegetCity.json";
                //echo $API_URL."<pre>";print_r(json_encode($arr_param));exit;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $API_URL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                   "Content-Type: application/json",
                )); 

                $response = curl_exec($ch);
                curl_close($ch);  
                $res= json_decode($response, true);
                $rs_citycounty =$res['result'];
                //echo"<pre>";print_r($rs_citycounty);exit;

                $rs_site[0]['iCityId']= $rs_citycounty['iCityId'];
                $rs_site[0]['iCountyId']= $rs_citycounty['iCountyId'];
            }

            //get zipcode id
            $jsonData = array();
            if($vZipcode != ""){

                $arr_param = array(
                    'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
                    "vZipcode"    => $vZipcode,
                );
                //echo"<pre>";print_r(json_encode($arr_param));exit;
                $API_URL = $site_api_url."autoGooglegetZipcode.json";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $API_URL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                   "Content-Type: application/json",
                )); 

                $response = curl_exec($ch);
                curl_close($ch);  
                $res= json_decode($response, true);
                //echo"<pre>";print_r($res);exit;
                $rs_zipcode =json_decode($res['result'], true);
               $rs_site[0]['iZipcode'] = $rs_zipcode['iZipcode'];
            }
 
        }
    }
}
//echo "<pre>";print_r($rs_site);exit();


// Premise Type dropdown
$premise_type_arr_param = array();
$premise_type_arr_param = array(
    "iStatus"    => 1,
    "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$premise_type_API_URL = $site_api_url."premise_type_dropdown.json";
//echo json_encode($premise_type_arr_param);exit();
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $premise_type_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_type_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_sitetype = curl_exec($ch);
curl_close($ch); 
$rs_sitetype1 = json_decode($response_sitetype, true); 
$rs_sitetype = $rs_sitetype1['result'];
//echo "<pre>";print_r($rs_sitetype);exit();

// Premise Attribute dropdown
$premise_attr_arr_param = array();
$premise_attr_arr_param = array(
    "iStatus"    => 1,
    "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$premise_attr_API_URL = $site_api_url."premise_attribute_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $premise_attr_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_attr_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_attr = curl_exec($ch);
curl_close($ch); 
$rs_siteattr1 = json_decode($response_attr, true); 
$rs_siteattr = $rs_siteattr1['result'];

$module_name = "Premise ";
$module_title = "Premise";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_sitetype", $rs_sitetype);
$smarty->assign("rs_siteattr", $rs_siteattr);
$smarty->assign("rs_site", $rs_site);
$smarty->assign("iSAttributeIdArr", $iSAttributeIdArr);

$smarty->assign("rs_site_contact", $rs_site_contact);

$smarty->assign("rs_site_doc", $rs_site_doc);

$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);


$smarty->assign("access_group_var_edit", $access_group_var_edit);

$smarty->assign("tabid",$_REQUEST['tabid']);
?>