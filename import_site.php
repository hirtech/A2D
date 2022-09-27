<?php
include_once("server.php");

$file_path = $site_storage_images_path."westumatilla/";
$file_name = "westmulla_site_2.csv";
//echo $file_path.$file_name;exit;

$mat_arr = gen_read_csv_to_array($file_path, $file_name);
//echo count($mat_arr);exit;
//echo "<pre>";print_r($mat_arr);exit;

$ni = count($mat_arr);
if($ni > 0){
	$counter = 0;
	for($i=0;$i<$ni;$i++){
				$sql = 	'INSERT INTO public.site_mas ("vName","iSTypeId","iSSTypeId","vAddress1","vAddress2","vStreet","vCrossStreet","iZipcode","iStateId","iCountyId","iCityId","iGeometryType","iZoneId","vLatitude","vLongitude","vPointLatLong","dAddedDate","dModifiedDate","iStatus","vLoginUserName","vPolygonLatLong","vPolyLineLatLong","vNewLatitude","vNewLongitude")
					VALUES ('.gen_allow_null_char($mat_arr[$i][0]).',\'1\',\'0\',NULL,NULL,NULL,NULL,\'0\',\'0\',\'0\',\'0\',\'1\',\'1\','.gen_allow_null_char($mat_arr[$i][13]).','.gen_allow_null_char($mat_arr[$i][14]).',ST_GeomFromText(\'POINT('.$mat_arr[$i][14].' '.$mat_arr[$i][13].')\',4326),NULL,NULL,\'1\',\'Randy Gerard\',NULL,NULL,NULL,NULL);';

				echo "<br>\n".$sql;
			}
			$counter++;
		}
	
?>
