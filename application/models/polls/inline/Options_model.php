<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Options_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->table = 'poll_answers';
        $this->column_order = array(null, 'id','answer','question_id','status');
        $this->column_search = array('id','answer','question_id','status');
        $this->order = array('id' => 'asc');

        $this->load->library('form_validation');
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

        if(isset($postData['question_id'])){
            $this->db->where_in('question_id', $postData['question_id']);
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function update_field(){
        $this->form_validation->set_rules('name', lang('poll_name'), 'required|min_length[3]|max_length[200]');
        $this->form_validation->set_rules('value', lang('poll_value'), 'required|min_length[3]|max_length[200]');
        $this->form_validation->set_rules('pk', lang('poll_pk'), 'required|min_length[3]|max_length[200]');

        if ( !$this->db->field_exists($this->input->post('name'), 'poll_answers')) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => false,
                    'messages' => [lang('poll_field_not_exists')]
                ])
            );
        }

        $query = $this->db->get_where('poll_answers', [
            'id' => $this->input->post('pk')
        ]);

        if ( $query->num_rows() > 0 ) {
            $this->db->update('poll_answers', [
                $this->input->post('name') => $this->input->post('value')
            ],[
                'id' => $this->input->post('pk')
            ]);

            $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'messages' => [lang('poll_data_successfully_updated')]
                ])
            );
        }else{
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => false,
                    'messages' => [lang('poll_answer_not_exists')]
                ])
            );
        }
    }

    public function addanswer( $id ){
        $this->db->insert('poll_answers', [
            'answer' => $this->input->post('answer'),
            'question_id' => $id,
            'status' => '1',
        ]);

        $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'reset' => true,
                'messages' => [lang('poll_successfully_added')],
                '_callback' => "function(){\$dtables['polls_inline_options'].ajax.reload(null, false); $('.bs-add-modal').modal('hide');}"
            ])
        );
    }
}

/* End of file Options_model.php */
/* Location: ./application/models/polls/inline/Options_model.php */