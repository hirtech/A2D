<?
include_once($class_path."Global/Imagecrop.class.php");
function img_checkURL($tPageContent)
{
	global $site_url;
	$tPageContent=str_replace('images/',$site_url."images/",$tPageContent);
	return $tPageContent;
}

function img_replaceURL($tPageContent)
{
	global $site_url;
	$tPageContent=str_replace($site_url."images/",'images/',$tPageContent);
	return $tPageContent;
}

function img_getImageWithRatio($act_width, $act_height,$width=200,$height=100){
	$imagehw_a[0] = $width;
	$imagehw_a[1] = $height;
	if($act_height > $imagehw_a[1] and $act_width > $imagehw_a[0]){
		$r1 = $act_width/$imagehw_a[0];
		$r2 = $act_height/$imagehw_a[1];

		if($r2 >= $r1){
			$ratio = (100*$imagehw_a[1]) / $act_height;
			$imagehw_a[0] = ($act_width * $ratio) / 100;
		}
		else{
			$ratio = (100*$imagehw_a[0]) / $act_width;
			$imagehw_a[1] = ($act_height * $ratio) / 100;
		}
	}
	else if($act_height > $imagehw_a[1] and $act_width < $imagehw_a[0]){
		$ratio = (100*$imagehw_a[1]) / $act_height;
		$imagehw_a[0] = ($act_width * $ratio) / 100;
	}
	else if($act_height < $imagehw_a[1] and $act_width > $imagehw_a[0]){
		$ratio = (100*$imagehw_a[0]) / $act_width;
		$imagehw_a[1] = ($act_height * $ratio) / 100;
	}
	else if($act_height < $imagehw_a[1] and $act_width < $imagehw_a[0]){
		$imagehw_a[0] = $act_width;
		$imagehw_a[1] = $act_height;
	}
	$imagehw_a[0] = intval($imagehw_a[0]);
	$imagehw_a[1] = intval($imagehw_a[1]);
	//echo print_r($imagehw_a);
	return $imagehw_a;
}

