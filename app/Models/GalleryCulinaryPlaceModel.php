<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryCulinaryPlaceModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'gallery_culinary_place';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'culinary_place_id', 'url'];

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

    public function get_gallery($culinary_place_id = null)
    {
        $query = $this->db->table($this->table)
            ->select('url')
            ->where('culinary_place_id', $culinary_place_id)
            ->get();
        return $query;
    }
}
