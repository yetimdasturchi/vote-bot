<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hook_model extends CI_Model {

	public $module_name;
	public $settings;

	public function load( $module_name ){
		$this->module_name = $module_name;
	}

	public function run(){
		$this->process();
	}

	public function process(){
		$language = $this->user->get_data('language');
		
		if ( empty( $language ) ) {
			$this->bot->messageText( '/welcome', $this->user->chat_id, 'command_set', FALSE );
			$this->bot->messageText( '/select_language', $this->user->chat_id, 'command_set', FALSE );
			
			$this->db->update('users', [
				'active_function' => '',
				'last_command' => '',
			], [
				'chat_id' => $this->user->chat_id
			]);

			$this->cache->file->delete( $this->user->chat_id . '.user' );
			exit();
		}else{
			$this->bot->messageText( '/main', $this->user->chat_id, 'command_set', FALSE, TRUE, FALSE );
		}

		return TRUE;
	}

	public function back(){
		$this->user->active_function = '';
		$this->menu->back();
	}
}

/* End of file Hook.php */
/* Location: ./application/modules/functions/contact/models/Hook.php */