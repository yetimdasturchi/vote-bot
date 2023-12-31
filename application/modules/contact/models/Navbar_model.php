<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Navbar_model extends CI_Model {
	public $module_name;

	public function parse(){
		$unread_messages = $this->get_unread_count();
		
		$button = <<<EOL
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-location="${!${''} = base_url($this->module_name) }">
                    <i class="bx bx-chat ${!${''} = ( $unread_messages > 0 ? 'bx-tada' : ' ') }"></i>
                    ${!${''} = ( $unread_messages > 0 ? '<span class="badge bg-danger rounded-pill">'.$unread_messages.'</span>' : ' ' ) }
                </button>
            </div>
        EOL;

		return $button;
	}

	public function get_unread_count(){
		return $this->db->where( $this->module_name . '_read', '0' )->from( $this->module_name )->count_all_results();
	}
}

/* End of file Navbar_model.php */
/* Location: ./application/modules/contact/models/Navbar_model.php */