<?php

namespace App\Controllers\Web;

use App\Models\ServicePackageModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class ServicePackage extends ResourcePresenter
{
    protected $servicePackageModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->servicePackageModel = new ServicePackageModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
    }

    public function show($id = null)
    {
        $sp = $this->servicePackageModel->get_servicePackage_by_id($id)->getRowArray();

        if (empty($sp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $sp['name'],
            'data' => $sp,
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_servicepackage', $data);
        }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        $servicePackage = $this->servicePackageModel->get_list_service_package()->getResultArray();

        $data = [
            'title' => 'New Service Package',
            'facility' => $servicePackage
        ];
        return view('dashboard/service-package-form', $data);
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

        $id = $this->servicePackageModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addSP = $this->servicePackageModel->add_new_servicePackage($requestData);

        if ($addSP) {
            return redirect()->to(base_url('dashboard/servicepackage'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $sp = $this->servicePackageModel->get_servicePackage_by_id($id)->getRowArray();
        if (empty($sp)) {
            return redirect()->to('dashboard/service-package');
        }

        $servicePackage = $this->servicePackageModel->get_list_service_package()->getResultArray();

        $data = [
            'title' => 'Edit Service Package',
            'data' => $sp,
            'facility' => $servicePackage
        ];
        return view('dashboard/service-package-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateSP = $this->servicePackageModel->update_servicePackage($id, $requestData);

        if ($updateSP) {
            return redirect()->to(base_url('dashboard/servicepackage') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }
}