function img_imageUploadResize($img_name, $company_logo_path, $company_logo_size, $fix="no"){
	global $temp_gallery, $_FILES;
	//print_r($_FILES);
	//exit;
	$vImage_name = $_FILES[$img_name]['name'];
	$vImage = "";
	$temp_msg = "";
	if($vImage_name != "")
 	{
		$tmp = explode(".",$vImage_name);
		$ext = $tmp[count($tmp)-1];
		$valid_ext = array('jpg','jpeg','gif','png');
		if(in_array(strtolower($ext), $valid_ext))
		{
			$time = time() + sprintf("%06d",(microtime(true) - floor(microtime(true))) * 1000000);
			$time_val = $time."_";
			//echo "<br>".$_FILES[$img_name][tmp_name]," ** ",$temp_gallery.$vImage_name;exit;
			copy($_FILES[$img_name][tmp_name],$temp_gallery.$vImage_name);
			$thumb=new Global_Imagecrop($temp_gallery."/".$vImage_name);		// generate image_file, set filename to resize/resample
			for($cc=0 ; $cc<count($company_logo_size) ; $cc++)
			{
				$t_w = $company_logo_size[$cc]["W"];
				$t_h = $company_logo_size[$cc]["H"];
				//echo "<br>".$t_w." x ". $t_h;
				//echo "<br>Image-1: ".$company_logo_path."1_".$time_val.$vImage_name;
				$f_name = ($cc+1)."_".$time_val.$vImage_name;
				
				if($t_w=='' && $t_h=='')
				{
					copy($temp_gallery.$vImage_name, $company_logo_path.$f_name);
				}
				else
				{
					$thumb->size_height($t_h, $fix);						// set height for thumbnail, or
					$thumb->size_width($t_w, $fix);						// set width for thumbnail, or
					//$thumb->size_auto($company_logo_size1);		// set the biggest width or height for thumbnail
					$thumb->jpeg_quality(100);						// [OPTIONAL] set quality for jpeg only (0 - 100) (worst - best), default = 75
					
					$thumb->save($company_logo_path.$f_name);
				}
				if(file_exists($company_logo_path.$f_name)) { 
					@chmod($company_logo_path.$f_name, 0777);
					$msg="Image Uploaded Successfully.";
					$temp_msg .= "<hr />".$msg;
					//$del_filename = ($cc+1)."_".$_POST[$img_name.'_old'];
					$del_filename = ($cc+1)."_".$_POST[$img_name.'_old'];
					if($_POST[$img_name.'_old'] != '' && file_exists($company_logo_path.$del_filename))
						@unlink($company_logo_path.$del_filename);
				}
				else {
					$msg="Image Not Uploaded Successfully.";
					$temp_msg .= "<hr />".$msg;
				}
			}
			$vImage=$time_val.$vImage_name;
			@unlink($temp_gallery."/".$vImage_name);
			//echo "<br>".$vImage;
		}else{
			$vImage = '';
			$msg="Image Type Is Not Valid.";
			$temp_msg .= "<hr />".$msg;
		}
	}
	//echo $temp_msg;exit;
	//exit;
//	return $vImage;
	$ret[0] = $vImage;
	$ret[1] = $msg;
	return $ret;
}
/*function img_fileUpload($img_name, $photo_path, $prefix, $valid_ext = array('txt','doc','docx','pdf','jpg','jpeg','gif')){
//echo $_FILES[$img_name][tmp_name];exit;
	//echo $photo_path;
	//print_r($_FILES);exit;
	$vImage_name = $_FILES[$img_name][name];
	$vImage = "";
	if($vImage_name != "")
 	{
		$time_val = time()."_";
		$tmp = explode(".",$vImage_name);
		$ext = $tmp[count($tmp)-1];
		if(in_array(strtolower($ext), $valid_ext))
		{
			//echo $ext;exit;
			$vphotofile = $prefix.$time_val.$vImage_name;
			$ftppath1 = $photo_path.$vphotofile;
			
			if(!copy($_FILES[$img_name][tmp_name], $ftppath1))
			{
				$vphotofile = '';
				$msg="File Not Uploaded Successfully.";
			}
			else
			{
				@chmod($ftppath1, 0777);
				$msg="File Uploaded Successfully.";
			}
			
			//Delete old file if already exists	
			if($_POST[$img_name.'_old'] != ''  && file_exists($photo_path.$_POST[$img_name.'_old']))
			{
				//echo "2333";exit;
				@unlink($photo_path.$_POST[$img_name.'_old']);
			}
			
		}
		else
		{
			$msg="File Not Uploaded Successfully.Please Check the file type.";
		}
	}
	
	$ret[0] = $vphotofile;
	$ret[1] = $msg;
	//print_r ($ret);exit;
	return $ret;
}*/
function img_fileUpload($img_name, $photo_path, $prefix, $valid_ext = array('txt','doc','docx','pdf','jpg','jpeg','gif','mov', 'avi','wmv','mp4','m4v','MOV', 'AVI','WMV','MP4','log', 'csv')){
	//echo $_FILES[$img_name][tmp_name];exit;
	//echo $photo_path;
	//echo "<pre>";print_r($_FILES);exit;
	$vImage_name = $_FILES[$img_name][name];
	$vImage = "";
	$vphotofile = "";
	if($vImage_name != "")
 	{
		$time_val = time()."_";
		$tmp = explode(".",$vImage_name);
		$ext = $tmp[count($tmp)-1];
		//echo "<pre>";print_r($valid_ext);exit;
		if(in_array(strtolower($ext), $valid_ext))
		{
			//echo $ext;exit;
			$vphotofile = $prefix.$time_val.str_replace(" ","_",$vImage_name);
			$ftppath1 = $photo_path.$vphotofile;
			//echo $ftppath1;exit;
			//echo $ftppath1;exit;
			if(!copy($_FILES[$img_name]['tmp_name'], $ftppath1))
			{
				$vphotofile = '';
				$msg="File Not Uploaded Successfully.";
			}
			else
			{
				@chmod($ftppath1, 0777);
				//@chmod($company_logo_path.$f_name, 0777);
				//$msg="Image Uploaded Successfully.";
				$del_filename = $_POST[$img_name.'_old'];
				if($_POST[$img_name.'_old'] != '' && file_exists($photo_path.$del_filename))
					@unlink($photo_path.$del_filename);
				$msg="File Uploaded Successfully.";
			}
		}
		else
		{
			//$msg="File Not Uploaded Successfully.Please Check the file type.";
			$msg="File Not Uploaded Successfully. Please upload ".implode(',',$valid_ext)." file.";
		}
	}else{
		$msg="File Not Uploaded.Please Check it.";
	}
	
	$ret[0] = $vphotofile;
	$ret[1] = $msg;
	//print_r ($ret);exit;
	return $ret;
}
#=======================================================================
# IMAGE MAGIC function img_: made only one image create
#=======================================================================
function img_importOne() {
	Global $gallery_assets_path,$iGalleryId,$vFile;
	Global $site_path, $useimagemagick, $vSize;
	Global $obj, $_POST, $imagemagickinstalldir, $temp_gallery,$target_dir ;
	Global $THUMB_IMAGE_SIZE, $LARGE_IMAGE_SIZE, $MEDIUM_IMAGE_SIZE;
	$thumb = explode("X",$THUMB_IMAGE_SIZE);
	$large = explode("X",$LARGE_IMAGE_SIZE);
	$medium = explode("X",$MEDIUM_IMAGE_SIZE);

	include_once ("../config/imagemagick.class.php");
	$count = 0;
	$files=$vFile;
	foreach ($files as $file)
	{
		$idx ++;
		if($useimagemagick == "Yes")
		{
				$imObj = new ImageMagick($imagemagickinstalldir,$temp_gallery );
				$imObj->loadByFilePath($file);
				$imObj -> setVerbose(FALSE);
				$imObj -> setTargetdir($target_dir);
				$size = $imObj->GetSize();
		}
		else
		{
			$size = GetImageSize($file);
		}
		if(count($vSize) == "1")
		{
			$size = GetImageSize($file);
			$vSize[0] = $size[0];
			$vSize[1] = $size[1];
		}
		if ($size[0] > $size[1])
		{
			//Landscape
			if ($_POST['l_orig_l'])
			{
				$l_width = $vSize[0];
				$l_height = $vSize[1];
			} else
			{
				$l_width 	= $vSize[0];
				$l_height 	= $vSize[1];
			}
			$aspect_1 	= $_POST['l_aspect_p'];
		}
		else
		{
			//Portrait
			if ($_POST['l_orig_p'])
			{
				$l_width = $vSize[0];
				$l_height = $vSize[1];
			}
			else
			{
				$l_width 	= $vSize[0];
				$l_height 	= $vSize[1];
			}
			$aspect_1 	= $_POST['l_aspect_p'];
		}
		//echo $l_width;exit;
		if($useimagemagick == "Yes")
		{
			$time=time();
			$imObj -> Resize($l_width, $l_height, $aspect_1);
			list($l_width, $l_height) = $imObj->GetSize();
			$filename = $imObj -> Save("1_$time");
			$filelocation = $targetdir . "/" . substr($filename, 2);
			$imObj -> CleanUp();
		}
		else
		{
			$filename = $targetdir."/1_".basename($file);
			copy($file, $filename);
			$filename = $targetdir."/2_".basename($file);
			copy($file, $filename);
			$filelocation = $targetdir . "/" . basename($file);
		}

		$Title 	= basename($filelocation);
		$FileLocation 	= $filelocation;

		$LargeWidth	= $l_width;
		$LargeHeight	= $l_height;
	}
	print("Finished Processing: $idx files");
	return $Title;
}

