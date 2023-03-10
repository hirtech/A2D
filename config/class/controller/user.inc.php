<?php

include_once("security_audit_log.inc.php");

class User {

    var $join_field = array();
    var $join = array();
    var $where = array();
    var $param = array();
    var $ids = 0;
    var $action;
    var $insert_arr = array();
    var $update_arr = array();
    var $join_field_str = "";
    var $where_clause = "";
    var $join_clause = "";
    var $order_by_clause = "";
    var $group_by_clause = "";
    var $limit_clause = "";
    var $debug_query = false;

    function User() {
        $this->SALObj = new Security_audit_log();
    }

    function setClause() {
        //Join Fields for select query	
        if (is_array($this->join_field) && count($this->join_field) > 0) {
            $this->join_field_str = ", " . implode(", ", $this->join_field);
        }
        // Join clause
        if (is_array($this->join) && count($this->join) > 0) {
            $this->join_clause = " " . implode(" ", $this->join);
        }
        // Where clause
        if (is_array($this->where) && count($this->where) > 0) {
            $this->where_clause = " WHERE " . implode(" AND ", $this->where);
        }

        //echo "123".$this->param['group_by'];exit;
        if (is_array($this->param) && count($this->param) > 0) {
            // Order by clause
            if (!empty($this->param['order_by']))
                $this->order_by_clause = " ORDER BY " . $this->param['order_by'];

            // Group by clause
            if (!empty($this->param['group_by']))
                $this->group_by_clause = " GROUP BY " . $this->param['group_by'];

            // Limit clause
            if (!empty($this->param['limit'])) {
                if (intval($this->param['limit']) > 0) {
                    //$this->limit_clause = " LIMIT 0, ".intval($this->param['limit']);
                    $this->limit_clause = " LIMIT " . intval($this->param['limit']) . " OFFSET 0";
                } else if (strstr($this->param['limit'], "LIMIT")) {
                    $this->limit_clause = " " . $this->param['limit'];
                } else {
                    $this->limit_clause = " LIMIT " . $this->param['limit'];
                }
            } else {
                $this->limit_clause = "";
            }
        }
    }

    function recordset_list() {
        global $sqlObj;
        $sql = "SELECT user_mas.* " . $this->join_field_str . " FROM user_mas" . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
        //echo $sql;exit;
        //file_put_contents($site_path."a.txt", $sql);
        $rs_db = $sqlObj->GetAll($sql);
        $this->debug_query($sql);
        return $rs_db;
    }

    function recordset_total() {
        global $sqlObj;

        $sql = "SELECT user_mas.* " . $this->join_field_str . " FROM user_mas" . $this->join_clause . $this->where_clause . $this->group_by_clause;
        //$rs_db = $sqlObj->GetAll($sql);
        $rs_db = $sqlObj->Execute($sql);
        if ($rs_db === false)
            $count = 0;
        else
            $count = $rs_db->RecordCount();

        $this->debug_query($sql);
        return $count;
    }

    function delete_records() {
        global $sqlObj;
       
        $sql = "DELETE FROM user_mas" . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;

        $sqlObj->Execute($sql);
        $rs_del = $sqlObj->Affected_Rows();

        $sql = "DELETE FROM user_details WHERE \"iUserId\" IN (" . $_POST['iUserId'] . ")";
        $sqlObj->Execute($sql);

		$sql = "DELETE FROM user_panel_customizer WHERE \"iUserId\" IN (" . $_POST['iUserId'] . ")";
        $sqlObj->Execute($sql);

		$sql = "DELETE FROM user_zone WHERE \"iUserId\" IN (" . $_POST['iUserId'] . ")";
        $sqlObj->Execute($sql);

        $sql = "DELETE FROM user_department WHERE \"iUserId\" IN (" . $_POST['iUserId'] . ")";
        $sqlObj->Execute($sql);
        $this->debug_query($sql);

        /* -------------- Log Entry ------------- */
        $this->SALObj->type = 2;
        $this->SALObj->module_name = "user";
        $this->SALObj->audit_log_entry();
        /* -------------- Log Entry ------------- */

        return $rs_del;
    }

