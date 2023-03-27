<?php

include_once($site_path . "scripts/session_valid.php");
include_once($controller_path . "access_group.inc.php");

$mode = $_GET['mode'];
# ----------- Access Rule Condition -----------
if($mode == "Manage"){
	per_hasModuleAccess("Access Group", 'Edit');
}
# ----------- Access Rule Condition -----------
$access_group_var_delete = per_hasModuleAccess("Access Group", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Access Group", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Access Group", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Access Group", 'Edit', 'N');
# ----------- Access Rule Condition -----------

$AccessGroupObj = new AccessGroup();
$iAGroupId = $_GET['iAGroupId'];
if($mode == "Manage") {
	#----------------
	/*$sql_module="select * from access_module_mas where \"iStatus\" = 1";
	$db_sql_module=$sqlObj->GetAll($sql_module);*/
	#--------End--------------------------------------
	$iDefault_arr = array();
	if($_GET['iDefault'] != ""){

		$sql_role = "select * from access_module_role where \"iAGroupId\" IN (".$_GET['iDefault'].")";
		$db_sql_role = $sqlObj->GetAll($sql_role);

		$iDefault_arr = explode(",", $_GET['iDefault']);
	}
	else{
		$sql_role = "select * from access_module_role where \"iAGroupId\" = '".$iAGroupId."'";
		$db_sql_role = $sqlObj->GetAll($sql_role);
	}

	$sql="select * from access_group_mas where \"iAGroupId\" = '".$iAGroupId."'";
	$db_sql=$sqlObj->GetAll($sql);
	if(count($db_sql)>0)
	{
		$iAGroupId=$db_sql[0]["iAGroupId"];
		$vAccessGroup= ($db_sql[0]["vAccessGroup"]);
		$tDescription= ($db_sql[0]["tDescription"]);
		$iDefault= ($db_sql[0]["iDefault"]);
		$eStatus = $db_sql[0]["eStatus"];
		//$mode = "Manage_Update";
		$mode = "Manage_Role";
	}


	/***************************************************************************/
	$asso_array =$accmod_assoc_arr = $param_array= array();
	$arid = 0;
		
	$sql_module = "select * from access_module_mas WHERE \"iStatus\"=1 ORDER BY \"iParentId\", \"iDispOrder\" ";
	$db_module=$sqlObj->GetAll($sql_module);

	//echo "<pre>";print_r($db_module);exit();
	for($i=0,$mo=count($db_module); $i< $mo; $i++)
	{//set parent id array
		$accmod_assoc_arr[$db_module[$i]['iParentId']][] = $db_module[$i];	
	}
	for($j=0,$ji=count($db_sql_role); $j<$ji; $j++)
	{   //all module arary
		$asso_array[$db_sql_role[$j]['iAModuleId']]=$db_sql_role[$j];
	}
	//set parent-child module array
	getParentChildAModuleList(0,"",0,5); 
	$modules_arr = &$param_array;
	$ni = count($modules_arr);
	$rs_access_group = array();
	for($i=0 ; $i<$ni ; $i++)
	{
		$j=$i;
		$str_list = $str_add = $str_edit = $str_delete = $str_status = $str_csv = $str_pdf =$str_respond =$str_calsurv ="";
		$checked_list = $checked_add = $checked_edit = $checked_delete = $checked_status = $checked_csv = $checked_pdf =$checked_respond =$checked_calsurv  = "1";
		$iAModuleId = $modules_arr[$i]['iAModuleId'];

	
			$str_list = '<div class="custom-control custom-checkbox custom-control-inline text-center"><input type="checkbox" class="custom-control-input case_list" id="eList_'.$iAModuleId.'" name="eList['.$iAModuleId.']" title="List" value="Y" '.(($asso_array[$iAModuleId]) ? ($asso_array[$iAModuleId]['eList'] == "Y")? "checked":"":"").'><label class="custom-control-label" for="eList_'.$iAModuleId.'"></label></div>';
			$checked_list = ($asso_array[$iAModuleId]['eList'] == "Y");

			$str_add = '<div class="custom-control custom-checkbox custom-control-inline text-center"><input type="checkbox" class="custom-control-input  case_add" id="eAdd_'.$iAModuleId.'" name="eAdd['.$iAModuleId.']" title="Add" value="Y" '.(($asso_array[$iAModuleId]) ? ($asso_array[$iAModuleId]['eAdd'] == "Y")? "checked":"":"").'><label class="custom-control-label" for="eAdd_'.$iAModuleId.'"></label></div>';
			$checked_add = ($asso_array[$iAModuleId]['eAdd'] == "Y");

			$str_edit = '<div class="custom-control custom-checkbox custom-control-inline text-center"><input type="checkbox" class="custom-control-input  case_edit" id="eEdit_'.$iAModuleId.'" name="eEdit['.$iAModuleId.']" title="Edit" value="Y" '.(($asso_array[$iAModuleId]) ? ($asso_array[$iAModuleId]['eEdit'] == "Y")? "checked":"":"").'><label class="custom-control-label" for="eEdit_'.$iAModuleId.'"></label></div>';
			$checked_edit = ($asso_array[$iAModuleId]['eEdit'] == "Y");

			$str_delete = '<div class="custom-control custom-checkbox custom-control-inline text-center"><input type="checkbox" class="custom-control-input  case_delete" id="eDelete_'.$iAModuleId.'" name="eDelete['.$iAModuleId.']" title="Delete" value="Y" '.(($asso_array[$iAModuleId]) ? ($asso_array[$iAModuleId]['eDelete'] == "Y")? "checked":"":"").'><label class="custom-control-label" for="eDelete_'.$iAModuleId.'"></label></div>';
			$checked_delete = ($asso_array[$iAModuleId]['eDelete'] == "Y");

			$str_status = '<div class="custom-control custom-checkbox custom-control-inline text-center"><input type="checkbox" class="custom-control-input  case_status" id="eStatus_'.$iAModuleId.'" name="eStatus['.$iAModuleId.']" title="Status" value="Y" '.(($asso_array[$iAModuleId]) ? ($asso_array[$iAModuleId]['eStatus'] == "Y")? "checked":"":"").'><label class="custom-control-label" for="eStatus_'.$iAModuleId.'"></label></div>';
			$checked_status = ($asso_array[$iAModuleId]['eStatus'] == "Y");

			$str_respond = '<div class="custom-control custom-checkbox custom-control-inline text-center"><input type="checkbox" class="custom-control-input  case_respond" id="eRespond_'.$iAModuleId.'" name="eRespond['.$iAModuleId.']" title="Respond" value="Y" '.(($asso_array[$iAModuleId]) ? ($asso_array[$iAModuleId]['eRespond'] == "Y")? "checked":"":"").'><label class="custom-control-label" for="eRespond_'.$iAModuleId.'"></label></div>';
			$checked_respond = ($asso_array[$iAModuleId]['eRespond'] == "Y");

			$str_csv = '<div class="custom-control custom-checkbox custom-control-inline text-center"><input type="checkbox" class="custom-control-input  case_csv" id="eCSV_'.$iAModuleId.'" name="eCSV['.$iAModuleId.']" title="CSV" value="Y" '.(($asso_array[$iAModuleId]) ? ($asso_array[$iAModuleId]['eCSV'] == "Y")? "checked":"":"").'><label class="custom-control-label" for="eCSV_'.$iAModuleId.'"></label></div>';
			$checked_csv = ($asso_array[$iAModuleId]['eCSV'] == "Y");
		
			$str_pdf = '<div class="custom-control custom-checkbox custom-control-inline text-center"><input type="checkbox" class="custom-control-input  case_pdf" id="ePDF_'.$iAModuleId.'" name="ePDF['.$iAModuleId.']" title="PDF" value="Y" '.(($asso_array[$iAModuleId]) ? ($asso_array[$iAModuleId]['ePDF'] == "Y")? "checked":"":"").'><label class="custom-control-label" for="ePDF_'.$iAModuleId.'"></label></div>';
			$checked_pdf = ($asso_array[$iAModuleId]['ePDF'] == "Y");

			$str_calsurv = '<div class="custom-control custom-checkbox custom-control-inline text-center"><input type="checkbox" class="custom-control-input  case_calsurv" id="eCalsurv_'.$iAModuleId.'" name="eCalsurv['.$iAModuleId.']" title="Calsurv" value="Y" '.(($asso_array[$iAModuleId]) ? ($asso_array[$iAModuleId]['eCalsurv'] == "Y")? "checked":"":"").'><label class="custom-control-label" for="eCalsurv_'.$iAModuleId.'"></label></div>';
			$checked_pdf = ($asso_array[$iAModuleId]['eCalsurv'] == "Y");
		

		$checked_raw = ($checked_list && $checked_add && $checked_edit && $checked_delete && $checked_status && $checked_csv && $checked_pdf && $checked_respond && $checked_calsurv)?"checked":"";

		$first_col = '<div class="custom-control custom-checkbox custom-control-inline text-center"><input type="checkbox" class="custom-control-input case" onclick="checkAllRow(this);" id="row_'.$iAModuleId.'" value="'.$iAModuleId.'" '.$checked_raw.' title="Select Row" /><label class="custom-control-label" for="row_'.$iAModuleId.'"></label></div>';


		$rs_access_group[] = array(
			"id" => $modules_arr[$i]['disnum'],
	      	"vAccessModule" => $modules_arr[$i]['vPath'] ,
	      	"chck" =>$first_col,
		  	"listchck" =>$str_list,      
	      	"addchck" => $str_add,
	      	"editchck" => $str_edit,
	      	"deletechck" => $str_delete,
	      	"statuschck" => $str_status,
	      	"respondchck" => $str_respond,
	      	"csvchck" => $str_csv,
	      	"pdfchck" => $str_pdf,
	      	"calsurvchck" =>$str_calsurv
	    );
	}
	//echo "<pre>";print_r($records);exit();
	/************************************************************************/

	// Access Group Checkbox...
	$where_arr = array();
	$where_arr[] = 'access_group_mas."iStatus"=\'1\'';
	$where_arr[] = 'access_group_mas."iDefault"=\'1\'';
	$AccessGroupObj->where = $where_arr;	
	$AccessGroupObj->param['order_by'] = "access_group_mas.\"iAGroupId\"";
	$AccessGroupObj->param['limit'] =  0;
	$AccessGroupObj->setClause();
	$rs_access = $AccessGroupObj->recordset_list();
}

function getParentChildAModuleList($iParent=0, $prefix="", $loop=0, $maxloop=5)
{
	global $accmod_assoc_arr, $param_array,$arid;
	if($loop<=$maxloop && is_array($accmod_assoc_arr[$iParent]))
	{	
		
		foreach($accmod_assoc_arr[$iParent] as $Pid=>$rsamodule)
		{		

			if($loop>0){
				if($rsamodule['iSubParentId'] > 0 ){
					$path = $prefix."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - ".$rsamodule['vAccessModule'];
				}else {
					$path = $prefix." - ".$rsamodule['vAccessModule'];
				}
				$disnum = "";
			}
			else{
				$path = $rsamodule['vAccessModule'];
				$disnum=$arid+=1;
				//$arid++;
			}
			$param_array[]=array_merge($rsamodule, array('disnum' => $disnum,'vPath' => $path , 'loop'=>$loop));
			getParentChildAModuleList($rsamodule['iAModuleId'],"&nbsp;&nbsp;&nbsp;",$loop+1,$maxloop);
			
		}
		
	}
	$prefix = "";
	return $param_array;
}

// General Variables
$module_name = "Access Group";
$module_title = "Access Group Role";
$smarty->assign("module_name",$module_name);
$smarty->assign("module_title",$module_title);
$smarty->assign("rs_access_group", $rs_access_group);
//$smarty->assign("db_sql_module", $db_sql_module);
$smarty->assign("vAccessGroup", $vAccessGroup);
$smarty->assign("iAGroupId", $iAGroupId);
$smarty->assign("mode", $mode);
$smarty->assign("tDescription",$tDescription);
$smarty->assign("iDefault",$iDefault);
$smarty->assign("rs_access",$rs_access);
$smarty->assign("iDefault_arr",$iDefault_arr);

?>