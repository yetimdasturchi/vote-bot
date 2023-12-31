<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hook_model extends CI_Model {

	public $module_name;
	public $settings;

	public function load( $module_name ){
		$this->module_name = $module_name;
		$this->lang->load_module($this->module_name, 'bot');
		$this->settings = $this->module->load_settings( $this->module_name, ['min' => 30, 'max' => 2000] );
	}

	public function run(){
		$this->user->last_command = $this->user->command_text;
		$keyboard[][] = lang('back_button');
		$this->telegram->set_replyKeyboard( $keyboard, lang('select_section') );
		$this->telegram->send_message( $this->user->command['command_message'] );
	}

	public function process(){
		$message_length = strlen( $this->user->command_text );
		if ( !empty( $this->user->command_text ) && $message_length > 10 && $message_length < 2000 ) {
			$this->save_contact( $this->user->command_text );
			$this->telegram->send_message( lang('contact_success') );
			$this->back();
			return FALSE;
		}else{
			$this->telegram->send_message( str_replace( [ '{{min}}', '{{max}}' ], $this->settings, lang('contact_text_error') ) );
		}

		return TRUE;
	}

	private function save_contact( $message, $file_id = NULL ){
		$this->db->insert('contact', [
			'contact_from' => $this->user->chat_id,
			'contact_send' => time(),
			'contact_message' => $message,
			'contact_file' => $file_id
		]);
	}

	public function back(){
		$this->user->active_function = '';
		$this->menu->back();
	}
}

/* End of file Hook.php */
/* Location: ./application/modules/functions/contact/models/Hook.php */