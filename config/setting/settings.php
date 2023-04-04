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

$user_path = $site_storage_images_path."user/";
$user_url = $site_storage_images_url."user/";

$download_path = $site_storage_images_path."download/";
$download_url = $site_storage_images_url."download/";

$cache_file_path = $site_storage_images_path."CacheFiles/";
$cache_file_url = $site_storage_images_url."CacheFiles/";

$premise_documents_path = $site_storage_images_path."premise_documents/";
$premise_documents_url = $site_storage_images_url."premise_documents/";

$time_zone_path = $site_storage_images_path."time_zone/";

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
	'Not Started'=> 'info',
	'In Progress'=> 'primary',
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

$panel_default_customizer = array(
	"template_color" => "#3e8ef7",
	"template_layout" => "vertical",
	"template_style" => "light",
	"bCompactMenu" => "FALSE",
	"bsmallMenu" => "TRUE"
);

$premise_type_icon_path = $site_storage_images_path."premise_type_icon/";
$premise_type_icon_url = $site_storage_images_url."premise_type_icon/";

$field_map_json_path = $site_storage_images_path."field_map_json_data/";
$field_map_json_url = $site_storage_images_url."field_map_json_data/";

#Custom layer kml file
$custom_layer_path = $site_storage_images_path."kml/";
$custom_layer_url = $site_storage_images_url."kml/";

#Network kml file
$network_path = $site_storage_images_path."netowrk_kml/";
$network_url = $site_storage_images_url."netowrk_kml/";

#Service Pricing file
$service_pricing_path = $site_storage_images_path."service_pricing/";
$service_pricing_url = $site_storage_images_url."service_pricing/";

#Service Order file
$service_order_path = $site_storage_images_path."service_order/";
$service_order_url = $site_storage_images_url."service_order/";

#Zone
$zone_path = $site_storage_images_path."zone/";
$zone_url = $site_storage_images_url."zone/";

#Circuit
$circuit_path = $site_storage_images_path."circuit/";
$circuit_url = $site_storage_images_url."circuit/";

#Premise Circuit
$premise_circuit_path = $site_storage_images_path."premise_circuit/";
$premise_circuit_url = $site_storage_images_url."premise_circuit/";

#Equipment
$equipment_path = $site_storage_images_path."equipment/";
$equipment_url = $site_storage_images_url."equipment/";


$site_api_url = $site_url."api/v2/";

/*Access Group ID of super/administrator*/
$Access_Group_SuperAdmin= array('1','2');

$EVENT_CAMPAIGN_BY_ARR = array("1" => "Premise", "2" => "Fiber Zone", "3" => "Zip Code", "4" => "City", "5" => "Network");

$site_logo = $site_url."assets/images/logo.png";
$pdf_file_path = $class_path."tcpdf/";

$SALES_ACCESS_TYPE_ID = 6;
$TECHNICIAN_ACCESS_TYPE_ID = 1;
$CARRIER_ACCESS_TYPE_ID = 10;

$notification_class_arr = array(
	"FiberInquiry" => array('icon' => 'fa fa-question-circle' , "color" => 'text-success'),
	"Serviceorder" => array('icon' => 'fa fa-truck' , "color" => 'text-info'),
	"Workorder" => array('icon' => 'fas fa-truck-loading' , "color" => 'text-danger'),
	"TroubleTicket" => array('icon' => 'fa fa-list-alt' , "color" => 'text-primary'),
	"MaintenanceTicket" => array('icon' => 'fas fa-clipboard-list' , "color" => 'text-secondary'),
);

$A2D_COMPANY_ID = 1;
?>
