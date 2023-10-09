<?php

namespace App\Controllers\Web;

use App\Models\FacilityHomestayDetailModel;
use App\Models\FacilityHomestayModel;
use App\Models\HomestayModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Facilityhomestay extends ResourcePresenter
{
    protected $facilityHomestayDetailModel;
    protected $facilityHomestayModel;
    protected $homestayModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->facilityHomestayDetailModel = new FacilityHomestayDetailModel();
        $this->facilityHomestayModel = new FacilityHomestayModel();
        $this->homestayModel = new HomestayModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {

    }

    public function createfacility($id)
    {

        $request = $this->request->getPost();

        $id_facility = $this->facilityHomestayModel->get_new_id();

        $requestData = [
            'id' => $id_facility,
            'name' => $request['facility_name'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addFH = $this->facilityHomestayModel->add_new_facilityHomestay($requestData);

        if ($addFH) {
            // // return view('dashboard/detail-package-form');
            // $package = $this->packageModel->get_package_by_id($id)->getRowArray();

            // $id=$package['id'];
            // $data = [
            //     'title' => 'New Detail Package',
            //     'data' => $package
            // ];
            
            // // return view('dashboard/detail-package-form', $data);
            return redirect()->to(base_url('dashboard/homestay/').$id.'/edit');

        } else {
            return redirect()->back()->withInput();
        }
    }

    public function createfacilityhomestay($id)
    {

        $request = $this->request->getPost();

        $requestData = [
            'facility_homestay_id' => $request['facility'],
            'homestay_id' => $id,
            'description' => $request['description_facility']
        ];

        $addFHD = $this->facilityHomestayDetailModel->add_new_facilityHomestayDetail($id, $requestData);

        if ($addFHD) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function delete ($homestay_id=null, $facility_homestay_id=null, $description=null)
    {
        $request = $this->request->getPost();

        $facility_homestay_id=$request['facility_homestay_id'];
        $description=$request['description'];

        $array = array('homestay_id' => $homestay_id, 'facility_homestay_id' => $facility_homestay_id, 'description' => $description);
        $facilityHomestayDetail = $this->facilityHomestayDetailModel->where($array)->find();
        $deleteFHD= $this->facilityHomestayDetailModel->where($array)->delete();

        if ($deleteFHD) {
            session()->setFlashdata('pesan', 'Fasilitas Homestay "'.$facility_homestay_id.'" Berhasil di Hapus.');
            
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

}
