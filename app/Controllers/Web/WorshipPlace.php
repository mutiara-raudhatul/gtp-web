<?php

namespace App\Controllers\Web;

use App\Models\WorshipPlaceModel;
use App\Models\GalleryWorshipPlaceModel;
use CodeIgniter\RESTful\ResourcePresenter;

class WorshipPlace extends ResourcePresenter
{
    protected $worshipPlaceModel;
    protected $galleryWorshipPlaceModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->galleryWorshipPlaceModel = new GalleryWorshipPlaceModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
    }

    /**
     * Present a view to present a wpecific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $wp = $this->worshipPlaceModel->get_wp_by_id($id)->getRowArray();

        if (empty($wp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryWorshipPlaceModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $wp['gallery'] = $galleries;

        $data = [
            'title' => $wp['name'],
            'data' => $wp,
            'folder' => 'worship_place'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_worship_place', $data);
        }
        return view('web/detail_worship_place', $data);
    }
}
