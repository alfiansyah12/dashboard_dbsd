<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Atasan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

        if (!$this->session->userdata('logged_in')) redirect('auth');
        if ($this->session->userdata('role') != 'atasan') show_error('Akses ditolak', 403);

        $this->load->model('Divisi_model');
    }

    public function index()
    {
        $filter_divisi_id = $this->input->get('divisi_id'); // dari GET

        // ====== DATA TABEL ======
        $this->db->select('
            pt.id as pegawai_tugas_id,
            pt.tanggal_ambil,
            pt.status,
            u.nama as pegawai_nama,
            t.nama_tugas,
            d.nama_divisi,
            di.activity,
            di.pending_matters,
            di.close_the_path,
            ar.review_status
        ');
        $this->db->from('pegawai_tugas pt');
        $this->db->join('users u', 'u.id = pt.user_id');
        $this->db->join('tugas t', 't.id = pt.tugas_id');
        $this->db->join('divisi d', 'd.id = t.divisi_id');
        $this->db->join('dashboard_input di', 'di.pegawai_tugas_id = pt.id', 'left');
        $this->db->join('atasan_review ar', 'ar.pegawai_tugas_id = pt.id', 'left');

        if ($filter_divisi_id !== null && $filter_divisi_id !== '') {
            $this->db->where('t.divisi_id', (int)$filter_divisi_id);
        }

        $this->db->order_by('pt.id', 'DESC');
        $data['rows'] = $this->db->get()->result();

        // dropdown filter
        $data['divisi'] = $this->Divisi_model->getAll();
        $data['filter_divisi_id'] = $filter_divisi_id;

        // ====== DATA CHART: Penilaian Atasan (done vs not_yet) ======
        $countsReview = ['done' => 0, 'not_yet' => 0];

        $this->db->select('ar.review_status, COUNT(*) as total');
        $this->db->from('atasan_review ar');
        $this->db->join('pegawai_tugas pt', 'pt.id = ar.pegawai_tugas_id');
        $this->db->join('tugas t', 't.id = pt.tugas_id');

        if ($filter_divisi_id !== null && $filter_divisi_id !== '') {
            $this->db->where('t.divisi_id', (int)$filter_divisi_id);
        }

        $this->db->group_by('ar.review_status');
        foreach ($this->db->get()->result() as $r) {
            if (isset($countsReview[$r->review_status])) {
                $countsReview[$r->review_status] = (int)$r->total;
            }
        }

        $data['reviewLabels'] = json_encode(array_keys($countsReview));
        $data['reviewValues'] = json_encode(array_values($countsReview));

        // ====== DATA CHART: Status Pegawai (on going/done/terminated) ======
        $countsStatus = ['on going' => 0, 'done' => 0, 'terminated' => 0];

        $this->db->select('pt.status, COUNT(*) as total');
        $this->db->from('pegawai_tugas pt');
        $this->db->join('tugas t', 't.id = pt.tugas_id');

        if ($filter_divisi_id !== null && $filter_divisi_id !== '') {
            $this->db->where('t.divisi_id', (int)$filter_divisi_id);
        }

        $this->db->group_by('pt.status');
        foreach ($this->db->get()->result() as $s) {
            if (isset($countsStatus[$s->status])) {
                $countsStatus[$s->status] = (int)$s->total;
            }
        }

        $data['statusLabels'] = json_encode(array_keys($countsStatus));
        $data['statusValues'] = json_encode(array_values($countsStatus));

        $this->load->view('atasan/output', $data);
    }

    public function review_store()
    {
        $pegawai_tugas_id = (int)$this->input->post('pegawai_tugas_id');
        $review_status    = $this->input->post('review_status');
        $divisi_id        = $this->input->post('divisi_id'); // <-- penting (dari hidden input)

        if (!in_array($review_status, ['done','not_yet'], true)) {
            redirect('atasan');
        }

        $data = [
            'pegawai_tugas_id' => $pegawai_tugas_id,
            'review_status'    => $review_status,
            'review_by'        => $this->session->userdata('user_id'),
            'review_at'        => date('Y-m-d H:i:s'),
        ];

        $exists = $this->db->get_where('atasan_review', ['pegawai_tugas_id' => $pegawai_tugas_id])->row();
        if ($exists) {
            $this->db->where('pegawai_tugas_id', $pegawai_tugas_id)->update('atasan_review', $data);
        } else {
            $this->db->insert('atasan_review', $data);
        }

        // redirect balik ke halaman atasan + tetap bawa filter + scroll ke chart
        if ($divisi_id !== null && $divisi_id !== '') {
            redirect('atasan?divisi_id='.(int)$divisi_id.'#chart');
        }
        redirect('atasan#chart');
    }
}
