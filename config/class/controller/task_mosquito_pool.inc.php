<?php
include_once("security_audit_log.inc.php");
class TaskMosquitoPool {

	var $join_field = array();
	var $join = array();
	var $where = array();
	var $param = array();
	var $ids = 0;
	var $action;
	var $insert_arr = array();
	var $update_arr = array();
	var $join_field_str="";
	var $where_clause="";
	var $join_clause="";
	var $order_by_clause="";
	var $group_by_clause="";
	var $limit_clause = "";
	var $debug_query = false;
	
	function TaskMosquitoPool() {
		$this->SALObj = new Security_audit_log();
	}
	
	function setClause() {			

		//Join Fields for select query	
		if(is_array($this->join_field) && count($this->join_field) > 0){
				$this->join_field_str = ", ".implode(", ", $this->join_field);
		}
		// Join clause
		if(is_array($this->join) && count($this->join) > 0){
			$this->join_clause = " ".implode(" ", $this->join);	
		}
		// Where clause
		if(is_array($this->where) && count($this->where) > 0){
			$this->where_clause = " WHERE ".implode(" AND ", $this->where);	
		}
		
		if(is_array($this->param) && count($this->param) > 0){		

			// Order by clause
			if(!empty($this->param['order_by']))
				$this->order_by_clause = " ORDER BY ".$this->param['order_by'];
				
			// Group by clause
			if(!empty($this->param['group_by']))
				$this->group_by_clause = " GROUP BY ".$this->param['group_by'];
			
			// Limit clause
			if(!empty($this->param['limit'])){
				if(intval($this->param['limit']) > 0){
					//$this->limit_clause = " LIMIT 0, ".intval($this->param['limit']);
					$this->limit_clause = " LIMIT ".intval($this->param['limit'])." OFFSET 0";
				}
				else if(strstr($this->param['limit'], "LIMIT")){
					$this->limit_clause = " ".$this->param['limit'];
				}
				else{
					$this->limit_clause = " LIMIT ".$this->param['limit'];
				}		
			}
			else{
				$this->limit_clause="";
			}
		}
	}

	function recordset_list()
	{
		global $sqlObj;
			
		$sql = "SELECT task_mosquito_pool.* ".$this->join_field_str." FROM task_mosquito_pool".$this->join_clause.$this->where_clause.$this->group_by_clause.$this->order_by_clause.$this->limit_clause;
		//echo $sql;exit();
		$rs_db = $sqlObj->GetAll($sql);
		// echo $sql;exit;
		//echo "<pre>";print_r($rs_db);exit;
		return $rs_db;
	}
	
	function recordset_total()
	{
		global $sqlObj;
			
		$sql = "SELECT task_mosquito_pool.* ".$this->join_field_str." FROM task_mosquito_pool".$this->join_clause.$this->where_clause.$this->group_by_clause;
		$rs_db = $sqlObj->Execute($sql);
		if($rs_db === false)
			$count = 0;
		else
			$count = $rs_db->RecordCount();

		return $count;
		
	}

/*	function add_records(){
		global $sqlObj,$admin_panel_session_suffix;
		if($this->insert_arr){

				$countMosqperpool_arr = $this->insert_arr['iCountMosqperpool_arr'];
				$ci = count($countMosqperpool_arr);
				if($ci > 0){
					$cont_arr  = array();
					for($c=0;$c<$ci;$c++){
						$cont_arr[] = '('.gen_allow_null_char($this->insert_arr['iTTId']).",".gen_allow_null_char($this->insert_arr['iTMCId']).",".gen_allow_null_char($this->insert_arr['vPool']).",".gen_allow_null_char($countMosqperpool_arr[$c]).','.gen_allow_null_char($this->insert_arr['iNumberinPool']).') ';
					}
					if(count($cont_arr) > 0){
	 					$sql_sc = 'INSERT INTO task_mosquito_pool("iTTId","iTMCId", "vPool", "iCountMosqperpool", "iNumberinPool") VALUES '.implode(",", $cont_arr).'';
	 					//echo $sql_sc;exit();
	 					$rs_ins = $sqlObj->Execute($sql_sc);
	 				}
				}
			return $rs_ins;
		}
	}*/

