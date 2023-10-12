<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'package';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'name', 'type_id', 'price', 'contact_person', 'description', 'video_url', 'geom'];

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

    public function get_list_package()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url,{$this->table}.min_capacity";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->join('package_type', 'package.type_id = package_type.id')
            ->join('package_day', 'package.id = package_day.package_id')
            ->select('package_type.type_name, package_day.day')
            ->get();
        return $query;
    }

    public function get_package_by_id($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type_id,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url";
        $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$geoJson}")
            ->where('package.id', $id)
            ->join('package_type', 'package.type_id = package_type.id')
            ->select('package_type.type_name')
            ->get();
        return $query;
    }

    public function get_package_by_name($name = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->like("{$this->table}.name", $name)
            ->get();
        return $query;
    }

    public function get_package_by_type($type = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.contact_person,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->like("{$this->table}.type_id", $type)
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        $count = (int)substr($lastId['id'], 2);
        $id = sprintf('P%04d', $count + 1);
        return $id;
    }
    

    public function add_new_package($requestData = null, $geom = null)
    {

        $insert = $this->db->table($this->table)
            ->insert($requestData);
        $update = $this->db->table($this->table)
            ->set('geom', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $requestData['id'])
            ->update();
        return $insert && $update;
    }

    public function update_package($id = null, $package = null)
    {
        foreach ($package as $key => $value) {
            if (empty($value)) {
                unset($package[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($package);
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
