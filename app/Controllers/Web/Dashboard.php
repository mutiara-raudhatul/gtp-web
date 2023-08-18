<?php

namespace App\Controllers\Web;

use App\Models\GtpModel;
use App\Models\GalleryGtpModel;
use App\Models\AttractionModel;
use App\Models\EventModel;
use App\Models\PackageModel;
use App\Models\FacilityModel;
use App\Models\ServicePackageModel;
use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $gtpModel;
    protected $galleryGtpModel;
    protected $attractionModel;
    protected $eventModel;
    protected $packageModel;
    protected $facilityModel;
    protected $servicePackageModel;

    public function __construct()
    {
        $this->gtpModel = new GtpModel();
        $this->galleryGtpModel = new GalleryGtpModel();
        $this->attractionModel = new AttractionModel();
        $this->eventModel = new EventModel();
        $this->packageModel = new PackageModel();
        $this->facilityModel = new FacilityModel();
        $this->servicePackageModel = new ServicePackageModel();

    }
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
        ];
        return view('dashboard/analytics', $data);
    }

    public function gtp()
    {
        $contents = $this->gtpModel->get_gtp()->getRowArray();

        $list_gallery = $this->galleryGtpModel->get_all_gallery()->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $contents['gallery'] = $galleries;

        $data = [
            'title' => 'Manage GTP Information',
            'data' => $contents,
            'folder' => 'gtp'
        ];
        return view('dashboard/manage-gtp', $data);
    }

    public function attraction()
    {
        $contents = $this->attractionModel->get_list_attraction()->getResultArray();

        $data = [
            'title' => 'Manage Attraction',
            'manage' => 'Attraction',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }

    public function event()
    {
        $contents = $this->eventModel->get_list_event()->getResultArray();

        $data = [
            'title' => 'Manage Event',
            'manage' => 'Event',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }

    public function package()
    {
        $contents = $this->packageModel->get_list_package()->getResultArray();

        $data = [
            'title' => 'Manage Package',
            'manage' => 'Package',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }

    public function facility()
    {
        $contents = $this->facilityModel->get_list_facility()->getResultArray();

        $data = [
            'title' => 'Manage Facility',
            'manage' => 'Facility',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }

    public function servicepackage()
    {
        $contents = $this->servicePackageModel->get_list_service_package()->getResultArray();
        $data = [
            'title' => 'Manage Service Package',
            'manage' => 'Service',
            'data' => $contents,
        ];

        return view('dashboard/manage-page', $data);
    }
}
