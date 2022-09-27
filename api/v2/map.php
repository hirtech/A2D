<?php
include_once($controller_path . "fieldmap.inc.php");

$mapObj = new Fieldmap();

if($request_type == "get_map_filter_data"){
	/*get zone active data*/
   	$sk_zones = $mapObj->getZones();

   	/*get Premise type and subtype active Recrods*/
	$sTypes = $mapObj->getSiteType();

	/*get Premise Attribute active Recrods*/
	$sAttrubutes = $mapObj->getAttributes();

	/*get city active Recrods*/
	$cityArr = $mapObj->getCities();

    $result = array('zone' =>$sk_zones, 'site_type' => $sTypes, 'site_attribute' => $sAttrubutes, 'city' => $cityArr);


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