<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');
        $this->load->model('Divisi_model');
        $this->load->model('Tugas_model');


        // Proteksi login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Proteksi role
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak', 403);
        }
    }

   public function index()
{
    $data = [
        'total_user'   => $this->db->count_all('users'),
        'total_divisi' => $this->db->count_all('divisi'),
        'total_tugas'  => $this->db->count_all('tugas'),
    ];

    $this->load->view('admin/layout/header', ['title' => 'Dashboard Admin']);
    $this->load->view('admin/layout/sidebar');
    $this->load->view('admin/dashboard', $data);   // <- kirim $data
    $this->load->view('admin/layout/footer');
}

    public function user()
{
    // AMBIL USER + DIVISI
    $data['users']  = $this->User_model->getAllWithDivisi();
    $data['divisi'] = $this->Divisi_model->getAll();

    $this->load->view('admin/layout/header', ['title'=>'Kelola User']);
    $this->load->view('admin/layout/sidebar');
    $this->load->view('admin/user', $data);
    $this->load->view('admin/layout/footer');
}

public function user_store()
{
    $data = [
        'nama'     => $this->input->post('nama'),
        'email'    => $this->input->post('email'),
        'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        'role'     => $this->input->post('role')
    ];

    $this->User_model->insert($data);
    redirect('admin/user');
}
public function user_delete($id)
{
    $this->User_model->delete($id);
    redirect('admin/user');
}

public function divisi()
{
    $data['divisi'] = $this->Divisi_model->getAll();

    $this->load->view('admin/layout/header', ['title'=>'Kelola Divisi']);
    $this->load->view('admin/layout/sidebar');
    $this->load->view('admin/divisi', $data);
    $this->load->view('admin/layout/footer');
}

public function divisi_store()
{
    $data = [
        'nama_divisi' => $this->input->post('nama_divisi')
    ];

    $this->Divisi_model->insert($data);
    redirect('admin/divisi');
}

public function divisi_delete($id)
{
    $this->Divisi_model->delete($id);
    redirect('admin/divisi');
}



    public function tugas()
{
    $data['tugas']  = $this->Tugas_model->getAll();
    $data['divisi'] = $this->Divisi_model->getAll();

    $this->load->view('admin/layout/header', ['title'=>'Kelola Tugas']);
    $this->load->view('admin/layout/sidebar');
    $this->load->view('admin/tugas', $data);
    $this->load->view('admin/layout/footer');
}

public function tugas_store()
{
    $data = [
        'nama_tugas' => $this->input->post('nama_tugas'),
        'deskripsi'  => $this->input->post('deskripsi'),
        'divisi_id'  => $this->input->post('divisi_id')
    ];

    $this->Tugas_model->insert($data);
    redirect('admin/tugas');
}

public function tugas_delete($id)
{
    $this->Tugas_model->delete($id);
    redirect('admin/tugas');
}

public function assign_divisi()
{
    $this->db->where('id', $this->input->post('user_id'));
    $this->db->update('users', [
        'divisi_id' => $this->input->post('divisi_id')
    ]);

    redirect('admin/user');
}


}
