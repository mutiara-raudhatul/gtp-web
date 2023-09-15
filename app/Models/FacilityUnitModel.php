<?php

namespace App\Models;

use CodeIgniter\Model;

class FacilityUnitModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'facility_unit';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id','name'];

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

    public function get_facility_unit()
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->get();
        return $query;
    }

}