#=========================================================================================
# Image Upload function img_Using ImageMagic
#=========================================================================================
function img_import() {
	Global $gallery_assets_path,$iGalleryId,$vFile;
	Global $site_path, $useimagemagick;
	Global $obj, $_POST, $imagemagickinstalldir, $temp_gallery,$target_dir ;
	include_once ("../config/imagemagick.class.php");
	$count = 0;

	$files=$vFile;
	$idx = 0;
	foreach ($files as $file) {
		$idx ++;
		if($useimagemagick == "Yes")
		{
				$imObj = new ImageMagick($imagemagickinstalldir,$temp_gallery );
				$imObj->loadByFilePath($file);
				$imObj -> setVerbose(FALSE);
				$imObj -> setTargetdir($target_dir);
				$size = $imObj->GetSize();
		}
		else
		{
			$size = GetImageSize($file);
		}
		if ($size[0] > $size[1]) {
			//Landscape
			if ($_POST['l_orig_l']) {
				$l_width = $size[0];
				$l_height = $size[1];
			} else {
				$l_width 	= "250";
				$l_height 	= "250";
			}

			if ($_POST['s_orig_l']) {
				$s_width = $size[0];
				$s_height = $size[1];
			} else {
				$s_width 	= "200";
				$s_height 	= "200";
			}
			if ($_POST['t_orig_l']) {
				$t_width = $size[0];
				$t_height = $size[1];
			} else {
				$t_width 	= "130";
				$t_height 	= "130";
			}

			$aspect_3 	= "1";
			$aspect_2 	= "1";
			$aspect_1 	= "1";
		} else {
			//Portrait
			if ($_POST['l_orig_p']) {
				$l_width = $size[0];
				$l_height = $size[1];
			} else {
				$l_width 	= "250";
				$l_height 	= "250";
			}

			if ($_POST['s_orig_p']) {
				$s_width = $size[0];
				$s_height = $size[1];
			} else {
				$s_width 	= "200";
				$s_height 	= "200";
			}

			if ($_POST['t_orig_p']) {
				$t_width = $size[0];
				$t_height = $size[1];
			} else {
				$t_width 	= "130";
				$t_height 	= "130";
			}

			$aspect_3 	= "1";
			$aspect_2 	= "1";
			$aspect_1 	= "1";
		}
   		$time= time();
		if($useimagemagick == "Yes")
		{
			$imObj -> Resize($l_width, $l_height, $aspect_3);
			list($l_width, $l_height) = $imObj->GetSize();
			$filename = $imObj -> Save("3_".$time);

			$imObj -> Resize($s_width, $s_height, $aspect_2);
			list($s_width, $s_height) = $imObj->GetSize();
			$filename = $imObj -> Save("2_".$time);

			$imObj -> Resize($t_width, $t_height, $aspect_1);
			list($t_width, $t_height) = $imObj->GetSize();
			$filename = $imObj -> Save("1_".$time);

			$filelocation = $targetdir . "/" . substr($filename, 2);

			$imObj -> CleanUp();
		}
		else
		{
			$filename = $targetdir."/1_".$time.basename($file);
			copy($file, $filename);
			$filename = $targetdir."/2_".$time.basename($file);
			copy($file, $filename);
			$filename = $targetdir."/3_".$time.basename($file);
			copy($file, $filename);
			$filelocation = $targetdir . "/" . basename($file);
		}

		$Title 	= basename($filelocation);

		$FileLocation 	= $filelocation;
		$ThumbWidth	= $t_width;
		$ThumbHeight	= $t_height;
		$SmallWidth	= $s_width;
		$SmallHeight	= $s_height;
		$LargeWidth	= $l_width;
		$LargeHeight	= $l_height;
	}
	print("Finished Processing: $idx files");
	return $Title;
}

