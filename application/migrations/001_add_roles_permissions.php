<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_roles_permissions extends CI_Migration {

	/**
	 * @var mixed
	 */
	private $dbforge;

	public function up()
	{
		// Create roles table
		$this->dbforge->add_field([
			'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE],
			'role_name' => ['type' => 'VARCHAR', 'constraint' => 50],
			'created_at' => ['type' => 'TIMESTAMP']
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('roles');

		// Create permissions table
		$this->dbforge->add_field([
			'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE],
			'permission_name' => ['type' => 'VARCHAR', 'constraint' => 50],
			'category_id' => ['type' => 'INT', 'constraint' => 11],
			'created_at' => ['type' => 'TIMESTAMP']
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('permissions');

		// Create permission_categories table
		$this->dbforge->add_field([
			'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE],
			'category_name' => ['type' => 'VARCHAR', 'constraint' => 50],
			'created_at' => ['type' => 'TIMESTAMP']
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('permission_categories');

		// Create role_permissions table
		$this->dbforge->add_field([
			'role_id' => ['type' => 'INT', 'constraint' => 11],
			'permission_id' => ['type' => 'INT', 'constraint' => 11]
		]);
		$this->dbforge->add_key(['role_id', 'permission_id'], TRUE);
		$this->dbforge->create_table('role_permissions');

		// Create users table
		$this->dbforge->add_field([
			'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE],
			'username' => ['type' => 'VARCHAR', 'constraint' => 50],
			'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => TRUE],
			'password' => ['type' => 'VARCHAR', 'constraint' => 255],
			'role_id' => ['type' => 'INT', 'constraint' => 11, 'null' => TRUE],
			'created_at' => ['type' => 'TIMESTAMP']
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_field('CONSTRAINT FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL');
		$this->dbforge->create_table('users');
	}

	public function down()
	{
		// Drop tables if we roll back the migration
		$this->dbforge->drop_table('users', TRUE);
		$this->dbforge->drop_table('role_permissions', TRUE);
		$this->dbforge->drop_table('permissions', TRUE);
		$this->dbforge->drop_table('permission_categories', TRUE);
		$this->dbforge->drop_table('roles', TRUE);
	}
}
