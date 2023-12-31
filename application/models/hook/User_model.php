<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
	public $chat_id;
	public $updates;
	public $command;
	public $last_command;
	public $command_text;
	public $active_function;
	public $active_function_args;
	public $active_function_steps;

	public function set_chatId( $chat_id ){
		$this->chat_id = $chat_id;
		return $this;
	}

	public function get_data( $key, $chat_id = NULL ){
		if ( is_null( $chat_id ) ) $chat_id = $this->chat_id;

		if ( ! $user = $this->cache->file->get( $chat_id . '.user' ) ) {
			$user = $this->db->get_where('users', [
				'chat_id' => $chat_id
			]);

			if ( $user->num_rows() > 0 ) {
				$user = ( $user->num_rows() > 0 ) ? $user->row_array() : [] ;
				$this->cache->file->save( $chat_id . '.user', json_encode( $user ), 86400);
			}else{
				return FALSE;
			}
		}

		if ( is_string( $user ) ) {
			$user = json_decode( $user, TRUE );
		}

		return array_key_exists( $key , $user) ? $user[ $key ] : FALSE;
	}

	public function set_data( $key, $value, $chat_id = NULL){
		if ( is_null( $chat_id ) ) $chat_id = $this->chat_id;

		if ( is_array( $key ) && is_array( $value ) ) {
			$new_data = array_combine($key, $value);
		}else{
			$new_data = [ $key => $value ];
		}

		$query = $this->db->get_where('users', [
			'chat_id' => $this->telegram->get_chatId()
		]);

		if ( $query->num_rows() > 0 ) {
			$this->db->update('users', $new_data, [
				'chat_id' => $chat_id
			]);
		}else{
			$new_data['chat_id'] = $chat_id;
			$new_data['registered'] = time();
			$this->db->insert('users', $new_data);
		}

		$this->update_data( $key, $value, $chat_id );
		
	}

	public function update_data( $key = '', $value = '', $chat_id = NULL ){
		if ( is_null( $chat_id ) ) $chat_id = $this->chat_id;

		@unlink( APPPATH . 'application/cache/' . $chat_id . '.user' );

		$user = $this->db->get_where('users', [
			'chat_id' => $chat_id
		]);

		if ( $user->num_rows() > 0 ) {
			$user = $user->row_array();
			$this->cache->file->save( $chat_id . '.user', json_encode( $user ), 86400);
		}

		return TRUE;

		if ( empty( $key ) ) {
			$user = $this->db->get_where('users', [
				'chat_id' => $chat_id
			]);

			if ( $user->num_rows() > 0 ) {
				$this->cache->file->delete( $chat_id . '.user' );
				$this->cache->file->save( $chat_id . '.user', json_encode( $user->row_array() ), 86400);
				return TRUE;
			}
		}

		if ( ! $user = $this->cache->file->get( $chat_id . '.user' ) ) {
			$user = $this->db->get_where('users', [
				'chat_id' => $chat_id
			]);

			$user = ( $user->num_rows() > 0 ) ? $user->row_array() : [] ;
		}

		if ( is_string( $user ) ) {
			$user = json_decode( $user, TRUE );
		}

		if ( is_array( $key ) && is_array( $value ) ) {
			$new_data = array_combine($key, $value);
		}else{
			$new_data = [ $key => $value ];
		}

		if ( !empty( $user ) ) {
			foreach ($new_data as $k => $v) $user[$k] = $v;
			$this->cache->file->delete( $chat_id . '.user' );
			$this->cache->file->save( $chat_id . '.user', json_encode( $user ), 86400);
			return TRUE;
		}

		return FALSE;
	}

	public function stats( $method, $user_updates ){
		$update = FALSE;
		if ( $method == 'message' ) {
			$chat = $user_updates['message']['chat'];
		}else if ( $method == 'callback_query' ) {
			$chat = $user_updates['callback_query']['message']['chat'];
		}else{
			return;
		}
		
		if ( !empty( $chat['username'] ) && $chat['username'] != $this->get_data('username') ){
			$user_data['username'] = $chat['username'];
			$update = TRUE;
		}

		if ( !empty( $chat['first_name'] ) && $chat['first_name'] != $this->get_data('first_name') ){
			$user_data['first_name'] = $chat['first_name'];
			$update = TRUE;
		}

		if ( !empty( $chat['last_name'] ) && $chat['last_name'] != $this->get_data('last_name') ){
			$user_data['last_name'] = $chat['last_name'];
			$update = TRUE;
		}

		if ( !is_null( $this->user->last_command ) && $this->user->last_command != $this->get_data('last_command') ) {
			$user_data['last_command'] = $this->last_command;
			$update = TRUE;
		}

		if ( !is_null( $this->user->active_function ) && $this->user->active_function != $this->get_data('active_function') ) {
			$user_data['active_function'] = $this->active_function;
			$update = TRUE;
		}

		if ( !is_null( $this->user->active_function_args ) && $this->user->active_function_args != $this->get_data('active_function_args') ) {
			$user_data['active_function_args'] = $this->active_function_args;
			$update = TRUE;
		}

		if ( !is_null( $this->user->active_function_steps ) && $this->user->active_function_steps != $this->get_data('active_function_steps') ) {
			$user_data['active_function_steps'] = $this->active_function_steps;
			$update = TRUE;
		}

		$user_data['last_action'] = time();
		$update = TRUE;

		if ( $update ) {
			$user = $this->db->get_where('users', [
				'chat_id' => $this->user->chat_id
			]);
			
			$user_data['last_action'] = time();

			if ( $user->num_rows() > 0 ) {

				$this->db->update('users', $user_data, [
					'chat_id' => $this->chat_id
				]);
			}else{
				$user_data['chat_id'] = $this->chat_id;
				$user_data['registered'] = time();
				$this->db->insert('users', $user_data);
			}

			$this->update_data( array_keys( $user_data ), array_values( $user_data ) );
		}
	}

	public function language( $set = NULL ){
		
	}

	public function check_ref(){
		$query = $this->db->get_where('referrals', [
			'chat_id' => $this->chat_id,
		]);

		return (bool) $query->num_rows();
	}

	public function add_ref( $owner_id ){
		$this->db->insert('referrals', [
			'chat_id' => $this->chat_id,
			'owner_id' => $owner_id,
			'date' => time(),
		]);
	}

	public function set_additional_data( $field, $value, $chat_id = NULL ){
		if ( is_null( $chat_id ) ) $chat_id = $this->chat_id;

		$fields = $this->db->list_fields('additional_fields');
		
		if ( !in_array($field, $fields) ) {
			return FALSE;
		}

		$additional = $this->db->get_where('additional_fields' ,[
			'user_id' => $this->get_data('id')
		]);

		if ( $additional->num_rows() > 0 ) {
			$this->db->update('additional_fields', [
				$field => $value
			], [
				'user_id' => $this->get_data('id')
			]);
		}else{
			$this->db->insert('additional_fields', [
				'user_id' => $this->get_data('id'),
				$field => $value
			]);
		}

		return TRUE;
	}
}

/* End of file User_model.php */
/* Location: ./application/models/hook/User_model.php */