/*function img_doUnlinkImages($img_name, $primaryId, $table_name, $id_arr, $img_path, $uploaded_sizes, $sizes="single")
{
	/*echo "<pre>";print_r($img_name);
	echo "<pre>";print_r($primaryId);
	echo "<pre>";print_r($table_name);
	echo "<pre>";print_r($id_arr);
	echo "<pre>";print_r($uploaded_sizes);
	echo "<pre>";print_r($sizes);
	echo "<pre>";print_r($img_path);exit;
	global $sqlObj;
	$img_delete_id = array();
	$cnt_deleted = $cnt_not_deleted = 0;
	if(empty($id_arr) || (is_array($id_arr) && count($id_arr)==0))
	{		 
		return 0;
	}
	else if(!is_array($id_arr) && !empty($id_arr))
	{
		//echo "<pre>";print_r($_REQUEST);exit;
		$img_delete_id[] = $id_arr;
	}
	else
		$img_delete_id = $id_arr;
	$sql_sel = "SELECT ".$img_name." FROM ".$table_name." WHERE ".$primaryId." IN ( ".implode(", ", $img_delete_id)." )";
	$rs_sel = $sqlObj->select($sql_sel);
	for($i = 0, $ni = count($rs_sel) ; $i < $ni ; $i++)
	{
		if($sizes == 'single')
		{
			$del_file = $img_path.$rs_sel[$i][$img_name];
			//echo $del_file;exit;
			if($del_file != '' && file_exists($del_file))
			{
				@unlink($del_file);
				$cnt_deleted++;
			}
			else
			{
				$cnt_not_deleted++;
			}
		}
		else
		{
			for($j = 1, $nj = count($uploaded_sizes) ; $j <= $nj ; $j++)
			{
				$del_file = $img_path.$j."_".$rs_sel[$i][$img_name];
				if($del_file != '' && file_exists($del_file))
				{
					@unlink($del_file);
					$cnt_deleted++;
				}
				else
				{
					$cnt_not_deleted++;
				}
			}
		}
	}
	/*$sql_up = "UPDATE ".$table_name." SET ".$img_name."='' WHERE ".$primaryId." IN ( ".implode(", ", $img_delete_id)." )";
	$rs_up = $sqlObj->execute($sql_up);
	$res = array();
	$res[0] = $cnt_deleted;
	$res[1] = $cnt_not_deleted;
	return $res;
}*/

