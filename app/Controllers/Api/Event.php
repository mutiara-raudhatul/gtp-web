<?php

namespace App\Controllers\Api;

use App\Models\EventModel;
use App\Models\GalleryEventModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Event extends ResourceController
{
    use ResponseTrait;

    protected $eventModel;
    protected $galleryEventModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->galleryEventModel = new GalleryEventModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->eventModel->get_list_event()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Event"
            ]
        ];
        return $this->respond($response);
    }

    public function show($id = null)
    {
        $event = $this->eventModel->get_event_by_id($id)->getRowArray();

        $response = [
            'data' => $event,
            'status' => 200,
            'message' => [
                "Success display detail information of Event"
            ]
        ];
        return $this->respond($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $deleteGEV = $this->galleryEventModel->delete(['event_id' => $id]);
        $deleteEV = $this->eventModel->delete(['id' => $id]);
        if ($deleteEV) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete event"
                ]
            ];
            return $this->respondDeleted($response);
            // } else {
            //     $response = [
            //         'status' => 404,
            //         'message' => [
            //             "Event not found"
            //         ]
            //     ];
            //     return $this->failNotFound($response);
        }
    }
}
