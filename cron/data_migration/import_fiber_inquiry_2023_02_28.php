<?php
include_once("../config.php");
	
$file_path = $site_path."cron/data_migration/csv/";
$file_name = "fiber_inquiry_2023_02_28.csv";
//echo $file_path.$file_name;

$data_arr = gen_read_csv_to_array($file_path, $file_name);
//echo "<pre>";print_r($data_arr);exit;	
$ni = count($data_arr);
//echo $ni;exit;
$cnt = 0;
if($ni > 0){
	for($i=0; $i<$ni; $i++){
		$iFiberInquiryID	= trim($data_arr[$i][0]);	
		$vCarrier			= trim($data_arr[$i][1]);	
		$vNetwork			= trim($data_arr[$i][2]);	
		$vName				= trim($data_arr[$i][3]);	
		$vEmail				= trim($data_arr[$i][4]);	
		$vPhone				= trim($data_arr[$i][5]);	
		$vStreet			= trim($data_arr[$i][6]);	
		$vUnitNum			= trim($data_arr[$i][7]);	
		$vCity				= trim($data_arr[$i][8]);	
		$vState				= trim($data_arr[$i][9]);	
		$vZipcode			= trim($data_arr[$i][10]);	
		$vDate				= trim($data_arr[$i][11]);	
		$vTime				= trim($data_arr[$i][12]);	
		$vComments			= trim($data_arr[$i][13]);	
		$vNotes				= trim($data_arr[$i][14]);	
		$vLAT				= trim($data_arr[$i][15]);	
		$vLON				= trim($data_arr[$i][16]);	
		$vType				= trim($data_arr[$i][17]);	
		$vPostType			= trim($data_arr[$i][18]);	
		$vRequestType		= trim($data_arr[$i][19]);	
		$vStatus			= trim($data_arr[$i][20]);	
		$vFiberZone			= trim($data_arr[$i][21]);	
		$vEngagement		= trim($data_arr[$i][22]);	
		$vService			= trim($data_arr[$i][23]);	

		$vNameArr = explode(" ", $vName);
		$vFirstName = $vNameArr[0];
		$vLastName = $vNameArr[1];

		$vPhone1 = '';
		if($vPhone != ''){
			$vPhoneA = preg_replace('/[^A-Za-z0-9]/', '', $vPhone);

			$regex = '/(\\d{3})(\\d{3})(\\d{4})/';
			$vPhone1 = 	preg_replace($regex, '$1 $2 $3 $4 $5', $vPhoneA);
		}
		//echo $vPhone1."<hr/>";

		$iCId = 0;
		$sql_c = "SELECT \"iCId\" FROM contact_mas where \"vEmail\" = '".$vEmail."' ORDER BY \"iCId\" DESC LIMIT 1";
		$rs_c = $sqlObj->GetAll($sql_c);
		if($rs_c) {
			$iCId = $rs_c[0]['iCId'];
		}else {
			$sql_contact = "INSERT INTO contact_mas(\"vFirstName\", \"vLastName\", \"vEmail\",  \"vPhone\", \"iStatus\", \"iDelete\", \"dAddedDate\") VALUES('".$vFirstName."', '".$vLastName."', '".$vEmail."', '".$vPhone1."', 1, 0, '".date_getSystemDateTime()."')";
			$sqlObj->Execute($sql_contact);
			$iCId = $sqlObj->Insert_ID();
		}
		

		if($vLAT != "" && $vLON != ""){					
			$url = "https://maps.googleapis.com/maps/api/geocode/json?key=$GOOGLE_GEOCODE_API_KEY&latlng=".$vLAT.",".$vLON."&sensor=true";
			//echo $url;exit;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			curl_close($ch);
			$jsondata = json_decode($data,true);
			//echo "<pre>";print_r($jsondata);exit;
			$address_arr = array();
			if(is_array($jsondata) && $jsondata['status'] == "OK"){							
				 foreach($jsondata['results']['0']['address_components'] as $element){
					$address_arr[$element['types'][0]]['short_name'] = $element['short_name'];
					$address_arr[$element['types'][0]]['long_name'] = $element['long_name'];
				 }
			}
			
			$vAddress1 = "";
			$vAddress2 = "";
			$vStreet = "";
			$vCrossStreet = "";
			$vZipcode = "";
			$iZipcode = 0;
			$vStateCode = "";
			$vState = "";
			$iStateId = 0;
			$vCountry = "";
			$vCountryCode = "";
			$iCountyId = 0;
			$vCity = "";
			$iCityId = 0;
			$iZoneId = 0;
			//echo "<pre>";print_r($address_arr);exit;
			
			if(count($address_arr) > 0){
				$vAddress1 = $address_arr['street_number']['short_name'];
				$vStreet = $address_arr['route']['long_name'];
				$vCrossStreet = $address_arr['neighborhood']['long_name'];
				$vZipcode = $address_arr['postal_code']['short_name'];
				$vCity = $address_arr['locality']['long_name'];
				$vCounty = trim(str_replace("County", "", $address_arr['administrative_area_level_2']['long_name']));
				$vStateCode = $address_arr['administrative_area_level_1']['short_name'];
				$vState = $address_arr['administrative_area_level_1']['long_name'];
				$vCountry = $address_arr['country']['long_name'];
				$vCountryCode = $address_arr['country']['short_name'];

				$sql_state = 'SELECT "iStateId" FROM state_mas WHERE "vStateCode" = '.gen_allow_null_char($address_arr['administrative_area_level_1']['short_name']);
				$rs_state = $sqlObj->GetAll($sql_state);
				if($rs_state) {
					$iStateId = $rs_state[0]['iStateId'];
				}
				
				$long = number_format($vLON,6);
				$lat = number_format($vLAT,6);

				$sql_zone = "SELECT zone.\"iZoneId\", zone.\"vZoneName\" FROM zone WHERE  St_Within(ST_GeometryFromText('POINT(".$long." ".$lat.")', 4326)::geometry, (zone.\"PShape\")::geometry)='t'"; 
		        $rs_zone = $sqlObj->GetAll($sql_zone);
		        if($rs_zone){
		            $iZoneId = $rs_zone[0]['iZoneId'];
		        }

				$sql_county = 'SELECT "iCountyId" FROM county_mas WHERE "vCounty" ILIKE \'%'.$vCounty.'%\' LIMIT 1';
				$rs_county = $sqlObj->GetAll($sql_county);
				if($rs_county){
					$iCountyId = $rs_county[0]['iCountyId'];
				}
				
				$sql_city = 'SELECT "iCityId" FROM city_mas WHERE "vCity" = '.gen_allow_null_char($vCity);
				$rs_city = $sqlObj->GetAll($sql_city);
				if($rs_city){
					$iCityId = $rs_city[0]['iCityId'];
				}
				
				if($vZipcode != "") {
					$sql_zipcode = 'SELECT "iZipcode" FROM "zipcode_mas" WHERE "vZipcode"=\''.$vZipcode.'\' LIMIT 1';
					$rs_zipcode = $sqlObj->GetAll($sql_zipcode);
					$cnt_c = count($rs_zipcode);
					//echo $sql_zipcode."<br/>";
					if($cnt_c > 0)	{
						$iZipcode =  $rs_zipcode[0]['iZipcode']; 
					} else {
						$sql_in = 'INSERT INTO "zipcode_mas" ("vZipcode", "iStateId", "iCountyId", "iCityId") VALUES ('.gen_allow_null_char($vZipcode).', '.gen_allow_null_int($iStateId).', '.gen_allow_null_int($iCountyId).', '.gen_allow_null_int($iCityId).')';
						//echo $sql_zipcode."<br/>";
						$sqlObj->Execute($sql_in);
						$iZipcode =  $sqlObj->Insert_ID();
					}
				}
			}
		}
		//echo "iZipcode = ".$iZipcode."<br/>";
		//echo "iCId = ".$iCId."<br/>";
		$iPremiseSubTypeId = 0;
		$sql_stype = "SELECT \"iSSTypeId\" FROM site_sub_type_mas WHERE \"vSubTypeName\" = '".$vType."' ORDER BY \"iSSTypeId\" LIMIT 1";
		$rs_stype = $sqlObj->GetAll($sql_stype);
		if($rs_stype) {
			$iPremiseSubTypeId = $rs_stype[0]['iSSTypeId']; 
		}

		$iEngagementId = 0;
		$sql_eng = "SELECT \"iEngagementId\" FROM engagement_mas WHERE \"vEngagement\" = '".$vEngagement."' ORDER BY \"iEngagementId\" LIMIT 1";
		$rs_eng = $sqlObj->GetAll($sql_eng);
		if($rs_eng) {
			$iEngagementId = $rs_eng[0]['iEngagementId']; 
		}

		$iMatchingPremiseId = 0;
		$iLoginUserId = 1;
		$iInquiryType = 0;
		$iStatus = 4; // Complete

		if($vRequestType == "Check Availability") {
			$iInquiryType = 1;
		}else if($vRequestType == "Order") {
			$iInquiryType = 2;
		}else if($vRequestType == "Reservation") {
			$iInquiryType = 3;
		}
		$sql_fInquiry = "INSERT INTO fiberinquiry_details(\"vAddress1\", \"vStreet\",\"vCrossStreet\", \"iZipcode\", \"iStateId\", \"iCountyId\", \"iCityId\", \"iZoneId\", \"vLatitude\", \"vLongitude\", \"iCId\", \"iStatus\", \"iPremiseSubTypeId\", \"iEngagementId\", \"dAddedDate\", \"iMatchingPremiseId\", \"iLoginUserId\", \"iInquiryType\", \"tNotes\") VALUES ('".$vAddress1."','".$vStreet."', '".$vCrossStreet."', '".$iZipcode."', '".$iStateId."', '".$iCountyId."', '".$iCityId."', '".$iZoneId."', '".$lat."', '".$long."', '".$iCId."', '".$iStatus."', '".$iPremiseSubTypeId."', '".$iEngagementId."', '".date_getSystemDateTime()."', '".$iMatchingPremiseId."', '".$iLoginUserId."', '".$iInquiryType."', '".$vComments."')";
		echo $sql_fInquiry."<hr/>";//exit;
		$sqlObj->Execute($sql_fInquiry);
		$iFiberInquiryId =  $sqlObj->Insert_ID();
		if($iFiberInquiryId > 0){
			$sql = "INSERT INTO fiberinquiry_status_history(\"iFiberInquiryId\", \"iStatus\", \"dAddedDate\", \"iLoginUserId\") VALUES (".gen_allow_null_int($iFiberInquiryId).", ".$iStatus.",  ".gen_allow_null_char(date_getSystemDateTime()).", ".gen_allow_null_int($iLoginUserId).")";
			$sqlObj->Execute($sql);	
		}

		$cnt++;
	}

}

echo "Total ". $cnt." Records are inserted.";

?>