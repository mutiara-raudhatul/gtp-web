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

}
