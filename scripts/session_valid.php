<?
if(isset($_SESSION["sess_iUserId".$admin_panel_session_suffix]) && $_SESSION["sess_iUserId".$admin_panel_session_suffix] != '') {
		$sql = 'select "vSessionId" from user_recent_online WHERE "iUserId"='.gen_allow_null_int($_SESSION["sess_iUserId".$admin_panel_session_suffix]).' AND "iLogin" = 1 ORDER BY "iURO" DESC LIMIT 1';
		$rs_recent = $sqlObj->GetAll($sql);
		if($rs_recent[0]['vSessionId'] != session_id()){
			header("Location:".$site_url."home/logout");
			//hc_exit();
		}
}else{
	if(isset($page) && !in_array($page, ["m-login","m-logout" , "c-home"]) ) {
		header("Location:".$site_url."home/logout");
		hc_exit();	
	}
}
?>