function img_doUnlinkImages($img_name, $primaryId, $table_name, $id_arr, $img_path, $uploaded_sizes, $ext="", $sizes="single")
{
	global $sqlObj;
	$img_delete_id = array();
	$cnt_deleted = $cnt_not_deleted = 0;
	//echo "<pre>";print_r($id_arr);exit;
	if(empty($id_arr) || (is_array($id_arr) && count($id_arr)==0))
	{		 
		return 0;
	}
	else if(!is_array($id_arr) && !empty($id_arr))
	{
		//echo "<pre>";print_r($_REQUEST);exit;
		$img_delete_id[] = $id_arr;
	}
	else
		$img_delete_id = $id_arr;
	$sql_sel = "SELECT ".$img_name." FROM ".$table_name." WHERE ".$primaryId." IN ( ".implode(", ", $img_delete_id)." )";
	$rs_sel = $sqlObj->select($sql_sel);
	//echo "<pre>";print_r($rs_sel);exit;
	for($i = 0, $ni = count($rs_sel) ; $i < $ni ; $i++)
	{
		if($sizes == 'single')
		{
			$del_file = $img_path.$rs_sel[$i][$img_name];
			//echo $del_file;exit;
			if($del_file != '' && file_exists($del_file))
			{
				@unlink($del_file);
				$cnt_deleted++;
			}
			else
			{
				$cnt_not_deleted++;
			}
		}
		else
		{
			for($z=0,$id=count($id_arr);$z<$id;$z++)
			{
				for($j =1, $nj = count($uploaded_sizes); $j <= $nj; $j++)
				{
					if($ext != "")
						$del_file = $img_path.$ext."/".$j."_".$rs_sel[$i][$img_name];
					else
						$del_file = $img_path.$id_arr[$z]."/".$j."_".$rs_sel[$i][$img_name];
					if($del_file != '' && file_exists($del_file))
					{
						@unlink($del_file);
						$cnt_deleted++;
					}
					else
					{
						$cnt_not_deleted++;
					}	
				}
			}
		}
	}
	/*$sql_up = "UPDATE ".$table_name." SET ".$img_name."='' WHERE ".$primaryId." IN ( ".implode(", ", $img_delete_id)." )";
	$rs_up = $sqlObj->execute($sql_up);*/
	$res = array();
	$res[0] = $cnt_deleted;
	$res[1] = $cnt_not_deleted;
	return $res;
}

