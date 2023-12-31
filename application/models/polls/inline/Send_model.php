<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_model extends CI_Model {
	public function getChannels(){
		$search = $this->input->get('search');
        $page = $this->input->get('page');

		$this->db->select('channel_chat_username as id, channel_name as text');
		$this->db->from('channels');
		
		if ( !empty( $search ) ) {
            $this->db->like('channel_chat_username', $search);
            $this->db->or_like('channel_chat_id', $search);
            $this->db->or_like('channel_name', $search);
        }

        if ( !empty( $page ) ) {
            $resultCount = 10;
            $end = ($page - 1) * $resultCount;
            $start = $end + $resultCount;

            $this->db->limit($start, $end);
        }

		$this->db->where('channel_status', '1');
		$this->db->order_by('channel_name', 'asc');

		$query = $this->db->get()->result_array();

        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($query));
	}

	public function process( $id ){
		$channels = $this->input->post('channels');
		
		if ( empty( $channels ) ) {
			return $this->output
	    		->set_content_type('application/json')
	    		->set_status_header(400)
	    		->set_output(json_encode([
	    			'status' => false,
	    			'messages' => [ lang('polls_channels_not_selected') ]
	    		]));
		}

		$message = $this->get_poll( $id );

		if ( !is_array( $message ) ) {
			return $this->output
	    		->set_content_type('application/json')
	    		->set_status_header(400)
	    		->set_output(json_encode([
	    			'status' => false,
	    			'messages' => [ lang('polls_channels_send_error') ]
	    		]));
		}

		$data = "<?php\nreturn false;\n?>";
		file_put_contents( APPPATH . 'config/notifications.php', $data);

		$filetime = time();

		foreach ($channels  as $channel) {
			$filetime += rand(1, 2);
			$message['chat_id'] = $channel;
			$filename = FCPATH . 'tmp/notifications/m_' . $channel .'_'. md5( $channel.time() ) . '.json';
			file_put_contents( $filename , json_encode( $message ));
			touch($filename, $filetime);
		}

		return $this->output
    		->set_content_type('application/json')
    		->set_status_header(200)
    		->set_output(json_encode([
    			'status' => true,
    			'messages' => [lang('polls_channels_send_ok')],
    			'_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('notifications/records')."' }, 1000);}"
    		]));

	}

	private function get_poll( $id ) {
		$question = $this->db->get_where( 'poll_questions', ['id' => $id] );
		if ( $question->num_rows() == 0 ) return FALSE;
		$question = $question->row_array();

		$answers = $this->db->get_where( 'poll_answers', [ 'question_id' => $id, 'status' => '1' ] );
		if ( $answers->num_rows() == 0 ) return FALSE;
		$answers = $answers->result_array();

		if ( !empty( $question['file'] ) ) {
			$file = json_decode( $question['file'], TRUE );
		}
		

		$data = [
			'message' => $question['question'],
			'buttons' => array_map(function($x) use ( &$id ) {
				return [
					'name' => $x['answer'],
					'value' => "https://t.me/".$GLOBALS['system_config']['bot_username']."?start=pi-".$id,
					'type' => 'url',
				];
			}, $answers)
		];

		if ( isset( $file ) )
			$data = array_merge( $data, $file );
		
		return $data;
	}
}

/* End of file Send_model.php */
/* Location: ./application/models/polls/inline/Send_model.php */