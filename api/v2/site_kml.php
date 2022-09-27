<?php

# ------------------------------------------------------------
 ///kml export api//
# ------------------------------------------------------------

if($request_type == "exportkml"){
	$iSiteId=$RES_PARA['iSiteId'];
	$jsonData = array();
	if ($iSiteId != "") {

		$sql_kml = 'SELECT a."iSiteId",a."vName",b."vTypeName",c."vSubTypeName",STRING_AGG(i."vAttribute",\',\' order by h."iSAttributeId"),
		a."vAddress1"||\',\'||a."vStreet"||\',\'||d."vCity"||\',\'||replace(e."vCounty",\' County\',\'\')||\',\'||f."vStateCode"||\',\'||g."vZipcode" as "vAddress" ,
		a."iGeometryType",a."iZoneId", z."vZoneName",ST_AsKML(a."vPointLatLong") as "vPointKML" ,ST_AsKML(a."vPolygonLatLong") as "vPolygonKML" ,ST_AsKML(a."vPolyLineLatLong") as "vPolyLineKML" FROM site_mas as a 
		left join site_type_mas as b on a."iSTypeId"=b."iSTypeId"
		left join site_sub_type_mas as c on a."iSSTypeId"=c."iSSTypeId" 
		left join city_mas as d on a."iCityId"=d."iCityId"
		left join county_mas as e on a."iCountyId"=e."iCountyId"
		left join state_mas as f on a."iStateId"=f."iStateId"
		left join zipcode_mas as g on a."iZipcode"=g."iZipcode"
		left join site_attribute as h on a."iSiteId"=h."iSiteId"
		left join site_attribute_mas as i on h."iSAttributeId"=i."iSAttributeId"
		left join zone as z on a."iZoneId"=z."iZoneId"
		where a."iSiteId" IN ('.$iSiteId.')
		group by a."iSiteId",a."vName",b."vTypeName",c."vSubTypeName",
		a."vAddress1"||\',\'||a."vStreet"||\',\'||d."vCity"||\',\'||replace(e."vCounty",\' County\',\'\')||\',\'||f."vStateCode"||\',\'||g."vZipcode",
		a."iGeometryType",a."iZoneId", z."vZoneName", "vPointKML", "vPolygonKML", "vPolyLineKML"'; 

		$rs = $sqlObj->GetAll($sql_kml);
		$jsonData = array('result' => $rs); 

	} 

	$rh = HTTPStatus(200);
	$code = 2000;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 200, "Message" => $message, "result" => $jsonData);

}
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400, "Message" => $message);
}

?>