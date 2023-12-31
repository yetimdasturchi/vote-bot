<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Installer_model extends CI_Model {
	public $module_name;

	public function __construct(){
		parent::__construct();
		$this->load->dbforge();
	}

	public function install(){

		$this->module->add_route( $this->module_name, [
			$this->module_name => $this->module_name,
			$this->module_name . '/get_chats' => $this->module_name . '/get_chats',
			$this->module_name . '/get_chat_window/(:num)' => $this->module_name . '/get_chat_window/$1',
			$this->module_name . '/(:num)' => $this->module_name . '/$1',
		]);

		$this->module->add_menus( 'navbar', $this->module_name, [
			[
				'model' => 'navbar'
			]
		]);

		$this->create_table();
		return TRUE;
	}

	public function uninstall(){
		$this->module->remove_route( $this->module_name );
		$this->module->remove_menus( 'navbar', $this->module_name );

		$this->drop_table();

		return TRUE;
	}

	private function drop_table(){
		$this->dbforge->drop_table( $this->module_name, TRUE );
	}

	private function create_table(){
		$fields = [
			$this->module_name . '_id' => [
				'type' => 'BIGINT',
				'constraint' => 22,
				'unsigned' => TRUE,
				'auto_increment' => TRUE

			],
			$this->module_name . '_from' => [
				'type' => 'BIGINT',
				'constraint' => 22
			],
			$this->module_name . '_to' => [
				'type' => 'BIGINT',
				'constraint' => 22
			],
			$this->module_name . '_send' => [
				'type' => 'BIGINT',
				'constraint' => 22
			],
			$this->module_name . '_read' => [
				'type' => 'BIGINT',
				'constraint' => 22
			],
			$this->module_name . '_message' => [
				'type' => 'TEXT',
				'null' => TRUE
			],
			$this->module_name . '_file' => [
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE
			],
		];

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key( $this->module_name . '_id', TRUE );
		$this->dbforge->create_table( $this->module_name, TRUE, [
			'ENGINE' => 'InnoDB'
		]);
	}
}

/* End of file Install_model.php */
/* Location: ./application/modules/contact/models/Install_model.php */