    function delete_single_record($iUserId) {
        global $sqlObj;
       
        $sql = "DELETE FROM user_mas WHERE \"iUserId\" = ".$iUserId;

        $sqlObj->Execute($sql);
        $rs_del = $sqlObj->Affected_Rows();

        $sql = "DELETE FROM user_details WHERE \"iUserId\" = ".$iUserId;
        $sqlObj->Execute($sql);

		$sql = "DELETE FROM user_panel_customizer WHERE \"iUserId\" IN (" . $_POST['iUserId'] . ")";
        $sqlObj->Execute($sql);

		$sql = "DELETE FROM user_zone WHERE \"iUserId\" IN (" . $_POST['iUserId'] . ")";
        $sqlObj->Execute($sql);

        $sql = "DELETE FROM user_network WHERE \"iUserId\" IN (" . $_POST['iUserId'] . ")";
        $sqlObj->Execute($sql);

        $sql = "DELETE FROM user_department WHERE \"iUserId\" = ".$iUserId;
        $sqlObj->Execute($sql);
        return $rs_del;
    }


    function add_records() {
        global $sqlObj, $admin_panel_session_suffix,$panel_default_customizer;

        if ($this->insert_arr) {
            $userid = "";
            $sql_user = "INSERT INTO user_mas (\"iAGroupId\", \"iZoneId\", \"vUsername\", \"vPassword\", \"vFirstName\", \"vLastName\", \"vNickName\", \"vEmail\", \"vFromIP\", \"dDate\", \"iStatus\", \"iType\", \"vADPFileNumber\", \"vLoginUserName\",\"vImage\", \"sSalt\", \"dAddedDate\") VALUES(" . gen_allow_null_int($this->insert_arr['iAGroupId']) . ",  " . gen_allow_null_int($this->insert_arr['iZoneId']) . ", " . gen_allow_null_char($this->insert_arr['vUsername']) . ", " . gen_allow_null_char($this->insert_arr['vPassword']) . ", " . gen_allow_null_char($this->insert_arr['vFirstName']) . ", " . gen_allow_null_char($this->insert_arr['vLastName']) . ", " . gen_allow_null_char($this->insert_arr['vNickName']) . ", " . gen_allow_null_char($this->insert_arr['vEmail']) . ", " . gen_allow_null_char($this->insert_arr['vFromIP']) . ", " . gen_allow_null_char($this->insert_arr['dDate']) . ", " . gen_allow_null_int($this->insert_arr['iStatus']) . ", " . gen_allow_null_int($this->insert_arr['iType']) . ", " . gen_allow_null_char($this->insert_arr['vADPFileNumber']) . ", " . gen_allow_null_char($_SESSION["sess_vName" . $admin_panel_session_suffix]) . ", " . gen_allow_null_char($this->insert_arr['vImage']) . ", " .gen_allow_null_char($this->insert_arr['sSalt']) . ",".gen_allow_null_char(date_getSystemDateTime()).")";

            $sqlObj->Execute($sql_user);
            $userid = $sqlObj->Insert_ID("user_mas","iUserId");
          // echo "user-".$userid ;exit();
            $this->debug_query($sql_user);
            if ($userid != "") {
                ###user department
                $iDepartmentId_arr = explode(",",$this->insert_arr['iDepartmentId']); 
                //$iDepartmentId_arr = $_POST['iDepartmentId']; 
                $di = count($iDepartmentId_arr);
                if($di > 0){
                    $user_dept_arr = array();
                    
                    for($d=0;$d<$di;$d++){
                        
                        $user_dept_arr[] = '('.gen_allow_null_int($iDepartmentId_arr[$d]).', '.gen_allow_null_int($userid).', '.gen_allow_null_char($_SESSION["sess_vName" . $admin_panel_session_suffix]).')';
                    }
                    
                    if(count($user_dept_arr) > 0){
                        $sql = 'INSERT INTO user_department("iDepartmentId", "iUserId", "vLoginUserName") VALUES '.implode(", ", $user_dept_arr);
                        $sqlObj->Execute($sql);
                    }
                }

				## Default RecLimit = 100;
				$iRecLimit = 100;


                $sql_user_details = "INSERT INTO user_details (\"iUserId\", \"iCompanyId\", \"vAddress1\", \"vAddress2\", \"vStreet\",\"vCrossStreet\", \"iZipcode\", \"iStateId\", \"iCountyId\", \"iCityId\", \"iZoneId\", \"vLatitude\", \"vLongitude\", \"vPhone\", \"vCell\", \"vFax\", \"vLoginUserName\",  \"iRecLimit\") VALUES (" . gen_allow_null_int($userid) . ", " . gen_allow_null_char($this->insert_arr['iCompanyId'])  . ", " .gen_allow_null_char($this->insert_arr['vAddress1']).", ".gen_allow_null_char($this->insert_arr['vAddress2']).", ".gen_allow_null_char($this->insert_arr['vStreet']).", ".gen_allow_null_char($this->insert_arr['vCrossStreet']).", ".gen_allow_null_char($this->insert_arr['iZipcode']).", ".gen_allow_null_char($this->insert_arr['iStateId']).", ".gen_allow_null_char($this->insert_arr['iCountyId']).", ".gen_allow_null_char($this->insert_arr['iCityId']).", ".gen_allow_null_char($this->insert_arr['iZoneId']).", ".gen_allow_null_char($this->insert_arr['vLatitude']).", ".gen_allow_null_char($this->insert_arr['vLongitude']).", " . gen_allow_null_char($this->insert_arr['vPhone']) . ", " . gen_allow_null_char($this->insert_arr['vCell']) . ", " . gen_allow_null_char($this->insert_arr['vFax']) . ", " . gen_allow_null_char($_SESSION["sess_vName" . $admin_panel_session_suffix]). ", ". gen_allow_null_int($iRecLimit) .")";
                $iUDetailId = $sqlObj->Execute($sql_user_details);

                $iNetworkId_arr = explode(",",$this->insert_arr['networkId_arr']);
                $pi = count($iNetworkId_arr);
                if($pi > 0){
                    $sql_sp = 'INSERT INTO user_network ("iUserId", "iNetworkId", "dAddedDate") VALUES ';
                    for($p=0;$p<$pi;$p++){
                        $sql_sp .= '('.gen_allow_null_int($this->update_arr['iUserId']).', '.gen_allow_null_int($iNetworkId_arr[$p]).', '.gen_allow_null_char(date_getSystemDateTime()).'), ';
                    }
                    $sqlObj->Execute(substr($sql_sp, 0, -2));
                }

                //Add Default user theme data
                $sql = 'INSERT INTO user_panel_customizer ("iUserId", "vTemplateColor", "vLayout", "vTemplateStyle", "bCompactSidebar", "bSmallMenu") VALUES (' . gen_allow_null_int($userid) . ',' . gen_allow_null_char($panel_default_customizer['template_color']) . ',' . gen_allow_null_char($panel_default_customizer['template_layout']) . ',' . gen_allow_null_char($panel_default_customizer['template_style']) . ',' . gen_allow_null_char($panel_default_customizer['bCompactMenu']) . ',' . gen_allow_null_char($panel_default_customizer['bsmallMenu']) . ')';

                $sqlObj->Execute($sql);
				
            }

            /* -------------- Log Entry ------------- */
            $this->SALObj->type = 0;
            $this->SALObj->module_name = "user";
            $this->SALObj->audit_log_entry();
            /* -------------- Log Entry ------------- */

            return $userid;
        }
    }