function img_doUnlinkFile($img_name, $primaryId, $table_name, $id_arr, $img_path, $uploaded_sizes, $sizes="single")
{
	global $sqlObj;
	$img_delete_id = array();
	$cnt_deleted = $cnt_not_deleted = 0;
	if(empty($id_arr) || (is_array($id_arr) && count($id_arr)==0))
	{
		 
		return 0;
	}
	else if(is_array($id_arr) && !empty($id_arr))
	{
		//echo "<pre>";print_r($_REQUEST);exit;
		$img_delete_id[] = $id_arr;
	}
	else
		$img_delete_id = $id_arr;

	$sql_sel = "SELECT ".$img_name." FROM ".$table_name." WHERE ".$primaryId." IN ( ".implode(", ", $id_arr).")"; 
	$rs_sel = $sqlObj->select($sql_sel);
	for($i = 0, $ni = count($rs_sel) ; $i < $ni ; $i++)
	{
		if($sizes == 'single')
		{
			$del_file = $img_path.$rs_sel[$i][$img_name];
			//echo $del_file;exit;
			if($del_file != '' && file_exists($del_file))
			{
				@unlink($del_file);
				$cnt_deleted++;
			}
			else
			{
				$cnt_not_deleted++;
			}
		}
		else
		{
			for($j = 1, $nj = count($uploaded_sizes) ; $j <= $nj ; $j++)
			{
				$del_file = $img_path.$j."_".$rs_sel[$i][$img_name];
				if($del_file != '' && file_exists($del_file))
				{
					@unlink($del_file);
					$cnt_deleted++;
				}
				else
				{
					$cnt_not_deleted++;
				}
			}
		}
	}
	$res = array();
	$res[0] = $cnt_deleted;
	$res[1] = $cnt_not_deleted;
	return $res;
}
function img_eventFileUpload($img_name, $photo_path, $prefix, $valid_ext = array('txt','doc','docx','pdf','zip','rar','xls','xlsx','ppt','pptx','png','jpg','jpeg','gif')) {
//echo $_FILES[$img_name][tmp_name];exit;
	//echo $photo_path;
	//echo "<pre>";print_r($_FILES);exit;
	$vImage_name1 = str_replace(" ","_",$_FILES[$img_name]['name']);
	$vImage_name2 = str_replace("(","",$vImage_name1);
	$vImage_name = str_replace(")","",$vImage_name2);
	$vImage = "";
	if($vImage_name != "")
 	{
		$time_val = time()."_";
		$tmp = explode(".",$vImage_name);
		$ext = $tmp[count($tmp)-1];
		//echo "<pre>";print_r($valid_ext);exit;
		if(in_array(strtolower($ext), $valid_ext))
		{
			//echo $ext;exit;
			$vphotofile = $prefix.$time_val.$vImage_name;

			if($_REQUEST['page'] == 'm-operations_list' || $_REQUEST['page'] == 'm-ground_larviciding')
			{
				$vphotofile = $vImage_name;
				$file_to_delete = $photo_path.substr($vphotofile, 0, -4);
				if(file_exists($file_to_delete))
				{
					@chmod($file_to_delete, 0777);
					@unlink($file_to_delete);
				}
			}

			$ftppath1 = $photo_path.$vphotofile;
			//echo $ftppath1;exit;
			if(!copy($_FILES[$img_name]['tmp_name'], $ftppath1))
			{
				$vphotofile = '';
				$msg="File Not Uploaded Successfully.";
			}
			else
			{
				@chmod($ftppath1, 0777);
				//@chmod($company_logo_path.$f_name, 0777);
				//$msg="Image Uploaded Successfully.";
				$del_filename = $_POST[$img_name.'_old'];
				if($_POST[$img_name.'_old'] != '' && file_exists($photo_path.$del_filename))
					@unlink($photo_path.$del_filename);
			$msg="File Uploaded Successfully.";
			}
		}
		else
		{
			$msg="File Not Uploaded Successfully.Please Check the file type.";
		}
	}
	$ret[0] = $vphotofile;
	$ret[1] = $msg;
	//print_r ($ret);exit;
	return $ret;
}
function img_doUnlinkEventFile($img_name, $primaryId, $table_name, $id_arr, $img_path, $uploaded_sizes, $sizes="single")
{
	global $sqlObj;
	$img_delete_id = array();
	$cnt_deleted = $cnt_not_deleted = 0;
	if(empty($id_arr) || (is_array($id_arr) && count($id_arr)==0))
	{
		 
		return 0;
	}
	else if(is_array($id_arr) && !empty($id_arr))
	{
		//echo "<pre>";print_r($_REQUEST);exit;
		$img_delete_id[] = $id_arr;
	}
	else
		$img_delete_id = $id_arr;

	$sql_sel = "SELECT ".$img_name." FROM ".$table_name." WHERE ".$primaryId." IN ( ".implode(", ", $id_arr).")"; 
	$rs_sel = $sqlObj->select($sql_sel);
	for($i = 0, $ni = count($rs_sel) ; $i < $ni ; $i++)
	{
		if($sizes == 'single')
		{
			$del_file = $img_path.$rs_sel[$i][$img_name];
			//echo $del_file;exit;
			if($del_file != '' && file_exists($del_file))
			{
				@unlink($del_file);
				$cnt_deleted++;
			}
			else
			{
				$cnt_not_deleted++;
			}
		}
		else
		{
			for($j = 1, $nj = count($uploaded_sizes) ; $j <= $nj ; $j++)
			{
				$del_file = $img_path.$j."_".$rs_sel[$i][$img_name];
				if($del_file != '' && file_exists($del_file))
				{
					@unlink($del_file);
					$cnt_deleted++;
				}
				else
				{
					$cnt_not_deleted++;
				}
			}
		}
	}
	$res = array();
	$res[0] = $cnt_deleted;
	$res[1] = $cnt_not_deleted;
	return $res;
}

