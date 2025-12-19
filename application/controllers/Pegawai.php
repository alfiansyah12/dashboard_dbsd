<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Tugas_model');
        $this->load->model('Pegawai_tugas_model');

        if (!$this->session->userdata('logged_in')) redirect('auth');

        // hanya pegawai
        if ($this->session->userdata('role') != 'pegawai') show_error('Akses ditolak', 403);

        // wajib ada divisi
        if (!$this->session->userdata('divisi_id')) {
            show_error('Divisi belum ditentukan. Hubungi admin.', 403);
        }
    }

    public function index()
    {
        // kalau sudah ada tugas aktif, masuk form input tugas aktif
        $active = $this->Pegawai_tugas_model->getActiveByUser($this->session->userdata('user_id'));
        if ($active) redirect('pegawai/input/'.$active->id);

        // kalau belum ada tugas aktif -> ke pilih tugas
        redirect('pegawai/pilih_tugas');
    }

    public function pilih_tugas()
{
    $divisi_id = $this->session->userdata('divisi_id');
    $data['tugas'] = $this->Tugas_model->getByDivisi($divisi_id);

    $this->load->view('pegawai/layout/header', ['title' => 'Pilih Tugas']);
    $this->load->view('pegawai/layout/sidebar');
    $this->load->view('pegawai/pilih_tugas', $data);
    $this->load->view('pegawai/layout/footer');
}


    public function ambil_tugas()
    {
        $tugas_id = (int)$this->input->post('tugas_id');
        if (!$tugas_id) redirect('pegawai/pilih_tugas');

        // cegah ambil tugas kalau masih ada on going
        $active = $this->Pegawai_tugas_model->getActiveByUser($this->session->userdata('user_id'));
        if ($active) redirect('pegawai/input/'.$active->id);

        $this->db->insert('pegawai_tugas', [
            'user_id'      => $this->session->userdata('user_id'),
            'tugas_id'     => $tugas_id,
            'tanggal_ambil'=> date('Y-m-d'),
            'status'       => 'on going',
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        $id = $this->db->insert_id();
        redirect('pegawai/input/'.$id);
    }

    // =============================
    // HALAMAN FORM INPUT (menu Input Data)
    // =============================
    public function dashboard($pegawai_tugas_id)
    {
        $row = $this->Pegawai_tugas_model->getById($pegawai_tugas_id);
        if (!$row || $row->user_id != $this->session->userdata('user_id')) show_error('Data tidak ditemukan', 404);

        $input = $this->db->get_where('dashboard_input', ['pegawai_tugas_id' => $pegawai_tugas_id])->row();

        $data['row'] = $row;
        $data['input'] = $input;

        // layout + sidebar
        $this->load->view('pegawai/layout/header', ['title' => 'Input Aktivitas']);
        $this->load->view('pegawai/layout/sidebar');
        $this->load->view('pegawai/dashboard_form', $data); // <-- FORM INPUT
        $this->load->view('pegawai/layout/footer');
    }

    public function dashboard_store()
    {
        $pegawai_tugas_id = (int)$this->input->post('pegawai_tugas_id');
        $status = $this->input->post('status');

        $row = $this->Pegawai_tugas_model->getById($pegawai_tugas_id);
        if (!$row || $row->user_id != $this->session->userdata('user_id')) show_error('Data tidak ditemukan', 404);

        $data = [
            'pegawai_tugas_id'  => $pegawai_tugas_id,
            'activity'          => $this->input->post('activity'),
            'pending_matters'   => $this->input->post('pending_matters'),
            'close_the_path'    => $this->input->post('close_the_path'),
        ];

        // upsert dashboard_input (1 tugas = 1 input)
        $exists = $this->db->get_where('dashboard_input', ['pegawai_tugas_id' => $pegawai_tugas_id])->row();
        if ($exists) {
            $this->db->where('pegawai_tugas_id', $pegawai_tugas_id)->update('dashboard_input', $data);
        } else {
            $this->db->insert('dashboard_input', $data);
        }

        // update status pegawai_tugas
        $allowed = ['on going','done','terminated'];
        if (in_array($status, $allowed, true)) {
            $this->Pegawai_tugas_model->updateStatus($pegawai_tugas_id, $status);
        }

        // pesan sukses
        $this->session->set_flashdata('success', 'Data berhasil tersimpan.');

        // setelah simpan, balik ke DASHBOARD LIST (lihat semua input)
        redirect('pegawai/dashboard');
    }

    // =============================
    // HALAMAN LIST INPUT (menu Dashboard)
    // =============================
    public function dashboard_list()
    {
        $user_id = (int)$this->session->userdata('user_id');

        $this->db->select('
            pt.id as pegawai_tugas_id,
            pt.tanggal_ambil,
            pt.status,
            t.nama_tugas,
            di.activity,
            di.pending_matters,
            di.close_the_path
        ');
        $this->db->from('pegawai_tugas pt');
        $this->db->join('tugas t', 't.id = pt.tugas_id');
        $this->db->join('dashboard_input di', 'di.pegawai_tugas_id = pt.id', 'left');
        $this->db->where('pt.user_id', $user_id);
        $this->db->order_by('pt.id', 'DESC');

        $data['rows'] = $this->db->get()->result();

        $this->load->view('pegawai/layout/header', ['title' => 'Dashboard Pegawai']);
        $this->load->view('pegawai/layout/sidebar');
        $this->load->view('pegawai/dashboard_list', $data); // <-- LIST
        $this->load->view('pegawai/layout/footer');
    }
}