    function update_records() {
        global $sqlObj, $admin_panel_session_suffix;

        if ($this->update_arr) {            
            $encryptedPassword = encrypt_password($this->update_arr['vPassword']);
            //echo "<pre>"; print_r($encryptedPassword); exit;
            if($this->update_arr['vPassword'] != ''){

                $vPassword = $encryptedPassword['encryptedPassword'];
                $sSalt = $encryptedPassword['salt'];
                $password_str = ", \"vPassword\"=" . gen_allow_null_char($vPassword) . "";
                $salt_str = ", \"sSalt\"=" . gen_allow_null_char($sSalt) . "";
            }else{
                $password_str = "";
                $salt_str = "";
            }

            $sql_user = "UPDATE user_mas SET \"iAGroupId\"=" . gen_allow_null_int($this->update_arr['iAGroupId']) . ", \"iZoneId\"=" . gen_allow_null_int($this->update_arr['iZoneId']) . ", \"vUsername\"=" . gen_allow_null_char($this->update_arr['vUsername']) . "".$password_str.", \"vFirstName\"=" . gen_allow_null_char($this->update_arr['vFirstName']) . ", \"vLastName\"=" . gen_allow_null_char($this->update_arr['vLastName']) . ", \"vNickName\"=" . gen_allow_null_char($this->update_arr['vNickName']) . ", \"vEmail\"=" . gen_allow_null_char($this->update_arr['vEmail']) . ", \"iStatus\"=" . gen_allow_null_int($this->update_arr['iStatus']) . ", \"iType\"=" . gen_allow_null_int($this->update_arr['iType']) . ", \"vADPFileNumber\"=" . gen_allow_null_char($this->update_arr['vADPFileNumber']) . ", \"vLoginUserName\"=" . gen_allow_null_char($_SESSION["sess_vName" . $admin_panel_session_suffix]) . "".$salt_str.", \"vImage\" =". gen_allow_null_char($this->update_arr['vImage']).", \"dModifiedDate\" = ".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iUserId\"=" . $this->update_arr['iUserId'];
            $rs_up = $sqlObj->Execute($sql_user);
            //$this->debug_query($sql_user);
            if ($rs_up) {
                $sql_det = 'SELECT "iUDetailId" FROM user_details WHERE "iUserId" = '.$this->update_arr['iUserId'].' LIMIT 1';
                $rs_det = $sqlObj->GetAll($sql_det);
                //print_r($rs_det);exit;
                if(count($rs_det) > 0){
                    $sql_user_details = "UPDATE user_details SET \"iCompanyId\"=" . gen_allow_null_char($this->update_arr['iCompanyId'])  . ",\"vAddress1\"=".gen_allow_null_char($this->update_arr['vAddress1']).", \"vAddress2\"=".gen_allow_null_char($this->update_arr['vAddress2']).", \"vStreet\"=".gen_allow_null_char($this->update_arr['vStreet']).", \"vCrossStreet\"=".gen_allow_null_char($this->update_arr['vCrossStreet']).", \"iZipcode\"=".gen_allow_null_char($this->update_arr['iZipcode']).", \"iStateId\"=".gen_allow_null_char($this->update_arr['iStateId']).", \"iCountyId\"=".gen_allow_null_char($this->update_arr['iCountyId']).", \"iCityId\"=".gen_allow_null_char($this->update_arr['iCityId']).", \"iZoneId\"=".gen_allow_null_char($this->update_arr['iZoneId']).", \"vLatitude\"=".gen_allow_null_char($this->update_arr['vLatitude']).", \"vLongitude\"=".gen_allow_null_char($this->update_arr['vLongitude']).", \"vPhone\"=" . gen_allow_null_char($this->update_arr['vPhone']) . ", \"vCell\"=" . gen_allow_null_char($this->update_arr['vCell']) . ", \"vFax\"=" . gen_allow_null_char($this->update_arr['vFax']) . ", \"vLoginUserName\"=" . gen_allow_null_char($_SESSION["sess_vName" . $admin_panel_session_suffix]). " WHERE \"iUserId\"=" . $this->update_arr['iUserId'];
                    $sqlObj->Execute($sql_user_details);
                } else {
                    ## Default RecLimit = 100;
                    $iRecLimit = 100;
                    $sql_user_details = "INSERT INTO user_details (\"iUserId\", \"vCompanyName\", \"vCompanyNickName\", \"vAddress1\", \"vAddress2\", \"vStreet\",\"vCrossStreet\", \"iZipcode\", \"iStateId\", \"iCountyId\", \"iCityId\", \"iZoneId\", \"vLatitude\", \"vLongitude\", \"vPhone\", \"vCell\", \"vFax\", \"vLoginUserName\",  \"iRecLimit\") VALUES (" . gen_allow_null_int($this->update_arr['iUserId']) . ", " . gen_allow_null_char($this->update_arr['vCompanyName']) . ", " . gen_allow_null_char($this->update_arr['vCompanyName']) . ", " .gen_allow_null_char($this->update_arr['vAddress1']).", ".gen_allow_null_char($this->update_arr['vAddress2']).", ".gen_allow_null_char($this->update_arr['vStreet']).", ".gen_allow_null_char($this->update_arr['vCrossStreet']).", ".gen_allow_null_char($this->update_arr['iZipcode']).", ".gen_allow_null_char($this->update_arr['iStateId']).", ".gen_allow_null_char($this->update_arr['iCountyId']).", ".gen_allow_null_char($this->update_arr['iCityId']).", ".gen_allow_null_char($this->update_arr['iZoneId']).", ".gen_allow_null_char($this->update_arr['vLatitude']).", ".gen_allow_null_char($this->update_arr['vLongitude']).", " . gen_allow_null_char($this->update_arr['vPhone']) . ", " . gen_allow_null_char($this->update_arr['vCell']) . ", " . gen_allow_null_char($this->update_arr['vFax']) . ", " . gen_allow_null_char($_SESSION["sess_vName" . $admin_panel_session_suffix]). ", ". gen_allow_null_int($iRecLimit) .")";
                    //echo $sql_user_details;exit;
                    $sqlObj->Execute($sql_user_details);
                }
                
               //  echo $sql_user_details;exit();
				###user department
				$sql = 'DELETE FROM user_department WHERE "iUserId" = '.$this->update_arr['iUserId'];
				$sqlObj->Execute($sql);
				
				$iDepartmentId_arr = explode(",",$this->update_arr['iDepartmentId']); 
				$di = count($iDepartmentId_arr);
				if($di > 0){
                    $sql = 'INSERT INTO user_department("iDepartmentId", "iUserId", "vLoginUserName") VALUES ';
					$user_dept_arr = array();
					for($d=0;$d<$di;$d++){
						$sql .= '('.gen_allow_null_int($iDepartmentId_arr[$d]).', '.gen_allow_null_int($this->update_arr['iUserId']).', '.gen_allow_null_char($_SESSION["sess_vName" . $admin_panel_session_suffix]).'), ';
					}
                    $sqlObj->Execute(substr($sql, 0, -2));
				}

                $sql_del = 'DELETE FROM user_network WHERE "iUserId" = '.gen_allow_null_int($this->update_arr['iUserId']);
                $sqlObj->Execute($sql_del);
                
                $iNetworkId_arr = explode(",",$this->update_arr['networkId_arr']);
                $pi = count($iNetworkId_arr);
                if($pi > 0){
                    $sql_sp = 'INSERT INTO user_network ("iUserId", "iNetworkId", "dAddedDate") VALUES ';
                    for($p=0;$p<$pi;$p++){
                        $sql_sp .= '('.gen_allow_null_int($this->update_arr['iUserId']).', '.gen_allow_null_int($iNetworkId_arr[$p]).', '.gen_allow_null_char(date_getSystemDateTime()).'), ';
                    }
                    $sqlObj->Execute(substr($sql_sp, 0, -2));
                    
                }
            }

            /* -------------- Log Entry ------------- */
            $this->SALObj->type = 1;
            $this->SALObj->module_name = "user";
            $this->SALObj->action = "Update";
            $this->SALObj->audit_log_entry();
            /* -------------- Log Entry ------------- */

            return $rs_up;
        }
    }