function create_image_folder($iId, $image_path)
{
	$path = $image_path.$iId.'/';
	if(!is_dir($path))
	{
		@mkdir($path, 0777); // For Property
		@chmod($path, 0777);
	}
	
	return $path;
}
function get_image_folder_path($iId, $image_path)
{
	return $image_path.$iId.'/';
}
function get_image_folder_url($iId, $image_url)
{
	return $image_url.$iId.'/';
}
function do_image_folder_unlink($iId, $image_path)
{
	if(is_dir($image_path.$iId))
	{
		@unlink($image_path.$iId);
	}	
}
function img_FileUploadMultiple($img_name, $company_logo_path){
	$ret_arr = array();

	foreach($_FILES[$img_name]['tmp_name'] as $key => $tmp_name ){
		$time_val = time()."_";
		$file_name = $time_val.str_replace(" ", "_", $_FILES[$img_name]['name'][$key]);
		if($file_name != "")
		{			
			$tmp = explode(".", $file_name);
			$ext = $tmp[count($tmp)-1];
			$valid_ext = array('log','txt');
			if(in_array(strtolower($ext), $valid_ext))
			{
				$ftppath = $company_logo_path.$file_name;
				if(copy($_FILES[$img_name]['tmp_name'][$key], $ftppath)){
					$ret_arr[] = $file_name;
				}
			}
		}
	}
	return $ret_arr;
}
?>