	function add_records(){
		global $sqlObj,$admin_panel_session_suffix;
		if($this->insert_arr){
				$countMosqperpool_arr = $this->insert_arr['iCountMosqperpool_arr'];
				$poolAgentTest_arr = $this->insert_arr['poolAgentTest_arr'];
				$ci = count($countMosqperpool_arr);
				$pi =count($poolAgentTest_arr);
				$cont_id_arr  = array();
				if($ci > 0){
					for($c=0;$c<$ci;$c++){
						$sql_sc = 'INSERT INTO task_mosquito_pool("iTTId","iTMCId", "vPool", "iCountMosqperpool", "iNumberinPool","dAddedDate") VALUES ('.gen_allow_null_char($this->insert_arr['iTTId']).",".gen_allow_null_char($this->insert_arr['iTMCId']).",".gen_allow_null_char($this->insert_arr['vPool']).",".gen_allow_null_char($countMosqperpool_arr[$c]).','.gen_allow_null_char($this->insert_arr['iNumberinPool']).','.gen_allow_null_char(date_getSystemDateTime()).')';
	 					$sqlObj->Execute($sql_sc);

	 					$id = $sqlObj->Insert_ID();
	 					$cont_id_arr[] = $id;
					}
					if(count($poolAgentTest_arr) > 0 && count($cont_id_arr)>0){
						$pol_ins_qur = array();
						for($j=0;$j<$pi;$j++){
							for($i=0;$i<count($cont_id_arr);$i++){
								$pol_ins_qur[] = '('.gen_allow_null_int($cont_id_arr[$i]).','.gen_allow_null_int($poolAgentTest_arr[$j]['Agent']).','.gen_allow_null_int($poolAgentTest_arr[$j]['Test']).',\'1\','.gen_allow_null_char(date_getSystemDateTime()).')';
							}
						}
						if(count($pol_ins_qur) > 0){
							$sql_pool = 'INSERT INTO task_mosquito_pool_result("iTMPId","iAMId","iTMMId","iResultId","dAddedDate") VALUES '.implode(",", $pol_ins_qur).'';
							$rs_pool =  $sqlObj->Execute($sql_pool);
						}
					}
				}
			return $id;
		}
	}

	function update_records(){
		global $sqlObj;
		if($this->update_arr){
			$rs_db = "UPDATE task_mosquito_pool SET 
			\"iTTId\"=".gen_allow_null_char($this->update_arr['iTTId']).", 
			\"iTMCId\"=".gen_allow_null_char($this->update_arr['iTMCId']).", 
			\"vPool\"=".gen_allow_null_char($this->update_arr['vPool']).", 
			\"iCountMosqperpool\"=".gen_allow_null_char($this->update_arr['iCountMosqperpool']).", 
			\"iNumberinPool\"=".gen_allow_null_char($this->update_arr['iNumberinPool']).",
			\"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())." 
			WHERE \"iTMPId\" = ".$this->update_arr['iTMPId'];
		
			$sqlObj->Execute($rs_db);
			$rs_up = $sqlObj->Affected_Rows();
			
			return $rs_up;
		}
	}

	function delete_records($id){
		global $sqlObj;
		
		$sql_del = "DELETE FROM task_mosquito_pool WHERE task_mosquito_pool.\"iTMPId\" IN (".$id.")";
		$rs_del = $sqlObj->Execute($sql_del);
		return $rs_del;
	}

	function update_labwork_records(){
		global $sqlObj;
		if($this->update_arr){
			$sql_db = "UPDATE task_mosquito_pool SET \"bLabWorkComplete\"=".gen_allow_null_char($this->update_arr['bLabWorkComplete'])." , \"dModifiedDate\"=".gen_allow_null_char(date_getSystemDateTime())." WHERE \"iTMPId\" = ".$this->update_arr['iTMPId'];
			//echo $sql_db;exit;
			$sqlObj->Execute($sql_db);
			$rs_up = $sqlObj->Affected_Rows();

			return $rs_up;
		}
	}

	function clear_variable(){
		$this->join_field = array();
		$this->join = array();
		$this->where = array();
		$this->param = array();
		$this->ids = 0;
		$this->action="";
		$this->insert_arr = array();
		$this->update_arr = array();
		$this->join_field_str="";
		$this->where_clause="";
		$this->join_clause="";
		$this->order_by_clause="";
		$this->group_by_clause="";
		$this->limit_clause = "";
	}

	function recordset_glance_data($where_clause1 = "",$where_clause2 ="")
	{	global $sqlObj;
		if($where_clause1 != ""){
			$where_clause1 = " WHERE ".$where_clause1 ;	
		}

		if($where_clause2 != ""){
			$where_clause2 = " WHERE ".$where_clause2 ;	
		}
		
		$sql_glance =  "select (SELECT count(\"iTMPId\") from task_mosquito_pool ".$where_clause1." ) as mospoolcount1 , ( SELECT  count(\"iTMPId\")  from  task_mosquito_pool ".$where_clause2." ) as mospoolcount2 ";
		//echo $sql_glance;exit();
		$rs_db = $sqlObj->GetAll($sql_glance);
		//echo "<pre>";print_r($rs_db);exit();	
		return $rs_db;
	}

	function mosquito_pool_countByPool(){
		global $sqlObj;
			
		$sql = "SELECT sum(task_mosquito_pool.\"iCountMosqperpool\") as CountMosqperpool".$this->join_field_str." FROM task_mosquito_pool".$this->join_clause.$this->where_clause.$this->group_by_clause;
		//echo $sql;exit();
		$rs_db = $sqlObj->GetAll($sql);


		return $rs_db;
	}

}
?>