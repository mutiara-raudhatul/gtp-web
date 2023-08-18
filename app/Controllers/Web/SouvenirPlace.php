<?php

namespace App\Controllers\Web;

use App\Models\SouvenirPlaceModel;
use App\Models\GallerySouvenirPlaceModel;
use CodeIgniter\RESTful\ResourcePresenter;

class SouvenirPlace extends ResourcePresenter
{
    protected $souvenirPlaceModel;
    protected $gallerySouvenirPlaceModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->gallerySouvenirPlaceModel = new GallerySouvenirPlaceModel();
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
     * Present a view to present a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $sp = $this->souvenirPlaceModel->get_sp_by_id($id)->getRowArray();

        if (empty($sp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->gallerySouvenirPlaceModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $sp['gallery'] = $galleries;

        $data = [
            'title' => $sp['name'],
            'data' => $sp,
            'folder' => 'souvenir_place'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_souvenir_place', $data);
        }
        return view('web/detail_souvenir_place', $data);
    }
}
