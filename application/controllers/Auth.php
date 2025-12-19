<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        
    }

    public function index()
    {
        $this->load->view('auth/login');
    }

    public function login()
    {
        $email    = $this->input->post('email', TRUE);
        $password = $this->input->post('password', TRUE);

        // CEK DATA POST
        if (!$email || !$password) {
            $this->session->set_flashdata('error', 'Email dan password wajib diisi');
            redirect('auth');
        }

        $user = $this->User_model->getByEmail($email);

        if ($user && password_verify($password, $user->password)) {

            // SET SESSION
            $this->session->set_userdata([
                'logged_in' => TRUE,
                'user_id'   => $user->id,
                'nama'      => $user->nama,
                'role'      => $user->role,
  'divisi_id' => $user->divisi_id,
            ]);

            // REDIRECT SESUAI ROLE
            switch ($user->role) {
                case 'admin':
                    redirect('admin');
                    break;
                case 'atasan':
                    redirect('atasan');
                    break;
                default:
                    redirect('pegawai');
            }

        } else {
            $this->session->set_flashdata('error','Email atau password salah');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
