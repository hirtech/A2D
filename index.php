<?
include_once("server.php");
$module = $_REQUEST['module'];  // Module Name
$page = $_REQUEST['page'];	  // Page Name
$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

//echo"<pre>";print_r($_REQUEST);exit;
//echo "<pre>";print_r($_SESSION);exit;
#-------------------------------------------------------------------------
# SITE UNDER MAINTANANCE BLOCK
#-------------------------------------------------------------------------
if ((isset($_REQUEST['dev']) && $_REQUEST['dev']=="1") || ($_SESSION['dev']=="1"))
	$_SESSION['dev']="1";
else if ($_SESSION[dev]=="")
	$_SESSION['dev']="0";

if ($SITE_MAINTANANCE=='Y' || $SITE_MAINTANANCE=='yes' ) {
	 if ($_SESSION['dev']!=1 && $_SESSION['dev']!="")  {
		header("location:".$site_url."unavailable.php");
		exit;
	 }else{}
}


include_once("includes/include.php");

#-------------------------------------------------------------------------
# Following code finds out the modules from sufiix and find out the script name
#------------------------------------------------------------------------
if(isset($module)){
	$var = explode("/",$module);
	$module_prefix = $var[0]."/";
	
	$page_module = $var[0];
}
if(isset($page)){
	$var=explode("-",$page);
	$prefix=$var[0];
	$script=$var[1];
	$middle = "middle";
}else{
	$page="c-home";
	$prefix="c";
	$script="home";
	$modid=1;
	$middle = "middle";
}
if($script=="")
{
	$script="home";
	$modid=1;
	$middle = "middle";
}


//echo "<pre>";print_r($_SESSION);exit;
include_once("cookie_login.php");

switch ($prefix){
	case "m":
		$module = "scripts";
		break;
	default:
		$module = "scripts";
		break;
}

 $include_script = $module."/".$module_prefix.$script.".php";
//echo "<pre>";print_r($include_script);exit;
if(file_exists($include_script)){
	//include_once($include_script) or die('error');
	include_once($include_script);
	
}else{
	include_once("scripts/404.php");
}

//echo "<pre>";print_r($_SESSION);exit;

if($_SESSION["sess_iUserId".$admin_panel_session_suffix]!= '') {
	$smarty->assign("sess_iUserId", $_SESSION["sess_iUserId".$admin_panel_session_suffix]);
	$smarty->assign("sess_vName", $_SESSION["sess_vName".$admin_panel_session_suffix]);
	$smarty->assign("sess_vAccessGroup", $_SESSION["sess_vAccessGroup".$admin_panel_session_suffix]);
	$smarty->assign("sess_vCompanyName", $_SESSION["sess_vCompanyName".$admin_panel_session_suffix]);
	$smarty->assign("sess_iAGroupId", $_SESSION["sess_iAGroupId".$admin_panel_session_suffix]);
	$smarty->assign("sess_vImage", $_SESSION["sess_vImage".$admin_panel_session_suffix]);
	$smarty->assign("sess_vImage_url", $_SESSION["sess_vImage_url".$admin_panel_session_suffix]);
	$smarty->assign("host_name", $_SERVER["HTTP_HOST"]);
	$smarty->assign("REMOTE_ADDR", $REMOTE_ADDR);
	$smarty->assign("currenttime",  date("F d, Y H:i:s", time()));
	$smarty->assign("sess_vCountyName", $_SESSION["sess_vCountyName".$admin_panel_session_suffix]);
	$smarty->assign("sess_vCountyCode", $_SESSION["sess_vCountyCode".$admin_panel_session_suffix]);
	$smarty->assign("sess_iCountySaasId", $_SESSION["sess_iCountySaasId".$admin_panel_session_suffix]);
	$mod_access_arr = per_getAssocArray($_SESSION["sess_iAGroupId".$admin_panel_session_suffix],'');
	$smarty->assign('sess_ShowImport',$_SESSION["sess_ShowImport".$admin_panel_session_suffix]);
	$smarty->assign('sess_GoogleMapCountryCode',$_SESSION["sess_vGoogleMapCountryCode".$admin_panel_session_suffix]);

}
//echo "<pre>";print_r($_SESSION);exit;
# ----------- Access Rule Condition -----------

$acces_mod_arr = array();
$mod_search_arr = array(" ", "/");
$mod_replace_arr = array("_", "_");
foreach($mod_access_arr as $key=>$value){
	$acces_mod_arr[str_replace($mod_search_arr, $mod_replace_arr, $key)] = $value;
}

$smarty->assign("acces_mod_arr",  $acces_mod_arr);
# ----------- Access Rule Condition -----------

#------------------------Date Pickers Date ----------------------
$smarty->assign("js_date_picker_date",  date("y-m-d"));
#------------------------Date Pickers Date ----------------------

//echo "<pre>111111111";print_r($_SESSION);exit;
//echo $COMPANY_COPYRIGHTS;exit();
$smarty_array[] = '';
$smarty_array = array_merge($smarty_array, array("SUPPORT_EMAIL", "SHOW_SAVING", "banner_url", "page_title","file", "msgs", "site_images", "site_style", "script", "file", "top_banner", "middle", "site_url", "module","module_prefix", "var_msg", "sslash", "aslash","hentites", "adminfolder", "page", "section", "stylesheet_name", "admin_url", "SITE_TITLE", "site_images_url","META_KEYWORD", "META_DESCRIPTION", "LOGO_ALT_TEXT", "META_OTHER","prefix", "REC_LIMIT","access_group_var_delete","access_group_var_status","access_group_var_add","access_group_var_edit", "access_group_var_PDF", "access_group_var_CSV","access_group_var_Respond", "page_module", "access_group_var_Calsurv", "device_detect", "user_url", "user_path", "site_api_url"));

for($k=0;$k<count($smarty_array);$k++)
{
	$smarty->assign($smarty_array[$k], @${$smarty_array[$k]});
}

$smarty->display("template.tpl");

unset($smarty);
?>