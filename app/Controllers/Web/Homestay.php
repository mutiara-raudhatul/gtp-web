<?php

namespace App\Controllers\Web;

use App\Models\HomestayModel;
use App\Models\GalleryHomestayModel;
use App\Models\UnitHomestayModel;
use App\Models\FacilityUnitModel;
use App\Models\FacilityUnitDetailModel;
use CodeIgniter\RESTful\ResourcePresenter;

class Homestay extends ResourcePresenter
{
    protected $homestayModel;
    protected $galleryHomestayModel;
    protected $unitHomestayModel;
    protected $facilityUnitModel;
    protected $facilityUnitDetailModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->homestayModel = new HomestayModel();
        $this->galleryHomestayModel = new GalleryHomestayModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->facilityUnitModel = new FacilityUnitModel();
        $this->facilityUnitDetailModel = new FacilityUnitDetailModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->homestayModel->get_list_homestay()->getResultArray();
        $data = [
            'title' => 'Homestay',
            'data' => $contents,
        ];

        return view('web/list_homestay', $data);
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

        // $list_unit = $this->unitHomestayModel->get_unit_homestay($id)->getRowArray();
        $list_unit = $this->unitHomestayModel->get_unit_homestay($id)->getResultArray();

        $unithomes = array();
        foreach ($list_unit as $unithome) {
            $unithomes[] = $unithome['id'];
        }
        $homestay['unithomes'] = $unithomes;

        $facilities = array();
        foreach ($homestay['unithomes'] as $uh_id) {
            $unit_homestay_id=$uh_id;
            $list_facility = $this->facilityUnitDetailModel->get_facility_unit_detail($unit_homestay_id)->getResultArray();
            $facilities[]=$list_facility;
        }
        $fc = $facilities;

        $data = [
            'title' => $homestay['name'],
            'data' => $homestay,
            'unit' => $list_unit,
            'facility' => $fc,
            'folder' => 'homestay'
        ];

        // dd($data);

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_homestay', $data, $unit);
        }
        return view('web/detail_homestay',$data);
    }
}
