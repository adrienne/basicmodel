<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Creates `users` table
 */
class Migration_Create_users_table extends CI_Migration {

    public function up()
    {

        $this->dbforge->add_field('id');
        $this->dbforge->add_field(array(
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ),

            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            )
        ));

        $this->dbforge->create_table('users');

    }

    public function down()
    {
        
        $this->dbforge->drop_table('users');

    }

}

/* End of file 001_create_users_table.php */
/* Location: ./application/migrations/001_create_users_table.php */