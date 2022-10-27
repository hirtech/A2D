<?php
//$sitefolder = "/";
$sitefolder = "/eCommunityfiber/";  

$adminfolder = "smspanel/";
$site_path	= $_SERVER["DOCUMENT_ROOT"].$sitefolder;

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off")
	$site_url	= "http://".$_SERVER["HTTP_HOST"].$sitefolder;
else
	$site_url	= "https://".$_SERVER["HTTP_HOST"].$sitefolder;


//global $admin_panel_session_suffix;
$admin_panel_session_suffix = "_erp";
$user_suffix = "_erp";

$admin_path = $site_path.$adminfolder;
$admin_url = $site_url.$adminfolder;
$admin_images_path = $admin_path."images/";
$admin_images_url = $admin_url."images/";

$domain_name = ".vectorcontrolsystem.com";
$domain_url = "https://www.vectorcontrolsystem.com";

$lib_path = $site_path."config/";
$function_path = $site_path."config/function/";
$class_path = $site_path."config/class/";
$controller_path = $class_path."controller/";
$setting_path = $site_path."config/setting/";
$serverdir_path = $site_path."config/server/";
$logdir_path = $site_path."logs/";

$desc_order = $admin_images_url."asc_order.gif";
$asc_order 	= $admin_images_url."desc_order.gif";

$template_path = $site_path . "views/";

# SQL Data Backup Path
$db_backup_path =$admin_path."db_backup/";
$db_backup_url = $admin_url."db_backup/";

$site_images_path = $site_path."images/";
$site_images_url = $site_url."images/";


$USERNAME_MIN_LENGTH = 5; // Minimum username length
$PASSWORD_MIN_LENGTH = 5; // Minimum password length

# Image Storage Path and URL
$site_storage_images_path = $site_path."storage/";
$site_storage_images_url = $site_url."storage/";
$temp_gallery = $site_storage_images_path."temp/";
$temp_gallery_url = $site_storage_images_url."temp/";

$master_db_path = $site_storage_images_path."sql_master_copy/";
$master_db_name ="ecommunity_fiber_base_db.sql";

$user_path = $site_storage_images_path."user/";
$user_url = $site_storage_images_url."user/";

$download_path = $site_storage_images_path."download/";
$download_url = $site_storage_images_url."download/";

$cache_file_path = $site_storage_images_path."CacheFiles/";
$cache_file_url = $site_storage_images_url."CacheFiles/";

$premise_documents_path = $site_storage_images_path."premise_documents/";
$premise_documents_url = $site_storage_images_url."premise_documents/";

$time_zone_path = $site_storage_images_path."time_zone/";

## Captcha Security Key and Secret Key
$RECAPTCHA_MASTER_SITE_KEY = '6LfffjAUAAAAAPWFt5X3BJfDEWWIdOIZBNB1w-Yg';
$RECAPTCHA_MASTER_SECRET = '6LfffjAUAAAAAMidlNlH9EIUBaPqmpcYlJ8pbSes';

$status_color = array(
	'Active'=> 'success',
	'Inactive'=> 'danger',
	'Delete'=> 'danger',
	'Pending'=> 'info',
	'Shipped'=> 'primary',
	'Accepted'=> 'success',
	'Closed'=> 'primary',
	'Rejected'=> 'danger',
	'Overdue'=> 'danger',
	'Open'=> 'warning',
	'Paid'=> 'success',
	'Expired'=> 'danger',
	'Blocked'=> 'danger',
	'Partial'=> 'primary',
	'Locked'=> 'warning',
	'Delivered'=> 'info',
	'Unapplied'=> 'warning',
	'Failed'=> 'danger',
	'Cleared'=> 'success',
	'Uncleared'=> 'warning',
	'Sent'=> 'success',
	'Upcoming'=> 'info',
	'Planning'=> 'info',
	'Cancelled'=> 'warning',
	'Inprocess'=> 'primary',
	'Completed'=> 'success',
	'Approve'=> 'success',
	'Approved'=> 'success',
	'Finished'=> 'primary',
	'Processing'=> 'info',
	'Incomplete' => 'warning',
	'Waiting For Approval' => 'warning',
	'Unshipped'=> 'primary',
	'Declined'=> 'danger',
	'Deleted'=> 'danger',
	'Refunded'=> 'success',
	'Paused'=> 'danger',
	'Subscribed'=> 'success',
	'UnSubscribed'=> 'dark',
	''=> 'default',
	'Suspended' => 'warning',
	'Yes' => 'success',
	'No' =>'danger',
);

