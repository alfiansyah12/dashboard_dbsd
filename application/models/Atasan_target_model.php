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

    // application/models/Atasan_target_model.php

    public function get_aggregated_data($mode = 'day')
    {
        if ($mode == 'month') {
            $this->db->select("DATE_FORMAT(periode, '%Y-%m') as label");
            $this->db->group_by("DATE_FORMAT(periode, '%Y-%m')");
        } elseif ($mode == 'week') {
            $this->db->select("YEARWEEK(periode) as label");
            $this->db->group_by("YEARWEEK(periode)");
        } else {
            $this->db->select("periode as label");
            $this->db->group_by("periode");
        }

        $this->db->select("
        SUM(target_voa) as t_voa, SUM(real_voa) as r_voa,
        SUM(target_fbi) as t_fbi, SUM(real_fbi) as r_fbi,
        SUM(target_transaksi) as t_trans, SUM(real_transaksi) as r_trans,
        MAX(updated_at) as last_update
    ");

        $this->db->from($this->table);
        $this->db->order_by('periode', 'ASC');
        return $this->db->get()->result();
    }

    public function get_chart_data($mode = 'day')
    {
        // Pengaturan label dan grouping berdasarkan mode
        if ($mode == 'year') {
            $this->db->select("DATE_FORMAT(periode, '%Y') as label");
            $this->db->group_by("DATE_FORMAT(periode, '%Y')");
        } elseif ($mode == 'month') {
            $this->db->select("DATE_FORMAT(periode, '%Y-%m') as label");
            $this->db->group_by("DATE_FORMAT(periode, '%Y-%m')");
        } elseif ($mode == 'week') {
            $this->db->select("YEARWEEK(periode) as label");
            $this->db->group_by("YEARWEEK(periode)");
        } else {
            $this->db->select("periode as label");
            $this->db->group_by("periode");
        }

        $this->db->select("SUM(real_voa) as r_voa, SUM(real_fbi) as r_fbi, SUM(real_transaksi) as r_trans");
        $this->db->from('kpi_realizations');
        $this->db->order_by('periode', 'ASC');
        $realizations = $this->db->get()->result();

        // Mapping target bulanan ke setiap label chart
        foreach ($realizations as $r) {
            $month = date('Y-m', strtotime($r->label));
            // FIX: Tambahkan operator '=' pada key array
            $target = $this->db->get_where('kpi_targets', ['DATE_FORMAT(periode, "%Y-%m") =' => $month])->row();

            $r->t_voa   = (float)($target->target_voa ?? 0);
            $r->t_fbi   = (float)($target->target_fbi ?? 0);
            $r->t_trans = (float)($target->target_transaksi ?? 0);
        }
        return $realizations;
    }
}
