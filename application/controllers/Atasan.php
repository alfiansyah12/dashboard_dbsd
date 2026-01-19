<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Atasan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->require_role('atasan');

        $this->load->model('Departemen_model');
        $this->load->model('Atasan_target_model');
        $this->load->model('Goals_model');
    }

    /** =========================
     *  HALAMAN UTAMA (output.php)
     *  ========================= */
    public function index()
    {
        $this->load->library('pagination');
        $filter_departemen_id = $this->input->get('departemen_id', true);
        $filter_departemen_id = ($filter_departemen_id !== null && $filter_departemen_id !== '') ? (int)$filter_departemen_id : null;
        $mode = $this->input->get('mode') ?: 'day';

        // 1. QUERY MONITORING (Pastikan alias pegawai_tugas_id dan kolom target/progress ada)
        $this->db->select("
        pt.id as pegawai_tugas_id, 
        pt.tanggal_ambil, 
        pt.status,
        pt.target_nilai, 
        pt.deadline_tanggal,
        u.nama as pegawai_nama, 
        t.nama_tugas, 
        d.nama_departemen,
        di.activity, 
        di.pending_matters, 
        di.close_the_path, 
        di.progress_nilai,
        COALESCE(di.updated_at, di.created_at, pt.created_at) as last_update,
        ar.review_status
    ", false);
        $this->db->from('pegawai_tugas pt');
        $this->db->join('users u', 'u.id = pt.user_id');
        $this->db->join('tugas t', 't.id = pt.tugas_id');
        $this->db->join('departemen d', 'd.id = t.departemen_id');
        $this->db->join('dashboard_input di', 'di.pegawai_tugas_id = pt.id', 'left');
        $this->db->join('atasan_review ar', 'ar.pegawai_tugas_id = pt.id', 'left');

        if ($filter_departemen_id !== null) {
            $this->db->where('t.departemen_id', $filter_departemen_id);
        }
        $this->db->order_by('last_update', 'DESC');
        $data['rows'] = $this->db->get()->result();

        // 2. PAGINATION RIWAYAT KPI (Samakan dengan Admin/Pegawai)
        $config['base_url'] = base_url('index.php/atasan/index');
        $config['total_rows'] = $this->db->count_all('kpi_realizations');
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = TRUE;

        // Styling Pagination Kotak Biru
        $config['full_tag_open'] = '<ul class="pagination pagination-sm m-0 justify-content-end">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['targets'] = $this->db->limit($config['per_page'], $page)
            ->order_by('periode', 'DESC')
            ->get('kpi_realizations')->result();
        $data['pagination_links'] = $this->pagination->create_links();

        // 3. LOGIKA CHART (Samakan dengan Admin)
        $chart_raw = $this->Atasan_target_model->get_chart_data($mode);
        $labels = [];
        $voa = ['t' => [], 'r' => []];
        $fbi = ['t' => [], 'r' => []];
        $trans = ['t' => [], 'r' => []];
        $agen = ['t' => [], 'r' => []];

        foreach ($chart_raw as $row) {
            $labels[] = ($mode == 'day') ? date('d M', strtotime($row->label)) : $row->label;
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
        $data['c_agen']       = json_encode($agen); 

        $data['current_mode'] = $mode;
        $data['departemen']   = $this->Departemen_model->getAll();
        $data['filter_departemen_id'] = $filter_departemen_id;

        $this->load->view('atasan/dashboard', $data);
    }

    /** =========================
     *  HALAMAN CHART (chart.php)
     *  ========================= */
    public function chart()
    {
        $filter_departemen_id = $this->input->get('departemen_id', true);
        $filter_departemen_id = ($filter_departemen_id !== null && $filter_departemen_id !== '') ? (int)$filter_departemen_id : null;

        // dropdown filter
        $data['departemen'] = $this->Departemen_model->getAll();
        $data['filter_departemen_id'] = $filter_departemen_id;

        // =======================
        // CHART 1: Penilaian Atasan
        // =======================
        $this->db->select('ar.review_status, COUNT(*) as total');
        $this->db->from('atasan_review ar');
        $this->db->join('pegawai_tugas pt', 'pt.id = ar.pegawai_tugas_id');
        $this->db->join('tugas t', 't.id = pt.tugas_id');

        if ($filter_departemen_id !== null) {
            $this->db->where('t.departemen_id', $filter_departemen_id);
        }

        $this->db->group_by('ar.review_status');
        $reviewRes = $this->db->get()->result();

        $reviewLabels = [];
        $reviewValues = [];
        foreach ($reviewRes as $r) {
            $reviewLabels[] = $r->review_status;
            $reviewValues[] = (int)$r->total;
        }

        if (empty($reviewLabels)) {
            $reviewLabels = ['done', 'not_yet'];
            $reviewValues = [0, 0];
        }

        // =======================
        // CHART 2: Status Pegawai
        // =======================
        $this->db->select('pt.status, COUNT(*) as total');
        $this->db->from('pegawai_tugas pt');
        $this->db->join('tugas t', 't.id = pt.tugas_id');

        if ($filter_departemen_id !== null) {
            $this->db->where('t.departemen_id', $filter_departemen_id);
        }

        $this->db->group_by('pt.status');
        $statusRes = $this->db->get()->result();

        $statusLabels = [];
        $statusValues = [];
        foreach ($statusRes as $s) {
            $statusLabels[] = $s->status;
            $statusValues[] = (int)$s->total;
        }

        if (empty($statusLabels)) {
            $statusLabels = ['on going', 'done', 'terminated'];
            $statusValues = [0, 0, 0];
        }

        $data['reviewLabels'] = json_encode($reviewLabels);
        $data['reviewValues'] = json_encode($reviewValues);
        $data['statusLabels'] = json_encode($statusLabels);
        $data['statusValues'] = json_encode($statusValues);

        $this->load->view('atasan/chart', $data);
    }

    public function review_store()
    {
        $pegawai_tugas_id = (int)$this->input->post('pegawai_tugas_id', true);
        $review_status    = $this->input->post('review_status', true);
        $pending_matters  = $this->input->post('pending_matters', true); // Input baru
        $close_the_path   = $this->input->post('close_the_path', true);  // Input baru

        if (!$pegawai_tugas_id) {
            redirect('atasan');
            return;
        }

        // 1. Simpan/Update Assessment di tabel atasan_review
        $review_data = [
            'pegawai_tugas_id' => $pegawai_tugas_id,
            'review_status'    => $review_status,
            'review_by'        => (int)$this->session->userdata('user_id'),
            'review_at'        => date('Y-m-d H:i:s'),
        ];

        $exists_review = $this->db->get_where('atasan_review', ['pegawai_tugas_id' => $pegawai_tugas_id])->row();
        if ($exists_review) {
            $this->db->where('pegawai_tugas_id', $pegawai_tugas_id)->update('atasan_review', $review_data);
        } else {
            $this->db->insert('atasan_review', $review_data);
        }

        // 2. Update Pending & Clear the Path di tabel dashboard_input
        $input_data = [
            'pending_matters' => $pending_matters,
            'close_the_path'  => $close_the_path,
            'updated_at'      => date('Y-m-d H:i:s')
        ];

        $exists_input = $this->db->get_where('dashboard_input', ['pegawai_tugas_id' => $pegawai_tugas_id])->row();
        if ($exists_input) {
            $this->db->where('pegawai_tugas_id', $pegawai_tugas_id)->update('dashboard_input', $input_data);
        } else {
            // Jika pegawai belum pernah menginput aktivitas sama sekali
            $input_data['pegawai_tugas_id'] = $pegawai_tugas_id;
            $input_data['created_at']       = date('Y-m-d H:i:s');
            $this->db->insert('dashboard_input', $input_data);
        }

        $this->session->set_flashdata('success', 'Update Monitoring & Assessment berhasil disimpan.');
        redirect('atasan');
    }

    public function target_store()
    {
        $periode = $this->input->post('periode', true); // Menerima YYYY-MM-DD dari input type="date"
        if (!$periode) {
            $this->session->set_flashdata('error', 'Tanggal wajib diisi.');
            redirect('admin/target');
            return;
        }

        $payload = [
            'periode'          => $periode, // Tidak lagi ditambah '-01'
            'target_voa'       => (int)$this->input->post('target_voa', true),
            'real_voa'         => (int)$this->input->post('real_voa', true),
            'target_fbi'       => (int)$this->input->post('target_fbi', true),
            'real_fbi'         => (int)$this->input->post('real_fbi', true),
            'target_transaksi' => (int)$this->input->post('target_transaksi', true),
            'real_transaksi'   => (int)$this->input->post('real_transaksi', true),
            'tgl_target_final' => $this->input->post('tgl_target_final', true),
            'catatan'          => $this->input->post('catatan', true),
            'created_by'       => (int)$this->session->userdata('user_id'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        // Gunakan upsert agar jika tanggal sama diinput lagi, datanya diupdate
        $this->Atasan_target_model->upsert($payload);
        $this->session->set_flashdata('success', 'Data harian berhasil disimpan.');
        redirect('admin/target');
    }

    public function terminate($pegawai_tugas_id)
    {
        $pegawai_tugas_id = (int)$pegawai_tugas_id;
        if (!$pegawai_tugas_id) {
            redirect('atasan');
            return;
        }

        $filter_departemen_id = $this->input->get('departemen_id', true);
        $filter_departemen_id = ($filter_departemen_id !== null && $filter_departemen_id !== '') ? (int)$filter_departemen_id : null;

        $this->db->where('id', $pegawai_tugas_id)->update('pegawai_tugas', [
            'status' => 'terminated'
        ]);

        $this->session->set_flashdata('success', 'Task berhasil di-terminate.');

        $url = 'atasan';
        if ($filter_departemen_id !== null) $url .= '?departemen_id=' . $filter_departemen_id;
        redirect($url);
    }

    public function get_aggregated_data($mode = 'day')
    {
        if ($mode == 'month') {
            $this->db->select("DATE_FORMAT(periode, '%Y-%m') as label");
        } elseif ($mode == 'week') {
            $this->db->select("YEARWEEK(periode) as label");
        } else {
            $this->db->select("periode as label");
        }

        $this->db->select("
        SUM(target_voa) as t_voa, SUM(real_voa) as r_voa,
        SUM(target_fbi) as t_fbi, SUM(real_fbi) as r_fbi,
        SUM(target_transaksi) as t_trans, SUM(real_transaksi) as r_trans,
        SUM(target_agen) as t_agen, SUM(real_agen) as r_agen
    ");

        $this->db->from($this->table);

        if ($mode == 'month') {
            $this->db->group_by("DATE_FORMAT(periode, '%Y-%m')");
        } elseif ($mode == 'week') {
            $this->db->group_by("YEARWEEK(periode)");
        } else {
            $this->db->group_by("periode");
        }

        $this->db->order_by('periode', 'ASC');
        return $this->db->get()->result();
    }
}
