<?php

namespace App\Models;

use CodeIgniter\Model;

class FacilityUnitDetailModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'facility_unit_detail';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['facility_unit_id','unit_homestay_id','description'];

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

    public function get_facility_unit_detail($unit_homestay_id =  null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->join('facility_unit', 'facility_unit_detail.facility_unit_id = facility_unit.id')
            ->where('unit_homestay_id', $unit_homestay_id)
            ->get();
        return $query;
    }

    public function add_new_facilityUnitDetail($id, $requestData)
    {
        $query = false;
        $content = [
            'facility_unit_id' => $requestData['facility_unit_id'],
            'unit_homestay_id' => $requestData['unit_homestay_id'],
            'description' => $requestData['description']
        ];

        $insert = $this->db->table($this->table)
            ->insert($content);
        return $insert;
    }

}
