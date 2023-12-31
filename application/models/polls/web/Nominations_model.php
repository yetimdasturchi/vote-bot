<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nominations_model extends CI_Model {
	function __construct() {
        $this->table = 'nominations';
        $this->column_order = array(null, 'id','name','max_votes','status');
        $this->column_search = array('id','name', 'name_uzbek', 'name_uzbek_cyr', 'name_russian', 'name_english','description','description_uzbek','description_uzbek_cyr','description_russian','description_english');
        $this->order = array('id' => 'asc');
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

        if(isset($postData['contest'])){
            $this->db->where_in('contest', $postData['contest']);
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function add( $id ){
    	$this->set_rules();

    	if ( $this->form_validation->run() ) {
			$status = $this->input->post('status');
			
            $name = $this->input->post('name');

            $name_uzbek = $this->input->post('name_uzbek');
            $name_uzbek_cyr = $this->input->post('name_uzbek_cyr');
            $name_russian = $this->input->post('name_russian');
            $name_english = $this->input->post('name_english');

			$description = $this->input->post('description');

            $description_uzbek = $this->input->post('description_uzbek');
            $description_uzbek_cyr = $this->input->post('description_uzbek_cyr');
            $description_russian = $this->input->post('description_russian');
            $description_english = $this->input->post('description_english');

			$max_votes = $this->input->post('max_votes');

			$this->db->insert('nominations', [
				'contest' => $id,
				'name' => $name,
                'name_uzbek' => $name_uzbek,
                'name_uzbek_cyr' => $name_uzbek_cyr,
                'name_russian' => $name_russian,
                'name_english' => $name_english,
                'description' => $description,
                'description_uzbek' => $description_uzbek,
                'description_uzbek_cyr' => $description_uzbek_cyr,
                'description_russian' => $description_russian,
                'description_english' => $description_english,
				'max_votes' => $max_votes,
        		'status' => $status
			]);

			return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'reset' => true,
                    'messages' => [
                        'addedd' => lang('poll_successfully_added')
                    ],
                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('polls/web/nominations/'.$id)."' }, 1000);}"
                ])
            );
		}else{
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(400)
		    	->set_output(json_encode([
		        	'status' => false,
		        	'messages' => $this->form_validation->error_array()
		    	])
		    );
		}
    }

    public function edit( $contest, $nomination ){
    	$this->set_rules();

    	if ( $this->form_validation->run() ) {
			$status = $this->input->post('status');
			$name = $this->input->post('name');

            $name_uzbek = $this->input->post('name_uzbek');
            $name_uzbek_cyr = $this->input->post('name_uzbek_cyr');
            $name_russian = $this->input->post('name_russian');
            $name_english = $this->input->post('name_english');

            $description = $this->input->post('description');

            $description_uzbek = $this->input->post('description_uzbek');
            $description_uzbek_cyr = $this->input->post('description_uzbek_cyr');
            $description_russian = $this->input->post('description_russian');
            $description_english = $this->input->post('description_english');
			$max_votes = $this->input->post('max_votes');

			$this->db->update('nominations', [
				'name' => $name,
                'name_uzbek' => $name_uzbek,
                'name_uzbek_cyr' => $name_uzbek_cyr,
                'name_russian' => $name_russian,
                'name_english' => $name_english,
                'description' => $description,
                'description_uzbek' => $description_uzbek,
                'description_uzbek_cyr' => $description_uzbek_cyr,
                'description_russian' => $description_russian,
                'description_english' => $description_english,
				'max_votes' => $max_votes,
        		'status' => $status
			], ['id' => $nomination]);

			return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'messages' => [
                        lang('poll_data_successfully_updated')
                    ],
                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('polls/web/nominations/'.$contest)."' }, 1000);}"
                ])
            );
		}else{
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(400)
		    	->set_output(json_encode([
		        	'status' => false,
		        	'messages' => $this->form_validation->error_array()
		    	])
		    );
		}
    }

    public function set_rules(){
    	$this->load->library('form_validation');

    	$this->form_validation->set_rules('max_votes', lang('polls_max_votes'), 'required|greater_than_equal_to[1]|less_than_equal_to[999]');
		$this->form_validation->set_rules('name', lang('poll_name'), 'required');
		$this->form_validation->set_rules('status', lang('polls_status'), 'required|in_list[0,1]');
    }
}

/* End of file Nominations_model.php */
/* Location: ./application/models/polls/web/Nominations_model.php */