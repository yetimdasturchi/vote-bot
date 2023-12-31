<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function get_selected_polls( $ids ){
		if ( $ids == '' ) return [];
		$this->db->select('id, name as text');
		$this->db->from('poll_questions');
		
		$this->db->where_in('id', explode(',', $ids));
		$this->db->where('status', '1');
		$this->db->order_by('name', 'asc');

		return  $this->db->get()->result_array();
	}

	public function process( $id, $data ){
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
			$datetime = $this->input->post('date') . ' ' . $this->input->post('time');
			$polls = $this->input->post('polls');

			$polls_uzbek = $this->input->post('polls_uzbek');
			$polls_uzbek_cyr = $this->input->post('polls_uzbek_cyr');
			$polls_russian = $this->input->post('polls_russian');
			$polls_english = $this->input->post('polls_english');

			$polls_check = $this->input->post('polls_check');

			$this->db->update('contests', [
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
        		'expire' => !empty( trim( $datetime ) ) ? strtotime( $datetime ) : '0',
        		'status' => $status,
        		'polls' => !empty( $polls ) ? implode(',', $polls) : '',
        		'polls_uzbek' => !empty( $polls_uzbek ) ? implode(',', $polls_uzbek) : '',
        		'polls_uzbek_cyr' => !empty( $polls_uzbek_cyr ) ? implode(',', $polls_uzbek_cyr) : '',
        		'polls_russian' => !empty( $polls_russian ) ? implode(',', $polls_russian) : '',
        		'polls_english' => !empty( $polls_english ) ? implode(',', $polls_english) : '',
        		'polls_check' => $polls_check
			], [ 'id' => $id ]);

			return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'messages' => [
                        'addedd' => lang('poll_data_successfully_updated')
                    ],
                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('polls/web/records')."' }, 1000);}"
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
		$this->form_validation->set_rules('date', lang('polls_expire'), [
			['date_check', [$this, 'date_check']]
		], ['date_check' => lang('polls_date_field_incorrect')]);

		$this->form_validation->set_rules('max_votes', lang('polls_max_votes'), 'required|greater_than_equal_to[1]|less_than_equal_to[999]');
		$this->form_validation->set_rules('polls_check', lang('polls_check_votes'), 'required|greater_than_equal_to[1]|less_than_equal_to[100]');
		$this->form_validation->set_rules('name', lang('poll_name'), 'required');
		$this->form_validation->set_rules('status', lang('polls_status'), 'required|in_list[0,1]');
	}

	public function date_check(){
		if ( empty( $this->input->post('date') ) ) {
			return TRUE;
		}

		$time = !empty( $this->input->post('time') ) ? $this->input->post('time') : '00:00' ;
		$datetime = $this->input->post('date') . ' ' . $this->input->post('time');
		return (bool)preg_match('/\d{2}\-\d{2}\-\d{4} \d+:\d+/', $datetime);
	}
}

/* End of file Edit_model.php */
/* Location: ./application/models/polls/web/Edit_model.php */