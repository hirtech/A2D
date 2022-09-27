<?php
include_once($site_path . "scripts/session_valid.php");


# ----------- Access Rule Condition -----------
per_hasModuleAccess("User", 'List');
$access_group_var_delete = per_hasModuleAccess("User", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("User", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("User", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("User", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("User", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("User", 'CSV', 'N');
$access_group_var_Respond = per_hasModuleAccess("User", 'Respond', 'N');
# ----------- Access Rule Condition -----------

include_once($function_path . "mail.inc.php");
include_once($controller_path . "user.inc.php");
include_once($controller_path . "report.inc.php");
include_once($controller_path . "department.inc.php");
include_once($controller_path . "access_group.inc.php");
include_once($function_path."image.inc.php");
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
$UserObj = new User();
$ReportObj = new Report();
$DepartmentObj = new Department();
$AccessGroupObj = new AccessGroup();
$sess_iCountySaasId = $_SESSION["sess_iCountySaasId".$admin_panel_session_suffix];

if ($mode == "List") {
    //echo "<pre>";print_r($_REQUEST);exit;
    $where_arr = array();
    if ($query) {
        $where_arr[] = $qtype . " LIKE '" . addslashes($query) . "%'";
    }

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        if ($vOptions == "Name") {
            $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") ILIKE '" . $Keyword . "%'";
        } else if ($vOptions == "iStatus") {
            if (strtolower($Keyword) == "active") {
                $where_arr[] = "user_mas.\"iStatus\" = '1'";
            } else if (strtolower($Keyword) == "inactive") {
                $where_arr[] = "user_mas.\"iStatus\" = '0'";
            }
        } else {
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


    if ($_REQUEST['iDepartmentId'] != "") {
       $where_arr[] = 'user_mas."iUserId" IN (SELECT user_department."iUserId" FROM user_department WHERE user_department."iDepartmentId" = '.$_REQUEST['iDepartmentId'].')';
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

    if ($_REQUEST['vEmail'] != "") {
        if ($_REQUEST['vEmailDD'] != "") {
            if ($_REQUEST['vEmailDD'] == "Begins") {
                $where_arr[] = 'user_mas."vEmail" LIKE \'' . trim($_REQUEST['vEmail']) . '%\'';
            } else if ($_REQUEST['vEmailDD'] == "Ends") {
                $where_arr[] = 'user_mas."vEmail" LIKE \'%' . trim($_REQUEST['vEmail']) . '\'';
            } else if ($_REQUEST['vEmailDD'] == "Contains") {
                $where_arr[] = 'user_mas."vEmail" LIKE \'%' . trim($_REQUEST['vEmail']) . '%\'';
            } else if ($_REQUEST['vEmailDD'] == "Exactly") {
                $where_arr[] = 'user_mas."vEmail" = \'' . trim($_REQUEST['vEmail']) . '\'';
            }
        } else {
            $where_arr[] = 'user_mas."vEmail" LIKE \'' . trim($_REQUEST['vEmail']) . '%\'';
        }
    }

    if ($_REQUEST['vName'] != "") {
        if ($_REQUEST['vNameDD'] != "") {
            if ($_REQUEST['vNameDD'] == "Begins") {
                   // $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '" . trim($_REQUEST["vName"]) . "%'";
                $where_arr[] = "user_mas.\"vFirstName\" LIKE '" . trim($_REQUEST["vName"]) . "%'";
            } else if ($_REQUEST['vNameDD'] == "Ends") {
                    //$where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '%" . trim($_REQUEST["vName"]) . "'";
                $where_arr[] = "user_mas.\"vLastName\" LIKE '%" . trim($_REQUEST["vName"]) . "'";
            } else if ($_REQUEST['vNameDD'] == "Contains") {
                $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '%" . trim($_REQUEST["vName"]) . "%'";
            } else if ($_REQUEST['vNameDD'] == "Exactly") {
                $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") = '" . trim($_REQUEST["vName"]) . "'";
            }
        } else {
            $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '" . trim($_REQUEST["vName"]) . "%'";
        }
    }


    switch ($display_order) {
        case "0":
        $sortname = "user_mas.\"iUserId\"";
        break;
        case "1":
        $sortname = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\" )";
        break;
        case "2":
        $sortname = "user_mas.\"vEmail\"";
        break;
        case "3":
        $sortname = "user_mas.\"vUsername\"";
        break;
        case "5":
        $sortname = "access_group_mas.\"vAccessGroup\"";
        break;
        case "7":
        $sortname = "user_mas.\"dDate\"";
        break;
        case "8":
        $sortname = "user_mas.\"iStatus\"";
        break;
        default:
        $sortname = 'user_mas."dDate"';
        break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = "access_group_mas.\"vAccessGroup\"";
    $join_fieds_arr[] = "user_details.\"vCompanyName\"";
    $join_arr = array();
    $join_arr[] = "LEFT JOIN access_group_mas ON user_mas.\"iAGroupId\" = access_group_mas.\"iAGroupId\"";
    $join_arr[] = "LEFT JOIN user_details ON user_mas.\"iUserId\" = user_details.\"iUserId\"";
    $UserObj->join_field = $join_fieds_arr;
    $UserObj->join = $join_arr;
    $UserObj->where = $where_arr;
    $UserObj->param['order_by'] = $sortname . " " . $dir;
    $UserObj->param['limit'] = $limit;
    $UserObj->setClause();
    $UserObj->debug_query = false;
    $rs_user = $UserObj->recordset_list();
    $rs_agroup = $UserObj->recordset_list();


        // Paging Total Records
    $total = $UserObj->recordset_total();
        // Paging Total Records

    //echo $page_length;exit;
   // $jsonData = array('sEcho' => $sEcho, 'recordsTotal' => $total, 'recordsFiltered' => $total, 'data' => array());
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $ni = count($rs_user);
    $entry =array();
    if ($ni > 0) {
        for ($i = 0; $i < $ni; $i++) {
           $actions=$delete = "";
           if($access_group_var_edit == "1"){
                $actions .= '<a class="btn btn-outline-secondary" title="Edit" href="' . $site_url . 'user/edit&mode=Update&iUserId=' . $rs_user[$i]['iUserId'] . '"><i class="fa fa-edit"></i></a>';
            }
            if ($access_group_var_delete == 1) {
                $actions .= ' <a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_user[$i]['iUserId'].');"><i class="fa fa-trash"></i></a>';
            }

            $UserObj->user_clear_variable();
            $where_arr = array();
            $join_arr = array();
            $join_fieds_arr = array();
            $join_fieds_arr[] = 'department_mas."vDepartment"';
            $join_arr[] = 'INNER JOIN department_mas ON user_department."iDepartmentId" = department_mas."iDepartmentId"';
            $where_arr[] = 'user_department."iUserId" = ' . $rs_user[$i]['iUserId'];
            $UserObj->join_field = $join_fieds_arr;
            $UserObj->join = $join_arr;
            $UserObj->where = $where_arr;
            $UserObj->param['limit'] = 0;
            $UserObj->setClause();
            $rs_user_dept = $UserObj->user_department_list();
            $pi = count($rs_user_dept);
            $user_department = '';
            if ($pi > 0) {
                for ($p = 0; $p < $pi; $p++) {
                    $user_department .= $rs_user_dept[$p]['vDepartment'] . ', ';					
                }
                $user_department = substr($user_department, 0, -2);
            }
            $user_department = wordwrap($user_department,40,"<br>\n");

            $entry[] = array(
                // "checkbox" => '<input type="checkbox" class="list" value="' . $rs_user[$i]['iUserId'] . '"/>',
                "checkbox" => $rs_user[$i]['iUserId'],
                "name" => gen_strip_slash($rs_user[$i]['vFirstName']) . " " . gen_strip_slash($rs_user[$i]['vLastName']),
                "vEmail" => $rs_user[$i]['vEmail'],
                'vUsername' => gen_strip_slash($rs_user[$i]['vUsername']),
                'vDepartment' => $user_department,
                'vAccessGroup' => gen_strip_slash($rs_user[$i]['vAccessGroup']),
                'vLoginHistory' => '<a class="btn btn-outline-primary" title="View" href="' . $site_url . 'login_history/list&iUserId=' . $rs_user[$i]['iUserId'] . '"  target="_blank"><i class="fa fa-eye"></i></a>',
                'dDate' =>  date_getDateTime($rs_user[$i]['dDate']),
                'iStatus'=>'<span data-toggle="tooltip" data-placement="top" title="'.gen_status($rs_user[$i]['iStatus']).'" class="badge badge-pill badge-'.$status_color[ gen_status($rs_user[$i]['iStatus'])].'">&nbsp;</span>',
                "actions" => ($actions == "")?"---":$actions
            );
        }
    }

    $jsonData['aaData'] = $entry;
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    //echo"<pre>";print_r($jsonData);exit;

    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
} else if ($mode == "GetCountyFromState") {
    $data = '';
    $data .= '<option value="">--- Select ---</option>';

    $pageMode = $_REQUEST['pageMode'];
    $sql_county = "SELECT * From county_mas  ORDER BY \"vCounty\"";
    $rs_county = $sqlObj->Getall($sql_county);
    $ni = count($rs_county);


    if ($ni > 0) {
        for ($i = 0; $i < $ni; $i++) {
            $selected = '';
            
            if ($rs_county[$i]['iCountyId'] == $_POST['iCountyId'])
                $selected = 'selected';

            $data .= '<option value="' . $rs_county[$i]['iCountyId'] . '" ' . $selected . '>' . $rs_county[$i]['vCounty'] . '</option>';
        }
    }
    $jsonData = array('total' => $ni, 'county' => $data);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------   
} else if ($mode == "GetCityFromCounty") {
    $data = '';
    $data .= '<option value="">--- Select ---</option>';
    if ($_POST['iCountyId'] != "") {
        $pageMode = $_REQUEST['pageMode'];
        $sql_city = "SELECT * From city_mas ORDER BY \"vCity\"";
        $rs_city = $sqlObj->Getall($sql_city);
        $ni = count($rs_city);
        if ($ni > 0) {
            for ($i = 0; $i < $ni; $i++) {
                $selected = '';
                if ($rs_city[$i]['iCityId'] == $_POST['iCityId'])
                    $selected = 'selected';

                $data .= '<option value="' . $rs_city[$i]['iCityId'] . '" ' . $selected . '>' . $rs_city[$i]['vCity'] . '</option>';
            }
        }
    }
    $jsonData = array('total' => $ni, 'city' => $data);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------   
} else if ($mode == "DuplicateUsernameCheck") {
    $vUsername = $_POST['vUsername'];

    $where_arr = array();
    $where_arr[] = "user_mas.\"vUsername\" = '" . $vUsername . "'";

    $UserObj->where = $where_arr;
    $UserObj->setClause();
    $UserObj->param['limit'] = " LIMIT 1";
    $rs_user = $UserObj->recordset_list();

    $jsonData = array('total' => count($rs_user));
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------   
} else if($_REQUEST['mode'] == "Update")
{   

    $file_name = $file_msg ="";
    //echo "<pre>";print_r($_FILES);exit;
    if($_FILES['vImage']['name'] != ""){
        $folder = create_image_folder($sess_iCountySaasId,$user_path);
        // echo $sess_iCountySaasId;
        //     echo $folder;
        //     exit;
        $file_arr = img_fileUpload("vImage", $folder, '', $valid_ext = array('jpg','jpeg','gif','png'));
        $file_name = $file_arr[0];
        $file_msg = $file_arr[1];
    }else{
       $file_name = $_POST['vImage_old'];
    }

    $update_array = array(
        "iUserId" => $_POST['iUserId'],
        "iAGroupId" => $_POST['iAGroupId'],
        "iDepartmentId" => explode(',',$_POST['iDepartmentId']),
        "iZoneId" => $_POST['iZoneId'],
        "vFirstName" => addslashes($_POST['vFirstName']),
        "vLastName" => addslashes($_POST['vLastName']),
        "vUsername" => addslashes($_POST['vUsername']),
        //"vPassword" => $encryptedPassword['encryptedPassword'],
        "vPassword" => $_POST['vPassword'],
        "vEmail" => addslashes($_POST['vEmail']),
        "iStatus" => $_POST['iStatus'],
        "iType" => $_POST['iType'],
        "vCompanyName" => addslashes($_POST['vCompanyName']),
        "vCompanyNickName" => addslashes($_POST['vCompanyNickName']),
        "vAddress1"  => addslashes($_POST['vAddress1']),
        "vAddress2"  => addslashes($_POST['vAddress2']),
        "vStreet"    => addslashes($_POST['vStreet']),
        "vCrossStreet" => addslashes($_POST['vCrossStreet']),
        "iZipcode"       => $_POST['iZipcode'],
        "iStateId"      => $_POST['iStateId'],
        "iCountyId"       => $_POST['iCountyId'],
        "iCityId"        => $_POST['iCityId'],
        "iZoneId"       => $_POST['iZoneId'],
        "vLatitude"     => $_POST['vLatitude'],
        "vLongitude"    => $_POST['vLongitude'],
        "vPhone" => addslashes($_POST['vPhone']),
        "vCell" => addslashes($_POST['vCell']),
        "vFax" => addslashes($_POST['vFax']),
        "vADPFileNumber" => addslashes($_POST['vADPFileNumber']),
        "vImage" => $file_name,
        "zoneId_arr" => explode(',',$_POST['zoneId_arr'])
    );
        ## Function to write query in temp file.
        //gen_writeDataInTmpFile($update_array);
      //echo "<pre>";print_r($update_array);exit();
        $UserObj->update_arr = $update_array;
        $rs_db = $UserObj->update_records();
        if($rs_db){

            if($_SESSION["sess_iUserId" . $admin_panel_session_suffix] == $_POST['iUserId']) {
                $_SESSION["sess_vImage_url" . $admin_panel_session_suffix] = $user_url.$_SESSION["sess_iCountySaasId" . $admin_panel_session_suffix]."/".$file_name;
            }
            $result['error'] = 0 ;
               // $result['msg'] = MSG_UPDATE;
            $result['msg'] = MSG_UPDATE.$file_msg;
        }else{
            $result['error'] = 1 ;
                //$result['msg'] = MSG_UPDATE_ERROR;
            $result['msg'] = MSG_UPDATE_ERROR.$file_msg;

        }
        //  $jsonData = array('total' => $rs_db);
        # -----------------------------------
        # Return jSON data.
        # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------
} else if ($mode == "Add") {
    
    $result = array();
    $iUserId = "";
    $vUsername = $_POST['vUsername'];

    $where_arr = array();
    $where_arr[] = "user_mas.\"vUsername\" = '" . $vUsername . "'";

    $UserObj->where = $where_arr;
    $UserObj->param['limit'] = " LIMIT 1";
    $UserObj->setClause();
    $rs_user = $UserObj->recordset_list();
    $result = array('duplicate_check_tot' => count($rs_user));

    if (count($rs_user) == 0) {
        $file_name = $file_msg = "";

        if($_FILES['vImage']['name'] != ""){
            
            $folder = create_image_folder($sess_iCountySaasId,$user_path);
            $file_arr = img_fileUpload("vImage", $folder, '', $valid_ext = array('jpg','jpeg','gif','png'));
            
            $file_name = $file_arr[0];
            $file_msg = $file_arr[1];
        }
        
        $encryptedPassword = encrypt_password($_POST['vPassword']);

        $insert_array = array("iAGroupId" => $_POST['iAGroupId'],
            "iDepartmentId" => explode(',',$_POST['iDepartmentId']),
            "iZoneId" => $_POST['iZoneId'],
            "vFirstName" => addslashes($_POST['vFirstName']),
            "vLastName" => addslashes($_POST['vLastName']),
            "vUsername" => addslashes($_POST['vUsername']),
            "vPassword" => $encryptedPassword['encryptedPassword'],
            "vEmail" => addslashes($_POST['vEmail']),
            "vFromIP" => getIP(),
            "iStatus" => $_POST['iStatus'],
            "iType" => $_POST['iType'],
            "dDate" => date_getSystemDateTime(),
            "vCompanyName" => addslashes($_POST['vCompanyName']),
            "vCompanyNickName" => addslashes($_POST['vCompanyNickName']),
            "vAddress1"  => addslashes($_POST['vAddress1']),
            "vAddress2"  => addslashes($_POST['vAddress2']),
            "vStreet"    => addslashes($_POST['vStreet']),
            "vCrossStreet" => addslashes($_POST['vCrossStreet']),
            "iZipcode"       => $_POST['iZipcode'],
            "iStateId"      => $_POST['iStateId'],
            "iCountyId"       => $_POST['iCountyId'],
            "iCityId"        => $_POST['iCityId'],
            "iZoneId"       => $_POST['iZoneId'],
            "vLatitude"     => $_POST['vLatitude'],
            "vLongitude"    => $_POST['vLongitude'],
            "vPhone" => addslashes($_POST['vPhone']),
            "vCell" => addslashes($_POST['vCell']),
            "vNickName" => "",
            "vFax" => addslashes($_POST['vFax']),
            "vADPFileNumber" => addslashes($_POST['vADPFileNumber']),
            "sSalt" => addslashes($encryptedPassword['salt']),
            "vImage" => $file_name,
            "zoneId_arr" => explode(',',$_POST['zoneId_arr'])
            );
        
        $UserObj->insert_arr = $insert_array;
        $UserObj->setClause();
        $iUserId = $UserObj->add_records();

        if ($iUserId != "" && $_POST['notify'] == 1) {
            sendSystemMail("User", "Registration", $iUserId);
        }
        if($iUserId){
            $result['iUserId'] = $iUserId;
            $result['error'] = 0 ;
            $result['msg'] = MSG_ADD.$file_msg;
        }else{
            $result['error'] = 1 ;
            $result['msg'] = MSG_ADD_ERROR.$file_msg;
        }
    }
        //$jsonData = array('iUserId' => $iUserId);
    
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # -----------------------------------   
} else if ($mode == "Delete") {
    $iUserId = $_POST['iUserId'];
    $result  = array();
    $rs_tot = $UserObj->delete_single_record($iUserId);

    if($rs_tot){
     $result['msg'] = MSG_DELETE;
     $result['error']= 0 ;
 }else{
   $result['msg'] = MSG_DELETE_ERROR;
   $result['error']= 1 ;

}
    //$jsonData = array('total' => $rs_tot);
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
echo json_encode($result);
hc_exit();
    # -----------------------------------   
} else if($mode== "Excel"){
    $where_arr = array();
    if ($query) {
        $where_arr[] = $qtype . " LIKE '" . addslashes($query) . "%'";
    }

    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));
    if ($Keyword != "") {
        if ($vOptions == "Name") {
            $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") ILIKE '" . $Keyword . "%'";
        } else if ($vOptions == "iStatus") {
            if (strtolower($Keyword) == "active") {
                $where_arr[] = "user_mas.\"iStatus\" = '1'";
            } else if (strtolower($Keyword) == "inactive") {
                $where_arr[] = "user_mas.\"iStatus\" = '0'";
            }
        } else {
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


    if ($_REQUEST['iDepartmentId'] != "") {
       $where_arr[] = 'user_mas."iUserId" IN (SELECT user_department."iUserId" FROM user_department WHERE user_department."iDepartmentId" = '.$_REQUEST['iDepartmentId'].')';
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

if ($_REQUEST['vEmail'] != "") {
    if ($_REQUEST['vEmailDD'] != "") {
        if ($_REQUEST['vEmailDD'] == "Begins") {
            $where_arr[] = 'user_mas."vEmail" LIKE \'' . trim($_REQUEST['vEmail']) . '%\'';
        } else if ($_REQUEST['vEmailDD'] == "Ends") {
            $where_arr[] = 'user_mas."vEmail" LIKE \'%' . trim($_REQUEST['vEmail']) . '\'';
        } else if ($_REQUEST['vEmailDD'] == "Contains") {
            $where_arr[] = 'user_mas."vEmail" LIKE \'%' . trim($_REQUEST['vEmail']) . '%\'';
        } else if ($_REQUEST['vEmailDD'] == "Exactly") {
            $where_arr[] = 'user_mas."vEmail" = \'' . trim($_REQUEST['vEmail']) . '\'';
        }
    } else {
        $where_arr[] = 'user_mas."vEmail" LIKE \'' . trim($_REQUEST['vEmail']) . '%\'';
    }
}

if ($_REQUEST['vName'] != "") {
    if ($_REQUEST['vNameDD'] != "") {
        if ($_REQUEST['vNameDD'] == "Begins") {
               // $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '" . trim($_REQUEST["vName"]) . "%'";
            $where_arr[] = "user_mas.\"vFirstName\" LIKE '" . trim($_REQUEST["vName"]) . "%'";
        } else if ($_REQUEST['vNameDD'] == "Ends") {
                //$where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '%" . trim($_REQUEST["vName"]) . "'";
            $where_arr[] = "user_mas.\"vLastName\" LIKE '%" . trim($_REQUEST["vName"]) . "'";
        } else if ($_REQUEST['vNameDD'] == "Contains") {
            $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '%" . trim($_REQUEST["vName"]) . "%'";
        } else if ($_REQUEST['vNameDD'] == "Exactly") {
            $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") = '" . trim($_REQUEST["vName"]) . "'";
        }
    } else {
        $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '" . trim($_REQUEST["vName"]) . "%'";
    }
}

$join_fieds_arr = array();
$join_fieds_arr[] = "access_group_mas.\"vAccessGroup\"";
$join_fieds_arr[] = "state_mas.\"vState\"";
$join_fieds_arr[] = "county_mas.\"vCounty\"";
$join_fieds_arr[] = "city_mas.\"vCity\"";
$join_fieds_arr[] = "user_details.\"vCompanyName\",user_details.\"vAddress\",user_details.\"vArea\",user_details.\"vZipCode\",user_details.\"vPhone\",user_details.\"vCell\",user_details.\"vFax\"";
$join_arr = array();
$join_arr[] = "LEFT JOIN access_group_mas ON user_mas.\"iAGroupId\" = access_group_mas.\"iAGroupId\"";
$join_arr[] = "LEFT JOIN user_details ON user_mas.\"iUserId\" = user_details.\"iUserId\"";
$join_arr[] = "LEFT JOIN city_mas ON user_details.\"iCityId\" = city_mas.\"iCityId\"";
$join_arr[] = "LEFT JOIN state_mas ON user_details.\"iStateId\" = state_mas.\"iStateId\"";
$join_arr[] = "LEFT JOIN county_mas ON user_details.\"iCountyId\" = county_mas.\"iCountyId\"";
$UserObj->join_field = $join_fieds_arr;
$UserObj->join = $join_arr;
$UserObj->where = $where_arr;
$UserObj->param['order_by'] = "user_mas.\"iUserId\"";
$UserObj->param['limit'] = "";
$UserObj->setClause();
$UserObj->debug_query = false;
$rs_export = $UserObj->recordset_list();
$cnt_export = count($rs_export);
       // echo "<pre>";print_r($rs_export);exit();
include_once($class_path.'PHPExcel/PHPExcel.php'); 
    //include_once($class_path.'PHPExcel-1.8/PHPExcel.php'); 
       // // Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$file_name = "user_".time().".xlsx";

if($cnt_export >0) {

    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Id')
    ->setCellValue('B1', 'Name')
    ->setCellValue('C1', 'Email')
    ->setCellValue('D1', 'User Name')
    ->setCellValue('E1', 'Department')
    ->setCellValue('F1', 'AccessGroup')
    ->setCellValue('G1', 'Company')
    ->setCellValue('H1', 'Address')
    ->setCellValue('I1', 'Area')
    ->setCellValue('J1', 'State')
    ->setCellValue('K1', 'County')
    ->setCellValue('L1', 'City')
    ->setCellValue('M1', 'Zip Code')
    ->setCellValue('N1', 'Phone')
    ->setCellValue('O1', 'Cell')
    ->setCellValue('P1', 'Fax')
    ->setCellValue('Q1', 'Last Login');

    for($e=0; $e<$cnt_export; $e++) {

        $UserObj->user_clear_variable();
        $where_arr = array();
        $join_arr = array();
        $join_fieds_arr = array();
        $join_fieds_arr[] = 'department_mas."vDepartment"';
        $join_arr[] = 'INNER JOIN department_mas ON user_department."iDepartmentId" = department_mas."iDepartmentId"';
        $where_arr[] = 'user_department."iUserId" = ' . $rs_export[$e]['iUserId'];
        $UserObj->join_field = $join_fieds_arr;
        $UserObj->join = $join_arr;
        $UserObj->where = $where_arr;
        $UserObj->param['limit'] = 0;
        $UserObj->setClause();
        $rs_user_dept = $UserObj->user_department_list();
        $pi = count($rs_user_dept);
        $user_department = '';
        if ($pi > 0) {
            for ($p = 0; $p < $pi; $p++) {
                $user_department .= $rs_user_dept[$p]['vDepartment'] . ', ';                    
            }
            $user_department = substr($user_department, 0, -2);
        }
        $user_department = wordwrap($user_department,40,"\n");

        $name = gen_strip_slash($rs_export[$e]['vFirstName']) . ' ' . gen_strip_slash($rs_export[$e]['vLastName']);
        $dLastAccess = date_getDateTime($rs_export[$e]['dLastAccess']);

        $objPHPExcel->getActiveSheet()
        ->setCellValue('A'.($e+2), $rs_export[$e]['iUserId'])
        ->setCellValue('B'.($e+2), $name)
        ->setCellValue('C'.($e+2), $rs_export[$e]['vEmail'])
        ->setCellValue('D'.($e+2), $rs_export[$e]['vUsername'])
        ->setCellValue('E'.($e+2), $user_department)
        ->setCellValue('F'.($e+2), $rs_export[$e]['vAccessGroup'])
        ->setCellValue('G'.($e+2), $rs_export[$e]['vCompanyName'])
        ->setCellValue('H'.($e+2), $rs_export[$e]['vAddress'])
        ->setCellValue('I'.($e+2), $rs_export[$e]['vArea'])
        ->setCellValue('J'.($e+2), $rs_export[$e]['vState'])
        ->setCellValue('K'.($e+2), $rs_export[$e]['vCountry'])
        ->setCellValue('L'.($e+2), $rs_export[$e]['vCity'])
        ->setCellValue('M'.($e+2), $rs_export[$e]['vZipCode'])
        ->setCellValue('N'.($e+2), $rs_export[$e]['vPhone'])
        ->setCellValue('O'.($e+2), $rs_export[$e]['vCell'])
        ->setCellValue('P'.($e+2), $rs_export[$e]['vFax'])
        ->setCellValue('Q'.($e+2), $dLastAccess);
    }

    /* Set Auto width of each comlumn */
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

    /* Set Font to Bold for each comlumn */
    $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->setBold(true);


    /* Set Alignment of Selected Columns */
    $objPHPExcel->getActiveSheet()->getStyle("A1:A".($e+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('User');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$result_arr  = array();
       //  $objWriter  =   new PHPExcel_Writer_Excel2007($objPHPExcel);
       //  //$objWriter->save('php://output');

         //save in file 
$objWriter->save($temp_gallery.$file_name);
$result_arr['isError'] = 0;
$result_arr['file_path'] = base64_encode($temp_gallery.$file_name);
$result_arr['file_url'] = base64_encode($temp_gallery_url.$file_name);
    # -------------------------------------

echo json_encode($result_arr);
exit;
}

// department dropdown
$where_arr = array();
$where_arr[] = "department_mas.\"iStatus\"='1'";
$DepartmentObj->where = $where_arr;
$DepartmentObj->param['order_by'] = "department_mas.\"vDepartment\"";
$DepartmentObj->setClause();
$rs_department = $DepartmentObj->recordset_list();
$smarty->assign("rs_department", $rs_department);

// accessgroup dropdown
$where_arr = array();
$where_arr[] = "access_group_mas.\"iStatus\"='1'";
$AccessGroupObj->where = $where_arr;
$AccessGroupObj->param['order_by'] = "access_group_mas.\"vAccessGroup\"";
$AccessGroupObj->setClause();
$rs_agroup = $AccessGroupObj->recordset_list();
$smarty->assign("rs_agroup", $rs_agroup);



$module_name = "User List";
$module_title = "User";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("option_arr", $option_arr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("iAGroupId", $_GET['iAGroupId']);
$smarty->assign("access_group_var_add", $access_group_var_add);
$smarty->assign("access_group_var_CSV", $access_group_var_CSV);

?>