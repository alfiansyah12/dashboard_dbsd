<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Atasan_target_model extends CI_Model {

    private $table = 'atasan_target';

    public function getAll($divisi_id = null)
    {
        $this->db->select('at.*, d.nama_divisi');
        $this->db->from($this->table.' at');
        $this->db->join('divisi d', 'd.id = at.divisi_id', 'left');

        if ($divisi_id !== null && $divisi_id !== '') {
            $this->db->where('at.divisi_id', (int)$divisi_id);
        }

        // urut terbaru (periode terbaru + updated terbaru)
        $this->db->order_by('at.periode', 'DESC');
        $this->db->order_by('at.updated_at', 'DESC');

        return $this->db->get()->result();
    }

    public function upsert($data)
    {
        // pastikan key ada
        $divisi_id = array_key_exists('divisi_id', $data) ? $data['divisi_id'] : null;
        $periode   = $data['periode'] ?? null;

        if (!$periode) return false;

        // cari existing (handle NULL divisi_id dengan benar)
        $this->db->from($this->table);
        $this->db->where('periode', $periode);

        if ($divisi_id === null) {
            $this->db->where('divisi_id IS NULL', null, false);
        } else {
            $this->db->where('divisi_id', (int)$divisi_id);
        }

        $exists = $this->db->get()->row();

        if ($exists) {
            $this->db->where('id', (int)$exists->id)->update($this->table, $data);
            return (int)$exists->id;
        }

        $this->db->insert($this->table, $data);
        return (int)$this->db->insert_id();
    }

    public function delete($id)
    {
        return $this->db->where('id', (int)$id)->delete($this->table);
    }
}
