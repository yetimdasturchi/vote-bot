<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Votes_model extends CI_Model {
	function __construct() {
        $this->table = 'contest_votes v';
        $this->column_order = array();
        $this->column_search = array('v.chat_id', 'u.username', 'u.first_name', 'u.last_name', 'c.name', 'm.name', 'n.name');
        $this->order = array('v.id' => 'desc');
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
        
        $this->db->select( "v.id, v.chat_id, v.date, v.check_status, u.id as u_id, u.username as u_username, u.first_name  as u_first_name, u.last_name  as u_last_name, c.name contest_name, m.name as member_name, n.name as nomination_name" );
        $this->db->from($this->table);
 		$this->db->join('users u', 'u.chat_id = v.chat_id');
 		$this->db->join('contests c', 'c.id = v.contest');
 		$this->db->join('members m', 'm.id = v.member');
 		$this->db->join('nominations n', 'n.id = v.nomination');
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

        if(isset($postData['chat_id'])){
            $this->db->where_in('v.chat_id', $postData['chat_id']);
        }

        if ( isset( $postData['check_status_filter'] ) ) {
            $this->db->where_in('v.check_status', $postData['check_status_filter']);
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}

/* End of file Votes_model.php */
/* Location: ./application/models/polls/Votes_model.php */