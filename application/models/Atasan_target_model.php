<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Atasan_target_model extends CI_Model
{

    private $table = 'atasan_target';

    public function getAll()
    {
        $this->db->from($this->table);
        $this->db->order_by('periode', 'DESC');
        $this->db->order_by('updated_at', 'DESC');
        return $this->db->get()->result();
    }

    public function getById($id)
    {
        return $this->db
            ->get_where($this->table, ['id' => (int)$id])
            ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function updateById($id, $data)
    {
        return $this->db
            ->where('id', (int)$id)
            ->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db
            ->where('id', (int)$id)
            ->delete($this->table);
    }

    /**
     * UPSERT GLOBAL (tanpa departemen)
     * berdasarkan periode
     */
    public function upsert($data)
    {
        if (empty($data['periode'])) return false;

        // pastikan global (tanpa departemen)
        $data['departemen_id'] = null;

        $exists = $this->db->get_where($this->table, [
            'periode' => $data['periode']
        ])->row();

        if ($exists) {
            $this->db->where('id', $exists->id)->update($this->table, $data);
            return (int)$exists->id;
        }

        $this->db->insert($this->table, $data);
        return (int)$this->db->insert_id();
    }
}
