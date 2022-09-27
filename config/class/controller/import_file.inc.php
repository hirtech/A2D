<?php

include_once("security_audit_log.inc.php");

class ImportFile {

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

    function ImportFile() {
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




    function add_records() {
        global $sqlObj;

        if ($this->insert_arr) {
           
            $sql = "INSERT INTO import_file (\"vFile\", \"vOption\",  \"dImportDate\") VALUES(" . gen_allow_null_char($this->insert_arr['vFile']) . ", " . gen_allow_null_char($this->insert_arr['vOption']) . ", " . gen_allow_null_char($this->insert_arr['dImportDate']). ")";

            $sqlObj->Execute($sql);
            $rs_db = $sqlObj-> Insert_ID();
    
            $this->debug_query($sql);

            /* -------------- Log Entry ------------- */
            $this->SALObj->type = 0;
            $this->SALObj->module_name = "import file";
            $this->SALObj->audit_log_entry();
            /* -------------- Log Entry ------------- */

            return $rs_db;
        }
    }




    function clear_variable() {
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


}

?>