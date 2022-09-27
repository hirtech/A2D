<?php
#######################################
## Created By Dev 			###
#######################################
class Global_Database
{
	#-----------------------------------------------------------------
	# Function about General Settings ::
	#-----------------------------------------------------------------
	
	function getSettingVariables()
	{
		global $sqlObj, $smarty;
		
		$wri_usql = "SELECT * FROM setting_mas where \"bStatus\" = '1'";
		$wri_ures = $sqlObj->GetAll($wri_usql);
		
		for($i=0;$i<count($wri_ures);$i++){
			$vName  = $wri_ures[$i]["vName"];
			$vValue  = $wri_ures[$i]["vValue"];
			global $$vName;
			$$vName=$vValue;
			if($smarty)
				$smarty->assign($vName, $vValue);
		}
	}
	
	#-----------------------------------------------------------------------------------------
	# Create Dynamic Combobox 
	#(Usage : 
	# echo dynamicDropeDown(country_master,iCountryId,vCountry,vCountryCode,$iCountryId); 
	# If u don't want vCountryCode then only write ''. 
	#-----------------------------------------------------------------------------------------
	function dynamicDropDown($tableName, $fieldId, $fieldName, $extVal='', $selectedVal, $where_clause="", $firstOption="", $width='150px', $tag_attribute="", $drop_down_name='', $order_by='', $group_by = '')
	{
		Global $sqlObj;
		$groupdropdown = "";
		
		if ($extVal != "")	
			$ssql = '"'.$fieldId.'","'.$fieldName.'","'.$extVal.'"';	
		else
			$ssql = '"'.$fieldId.'", "'.$fieldName.'"';
			
		$sql_query = "SELECT $ssql FROM $tableName";
		if($where_clause !="")
			$sql_query .=' where '.$where_clause;

		if($group_by!='')
		$sql_query .=' group by $group_by';

		if($order_by=='')	$order_by = '2';
		$sql_query .=' order by $order_by';

		
		
		$catres = $sqlObj->select($sql_query,'mysql_fetch_array');
		if($drop_down_name=='')
			$drop_down_name = $fieldId;
		$drop_down_id = str_replace("[]", "", $drop_down_name);
		$groupdropdown .= "<select id=\"$drop_down_id\" name=\"$drop_down_name\" style=\"width:$width\" ". $tag_attribute .">".$firstOption;
		$arr_selected = array();
		if($selectedVal!='' && !is_array($selectedVal))
			$arr_selected[] = $selectedVal;
		else if(is_array($selectedVal))
			$arr_selected = $selectedVal;
		
		for($g=0;$g<count($catres);$g++){
			$cid = $catres[$g][0];
			$cname = $catres[$g][1];
			$extname = $catres[$g][$extVal];
			
			if ($extVal != "")	
				$vData = "$cname ( $extname )";
			else
				$vData = "$cname"; 
			
			//if($cid==$selectedVal)
			if(in_array($cid, $arr_selected))
				$groupdropdown .= "<option value=\"$cid\" selected>".$vData."</option>";
			else
				$groupdropdown .= "<option value=\"$cid\">".$vData."</option>";  
		}
	 
		$groupdropdown .= "</select>";
		return $groupdropdown;
	}

	#-----------------------------------------------------------------
	# Function get County General Settings 
	#-----------------------------------------------------------------
	
	function getCountrySettingVariables($field = "",$prefix ="")
	{
	
		global $sqlObj, $smarty;
	
		$extra_where ="";
		if(is_array($field) && count($field) > 0){
        	$extra_where = " AND \"vName\" IN ('".implode($field,"','")."')";
		}else if($field != ""){
			$extra_where = " AND \"vName\" ILIKE  '".$field."'";
		}
		
		$wri_usql = "SELECT * FROM county_setting_mas where \"bStatus\" = '1' ".$extra_where;

		$wri_ures = $sqlObj->GetAll($wri_usql);

		for($i=0;$i<count($wri_ures);$i++){
			$vName  = $prefix.$wri_ures[$i]["vName"];
			$vValue  = $wri_ures[$i]["vValue"];
			global $$vName;
			$$vName=$vValue;
			if($smarty){
				$smarty->assign($vName, $vValue);
			}
		}
	}
	
	/*function checkField($tblName, $field)
	{
		global $sqlObj;
		
		$db = $sqlObj->getTableFields($tblName); 
		$flag = false;
		if(in_array($field, $db))
			$flag = true;
		return 	$flag;
	}
	
	function getNameById($id,$id_val,$name,$table) {
		global $sqlObj;
		$sql = "SELECT $name FROM $table WHERE $id = '".$id_val."'";
		$db_sql = $sqlObj->select($sql);
		$str = $db_sql[0][$name];
		return $str;
	}
	
	function getResult($table,$clause)
	{
		global $sqlObj;
		$sql="select *  from ".$table.$clause;
		$res=$sqlObj->select($sql);
		return $res;
	}
	
	#-----------------------------
	# Get Data fron any table.
	#-----------------------------
	function getFieldData($Id,$fieldIdName,$fieldName,$tableName)
	{
		global $sqlObj;
	
		$pack_usql = "SELECT $fieldName as fielnData FROM $tableName where $fieldIdName = '$Id' and eStatus='Active'";
		$pack_ures = $sqlObj->select($pack_usql);
	
		$fieldName  = $pack_ures[0]["fielnData"];
		return $fieldName;
	}
	
	
	#-----------------------------
	# Get Count fron any table.
	#-----------------------------
	function getTotalRecord($tableName,$fieldName,$where_clause="")
	{
		global $sqlObj;
		if ($where_clause!='')
			$pack_usql = "SELECT count($fieldName) as tot FROM $tableName where $where_clause";
		else
			$pack_usql = "SELECT count($fieldName) as tot FROM $tableName";
	
		$pack_ures = $sqlObj->select($pack_usql);
		return $pack_ures[0]['tot'];
	}*/
}

?>