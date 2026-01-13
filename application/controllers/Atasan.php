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
        // 1. Ambil Parameter Filter dan Mode
        $filter_departemen_id = $this->input->get('departemen_id', true);
        $filter_departemen_id = ($filter_departemen_id !== null && $filter_departemen_id !== '') ? (int)$filter_departemen_id : null;

        // Default mode adalah 'day' (Harian)
        $mode = $this->input->get('mode') ?: 'day';

        // =======================
        // TABEL MONITORING & GOALS
        // =======================
        // (Gunakan Query Builder untuk mengambil data aktivitas pegawai)
        $this->db->select("
        pt.id as pegawai_tugas_id, pt.tanggal_ambil, pt.status,
        u.nama as pegawai_nama, t.nama_tugas, d.nama_departemen,
        di.activity, di.pending_matters, di.close_the_path,
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

        // =======================
        // AGREGASI DATA UNTUK CHART
        // =======================
        // Memanggil fungsi agregasi yang ada di model
        $aggregated_data = $this->Atasan_target_model->get_aggregated_data($mode, $filter_departemen_id);

        $labels = [];
        $voa_series = ['t' => [], 'r' => []];
        $fbi_series = ['t' => [], 'r' => []];
        $trans_series = ['t' => [], 'r' => []];

        // Variabel untuk KPI Summary
        $sumTarget = 0;
        $sumRealisasi = 0;
        $sumFee = 0;
        $sumVol = 0;

        foreach ($aggregated_data as $row) {
            // Format Label berdasarkan Mode
            $labels[] = ($mode == 'day') ? date('d M', strtotime($row->label)) : $row->label;

            // Data Series (Target & Realisasi)
            $voa_series['t'][]   = (int)$row->t_voa;
            $voa_series['r'][]   = (int)$row->r_voa;
            $fbi_series['t'][]   = (int)$row->t_fbi;
            $fbi_series['r'][]   = (int)$row->r_fbi;
            $trans_series['t'][] = (int)$row->t_trans;
            $trans_series['r'][] = (int)$row->r_trans;

            // Hitung Total untuk KPI Card
            $sumTarget    += ($row->t_voa + $row->t_fbi + $row->t_trans);
            $sumRealisasi += ($row->r_voa + $row->r_fbi + $row->r_trans);
            $sumFee       += $row->r_fbi;
            $sumVol       += $row->r_voa;
        }

        // Persiapan Data Kirim ke View
        $data['current_mode'] = $mode;
        $data['departemen']   = $this->Departemen_model->getAll();
        $data['filter_departemen_id'] = $filter_departemen_id;

        // JSON Encode untuk Chart.js
        $data['chart_labels'] = json_encode($labels);
        $data['voa_json']     = json_encode($voa_series);
        $data['fbi_json']     = json_encode($fbi_series);
        $data['trans_json']   = json_encode($trans_series);

        // Data Summary
        $data['sumTarget']    = $sumTarget;
        $data['sumRealisasi'] = $sumRealisasi;
        $data['sumFee']       = $sumFee;
        $data['sumVol']       = $sumVol;
        $data['avgProgress']  = ($sumTarget > 0) ? round(($sumRealisasi / $sumTarget) * 100, 2) : 0;

        // Data Goals Table
        $data['goals_rows']   = $this->Goals_model->getAllForAtasan($filter_departemen_id);

        // Mengirim data ke View output.php yang berisi Tab Transaksi/VoA/FBI
        $this->load->view('atasan/output', $data);
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
        $departemen_id_raw    = $this->input->post('departemen_id', true);

        if (!$pegawai_tugas_id || !in_array($review_status, ['done', 'not_yet'], true)) {
            redirect('atasan');
            return;
        }

        $departemen_id = ($departemen_id_raw !== null && $departemen_id_raw !== '') ? (int)$departemen_id_raw : null;

        $data = [
            'pegawai_tugas_id' => $pegawai_tugas_id,
            'review_status'    => $review_status,
            'review_by'        => (int)$this->session->userdata('user_id'),
            'review_at'        => date('Y-m-d H:i:s'),
        ];

        $exists = $this->db->get_where('atasan_review', ['pegawai_tugas_id' => $pegawai_tugas_id])->row();
        if ($exists) {
            $this->db->where('pegawai_tugas_id', $pegawai_tugas_id)->update('atasan_review', $data);
        } else {
            $this->db->insert('atasan_review', $data);
        }

        $url = 'atasan';
        if ($departemen_id !== null) $url .= '?departemen_id=' . $departemen_id;


        $this->session->set_flashdata('success', 'Assessment berhasil disimpan.');
        redirect($url);
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
        SUM(target_transaksi) as t_trans, SUM(real_transaksi) as r_trans
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
