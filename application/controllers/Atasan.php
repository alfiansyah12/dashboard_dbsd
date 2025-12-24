<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Atasan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_role('atasan');

        $this->load->model('Divisi_model');
        $this->load->model('Atasan_target_model');
        $this->load->model('Goals_model');
    }

    /** =========================
     *  HALAMAN UTAMA (output.php)
     *  ========================= */
    public function index()
    {
        $filter_divisi_id = $this->input->get('divisi_id', true);
        $filter_divisi_id = ($filter_divisi_id !== null && $filter_divisi_id !== '') ? (int)$filter_divisi_id : null;

        // =======================
        // TABEL MONITORING
        // =======================
        $this->db->select("
            pt.id as pegawai_tugas_id,
            pt.tanggal_ambil,
            pt.status,
            pt.created_at as pt_created_at,

            u.nama as pegawai_nama,
            t.nama_tugas,
            d.nama_divisi,

            di.activity,
            di.pending_matters,
            di.close_the_path,
            di.created_at as input_created_at,
            di.updated_at as input_updated_at,

            COALESCE(di.updated_at, di.created_at, pt.created_at) as last_update,

            ar.review_status,
            ar.review_at
        ", false);

        $this->db->from('pegawai_tugas pt');
        $this->db->join('users u', 'u.id = pt.user_id');
        $this->db->join('tugas t', 't.id = pt.tugas_id');
        $this->db->join('divisi d', 'd.id = t.divisi_id');
        $this->db->join('dashboard_input di', 'di.pegawai_tugas_id = pt.id', 'left');
        $this->db->join('atasan_review ar', 'ar.pegawai_tugas_id = pt.id', 'left');

        if ($filter_divisi_id !== null) {
            $this->db->where('t.divisi_id', $filter_divisi_id);
        }

        $this->db->order_by('last_update', 'DESC');
        $data['rows'] = $this->db->get()->result();

        // dropdown filter
        $data['divisi'] = $this->Divisi_model->getAll();
        $data['filter_divisi_id'] = $filter_divisi_id;

        // =======================
        // TARGET & REALISASI + CHART
        // =======================
        $data['targets'] = $this->Atasan_target_model->getAll($filter_divisi_id);

        $labels = [];
        $targetSeries = [];
        $realisasiSeries = [];
        $feeSeries = [];
        $volSeries = [];

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

        // =======================
        // GOALS TABLE
        // =======================
        $data['goals_rows'] = $this->Goals_model->getAllForAtasan($filter_divisi_id);

        // =======================
        // SUMMARY (untuk KPI di output.php)
        // =======================
        $sumTarget = 0; $sumRealisasi = 0; $sumFee = 0; $sumVol = 0;
        foreach (($data['targets'] ?? []) as $t) {
            $sumTarget    += (int)$t->target;
            $sumRealisasi += (int)$t->realisasi;
            $sumFee       += (int)($t->fee_base_income ?? 0);
            $sumVol       += (int)($t->volume_of_agent ?? 0);
        }
        $data['sumTarget'] = $sumTarget;
        $data['sumRealisasi'] = $sumRealisasi;
        $data['sumFee'] = $sumFee;
        $data['sumVol'] = $sumVol;
        $data['avgProgress'] = ($sumTarget > 0) ? round(($sumRealisasi / $sumTarget) * 100, 2) : 0;

        $this->load->view('atasan/output', $data);
    }

    /** =========================
     *  HALAMAN CHART (chart.php)
     *  ========================= */
    public function chart()
    {
        $filter_divisi_id = $this->input->get('divisi_id', true);
        $filter_divisi_id = ($filter_divisi_id !== null && $filter_divisi_id !== '') ? (int)$filter_divisi_id : null;

        // dropdown filter
        $data['divisi'] = $this->Divisi_model->getAll();
        $data['filter_divisi_id'] = $filter_divisi_id;

        // =======================
        // CHART 1: Penilaian Atasan
        // =======================
        $this->db->select('ar.review_status, COUNT(*) as total');
        $this->db->from('atasan_review ar');
        $this->db->join('pegawai_tugas pt', 'pt.id = ar.pegawai_tugas_id');
        $this->db->join('tugas t', 't.id = pt.tugas_id');

        if ($filter_divisi_id !== null) {
            $this->db->where('t.divisi_id', $filter_divisi_id);
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

        if ($filter_divisi_id !== null) {
            $this->db->where('t.divisi_id', $filter_divisi_id);
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
        $divisi_id_raw    = $this->input->post('divisi_id', true);

        if (!$pegawai_tugas_id || !in_array($review_status, ['done','not_yet'], true)) {
            redirect('atasan');
            return;
        }

        $divisi_id = ($divisi_id_raw !== null && $divisi_id_raw !== '') ? (int)$divisi_id_raw : null;

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
        if ($divisi_id !== null) $url .= '?divisi_id=' . $divisi_id;


        $this->session->set_flashdata('success', 'Assessment berhasil disimpan.');
        redirect($url);
    }

    public function target_store()
    {
        $divisi_id_input   = $this->input->post('divisi_id', true);
        $current_filter_id = $this->input->post('current_divisi_id', true);
        $periode           = $this->input->post('periode', true);
        $target            = (int)$this->input->post('target', true);
        $realisasi         = (int)$this->input->post('realisasi', true);
        $catatan           = $this->input->post('catatan', true);

        if (!$periode) { redirect('atasan'); return; }

        $periodeDate = $periode . '-01';
        $divisi_id_db = ($divisi_id_input === '' ? null : (int)$divisi_id_input);

        // cek exists (handle NULL)
        $this->db->from('atasan_target');
        $this->db->where('periode', $periodeDate);
        if ($divisi_id_db === null) $this->db->where('divisi_id IS NULL', null, false);
        else $this->db->where('divisi_id', $divisi_id_db);
        $exists = $this->db->get()->row();

        $now = date('Y-m-d H:i:s');

        $data = [
            'divisi_id'  => $divisi_id_db,
            'periode'    => $periodeDate,
            'target'     => $target,
            'realisasi'  => $realisasi,
            'catatan'    => $catatan,
            'created_by' => (int)$this->session->userdata('user_id'),
            'updated_at' => $now,
        ];
        if (!$exists) $data['created_at'] = $now;

        $this->Atasan_target_model->upsert($data);

        $url = 'atasan';
        if ($current_filter_id !== null && $current_filter_id !== '') {
            $url .= '?divisi_id=' . (int)$current_filter_id;
        }

        
        $this->session->set_flashdata('success', 'Target/Realisasi tersimpan.');
        redirect($url.'#target');
    }

    public function terminate($pegawai_tugas_id)
    {
        $pegawai_tugas_id = (int)$pegawai_tugas_id;
        if (!$pegawai_tugas_id) { redirect('atasan'); return; }

        $filter_divisi_id = $this->input->get('divisi_id', true);
        $filter_divisi_id = ($filter_divisi_id !== null && $filter_divisi_id !== '') ? (int)$filter_divisi_id : null;

        $this->db->where('id', $pegawai_tugas_id)->update('pegawai_tugas', [
            'status' => 'terminated'
        ]);

        $this->session->set_flashdata('success', 'Task berhasil di-terminate.');

        $url = 'atasan';
        if ($filter_divisi_id !== null) $url .= '?divisi_id=' . $filter_divisi_id;
        redirect($url);
    }
}
