<?php
//echo "<pre>";print_r($_REQUEST);exit();

include_once($site_path . "scripts/session_valid.php");

$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");

# ----------- Access Rule Condition -----------
$access_group_var_delete = per_hasModuleAccess("Contact", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Contact", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Contact", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Contact", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "contact.inc.php");
include_once($controller_path . "report.inc.php");

$ReportObj = new Report();
$ContactObj = new Contact();

$rs_contact = array();
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Contact", 'Edit');
} else {
    per_hasModuleAccess("Contact", 'Add');
}

if ($mode == "Update") {
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();

    if(isset($_REQUEST['iCId'])) {
    	$join_arr[] = " LEFT JOIN contact_phone ON contact_phone.\"iCId\" = contact_mas.\"iCId\" ";
	    $ContactObj->join_field = $join_fieds_arr;
	    $ContactObj->join = $join_arr;
	    $where_arr[] = " contact_mas.\"iCId\"='" . $_REQUEST['iCId']. "' ";

	    $ContactObj->where = $where_arr;
	    $ContactObj->param['limit'] = "LIMIT 1";
	    $ContactObj->setClause();
	    $rs_contact = $ContactObj->recordset_list();
	  	

	    # Get all phone numbers of contact
		$ContactObj->iContactId = $_REQUEST['iCId'];
		$rs_contact_phone = $ContactObj->getContactPhoneNumbers($_REQUEST['iCId']);

		if(count($rs_contact_phone)) {
			for($p=0, $np=count($rs_contact_phone); $p<$np; $p++) {
				if($rs_contact_phone[$p]['vType'] == "Primary" && $rs_contact_phone[$p]['vPhone'] !=""){
					$primary_phone_num = $rs_contact_phone[$p]['vPhone'];
				}
				else if($rs_contact_phone[$p]['vType'] == "Alternate" && $rs_contact_phone[$p]['vPhone'] !=""){
					$alternate_phone_num = $rs_contact_phone[$p]['vPhone'];
				}
			}
		}
    }	
}

// General Variables
$module_name = "Contact";
$smarty->assign("rs_contact", $rs_contact);
$smarty->assign("mode", $mode);
$smarty->assign("iAGroupId", $iAGroupId);
$smarty->assign("module_name", $module_name);

$smarty->assign("primary_phone_num", $primary_phone_num);
$smarty->assign("alternate_phone_num", $alternate_phone_num);

$smarty->assign("iPremiseId", $_GET['iPremiseId']);
$smarty->assign("referer", $_GET['referer']);

?>
