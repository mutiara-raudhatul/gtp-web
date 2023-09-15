<?php

namespace App\Models;

use CodeIgniter\Model;

class AttractionModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'attraction';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'name', 'type', 'price', 'description', 'video_url', 'geom', 'geom_area'];

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

    public function get_tracking()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->where('id', 'A0001')
            ->get();
        return $query;
    }

    public function get_list_attraction()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->get();
        return $query;
    }

    public function get_talao()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->notLike('id', 'A0001')
            ->get();
        return $query;
    }

    public function get_attraction_by_id($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.description,{$this->table}.video_url";
        $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_geoJson($id = null)
    {
        $geoJson = "ST_AsGeoJSON({$this->table}.geom_area) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_attraction2_by_id($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom_area)) AS lat, ST_X(ST_Centroid({$this->table}.geom_area)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.type,{$this->table}.price,{$this->table}.description,{$this->table}.video_url";
        $geoJson = "ST_AsGeoJSON({$this->table}.geom_area) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function update_attraction($id = null, $attraction = null)
    {
        foreach ($attraction as $key => $value) {
            if (empty($value)) {
                unset($attraction[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($attraction);
        return $query;
    }

    public function update_geom($id = null, $geom = null)
    {
        $query = $this->db->table($this->table)
            ->set('geom_area', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $id)
            ->update();
        return $query;
    }

    function get_object(){
        $query = $this->db->table($this->table)->select('name')->get;
        return $query;  
    }

    function get_attraction(){

        $object="<option value='0'>--pilih--</pilih>";
        
        $this->db->order_by('name','ASC');
        $ob= $this->db->get();
        
        foreach ($ob->result_array() as $data ){
        $object.= "<option value='$data[id]'>$data[name]</option>";
        }
        
        return $object;
        
        }
        
}
