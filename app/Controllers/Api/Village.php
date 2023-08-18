<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\GtpModel;
use App\Models\VillageModel;
use App\Models\AttractionModel;
use CodeIgniter\API\ResponseTrait;

class Village extends BaseController
{
    use ResponseTrait;
    protected $gtpModel;
    protected $villageModel;
    protected $attractionModel;

    public function __construct()
    {
        $this->gtpModel = new GtpModel();
        $this->villageModel = new VillageModel();
        $this->attractionModel = new AttractionModel();
    }

    public function getData()
    {
        $request = $this->request->getPost();
        $digitasi = $request['digitasi'];

        if ($digitasi == 'GTP01') {
            $digiProperty = $this->gtpModel->get_desa_wisata()->getRowArray();
            $geoJson = json_decode($this->gtpModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
        } elseif ($digitasi == 'V0001') {
            $digiProperty = $this->villageModel->get_ulakan()->getRowArray();
            $geoJson = json_decode($this->villageModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
        } elseif ($digitasi == 'A0001') {
            $digiProperty = $this->attractionModel->get_tracking()->getRowArray();
            $geoJson = json_decode($this->attractionModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
        } else {
            $digiProperty = $this->attractionModel->get_list_attraction()->getRowArray();
            $geoJson = json_decode($this->attractionModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
        }

        $content = [
            'type' => 'Feature',
            'geometry' => $geoJson,
            'properties' => [
                'id' => $digiProperty['id'],
                'name' => $digiProperty['name'],
                'lat' => $digiProperty['lat'],
                'lng' => $digiProperty['lng'],
            ]
        ];
        $response = [
            'data' => $content,
            'status' => 200,
            'message' => [
                "Success"
            ]
        ];
        return $this->respond($response);
    }
}