$notification_class_arr = array(
	"sr" => array('icon' => 'icon-grid' , "color" => 'text-success'),
	"trap" => array('icon' => 'icon-grid' , "color" => 'text-warning'),
	"treatment" => array('icon' => 'icon-grid' , "color" => 'text-primary' ),
	"pool"  => array('icon' => 'icon-grid' , "color" => 'text-info' ),
	"mosquitocount"  => array('icon' => 'icon-grid' , "color" => 'text-secondary' ),
);

#help
$help_path = $site_storage_images_path."help/";
$help_url = $site_storage_images_url."help/";

$panel_default_customizer = array(
	"template_color" => "#3e8ef7",
	"template_layout" => "vertical",
	"template_style" => "light",
	"bCompactMenu" => "FALSE",
	"bsmallMenu" => "TRUE"
);


$premise_type_icon_path = $site_storage_images_path."premise_type_icon/";
$premise_type_icon_url = $site_storage_images_url."premise_type_icon/";

$field_map_json_url = $site_storage_images_path."field_map_json_data/";
$field_map_json_path = $site_storage_images_url."field_map_json_data/";

$admin_user_path = $site_storage_images_path."admin_user/";
$admin_user_url = $site_storage_images_url."admin_user/";

//Array For Hide Data According Login type
$settings_hide_data_logintype =array(
    'country_user' => array('SR_FORM_FAILER_MESSAGE','MAP_LATITUDE','MAP_LONGITUDE','MAP_REFRESH_TIME'),
    'normal_user' =>array('SITE_TITLE','COMPANY_COPYRIGHTS','SESSION_STORED_IN_DB','GOOGLE_GEOCODE_API_KEY')
);

$county_setting_var =array('COMPANY_COPYRIGHTS','SITE_TITLE','GOOGLE_GEOCODE_API_KEY','ADMIN_EMAIL','EMAIL_FROM_NAME');
$county_settings_prefix  = "COUNTY_";

$import_file_path = $site_storage_images_path."import_file/";
$import_file_url = $site_storage_images_url."import_file/";

$samplefiles_url =$site_storage_images_path."samplefiles/";
$samplefiles_url = $site_storage_images_url."samplefiles/";

$import_larval_file_headers = array('A' => 'Zone' , 'B' => 'Premise' , 'C' => 'Premise Type' ,'D' => 'Premise Status','E' => 'Treatment Date/Time' , 'F' => 'Treatment Justification','G' => 'Total Area Treated', 'H' => 'Total Area Unit','I'=>'Total Product Applied','J' => 'Total Product Unit','K' => 'Equipment ID','L' => 'Larvae Present' ,'M' => 'Water Temp','N' => 'Followup' ,'O' => 'Sample' ,'P' => 'Larvae Count','Q' => 'Source Reduction' ,'R' => 'Source Reduction Comments','S' => 'Finished Mix' ,'T' => 'Weather Condition','U' => 'Instar' ,'V' => 'Application Method','W' => 'Adult','X'=>'Aerial','Y'=>'Larval','Z'=>'Applicator','AA'=>'Applicator Phone','AB'=>'Application Rate','AC' => 'Rate Type','AD' => 'Wind Speed','AE' => 'Product Name','AF' => 'Product Code','AG' => 'URL' ,'AH' => 'Product Formulation' , 'AI' => 'Product Quantity' ,'AJ' => 'Pack Size','AK' => 'Product Size' ,'AL' => 'Product Potency','AM' => 'Manufacturer','AN' => 'Mix Ratio','AO' => 'Treatment System' ,'AP' => 'Species','AQ' => 'Species Abbr','AR' => 'Subspecies','AS' => 'Habitat','AT' => 'Comments','AU'=> 'Address (1)','AV' => 'Address (2)','AW' => 'City','AX' => 'State','AY' => 'Postal Code','AZ' => 'Longitude','BA' => 'Latitude','BB' => 'PTI' ,'BC'=>'PTI Date');

