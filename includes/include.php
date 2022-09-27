<?
$mod_access_arr = per_getAssocArray($_SESSION["sess_iAGroupId".$admin_panel_session_suffix],'');
// ****** Menu ****** //
$sql_menu = 'SELECT * FROM menu_mas WHERE "iStatus" = 1 ORDER BY "iParentId", "iDisplayOrder"';
$rs_menu = $sqlObj->GetAll($sql_menu);
//echo "<pre>";print_r($rs_menu);exit;
$mi = count($rs_menu);
$menu_arr = array();
if($mi > 0) {
	for($m = 0; $m< $mi; $m++){
		if($rs_menu[$m]['vURL'] == '') {
			$rs_menu[$m]['vURL'] = 'javascript:void(0);';
		}

		$rs_menu[$m]['vActiveClass'] = '';
		if($_REQUEST['module'] ==  $rs_menu[$m]['vURL'] || ($_REQUEST['module'] == '' && $rs_menu[$m]['vName'] == 'Dashboard')){
			$rs_menu[$m]['vActiveClass'] = 'active';
		}
		if($rs_menu[$m]['iParentId'] == 0){
			$acesscheck = ($rs_menu[$m]['vAccessModule'] != "")?explode(",", $rs_menu[$m]['vAccessModule']):array();
			
			$checkaccess = 0;
			if(count($acesscheck) > 0){
				for($t=0;$t<count($acesscheck);$t++){
					if($mod_access_arr[$acesscheck[$t]]['eList'] == 'Y'){
						$checkaccess = 1;
					}
				}
			}else{
				$checkaccess = 1;
			}
			
			if($checkaccess == 1){
				$rs_menu[$m]['vClassId'] = 'menu-'.$rs_menu[$m]['iMenuId'];
				$menu_arr[$rs_menu[$m]['iMenuId']] = $rs_menu[$m];
			}
		}else {
			$checkaccess = 0;
			$checkaccess = ($rs_menu[$m]['vAccessModule'] != "")?(($mod_access_arr[$rs_menu[$m]['vAccessModule']]['eList'] == 'Y')?1:0):0;
			if($checkaccess == 1){
				$rs_menu[$m]['vClassId'] = 'menu-'.$rs_menu[$m]['iParentId'];
				$menu_arr[$rs_menu[$m]['iParentId']]['submenu'][] = $rs_menu[$m];
			}
		}
	}
	 
}
//echo "<pre>";print_r($menu_arr);exit;
$smarty->assign("menu_arr", $menu_arr);
$smarty->assign('active_module_name',$_REQUEST['module']);

// ****** Menu ****** //	


//*****User wise panel Theme data******//
$sess_iUserId = $_SESSION["sess_iUserId".$admin_panel_session_suffix];
if($sess_iUserId != ""){
	$sql_custtheme = 'SELECT * FROM user_panel_customizer WHERE "iUserId" = '.$sess_iUserId.' LIMIT 1 ';
	$rs_custtheme = $sqlObj->GetAll($sql_custtheme);
	
	$mi = count($rs_custtheme);

	if($mi > 0) {
		$user_panel_theme_arr = array(
			"template_color" => $rs_custtheme[0]['vTemplateColor'],
			"template_layout" => $rs_custtheme[0]['vLayout'],
			"template_style" => $rs_custtheme[0]['vTemplateStyle'],
			"bCompactMenu" => $rs_custtheme[0]['bCompactSidebar'],
			"bsmallMenu" => $rs_custtheme[0]['bSmallMenu']
		);
	}else{
		$user_panel_theme_arr = $panel_default_customizer;
	}

}else{
	$user_panel_theme_arr = $panel_default_customizer;
}
$smarty->assign("user_panel_theme_arr", $user_panel_theme_arr);
//*****User wise panel Theme data******//
?>