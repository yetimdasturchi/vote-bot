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
			$this->db->insert('channels', [
				'channel_name' => $this->input->post('name'),
        		'channel_chat_username' => $this->input->post('username'),
        		'channel_chat_id' => $this->input->post('chat_id'),
        		'channel_subscription' => $this->input->post('subscription'),
        		'channel_status' => $this->input->post('status')
			]);

			clear_cache([
                'channels',
			]);

			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode([
		        	'status' => true,
		        	'reset' => true,
		        	'messages' => [
		        		'addedd' => lang('channel_successfully_added')
		        	],
		        	'_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('channels/records')."' }, 1000);}"
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
		$this->form_validation->set_rules('name', lang('channels_name'), 'required|alpha_numeric_spaces|min_length[4]|max_length[255]');
		$this->form_validation->set_rules('username', lang('channel_chat_username'), [
			['username_check', [$this, 'username_check']],
			'is_unique[channels.channel_chat_username]'
		], ['username_check' => lang('channel_username_field_incorrect')]);
		$this->form_validation->set_rules('chat_id', lang('channel_chat_id'), 'required|min_length[4]|max_length[32]|is_unique[channels.channel_chat_id]');
		$this->form_validation->set_rules('subscription', lang('channel_subscription'), 'required|in_list[0,1]');
		$this->form_validation->set_rules('status', lang('channel_status'), 'required|in_list[0,1]');
	}

	public function username_check(){

		$str = $this->input->post('username');
		if( preg_match('/.*\B@(?=\w{5,32}\b)[a-zA-Z0-9]+(?:_[a-zA-Z0-9]+)*.*/', $str ) ){
			return TRUE;
		}

		return FALSE;
	}
}

/* End of file Add_model.php */
/* Location: ./application/models/channels/Add_model.php */