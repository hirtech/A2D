<?php
//echo"<pre>";print_r($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Login History", 'List');
$access_group_var_delete = per_hasModuleAccess("Login History", 'Delete', 'N');
$access_group_var_PDF = per_hasModuleAccess("Login History", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Login History", 'CSV', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "login_history.inc.php");
include_once($controller_path . "access_group.inc.php");
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
$Login_HistoryObj = new Login_History();
$AccessGroupObj = new AccessGroup();
$iUserId = $_REQUEST['iUserId'];

if ($mode == "List") {

    //echo "<pre>";print_r($_REQUEST);exit;
    $where_arr = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if($iUserId != '')
    {
        $where_arr[] = "login_logs_mas.\"iID\"=".$iUserId."";
    }
    if ($Keyword != "") {
        if ($vOptions == "vIP") {
            $where_arr[] = "user_mas.\"vIP\" LIKE '%" . $Keyword . "%'";
        } 
        if ($vOptions == "vUsername") {
            $where_arr[] = "user_mas.\"vUsername\" LIKE '" . $Keyword . "%'";
           // echo "<pre>";print_r($where_arr);exit;
        } 
        else {
            $where_arr[] = '"' . $vOptions . "\" ILIKE '" . $Keyword . "%'";
        }
    }

   if ($_REQUEST['iAGroupId'] != "") {
        $where_arr[] = "user_mas.\"iAGroupId\"='" . $_REQUEST['iAGroupId'] . "'";
    }
    if ($_REQUEST['iStatus'] != "") {
        if ($_REQUEST['iStatus'] != "-1")
            $where_arr[] = "user_mas.\"iStatus\"='" . $_REQUEST['iStatus'] . "'";
    }


    if ($_REQUEST['vUsername'] != "") {
        if ($_REQUEST['vUsernameDD'] != "") {
            if ($_REQUEST['vUsernameDD'] == "Begins") {
                $where_arr[] = 'user_mas."vUsername" LIKE \'' . trim($_REQUEST['vUsername']) . '%\'';
            } else if ($_REQUEST['vUsernameDD'] == "Ends") {
                $where_arr[] = 'user_mas."vUsername" LIKE \'%' . trim($_REQUEST['vUsername']) . '\'';
            } else if ($_REQUEST['vUsernameDD'] == "Contains") {
                $where_arr[] = 'user_mas."vUsername" LIKE \'%' . trim($_REQUEST['vUsername']) . '%\'';
            } else if ($_REQUEST['vUsernameDD'] == "Exactly") {
                $where_arr[] = 'user_mas."vUsername" = \'' . trim($_REQUEST['vUsername']) . '\'';
            }
        } else {
            $where_arr[] = 'user_mas."vUsername" LIKE \'' . trim($_REQUEST['vUsername']) . '%\'';
        }
    }


    switch ($display_order) {
        case "0":
         $sortname = "login_logs_mas.\"iLLogsId\"";
            break;
        case "1":
         $sortname = "user_mas.\"vUsername\"";
            break;
        case "2":
            $sortname = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\" )";
            break;
        case "3":
            $sortname = "login_logs_mas.\"vIP\"";
            break;
        case "4":
            $sortname = "login_logs_mas.\"dLoginDate\"";
            break;
        case "5":
            $sortname = "login_logs_mas.\"dLogoutDate\"";
            break;
        default:
            $sortname = 'login_logs_mas."dLoginDate"';
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = "user_mas.\"vUsername\", user_mas.\"vFirstName\", user_mas.\"vLastName\"";
    $join_fieds_arr[] = "access_group_mas.\"vAccessGroup\"";
    $join_arr  = array();
    $join_arr[] = "LEFT JOIN user_mas ON login_logs_mas.\"iID\" = user_mas.\"iUserId\"";
    $join_arr[] = "LEFT JOIN access_group_mas ON user_mas.\"iAGroupId\" = access_group_mas.\"iAGroupId\"";
    
    $Login_HistoryObj->join_field = $join_fieds_arr;
    $Login_HistoryObj->join = $join_arr;
    $Login_HistoryObj->where = $where_arr;
    $Login_HistoryObj->param['order_by'] = $sortname . " " . $dir;
    $Login_HistoryObj->param['limit'] = $limit;
    $Login_HistoryObj->setClause();
    $Login_HistoryObj->debug_query = false;
    $rs_login_history = $Login_HistoryObj->recordset_list();

    // Paging Total Records
    $total = $Login_HistoryObj->recordset_total();
    // Paging Total Records

    //echo $page_length;exit;
   // $jsonData = array('sEcho' => $sEcho, 'recordsTotal' => $total, 'recordsFiltered' => $total, 'data' => array());
     $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $ni = count($rs_login_history);
    if ($ni > 0) {
       
        for ($i = 0; $i < $ni; $i++) {
            $delete = '';
            if ($access_group_var_delete == '1') {
                $delete = '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_login_history[$i]['iLLogsId'].');"><i class="fa fa-trash"></i></a>';
            }

			$Login_HistoryObj->Login_History();
            $where_arr = array();
            $join_arr = array();
           
            $Login_HistoryObj->where = $where_arr;
            $Login_HistoryObj->param['limit'] = 0;
            $Login_HistoryObj->setClause();
       
            $one_date = strtotime($rs_login_history[$i]['dLoginDate']);
            $two_date = strtotime($rs_login_history[$i]['dLogoutDate']);
            $date_diff = date_timeBetween($one_date, $two_date);
            if ($date_diff=='0 seconds')
                $date_diff='---';

            //$entry[$i]['checkbox'] = '<input type="checkbox" class="list" value="' . $rs_login_history[$i]['iLLogsId'] . '"/>';
            $entry[$i]['checkbox'] =  $rs_login_history[$i]['iLLogsId'];
            $entry[$i]['vUsername'] = gen_strip_slash($rs_login_history[$i]['vUsername']);
            $entry[$i]['Name'] = gen_strip_slash($rs_login_history[$i]['vFirstName']) . " " . gen_strip_slash($rs_login_history[$i]['vLastName']). " - " . gen_strip_slash($rs_login_history[$i]['vAccessGroup']);
            $entry[$i]['vIP'] = $rs_login_history[$i]['vIP'];
            $entry[$i]['dLoginDate'] = date_getDateTime($rs_login_history[$i]['dLoginDate']);
            $entry[$i]['dLogoutDate'] = date_getDateTime($rs_login_history[$i]['dLogoutDate']);
            $entry[$i]['date_diff'] = $date_diff;


        }
        $jsonData['aaData'] = $entry;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    //echo"<pre>";print_r($jsonData);exit;

    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}

$module_name = "Login History List";
$module_title = "Login History";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("iAGroupId", $_GET['iAGroupId']);
$smarty->assign("iUserId",$iUserId);