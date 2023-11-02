<?php

namespace App\Controllers\Api;

use App\Models\WorshipPlaceModel;
use App\Models\GalleryWorshipPlaceModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class WorshipPlace extends ResourceController
{
    use ResponseTrait;

    protected $worshipPlaceModel;
    protected $galleryWorshipPlaceModel;

    public function __construct()
    {
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->galleryWorshipPlaceModel = new GalleryWorshipPlaceModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->worshipPlaceModel->get_list_wp()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Worship Place"
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
        $cp = $this->worshipPlaceModel->get_wp_by_id($id)->getRowArray();

        $response = [
            'data' => $cp,
            'status' => 200,
            'message' => [
                "Success display detail information of Worship Place"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadius()
    {
        $request = $this->request->getPost();
        $contents = $this->worshipPlaceModel->get_wp_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find worship place by radius"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteGWP = $this->galleryWorshipPlaceModel->delete_gallery($id);
        $deleteWP = $this->worshipPlaceModel->delete(['id' => $id]);
        if ($deleteWP) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete worship"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }
}
