<?php

namespace App\Controllers\Web;

use App\Models\HomestayModel;
use App\Models\GalleryHomestayModel;
use App\Models\UnitHomestayModel;
use App\Models\FacilityUnitModel;
use App\Models\FacilityUnitDetailModel;
use App\Models\FacilityHomestayModel;
use App\Models\FacilityHomestayDetailModel;
use App\Models\DetailReservationModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Homestay extends ResourcePresenter
{
    protected $homestayModel;
    protected $galleryHomestayModel;
    protected $unitHomestayModel;
    protected $facilityUnitModel;
    protected $facilityUnitDetailModel;
    protected $facilityHomestayModel;
    protected $facilityHomestayDetailModel;
    protected $detailReservationModel;
    
    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->homestayModel = new HomestayModel();
        $this->galleryHomestayModel = new GalleryHomestayModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->facilityUnitModel = new FacilityUnitModel();
        $this->facilityUnitDetailModel = new FacilityUnitDetailModel();
        $this->facilityHomestayModel = new FacilityHomestayModel();
        $this->facilityHomestayDetailModel = new FacilityHomestayDetailModel();
        $this->detailReservationModel = new DetailReservationModel();
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

        $datareview = array();
        $datarating = array();
        foreach($list_unit as $unit){
            $unit_number=$unit['unit_number'];
            $unit_type=$unit['unit_type'];
            $dreview = $this->detailReservationModel->getReview($id, $unit_number, $unit_type)->getResultArray();
            $drating = $this->detailReservationModel->getRating($id, $unit_number, $unit_type)->getRowArray();
            $datareview[]=$dreview;
            $datarating[]=$drating;

        }

        $review = $datareview;
        $rating = $datarating;

        $data = [
            'title' => $homestay['name'],
            'data' => $homestay,
            'unit' => $list_unit,
            'facility' => $fc,
            'review' => $review,
            'rating' => $rating,
            'folder' => 'homestay'
        ];

        // dd($data);

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_homestay', $data);
        }
        return view('web/detail_homestay',$data);
    }

    public function new()
    {
        $facility = $this->facilityHomestayModel->get_list_facility_homestay()->getResultArray();
        $id = $this->homestayModel->get_new_id();

        $data = [
            'title' => 'New Homestay',
            'homestay_id'=>$id,
            'facility' => $facility,
        ];
        
        return view('dashboard/homestay-form', $data);
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->homestayModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'address' => $request['address'],
            'contact_person' => $request['contact_person'],
            'description' => $request['description']
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        $geojson = $request['geo-json'];

        $addHM = $this->homestayModel->add_new_homestay($requestData, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/homestay');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryHomestayModel->add_new_gallery($id, $gallery);
        }

        if ($addHM) {
            return redirect()->to(base_url('dashboard/homestay/').$id.'/edit');
        } else {
            $session = session();
            $session->setFlashdata('error', 'Data tersebut sudah ada');
            return redirect()->back()->withInput();
        }
    }

    public function createafacilityhomestay($id)
    {
        $request = $this->request->getPost();
        // $id = $this->homestayModel->get_new_id();

        $requestData = [
            'facility_homestay_id' => $request['facility_homestay_id'],
            'homestay_id' => $id,
            'description' => $request['description_facility']
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $checkExistingData = $this->facilityHomestayDetailModel->checkIfDataExists($requestData);

        if ($checkExistingData) {
            // Data sudah ada, set pesan error flash data
            $session = session();
            $session->setFlashdata('error', 'Data sudah ada');
            return redirect()->back()->withInput();
        } else {
            // Data belum ada, jalankan query insert
            $addFH = $this->facilityHomestayDetailModel->add_new_facilityHomestayDetail($requestData);
       
            if ($addFH) {
                return redirect()->to(base_url('dashboard/homestay/new/').$id);
            } else {
                return redirect()->back()->withInput();
            }
        }
        


        // if ($addFH) {
        //     // return view('dashboard/detail-package-form');
        //     $facilityHomestay = $this->packageModel->get_package_by_id($id)->getRowArray();
        //     $facilityHomestay = $this->homestayModel->get_facility_homestay_by_id($id)->getRowArray();

        //     $id=$facilityHomestay['id'];
        //     $data = [
        //         'title' => 'New Facility Homestay',
        //         'data' => $facilityHomestay
        //     ];
            
        //     // return view('dashboard/detail-package-form', $data);

        //     return redirect()->to(base_url('dashboard/packageday/').$id);
        // } else {
        //     return redirect()->back()->withInput();
        // }
    }


    public function edit($id = null)
    {
        $facility = $this->facilityHomestayModel->get_list_facility_homestay()->getResultArray();

        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();
        if (empty($homestay)) {
            return redirect()->to('dashboard/homestay');
        }

        $list_gallery = $this->galleryHomestayModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $homestay['gallery'] = $galleries;

        $facilityHomestay= $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($id)->getResultArray();

        $data = [
            'title' => 'Homestay',
            'data' => $homestay,
            'facility' => $facility,
            'facility_homestay'=>$facilityHomestay

        ];

        return view('dashboard/homestay-form', $data);
    }

    public function update($id = null)
    {

        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'address' => $request['address'],
            'price' => $request['price'],
            'contact_person' => $request['contact_person'],
            'description' => $request['description'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        // $geojson = $request['geo-json'];

        $updateHM = $this->homestayModel->update_homestay($id, $requestData);
        $updateGeom = $this->homestayModel->update_geom($id, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/homestay');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryHomestayModel->update_gallery($id, $gallery);
        } else {
            $this->galleryHomestayModel->delete_gallery($id);
        }

        if ($updateHM) {
            return redirect()->to(base_url('dashboard/homestay') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }
}
