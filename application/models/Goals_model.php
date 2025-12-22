<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goals_model extends CI_Model {

    private $table = 'goals';

    public function getByPegawaiTugas($pegawai_tugas_id)
    {
        return $this->db->get_where($this->table, ['pegawai_tugas_id' => (int)$pegawai_tugas_id])->row();
    }

    public function upsert($pegawai_tugas_id, $goals)
    {
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');

        $exists = $this->getByPegawaiTugas($pegawai_tugas_id);

        $data = [
            'pegawai_tugas_id' => (int)$pegawai_tugas_id,
            'goals'            => $goals,
            'updated_at'       => $now,
        ];

        if ($exists) {
            $this->db->where('pegawai_tugas_id', (int)$pegawai_tugas_id)->update($this->table, $data);
            return $exists->id;
        }

        $data['created_at'] = $now;
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function getAllForAtasan($filter_divisi_id = null)
    {
        $this->db->select('
            g.id,
            g.goals,
            g.created_at,
            g.updated_at,

            pt.id as pegawai_tugas_id,
            pt.tanggal_ambil,
            pt.status,

            u.nama as pegawai_nama,
            t.nama_tugas,
            d.nama_divisi
        ');
        $this->db->from('goals g');
        $this->db->join('pegawai_tugas pt', 'pt.id = g.pegawai_tugas_id');
        $this->db->join('users u', 'u.id = pt.user_id');
        $this->db->join('tugas t', 't.id = pt.tugas_id');
        $this->db->join('divisi d', 'd.id = t.divisi_id');

        if ($filter_divisi_id !== null && $filter_divisi_id !== '') {
            $this->db->where('t.divisi_id', (int)$filter_divisi_id);
        }

        $this->db->order_by('COALESCE(g.updated_at,g.created_at)', 'DESC', false);
        return $this->db->get()->result();
    }
}
