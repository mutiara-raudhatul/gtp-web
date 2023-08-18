<?php

namespace App\Controllers\Web;

use App\Models\HomestayModel;
use App\Models\GalleryHomestayModel;
use CodeIgniter\RESTful\ResourcePresenter;

class Homestay extends ResourcePresenter
{
    protected $homestayModel;
    protected $galleryHomestayModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->homestayModel = new HomestayModel();
        $this->galleryHomestayModel = new GalleryHomestayModel();
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
        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();

        if (empty($homestay)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryHomestayModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $homestay['gallery'] = $galleries;

        $data = [
            'title' => $homestay['name'],
            'data' => $homestay,
            'folder' => 'homestay'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_homestay', $data);
        }
        return view('web/detail_homestay', $data);
    }
}
