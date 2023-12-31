<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commands extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('bot/Commands_model','commands');
		$this->load->model('Module_model','module');
		$this->config->load('command_functions');

		if ($this->auth->checkLogged() == FALSE) {
        	redirect( base_url('login') );
		}

		$this->lang->pack([
			'index',
			'commands',
			'dropify'
		]);

		$this->auth->check_acces('telegram_bot.view');
	}

	public function index() {
		$this->load->view('manage/index', [
			'index_title' => lang('index_commands'),
			'load_css' => [
				base_url('assets/libs/datatables/dataTables.bootstrap4.min.css'),
				base_url('assets/libs/datatables/buttons.bootstrap4.min.css'),
				base_url('assets/libs/datatables/responsive.bootstrap4.min.css'),
				base_url('assets/libs/editable/editable.css'),
			],
			'load_js' => [
				base_url('assets/libs/editable/index.js'),
				base_url('assets/libs/moment/moment.min.js'),
				base_url('assets/libs/datatables/jquery.dataTables.min.js'),
				base_url('assets/libs/datatables/dataTables.bootstrap4.min.js'),
				base_url('assets/libs/datatables/dataTables.buttons.min.js'),
				base_url('assets/libs/datatables/buttons.bootstrap4.min.js'),
				base_url('assets/libs/datatables/jszip.min.js'),
				base_url('assets/libs/datatables/pdfmake.min.js'),
				base_url('assets/libs/datatables/vfs_fonts.js'),
				base_url('assets/libs/datatables/buttons.html5.min.js'),
				base_url('assets/libs/datatables/buttons.print.min.js'),
				base_url('assets/libs/datatables/buttons.colVis.min.js'),
				base_url('assets/libs/datatables/dataTables.responsive.min.js'),
				base_url('assets/libs/datatables/responsive.bootstrap4.min.js'),
				base_url('assets/js/dtable.js'),
			],
			'content' => 'bot/commands'
		]);
	}

	public function add(){
		$this->auth->check_acces('telegram_bot.add');

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->commands->add();
		}

		$this->load->view('manage/index', [
			'load_css' => [
				base_url('assets/libs/dropify/dropify.min.css'),
				base_url('assets/libs/select2/select2.min.css')
			],
			'load_js' => [
				base_url('assets/libs/jquery-ui/jquery-ui.js'),
				base_url('assets/libs/jquery-ui/jquery.ui.touch-punch.js'),
				base_url('assets/libs/select2/select2.min.js'),
				base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
				base_url('assets/libs/dropify/dropify.min.js'),
				base_url('assets/libs/repeater/jquery.repeater.min.js'),
				base_url('assets/libs/repeater/form-repeater.int.js')
			],
			'index_title' => lang('index_add'),
			'content' => 'bot/commands_add'
		]);
	}

	public function edit( $id = '' ){
		$this->auth->check_acces('telegram_bot.edit');

		if ( !empty( $id ) ) {
			
			$this->db->select('commands.*, c1.command_set as parent_name');
        	$this->db->from('commands');
        	$this->db->join('commands AS c1','c1.command_id = commands.prent_command','left');
        	$this->db->where('commands.command_id', $id);

			$query = $this->db->get();

			if ( $query->num_rows() > 0 ) {
				$query = $query->row_array();

				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					return $this->commands->edit( $id, $query );
				}

				$this->load->view('manage/index', [
					'load_css' => [
						base_url('assets/libs/dropify/dropify.min.css'),
						base_url('assets/libs/select2/select2.min.css')
					],
					'load_js' => [
						base_url('assets/libs/jquery-ui/jquery-ui.js'),
						base_url('assets/libs/jquery-ui/jquery.ui.touch-punch.js'),
						base_url('assets/libs/select2/select2.min.js'),
						base_url('assets/libs/select2/'.getDefaultLanguage().'.js'),
						base_url('assets/libs/dropify/dropify.min.js'),
						base_url('assets/libs/repeater/jquery.repeater.min.js'),
						base_url('assets/libs/repeater/form-repeater.int.js')
					],
					'index_title' => lang('index_edit'),
					'content' => 'bot/commands_edit',
					'content_data' => $query
				]);
			}else{
				$this->session->set_flashdata('toast_error', lang('command_not_found'));
				redirect(base_url('bot/commands'));
			}
		}else{
			$this->session->set_flashdata('toast_error', lang('command_no_id_specified'));
			redirect(base_url('bot/commands'));
		}
	}

	public function remove($id=''){
		$this->auth->check_acces('telegram_bot.delete');

		if ( !empty( $id ) ) {
			
			$query = $this->db->get_where('commands', ['command_id' => $id]);

			if ( $query->num_rows() > 0 ) {
				if ($this->input->server('REQUEST_METHOD') === 'POST') {
					
					$this->commands->recursive_remove( $id );

					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(200)
				    	->set_output(json_encode([
				        	'status' => true,
				        	'reset' => false,
				        	'messages' => [
				        		'removed' => lang('command_successfully_removed')
				        	],
				        	'_callback' => "function(){\$dtables['commands'].ajax.reload(null, false);}"
				    	])
				    );
				}else{
					return $this->output
				    	->set_content_type('application/json')
				    	->set_status_header(405)
				    	->set_output(json_encode([
				        	'status' => false,
				        	'messages' => [
				        		lang('managers_method_not_allowed')
				        	]
				    	])
				    );
				}
			}else{
				$this->session->set_flashdata('toast_error', lang('command_not_found'));
				redirect(base_url('bot/commands'));
			}
		}else{
			$this->session->set_flashdata('toast_error', lang('command_no_id_specified'));
			redirect(base_url('bot/commands'));
		}
	}

	public function update_field(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->commands->update_field();
		}else{
			return $this->output
	    		->set_content_type('application/json')
	    		->set_status_header(405)
	    		->set_output(json_encode([
	    			'status' => false,
                    'messages' => [lang('command_method_not_allowed')]
	    		]));
		}
	}

	public function parent(){
		$output = $this->commands->get_parents();
		return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
	}

	public function getlist(){
		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			return $this->output
		    	->set_content_type('application/json')
		    	->set_status_header(405)
		    	->set_output(json_encode(['status' => false]));
		}
        $memData = $this->commands->getRows( $this->input->post() );
        $command_functions = config_item('command_functions');
        
        $language = getDefaultLanguage();

        $that = $this;                                                  
        $memData = array_map(function($x) use ($command_functions, $language, &$that){
        	$x['command_function'] = $x['function'];
			unset($x['function']);


			if ( @file_exists( APPPATH . 'modules/' . $x['command_function'] . '/models/Hook_model.php' ) ) {
				$module_config = $that->module->load_config( $x['command_function'] );
				$x['command_function'] =  $module_config['name'][$language]; 
			}else{
				$x['command_function'] = lang('command_not_specified');
			}

			$x['command_function'] = "<code>".$x['command_function']."</code>";

			$language = getLanguagedata( $x['language'] );
			if ( $language ) {
				$x['language'] = $language['name'];
			}else{
				$x['language'] = lang('command_not_specified');
			}

			$x['language'] = "<code>".$x['language']."</code>";

			if( empty( $x['parent_name'] ) ){
				$x['parent_name'] = "<code>".lang('command_not_specified')."</code>";	
			}

			if ( !empty( $x['file'] ) ) {
				$x['file'] = json_decode( $x['file'], TRUE );
				$x['type'] = lang( 'command_type_' . $x['file']['type'] );
			}else{
				$x['type'] = lang('command_type_text');
			}

			$x['type'] = "<code>{$x['type']}</code>";

			$x['sort'] = "<a href=\"javascript: void(0);\" data-pk=\"{$x['command_id']}\" class=\"inline-sort\">{$x['sort']}</a>";
			$x['chunk'] = "<a href=\"javascript: void(0);\" data-value=\"{$x['chunk']}\" data-pk=\"{$x['command_id']}\" class=\"inline-chunk\">{$x['chunk']}</a>";

        	$x['command_action'] = <<<EOL
            <div class="btn-group" role="group">
                <a class="btn btn-outline-info" href="${!${''} = base_url('bot/commands/edit/' . $x['command_id']) }"><i class="bx bx-edit"></i></a>
                <button class="btn btn-outline-danger" data-ajax-button="${!${''} = base_url('bot/commands/remove/' . $x['command_id']) }" data-message="${!${''} = lang('command_do_you_really_remove') }"><i class="bx bx-trash"></i></button>
            </div>
            EOL;

        	return $x;
        }, $memData);
        
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->commands->countAll(),
            "recordsFiltered" => $this->commands->countFiltered( $this->input->post() ),
            "data" => $memData,
        );
        
        return $this->output
	    	->set_content_type('application/json')
	    	->set_status_header(200)
	    	->set_output(json_encode($output));
    }

}

/* End of file Commands.php */
/* Location: ./application/controllers/bot/Commands.php */