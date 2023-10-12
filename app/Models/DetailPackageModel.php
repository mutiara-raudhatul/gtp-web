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

    public function get_detailPackage_by_id($package_id)
    {

        $query = $this->db->table($this->table)
            ->select("*")
            ->where('package_id', $package_id) 
            ->get();

        return $query;
    }

    public function get_day_by_package($package_id)
    {
        $query = $this->db->table($this->table)
            ->select("day")
            ->where('package_id', $package_id) 
            ->distinct()
            ->get();

        return $query;
    }

    public function getCombinedData()
    {
        $culinaryPlaceModel = new CulinaryPlaceModel();
        $souvenirPlaceModel = new SouvenirPlaceModel();
        $worshipPlaceModel = new WorshipPlaceModel();
        $facilityModel = new FacilityModel();
        $attractionModel = new AttractionModel();
        $eventModel = new EventModel();

        $culinaryData = $culinaryPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom')
        ->join('detail_package', 'detail_package.object_id=culinary_place.id')
        ->get()->getResultArray();

        $souvenirData = $souvenirPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom')
        ->join('detail_package', 'detail_package.object_id=souvenir_place.id')
        ->get()->getResultArray();

        $worshipData = $worshipPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom')
        ->join('detail_package', 'detail_package.object_id=worship_place.id')
        ->get()->getResultArray();

        $facilityData = $facilityModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom')
        ->join('detail_package', 'detail_package.object_id=facility.id')
        ->get()->getResultArray();

        $attractionData = $attractionModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom')
        ->join('detail_package', 'detail_package.object_id=attraction.id')
        ->get()->getResultArray();

        $eventData = $eventModel->select('package_id, day, activity, object_id, activity_type, detail_package.description, name, geom')
        ->join('detail_package', 'detail_package.object_id=event.id')
        ->get()->getResultArray();

        // Gabungkan hasil dari kedua model
        $combinedData = array_merge($culinaryData, $souvenirData, $worshipData, $facilityData, $attractionData, $eventData);

        // Urutkan data berdasarkan kolom "activity"
        usort($combinedData, function($a, $b) {
            return strcmp($a['activity'], $b['activity']);
        });

        return $combinedData;
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
