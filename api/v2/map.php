<?php
include_once($controller_path . "fieldmap.inc.php");
include_once($controller_path . "user.inc.php");

$mapObj = new Fieldmap();
$userObj = new User();

if($request_type == "get_map_filter_data"){
    //echo "<pre>";print_r($RES_PARA);exit;
    $iLoginUserId = $RES_PARA['iLoginUserId'];

    $user_networks = $userObj->user_networkFromId($iLoginUserId);
    //echo"<pre>";print_r($user_networks);exit;
	/*get zone active data*/
    $sk_zones = $mapObj->getZones();

    /*get network active data*/
    $networkArr = $mapObj->getNetworks();

	/*get city active Recrods*/
	$cityArr = $mapObj->getCities();

    /*get Zipcode Recrods*/
    $zipcodeArr = $mapObj->getZipCodes();

    /*get Zone KML Recrods*/
    $zone_kml = $mapObj->getZoneKMLFile();

    /*get Site type and subtype active Recrods*/
    $premise_type = $mapObj->getPremiseType();

    /*get Site attribute active Recrods*/
    $premise_attribute = $mapObj->getAttributes();

    /*get Connection Type active Recrods*/
    $connection_types = $mapObj->getConnectionTypes();

    $result = array('zone' =>$sk_zones, 'network' =>$networkArr, 'city' => $cityArr, 'zipcode' => $zipcodeArr, 'user_networks' => $user_networks, 'zone_kml' => $zone_kml, 'premise_type' => $premise_type, 'premise_attribute' => $premise_attribute, 'connection_types' => $connection_types);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);

}else if($request_type == "get_map_cluster_layers"){
	$custLayers = $mapObj->getCustomLayers();

    $result = array('custLayers' =>$custLayers);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400, "Message" => $message);
}