<?php

namespace App\Controllers\Web;

use App\Models\CulinaryPlaceModel;
use App\Models\GalleryCulinaryPlaceModel;
use CodeIgniter\RESTful\ResourcePresenter;

class CulinaryPlace extends ResourcePresenter
{
    protected $culinaryPlaceModel;
    protected $galleryCulinaryPlaceModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->galleryCulinaryPlaceModel = new GalleryCulinaryPlaceModel();
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
        $cp = $this->culinaryPlaceModel->get_cp_by_id($id)->getRowArray();

        if (empty($cp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryCulinaryPlaceModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $cp['gallery'] = $galleries;

        $data = [
            'title' => $cp['name'],
            'data' => $cp,
            'folder' => 'culinary_place'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_culinary_place', $data);
        }
        return view('web/detail_culinary_place', $data);
    }
}
