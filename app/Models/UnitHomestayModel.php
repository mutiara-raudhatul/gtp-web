<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitHomestayModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'unit_homestay';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id','homestay_id','nama_unit','description','price','capacity'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function get_unit_homestay($homestay_id =  null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->where('homestay_id', $homestay_id)
            ->get();
        return $query;
    }

    public function get_unit_homestay_by_id($homestay_id)
    {

        $query = $this->db->table($this->table)
            ->select("*")
            ->where('homestay_id', $homestay_id)
            ->get();

        return $query;
    }
 
    public function get_list_unit_homestay() {
        $query = $this->db->table($this->table)
            ->select("*")
            ->orderBy('id', 'ASC')
            ->get();

        return $query;
    }

    public function get_unit_homestay_selected($unit_homestay_id) 
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('id', $unit_homestay_id)
            ->get();
        return $query;
    }
    
    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        $count = (int)substr($lastId['id'], 1);
        $id = sprintf('U%02d', $count + 1);

        return $id;
    }

    public function add_new_unitHomestay($requestData = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($requestData);
        return $insert;
    }

}
