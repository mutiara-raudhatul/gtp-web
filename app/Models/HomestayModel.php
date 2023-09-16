<?php

namespace App\Models;

use CodeIgniter\Model;

class HomestayModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'homestay';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = [
        'id', 'name', 'address', 'price', 'contact_person', 'description', 'status', 'geom'
    ];

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

    public function get_list_homestay()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.status";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->get();
        return $query;
    }

    // public function get_homestay_by_id($id = null)
    // {
    //     $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
    //     $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.status";
    //     $query = $this->db->table($this->table)
    //         ->select("{$columns}, {$coords}")
    //         ->where('id', $id)
    //         ->get();

    //     return $query;
    // }


    public function get_homestay_by_id($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.status";
        $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_homestay_by_radius($data = null)
    {
        $radius = (int)$data['radius'] / 1000;
        $lat = $data['lat'];
        $long = $data['long'];
        $distance = "(6371 * acos(cos(radians({$lat})) * cos(radians(ST_Y(ST_CENTROID({$this->table}.geom)))) 
                    * cos(radians(ST_X(ST_CENTROID({$this->table}.geom))) - radians({$long})) 
                    + sin(radians({$lat}))* sin(radians(ST_Y(ST_CENTROID({$this->table}.geom))))))";
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.status";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$distance} as distance")
            ->having(['distance <=' => $radius])
            ->get();
        return $query;
    }


    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        $count = (int)substr($lastId['id'], 2);
        $id = sprintf('HO%03d', $count + 1);
        return $id;
    }

    public function add_new_homestay($homestay = null, $geom = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($homestay);
        $update = $this->db->table($this->table)
            ->set('geom', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $homestay['id'])
            ->update();
        return $insert && $update;
    }

    public function update_homestay($id = null, $homestay = null)
    {
        foreach ($homestay as $key => $value) {
            if (empty($value)) {
                unset($homestay[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($homestay);
        return $query;
    }

    public function update_geom($id = null, $geom = null)
    {
        $query = $this->db->table($this->table)
            ->set('geom', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $id)
            ->update();
        return $query;
    }
}