$import_sr_file_headers =array('A' => 'Zone' , 'B' => 'Site' , 'C' => 'Description' ,'D' => 'Open Date','E' => 'Open Time' , 'F' => 'Close Date','G' => 'Close Time', 'H' => 'Requested By','I'=>'Disposition','J' => 'Notes','K' => 'Inspection','L' => 'Surveillance' ,'M' => 'Treatment','N' => 'Address (1)' ,'O' => 'Address (2)' ,'P' => 'City','Q' => 'State' ,'R' => 'Postal Code','S' => 'Longitude' ,'T' => 'Latitude','U' => 'WA Source');

$import_adult_file_headers = array('A' => 'Zone' , 'B' => 'Site' , 'C' => 'Treatment Date/Time' ,'D' => 'Application Rate','E' => 'Application Area Rate Units' , 'F' => 'Application Start Date/Time','G' => 'Application End Date/Time', 'H' => 'Area Name','I'=>'Area Treated','J' => 'Area Units','K' => 'Rate Type','L' => 'Wind Speed' ,'M' => 'Total Product','N' => 'Total Product Unit' ,'O' => 'Odometer Start' ,'P' => 'Odometer End','Q' => 'Finished Mix' ,'R' => 'Finished Mix Unit','S' => 'Flow Rate' ,'T' => 'Flow Rate Type','U' => 'Comments' ,'V' => 'Equipment Serial Number','W' => 'Address (1)','X'=>'Address (2)','Y'=>'City','Z'=>'State','AA'=>'Postal Code','AB'=>'Longitude','AC' => 'Latitude','AD' => 'Treatment System','AE' => 'Application Method','AF' => 'Treatment Justification','AG' => 'Adult' ,'AH' => 'Aerial' , 'AI' => 'Larval' ,'AJ' => 'Applicator','AK' => 'Applicator Phone' ,'AL' => 'Product Name','AM' => 'Product Code','AN' => 'URL','AO' => 'Product Formulation' ,'AP' => 'Product Quantity','AQ' => 'Pack Size','AR' => 'Product Size','AS' => 'Product Potency','AT' => 'Dilutent','AU'=> 'Mix Ratio','AV' => 'Manufacturer','AW' => 'Humidity','AX' => 'Cloud Cover','AY' => 'Temperature Start','AZ' => 'Temperature End');

#Custom layer kml file
$custom_layer_path = $site_storage_images_path."kml/";
$custom_layer_url = $site_storage_images_url."kml/";

#Network kml file
$network_path = $site_storage_images_path."netowrk_kml/";
$network_url = $site_storage_images_url."netowrk_kml/";

#Zone
$zone_path = $site_storage_images_path."zone/";
$zone_url = $site_storage_images_url."zone/";

