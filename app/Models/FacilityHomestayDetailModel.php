<?php

namespace App\Models;

use CodeIgniter\Model;

class FacilityHomestayDetailModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'facility_homestay_detail';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['facility_homestay_id', 'homestay_id','description'];

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

    public function get_facility($homestay_id = null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->where('homestay_id', $homestay_id)
            ->orderBy('facility_homestay_id', 'ASC')
            ->get();
        return $query;
    }

    public function get_detailFacilityHomestay_by_id($homestay_id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('homestay_id', $homestay_id)
            ->get();
        return $query;
    }

    public function add_new_facilityHomestayDetail($facilityHomestay = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($facilityHomestay);
        return $insert;
    }



// ----------------------------------------------------------

    public function add_new_detail_service($id, $requestDetailService)
    {
        $query = false;
        foreach ($requestDetailService as $ds) {
            $content = [
                'service_package_id' => $ds,
                'package_id' => $id,
                'status' => '1',
            ];
            $query = $this->db->table($this->table)->insert($content);
        }
        return $query;
    }

    public function delete_detail_service($id = null)
    {
        return $this->db->table($this->table)->delete(['package_id' => $id]);
    }

}
