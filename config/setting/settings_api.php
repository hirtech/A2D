<?php
define('API_REQUEST_MODE_GET','GET');
define('API_REQUEST_MODE_CREATE','POST');
define('API_REQUEST_MODE_DELETE','DELETE');
define('API_REQUEST_MODE_UPDATE','PUT');

$SCREEN_TYPE_ARR = array(1=>'SS', 2=>'LS', 3=>'AU', 4=>'WN', 5=>'CU');

$county_site_map_json_path = $site_storage_images_path."api_map_json/";
$county_site_map_json_url = $site_storage_images_url."api_map_json/";

// variables used for jwt
$jwt_key = "bmFtZSI6IkpvaG4gRG9lIn0";
$jwt_issued_at = time();
$jwt_expiration_time = $jwt_issued_at + (60 * 60); // valid for 1 hour
$jwt_issuer = $site_url;

?>
