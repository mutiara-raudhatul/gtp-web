<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPackageModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detail_package';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['package_id','day','activity','activity_type','object_id','description'];

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

    // public function get_allDetailPackage($package_id = null)
    // {
    //     $query = $this->db->table($this->table)
    //         ->select('service_package_id')
    //         ->where('package_id', $package_id)
    //         ->orderBy('service_package_id', 'ASC')
    //         ->get();
    //     return $query;
    // }

    public function get_detailPackage_by_id($package_id, $packageDay)
    {
        // dd($dayp);
        // foreach ($packageDay as $item => $key):
            // $day=$key['day'];
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('package_id', $package_id) 
            // ->groupby('day')
            ->get();
        // endforeach;
        return $query;
    }


    public function add_new_packageActivity($packageActivity = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($packageActivity);
        return $insert;
    }

    function get_object(){
        $query = $this->db->get('attraction');
        return $query;  
    }

    // public function add_new_detail_service($id, array $detailService)
    // {
    //     // dd($detailService);
    //     $query = false;
    //     foreach ($detailService as $ds) {
    //         $content = [
    //             'service_package_id' => $ds,
    //             'package_id' => $id,
    //             'status' => '1',
    //         ];
    //         $query = $this->db->table($this->table)->insert($content);
    //     }
    //     return $query;
    // }

    // public function delete_detail_service($id = null)
    // {
    //     return $this->db->table($this->table)->delete(['package_id' => $id]);
    // }

}
