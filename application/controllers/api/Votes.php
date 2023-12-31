<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Votes extends MY_Controller {

	private $contest;
	private $nomination;
	private $member;

	public function __construct() {
		parent::__construct();
		
		$this->contest = $this->input->get('contest');
		$this->sort = $this->input->get('sort');

		$this->method = $this->input->get('method');

		if ( is_null( $this->method ) ) {
			$this->method = '0';
		}

		$this->sort_columns = [
			'uz' => 'members.sort_uzbek',
			'ru' => 'members.sort_russian',
		];
	}

	public function index() {
		$nominations = $this->getNominations();
		
		if ( !empty( $nominations ) ) {
			$votes = [];

			foreach ($nominations as $nomination) {
				$member_votes = $this->getMembers( $nomination['id'] );
				
				if ( !empty( $member_votes ) ) {
					$votes[] = array_merge(
						$nomination,
						[
							'votes' => $member_votes
						]
					);
				}
				
			}

			return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'data' => array_values( $votes )
                ])
            );

		}else{
			return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Data not found'
                ])
            );
		}
	}

	private function getNominations() {
		$this->db->select('id, name_uzbek as uz, name_russian as ru');
		$this->db->from('nominations');
		$this->db->where('status', '1');
		$this->db->order_by('id', 'asc');

		if ( !empty( $this->contest ) ) {
        	$this->db->where('contest', $this->contest);
        }
		
		return  $this->db->get()
				->result_array();

	}

	private function getMembers( $nomination ){
		
		$sq = $this->db->escape('\\b('.$nomination.')\\b');

		if ( $this->method == '1' ) {
			$sql = "SELECT members.id, members.name_uzbek as uz, members.name_russian as ru,
				(SELECT COUNT(*) 
			          FROM contest_votes 
			         WHERE contest_votes.member = members.id) count,
				(SELECT COUNT(*) 
			          FROM contest_votes 
			         WHERE contest_votes.member = members.id AND check_status = 1 AND contest_votes.nomination REGEXP  ($sq) ) checked,
				(SELECT COUNT(*) 
			          FROM contest_votes 
			         WHERE contest_votes.member = members.id AND check_status = 0 AND contest_votes.nomination REGEXP  ($sq) ) unchecked,
				(SELECT COUNT(*) 
			          FROM contest_votes 
			         WHERE contest_votes.member = members.id AND check_status = 2 AND contest_votes.nomination REGEXP  ($sq) ) invalid
				FROM members
				WHERE members.nomination REGEXP  ($sq)
				AND members.contest = '1'
				AND members.status = '1'
				ORDER BY members.sort_uzbek ASC";
		}else{
			$sql = "SELECT members.id, members.name_uzbek as uz, members.name_russian as ru,
				(SELECT COUNT(*) 
		          FROM contest_votes 
		         WHERE contest_votes.member = members.id) count,
				(SELECT COUNT(*) 
		          FROM contest_votes 
		         WHERE contest_votes.member = members.id AND check_status = 1) checked,
				(SELECT COUNT(*) 
		          FROM contest_votes 
		         WHERE contest_votes.member = members.id AND check_status = 0) unchecked,
				(SELECT COUNT(*) 
		          FROM contest_votes 
		         WHERE contest_votes.member = members.id AND check_status = 2) invalid
				FROM members
				WHERE members.nomination REGEXP  ($sq)
				AND members.contest = '1'
				AND members.status = '1'
				ORDER BY members.sort_uzbek ASC";
		}

		return $this->db->query( $sql )->result_array();
	}

}

/* End of file Votes.php */
/* Location: ./application/controllers/api/Votes.php */