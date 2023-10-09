<?php

namespace App\Controllers\Web;

use App\Models\HomestayModel;
use App\Models\UnitHomestayModel;
use App\Models\FacilityUnitModel;
use App\Models\FacilityUnitDetailModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class UnitHomestay extends ResourcePresenter
{
    protected $homestayModel;
    protected $unitHomestayModel;
    protected $facilityUnitModel;
    protected $facilityUnitDetailModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->homestayModel = new HomestayModel();
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
    }

    // public function show($id = null)
    // {
    //     $sp = $this->servicePackageModel->get_servicePackage_by_id($id)->getRowArray();

    //     if (empty($sp)) {
    //         return redirect()->to(substr(current_url(), 0, -strlen($id)));
    //     }

    //     $data = [
    //         'title' => $sp['name'],
    //         'data' => $sp,
    //     ];

    //     if (url_is('*dashboard*')) {
    //         return view('dashboard/detail_servicepackage', $data);
    //     }
    // }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new($id = null)
    {
        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();

        $facilityUnit = $this->facilityUnitModel->get_list_facility_unit()->getResultArray();

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

        // $data = [
            // 'title' => $homestay['name'],
            // 'data' => $homestay,
            // 'unit' => $list_unit,
            // 'facility' => $fc,
            // 'folder' => 'homestay'
        // ];

        $data = [
            'title' => 'Unit Homestay',
            'homestay_id'=>$id,
            'data'=>$homestay,
            'unit' => $list_unit,
            'facility_unit' => $facilityUnit,
            'facility' => $fc,
        ];
// dd($data);
        return view('dashboard/unit-homestay-form', $data);
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
    //  */
    public function createunit($id)
    {

        $request = $this->request->getPost();
        $id_unit = $this->unitHomestayModel->get_new_id();

        $requestData = [
            'id' => $id_unit,
            'homestay_id' => $id,
            'nama_unit' => $request['nama_unit'],
            'capacity' => $request['capacity'],
            'price' => $request['price'],
            'description' => $request['description']
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addUH = $this->unitHomestayModel->add_new_unitHomestay($requestData);

        if ($addUH) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function createfacilityunit($id)
    {
        $request = $this->request->getPost();

        $requestData = [
            'facility_unit_id' => $request['facility_unit_id'],
            'unit_homestay_id' => $request['unit_homestay'],
            'description' => $request['description_facility']
        ];

        $addFU = $this->facilityUnitDetailModel->add_new_facilityUnitDetail($id, $requestData);

        if ($addFU) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function deletefacilityunit ($unit_homestay_id=null, $facility_unit_id=null, $description=null)
    {
        $request = $this->request->getPost();

        $unit_homestay_id=$request['unit_homestay_id'];
        $facility_unit_id=$request['facility_unit_id'];
        $description=$request['description'];

        $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($unit_homestay_id)->getRowArray();
        $data_facility = $this->facilityUnitModel->get_facility_unit_selected($facility_unit_id)->getRowArray();

        $array = array('unit_homestay_id' => $unit_homestay_id, 'facility_unit_id' => $facility_unit_id, 'description' => $description);
        $facilityUnitDetail = $this->facilityUnitDetailModel->where($array)->find();
        $deleteFUD= $this->facilityUnitDetailModel->where($array)->delete();

        if ($deleteFUD) {
            session()->setFlashdata('pesan', 'Fasilitas Unit "'.$data_facility['name'].'" di "'.$data_unit['nama_unit'].'" Berhasil di Hapus.');
            
            return redirect()->back();

        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Package not found"
                ]
            ];
            return $this->failNotFound($response);
        }
    }

    public function createfacility($id)
    {
        $request = $this->request->getPost();

        $id_facility = $this->facilityUnitModel->get_new_id();

        $requestData = [
            'id' => $id_facility,
            'name' => $request['facility_name'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addFU = $this->facilityUnitModel->add_new_facilityUnit($requestData);

        if ($addFU) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $uh = $this->unitHomestayModel->get_unit_homestay_by_id($id)->getRowArray();

        $unitHomestay = $this->unitHomestayModel->get_list_unit_homestay()->getResultArray();

        $data = [
            'title' => 'Unit Homestay',
            'homestay_id' => $id,
            'data' => $uh,
            'unithomestay' => $unitHomestay
        ];
        return view('dashboard/unit-homestay-form', $data);
    }

    // public function update($id = null)
    // {
    //     $request = $this->request->getPost();
    //     $requestData = [
    //         'id' => $id,
    //         'name' => $request['name'],
    //     ];
    //     foreach ($requestData as $key => $value) {
    //         if (empty($value)) {
    //             unset($requestData[$key]);
    //         }
    //     }

    //     $updateSP = $this->servicePackageModel->update_servicePackage($id, $requestData);

    //     if ($updateSP) {
    //         return redirect()->to(base_url('dashboard/servicepackage') . '/' . $id);
    //     } else {
    //         return redirect()->back()->withInput();
    //     }
    // }



    public function show($id = null)
    {
        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();

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


        if (url_is('*dashboard*')) {
            return view('dashboard/detail_homestay', $data);
        }
        return view('web/detail_homestay',$data);
    }

    public function delete ($homestay_id=null, $unit_homestay_id=null)
    {
        $request = $this->request->getPost();

        $homestay_id=$request['homestay_id'];
        $unit_homestay_id=$request['unit_homestay_id'];

        $array = array('id' => $unit_homestay_id, 'homestay_id' => $homestay_id);
        $unitHomestay = $this->unitHomestayModel->where($array)->find();
        $deleteUH= $this->unitHomestayModel->where($array)->delete();

        if ($deleteUH) {
            session()->setFlashdata('pesan', 'Unit "'.$unit_homestay_id.'"Homestay "'.$homestay_id.'" Berhasil di Hapus.');
            
            return redirect()->back();

        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Unit Homestay not found"
                ]
            ];
            return $this->failNotFound($response);
        }
    }
}
