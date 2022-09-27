<?php
include_once("shpParser.php");

$shpParsercls = new shpParser();

$file_path = "DowntownDT1.shp";
$load_shap_file = $shpParsercls->load($file_path);

echo "<pre>";
print_r($shpParsercls);
?>
