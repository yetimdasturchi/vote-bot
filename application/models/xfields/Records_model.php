<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Records_model extends CI_Model {
	public function get_columns(){
		$database = $this->db->escape($GLOBALS['system_config']['db_database']);
		$query = $this->db->query("SELECT COLUMN_NAME as xfield, COLUMN_COMMENT as name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ".$database." AND TABLE_NAME = 'additional_fields' AND COLUMN_NAME NOT IN ('id','user_id');");

		return $query->result_array();
	}
}

/* End of file Records_model.php */
/* Location: ./application/models/xfields/Records_model.php */