<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('client/Auth_model', 'auth');
	}

	public function index() {
		if ($this->auth->checkLogged() == TRUE) redirect( base_url( 'client' ) );

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$auth_data = $this->input->post();
			$login_status = $this->auth->checkAuth( $auth_data );

			if ( $login_status ) {
				$query = $this->db->get_where('clients', [
					'chat_id' => $login_status['id']
				]);

				if ( $query->num_rows() > 0 ) {
					$query = $query->row_array();
					if ( $query['status'] == '1') {
						if (array_key_exists('first_name', $login_status)) {
							$this->session->set_userdata('client_first_name', $login_status['first_name']);
						}
						if (array_key_exists('last_name', $login_status)) {
							$this->session->set_userdata('client_last_name', $login_status['last_name']);
						}
						if (array_key_exists('username', $login_status)) {
							$this->session->set_userdata('client_username', $login_status['username']);
						}
						if (array_key_exists('photo_url', $login_status)) {
							$this->session->set_userdata('client_photo_url', $login_status['photo_url']);
						}
						if (array_key_exists('auth_date', $login_status)) {
							$this->session->set_userdata('client_auth_date', $login_status['auth_date']);
						}
						$this->session->set_userdata('client_chat_id', $login_status['id']);
						$this->session->set_userdata('client_logged', TRUE);

						$this->session->set_userdata('client_id', $query['id']);
						
						$this->db->update('clients', [
							'logged' => time()
						], [
							'chat_id' => $login_status['id']
						]);

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
					        	'message' => "Foydalanuvchi o'chirilgan"
					    	])
					    );
					}
				}else{
					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(401)
				    	->set_output(json_encode([
				        	'status' => false,
				        	'message' => "Foydalanuvchi mavjud emas"
				    	])
				    );
				}
			}else{
				return $this->output
			    	->set_content_type('application/json')
			    	->set_status_header(401)
			    	->set_output(json_encode([
			        	'status' => false,
			        	'message' => "Tizimga kirishda xatolik"
			    	])
			    );
			}
		}

		$this->load->view('client/login');
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect( base_url( 'client/login' ) );
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/client/Login.php */