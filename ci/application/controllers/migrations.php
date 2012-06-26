<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migrations extends CI_Controller {

    /**
     * Upgrades your database to the latest migration
     */
    public function index()
    {
        $this->load->library('migration');

        if ( ! $this->migration->latest())
        {
            show_error($this->migration->error_string());
            return;
        }

        echo "Success!";
    }
}

/* End of file migrations.php */
/* Location: ./application/controllers/migrations.php */