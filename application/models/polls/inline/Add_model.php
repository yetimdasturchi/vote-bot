<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function process(){
		$this->set_rules();

		if ( $this->form_validation->run() ) {
			$status = $this->input->post('status');
			$name = $this->input->post('name');
			$question = $this->input->post('question');
			$datetime = $this->input->post('date') . ' ' . $this->input->post('time');
			$buttons = $this->input->post('buttons');
			$polls_additional_field = $this->input->post('polls_additional_field');

			if ( $_FILES["file"]["error"] == 0 ) {
                $this->load->helper('telegram_helper');
                $file_id = get_file_id( $_FILES["file"]["tmp_name"], $_FILES["file"]["name"],  $this->session->userdata('manager_telegram') );

                if ( $file_id == FALSE ) {
                    $this->output->set_content_type('application/json')
                        ->set_status_header(400)
                        ->_display(json_encode([
                            'status' => false,
                            'messages' => [ lang('polls_file_not_uploaded') ]
                        ])
                    );
                    exit(0);
                }
            }
            $this->db->insert('poll_questions', [
            	'name' => $name,
				'question' => $question,
        		'file' => isset( $file_id ) && !empty( $file_id ) ? json_encode($file_id) : '',
        		'expire' => !empty( trim( $datetime ) ) ? strtotime( $datetime ) : '0',
        		'status' => $status,
        		'buttons' => !empty( $buttons ) ? json_encode( $buttons ) : '',
        		'additional_field' => $polls_additional_field
			]);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'reset' => true,
                    'messages' => [
                        'addedd' => lang('polls_successfully_added')
                    ],
                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('polls/inline/records')."' }, 1000);}"
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
		$this->form_validation->set_rules('question', lang('polls_poll'), [
			['question_check', [$this, 'question_check']]
		], ['question_check' => lang('polls_question_field_incorrect')]);

		$this->form_validation->set_rules('date', lang('polls_expire'), [
			['date_check', [$this, 'date_check']]
		], ['date_check' => lang('polls_date_field_incorrect')]);

		$this->form_validation->set_rules('status', lang('polls_status'), 'required|in_list[0,1]');

		$buttons = $this->input->post('buttons');
        
        if ( !empty( $buttons ) ) {
            $error = "";
            foreach ($buttons as $button) {
                if ( empty( $button['name'] ) ){
                    $error = lang('polls_button_name_not_entered');
                    break;
                }
                if ( empty( $button['value'] ) ){
                    $error = lang('polls_button_value_not_entered');
                    break;
                }
                if ( empty( $button['type'] ) ) {
                    $error = lang('polls_button_type_not_selected');
                    break;
                }
                if ( !empty( $button['type'] ) && !in_array($button['type'], ['url', 'callback', 'webapp']) ) {
                    $error = lang('polls_button_type_not_matched');
                    break;
                }
            }

            if ( !empty( $error ) ) {
                $this->output->set_content_type('application/json')
                    ->set_status_header(400)
                    ->_display(json_encode([
                        'status' => false,
                        'messages' => [$error]
                    ])
                );
                exit(0);
            }
        }
	}

	public function question_check(){
		$str = $this->input->post('question');
		
		if ( !empty( $_FILES['file']['error'] ) && $_FILES['file']['error'] == 0 && mb_strlen($str, 'UTF-8') > 1024 ) {
			return FALSE;
		}else if (mb_strlen($str, 'UTF-8') > 4096){
			return FALSE;
		}

		if ( !empty( $_FILES['file']['error'] ) && $_FILES['file']['error'] == 4 && empty( $str ) ) {
			return FALSE;
		}

		return TRUE;
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

/* End of file Add_model.php */
/* Location: ./application/models/polls/inline/Add_model.php */