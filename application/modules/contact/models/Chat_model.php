<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends CI_Model {
	public $module_name;
	public $args;
	public $per_page = 10;

	public function __construct() {
		parent::__construct();
		$this->load->helper('text');
	}

	public function index(){
		if ( !empty( $this->args[1] ) && method_exists( $this, $this->args[1] ) ) {
			$method = $this->args[1];
			return $this->$method();
		}

		$totalChats = $this->getChatsCount();
		$data['module_name'] = $this->module_name;
        $data['total_pages']  = ceil( $totalChats / $this->per_page );

        if( ! empty( $this->input->get( "page" ) ) ){
            $start = $this->per_page * $this->input->get( 'page' );
            $data['chats'] = $this->getChats( $this->per_page, $start );
        }else {
            $start = 0;
            $data['chats'] = $this->getChats( $this->per_page, $start );
        }

		$this->module->load_index([
			'index_title' => lang('module_name'),
			'load_css' => [
				base_url('assets/libs/emoji-picker/lib/css/emoji.css')
			],
			'load_js' => [
				base_url('assets/libs/emoji-picker/lib/js/config.min.js'),
				base_url('assets/libs/emoji-picker/lib/js/util.min.js'),
				base_url('assets/libs/emoji-picker/lib/js/jquery.emojiarea.min.js'),
				base_url('assets/libs/emoji-picker/lib/js/emoji-picker.min.js'),
				base_url('assets/js/modules/chat.js') . '?t='.time()
			],
			'content' => 'module_chat',
			'content_data' => $data
		]);
	}

	public function get_chat_window(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$chat = $this->getChat( $this->args[2] );
			if ( $chat ) {
				return $this->output
	        		->set_content_type('application/json')
	        		->set_status_header(200)
					->set_output(json_encode([
	            		'status' => true,
	            		'chat' => $chat,
	            		'html' => $this->load->view('module_chat_window', array_merge($chat, ['module_name' => $this->module_name]), TRUE)
	    			])
				);	
			}else{
				return $this->output
		            ->set_content_type('application/json')
		            ->set_status_header(400)
		            ->set_output(json_encode([
		                'status' => false,
		                'messages' => [ lang('chat_user_not_found') ]
		            ])
	        	);
			}
		}else{
			return $this->output
	            ->set_content_type('application/json')
	            ->set_status_header(400)
	            ->set_output(json_encode([
	                'status' => false,
	                'messages' => [ lang('chat_method_not_allowed') ]
	            ])
	        );
		}
	}

	public function get_chats(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('query', lang('chat_query'), 'required|min_length[4]');
			if ( $this->form_validation->run() ) {
				$query = $this->input->post('query');
				$data = $this->searchChats( $query );
				
				$module_name = $this->module_name;

				$data = array_map( function($x) use( &$query, &$module_name ) {
					$x['url'] = base_url( $module_name . '/get_chat_window/' . $x['chat_id'] );

					if ( strpos($x['first_name'], $query) !== false  || strpos($x['last_name'], $query) !== false ) {
						$x['text'] = $x['first_name'] . ' ' .$x['last_name'];
						return $x;
					}

					if ( strpos($x['username'], $query) !== false ) {
						$x['text'] = '@' . $x['username'];
						return $x;
					}

					$x['text'] = $x['chat_id'];
					return $x;
				}, $data);
				return $this->output
		            ->set_content_type('application/json')
		            ->set_status_header(200)
		            ->set_output(json_encode([
		                'status' => true,
		                'data' => $data
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
		}else{
			return $this->output
	            ->set_content_type('application/json')
	            ->set_status_header(400)
	            ->set_output(json_encode([
	                'status' => false,
	                'messages' => [ lang('chat_method_not_allowed') ]
	            ])
	        );
		}
		
	}

	public function getChat( $id ){
		$this->db->select( 'u.chat_id, u.username, u.first_name, u.last_name');
		$this->db->from( 'users u' );
		$this->db->where( 'u.chat_id' , $id);
		$query = $this->db->get();
        return ( $query->num_rows() > 0 ) ? $query->row_array() : FALSE ;
	}

	public function searchChats( $query ){
		$this->db->select('chat_id, username, first_name, last_name');
		$this->db->limit(5);
		
		$this->db->like('chat_id', $query);
		$this->db->or_like('username', $query);
		$this->db->or_like('first_name', $query);
		$this->db->or_like('last_name', $query);

        $query = $this->db->get( 'users' );
        return $query->result_array();
	}

	public function getChats( $limit, $start ){
		$this->db->select( 'c.*, u.chat_id, u.username, u.first_name, u.last_name');
		$this->db->select( '(SELECT COUNT(contact_id) FROM ( SELECT c1.contact_id FROM contact c1 WHERE c1.contact_read = 0 AND c1.contact_from = c.contact_from ) AS items) as unread');
		$this->db->from( $this->module_name . ' c' );

		$this->db->join('users u', 'u.chat_id = c.'.$this->module_name.'_from');
		$this->db->group_by( 'c.' . $this->module_name . '_from' );
		$this->db->limit($limit, $start);
		$this->db->order_by( 'c.' . $this->module_name . '_send', 'desc' );
        $query = $this->db->get( $this->module_name );
        return $query->result_array();
	}

	public function getChatsCount(){
		$this->db->from( $this->module_name );
        return $this->db->count_all_results();
	}
}

/* End of file Chat_model.php */
/* Location: ./application/modules/contact/models/Chat_model.php */