    //Edit User Profile
    function update_user() {
        global $sqlObj, $admin_panel_session_suffix;

        if ($this->update_arr) {
            
            
            //echo "<pre>"; print_r($encryptedPassword); exit;
            if($this->update_arr['vPassword'] != ''){
                $encryptedPassword = encrypt_password($this->update_arr['vPassword']);
                $vPassword = $encryptedPassword['encryptedPassword'];
                $sSalt = $encryptedPassword['salt'];
                $password_str = ", \"vPassword\"=" . gen_allow_null_char($vPassword) . "";
                $salt_str = ", \"sSalt\"=" . gen_allow_null_char($sSalt) . "";
            }else{
                $password_str = "";
                $salt_str = "";
            }

            $sql_user = "UPDATE user_mas SET  \"vFirstName\"=" . gen_allow_null_char($this->update_arr['vFirstName']). "".$password_str.", \"vLastName\"=" . gen_allow_null_char($this->update_arr['vLastName']) . "".$salt_str." WHERE \"iUserId\"=" . $this->update_arr['iUserId'];
            //echo $sql_user;exit;
            $sqlObj->Execute($sql_user);
            $rs_up = $sqlObj->Affected_Rows();

            /* -------------- Log Entry ------------- */
            $this->SALObj->type = 1;
            $this->SALObj->module_name = "user";
            $this->SALObj->action = "Update";
            $this->SALObj->audit_log_entry();
            /* -------------- Log Entry ------------- */

            return $rs_up;
        }
    }

