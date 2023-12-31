<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referrals_model extends CI_Model {
	function __construct() {
        $this->table = 'referrals';
        $this->column_order = array(null, 'referrals.*');
        $this->column_search = array();
        $this->order = array('referrals.date' => 'desc');
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
        $this->db->select( "referrals.*, u.username as u_username, u.first_name  as u_first_name, u.last_name  as u_last_name, o.username as o_username, o.first_name  as o_first_name, o.last_name  as o_last_name" );
        $this->db->from($this->table);
 		$this->db->join('users u', 'u.chat_id = referrals.chat_id');
 		$this->db->join('users o', 'o.chat_id = referrals.owner_id');
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

        if(isset($postData['owner_id'])){
            $this->db->where_in('referrals.owner_id', $postData['owner_id']);
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}

/* End of file Referrals_model.php */
/* Location: ./application/models/users/Referrals_model.php */