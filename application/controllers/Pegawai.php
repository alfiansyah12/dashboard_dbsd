<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta'); // GMT+7

        $this->load->library('session');
        $this->load->model('Tugas_model');
        $this->load->model('Pegawai_tugas_model');
        $this->load->model('Goals_model');

        if (!$this->session->userdata('logged_in')) redirect('auth');
        if ($this->session->userdata('role') != 'pegawai') show_error('Akses ditolak', 403);

        if (!$this->session->userdata('divisi_id')) {
            show_error('Divisi belum ditentukan. Hubungi admin.', 403);
        }
    }

    private function hasActiveTask()
    {
        $this->db->where('user_id', (int)$this->session->userdata('user_id'));
        $this->db->where('status', 'on going');
        return $this->db->count_all_results('pegawai_tugas') > 0;
    }

    public function index()
    {
        $active = $this->Pegawai_tugas_model->getActiveByUser((int)$this->session->userdata('user_id'));
        if ($active) redirect('pegawai/dashboard/'.$active->id);

        redirect('pegawai/pilih_tugas');
    }

    public function pilih_tugas()
    {
        $divisi_id = (int)$this->session->userdata('divisi_id');

        $data = [];
        $data['tugas'] = $this->Tugas_model->getByDivisi($divisi_id);
        $data['has_active_task'] = $this->hasActiveTask();

        $this->load->view('pegawai/layout/header', ['title' => 'Pilih Tugas']);
        $this->load->view('pegawai/layout/sidebar', $data); // ✅ kirim data ke sidebar
        $this->load->view('pegawai/pilih_tugas', $data);
        $this->load->view('pegawai/layout/footer');
    }

    public function ambil_tugas()
    {
        $tugas_id = (int)$this->input->post('tugas_id', true);
        if (!$tugas_id) redirect('pegawai/pilih_tugas');

        $active = $this->Pegawai_tugas_model->getActiveByUser((int)$this->session->userdata('user_id'));
        if ($active) redirect('pegawai/dashboard/'.$active->id);

        $this->db->insert('pegawai_tugas', [
            'user_id'       => (int)$this->session->userdata('user_id'),
            'tugas_id'      => $tugas_id,
            'tanggal_ambil' => date('Y-m-d'),
            'status'        => 'on going',
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        $id = $this->db->insert_id();
        redirect('pegawai/dashboard/'.$id);
    }

    // =============================
    // FORM INPUT (pegawai/dashboard/{id})
    // =============================
    public function dashboard($pegawai_tugas_id)
    {
        $pegawai_tugas_id = (int)$pegawai_tugas_id;

        $row = $this->Pegawai_tugas_model->getById($pegawai_tugas_id);
        if (!$row || (int)$row->user_id !== (int)$this->session->userdata('user_id')) {
            show_error('Data tidak ditemukan', 404);
        }

        $input = $this->db->get_where('dashboard_input', [
            'pegawai_tugas_id' => $pegawai_tugas_id
        ])->row();

        // ✅ goals per tugas
        $goals = $this->Goals_model->getByPegawaiTugas($pegawai_tugas_id);

        $data = [];
        $data['row'] = $row;
        $data['input'] = $input;
        $data['goals'] = $goals;
        $data['has_active_task'] = $this->hasActiveTask();

        $this->load->view('pegawai/layout/header', ['title' => 'Input Aktivitas']);
        $this->load->view('pegawai/layout/sidebar', $data); // ✅ kirim data ke sidebar
        $this->load->view('pegawai/dashboard_form', $data);
        $this->load->view('pegawai/layout/footer');
    }

    public function dashboard_store()
    {
        $pegawai_tugas_id = (int)$this->input->post('pegawai_tugas_id', true);
        $status           = $this->input->post('status', true);

        if (!$pegawai_tugas_id) {
            $this->session->set_flashdata('error', 'Pegawai tugas tidak valid.');
            redirect('pegawai/dashboard');
            return;
        }

        $row = $this->Pegawai_tugas_model->getById($pegawai_tugas_id);
        if (!$row || (int)$row->user_id !== (int)$this->session->userdata('user_id')) {
            show_error('Data tidak ditemukan', 404);
        }

        $now = date('Y-m-d H:i:s');

        // =============================
        // SIMPAN DASHBOARD INPUT
        // =============================
        $payload = [
            'pegawai_tugas_id' => $pegawai_tugas_id,
            'activity'         => $this->input->post('activity', true),
            'pending_matters'  => $this->input->post('pending_matters', true),
            'close_the_path'   => $this->input->post('close_the_path', true),
            'updated_at'       => $now,
        ];

        $exists = $this->db->get_where('dashboard_input', ['pegawai_tugas_id' => $pegawai_tugas_id])->row();
        if ($exists) {
            $this->db->where('pegawai_tugas_id', $pegawai_tugas_id)->update('dashboard_input', $payload);
        } else {
            $payload['created_at'] = $now;
            $this->db->insert('dashboard_input', $payload);
        }

        // =============================
        // SIMPAN GOALS (baru)
        // =============================
        $goals_text = trim((string)$this->input->post('goals', true));
        if ($goals_text !== '') {
            $this->Goals_model->upsert($pegawai_tugas_id, $goals_text);
        }

        // update status pegawai_tugas
        $allowed = ['on going','done','terminated'];
        if (in_array($status, $allowed, true)) {
            $this->Pegawai_tugas_model->updateStatus($pegawai_tugas_id, $status);
        }

        $this->session->set_flashdata('success', 'Data berhasil tersimpan.');

        // ✅ setelah save kembali ke LIST dashboard
        redirect('pegawai/dashboard#top');
    }

    // =============================
    // LIST DASHBOARD (pegawai/dashboard)
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
            di.close_the_path,
            di.updated_at,
            di.created_at
        ');
        $this->db->from('pegawai_tugas pt');
        $this->db->join('tugas t', 't.id = pt.tugas_id');
        $this->db->join('dashboard_input di', 'di.pegawai_tugas_id = pt.id', 'left');
        $this->db->where('pt.user_id', $user_id);

        // paling baru muncul atas
        $this->db->order_by('COALESCE(di.updated_at, di.created_at, pt.created_at)', 'DESC', false);

        $data = [];
        $data['rows'] = $this->db->get()->result();
        $data['has_active_task'] = $this->hasActiveTask();

        $this->load->view('pegawai/layout/header', ['title' => 'Dashboard Pegawai']);
        $this->load->view('pegawai/layout/sidebar', $data); // ✅ kirim data ke sidebar
        $this->load->view('pegawai/dashboard_list', $data);
        $this->load->view('pegawai/layout/footer');
    }
}
