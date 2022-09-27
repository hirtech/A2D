<?
	include_once("config.php");
	$vFileName_path=base64_decode($_GET['vFileName_path']);
	$vFileName_url=base64_decode($_GET['vFileName_url']);
	//ob_clean_all();
	ob_clean();
	# -------------------------------------
	header("Pragma: public");
	header("Expires: 0"); // set expiration time
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	//header("Content-Disposition: attachment; filename=".substr(basename($vFileName_path),11).";");
	header("Content-Disposition: attachment; filename=".basename($vFileName_path).";");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($vFileName_path));
	
	@readfile($vFileName_path);
	exit(0);
	# --------------------------------------
?>