    function user_clear_variable() {
        $this->join_field = array();
        $this->join = array();
        $this->where = array();
        $this->param = array();
        $this->ids = 0;
        $this->action = "";
        $this->insert_arr = array();
        $this->update_arr = array();
        $this->join_field_str = "";
        $this->where_clause = "";
        $this->join_clause = "";
        $this->order_by_clause = "";
        $this->group_by_clause = "";
        $this->limit_clause = "";
    }

    function user_usernameFromId($iUserId) {
        global $sqlObj;

        $sql = "SELECT concat(\"vFirstName\",' ', \"vLastName\" ) as vName FROM user_mas WHERE \"iUserId\"='" . $iUserId . "' LIMIT 1";
        $rs_db = $sqlObj->GetAll($sql);
        return $rs_db[0]['vName'];
    }
	
	function user_department_list()
	{
		global $sqlObj;
			
		$sql = "SELECT user_department.* ".$this->join_field_str." FROM user_department".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		
		$rs_db = $sqlObj->GetAll($sql);
		return $rs_db;
	}

    function user_zone_list(){
        global $sqlObj;
        $sql = "SELECT user_zone.* " . $this->join_field_str . " FROM \"user_zone\" " . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
        $rs_db = $sqlObj->GetAll($sql);
        return $rs_db;
    }

