<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commands_model extends CI_Model {
	function __construct() {
        $this->load->library('form_validation');

        $this->table = 'commands';
        $this->column_order = array(null, 'commands.command_id','commands.command_set','commands.command_message','commands.prent_command','commands.sort', 'commands.chunk', 'commands.function');
        $this->column_search = array('commands.command_set','commands.command_message','commands.inline_keyboard', 'c1.command_set');
        $this->order = array('commands.command_set' => 'asc');
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
        $this->db->select('commands.*, c1.command_set as parent_name');
        $this->db->from($this->table);
        $this->db->join('commands AS c1','c1.command_id = commands.prent_command','left');
 
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

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function parents_count(){
        $this->db->from('commands');
        return $this->db->count_all_results();
    }

    public function get_parents(){
        $search = $this->input->get('search');
        $page = $this->input->get('page');

        $this->db->select('command_id as id, command_set as text');
        $this->db->from('commands');

        if ( !empty( $search ) ) {
            $this->db->like('command_set', $search);
        }

        if ( !empty( $page ) ) {
            $resultCount = 10;
            $end = ($page - 1) * $resultCount;
            $start = $end + $resultCount;

            $this->db->limit($start, $end);
        }
        
        $query = $this->db->get();
        $query = $query->result_array();
        if ( $page == '1' ) {
            array_unshift($query , ['id' => -1, 'text' => lang('command_select'), 'selected' => 'selected', 'search' => '', 'hidden' => true]);
        }
        return $query;
    }

    public function add() {
        $this->set_rules();
        if ( $this->form_validation->run() ) {
            if ( $_FILES["file"]["error"] == 0 ) {
                
                $this->load->helper('telegram_helper');
                $file_id = get_file_id( $_FILES["file"]["tmp_name"], $_FILES["file"]["name"],  $this->session->userdata('manager_telegram') );

                if ( $file_id == FALSE ) {
                    $this->output->set_content_type('application/json')
                        ->set_status_header(400)
                        ->_display(json_encode([
                            'status' => false,
                            'messages' => [ lang('command_file_not_uploaded') ]
                        ])
                    );
                    exit(0);
                }
            }

            clear_cache([
                'command',
                'commands',
            ]);

            $this->db->insert('commands', [
                'command_set' => $this->input->post('name'),
                'command_message' => $this->input->post('message'),
                'prent_command' => !empty( $this->input->post('parent') ) ? $this->input->post('parent') : '0',
                'sort' => $this->input->post('sort'),
                'language' => $this->input->post('language'),
                'inline_keyboard' => !empty( $this->input->post('buttons') ) ? json_encode( $this->input->post('buttons') ) : '',
                'file' => isset( $file_id ) ? json_encode( $file_id ) : '',
                'chunk' => $this->input->post('chunk'),
                'first_command' => $this->input->post('first_command'),
                'function' => $this->input->post('function'),
            ]);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'reset' => true,
                    'messages' => [
                        'addedd' => lang('command_successfully_added')
                    ],
                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('bot/commands')."' }, 1000);}"
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

    public function edit( $id, $data ){
        $this->set_rules( ( empty( $data['file'] ) ) );
        if ( $this->form_validation->run() ) {
            if ( $_FILES["file"]["error"] == 0 ) {
                
                $this->load->helper('telegram_helper');
                $file_id = get_file_id( $_FILES["file"]["tmp_name"], $_FILES["file"]["name"],  $this->session->userdata('manager_telegram') );

                if ( $file_id == FALSE ) {
                    $this->output->set_content_type('application/json')
                        ->set_status_header(400)
                        ->_display(json_encode([
                            'status' => false,
                            'messages' => [ lang('command_file_not_uploaded') ]
                        ])
                    );
                    exit(0);
                }
            }

            clear_cache([
                'command',
                'commands',
            ]);

            $this->db->update('commands', [
                'command_set' => $this->input->post('name'),
                'command_message' => $this->input->post('message'),
                'prent_command' => !empty( $this->input->post('parent') ) ? $this->input->post('parent') : '0',
                'sort' => $this->input->post('sort'),
                'language' => $this->input->post('language'),
                'inline_keyboard' => !empty( $this->input->post('buttons') ) ? json_encode( $this->input->post('buttons') ) : '',
                'file' => isset( $file_id ) ? json_encode( $file_id ) : $data['file'],
                'chunk' => $this->input->post('chunk'),
                'first_command' => $this->input->post('first_command'),
                'function' => $this->input->post('function'),
            ], ['command_id' => $id]);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'reset' => false,
                    'messages' => [
                        'addedd' => lang('command_successfully_edited')
                    ],
                    '_callback' => "function(){setTimeout( ()=> { window.location.href = '".base_url('bot/commands')."' }, 1000);}"
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

    public function set_rules($check_message = true){

        $this->form_validation->set_rules('name', lang('command_command'), 'required|min_length[3]|max_length[200]');
        $this->form_validation->set_rules('first_command', lang('command_first'), 'required|in_list[0,1]');
        $this->form_validation->set_rules('chunk', lang('command_chunk'), 'required|in_list[1,2,3,4,5,6]');
        $this->form_validation->set_rules('sort', lang('command_sort'), 'required|is_natural_no_zero');

        $this->form_validation->set_rules('function', lang('command_function'), [
            ['check_function', [$this, 'check_function']]
        ], ['check_function' => lang('command_function_not_found')]);

        $this->form_validation->set_rules('parent', lang('command_parent'), [
            ['check_parent', [$this, 'check_parent']]
        ], ['check_parent' => lang('command_parent_not_found')]);

        $this->form_validation->set_rules('language', lang('command_language'), [
            ['check_language', [$this, 'check_language']]
        ], ['check_language' => lang('command_language_not_found')]);

        if ( $check_message ) {
            $this->form_validation->set_rules('message', lang('command_message'), [
                ['check_message', [$this, 'check_message']]
            ], ['check_message' => lang('command_message_field_cannot_left_blank')]);    
        }

        $buttons = $this->input->post('buttons');
        
        if ( !empty( $buttons ) ) {
            $error = "";
            foreach ($buttons as $button) {
                if ( empty( $button['name'] ) ){
                    $error = lang('command_button_name_not_entered');
                    break;
                }
                if ( empty( $button['value'] ) ){
                    $error = lang('command_button_value_not_entered');
                    break;
                }
                if ( empty( $button['type'] ) ) {
                    $error = lang('command_button_type_not_selected');
                    break;
                }
                if ( !empty( $button['type'] ) && !in_array($button['type'], ['url', 'callback', 'webapp', 'switch_inline_query']) ) {
                    $error = lang('command_button_type_not_matched');
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

    public function check_language(){
        $lang = $this->input->post('language');

        $languages = getLanguages(TRUE);
        foreach ($languages as $k => $v) {
            if ( $k == $lang ) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function check_function(){
        $fn = $this->input->post('function');
        if ( empty( $fn ) ) {
            return TRUE;
        }
        if ( ! @file_exists( APPPATH . 'modules/' . $fn . 'models/Hook_model.php' ) ) {
            return TRUE;
        }

        return FALSE;
    }

    public function check_message(){
        $message = $this->input->post('message');
        
        if($_FILES["file"]["error"] == 0) {
            return TRUE;
        }

        if ( !empty( $message ) ) {
            return TRUE;
        }

        return FALSE;
    }

    public function check_parent(){
        $parent = $this->input->post('parent');
        
        if ( empty($parent) || $parent == "-1" ){
            return TRUE;
        }

        $query = $this->db->get_where('commands', [ 'command_id' => $parent ]);

        if ( $query->num_rows() > 0 ) {
            return TRUE;
        }

        return FALSE; 
    }

    public function update_field(){
        $this->form_validation->set_rules('name', lang('command_command'), 'required|min_length[3]|max_length[200]');
        $this->form_validation->set_rules('value', lang('command_first'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('pk', lang('command_chunk'), 'required|is_natural_no_zero');

        if ( !$this->db->field_exists($this->input->post('name'), 'commands')) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => false,
                    'messages' => [lang('command_field_not_exists')]
                ])
            );
        }

        $query = $this->db->get_where('commands', [
            'command_id' => $this->input->post('pk')
        ]);

        if ( $query->num_rows() > 0 ) {
            $this->db->update('commands', [
                $this->input->post('name') => $this->input->post('value')
            ],[
                'command_id' => $this->input->post('pk')
            ]);

            clear_cache([
                'command',
                'commands',
            ]);

            $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'messages' => [lang('command_data_successfully_updated')]
                ])
            );
        }else{
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => false,
                    'messages' => [lang('command_command_not_exists')]
                ])
            );
        }
    }

    public function recursive_remove( $command_id, $all_commands = [] ){
        if( ! is_array( $command_id ) ) {           
            $this->db->where('prent_command', $command_id);
            $all_commands[]=$command_id;
        }else{           
            $this->db->where_in('prent_command', $command_id);
        }

        $get_commands= $this->db->get('commands');

        if( $get_commands->num_rows() > 0 ) {
            $commands_vales = $get_commands->result(); 
            $new_subcommand = [];
            foreach($commands_vales as $cate_val) {
                $command_id = $cate_val->command_id;
                array_push( $new_subcommand, $command_id );
            }

            $all_commands = array_merge( $all_commands, $new_subcommand );
            if( count( $new_subcommand ) > 0 ) {
                $this->recursive_remove($new_subcommand, $all_commands);
            }
        }
        
        $this->db->where_in('command_id', $all_commands)->delete('commands');
        return TRUE;
    }
}

/* End of file Commands_model.php */
/* Location: ./application/models/bot/Commands_model.php */