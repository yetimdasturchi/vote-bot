<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Queue_model extends CI_Model {
	function __construct() {
        $this->table = 'contest_queue q';
        $this->column_order = array();
        $this->column_search = array('q.chat_id', 'u.username', 'u.first_name', 'u.last_name', 'c.name');
        $this->order = array('q.send_date' => 'desc');
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
        $this->db->select( "q.id, q.chat_id, q.expire, q.expire, q.send_date, q.answered, q.sended, q.status, u.id as u_id, u.username as u_username, u.first_name  as u_first_name, u.last_name  as u_last_name, c.name contest_name, pu.name as uz_name, puc.name as uzc_name, pr.name as ru_name, pe.name as en_name" );
        $this->db->from($this->table);
 		$this->db->join('users u', 'u.chat_id = q.chat_id');
 		$this->db->join('contests c', 'c.id = q.contest');
 		$this->db->join('poll_questions pu', 'pu.id = q.poll_uzbek', 'left');
 		$this->db->join('poll_questions puc', 'puc.id = q.poll_uzbek_cyr', 'left');
 		$this->db->join('poll_questions pr', 'pr.id = q.poll_russian', 'left');
 		$this->db->join('poll_questions pe', 'pe.id = q.poll_eglish', 'left');
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
            $this->db->where_in('q.chat_id', $postData['chat_id']);
        }

        if ( isset( $postData['status_filter'] ) ) {
            if ( in_array('2', $postData['status_filter']) ) {
                
                if (($key = array_search('2', $postData['status_filter'])) !== false) {
                    $postData['status_filter'][$key] = '0';
                }

                $this->db->where('q.expire <=', time() ) ;
            }
            $this->db->where_in('q.status', $postData['status_filter']);
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}

/* End of file Queue_model.php */
/* Location: ./application/models/polls/Queue_model.php */