<?

include_once($controller_path . "user.inc.php");

$UserObj = new User();
$sql = 'SELECT * FROM session_mas WHERE "iUserId"=' . $_COOKIE['userid'] . 'LIMIT 1';
$rs_uro = $sqlObj->GetAll($sql);
//echo "<pre>";print_r($rs_uro);exit;

//expiration time
$the_date = new DateTime($rs_uro[0]['sExpireTime']);
$triggerOn11 = $the_date->format('U');
//current time
$the_date = new DateTime('now');
$triggerOn22 = $the_date->format('U');

//if expiration time less then current time logout user
if ($triggerOn11 < $triggerOn22 && isset($_COOKIE['userid'])) {
    if (isset($_COOKIE['userid'])) {
        unset($_COOKIE['userid']);
        setcookie('userid', null, -1, '/');
    }
    if (isset($_COOKIE['sessionid'])) {
        unset($_COOKIE['sessionid']);
        setcookie('sessionid', null, -1, '/');
    }

    header("Location:" . $site_url . "home/logout");
    exit;
} else {
    //login
}
if ($page == "c-home" && $_SESSION["sess_iUserId" . $admin_panel_session_suffix] != "" && $page != 'm-logout') { //Login page then redirect to dashboard
    $module_prefix = "dashboard/";
    $page = "m-dashboard";
    $prefix = "m";
    $script = "dashboard";
    $modid = 1;
    $middle = "middle";
} else if (isset($_COOKIE["userid"]) && $_SESSION["sess_iUserId" . $admin_panel_session_suffix] == "" && $page != 'm-logout') {
	//$vUsername = $_COOKIE["username"];
	//$vPassword = $_COOKIE["password"];
	$iUserId = $_COOKIE["userid"];
	$where_arr = array();
	$join_fieds_arr = array();
	$join_arr = array();
	//$where_arr[] = "user_mas.\"vUsername\"='" . $vUsername . "'";
	//$where_arr[] = "user_mas.\"vPassword\"='" . $vPassword . "'";
	$where_arr[] = "user_mas.\"iUserId\"='" . $iUserId . "'";
	$join_fieds_arr[] = "access_group_mas.\"vAccessGroup\"";
	$join_arr[] = "LEFT JOIN access_group_mas ON user_mas.\"iAGroupId\" = access_group_mas.\"iAGroupId\"";
	$UserObj->join_field = $join_fieds_arr;
	$UserObj->join = $join_arr;
	$UserObj->where = $where_arr;
	$UserObj->param['limit'] = "LIMIT 1";
	$UserObj->setClause();
	$result = $UserObj->recordset_list();

    if ($result && ($result[0]["iStatus"] == '1')) {
        $sql = 'SELECT "iURO" FROM user_recent_online WHERE "iUserId"=' . gen_allow_null_int($result[0]["iUserId"]) . ' ORDER BY "iURO" DESC LIMIT 1';
        $rs_uro = $sqlObj->GetAll($sql);
        if ($rs_uro[0]['iURO'] != "") {
            $sql = 'UPDATE user_recent_online SET "vSessionId"=' . gen_allow_null_char(session_id()) . ', "vIP"=' . gen_allow_null_char(getIP()) . ', "vTimeEntry"=' . gen_allow_null_char(time()) . ' WHERE "iURO" = ' . gen_allow_null_int($rs_uro[0]['iURO']);
            $sqlObj->Execute($sql);
        } else {
            $sql = 'INSERT INTO user_recent_online("iUserId", "vSessionId", "vIP", "vTimeEntry", "iLogin") values (' . gen_allow_null_int($result[0]["iUserId"]) . ', ' . gen_allow_null_char(session_id()) . ', ' . gen_allow_null_char(getIP()) . ', ' . gen_allow_null_char(time()) . ', 1)';
            $sqlObj->Execute($sql);
        }

        if ($_SERVER['HTTP_HOST'] != "192.168.32.16") {
            $sql = 'select "vSessionId" from user_recent_online WHERE "iUserId"=' . gen_allow_null_int($result[0]["iUserId"]) . ' AND "iLogin" = 1 ORDER BY "iURO" DESC LIMIT 1';
            $rs_recent = $sqlObj->GetAll($sql);
            if ($rs_recent[0]['vSessionId'] != session_id()) {
                $jsonData = array('login' => -1);
                echo json_encode($jsonData);
                hc_exit();
            }
        }
        $_SESSION["sess_iUserId" . $admin_panel_session_suffix] = $result[0]["iUserId"];
        $_SESSION["sess_iAGroupId" . $admin_panel_session_suffix] = $result[0]["iAGroupId"];
        $_SESSION["sess_vAccessGroup" . $admin_panel_session_suffix] = gen_strip_slash($result[0]["vAccessGroup"]);
        $_SESSION["sess_vName" . $admin_panel_session_suffix] = gen_strip_slash($result[0]["vFirstName"]) . " " . gen_strip_slash($result[0]["vLastName"]);
        $_SESSION["sess_vUsername" . $admin_panel_session_suffix] = $result[0]["vUsername"];
        $_SESSION["sess_iStatus" . $admin_panel_session_suffix] = $result[0]["iStatus"];

        /* --------------------------- User Preferences ----------------------------------- */

        $join_fieds_arr = array();
        $join_arr = array();
        $where_arr = array();

        $where_arr[] = 'user_preference."iUserId" = ' . $result[0]["iUserId"];
        $UserObj->user_clear_variable();
        $UserObj->join_field = $join_fieds_arr;
        $UserObj->join = $join_arr;
        $UserObj->where = $where_arr;
        $UserObj->param['limit'] = 'LIMIT 1';
        $UserObj->setClause();
        $rs_user_preference = $UserObj->user_preference_list();


        $_SESSION["sess_user_preference" . $admin_panel_session_suffix] = $rs_user_preference[0];
        /* --------------------------- User Preferences ----------------------------------- */

        #=================================================================================
        # FOR Last Login Details.....
        #=================================================================================
        $dLastAccess = time();
        $dDate = date_getSystemDateTime();
        $vFromIP = getIP();

        $sql = "update user_mas set \"dLastAccess\" ='" . $dDate . "' where \"iUserId\"='" . $_SESSION['sess_iUserId' . $admin_panel_session_suffix] . "'";
        $db_sql = $sqlObj->Execute($sql);
        #=================================================================================
        #=================================================================================
        # Query for Inserting Login Details.....
        #=================================================================================
        $sql_logs = "insert into login_logs_mas (\"iID\", \"vIP\", \"dLoginDate\") values ('" . $_SESSION['sess_iUserId' . $admin_panel_session_suffix] . "', '" . $vFromIP . "', '" . $dDate . "')";
        $db_logs = $sqlObj->Execute($sql_logs);
        $id_log = $sqlObj->Insert_ID();
        $_SESSION['sess_id_log' . $admin_panel_session_suffix] = $id_log;
        #=================================================================================
        if (!isset($page)) {
            $module_prefix = "dashboard/";
            $page = "m-dashboard";
            $prefix = "m";
            $script = "dashboard";
            $modid = 1;
            $middle = "middle";
        }
    }
}
?>