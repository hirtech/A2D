<?php
include_once("../config.php");
	
$sql = "SELECT * FROM premise_mas order by \"iPremiseId\"";	
$rs = $sqlObj->GetAll($sql);
$ni = count($rs);
//echo"<pre>";print_r($rs);exit;
if($ni > 0){
	for($i=0; $i<$ni; $i++) {
	    if($rs[$i]['vLongitude'] != '' && $rs[$i]['vLatitude'] != ''){
			$long = number_format($rs[$i]['vLongitude'],6);
			$lat = number_format($rs[$i]['vLatitude'],6);

			$sql_zone = "SELECT zone.\"iZoneId\", zone.\"vZoneName\" FROM zone WHERE  St_Within(ST_GeometryFromText('POINT(".$long." ".$lat.")', 4326)::geometry, (zone.\"PShape\")::geometry)='t'"; 
			$rs_zone = $sqlObj->GetAll($sql_zone);
			$iZoneId = 0;
			if($rs){
				$iZoneId = $rs_zone[0]['iZoneId'];
			}

    	    //echo "Premise Id === ".$rs[$i]['iPremiseId']."<br/>";
    	    //echo "Zone Id === ".$iZoneId."<br/>";
			
			if($iZoneId > 0){
    			$sql_update = 'UPDATE premise_mas set "iZoneId" =  '.gen_allow_null_int($iZoneId).' WHERE "iPremiseId" = '.$rs[$i]['iPremiseId'];
    			echo $sql_update."<hr/>";
    			$sqlObj->Execute($sql_update);
			}
	    }
	}
}
?>