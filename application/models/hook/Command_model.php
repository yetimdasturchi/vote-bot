<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Command_model extends CI_Model {
	public function get_command( $user_command, $by_key='command_set' ){
		
		$language = $this->user->get_data('language');

		if ( empty( $language ) ) $language = getDefaultLanguage();

		$commands = $this->get_commands();
		$command_hash = command_hash( $user_command, $language );

		if ( ! $command = $this->cache->file->get( $command_hash ) ) {
			$command = array_values( array_filter($commands,function($v,$k) use ( $user_command, $by_key, $language ){
			  return ( $v[ $by_key ] == $user_command && $language == $v[ 'language' ] );
			}, ARRAY_FILTER_USE_BOTH));

			if ( !empty( $command[0] ) ) {
				$command = $command[0];
				$this->cache->file->save( $command_hash, json_encode( $command ), 3600);
			}else{
				return FALSE;
			}
		}

		return is_array( $command ) ? $command : json_decode( $command, TRUE );
	}

	public function get_chilrens($command_id, $by_key='prent_command'){
		$commands = $this->get_commands();

		if ( ! $childrens = $this->cache->file->get( 'childrens_' . $command_id . '.command' ) ) {
			$childrens = array_values( array_filter($commands,function($v,$k) use ( $command_id, $by_key ){
			  return $v[ $by_key ] == $command_id;
			}, ARRAY_FILTER_USE_BOTH));

			if ( !empty( $childrens ) ) {
				usort($childrens, function($a, $b) {
    				return $a['sort'] <=> $b['sort'];
				});
				$this->cache->file->save( 'childrens_' . $command_id . '.command', json_encode( $childrens ), 3600);
			}else{
				return FALSE;
			}
		}

		return is_array( $childrens ) ? $childrens : json_decode( $childrens, TRUE );
	}

	public function get_last_command( $last_command ){
		$last_command = $this->command->get_command( $last_command, 'command_set' );
		if ( $last_command ) {
			$parent_command = $this->command->get_command( $last_command['prent_command'], 'command_id' );
			if ( $parent_command ) {
				return $parent_command['command_set'];
			}
		}

		return FALSE;
	}

	private function get_commands(){
		if ( ! $commands = $this->cache->file->get( 'list.commands' ) ) {
			$query = $this->db->get('commands');
			if ( $query->num_rows() > 0 ) {
				$commands = $query->result_array();
				$this->cache->file->save( 'list.commands', json_encode( $commands ), 3600);
			}else{
				return FALSE;
			}
		}

		if ( is_string( $commands ) ) $commands = json_decode( $commands, TRUE );
		
		return $commands;
	}

	public function send_command( $command ){
		if ( !empty( $command['file'] ) ) {
			$command['file'] = json_decode( $command['file'], TRUE );
			switch ( $command['file']['type'] ) {
				case 'photo':
					$this->telegram->send_photo( $command['file']['file'], $command['command_message'] );
				break;

				case 'video':
					$this->telegram->send_video( $command['file']['file'], $command['command_message'] );
				break;

				case 'document':
					$this->telegram->send_document( $command['file']['file'], $command['command_message'] );
				break;

				case 'audio':
					$this->telegram->send_audio( $command['file']['file'], $command['command_message'] );
				break;

				case 'voice':
					$this->telegram->send_voice( $command['file']['file'], $command['command_message'] );
				break;

				case 'animation':
					$this->telegram->send_animation( $command['file']['file'], $command['command_message'] );
				break;
			}
		}else{
			$this->telegram->send_message( $command['command_message'] );
		}
	}
}

/* End of file Command_model.php */
/* Location: ./application/models/bot/Command_model.php */