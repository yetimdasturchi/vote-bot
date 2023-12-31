<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Start_model extends CI_Model {

	public $module_name;
	public $args;
	public $config = [];

	public function __construct(){
		parent::__construct();
	}

	public function load( $args = [] ){
		$this->config = $this->module->load_config( $this->module_name );
		$this->load->register_views( $this->module_name );
		$this->lang->load_module($this->module_name, 'module');
		
		if ( !empty( $this->args[0] ) && method_exists( $this, $this->args[0] ) ) {
			$method = $this->args[0];
		}else{
			$method = 'settings';
		}

		$this->$method();
	}

	public function contact(){
		$this->load->module_model($this->module_name, 'chat');
		$this->module_chat->module_name = $this->module_name;
		$this->module_chat->args = $this->args;
		$this->lang->load_module($this->module_name, 'chat');
		$this->module_chat->index();
	}

	public function datum(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			if ( !empty( $this->args[1] ) && $this->args[1] == 'clear' ) {
				if ( $this->config['installed'] ) {
					$this->db->truncate( $this->module_name );
					return $this->output
		                ->set_content_type('application/json')
		                ->set_status_header(200)
		                ->set_output(json_encode([
		                    'status' => true,
		                    'messages' => [ lang('module_data_cleared_successfully') ],
		                    '_callback' => 'function(){ setTimeout(() => { location.reload() }, 3000); }'
		                ])
	            	);
				}else{
					return $this->output
		                ->set_content_type('application/json')
		                ->set_status_header(400)
		                ->set_output(json_encode([
		                    'status' => false,
		                    'messages' => [ lang('module_not_possible_clear_data') ],
		                ])
	            	);
				}
				
			}

			return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => false,
                    'messages' => [ lang('module_undefined_action') ],
                    '_callback' => 'function(){ setTimeout(() => { location.reload() }, 1500); }'
                ])
            );
		}

		$this->module->load_index([
			'index_title' => lang('module_name'),
			'content' => 'module_main',
			'content_data' => [
				'content' => 'module_datum',
				'content_data' => $this->config,
				'module_name' => $this->module_name,
				'section' => lang('module_datum')
			]
		]);
	}

	public function installer(){
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			if ( !empty( $this->args[1] ) ) {
				$this->load->module_model($this->module_name, 'installer');
				$this->module_installer->module_name = $this->module_name;
				if ( $this->args[1] == 'install' ) {
					if ( $this->config['installed'] ) {
						return $this->output
			                ->set_content_type('application/json')
			                ->set_status_header(400)
			                ->set_output(json_encode([
			                    'status' => false,
			                    'messages' => [ lang('module_already_installed') ]
			                ])
			            );
					}else{

						$this->module_installer->install();
						$this->config['installed'] = true;
						$this->module->save_config($this->module_name, $this->config);

						return $this->output
			                ->set_content_type('application/json')
			                ->set_status_header(200)
			                ->set_output(json_encode([
			                    'status' => true,
			                    'messages' => [ lang('module_succesfully_installed') ],
			                    '_callback' => 'function(){ setTimeout(() => { window.location.href = window.location.href }, 3000); }'
			                ])
			            );
					}
				}elseif ( $this->args[1] == 'uninstall' ) {
					if ( $this->config['installed'] ) {
						$this->module_installer->uninstall();
						$this->config['installed'] = false;
						$this->module->save_config($this->module_name, $this->config);

						return $this->output
			                ->set_content_type('application/json')
			                ->set_status_header(200)
			                ->set_output(json_encode([
			                    'status' => true,
			                    'messages' => [ lang('module_succesfully_uninstalled') ],
			                    '_callback' => 'function(){ setTimeout(() => { window.location.href = window.location.href }, 3000); }'
			                ])
			            );
					}else{
						return $this->output
			                ->set_content_type('application/json')
			                ->set_status_header(400)
			                ->set_output(json_encode([
			                    'status' => false,
			                    'messages' => [ lang('module_already_uninstalled') ]
			                ])
			            );
					}
				}
			}
			

			return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'status' => false,
                    'messages' => [ lang('module_undefined_action') ],
                    '_callback' => 'function(){ setTimeout(() => { location.reload() }, 1500); }'
                ])
            );
		}

		$this->module->load_index([
			'index_title' => lang('module_name'),
			'content' => 'module_main',
			'content_data' => [
				'content' => 'module_installer',
				'content_data' => $this->config,
				'module_name' => $this->module_name,
				'section' => lang('module_installer')
			]
		]);
	}

	private function settings(){
		$this->lang->load_module($this->module_name, 'settings');

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			return $this->save_settings();
		}
		
		$settings = $this->module->load_settings( $this->module_name, ['min' => 30, 'max' => 2000] );

		$this->module->load_index([
			'index_title' => lang('module_name'),
			'content' => 'module_main',
			'content_data' => [
				'content' => 'module_settings',
				'content_data' => $settings,
				'module_name' => $this->module_name,
				'section' => lang('module_settings')
			]
		]);
	}

	public function save_settings(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('min', lang('settings_min_symbols'), 'required|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to['.$this->input->post('max').']');
		$this->form_validation->set_rules('max', lang('settings_man_symbols'), 'required|is_natural_no_zero|greater_than_equal_to['.$this->input->post('min').']|less_than_equal_to[2000]');

		if ( $this->form_validation->run() ) {
			$this->module->save_settings($this->module_name, [
				'min' => intval($this->input->post('min')),
				'max' => intval($this->input->post('max'))
			]);

			return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => true,
                    'messages' => [lang('settings_successfully_saved')]
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
}

/* End of file Start_model.php */
/* Location: ./application/modules/functions/contact/models/Start_model.php */