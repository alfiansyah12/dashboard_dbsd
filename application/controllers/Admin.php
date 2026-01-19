<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('User_model');
        $this->load->model('Departemen_model');
        $this->load->model('Tugas_model');

        // TAMBAHKAN BARIS INI
        $this->load->model('Atasan_target_model');

        $this->require_role('admin');
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
            'total_departemen' => $this->db->count_all('departemen'),
            'total_tugas'  => $this->db->count_all('tugas'),
        ];

        $this->render('Dashboard Admin', 'admin/dashboard', $data);
    }

    public function user()
    {
        $data['users']  = $this->User_model->getAllWithDepartemen();
        $data['departemen'] = $this->Departemen_model->getAll();

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

    public function departemen()
    {
        $data['departemen'] = $this->Departemen_model->getAll();
        $this->render('Kelola Departemen', 'admin/departemen', $data);
    }

    public function departemen_store()
    {
        $payload = ['nama_departemen' => $this->input->post('nama_departemen', true)];
        $this->Departemen_model->insert($payload);
        redirect('admin/departemen');
    }

    public function departemen_delete($id)
    {
        $this->Departemen_model->delete((int)$id);
        redirect('admin/departemen');
    }

    public function tugas()
    {
        // 1. Data Master Tugas
        $data['tugas']  = $this->Tugas_model->getAll();
        $data['departemen'] = $this->Departemen_model->getAll();

        // 2. Data Monitoring Progress Pegawai (Hanya On Going)
        $this->db->select('pt.*, u.nama as nama_pegawai, t.nama_tugas, di.progress_nilai, di.activity');
        $this->db->from('pegawai_tugas pt');
        $this->db->join('users u', 'u.id = pt.user_id');
        $this->db->join('tugas t', 't.id = pt.tugas_id');
        $this->db->join('dashboard_input di', 'di.pegawai_tugas_id = pt.id', 'left');

        // TAMBAHKAN BARIS INI: Hanya ambil yang sedang berjalan
        $this->db->where('pt.status', 'on going');

        $data['assignments'] = $this->db->get()->result();

        $this->render('Kelola Tugas', 'admin/tugas', $data);
    }

    public function update_assignment_target()
    {
        $id = (int)$this->input->post('assignment_id');
        $payload = [
            'target_nilai'     => (int)$this->input->post('target_nilai'),
            'deadline_tanggal' => $this->input->post('deadline_tanggal')
        ];

        $this->db->where('id', $id)->update('pegawai_tugas', $payload);
        $this->session->set_flashdata('success', 'Target & Deadline berhasil diperbarui.');
        redirect('admin/tugas');
    }

    public function tugas_store()
    {
        $payload = [
            'nama_tugas' => $this->input->post('nama_tugas', true),
            'deskripsi'  => $this->input->post('deskripsi', true),
            'departemen_id'  => (int)$this->input->post('departemen_id', true),
        ];

        $this->Tugas_model->insert($payload);
        redirect('admin/tugas');
    }

    public function tugas_delete($id)
    {
        $this->Tugas_model->delete((int)$id);
        redirect('admin/tugas');
    }

    public function assign_departemen()
    {
        $this->db->where('id', (int)$this->input->post('user_id'));
        $this->db->update('users', [
            'departemen_id' => (int)$this->input->post('departemen_id')
        ]);

        redirect('admin/user');
    }

    // ===============================
    // TARGET & REALISASI (ADMIN)
    // ===============================
    public function target()
    {
        date_default_timezone_set('Asia/Jakarta');
        $mode = $this->input->get('mode') ?: 'day';
        $this->load->library('pagination');

        // 1. Konfigurasi Pagination
        $config['base_url'] = base_url('index.php/admin/target');
        $config['total_rows'] = $this->db->count_all('kpi_realizations');
        $config['per_page'] = 10; // Jumlah baris per halaman
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = TRUE; // Agar filter grafik (mode) tidak hilang saat pindah halaman

        // Styling Bootstrap 4 untuk link pagination
        $config['full_tag_open'] = '<ul class="pagination pagination-sm m-0">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // 2. Ambil data dengan LIMIT dan OFFSET
        $data['targets_kpi']  = $this->db->order_by('periode', 'DESC')->get('kpi_targets')->result();
        $data['realizations'] = $this->db->limit($config['per_page'], $page)
            ->order_by('periode', 'DESC')
            ->get('kpi_realizations')->result();
        $data['targets']      = $data['realizations'];
        $data['pagination_links'] = $this->pagination->create_links();

        // 1. Ambil data untuk Tabel Terpisah
        $data['targets_kpi']  = $this->db->order_by('periode', 'DESC')->get('kpi_targets')->result();
        $data['realizations'] = $this->db->order_by('periode', 'DESC')->get('kpi_realizations')->result();
        $data['targets']      = $data['realizations']; // Untuk loop tabel riwayat harian

        // 2. Ambil data Chart dari Model
        $chart_raw = $this->Atasan_target_model->get_chart_data($mode);

        $labels = [];
        $voa = ['t' => [], 'r' => []];
        $fbi = ['t' => [], 'r' => []];
        $trans = ['t' => [], 'r' => []];
        $agen = ['t' => [], 'r' => []];

        foreach ($chart_raw as $row) {
            if ($mode == 'day') $labels[] = date('d M', strtotime($row->label));
            elseif ($mode == 'year') $labels[] = $row->label;
            else $labels[] = $row->label;

            $voa['t'][]   = $row->t_voa;
            $voa['r'][]   = (float)$row->r_voa;
            $fbi['t'][]   = $row->t_fbi;
            $fbi['r'][]   = (float)$row->r_fbi;
            $trans['t'][] = $row->t_trans;
            $trans['r'][] = (float)$row->r_trans;
            $agen['t'][]  = $row->t_agen;
            $agen['r'][]  = (float)$row->r_agen;
        }

        $data['chart_labels'] = json_encode($labels);
        $data['c_voa']        = json_encode($voa);
        $data['c_fbi']        = json_encode($fbi);
        $data['c_trans']      = json_encode($trans);
        $data['c_agen']  = json_encode($agen);
        $data['current_mode'] = $mode;

        // Inisialisasi variabel agar tidak error di View
        $edit_target_id = (int)$this->input->get('edit_target_id');
        $data['is_edit_target'] = (bool)$edit_target_id;
        $data['edit_target'] = $edit_target_id ? $this->db->get_where('kpi_targets', ['id' => $edit_target_id])->row() : null;

        $edit_real_id = (int)$this->input->get('edit_real_id');
        $data['is_edit_real'] = (bool)$edit_real_id;
        $data['edit_real'] = $edit_real_id ? $this->db->get_where('kpi_realizations', ['id' => $edit_real_id])->row() : null;

        $this->render('Target & Realisasi', 'admin/target_index', $data);
    }

    public function save_target()
    {
        $payload = [
            'periode' => $this->input->post('periode', true),
            'target_voa' => (int)$this->input->post('target_voa'),
            'target_fbi' => (int)$this->input->post('target_fbi'),
            'target_transaksi' => (int)$this->input->post('target_transaksi'),
            'target_agen' => (int)$this->input->post('target_agen'),
            'tgl_target_final' => $this->input->post('tgl_target_final'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('kpi_targets', $payload);
        redirect('admin/target');
    }

    public function save_realization()
    {
        $payload = [
            'periode' => $this->input->post('periode', true),
            'real_voa' => (int)$this->input->post('real_voa'),
            'real_fbi' => (int)$this->input->post('real_fbi'),
            'real_transaksi' => (int)$this->input->post('real_transaksi'),
            'real_agen' => (int)$this->input->post('real_agen'),
            'catatan' => $this->input->post('catatan', true),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('kpi_realizations', $payload);
        redirect('admin/target');
    }
    public function target_store()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Atasan_target_model');

        $periode = $this->input->post('periode', true); // Format: YYYY-MM-DD
        if (!$periode) {
            $this->session->set_flashdata('error', 'Tanggal wajib diisi.');
            redirect('admin/target');
            return;
        }

        $now = date('Y-m-d H:i:s');

        // Sesuaikan payload dengan nama kolom baru di database
        $payload = [
            'periode'          => $periode, // Langsung gunakan input harian
            'target_voa'       => (int)$this->input->post('target_voa', true),
            'real_voa'         => (int)$this->input->post('real_voa', true),
            'target_fbi'       => (int)$this->input->post('target_fbi', true),
            'real_fbi'         => (int)$this->input->post('real_fbi', true),
            'target_transaksi' => (int)$this->input->post('target_transaksi', true),
            'real_transaksi'   => (int)$this->input->post('real_transaksi', true),
            'target_agen'       => (int)$this->input->post('target_agen', true),
            'real_agen'         => (int)$this->input->post('real_agen', true),
            'tgl_target_final' => $this->input->post('tgl_target_final', true),
            'catatan'          => $this->input->post('catatan', true),
            'created_by'       => (int)$this->session->userdata('user_id'),
            'created_at'       => $now,
            'updated_at'       => $now,
        ];

        // Gunakan UPSERT agar jika tanggal sama sudah ada, maka otomatis UPDATE
        $this->Atasan_target_model->upsert($payload);
        $this->session->set_flashdata('success', 'Data KPI harian berhasil disimpan.');
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

        $periode = $this->input->post('periode', true); // Format: YYYY-MM-DD
        if (!$periode) {
            $this->session->set_flashdata('error', 'Tanggal wajib diisi.');
            redirect('admin/target?edit_id=' . $id);
            return;
        }

        $now = date('Y-m-d H:i:s');

        // Sesuaikan payload dengan nama kolom baru di database
        $payload = [
            'periode'          => $periode,
            'target_voa'       => (int)$this->input->post('target_voa', true),
            'real_voa'         => (int)$this->input->post('real_voa', true),
            'target_fbi'       => (int)$this->input->post('target_fbi', true),
            'real_fbi'         => (int)$this->input->post('real_fbi', true),
            'target_transaksi' => (int)$this->input->post('target_transaksi', true),
            'real_transaksi'   => (int)$this->input->post('real_transaksi', true),
            'target_agen'       => (int)$this->input->post('target_agen', true),
            'real_agen'         => (int)$this->input->post('real_agen', true),
            'tgl_target_final' => $this->input->post('tgl_target_final', true),
            'catatan'          => $this->input->post('catatan', true),
            'updated_at'       => $now,
        ];

        $this->Atasan_target_model->updateById($id, $payload);
        $this->session->set_flashdata('success', 'Data harian berhasil diperbarui.');
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

        if (!in_array($user->role, ['pegawai', 'atasan'], true)) {
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

        $this->session->set_flashdata('success', 'Password berhasil di-reset untuk ' . $user->nama . '.');
        redirect('admin/user');
    }

    public function delete_target($id)
    {
        $this->db->where('id', (int)$id)->delete('kpi_targets');
        $this->session->set_flashdata('success', 'Target berhasil dihapus.');
        redirect('admin/target');
    }

    public function delete_realization($id)
    {
        $this->db->where('id', (int)$id)->delete('kpi_realizations');
        $this->session->set_flashdata('success', 'Data realisasi berhasil dihapus.');
        redirect('admin/target');
    }
}