$google_map_country_code = array(array("countrycode" => "AF","countryname" => "Afghanistan"),array("countrycode" => "AL", "countryname" => "Albania" ),array("countrycode" => "DZ","countryname" => "Algeria"),array( "countrycode" => "AS","countryname" => "American Samoa"),array("countrycode" => "AD","countryname" => "Andorra"),array("countrycode" => "AO","countryname" => "Angola"),array("countrycode" => "AI","countryname" => "Anguilla"),array( "countrycode" => "AQ", "countryname" => "Antarctica"),array("countrycode" => "AG","countryname" => "Antigua & Barbuda"),array("countrycode" => "AR","countryname" => "Argentina"),array("countrycode" => "AM","countryname" => "Armenia"),array("countrycode" => "AW","countryname" => "Aruba"),array("countrycode" => "AC","countryname" => "Ascension Island"),array("countrycode" => "AU","countryname" => "Australia"),array("countrycode" => "AT","countryname" => "Austria"),array("countrycode" => "AZ","countryname" => "Azerbaijan"),array("countrycode" => "BS","countryname" => "Bahamas"),array(    "countrycode" => "BH",    "countryname" => "Bahrain"),array("countrycode" => "BD","countryname" => "Bangladesh"),array("countrycode" => "BB","countryname" => "Barbados"),array("countrycode" => "BY","countryname" => "Belarus"),array("countrycode" => "BE","countryname" => "Belgium"),array("countrycode" => "BZ","countryname" => "Belize"),array("countrycode" => "BJ","countryname" => "Benin"),array("countrycode" => "BM","countryname" => "Bermuda"),array("countrycode" => "BT","countryname" => "Bhutan"),array("countrycode" => "BO","countryname" => "Bolivia"),array("countrycode" => "BA","countryname" => "Bosnia & Herzegovina"),array("countrycode" => "BW","countryname" => "Botswana"),array("countrycode" => "BV","countryname" => "Bouvet Island"),array("countrycode" => "BR","countryname" => "Brazil"),array("countrycode" => "IO","countryname" => "British Indian Ocean Territory"),array("countrycode" => "VG","countryname" => "British Virgin Islands"),array("countrycode" => "BN","countryname" => "Brunei"),array("countrycode" => "BG","countryname" => "Bulgaria"),array("countrycode" => "BF","countryname" => "Burkina Faso"),array("countrycode" => "BI","countryname" => "Burundi"),array("countrycode" => "KH","countryname" => "Cambodia"),array("countrycode" => "CM","countryname" => "Cameroon"),array("countrycode" => "CA","countryname" => "Canada"),array("countrycode" => "IC","countryname" => "Canary Islands"),array("countrycode" => "CV","countryname" => "Cape Verde"),array("countrycode" => "BQ","countryname" => "Caribbean Netherlands"),array("countrycode" => "KY","countryname" => "Cayman Islands"),array("countrycode" => "CF","countryname" => "Central African Republic"),array("countrycode" => "EA","countryname" => "Ceuta & Melilla"),array("countrycode" => "TD","countryname" => "Chad"),array("countrycode" => "CL","countryname" => "Chile"),array("countrycode" => "CN","countryname" => "China"),array("countrycode" => "CX","countryname" => "Christmas Island"),array("countrycode" => "CP","countryname" => "Clipperton Island"),array("countrycode" => "CC","countryname" => "Cocos (Keeling) Islands"),array("countrycode" => "CO","countryname" => "Colombia"),array("countrycode" => "KM","countryname" => "Comoros"),array("countrycode" => "CG","countryname" => "Congo - Brazzaville"),array("countrycode" => "CD","countryname" => "Congo - Kinshasa"),array("countrycode" => "CK","countryname" => "Cook Islands"),array("countrycode" => "CR","countryname" => "Costa Rica"),array("countrycode" => "HR","countryname" => "Croatia"),array("countrycode" => "CU","countryname" => "Cuba"),array("countrycode" => "CW","countryname" => "Curaçao"),array("countrycode" => "CY","countryname" => "Cyprus"),array("countrycode" => "CZ","countryname" => "Czechia"),array("countrycode" => "CI","countryname" => "Côte d’Ivoire"),array("countrycode" => "DK","countryname" => "Denmark"),array("countrycode" => "DG","countryname" => "Diego Garcia"),array("countrycode" => "DJ","countryname" => "Djibouti"),array("countrycode" => "DM","countryname" => "Dominica"),array("countrycode" => "DO","countryname" => "Dominican Republic"),array("countrycode" => "EC","countryname" => "Ecuador"),array("countrycode" => "EG","countryname" => "Egypt"),array("countrycode" => "SV","countryname" => "El Salvador"),array("countrycode" => "GQ","countryname" => "Equatorial Guinea"),array("countrycode" => "ER","countryname" => "Eritrea"),array("countrycode" => "EE","countryname" => "Estonia"),array("countrycode" => "SZ","countryname" => "Eswatini"),array("countrycode" => "ET","countryname" => "Ethiopia"),array("countrycode" => "FK","countryname" => "Falkland Islands (Islas Malvinas)"),array("countrycode" => "FO","countryname" => "Faroe Islands"),array("countrycode" => "FJ","countryname" => "Fiji"),array("countrycode" => "FI","countryname" => "Finland"),array("countrycode" => "FR","countryname" => "France"),array("countrycode" => "GF","countryname" => "French Guiana"),array("countrycode" => "PF","countryname" => "French Polynesia"),array("countrycode" => "TF","countryname" => "French Southern Territories"),array("countrycode" => "GA","countryname" => "Gabon"),array("countrycode" => "GM","countryname" => "Gambia"),array("countrycode" => "GE","countryname" => "Georgia"),array("countrycode" => "DE","countryname" => "Germany"),array("countrycode" => "GH","countryname" => "Ghana"),array("countrycode" => "GI","countryname" => "Gibraltar"),array("countrycode" => "GR","countryname" => "Greece"),array("countrycode" => "GL","countryname" => "Greenland"),array("countrycode" => "GD","countryname" => "Grenada"),array("countrycode" => "GP","countryname" => "Guadeloupe"),array("countrycode" => "GU","countryname" => "Guam"),array("countrycode" => "GT","countryname" => "Guatemala"),array("countrycode" => "GG","countryname" => "Guernsey"),array("countrycode" => "GN","countryname" => "Guinea"),array("countrycode" => "GW","countryname" => "Guinea-Bissau"),array("countrycode" => "GY","countryname" => "Guyana"),array("countrycode" => "HT","countryname" => "Haiti"),array("countrycode" => "HM","countryname" => "Heard & McDonald Islands"),array("countrycode" => "HN","countryname" => "Honduras"),array("countrycode" => "HK","countryname" => "Hong Kong"),array("countrycode" => "HU","countryname" => "Hungary"),array("countrycode" => "IS","countryname" => "Iceland"),array("countrycode" => "IN","countryname" => "India"),array("countrycode" => "ID","countryname" => "Indonesia"),array("countrycode" => "IR","countryname" => "Iran"),array("countrycode" => "IQ","countryname" => "Iraq"),array("countrycode" => "IE","countryname" => "Ireland"),array("countrycode" => "IM","countryname" => "Isle of Man"),array("countrycode" => "IL","countryname" => "Israel"),array("countrycode" => "IT","countryname" => "Italy"),array("countrycode" => "JM","countryname" => "Jamaica"),array("countrycode" => "JP","countryname" => "Japan"),array("countrycode" => "JE","countryname" => "Jersey"),array("countrycode" => "JO","countryname" => "Jordan"),array("countrycode" => "KZ","countryname" => "Kazakhstan"),array("countrycode" => "KE","countryname" => "Kenya"),array("countrycode" => "KI","countryname" => "Kiribati"),array("countrycode" => "XK","countryname" => "Kosovo"),array("countrycode" => "KW","countryname" => "Kuwait"),array("countrycode" => "KG","countryname" => "Kyrgyzstan"),array("countrycode" => "LA","countryname" => "Laos"),array("countrycode" => "LV","countryname" => "Latvia"),array("countrycode" => "LB","countryname" => "Lebanon"),array("countrycode" => "LS","countryname" => "Lesotho"),array("countrycode" => "LR","countryname" => "Liberia"),array("countrycode" => "LY","countryname" => "Libya"),array("countrycode" => "LI","countryname" => "Liechtenstein"),array("countrycode" => "LT","countryname" => "Lithuania"),array("countrycode" => "LU","countryname" => "Luxembourg"),array("countrycode" => "MO","countryname" => "Macao"),array("countrycode" => "MG","countryname" => "Madagascar"),array("countrycode" => "MW","countryname" => "Malawi"),array("countrycode" => "MY","countryname" => "Malaysia"),array("countrycode" => "MV","countryname" => "Maldives"),array("countrycode" => "ML","countryname" => "Mali"),array("countrycode" => "MT","countryname" => "Malta"),array("countrycode" => "MH","countryname" => "Marshall Islands"),array("countrycode" => "MQ","countryname" => "Martinique"),array("countrycode" => "MR","countryname" => "Mauritania"),array("countrycode" => "MU","countryname" => "Mauritius"),array("countrycode" => "YT","countryname" => "Mayotte"),array("countrycode" => "MX","countryname" => "Mexico"),array("countrycode" => "FM","countryname" => "Micronesia"),array("countrycode" => "MD","countryname" => "Moldova"),array("countrycode" => "MC","countryname" => "Monaco"),array("countrycode" => "MN","countryname" => "Mongolia"),array("countrycode" => "ME","countryname" => "Montenegro"),array("countrycode" => "MS","countryname" => "Montserrat"),array("countrycode" => "MA","countryname" => "Morocco"),array("countrycode" => "MZ","countryname" => "Mozambique"),array("countrycode" => "MM","countryname" => "Myanmar (Burma)"),array("countrycode" => "NA","countryname" => "Namibia"),array("countrycode" => "NR","countryname" => "Nauru"),array("countrycode" => "NP","countryname" => "Nepal"),array("countrycode" => "NL","countryname" => "Netherlands"),array("countrycode" => "NC","countryname" => "New Caledonia"),array("countrycode" => "NZ","countryname" => "New Zealand"),array("countrycode" => "NI","countryname" => "Nicaragua"),array("countrycode" => "NE","countryname" => "Niger"),array("countrycode" => "NG","countryname" => "Nigeria"),array("countrycode" => "NU","countryname" => "Niue"),array("countrycode" => "NF","countryname" => "Norfolk Island"),array("countrycode" => "KP","countryname" => "North Korea"),array("countrycode" => "MK","countryname" => "North Macedonia"),array("countrycode" => "MP","countryname" => "Northern Mariana Islands"),array("countrycode" => "NO","countryname" => "Norway"),array("countrycode" => "OM","countryname" => "Oman"),array("countrycode" => "PK","countryname" => "Pakistan"),array("countrycode" => "PW","countryname" => "Palau"),array("countrycode" => "PS","countryname" => "Palestine"),array("countrycode" => "PA","countryname" => "Panama"),array("countrycode" => "PG","countryname" => "Papua New Guinea"),array("countrycode" => "PY","countryname" => "Paraguay"),array("countrycode" => "PE","countryname" => "Peru"),array("countrycode" => "PH","countryname" => "Philippines"),array("countrycode" => "PN","countryname" => "Pitcairn Islands"),array("countrycode" => "PL","countryname" => "Poland"),array("countrycode" => "PT","countryname" => "Portugal"),array("countrycode" => "PR","countryname" => "Puerto Rico"),array("countrycode" => "QA","countryname" => "Qatar"),array("countrycode" => "RO","countryname" => "Romania"),array("countrycode" => "RU","countryname" => "Russia"),array("countrycode" => "RW","countryname" => "Rwanda"),array("countrycode" => "RE","countryname" => "Réunion"),array("countrycode" => "WS","countryname" => "Samoa"),array("countrycode" => "SM","countryname" => "San Marino"),array("countrycode" => "SA","countryname" => "Saudi Arabia"),array("countrycode" => "SN","countryname" => "Senegal"),array("countrycode" => "RS","countryname" => "Serbia"),array("countrycode" => "SC","countryname" => "Seychelles"),array("countrycode" => "SL","countryname" => "Sierra Leone"),array("countrycode" => "SG","countryname" => "Singapore"),array("countrycode" => "SX","countryname" => "Sint Maarten"),array("countrycode" => "SK","countryname" => "Slovakia"),array("countrycode" => "SI","countryname" => "Slovenia"),array("countrycode" => "SB","countryname" => "Solomon Islands"),array("countrycode" => "SO","countryname" => "Somalia"),array("countrycode" => "ZA","countryname" => "South Africa"),array("countrycode" => "GS","countryname" => "South Georgia & South Sandwich Islands"),array("countrycode" => "KR","countryname" => "South Korea"),array("countrycode" => "SS","countryname" => "South Sudan"),array("countrycode" => "ES","countryname" => "Spain"),array("countrycode" => "LK","countryname" => "Sri Lanka"),array("countrycode" => "BL","countryname" => "St. Barthélemy"),array("countrycode" => "SH","countryname" => "St. Helena"),array("countrycode" => "KN","countryname" => "St. Kitts & Nevis"),array("countrycode" => "LC","countryname" => "St. Lucia"),array("countrycode" => "MF","countryname" => "St. Martin"),array("countrycode" => "PM","countryname" => "St. Pierre & Miquelon"),array("countrycode" => "VC","countryname" => "St. Vincent & Grenadines"),array("countrycode" => "SD","countryname" => "Sudan"),array("countrycode" => "SR","countryname" => "Suricountrycode"),array("countrycode" => "SJ","countryname" => "Svalbard & Jan Mayen"),array("countrycode" => "SE","countryname" => "Sweden"),array("countrycode" => "CH","countryname" => "Switzerland"),array("countrycode" => "SY","countryname" => "Syria"),array("countrycode" => "ST","countryname" => "São Tomé & Príncipe"),array("countrycode" => "TW","countryname" => "Taiwan"),array("countrycode" => "TJ","countryname" => "Tajikistan"),array("countrycode" => "TZ","countryname" => "Tanzania"),array("countrycode" => "TH","countryname" => "Thailand"),array("countrycode" => "TL","countryname" => "Timor-Leste"),array("countrycode" => "TG","countryname" => "Togo"),array("countrycode" => "TK","countryname" => "Tokelau"),array("countrycode" => "TO","countryname" => "Tonga"),array("countrycode" => "TT","countryname" => "Trinidad & Tobago"),array("countrycode" => "TA","countryname" => "Tristan da Cunha"),array("countrycode" => "TN","countryname" => "Tunisia"),array("countrycode" => "TR","countryname" => "Turkey"),array("countrycode" => "TM","countryname" => "Turkmenistan"),array("countrycode" => "TC","countryname" => "Turks & Caicos Islands"),array("countrycode" => "TV","countryname" => "Tuvalu"),array("countrycode" => "UM","countryname" => "U.S. Outlying Islands"),array("countrycode" => "VI","countryname" => "U.S. Virgin Islands"),array("countrycode" => "UG","countryname" => "Uganda"),array("countrycode" => "UA","countryname" => "Ukraine"),array("countrycode" => "AE","countryname" => "United Arab Emirates"),array("countrycode" => "GB","countryname" => "United Kingdom"),array("countrycode" => "US","countryname" => "United States"),array("countrycode" => "UY","countryname" => "Uruguay"),array("countrycode" => "UZ","countryname" => "Uzbekistan"),array("countrycode" => "VU","countryname" => "Vanuatu"),array("countrycode" => "VA","countryname" => "Vatican City"),array("countrycode" => "VE","countryname" => "Venezuela"),array("countrycode" => "VN","countryname" => "Vietnam"),array("countrycode" => "WF","countryname" => "Wallis & Futuna"),array("countrycode" => "EH","countryname" => "Western Sahara"),array("countrycode" => "YE","countryname" => "Yemen"),array("countrycode" => "ZM","countryname" => "Zambia"),array("countrycode" => "ZW","countryname" => "Zimbabwe"),array("countrycode" => "AX","countryname" => "Åland Islands"));

$test_field_map_json_url = $site_storage_images_path."test_field_map_json_data/";
$test_field_map_json_path = $site_storage_images_url."test_field_map_json_data/";

$site_api_url = $site_url."api/v2/";

/*Access Group ID of super/administrator*/
$Access_Group_SuperAdmin= array('1','2');
?>
