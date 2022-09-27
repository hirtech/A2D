<?
umask(0);

#
# If we are in subdir of X-Cart dir, then include with '../'
# else include with './'
#
if (!@include("./config/smarty/Smarty.class.php"))
	@include("./config/Smarty.class.php");


#
# Get absolute path
#
define('BASEDIR', realpath(dirname(__FILE__)));

#
# Smarty object for processing html templates
#
$smarty = new Smarty;

#
# Store all compiled templates to the single directory
#
$smarty->use_sub_dirs = false;
$smarty->template_dir = BASEDIR.DIRECTORY_SEPARATOR."views";
$smarty->compile_dir = BASEDIR.DIRECTORY_SEPARATOR."views_cache";
$smarty->config_dir = BASEDIR.DIRECTORY_SEPARATOR."views";
$smarty->cache_dir = BASEDIR.DIRECTORY_SEPARATOR."cache";
$smarty->secure_dir = BASEDIR.DIRECTORY_SEPARATOR."views";

//$smarty->secure_dir = BASEDIR.DIRECTORY_SEPARATOR."templates";
//$smarty->debug_tpl="file:debug_templates.tpl";

if( !is_dir($smarty->compile_dir) && !file_exists($smarty->compile_dir) )
	@mkdir($smarty->compile_dir);

if( !is_writable($smarty->compile_dir) || !is_dir($smarty->compile_dir) ){
	echo "Can't write template cache in the directory: <b>".$smarty->compile_dir."</b>.<br>Please check if it exists, and have writable permissions.";
	exit;
}

$file_temp_dir=$smarty->compile_dir;

$smarty->assign("ImagesDir",BASEDIR.DIRECTORY_SEPARATOR."images");
$smarty->assign("CategoryDir",BASEDIR.DIRECTORY_SEPARATOR."cat_images");
$smarty->assign("BannerDir",BASEDIR.DIRECTORY_SEPARATOR."banner");

$smarty->assign("SkinDir","../style");

#
# Smarty object for processing mail templates
#
$mail_smarty = $smarty;

#
# WARNING :
# Please ensure that you have no whitespaces / empty lines below this message.
# Adding a whitespace or an empty line below this line will cause a PHP error.
#
?>