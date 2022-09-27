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


$instaTreatment_Arr = array('INSTA_TREATMENT_PRODUCT_ID','INSTA_TREATMENT_AREA','INSTA_TREATMENT_AREA_TREATED','INSTA_TREATMENT_AMOUNT_APPLIED','INSTA_TREATMENT_UNIT_ID');

$enable_insta_treatment = 'N';
$setting_checkbox_field = array('SESSION_STORED_IN_DB','ENABLE_INSTA_TREATMENT');
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

            if($field_name == 'CALSURV_GATEWAY_LAB_DATA') {
                $vValue = 'D';
            }

            if($field_name == 'ENABLE_INSTA_TREATMENT') {
                $enable_insta_treatment = $vValue;
            }
            
            $vValue = (in_array($field_name,$instaTreatment_Arr) && $enable_insta_treatment== 'N')?'':$vValue;

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
else if($mode=="UpdateInsta_Treat_Setting"){

    //echo "<pre>";print_r($_POST);exit();
    $result = array();

    $instatreatmnet_pid = $_POST['serach_iTPId_treatment'];
    $instatreatmnet_area = $_POST['vArea_treatment'];
    $instatreatmnet_area_treated = $_POST['vAreaTreated_treatment'];
    $instatreatmnet_amount_applied = $_POST['vAmountApplied_treatment'];
    $instatreatmnet_unit_id = $_POST['iUId_treatment'];

    $sql = "Update setting_mas  set \"vValue\" = '".$instatreatmnet_pid."' where \"vName\" = 'INSTA_TREATMENT_PRODUCT_ID'";
    $db_update = $sqlObj->Execute($sql);

    $sql = "Update setting_mas  set \"vValue\" = '".$instatreatmnet_area."' where \"vName\" = 'INSTA_TREATMENT_AREA'";
    $db_update = $sqlObj->Execute($sql);

    $sql = "Update setting_mas  set \"vValue\" = '".$instatreatmnet_area_treated."' where \"vName\" = 'INSTA_TREATMENT_AREA_TREATED'";
    $db_update = $sqlObj->Execute($sql);

    $sql = "Update setting_mas  set \"vValue\" = '".$instatreatmnet_amount_applied."' where \"vName\" = 'INSTA_TREATMENT_AMOUNT_APPLIED'";
    $db_update = $sqlObj->Execute($sql);

    $sql = "Update setting_mas  set \"vValue\" = '".$instatreatmnet_unit_id."' where \"vName\" = 'INSTA_TREATMENT_UNIT_ID'";
    $db_update = $sqlObj->Execute($sql);

    if($db_update){
        $result['error']= 0 ;
        $result['data']= $_POST ;
    }else{
        $result['error']= 1 ;
    }

    echo json_encode($result);
    hc_exit();
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
$tmpinsta_db_res = array();

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

	if($setting_data[$i]['vName'] == 'ENABLE_INSTA_TREATMENT') {
			$enable_insta_treatment = $setting_data[$i]['vValue'];
		}
	if(in_array($setting_data[$i]['vName'], $instaTreatment_Arr)){
		$tmpinsta_db_res[$setting_data[$i]['vName']] = $setting_data[$i]['vValue'];
	}

}

if(isset($tmpinsta_db_res['INSTA_TREATMENT_PRODUCT_ID']) && $tmpinsta_db_res['INSTA_TREATMENT_PRODUCT_ID'] != ""){
            $TProdObj->clear_variable();

            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $join_fieds_arr[] = 'unit_mas."vUnit"';
            $where_arr[] = 'treatment_product."iTPId" = '.$tmpinsta_db_res['INSTA_TREATMENT_PRODUCT_ID'].'';
            $join_arr[] = 'LEFT JOIN unit_mas  on unit_mas."iUId" = treatment_product."iUId"';
            $TProdObj->join_field = $join_fieds_arr;
            $TProdObj->join = $join_arr;
            $TProdObj->where = $where_arr;
            $TProdObj->param['limit'] = "LIMIT 1";
            $TProdObj->param['order_by'] = 'treatment_product."iTPId" DESC';
            $TProdObj->setClause();
            $rs_trtproduct = $TProdObj->recordset_list();

            $appRate = (isset($rs_trtproduct[0]['vAppRate']))?$rs_trtproduct[0]['vAppRate']:"";
            $minRate = (isset($rs_trtproduct[0]['vMinAppRate']))?"min ".$rs_trtproduct[0]['vMinAppRate']:"";
            $maxRate = (isset($rs_trtproduct[0]['vMaxAppRate']))?"- max ".$rs_trtproduct[0]['vMaxAppRate']:"";
            $tragetappRate = (isset($rs_trtproduct[0]['vTragetAppRate']))?$rs_trtproduct[0]['vTragetAppRate']:"";
            $unitName = (isset($rs_trtproduct[0]['vUnit']))?$rs_trtproduct[0]['vUnit']:"";

            $appRate = $appRate . "(".$minRate.$maxRate.")".$unitName."/".$tragetappRate;


            $treatment_product = $rs_trtproduct[0]['vName'];

            $tmpinsta_db_res['treatment_product'] = $treatment_product;
            $tmpinsta_db_res['appRate'] = $appRate;

            $smarty->assign('insta_treatment_productname',$treatment_product);
            $smarty->assign('insta_appRate',$appRate);
}
if(isset($tmpinsta_db_res['INSTA_TREATMENT_UNIT_ID']) && $tmpinsta_db_res['INSTA_TREATMENT_UNIT_ID'] != ""){
    $sql_unit = "SELECT \"vUnit\",\"iParentId\"  FROM unit_mas WHERE \"iUId\" = '".$tmpinsta_db_res['INSTA_TREATMENT_UNIT_ID']."' LIMIT 1";
    $rs_unit = $sqlObj->Getall($sql_unit);

    $unit_parentid = $rs_unit[0]['iParentId'];

    $tmpinsta_db_res['unit_parentid'] = $unit_parentid;
    $tmpinsta_db_res['unit_name'] = $rs_unit[0]['vUnit'];

    $smarty->assign('insta_unit_parentid',$unit_parentid);
}

if($enable_insta_treatment == 'Y'){
    $insta_table = "<table width='100%' class='table'>
                                    <tr>
                                        <td>Insta Treatment product</td>
                                        <td>".$tmpinsta_db_res['treatment_product']."</td>
                                    </tr>
                                     <tr>
                                        <td>Insta Treatment Area Treated</td>
                                        <td>".$tmpinsta_db_res['INSTA_TREATMENT_AREA']." ".$tmpinsta_db_res['INSTA_TREATMENT_AREA_TREATED']."</td>
                                    </tr>
                                    <tr>
                                        <td>Insta Treatment Amount Applied</td>
                                        <td>".$tmpinsta_db_res['INSTA_TREATMENT_AMOUNT_APPLIED']." ".$tmpinsta_db_res['unit_name']."</td>
                                    </tr>
                            </table>";
    $smarty->assign('insta_data_table',$insta_table);
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
$smarty->assign("tmpinsta_db_res", $tmpinsta_db_res);
?>



