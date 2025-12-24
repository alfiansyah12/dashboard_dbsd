<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    protected function require_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
            exit;
        }
    }

    /**
     * @param string|array $roles  contoh: 'admin' atau ['admin','atasan']
     */
    protected function require_role($roles)
    {
        $this->require_login();

        $userRole = (string) $this->session->userdata('role');
        $roles    = is_array($roles) ? $roles : [$roles];

        if (!in_array($userRole, $roles, true)) {
            show_error('Akses ditolak', 403);
            exit;
        }
    }
}
