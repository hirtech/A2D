<?php
include_once("config.php");
	
$sql = "SELECT * FROM premise_mas order by \"iPremiseId\"";	
$rs = $sqlObj->GetAll($sql);
$ni = count($rs);
//echo"<pre>";print_r($rs);exit;
if($ni > 0){
	for($i=0; $i<$ni; $i++) {
	    if($rs[$i]['vLongitude'] != '' && $rs[$i]['vLatitude'] != ''){
			$long = number_format($rs[$i]['vLongitude'],6);
			$lat = number_format($rs[$i]['vLatitude'],6);

			$arr_param = array(
				'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
				"lat"       => $lat,
				"long"      => $long,
			);
			$API_URL = $site_api_url."autoGoogleZoneFromLatlong.json";
			//echo $API_URL." ".json_encode($arr_param);exit;
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
			$iZoneId = $jsonData['iZoneId'];

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