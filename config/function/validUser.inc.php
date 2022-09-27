<?
function per_hasModuleAccess($mod_name, $mod_access_name='', $redirect='')
{
	global $mod_access_arr, $admin_panel_session_suffix, $site_url;
	if(!is_array($mod_access_arr[$mod_name]))
	{
		//$mod_access_arr = array_merge($mod_access_arr, per_getAssocArray($_SESSION["sess_iAGroupId".$admin_panel_session_suffix], "'$mod_name'"));
		
		$mod_access_arr = per_getAssocArray($_SESSION["sess_iAGroupId".$admin_panel_session_suffix], "'$mod_name'");
	}
	$mod_arr = $mod_access_arr[$mod_name];
	
	//print_r($mod_access_arr );exit;
	
	if($mod_access_name=='List'){
		  if($mod_arr['eList']=='Y')
		 	return 1;
	}
	else if($mod_access_name=='Add'){
		 if($mod_arr['eAdd']=='Y')
		 	return 1;
	}
	else if($mod_access_name=='Edit'){
		 if($mod_arr['eEdit']=='Y')
		 	return 1;
	}
	else if($mod_access_name=='Delete'){
		 if($mod_arr['eDelete']=='Y')
		 	return 1;
	}
	else if($mod_access_name=='Status'){
		 if($mod_arr['eStatus']=='Y')
		 	return 1;
	}
	else if($mod_access_name=='Respond'){
		 if($mod_arr['eRespond']=='Y')
		 	return 1;
	}
	else if($mod_access_name=='CSV'){
		 if($mod_arr['eCSV']=='Y')
		 	return 1;
	}
	else if($mod_access_name=='PDF'){
		 if($mod_arr['ePDF']=='Y')
		 	return 1;
	}
	else if($mod_access_name=='Calsurv'){
		 if($mod_arr['eCalsurv']=='Y')
		 	return 1;
	}
	if($redirect=='N')
		return 0;

	$msg = 'Unauthorised Access!!!!! Contact Administrator....';
	//echo "<script>window.location='index.php?page=unauthorised&msg=$msg';</script>";
	echo "<script>window.location='".$site_url."user/unauthorised?msg=".$msg."';</script>";
	exit;
}
 
function per_getAssocArray($iAGroupId, $vAccessModule='') {
	global $sqlObj, $admin_panel_session_suffix;
	$rs_module =array();
	$sql_module = "select ac.\"iAModuleId\", ac.\"vAccessModule\", acr.\"eList\", acr.\"eAdd\", acr.\"eEdit\", acr.\"eDelete\", acr.\"eStatus\", acr.\"eRespond\", acr.\"eCSV\", acr.\"ePDF\", acr.\"eCalsurv\" from access_module_mas ac left join access_module_role acr on ac.\"iAModuleId\" = acr.\"iAModuleId\" where  ac.\"iStatus\" = 1";
	
	if($iAGroupId != ""){
		$sql_module .= " and acr.\"iAGroupId\" = '".$iAGroupId."'";
	}
	if($vAccessModule !=''){
		$sql_module .= " and ac.\"vAccessModule\" in ($vAccessModule)";
	}

	$rs_module = $sqlObj->GetAll($sql_module);
	$assoc_array = array();
	for($m=0, $nm=count($rs_module) ; $m<$nm ; $m++)
	{
		$assoc_array[$rs_module[$m]['vAccessModule']]['eList'] = $rs_module[$m]['eList'];
		$assoc_array[$rs_module[$m]['vAccessModule']]['eAdd'] = $rs_module[$m]['eAdd'];
		$assoc_array[$rs_module[$m]['vAccessModule']]['eEdit'] = $rs_module[$m]['eEdit'];
		$assoc_array[$rs_module[$m]['vAccessModule']]['eDelete'] = $rs_module[$m]['eDelete'];
		$assoc_array[$rs_module[$m]['vAccessModule']]['eStatus'] = $rs_module[$m]['eStatus'];
		$assoc_array[$rs_module[$m]['vAccessModule']]['eRespond'] = $rs_module[$m]['eRespond'];
		$assoc_array[$rs_module[$m]['vAccessModule']]['eCSV'] = $rs_module[$m]['eCSV'];
		$assoc_array[$rs_module[$m]['vAccessModule']]['ePDF'] = $rs_module[$m]['ePDF'];
		$assoc_array[$rs_module[$m]['vAccessModule']]['eCalsurv'] = $rs_module[$m]['eCalsurv'];
		
		if($cnt_employee_module > 0){
			for($e=0;$e<$cnt_employee_module;$e++){
				if($rs_employee_module[$e]['iAModuleId'] == $rs_module[$m]['iAModuleId']){
					if($rs_employee_module[$e]['eList'] == 'Y')
						$assoc_array[$rs_module[$m]['vAccessModule']]['eList'] = "Y";
					if($rs_employee_module[$e]['eAdd'] == 'Y')
						$assoc_array[$rs_module[$m]['vAccessModule']]['eAdd'] = "Y";
					if($rs_employee_module[$e]['eEdit'] == 'Y')
						$assoc_array[$rs_module[$m]['vAccessModule']]['eEdit'] = "Y";
					if($rs_employee_module[$e]['eDelete'] == 'Y')
						$assoc_array[$rs_module[$m]['vAccessModule']]['eDelete'] = "Y";
					if($rs_employee_module[$e]['eStatus'] == 'Y')
						$assoc_array[$rs_module[$m]['vAccessModule']]['eStatus'] = "Y";
					if($rs_employee_module[$e]['eRespond'] == 'Y')
						$assoc_array[$rs_module[$m]['vAccessModule']]['eRespond'] = "Y";
					if($rs_employee_module[$e]['eCSV'] == 'Y')
						$assoc_array[$rs_module[$m]['vAccessModule']]['eCSV'] = "Y";
					if($rs_employee_module[$e]['ePDF'] == 'Y')
						$assoc_array[$rs_module[$m]['vAccessModule']]['ePDF'] = "Y";
					if($rs_employee_module[$e]['eCalsurv'] == 'Y')
						$assoc_array[$rs_module[$m]['vAccessModule']]['eCalsurv'] = "Y";
				}
			}
		}
	}
	//echo "<pre>";print_r($assoc_array);exit;
	return $assoc_array;
}

function per_isSuperAdmin($iAdminId)
{
	if($iAdminId==1)
		return 1;
	else
		return 0;
}
?>