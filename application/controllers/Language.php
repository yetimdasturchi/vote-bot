<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('user_agent');
	}

	public function _remap($language=''){
		$this->set( $language );
	}

	public function set( $item='' ) {
		if ( !empty( $item ) ) {
			$info_file = APPPATH . 'language' . DIRECTORY_SEPARATOR . $item . DIRECTORY_SEPARATOR . 'info.json';
			if ( file_exists( $info_file ) ) {
				set_cookie( 'language', $item, strtotime("+1 year") );
			}
		}

		if ( $this->agent->is_referral() ) {
    		redirect( $this->agent->referrer() );
		}else{
			redirect( base_url( 'manage' ) );
		}
	}

}

/* End of file Language.php */
/* Location: ./application/controllers/Language.php */