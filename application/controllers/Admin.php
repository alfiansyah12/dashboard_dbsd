<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {


    public function __construct()
{
    parent::__construct();

    $this->load->model('User_model');
    $this->load->model('Divisi_model');
    $this->load->model('Tugas_model');

    $this->require_role('admin'); // ✅ semua method admin otomatis aman
}


    /**
     * Render halaman admin (header + sidebar + content + footer)
     * NOTE: ini memanggil wrapper template
     */
    private function render($title, $content_view, $data = [])
    {
        $data['title']   = $title;
        $data['content'] = $content_view;

        // ✅ ganti ke wrapper yang ada / kamu buat
        // Buat file: application/views/admin/layout/template.php
        $this->load->view('admin/layout/template', $data);
    }

    public function index()
    {
        $data = [
            'total_user'   => $this->db->count_all('users'),
            'total_divisi' => $this->db->count_all('divisi'),
            'total_tugas'  => $this->db->count_all('tugas'),
        ];

        $this->render('Dashboard Admin', 'admin/dashboard', $data);
    }

    public function user()
    {
        $data['users']  = $this->User_model->getAllWithDivisi();
        $data['divisi'] = $this->Divisi_model->getAll();

        $this->render('Kelola User', 'admin/user', $data);
    }

    public function user_store()
    {
        $payload = [
            'nama'     => $this->input->post('nama', true),
            'email'    => $this->input->post('email', true),
            'password' => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
            'role'     => $this->input->post('role', true),
        ];

        $this->User_model->insert($payload);
        redirect('admin/user');
    }

    public function user_delete($id)
    {
        $this->User_model->delete((int)$id);
        redirect('admin/user');
    }

    public function divisi()
    {
        $data['divisi'] = $this->Divisi_model->getAll();
        $this->render('Kelola Divisi', 'admin/divisi', $data);
    }

    public function divisi_store()
    {
        $payload = ['nama_divisi' => $this->input->post('nama_divisi', true)];
        $this->Divisi_model->insert($payload);
        redirect('admin/divisi');
    }

    public function divisi_delete($id)
    {
        $this->Divisi_model->delete((int)$id);
        redirect('admin/divisi');
    }

    public function tugas()
    {
        $data['tugas']  = $this->Tugas_model->getAll();
        $data['divisi'] = $this->Divisi_model->getAll();

        $this->render('Kelola Tugas', 'admin/tugas', $data);
    }

    public function tugas_store()
    {
        $payload = [
            'nama_tugas' => $this->input->post('nama_tugas', true),
            'deskripsi'  => $this->input->post('deskripsi', true),
            'divisi_id'  => (int)$this->input->post('divisi_id', true),
        ];

        $this->Tugas_model->insert($payload);
        redirect('admin/tugas');
    }

    public function tugas_delete($id)
    {
        $this->Tugas_model->delete((int)$id);
        redirect('admin/tugas');
    }

    public function assign_divisi()
    {
        $this->db->where('id', (int)$this->input->post('user_id'));
        $this->db->update('users', [
            'divisi_id' => (int)$this->input->post('divisi_id')
        ]);

        redirect('admin/user');
    }

    // ===============================
    // TARGET & REALISASI (ADMIN)
    // ===============================
    public function target()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Atasan_target_model');

        $edit_id = (int)$this->input->get('edit_id', true);

        $data['targets'] = $this->Atasan_target_model->getAll();
        $data['edit']    = null;

        if ($edit_id) {
            $data['edit'] = $this->Atasan_target_model->getById($edit_id);
            if (!$data['edit']) {
                $this->session->set_flashdata('error', 'Data edit tidak ditemukan.');
                redirect('admin/target');
                return;
            }
        }

        // Chart data
        $labels = $targetSeries = $realisasiSeries = $feeSeries = $volSeries = [];

        foreach (($data['targets'] ?? []) as $t) {
            $labels[]          = date('Y-m', strtotime($t->periode));
            $targetSeries[]    = (int)$t->target;
            $realisasiSeries[] = (int)$t->realisasi;
            $feeSeries[]       = (int)($t->fee_base_income ?? 0);
            $volSeries[]       = (int)($t->volume_of_agent ?? 0);
        }

        $data['chart_labels']    = json_encode($labels);
        $data['chart_target']    = json_encode($targetSeries);
        $data['chart_realisasi'] = json_encode($realisasiSeries);
        $data['chart_fee']       = json_encode($feeSeries);
        $data['chart_vol']       = json_encode($volSeries);

        $this->render('Target & Realisasi', 'admin/target_index', $data);
    }

    public function target_store()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Atasan_target_model');

        $periode = $this->input->post('periode', true); // YYYY-MM
        if (!$periode) {
            $this->session->set_flashdata('error', 'Periode wajib diisi.');
            redirect('admin/target');
            return;
        }

        $now = date('Y-m-d H:i:s');

        $payload = [
            'periode'          => $periode . '-01',
            'target'           => (int)$this->input->post('target', true),
            'realisasi'        => (int)$this->input->post('realisasi', true),
            'fee_base_income'  => (int)$this->input->post('fee_base_income', true),
            'volume_of_agent'  => (int)$this->input->post('volume_of_agent', true),
            'catatan'          => $this->input->post('catatan', true),
            'created_by'       => (int)$this->session->userdata('user_id'),
            'created_at'       => $now,
            'updated_at'       => $now,
        ];

        $this->Atasan_target_model->insert($payload);
        $this->session->set_flashdata('success', 'Target/Realisasi tersimpan.');
        redirect('admin/target');
    }

    public function target_update($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Atasan_target_model');

        $id = (int)$id;
        if (!$this->Atasan_target_model->getById($id)) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('admin/target');
            return;
        }

        $periode = $this->input->post('periode', true);
        if (!$periode) {
            $this->session->set_flashdata('error', 'Periode wajib diisi.');
            redirect('admin/target?edit_id='.$id);
            return;
        }

        $now = date('Y-m-d H:i:s');

        $payload = [
            'periode'          => $periode . '-01',
            'target'           => (int)$this->input->post('target', true),
            'realisasi'        => (int)$this->input->post('realisasi', true),
            'fee_base_income'  => (int)$this->input->post('fee_base_income', true),
            'volume_of_agent'  => (int)$this->input->post('volume_of_agent', true),
            'catatan'          => $this->input->post('catatan', true),
            'updated_at'       => $now,
        ];

        $this->Atasan_target_model->updateById($id, $payload);
        $this->session->set_flashdata('success', 'Data berhasil diupdate.');
        redirect('admin/target');
    }

    public function target_delete($id)
    {
        $this->load->model('Atasan_target_model');

        $id = (int)$id;
        if (!$this->Atasan_target_model->getById($id)) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('admin/target');
            return;
        }

        $this->Atasan_target_model->delete($id);
        $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        redirect('admin/target');
    }

    public function reset_password($user_id)
    {
        $user_id = (int)$user_id;
        $user = $this->db->get_where('users', ['id' => $user_id])->row();
        if (!$user) show_error('User tidak ditemukan', 404);

        if (!in_array($user->role, ['pegawai','atasan'], true)) {
            $this->session->set_flashdata('error', 'Hanya pegawai/atasan yang bisa di-reset.');
            redirect('admin/user');
            return;
        }

        $new_password = (string)$this->input->post('new_password', true);
        if ($new_password === '' || strlen($new_password) < 6) {
            $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
            redirect('admin/user');
            return;
        }

        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $now  = date('Y-m-d H:i:s');

        $this->db->where('id', $user_id)->update('users', [
            'password' => $hash,
            'reset_at' => $now,
        ]);

        $this->session->set_flashdata('success', 'Password berhasil di-reset untuk '.$user->nama.'.');
        redirect('admin/user');
    }
}
