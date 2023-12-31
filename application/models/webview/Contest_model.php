<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contest_model extends CI_Model {
	public function get_contest( $id ){
		return $this->db->get_where('contests', [
			'id' => $id
		])->row_array();
	}

	public function getVoted(){
		// code...
	}

	public function getNominationsByContest( $id ){
		
		$language = getDefaultLanguage();

		$this->db->select([
			'id',
			'name',
			'name_uzbek',
			'name_uzbek_cyr',
			'name_russian',
			'name_english',
			'max_votes',
			'status'
		]);

		$this->db->from('nominations');
		
		$this->db->where('contest', $id);
		$this->db->where('status', '1');

		$this->db->order_by('id', 'asc');

		return $this->db->get()->result_array();
	}


	public function getMemberByNomination( $contest_id, $nomination_id ){

		$language = getDefaultLanguage();

		$this->db->select('*');
		$this->db->from('members');
		$sq = $this->db->escape('\\b('.$nomination_id.')\\b');
        $this->db->where('nomination REGEXP ', $sq, false);
        $this->db->where('contest', $contest_id);
        $this->db->where('status', '1');

        $language = getDefaultLanguage();

        $this->db->order_by('sort_'.$language, 'asc');

		return $this->db->get()->result_array();
	}

	public function getNominationById( $id ){
		$this->db->select('*');
		$this->db->from('nominations');
		$this->db->where('id', $id);
        $this->db->where('status', '1');

		return $this->db->get()->row_array();
	}

	public function getMemberById( $id ){
		$this->db->select('*');
		$this->db->from('members');
		$this->db->where('id', $id);
        $this->db->where('status', '1');

		return $this->db->get()->row_array();
	}

	public function checkVotedToMember( $id, $chat_id ){
		$query = $this->db->get_where('contest_votes', [
			'member' => $id,
			'chat_id' => $chat_id,
			'status' => 1,
		]);

        return (bool) $query->num_rows();
	}
	public function checkVotedToNomination( $id, $chat_id ){
		$query = $this->db->get_where('contest_votes', [
			'nomination' => $id,
			'chat_id' => $chat_id,
			'status' => 1,
		]);

        return (bool) $query->num_rows();
	}

	public function checkVotedToContest( $id, $contest_id ){
		$query = $this->db->get_where('contest_votes', [
			'contest' => $contest_id,
			'chat_id' => $chat_id,
			'status' => 1,
		]);

        return (bool) $query->num_rows();
	}

	public function getNominationsVotes( $nomination, $member_id, $chat_id ){
		$query = $this->db->get_where('contest_votes', [
			'chat_id' => $chat_id,
			'nomination' => $nomination,
			'member' => $member_id,
			'status' => 1,
		]);
		
        return $query->num_rows();
	}

	public function getContestVotes( $contest, $member_id, $chat_id ){
		$query = $this->db->get_where('contest_votes', [
			'chat_id' => $chat_id,
			'contest' => $contest,
			'nomination' => $nomination,
			'status' => 1,
		]);
		
        return $query->num_rows();
	}

	public function checkUserByChatId( $chat_id ){
		$query = $this->db->get_where('users', [
			'chat_id' => $chat_id
		]);
		
        return (bool) $query->num_rows();
	}

	public function vote( $contest_id, $nomination_id, $member_id, $chat_id ){
		$language = getDefaultLanguage();
		
		$check_status = $this->vote_queue( $contest_id, $chat_id, $language );

		$this->db->insert('contest_votes', [
			'chat_id' => $chat_id,
			'contest' => $contest_id,
			'nomination' => $nomination_id,
			'member' => $member_id,
			'date' => time(),
			'check_status' => $check_status,
			'status' => '1',
		]);

		$this->thankful( $contest_id, $chat_id );

		return $check_status;
	}

	public function vote_queue( $contest_id, $chat_id, $language ){
		$contest = $this->get_contest( $contest_id );
		if ( empty( $contest ) ) return '0';

		$polls = explode(',', $contest[ 'polls_' . $language ] );
		$polls_count = count( $polls );

		$st = $this->vote_queue_check_exists( $contest, $chat_id, $language );

		switch ($st) {
			case '1':
				return '1';
			break;

			case '2':
				return '0';
			break;
			
			default:
				$queues = [];
				
				if ( empty( $contest[ 'polls_' . $language] ) ) return '0';

				$polls = array_partition( explode(',', $contest[ 'polls_' . $language]), $contest[ 'polls_check' ] );

				$x = 1;
				
				foreach ($polls as $poll) {
					$send_data = strtotime( '+' . $x . ' day' );
					foreach ($poll as $k => $v) {
						$queues[] = [
							'polls_count' => $polls_count,
							'chat_id' => $chat_id,
							'contest' => $contest_id,
							'poll_'.$language => $v,
							'expire' => $send_data + 64800,
							'send_date' => $send_data + rand(0, 10800),
							'status' => '0'
						];
					}

					$x++;
				}

				$this->db->insert_batch('contest_queue', $queues);

				return '0';
			break;
		}
	}

	public function vote_queue_check_exists( $contest, $chat_id, $language ){
		$polls = explode(',', $contest[ 'polls_' . $language ] );
		$polls_count = count( $polls );
		
		$this->db->select('*');
		$this->db->from('contest_queue');
		$this->db->where( 'chat_id', $chat_id );
		//$this->db->where_in('poll_'.$language,  $polls);
		
		$query = $this->db->get();

		if ( $query->num_rows() > 0 ) {
			$x = 0;
			foreach ($query->result_array() as $row) {
				if ( $row['status'] == '1' ) {
					$x++;
				}
			}

			return ( $x >= $polls_count ) ? '1' : '2';
		}

		return '0';
	}

	public function check_hash( $chat_id, $hash ){
		$query = $this->db->get_where('users', [
			'chat_id' => $chat_id,
			'hash' => $hash
		]);
		
        return (bool) $query->num_rows();
	}

	public function getVotesId( $contest_id, $chat_id ){
		$this->db->select('contest, nomination, member');
		$this->db->from('contest_votes');
		$this->db->where('status', '1');
		$this->db->where('contest', $contest_id);
		$this->db->where('chat_id', $chat_id);

		$votes = $this->db->get();
		
		$tmp = [
			'nomination' => [],
			'member' => [],
		];

		if ( $votes->num_rows() ) {
			foreach ($votes->result_array() as $row) {
				$tmp['nomination'][] = $row['nomination'];
				$tmp['member'][] = $row['member'];
				$tmp['thismember'][ $row['member'] ] = $row['nomination'];
			}
		}

		return $tmp;
	}

	public function thankful( $contest_id, $chat_id ){
		$query = $this->db->get_where('contest_votes', [
			'chat_id' => $chat_id,
			'contest' => $contest_id,
			'thankful' => '1'
		]);

		if ( $query->num_rows() == 0 ) {

			$this->load->library('Telegram');
			$this->load->helper('bot_helper');
			$this->load->model('Module_model', 'module');
			$this->load->model('hook/Command_model', 'command');
			$this->load->model('hook/Menu_model', 'menu');
			$this->load->model('hook/User_model', 'user');
			$this->load->model('hook/Bot_model', 'bot');

			$this->telegram->set_chatId( $chat_id );
			$this->user->set_chatId( $chat_id );

			$this->bot->messageText( '/invite', $chat_id, 'command_set' );

			$this->db->update('contest_votes', [
				'thankful' => '1'
			], [
				'chat_id' => $chat_id,
				'contest' => $contest_id,
			]);

		}
	}
}

/* End of file Contest_model.php */
/* Location: ./application/models/webview/Contest_model.php */