<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailServicePackageModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detail_service_package';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['service_package_id', 'package_id','status'];

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

    public function get_service($package_id = null)
    {
        $query = $this->db->table($this->table)
            ->select('service_package_id')
            ->where('package_id', $package_id)
            ->orderBy('service_package_id', 'ASC')
            ->get();
        return $query;
    }

    public function get_detailServicePackage_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('package_id', $id)
            ->get();
        return $query;
    }

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
