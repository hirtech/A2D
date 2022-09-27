<?php
## Created by PN as on Friday, December 19, 2014
function sg_getActivityHourTypeArr() {
	return array(1=>"RT", 2=>"OT");
}


function getGeomFromGeoJSON($json_data){
	
   global $sqlObj;
   $data = (isJson($json_data))?$json_data:json_encode($json_data);
    $sql= "SELECT ST_AsText(ST_GeomFromGeoJSON('".$data."'))";
         //echo $sql;exit;
    $rs_module = $sqlObj->GetAll($sql);  
      //echo "<pre>";print_r($rs_module);exit; 
    return $rs_module;
}


function isJson($string) {
  return ( json_decode( $string , true ) == NULL ) ? false : true ; 
}
?>