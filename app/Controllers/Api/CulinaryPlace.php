<?php

namespace App\Controllers\Api;

use App\Models\CulinaryPlaceModel;
use App\Models\GalleryCulinaryPlaceModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class CulinaryPlace extends ResourceController
{
    use ResponseTrait;

    protected $culinaryPlaceModel;
    protected $galleryCulinaryPlaceModel;

    public function __construct()
    {
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->galleryCulinaryPlaceModel = new GalleryCulinaryPlaceModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->culinaryPlaceModel->get_list_cp()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Culinary Place"
            ]
        ];
        return $this->respond($response);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $cp = $this->culinaryPlaceModel->get_cp_by_id($id)->getRowArray();

        $response = [
            'data' => $cp,
            'status' => 200,
            'message' => [
                "Success display detail information of Culinary Place"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadius()
    {
        $request = $this->request->getPost();
        $contents = $this->culinaryPlaceModel->get_cp_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find culinary place by radius"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteGCP = $this->galleryCulinaryPlaceModel->delete_gallery($id);
        $deleteCP = $this->culinaryPlaceModel->delete(['id' => $id]);
        if ($deleteCP) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Culinary"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }
}
