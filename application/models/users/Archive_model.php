<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archive_model extends CI_Model {
	function __construct() {
        $this->table = 'users_archive_users';
        $this->column_order = array(null, 'users_archive_users_id','users_archive_users_category','users_archive_users_name','users_archive_users_telegram','users_archive_users_phone');
        $this->column_search = array('users_archive_users_id','users_archive_users_category','users_archive_users_name','users_archive_users_telegram','users_archive_users_phone');
        $this->order = array('users_archive_users_id' => 'asc');
    }

    public function getRows($postData){
        $this->_get_datatables_query($postData);
        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
        $this->db->join('users_archive_categories', 'users_archive_categories.users_archive_category_id = users_archive_users.users_archive_users_category');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function countAll(){
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function countFiltered($postData){
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query($postData){
         
        $this->db->from($this->table);
 
        $i = 0;
        foreach($this->column_search as $item){
            if($postData['search']['value']){
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                if(count($this->column_search) - 1 == $i){
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($postData['filter_category'])){
        	$this->db->where_in('users_archive_users_category', $postData['filter_category']);
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function getCategories(){
    	$this->db->select('users_archive_category_id as id, users_archive_category_name as name');
    	return $this->db->get('users_archive_categories');
    }
}

/* End of file Archive_model.php */
/* Location: ./application/models/users/Archive_model.php */