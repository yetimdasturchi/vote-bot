<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notificationsrecords_model extends CI_Model {

	private $rows = [];
	private $filtered = 0;
	
	public function getRows($postData){
        $this->_get_datatables_query($postData);
        return $this->rows;
    }

    public function countAll(){
        $query = $this->db->query('SELECT COUNT(*) as count FROM notifications');
		return $query->row()->count;
    }

    public function countFiltered($postData){
        return $this->filtered;
    }

    private function _get_datatables_query($postData){
        
        if( $postData['length'] != -1 ){
        	$start = $postData['start'];
        	$limit = $postData['length'];
        }else{
        	$start = 0;
        	$limit = 10;
        }

        //$query = $this->db->query( "SELECT notifications.id, notifications.chat_id, notifications.source, notifications.date, notifications.template FROM notifications ORDER BY date DESC LIMIT {$start}, $limit" );
        $query = $this->db->query( "SELECT
			notifications.id, notifications.chat_id, notifications.source, notifications.date, notifications.template, users.username, users.first_name, users.last_name, users_archive_users.users_archive_users_name, notifications_templates.name as template_name
		FROM
			notifications
		LEFT JOIN users ON users.chat_id = notifications.chat_id
		LEFT JOIN users_archive_users ON users_archive_users.users_archive_users_telegram = notifications.chat_id
		LEFT JOIN notifications_templates ON notifications_templates.id = notifications.template
        GROUP By `chat_id`
		LIMIT {$start}, {$limit}" );
        $this->filtered = $query->num_rows();
        $this->rows = $query->result_array();
    }
}

/* End of file Notificationsrecords_model.php */
/* Location: ./application/models/client/Notificationsrecords_model.php */