<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View_model extends CI_Model {
	public function get_additional_info( $id ){
		$this->load->model('xfields/Records_model', 'xfields');
		$fields = $this->xfields->get_columns();

		$query = $this->db->get_where('additional_fields', [
			'user_id' => $id
		])->row_array();

		$tmp = [];

		$additional_source = $this->getAdditionalSource( $fields );
		
		foreach ($fields as $field) {
			$source = array_key_exists($field['xfield'], $additional_source) ? $additional_source[ $field['xfield'] ] : [];
			if ( !empty( $query ) && array_key_exists($field['xfield'], $query) && $query[ $field['xfield'] ] != '' ) {
				$tmp[] = [
					'name' => $field['name'],
					'field' => $field['xfield'],
					'value' => $query[ $field['xfield'] ],
					'source' => $source
				];
			}else{
				$tmp[] = [
					'name' => $field['name'],
					'field' => $field['xfield'],
					'value' => "<code>".lang('users_undefined')."</code>",
					'source' => $source
				];
			}
		}

		return $tmp;
	}

	public function getAdditionalSource( $fields ){
		$tmp = [];
		foreach ($fields as $field) {
			$question = $this->db
				->select('id')
				->from('poll_questions')
				->where('additional_field', $field['xfield'])
				->where( 'language', getDefaultLanguage() )
				->where('status', '1')->get();
		
			if ( $question->num_rows() > 0 ) {
				$question = $question->row_array();
				$answers = $this->db
						->select('id, answer')
						->from('poll_answers')
						->where('status', '1')
						->where('question_id', $question['id'])->get();
				if ( $answers->num_rows() > 0 ) {
					$answers = $answers->result_array();
					$tmp_answers = [];
					foreach ($answers as $answer) {
						$tmp_answers[ $answer['answer'] ] = $answer['answer'];
					}

					$tmp[ $field['xfield'] ] = $tmp_answers;
				}
			}
		}

		return $tmp;
	}

	public function get_referrals_count( $id){
		return $this->db->where('owner_id', $id)->count_all_results('referrals');
	}

	public function update_additional(){
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', lang('poll_name'), 'required|min_length[3]|max_length[200]');
        $this->form_validation->set_rules('pk', lang('poll_pk'), 'required|min_length[3]|max_length[200]');

         if ( !$this->db->field_exists($this->input->post('name'), 'additional_fields')) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => false,
                    'messages' => [lang('user_field_not_exists')]
                ])
            );
        }

        $additional = $this->db->get_where('additional_fields' ,[
			'user_id' => $this->input->post('pk')
		]);

		if ( $additional->num_rows() > 0 ) {
			$this->db->update('additional_fields', [
				$this->input->post('name') => $this->input->post('value')
			], [
				'user_id' => $this->input->post('pk')
			]);
		}else{
			$this->db->insert('additional_fields', [
				'user_id' => $this->input->post('pk'),
				$this->input->post('name') => $this->input->post('value')
			]);
		}

		$this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'status' => true,
                'messages' => [lang('user_data_successfully_updated')]
            ])
        );
	}
}

/* End of file View_model.php */
/* Location: ./application/models/users/View_model.php */