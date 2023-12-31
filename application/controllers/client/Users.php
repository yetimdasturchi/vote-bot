<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
		$this->load->model('client/Users_model', 'users');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('client/login') );
		}
	}

	public function index() {
		$this->load->view('client/index', [
			'index_title' => "Foydalanuvchilar",
			'load_css' => [
				base_url('assets/libs/datatables/dataTables.bootstrap4.min.css'),
				base_url('assets/libs/datatables/buttons.bootstrap4.min.css'),
				base_url('assets/libs/datatables/responsive.bootstrap4.min.css'),
			],
			'load_js' => [
				base_url('assets/libs/datatables/jquery.dataTables.min.js'),
				base_url('assets/libs/datatables/dataTables.bootstrap4.min.js'),
				base_url('assets/libs/datatables/dataTables.buttons.min.js'),
				base_url('assets/libs/datatables/buttons.bootstrap4.min.js'),
				base_url('assets/libs/datatables/jszip.min.js'),
				base_url('assets/libs/datatables/pdfmake.min.js'),
				base_url('assets/libs/datatables/vfs_fonts.js'),
				base_url('assets/libs/datatables/buttons.html5.min.js'),
				base_url('assets/libs/datatables/buttons.print.min.js'),
				base_url('assets/libs/datatables/buttons.colVis.min.js'),
				base_url('assets/libs/datatables/dataTables.responsive.min.js'),
				base_url('assets/libs/datatables/responsive.bootstrap4.min.js'),
				base_url('assets/js/dtable.js'),
			],
			'content' => 'client/users',
			'content_data' => [
				'additional_fields' => $this->users->get_columns(),
				'ct' => $this->users->get_ct(),
				'gender' => $this->users->get_gender()
			]
		]);
	}

	public function getlist(){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}
		
        $memData = $this->users->getRows( $this->input->post() );
        
        $memData = array_map(function($x){
        	
        	$name = "";

        	if ( !empty( $x['first_name'] ) ) {
				$name = $x['first_name'];        		
        	}

        	if ( !empty( $x['last_name'] ) ) {
        		$name .= $x['last_name'];
        	}

        	if ( !empty( $x['username'] ) ) {
				$name .= " ( @{$x['username']} )";
			}
			
			$name = trim( $name );

			if ( mb_strlen( $name, "UTF-8" ) > 52 ) {
				$name = "Excepted";
			}

			$x['phone'] = empty( $x['phone'] ) ? "<code>-</code>" : format_phone($x['phone']);
			
			$x['username'] = !empty( $name ) ? $name : $x['chat_id'];

			switch( $x['language'] ) {
				case 'uzbek':
					$x['language'] = '<img src="'.base_url('assets/images/flags/uz.jpg').'" class="me-1" height="11"> O\'zbek';
				break;

				case 'russian':
					$x['language'] = '<img src="'.base_url('assets/images/flags/ru.jpg').'" class="me-1" height="11"> Rus';
				break;
				
				default:
					$x['language'] = "-";
				break;
			}

        	$x['registered'] = date("d.m.Y (H:i)", $x['registered']);
        	$x['last_action'] = date("d.m.Y (H:i)", $x['last_action']);

        	if ( in_array($x['gender'], ['🧑 Erkak', '🧑 Мужчина']) ) {
                $x['gender'] = "Erkak";
            }else if ( in_array($x['gender'], ['👩 Ayol', '👩 Женщина']) ) {
                $x['gender'] = "Ayol";
            }

        	if ( empty( $x['age'] ) ) $x['age'] = '<code>-</code>';
        	if ( empty( $x['gender'] ) ) $x['gender'] = '<code>-</code>';
        	if ( empty( $x['city'] ) ) $x['city'] = '<code>-</code>';

        	$x['gender'] = remove_emojis( $x['gender'] );

        	$x['action'] = <<<EOL
                <button type="button" data-open-modal="${!${''} = base_url('client/users/votes/' . $x['chat_id']) }" data-modal-title="Ovozlar" class="btn btn-outline-success btn-sm waves-light mb-1 mx-1">Ovozlar</button><br />
                <button type="button" data-open-modal="${!${''} = base_url('client/users/question/' . $x['chat_id']) }" data-modal-title="Savollar" class="btn btn-outline-primary btn-sm waves-light mb-1 mx-1">Savollar</button><br />
                <button type="button" data-open-modal="${!${''} = base_url('client/users/referrals/' . $x['chat_id']) }" data-modal-title="Referallar" class="btn btn-outline-secondary btn-sm waves-light mb-1 mx-1">Referallar</button><br />
                <button type="button" data-open-modal="${!${''} = base_url('client/users/edit/' . $x['id']) }" data-modal-title="Tahrirlash" class="btn btn-outline-warning btn-sm waves-light mx-1">Tahrirlash</button>
            EOL;

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->users->countAll(),
            "recordsFiltered" => $this->users->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
    }

    public function edit( $id='' ) {
    	$query = $this->db->get_where('users', [
    		'id' => $id
    	]);

 		if ( $query->num_rows() > 0 ) {

 			$query = $query->row_array();

 			if ($this->input->server('REQUEST_METHOD') === 'POST') {
				return $this->users->edit( $id );
			}
 			
 			$html = $this->load->view('client/modals/user_edit', [
 				'user_data' => $query,
 				'additionals' => $this->users->get_additional_info( $id, $query['language'] ),
 				'id' => $id
 			], TRUE);

	    	return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode([
		    		'status' => true,
		    		'html' => $html,
		    		'size' => 'small'
		    	]));
 		}

 		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode([
                'status' => false,
                'messages' => [
                    'addedd' => "Ma'lumotlar mavjud emas"
                ]
            ])
        );
    }

    public function votes( $chat_id='' ){
    	$this->db->select( "v.id, v.chat_id, v.date, v.check_status, u.id as u_id, u.phone as u_phone, u.username as u_username, u.first_name  as u_first_name, u.last_name  as u_last_name, c.name contest_name, m.name as member_name, n.name as nomination_name" );
        $this->db->from('contest_votes v');
 		$this->db->join('users u', 'u.chat_id = v.chat_id');
 		$this->db->join('contests c', 'c.id = v.contest');
 		$this->db->join('members m', 'm.id = v.member');
 		$this->db->join('nominations n', 'n.id = v.nomination');
 		$this->db->where('v.chat_id', $chat_id);

 		$query = $this->db->get();

 		if ( $query->num_rows() > 0 ) {
 			$html = $this->load->view('client/modals/user_votes', [ 'votes' => $query->result_array() ], TRUE);
	    	return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode([
		    		'status' => true,
		    		'html' => $html,
		    		'size' => 'large'
		    	]));
 		}

 		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode([
                'status' => false,
                'messages' => [
                    'addedd' => "Ma'lumotlar mavjud emas"
                ]
            ])
        );
    	
    }

    public function question( $chat_id='' ){
    	 $this->db->select( "q.id, q.chat_id, q.expire, q.expire, q.send_date, q.answered, q.sended, q.tg_status, q.status, u.id as u_id, u.username as u_username, u.phone as u_phone, u.first_name  as u_first_name, u.last_name  as u_last_name, c.name contest_name, pu.name as uz_name, puc.name as uzc_name, pr.name as ru_name, pe.name as en_name" );
        $this->db->from('contest_queue q');
 		$this->db->join('users u', 'u.chat_id = q.chat_id');
 		$this->db->join('contests c', 'c.id = q.contest');
 		$this->db->join('poll_questions pu', 'pu.id = q.poll_uzbek', 'left');
 		$this->db->join('poll_questions puc', 'puc.id = q.poll_uzbek_cyr', 'left');
 		$this->db->join('poll_questions pr', 'pr.id = q.poll_russian', 'left');
 		$this->db->join('poll_questions pe', 'pe.id = q.poll_eglish', 'left');
 		$this->db->where('q.chat_id', $chat_id);

 		$query = $this->db->get();

 		if ( $query->num_rows() > 0 ) {
 			$html = $this->load->view('client/modals/user_questions', [ 'questions' => $query->result_array() ], TRUE);
	    	return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode([
		    		'status' => true,
		    		'html' => $html,
		    		'size' => 'large'
		    	]));
 		}

 		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode([
                'status' => false,
                'messages' => [
                    'addedd' => "Ma'lumotlar mavjud emas"
                ]
            ])
        );
    }

    public function referrals($chat_id=''){
    	$this->db->select( "referrals.*, u.username as u_username, u.first_name  as u_first_name, u.last_name  as u_last_name, o.username as o_username, o.first_name  as o_first_name, o.last_name  as o_last_name, (SELECT COUNT(*) 
		          FROM contest_votes 
		         WHERE contest_votes.chat_id = referrals.chat_id AND check_status = 1) checked, (SELECT COUNT(*) 
		          FROM contest_votes 
		         WHERE contest_votes.chat_id = referrals.chat_id AND check_status = 0) unchecked, (SELECT COUNT(*) 
		          FROM contest_votes 
		         WHERE contest_votes.chat_id = referrals.chat_id AND check_status = 2) ignored" );
        $this->db->from('referrals');
 		$this->db->join('users u', 'u.chat_id = referrals.chat_id');
 		$this->db->join('users o', 'o.chat_id = referrals.owner_id');
 		$this->db->where('referrals.owner_id', $chat_id);

 		$query = $this->db->get();

 		if ( $query->num_rows() > 0 ) {
 			$html = $this->load->view('client/modals/user_referrals', [ 'referrals' => $query->result_array() ], TRUE);
	    	return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode([
		    		'status' => true,
		    		'html' => $html,
		    		'size' => 'large'
		    	]));
 		}

 		return $this->output
            ->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode([
                'status' => false,
                'messages' => [
                    'addedd' => "Ma'lumotlar mavjud emas"
                ]
            ])
        );
    }

    public function export(){
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$ulanguage = $this->input->post('ulanguage');
			$ucity = $this->input->post('ucity');
			$ugender = $this->input->post('ugender');
			$term = $this->input->post('term');

			return $this->users->exportData( $ulanguage, $ucity, $ugender, $term );
		}

		$filename = $this->input->get('filename');
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if ( $ext == 'xlsx' && file_exists( FCPATH . 'tmp/'.$filename ) ) {
			$this->load->helper('download');
			force_download(FCPATH . 'tmp/'.$filename, NULL, FALSE, TRUE);
		}
	}

}

/* End of file Users.php */
/* Location: ./application/controllers/client/Users.php */