<?php
include_once($site_path . "scripts/session_valid.php");

# ----------- Access Rule Condition -----------
per_hasModuleAccess("Access Group", 'List');
$access_group_var_delete = per_hasModuleAccess("Access Group", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Access Group", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Access Group", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Access Group", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "access_group.inc.php");

$page = $_REQUEST['page'];
# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '7');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
$AccessGroupObj = new AccessGroup();
$iAGroupId = $_POST['iAGroupId'];
$vAccessGroup = trim($_POST['vAccessGroup']);

if($mode == "List"){
 
    //echo "<pre>";print_r($_REQUEST);exit();
    $where_arr = array();
    
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if($Keyword != ""){
        if($vOptions == "iStatus"){
            if(strtolower($Keyword) == "active"){
                $where_arr[] = "\"iStatus\" = '1'";
            }
            else if(strtolower($Keyword) == "inactive"){
                $where_arr[] = "\"iStatus\" = '0'";
            }
        }
    else{
            $where_arr[] = '"'.$vOptions."\" ILIKE '".$Keyword."%'";
        }
    }

    //echo "<pre>";print_r($where_arr);exit();
    switch ($display_order) {
        case "0" : 
            $sortname = "access_group_mas.\"iAGroupId\"";
            break;
        case "1":
            $sortname = "access_group_mas.\"vAccessGroup\"";
            break;
        case "4":
            $sortname = "access_group_mas.\"iStatus\"";
            break;
        default:
            $sortname = 'access_group_mas."vAccessGroup"';
            break;
    }
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $AccessGroupObj->join_field = $join_fieds_arr;
    $AccessGroupObj->join = $join_arr;
    $AccessGroupObj->where = $where_arr;
    $AccessGroupObj->param['order_by'] = $sortname . " " . $dir;
    $AccessGroupObj->param['limit'] = $limit;
    $AccessGroupObj->setClause();
    $AccessGroupObj->debug_query = false;
    $rs_data = $AccessGroupObj->recordset_list();
   
    //echo "<pre>";print_r($rs_data);exit();
    // Paging Total Records
    $total = $AccessGroupObj->recordset_total();
    // Paging Total Records


    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = array();
    $ni = count($rs_data);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $action = '';
            if ($access_group_var_edit == '1') {
                $action .= '<a class="btn btn-outline-secondary" title="Edit" href="javascript:void(0);" onclick="addEditData('.$rs_data[$i]['iAGroupId'].',\'edit\')"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == '1' && $rs_data[$i]['iDefault'] != 1) {
                $action .= '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_data[$i]['iAGroupId'].');"><i class="fa fa-trash"></i></a>';
            }

            $checkbox = '';
            if($rs_data[$i]['iDefault'] != 1){
               $checkbox = '<input type="checkbox" class="list" value="'.$rs_data[$i]['iAGroupId'].'"/><input type="hidden" name="" value="'.$rs_data[$i]['iAGroupId'].'">';
           
            }
            $entry[] = array(
               // "checkbox" => $checkbox,
                "checkbox" =>$rs_data[$i]['iAGroupId'].'<input type="hidden" id="ag_id_'.$rs_data[$i]['iAGroupId'].'" value="'.$rs_data[$i]['iAGroupId'].'">',
                "vAccessGroup" =>gen_strip_slash($rs_data[$i]['vAccessGroup']).'<input type="hidden" id="ag_name_'.$rs_data[$i]['iAGroupId'].'" value="'.$rs_data[$i]['vAccessGroup'].'">',
                "tDescription" =>gen_strip_slash($rs_data[$i]['tDescription']).'<input type="hidden" id="ag_tdesc_'.$rs_data[$i]['iAGroupId'].'" value="'.$rs_data[$i]['tDescription'].'">',
                'vManage'=>'<a class="btn btn-outline-primary link-view" href="'.$site_url.'access_group/access_group_add&mode=Manage&iAGroupId='.$rs_data[$i]['iAGroupId'].'">Manage</a>',
                'iStatus'=>'<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_data[$i]['iStatus']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_data[$i]['iStatus'])].'">&nbsp;</span><input type="hidden" id="ag_status_'.$rs_data[$i]['iAGroupId'].'" value="'.$rs_data[$i]['iStatus'].'">',
                "actions" => ($action == "")?"---":$action
            );
        }
        
    }
    $jsonData['aaData'] = $entry;
   // echo "<pre>";print_r($jsonData);exit();
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}

else if($mode == "Delete"){
    $result = array();
    $iAGroupId = $_POST['iAGroupId'];

    $AccessGroupObj->ids = $iAGroupId;
    $AccessGroupObj->action = $mode;
    $AccessGroupObj->setClause();
    $rs_tot = $AccessGroupObj->delete_records();
    
    if($rs_tot){
        $result['msg'] = MSG_DELETE;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_DELETE_ERROR;
        $result['error']= 1 ;

    }
    //$jsonData = array('total'=>$rs_tot);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
   // echo json_encode($jsonData);
   echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}
