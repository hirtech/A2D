<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
$mode = $_REQUEST['mode'];
# ----------- Access Rule Condition -----------
if($mode == "Update_Settings"){
    per_hasModuleAccess("System Settings", 'Edit');
}
else
{
    per_hasModuleAccess("System Settings", 'Add');
}
# ----------- Access Rule Condition -----------
per_hasModuleAccess("System Settings", 'List');
$access_group_var_delete = per_hasModuleAccess("System Settings", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("System Settings", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("System Settings", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("System Settings", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "treatment_product.inc.php");
$TProdObj = new TreatmentProduct();

$msg = $_REQUEST['msg'];
$keyword = $_REQUEST['keyword'];
$option = $_REQUEST['option'];
$Type = $_REQUEST['Type'];

$setting_checkbox_field = array('SESSION_STORED_IN_DB');
if($mode == "Update_Settings")
{
    //echo "<pre>";print_r($_POST);exit();
    $sql = "select \"vName\", \"vValue\", \"vDefValue\", \"vDisplayType\" from setting_mas order by \"iOrderBy\"";
    $db_setting_rs=$sqlObj->Getall($sql);
    //echo "<pre>";print_r($db_setting_rs);exit;
    $n = count($db_setting_rs);

    $update_count = 0;
    for($i=0;$i<$n;$i++)
    {   
        $field_name = $db_setting_rs[$i]["vName"];
        $vDefValue = $db_setting_rs[$i]["vDefValue"];
        if(isset($_POST[$field_name]) || in_array($field_name, $setting_checkbox_field)){
            if($db_setting_rs[$i]["vDisplayType"] == 'checkbox')
            {
                if(isset($_POST["$field_name"]) && $_POST["$field_name"] != "")
                    $vValue = "Y";
                else
                    $vValue = "N";
            }
            else if($db_setting_rs[$i]["vDisplayType"] == 'selectbox')
            {
                if(is_array($_POST["$field_name"]))
                    $vValue = implode("|",$_POST["$field_name"]);
                else
                    $vValue = $_POST["$field_name"];
                
                if($field_name == "TIME_ZONE"){
                    $vValue = $_POST["$field_name"];
                    
                    if($vValue != "")
                        file_put_contents($time_zone_path."timezone.txt", $vValue);
                    else
                        file_put_contents($time_zone_path."timezone.txt", "America/Los_Angeles");
                }
            }
            else
                $vValue = $_POST["$field_name"];

            if($vValue!="" && $vValue!="-9")
            {
                $vValue = $vValue;
            }
            else
            {
                $vValue = $vDefValue;
            }

            
            $sql = "Update setting_mas  set \"vValue\" = '$vValue' where \"vName\" = '$field_name'";
            //echo $sql."<br>";exit();
            $db_update = $sqlObj->Execute($sql); 
            
            
            if($db_update)
            {   

                $update_count+=1;
            }
        }
    }
   
    if($update_count != 0)
    {   
      $msg= "Site Settings Value Updated Succesfully";
        header("Location:setting_list?msg=$msg");
        exit;
    }
    else
    {
        $msg= "Site Settings Value Not Updated Succesfully";
        header("Location:setting_list?msg=$msg");
        exit;
    }
}

$ssql = " where \"bStatus\"='1'";
if(!isset($_REQUEST['Type']) || $_REQUEST['Type']== '') $_REQUEST['Type'] = 'Appearance';
//$ssql .= " AND vConfigType = '".$_REQUEST['Type']."'";

if(isset($_REQUEST['keyword']))
    $ssql.=" AND \"".$_REQUEST['option']."\" like '%".$_REQUEST['keyword']."%'";

$sql="select count(*) as tot from setting_mas $ssql";
$db_res_cnt=$sqlObj->Getall($sql);  
$num_totrec = $db_res_cnt[0]["tot"];
$rec_limit= "50";

$sort =" order by \"vConfigType\", \"iOrderBy\"";
$db_query = "select * from setting_mas  ".$ssql.$sort;
$setting_data = $sqlObj->Getall($db_query);

$db_res = array();

$module_name='Site Settings List';
if($mode == '')
    $mode = 'Search';
    

$ConfigType_Arr = array('General','Appearance','Email','Meta Tag Informatioin','SMS');

for($i=0 ; $i<$num_totrec ; $i++)
{   
	if($setting_data[$i]['vSelectType'] == 'Single'){
		if($setting_data[$i]["vSourceValue"] != ''){
			
			$Source_Arr = explode(",",$setting_data[$i]["vSourceValue"]); 
			
			$nSource_List = count($Source_Arr);
			
			$list_arr = array();
			for($j=0;$j<$nSource_List;$j++){
				$list_arr[] = explode("::",$Source_Arr[$j]);
			}
			
			$setting_data[$i]['vSourceValue'] = $list_arr;
		}
	}
	else if($setting_data[$i]['vSelectType'] == 'Multiple'){
		$vValue_arr = explode("|",$setting_data[$i]["vValue"]);   
	}   
	else if($setting_data[$i]["vSource"] == 'Query'){
		$db_selectSource_rs = $sqlObj->select($setting_data[$i]["vSourceValue"]);
		$nSource_Query = count($db_selectSource_rs);
	}

	$db_res[] = $setting_data[$i];

}

//echo "<pre>";print_r($db_res);exit();

$smarty->assign("module_name", $module_name);
$smarty->assign("Type", $Type);
$smarty->assign("db_res", $db_res);
$smarty->assign("cnt", count($db_res));
$smarty->assign("ConfigType_Arr", $ConfigType_Arr);
$smarty->assign("keyword", $keyword);
$smarty->assign("option", $option);
$smarty->assign("num_totrec", $num_totrec);
$smarty->assign("list_arr", $list_arr);
$smarty->assign("nSource_List", $nSource_List);
$smarty->assign("db_selectSource_rs", $db_selectSource_rs);
$smarty->assign("vValue_arr", $vValue_arr);
$smarty->assign("msg", $msg);
?>



