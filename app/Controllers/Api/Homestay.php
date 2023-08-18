<?php

namespace App\Controllers\Api;

use App\Models\HomestayModel;
use App\Models\GalleryHomestayModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Homestay extends ResourceController
{
    use ResponseTrait;

    protected $homestayModel;
    protected $galleryHomestayModel;

    public function __construct()
    {
        $this->homestayModel = new HomestayModel();
        $this->galleryHomestayModel = new GalleryHomestayModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->homestayModel->get_list_homestay()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Homestay"
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
        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();

        $list_gallery = $this->galleryHomestayModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }

        $homestay['gallery'] = $galleries;

        $response = [
            'data' => $homestay,
            'status' => 200,
            'message' => [
                "Success display detail information of Homestay"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadius()
    {
        $request = $this->request->getPost();
        $contents = $this->homestayModel->get_homestay_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find homestay by radius"
            ]
        ];
        return $this->respond($response);
    }
}
