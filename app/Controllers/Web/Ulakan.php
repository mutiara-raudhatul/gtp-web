<?php

namespace App\Controllers\Web;

// use App\Models\GtpModel;
// use App\Models\GalleryGtpModel;
use CodeIgniter\RESTful\ResourcePresenter;

class Ulakan extends ResourcePresenter
{
    // protected $gtpModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        // $this->gtpModel = new GtpModel();
        // $this->galleryGtpModel = new GalleryGtpModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        // $contents = $this->gtpModel->get_gtp()->getResultArray();

        // for ($index = 0; $index < count($contents); $index++) {
        //     $list_gallery = $this->galleryGtpModel->get_gallery($contents[$index]['id'])->getResultArray();
        //     $galleries = array();
        //     foreach ($list_gallery as $gallery) {
        //         $galleries[] = $gallery['url'];
        //     }
        //     $contents[$index]['gallery'] = $galleries;
        // }

        $data = [
            'title' => 'Explore Ulakan',
            // 'data' => $contents
        ];

        return view('web/explore_ulakan', $data);
    }
}