    function user_network_list(){
        global $sqlObj;
        $sql = "SELECT user_network.* " . $this->join_field_str . " FROM \"user_network\" " . $this->join_clause . $this->where_clause . $this->group_by_clause . $this->order_by_clause . $this->limit_clause;
        $rs_db = $sqlObj->GetAll($sql);
        return $rs_db;
    }

    function debug_query($sql) {
        global $site_path;
        if ($this->debug_query == true) {

            $str = '<?
	/*=================== Query ======================*/
	' . $sql . '
	/*=================== Query ======================*/
?>';
            file_put_contents($site_path . "debug/" . basename($_SERVER['SCRIPT_FILENAME']), $str);
        }
    }

    function add_Customize_Panel_Data($data) {
        global $sqlObj;

        $sql_del = 'DELETE FROM user_panel_customizer WHERE "iUserId" = '.gen_allow_null_int($data['iUserId']) .' ';
        $sqlObj->Execute($sql_del);

        //Add Default user theme data
        $sql = 'INSERT INTO user_panel_customizer ("iUserId", "vTemplateColor", "vLayout", "vTemplateStyle", "bCompactSidebar", "bSmallMenu") VALUES (' . gen_allow_null_int($data['iUserId']) . ',' . gen_allow_null_char($data['color']) . ',' . gen_allow_null_char($data['layout']) . ',' . gen_allow_null_char($data['style']) . ',' . gen_allow_null_char($data['bCompactMenu']) . ',' . gen_allow_null_char($data['bsmallMenu']).')';
        //echo "11<pre>";print_r($sqlObj);exit;
        $sqlObj->Execute($sql);

        $rs_up = $sqlObj->Affected_Rows();

        return $rs_up;
    }

    function user_zoneFromId($iUserId) {
        global $sqlObj;

        $sql = "SELECT \"iZoneId\" FROM user_zone WHERE \"iUserId\"='" . $iUserId . "' ORDER BY \"iZoneId\"";
        $rs_db = $sqlObj->GetAll($sql);
        $zone_arr = [];
        $ni = count($rs_db);
        if($ni > 0){
            for($i=0; $i<$ni; $i++){
                $zone_arr[] =  $rs_db[$i]['iZoneId'];
            }
        }
        return $zone_arr;
    }


    function user_networkFromId($iUserId) {
        global $sqlObj;

        $sql = "SELECT \"iNetworkId\" FROM user_network WHERE \"iUserId\"='" . $iUserId . "' ORDER BY \"iNetworkId\"";
        $rs_db = $sqlObj->GetAll($sql);
        $zone_arr = [];
        $ni = count($rs_db);
        if($ni > 0){
            for($i=0; $i<$ni; $i++){
                $zone_arr[] =  $rs_db[$i]['iNetworkId'];
            }
        }
        return $zone_arr;
    } 

}

?>