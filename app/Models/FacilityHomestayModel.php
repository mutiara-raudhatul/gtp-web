<?php

namespace App\Models;

use CodeIgniter\Model;

class FacilityHomestayModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'facility_homestay';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'name'];

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

    public function get_list_facility_homestay() {
        $query = $this->db->table($this->table)
            ->select("*")
            ->orderBy('id', 'ASC')
            ->get();

        return $query;
    }

    public function get_listservicePackage_by_id(array $data)
    {

        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }

            $query = $this->db->table($this->table)
                ->select('name')
                ->where('id', $value)
                ->get();
        }
            
        return $query;

        
        // foreach ($list_service as  $key){
        //     $query = $this->db->table($this->table)
        //         ->select("*")
        //         ->where('id', $key)
        //         ->get();
        // }
    }

    public function get_servicePackage_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('id', $id)
            ->get();
        return $query;
    }
    
    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        $count = (int)substr($lastId['id'], 1);
        $id = sprintf('S%1d', $count + 1);
        return $id;
    }

    public function add_new_servicePackage($servicePackage = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($servicePackage);
        return $insert;
    }

    public function update_servicePackage($id = null, $servicePackage = null)
    {
        foreach ($servicePackage as $key => $value) {
            if (empty($value)) {
                unset($servicePackage[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($servicePackage);
        return $query;
    }


}
