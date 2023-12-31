<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Active_model extends CI_Model {
	function __construct() {
        $this->table = 'users';
        $this->column_order = array(null, 'id', 'phone', 'registered', 'last_action', 'first_name','last_name', 'phone', 'registered', 'last_action');
        $this->column_search = array('id','username','chat_id','first_name','last_name', 'phone');
        $this->order = array('last_action' => 'desc');
    }

    public function getRows($postData){
        $this->_get_datatables_query($postData);
        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
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

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function export(){
        $this->db->select('users.id as real_id, users.chat_id, users.username, users.first_name, users.last_name, users.phone, users.registered, users.last_action, users.language, additional_fields.*, ');
        $this->db->from('users');
        $this->db->join('additional_fields', 'additional_fields.user_id = users.id', 'left');
        return $this->db->get()->result_array();
    }
}

/* End of file Active_model.php */
/* Location: ./application/models/users/Active_model.php */