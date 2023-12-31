<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->lang->pack( 'login');
	}

	public function index() {
		if ($this->auth->checkLogged() == TRUE) redirect( base_url( 'manage' ) );

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$auth_data = $this->input->post();
			$login_status = $this->auth->checkAuth( $auth_data );

			/*$login_status =  [
				'first_name' => "Manuchehr",
				'last_name' => "Usmonov",
				'username' => "@ytmdt",
				'photo_url' => "https://t.me/i/userpic/320/m7PbfTrwx9kvI2pP3wi7n8Rav9e9wxBexXLddS-M0Bw.jpg",
				'auth_date' => time(),
				'id' => '441307831'
			];*/

			if ( $login_status ) {
				$query = $this->db->get_where('managers', [
					'manager_telegram' => $login_status['id']
				]);

				if ( $query->num_rows() > 0 ) {
					$query = $query->row_array();
					if ( $query['manager_status'] == '1') {
						if (array_key_exists('first_name', $login_status)) {
							$this->session->set_userdata('manager_first_name', $login_status['first_name']);
						}
						if (array_key_exists('last_name', $login_status)) {
							$this->session->set_userdata('manager_last_name', $login_status['last_name']);
						}
						if (array_key_exists('username', $login_status)) {
							$this->session->set_userdata('manager_username', $login_status['username']);
						}
						if (array_key_exists('photo_url', $login_status)) {
							$this->session->set_userdata('manager_photo_url', $login_status['photo_url']);
						}
						if (array_key_exists('auth_date', $login_status)) {
							$this->session->set_userdata('manager_auth_date', $login_status['auth_date']);
						}
						$this->session->set_userdata('manager_telegram', $login_status['id']);
						$this->session->set_userdata('logged', TRUE);

						$this->session->set_userdata('manager_modules', $query['manager_modules']);
						$this->session->set_userdata('manager_id', $query['manager_id']);
						
						$this->db->update('managers', ['manager_logged' => time()], ['manager_telegram' => $login_status['id']]);

						return $this->output
					    	->set_content_type('application/json')
					    	->set_status_header(200)
					    	->set_output(json_encode([
					        	'status' => true,
					    	])
					    );

					}else{
						return $this->output
					    	->set_content_type('application/json')
					    	->set_status_header(401)
					    	->set_output(json_encode([
					        	'status' => false,
					        	'message' => lang('login_account_disabled')
					    	])
					    );
					}
				}else{
					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(401)
				    	->set_output(json_encode([
				        	'status' => false,
				        	'message' => lang('login_not_exist')
				    	])
				    );
				}
			}else{
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(401)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'message' => lang('login_error')
			    	])
			    );
			}
		}

		$this->load->view('manage/login');
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect( base_url( 'login' ) );
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */