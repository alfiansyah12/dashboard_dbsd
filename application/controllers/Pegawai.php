<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_role('pegawai');

        // load model yang dipakai di controller ini
        $this->load->model('Pegawai_tugas_model');
        $this->load->model('Tugas_model');
        $this->load->model('Goals_model');
    }

    private function hasActiveTask()
    {
        $this->db->where('user_id', (int)$this->session->userdata('user_id'));
        $this->db->where('status', 'on going');
        return $this->db->count_all_results('pegawai_tugas') > 0;
    }

    public function index()
    {
        redirect('pegawai/dashboard_list');
    }

    public function pilih_tugas()
    {
        $departemen_id = (int)$this->session->userdata('departemen_id');

        $data = [];
        $data['tugas'] = $this->Tugas_model->getByDepartemen($departemen_id);
        $data['has_active_task'] = $this->hasActiveTask();

        $this->load->view('pegawai/layout/header', ['title' => 'Pilih Tugas']);
        $this->load->view('pegawai/layout/sidebar', $data);
        $this->load->view('pegawai/pilih_tugas', $data);
        $this->load->view('pegawai/layout/footer');
    }

    public function ambil_tugas()
    {
        $tugas_id = (int)$this->input->post('tugas_id', true);
        $user_id  = (int)$this->session->userdata('user_id');

        if (!$tugas_id) {
            redirect('pegawai/pilih_tugas');
            return;
        }

        // CEK: Apakah pegawai sudah mengambil tugas yang sama dengan status 'on going'
        $check = $this->db->get_where('pegawai_tugas', [
            'user_id'  => $user_id,
            'tugas_id' => $tugas_id,
            'status'   => 'on going'
        ])->row();

        if ($check) {
            // Tampilkan alert jika tugas yang sama masih aktif
            $this->session->set_flashdata('error', 'Anda sudah mengambil tugas ini dan statusnya masih On Going.');
            redirect('pegawai/pilih_tugas');
            return;
        }

        // Jika belum ada, masukkan tugas baru
        $this->db->insert('pegawai_tugas', [
            'user_id'       => $user_id,
            'tugas_id'      => $tugas_id,
            'tanggal_ambil' => date('Y-m-d'),
            'status'        => 'on going',
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        $id = (int)$this->db->insert_id();
        $this->session->set_flashdata('success', 'Tugas baru berhasil diambil.');
        redirect('pegawai/dashboard/' . $id);
    }

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

        $goals = $this->Goals_model->getByPegawaiTugas($pegawai_tugas_id);

        $data = [];
        $data['row'] = $row;
        $data['input'] = $input;
        $data['goals'] = $goals;
        $data['has_active_task'] = $this->hasActiveTask();

        $this->load->view('pegawai/layout/header', ['title' => 'Input Aktivitas']);
        $this->load->view('pegawai/layout/sidebar', $data);
        $this->load->view('pegawai/dashboard_form', $data);
        $this->load->view('pegawai/layout/footer');
    }

    public function dashboard_store()
    {
        $pegawai_tugas_id = (int)$this->input->post('pegawai_tugas_id', true);
        $status           = $this->input->post('status', true);

        if (!$pegawai_tugas_id) {
            $this->session->set_flashdata('error', 'Pegawai tugas tidak valid.');
            redirect('pegawai/dashboard_list');
            return;
        }

        $row = $this->Pegawai_tugas_model->getById($pegawai_tugas_id);
        if (!$row || (int)$row->user_id !== (int)$this->session->userdata('user_id')) {
            show_error('Data tidak ditemukan', 404);
        }

        $now = date('Y-m-d H:i:s');

        $payload = [
            'pegawai_tugas_id' => $pegawai_tugas_id,
            'activity'         => $this->input->post('activity', true),
            'pending_matters'  => $this->input->post('pending_matters', true),
            'close_the_path'   => $this->input->post('close_the_path', true),
            'progress_nilai'   => (int)$this->input->post('progress_nilai', true),
            'updated_at'       => $now,
        ];

        $exists = $this->db->get_where('dashboard_input', ['pegawai_tugas_id' => $pegawai_tugas_id])->row();
        if ($exists) {
            $this->db->where('pegawai_tugas_id', $pegawai_tugas_id)->update('dashboard_input', $payload);
        } else {
            $payload['created_at'] = $now;
            $this->db->insert('dashboard_input', $payload);
        }

        $goals_text = trim((string)$this->input->post('goals', true));
        if ($goals_text !== '') {
            $this->Goals_model->upsert($pegawai_tugas_id, $goals_text);
        }

        $allowed = ['on going', 'done', 'terminated'];
        if (in_array($status, $allowed, true)) {
            $this->Pegawai_tugas_model->updateStatus($pegawai_tugas_id, $status);
        }

        $this->session->set_flashdata('success', 'Data berhasil tersimpan.');
        redirect('pegawai/dashboard_list#top');
    }
    public function dashboard_list()
    {
        $user_id = (int)$this->session->userdata('user_id');

        $this->db->select('
        pt.id as pegawai_tugas_id,
        pt.tanggal_ambil,
        pt.status,
        pt.target_nilai,
        pt.deadline_tanggal,
        t.nama_tugas,
        di.activity,
        di.progress_nilai,
        di.updated_at,
        di.created_at
    ');
        $this->db->from('pegawai_tugas pt');
        $this->db->join('tugas t', 't.id = pt.tugas_id');
        $this->db->join('dashboard_input di', 'di.pegawai_tugas_id = pt.id', 'left');
        $this->db->where('pt.user_id', $user_id);
        $this->db->order_by('COALESCE(di.updated_at, di.created_at, pt.created_at)', 'DESC', false);

        $data['rows'] = $this->db->get()->result();
        $data['has_active_task'] = $this->hasActiveTask();

        $this->load->view('pegawai/layout/header', ['title' => 'Dashboard Pegawai']);
        $this->load->view('pegawai/layout/sidebar', $data);
        $this->load->view('pegawai/dashboard_list', $data);
        $this->load->view('pegawai/layout/footer');
    }
}
