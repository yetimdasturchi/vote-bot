<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members_model extends CI_Model {
	function __construct() {
        $this->table = 'members';
        $this->column_order = array(null, 'id','name','image','status');
        $this->column_search = array('id','name', 'name_uzbek', 'name_uzbek_cyr', 'name_russian', 'name_english','description','description_uzbek','description_uzbek_cyr','description_russian','description_english');
        $this->order = array('id' => 'asc');
    }

    public function getRows($postData){
        $this->_get_datatables_query($postData);
        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function countAll(){
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function countFiltered($postData){
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query($postData){
         
        $this->db->from($this->table);
 
        $i = 0;
        foreach($this->column_search as $item){
            if($postData['search']['value']){
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                if(count($this->column_search) - 1 == $i){
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if(isset($postData['contest'])){
            $this->db->where_in('contest', $postData['contest']);
        }

        if(isset($postData['nomination'])){
            $sq = $this->db->escape('\\b('.$postData['nomination'].')\\b');
            $this->db->where('nomination REGEXP ', $sq, false);
        }

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function add( $contest_id, $nomination_id ){
    	$this->set_rules();

    	if ( $this->form_validation->run() ) {
    		$status = $this->input->post('status');
			$name = $this->input->post('name');

            $name_uzbek = $this->input->post('name_uzbek');
            $name_uzbek_cyr = $this->input->post('name_uzbek_cyr');
            $name_russian = $this->input->post('name_russian');
            $name_english = $this->input->post('name_english');

            $description = $this->input->post('description');

            $description_uzbek = $this->input->post('description_uzbek');
            $description_uzbek_cyr = $this->input->post('description_uzbek_cyr');
            $description_russian = $this->input->post('description_russian');
            $description_english = $this->input->post('description_english');

            $nominations = $this->input->post('nomination');

            if ( empty( $nominations ) ) $nominations = [ $nomination_id ];

            $buttons = $this->input->post('buttons');

            if ( $_FILES["file"]["error"] == 0 ) {
                if ( FALSE == FALSE ) {
                	$ext = strtolower( pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION) );
                   	if ( !in_array( $ext, ['png', 'jpeg', 'jpg', 'gif']) ) {
                   		$this->output->set_content_type('application/json')
	                        ->set_status_header(400)
	                        ->_display(json_encode([
	                            'status' => false,
	                            'messages' => [ lang('polls_ext_not_allowed') ]
	                        ])
	                    );
	                    exit(0);
                   	}

                   	$image = md5( $_FILES["file"]["name"]. time() ) . '.' . $ext;

                   	if ( ! @move_uploaded_file( $_FILES["file"]["tmp_name"], FCPATH . 'uploads/members/' . $image) ) {
                   		$this->output->set_content_type('application/json')
                        	->set_status_header(400)
                        	->_display(json_encode([
                            	'status' => false,
                            	'messages' => [ lang('polls_file_not_uploaded') ]
                        	])
                    	);
                    	exit(0);
                   	}

                   
                }
            }

            $this->db->insert('members', [
            	'contest' => $contest_id,
            	'nomination' => implode(',', $nominations),
				'name' => $name,
                'name_uzbek' => $name_uzbek,
                'name_uzbek_cyr' => $name_uzbek_cyr,
                'name_russian' => $name_russian,
                'name_english' => $name_english,
                'description' => $description,
                'description_uzbek' => $description_uzbek,
                'description_uzbek_cyr' => $description_uzbek_cyr,
                'description_russian' => $description_russian,
                'description_english' => $description_english,
                'image' => isset( $image ) && !empty( $image ) ? $image : '',
        		'status' => $status,
        		'buttons' => !empty( $buttons ) ? json_encode( $buttons ) : ''
			]);

			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode([
		        	'status' => true,
                    'reset' => true,
                    'messages' => [
                        'addedd' => lang('poll_successfully_added')
                    ],
                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('polls/web/members/'.$contest_id.'/'.$nomination_id.'/')."' }, 1000);}"
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
    }

    public function edit( $contest_id, $nomination_id, $id, $query ){
    	$this->set_rules();

    	if ( $this->form_validation->run() ) {
    		$status = $this->input->post('status');
			$name = $this->input->post('name');

            $name_uzbek = $this->input->post('name_uzbek');
            $name_uzbek_cyr = $this->input->post('name_uzbek_cyr');
            $name_russian = $this->input->post('name_russian');
            $name_english = $this->input->post('name_english');

            $description = $this->input->post('description');

            $description_uzbek = $this->input->post('description_uzbek');
            $description_uzbek_cyr = $this->input->post('description_uzbek_cyr');
            $description_russian = $this->input->post('description_russian');
            $description_english = $this->input->post('description_english');

            $nominations = $this->input->post('nomination');

            if ( empty( $nominations ) ) $nominations = [ $nomination_id ];

            $buttons = $this->input->post('buttons');

            if ( $_FILES["file"]["error"] == 0 ) {
                if ( FALSE == FALSE ) {
                	$ext = strtolower( pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION) );
                   	if ( !in_array( $ext, ['png', 'jpeg', 'jpg', 'gif']) ) {
                   		$this->output->set_content_type('application/json')
	                        ->set_status_header(400)
	                        ->_display(json_encode([
	                            'status' => false,
	                            'messages' => [ lang('polls_ext_not_allowed') ]
	                        ])
	                    );
	                    exit(0);
                   	}

                   	$image = md5( $_FILES["file"]["name"]. time() ) . '.' . $ext;

                   	if ( ! @move_uploaded_file( $_FILES["file"]["tmp_name"], FCPATH . 'uploads/members/' . $image) ) {
                   		$this->output->set_content_type('application/json')
                        	->set_status_header(400)
                        	->_display(json_encode([
                            	'status' => false,
                            	'messages' => [ lang('polls_file_not_uploaded') ]
                        	])
                    	);
                    	exit(0);
                   	}

                   	if ( !empty( $query['image'] ) ) @unlink( FCPATH . 'uploads/members/' . $query['image'] );
                }
            }

            $this->db->update('members', [
            	'name' => $name,
                'nomination' => implode(',', $nominations),
                'name_uzbek' => $name_uzbek,
                'name_uzbek_cyr' => $name_uzbek_cyr,
                'name_russian' => $name_russian,
                'name_english' => $name_english,
                'description' => $description,
                'description_uzbek' => $description_uzbek,
                'description_uzbek_cyr' => $description_uzbek_cyr,
                'description_russian' => $description_russian,
                'description_english' => $description_english,
                'image' => isset( $image ) && !empty( $image ) ? $image : $query['image'],
        		'status' => $status,
        		'buttons' => !empty( $buttons ) ? json_encode( $buttons ) : ''
			], ['id' => $id]);

			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(200)
		    	->set_output(json_encode([
		        	'status' => true,
                    'messages' => [
                        'addedd' => lang('poll_data_successfully_updated')
                    ],
                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('polls/web/members/'.$contest_id.'/'.$nomination_id.'/')."' }, 1000);}"
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
    }

    public function set_rules(){
    	$this->load->library('form_validation');

    	$this->form_validation->set_rules('name', lang('poll_name'), 'required');
		$this->form_validation->set_rules('status', lang('polls_status'), 'required|in_list[0,1]');

		$buttons = $this->input->post('buttons');
        
        if ( !empty( $buttons ) ) {
            $error = "";
            foreach ($buttons as $button) {
                if ( empty( $button['name'] ) ){
                    $error = lang('polls_button_name_not_entered');
                    break;
                }
                if ( empty( $button['value'] ) ){
                    $error = lang('polls_button_value_not_entered');
                    break;
                }
                if ( empty( $button['type'] ) ) {
                    $error = lang('polls_button_type_not_selected');
                    break;
                }
                if ( !empty( $button['type'] ) && !in_array($button['type'], ['url']) ) {
                    $error = lang('polls_button_type_not_matched');
                    break;
                }

                if ( !empty( $button['language'] ) && !in_array($button['language'], ['uzbek', 'uzbek_cyr', 'russian', 'english']) ) {
                    $error = lang('polls_button_language_not_matched');
                    break;
                }
            }

            if ( !empty( $error ) ) {
                $this->output->set_content_type('application/json')
                    ->set_status_header(400)
                    ->_display(json_encode([
                        'status' => false,
                        'messages' => [$error]
                    ])
                );
                exit(0);
            }
        }
    }

    public function get_nominations(){
        $search = $this->input->get('search');
        $page = $this->input->get('page');
        $contest = $this->input->get('contest');

        $this->db->select('id, name as text');
        $this->db->from('nominations');
        
        if ( !empty( $search ) ) {
            $this->db->like('name', $search);
            $this->db->like('name_uzbek', $search);
            $this->db->like('name_uzbek_cyr', $search);
            $this->db->like('name_russian', $search);
            $this->db->like('name_english', $search);
        }

        if ( !empty( $page ) ) {
            $resultCount = 10;
            $end = ($page - 1) * $resultCount;
            $start = $end + $resultCount;

            $this->db->limit($start, $end);
        }

        $this->db->where('contest', $contest);
        $this->db->where('status', '1');
        $this->db->order_by('name', 'asc');

        return $this->db->get()->result_array();
    }

    public function get_selected_nominations( $ids ){
        $this->db->select('id, name as text');
        $this->db->from('nominations');
        $this->db->where_in('id', explode( ',', $ids ) );

        $this->db->where('status', '1');
        $this->db->order_by('name', 'asc');

        return $this->db->get()->result_array();
    }
}

/* End of file Members_model.php */
/* Location: ./application/models/polls/web/Members_model.php */