else if($mode == "Add"){
    $result = array();
    $insert_arr = array("vAccessGroup"=>addslashes($_POST['vAccessGroup']),
    "iStatus"=>isset($_POST['iStatus'])?$_POST['iStatus']:"0",
    "tDescription"=>addslashes($_POST['tDescription']),
    "iDefault"=>0
    );
    $AccessGroupObj->insert_arr = $insert_arr;
    $rs_db = $AccessGroupObj->add_records();
    if(isset($rs_db)){
        $result['msg'] = MSG_ADD;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_ADD_ERROR;
        $result['error']= 1 ;

    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
}

else if($mode == "Update"){
    $result =array();
    $update_array = array("iAGroupId"=>$_POST['iAGroupId'], 
    "vAccessGroup"=>addslashes($_POST['vAccessGroup']),
    "tDescription"=>addslashes($_POST['tDescription']),
    "iStatus"=>isset($_POST['iStatus'])?$_POST['iStatus']:"0",
    "iDefault"=>0
    );
    $AccessGroupObj->update_arr = $update_array;
    $rs_db = $AccessGroupObj->update_records();
    if(isset($rs_db)){
        $result['msg'] = MSG_UPDATE;
        $result['error']= 0 ;
    }else{
         $result['msg'] = MSG_UPDATE_ERROR;
        $result['error']= 1 ;

    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
} else if($mode == "Manage_Role"){
    $iAGroupId = $_REQUEST['iAGroupId'];
    $result = array();
    //echo "<pre>";print_r($_REQUEST);exit();
    $sql_role = "delete from access_module_role where \"iAGroupId\" = '".$iAGroupId."'";
    $db_sql_role = $sqlObj->Execute($sql_role);

    $sql_module = "select \"iAModuleId\", \"vAccessModule\" from access_module_mas ORDER BY \"iAModuleId\", \"iDispOrder\"";
    $db_module=$sqlObj->GetAll($sql_module);

    //echo "<pre>";print_r($db_module);exit();
    if(count($db_module)>0){
        $value = array();
        for ($i=0; $i <count($db_module) ; $i++) { 
            $str_list = $str_add = $str_edit = $str_delete = $str_status = $str_csv = $str_pdf =$str_respond =$str_calsurv ="";
            $str_list = (isset($_REQUEST["eList"][$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_add = (isset($_REQUEST["eAdd"][$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_edit = (isset($_REQUEST["eEdit"][$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_delete = (isset($_REQUEST["eDelete"][$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_status = (isset($_REQUEST["eStatus"][$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_respond = (isset($_REQUEST["eRespond"][$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_csv = (isset($_REQUEST["eCSV"][$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_pdf = (isset($_REQUEST["ePDF"][$db_module[$i]['iAModuleId']]))?"Y":"N";
            $str_calsurv =(isset($_REQUEST["eCalsurv"][$db_module[$i]['iAModuleId']]))?"Y":"N";

            $value[]= "('".$db_module[$i]['iAModuleId']."', '".$iAGroupId."', '".$str_list."', '".$str_add."', '".$str_edit."', '".$str_delete."', '".$str_status."', '".$str_respond."', '".$str_csv."', '".$str_pdf."', '".$str_calsurv."')";
        }
        //echo "<pre>";print_r($value);exit();
        if(count($value) > 0)
        {
            $sql="INSERT INTO access_module_role (\"iAModuleId\", \"iAGroupId\", \"eList\", \"eAdd\", \"eEdit\", \"eDelete\", \"eStatus\", \"eRespond\", \"eCSV\", \"ePDF\", \"eCalsurv\")  VALUES ".implode(",", $value);
           // echo $sql;exit();
            $sqlObj->Execute($sql);
            $db_sql=$sqlObj->Affected_Rows();
            if($db_sql) {
                 $result['msg'] = MSG_ALL_CHANGES_APPLIED_SUCCESSFULLY;
                $result['error']= 0 ;
            }else{
                 $result['msg'] = MSG_ALL_CHANGES_APPLIED_SUCCESSFULLY_ERROR;
                $result['error']= 1 ;
            }
        }
        else
        {
            $result['msg'] = MSG_ALL_CHANGES_APPLIED_SUCCESSFULLY_ERROR;
            $result['error']= 1 ;
        }
    }else{
        $result['msg'] = MSG_ALL_CHANGES_APPLIED_SUCCESSFULLY_ERROR;
        $result['error']= 1 ;
    }
               
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
}
$module_name = "Access Group List";
$module_title = "Access Group";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("access_group_var_add", $access_group_var_add);