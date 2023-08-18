<?php

namespace App\Controllers\Api;

use App\Models\AttractionModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Attraction extends ResourceController
{
    use ResponseTrait;

    protected $attractionModel;

    public function __construct()
    {
        $this->attractionModel = new AttractionModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->attractionModel->get_list_attraction()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Attraction"
            ]
        ];
        return $this->respond($response);
    }

    public function show($id = null)
    {
        $attraction = $this->attractionModel->get_attraction_by_id($id)->getRowArray();

        $response = [
            'data' => $attraction,
            'status' => 200,
            'message' => [
                "Success display detail information of Attraction"
            ]
        ];
        return $this->respond($response);
    }
}
