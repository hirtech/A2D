<?
if ($_SESSION['sess_iUserId' . $admin_panel_session_suffix] != '') {
    $sql = "UPDATE login_logs_mas SET \"dLogoutDate\" = '" . date_getSystemDateTime() . "' WHERE \"iID\" = '" . $_SESSION['sess_iUserId' . $admin_panel_session_suffix] . "' And \"iLLogsId\" = '".$_SESSION['sess_id_log' . $admin_panel_session_suffix]."' ";
    $rs = $sqlObj->Execute($sql);
    if ($rs) {
               // echo "<pre>";print_r($_SESSION); exit();

        if ($SESSION_STORED_IN_DB == 'Y') {
            include_once($controller_path."session.inc.php");
            $SessionObj = new Session();
            $SessionObj->remove_sesssion(session_id(), $_SESSION["sess_iUserId" . $admin_panel_session_suffix]);
        }

        //Delete from recent online table
        $sql = 'Delete FROM user_recent_online WHERE "vSessionId" = ' . gen_allow_null_char(session_id());
        $sqlObj->Execute($sql);


        $msg = rawurlencode(LOGOUT_SUCCESSFULLY);
        $_SESSION["sess_iUserId" . $admin_panel_session_suffix] = "";
        $_SESSION["sess_iAGroupId" . $admin_panel_session_suffix] = "";
        $_SESSION["sess_vAccessGroup" . $admin_panel_session_suffix] = "";
        $_SESSION["sess_vName" . $admin_panel_session_suffix] = "";
        $_SESSION["sess_vUsername" . $admin_panel_session_suffix] = "";
        $_SESSION["sess_eFrontSesssion" . $admin_panel_session_suffix] = "";
        $_SESSION["sess_eStatus" . $admin_panel_session_suffix] = "";
        $_SESSION["sess_id_log" . $admin_panel_session_suffix] = "";
        $_SESSION["sess_user_preference" . $admin_panel_session_suffix] = "";
        unset($_SESSION["sess_iUserId" . $admin_panel_session_suffix]);
        unset($_SESSION["sess_iAGroupId" . $admin_panel_session_suffix]);
        unset($_SESSION["sess_vAccessGroup" . $admin_panel_session_suffix]);
        unset($_SESSION["sess_vName" . $admin_panel_session_suffix]);
        unset($_SESSION["sess_vUsername" . $admin_panel_session_suffix]);
        unset($_SESSION["sess_eFrontSesssion" . $admin_panel_session_suffix]);
        unset($_SESSION["sess_eStatus" . $admin_panel_session_suffix]);
        unset($_SESSION["sess_id_log" . $admin_panel_session_suffix]);
        unset($_SESSION["sess_user_preference" . $admin_panel_session_suffix]);

        if (isset($_COOKIE['username'])) {
            unset($_COOKIE['username']);
            setcookie('username', null, -1, '/');
        }
        if (isset($_COOKIE['password'])) {
            unset($_COOKIE['password']);
            setcookie('password', null, -1, '/');
        }
        if (isset($_COOKIE['userid'])) {
            unset($_COOKIE['userid']);
            setcookie('userid', null, -1, '/');
        }
        if (isset($_COOKIE['sessionid'])) {
            unset($_COOKIE['sessionid']);
            setcookie('sessionid', null, -1, '/');
        }
    }
    header("Location:" . $site_url);
    exit;
} else {
    //$msg = "Logout Failed";
    header("Location:" . $site_url);
    exit;
}
?>
