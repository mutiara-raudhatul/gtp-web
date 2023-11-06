<?php

namespace App\Controllers\Web;

use App\Models\BackupDetailReservationModel;
use App\Models\DetailReservationModel;
use App\Models\ReservationModel;
use App\Models\UnitHomestayModel;
use App\Models\PackageModel;
use App\Models\PackageDayModel;
use App\Models\DetailPackageModel;
use App\Models\detailServicePackageModel;

use App\Models\CulinaryPlaceModel;
use App\Models\WorshipPlaceModel;
use App\Models\FacilityModel;
use App\Models\SouvenirPlaceModel;
use App\Models\AttractionModel;
use App\Models\EventModel;
use App\Models\HomestayModel;
use App\Models\ServicePackageModel;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;
use DateTime;

class DetailReservation extends ResourcePresenter
{
    protected $backupDetailReservationModel;
    protected $detailReservationModel;
    protected $reservationModel;
    protected $unitHomestayModel;
    protected $packageModel;
    protected $packageDayModel;
    protected $detailPackageModel;
    protected $servicePackageModel;
    protected $detailServicePackageModel;
    protected $culinaryPlaceModel;
    protected $worshipPlaceModel;
    protected $facilityModel;
    protected $souvenirPlaceModel;
    protected $attractionModel;
    protected $eventModel;
    protected $homestayModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];
    protected $db, $builder;

    public function __construct()
    {
        $this->backupDetailReservationModel = new BackupDetailReservationModel();
        $this->detailReservationModel = new DetailReservationModel();
        $this->reservationModel = new ReservationModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->packageModel = new PackageModel();
        $this->packageDayModel = new PackageDayModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->servicePackageModel = new ServicePackageModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->facilityModel = new FacilityModel();
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->attractionModel = new AttractionModel();
        $this->eventModel = new EventModel();
        $this->homestayModel = new HomestayModel();

        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('package');;
    }

    public function addcustom()
    {
        $id = $this->packageModel->get_new_id();

        $date = date('Y-m-d H:i');

        $requestData = [
            'id' => $id,
            'name' => 'Custom by User at '.$date,
            'type_id' => 'T0000',
            'min_capacity' => '10',
            'price' => null,
            'description' => 'Paket wisata ini adalah kustomisasi dari user',
            'contact_person' => null,
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = null;
        $geojson = null;

        if (isset($request['video'])) {
            $folder = $request['video'];
            $filepath = WRITEPATH . 'uploads/' . $folder;
            $filenames = get_filenames($filepath);
            $vidFile = new File($filepath . '/' . $filenames[0]);
            $vidFile->move(FCPATH . 'media/videos');
            delete_files($filepath);
            rmdir($filepath);
            $requestData['video_url'] = $vidFile->getFilename();
        }
        
        $addPA = $this->packageModel->add_new_package($requestData, $geom);
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/package');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryPackageModel->add_new_gallery($id, $gallery);
        }
    
        if ($addPA) {
            return redirect()->to(base_url('web/detailreservation/packagecustom/').$id);
        } else {
            return redirect()->back()->withInput();
        } 
        
    }

    public function packagecustom($id)
    {
        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        $package_id=$package['id'];
        $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();

        $culinary = $this->culinaryPlaceModel->get_list_cp()->getResultArray();
        $worship = $this->worshipPlaceModel->get_list_wp()->getResultArray();
        $facility = $this->facilityModel->get_list_facility()->getResultArray();
        $souvenir = $this->souvenirPlaceModel->get_list_sp()->getResultArray();
        $attraction = $this->attractionModel->get_list_attraction()->getResultArray();
        $event = $this->eventModel->get_list_event()->getResultArray();
        $homestay = $this->homestayModel->get_list_homestay()->getResultArray();

        $data_object = array_merge($culinary,$worship,$facility,$souvenir,$attraction,$event,$homestay);
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData($package_id);
            
        $object = [
            'culinary' => $culinary,
            'worship' => $worship,
            'facility' => $facility,
            'souvenir' => $souvenir,
            'attraction' => $attraction,
            'event' => $event,
            'homestay' => $homestay
        ];

        $servicelist = $this->servicePackageModel->get_list_service_package()->getResultArray();

        $this->builder->select ('service_package.id, service_package.name');
        $this->builder->join ('detail_service_package', 'detail_service_package.package_id = package.id');
        $this->builder->join ('service_package', 'service_package.id = detail_service_package.service_package_id', 'right');
        $this->builder->where ('package.id', $id);
        $query = $this->builder->get();
        $datase['package']=$query->getResult();
        $datases = $datase['package'];
        $package['datase'] = $datases;

        $servicepackage = $this->detailServicePackageModel->get_service_package_detail_by_id($id)->getResultArray();

        $data = [
            'title' => 'Detail Package '.$package['name'],
            'id' => $id,
            'data' => $package,
            'day' => $packageDay,
            'activity' => $detailPackage,
            'data_package' => $combinedData,
            'object' => $object,
            'detailservice' => $servicepackage,
            'service' => $package['datase'],
            'servicelist' => $servicelist
        ];  
        // dd( $data);
        

        return view('web/custom-package-form', $data, $object);
        
    }

    public function createday($id)
    {

        $request = $this->request->getPost();

        $requestData = [
            'package_id' => $id,
            'day' => $request['day'],
            'description' => $request['description']
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addPD = $this->packageDayModel->add_new_packageDay($requestData);

        if ($addPD) {
            // return view('dashboard/detail-package-form');
            $package = $this->packageModel->get_package_by_id($id)->getRowArray();

            $id=$package['id'];
            $data = [
                'title' => 'New Detail Package',
                'data' => $package
            ];
            
            // return view('dashboard/detail-package-form', $data);

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function createactivity($id)
    {
        $request = $this->request->getPost();

        $requestData = [
            'package_id' => $id,
            'day' => $request['day'],
            'activity' => $request['activity'],
            'activity_type' => $request['activity_type'],
            'object_id' => $request['object'],
            'description' => $request['description_activity']
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $checkExistingData = $this->detailPackageModel->checkIfDataExists($requestData);

        if ($checkExistingData) {
            // Data sudah ada, set pesan error flash data
            session()->setFlashdata('failed', 'Urutan aktivitas tersebut sudah ada.');

            return redirect()->back()->withInput();
        } else {
            // Data belum ada, jalankan query insert
            $addPA = $this->detailPackageModel->add_new_packageActivity($requestData);

            if ($addPA) {
                session()->setFlashdata('success', 'Aktivitas tersebut berhasil ditambahkan.');

                return redirect()->back();
            } else {
                return redirect()->back()->withInput();
            }
        }

    }


    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function addhome($id=null)
    {
        $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
        $package_id_reservation= $datareservation['package_id'];
        
        //detail package 
        $package = $this->packageModel->get_package_by_id($package_id_reservation)->getRowArray();        
        $serviceinclude= $this->detailServicePackageModel->get_service_include_by_id($package_id_reservation)->getResultArray();
        $serviceexclude= $this->detailServicePackageModel->get_service_exclude_by_id($package_id_reservation)->getResultArray();
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id_reservation)->getResultArray();
        $getday = $this->packageDayModel->get_day_by_package($package_id_reservation)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData($package_id_reservation);
// dd($getday);
        //data homestay
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        if($datareservation['cancel']=='0'){
            $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        } else if ($datareservation['cancel']=='1'){
            $booking_unit = $this->backupDetailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        }
        // $unit_booking= $this->detailReservationModel->get_unit_homestay_dtbooking($id)->getResultArray();

        $day=max($getday);
        $daypack=$day['day'];
        $dayhome=$day['day']-1;

        // dd($booking_unit);
        if(!empty($booking_unit)){
            $data_unit_booking=array();
            $data_price=array();
            foreach($booking_unit as $booking){
                $homestay_id=$booking['homestay_id'];
                $unit_type=$booking['unit_type'];
                $unit_number=$booking['unit_number'];
                $reservation_id=$booking['reservation_id'];

                if($datareservation['cancel']=='0'){
                    $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($homestay_id,$unit_type,$unit_number,$id)->getRowArray();
                    $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id,$unit_type,$unit_number,$id)->getRow();
                } else if ($datareservation['cancel']=='1'){
                    $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($homestay_id,$unit_type,$unit_number,$id)->getRowArray();
                    $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id,$unit_type,$unit_number,$id)->getRow();
                }
                
                $total []= $total_price_homestay->price;
            }

            $data_price=$total;
            $tphom = array_sum($data_price);
            $tph=$tphom*$dayhome;
            $data_unit_booking=$unit_booking;

        } else{
            $data_unit_booking=[];
            $tph = '0';
        }

        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        // $check_in = "2023-10-29 11:51:00";
        $check_in = $datareservation['check_in'];
        $totday=max($getday);
        $day=$totday['day']-1;
        // Ubah $check_in menjadi objek DateTime untuk mempermudah perhitungan
        $check_in_datetime = new DateTime($check_in);
        if($day=='0'){
            $check_out = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
        } else {
            // Tambahkan jumlah hari
            $check_in_datetime->modify('+' . $day . ' days');
            // Atur waktu selalu menjadi 12:00:00
            $check_out = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
        }
        $data = [
            //data package
            'data_package' => $package,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day'=> $getday,
            'daypack'=> $daypack,
            'activity' => $combinedData,
            'detail' => $datareservation,

            //data homestay
            'title' => 'Reservation Homestay',
            'data' => $contents,
            'list_unit' => $list_unit,
            'date'=>$date,
            'dayhome'=> $dayhome,
            'check_out'=>$check_out,
            'data_unit'=>$booking_unit,
            'booking'=>$data_unit_booking,
            'price_home'=>$tph
        ];
        // dd($data);
        return view('web/detail-reservation-form', $data);
    }

    public function show($id=null)
    {
        $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
        $package_reservation= $datareservation['package_id'];
        
        //detail package 
        $package = $this->packageModel->get_package_by_id($package_reservation)->getRowArray();        
        $serviceinclude= $this->detailServicePackageModel->get_service_include_by_id($package_reservation)->getResultArray();
        $serviceexclude= $this->detailServicePackageModel->get_service_exclude_by_id($package_reservation)->getResultArray();
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_reservation)->getResultArray();
        $getday = $this->packageDayModel->get_day_by_package($package_reservation)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData($package_reservation);
        $day=max($getday);
        $daypack=$day['day'];
        $dayhome=$day['day']-1;

        //data homestay
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

        if($datareservation['cancel']=='0'){
            $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        } else if ($datareservation['cancel']=='1'){
            $booking_unit = $this->backupDetailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        }

        if(!empty($booking_unit)){
            $data_unit_booking=array();
            $data_price=array();
            foreach($booking_unit as $booking){
                $homestay_id=$booking['homestay_id'];
                $unit_type=$booking['unit_type'];
                $unit_number=$booking['unit_number'];
                $reservation_id=$booking['reservation_id'];

                if($datareservation['cancel']=='0'){
                    $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($homestay_id,$unit_type,$unit_number,$id)->getRowArray();
                $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id,$unit_type,$unit_number,$id)->getRow();
                } else if ($datareservation['cancel']=='1'){
                    $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($homestay_id,$unit_type,$unit_number,$id)->getRowArray();
                $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id,$unit_type,$unit_number,$id)->getRow();
                }
                
                $total []= $total_price_homestay->price;
            }

            $data_price=$total;
            $tphom = array_sum($data_price);
            $tph=$tphom*$dayhome;
            $data_unit_booking=$unit_booking;

        } else{
            $data_unit_booking=[];
            $tph = '0';
        }

        // $check_in = "2023-10-29 11:51:00";
        $check_in = $datareservation['check_in'];
        $totday=max($getday);
        $day=$totday['day']-1;
        // Ubah $check_in menjadi objek DateTime untuk mempermudah perhitungan
        $check_in_datetime = new DateTime($check_in);

        if($day=='0'){
            $check_out = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
        } else {
            // Tambahkan jumlah hari
            $check_in_datetime->modify('+' . $day . ' days');
            // Atur waktu selalu menjadi 12:00:00
            $check_out = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
        }
        // dd($booking_unit);
        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');
        $batas_dp_dt = $check_in_datetime->modify('-' . '3'. ' days');
        $batas_dp = $batas_dp_dt->format('Y-m-d H:i:s');

        $batas_cancel_dt = $check_in_datetime->modify('-' . '2'. ' days');
        $batas_cancel = $batas_cancel_dt->format('Y-m-d H:i:s');

        $data = [
            //data package
            'data_package' => $package,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day'=> $getday,
            'daypack'=> $daypack,
            'activity' => $combinedData,

            //data homestay
            'title' => 'Reservation Homestay',
            'data' => $contents,
            'detail' => $datareservation,
            'list_unit' => $list_unit,
            'date'=>$date,
            'dayhome'=> $dayhome,
            'check_out'=>$check_out,
            'batas_dp'=>$batas_dp,
            'batas_cancel'=>$batas_cancel,
            'data_unit'=>$booking_unit,
            'booking'=>$data_unit_booking,
            'price_home'=>$tph
        ];
        // dd($data);
        return view('web/detail-reservation-form', $data);
    }

    public function confirm($id=null)
    {
        $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
        $package_reservation= $datareservation['package_id'];
        
        //detail package 
        $package = $this->packageModel->get_package_by_id($package_reservation)->getRowArray();        
        $serviceinclude= $this->detailServicePackageModel->get_service_include_by_id($package_reservation)->getResultArray();
        $serviceexclude= $this->detailServicePackageModel->get_service_exclude_by_id($package_reservation)->getResultArray();
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_reservation)->getResultArray();
        $getday = $this->packageDayModel->get_day_by_package($package_reservation)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData($package_reservation);

        $day=max($getday);
        $daypack=$day['day'];
        $dayhome=$day['day']-1;

        //data homestay
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        if($datareservation['cancel']=='0'){
            $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        } else if ($datareservation['cancel']=='1'){
            $booking_unit = $this->backupDetailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        }

        if(!empty($booking_unit)){
            $data_unit_booking=array();
            $data_price=array();
            foreach($booking_unit as $booking){
                $homestay_id=$booking['homestay_id'];
                $unit_type=$booking['unit_type'];
                $unit_number=$booking['unit_number'];
                $reservation_id=$booking['reservation_id'];

                if($datareservation['cancel']=='0'){
                    $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($homestay_id,$unit_type,$unit_number,$id)->getRowArray();
                $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id,$unit_type,$unit_number,$id)->getRow();
                } else if ($datareservation['cancel']=='1'){
                    $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($homestay_id,$unit_type,$unit_number,$id)->getRowArray();
                $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id,$unit_type,$unit_number,$id)->getRow();
                }
                
                $total []= $total_price_homestay->price;
            }

            $data_price=$total;

            $tphom = array_sum($data_price);
            $tph=$tphom*$dayhome;
            $data_unit_booking=$unit_booking;

        } else{
            $data_unit_booking=[];
            $tph = '0';
        }

        // dd($booking_unit);
        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $check_in = $datareservation['check_in'];
        $totday=max($getday);
        $day=$totday['day']-1;
        // Ubah $check_in menjadi objek DateTime untuk mempermudah perhitungan
        $check_in_datetime = new DateTime($check_in);
        
        if($day=='0'){
            $check_out = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
        } else {
            // Tambahkan jumlah hari
            $check_in_datetime->modify('+' . $day . ' days');
            // Atur waktu selalu menjadi 12:00:00
            $check_out = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
        }

        $batas_dp_dt = $check_in_datetime->modify('-' . '3'. ' days');
        $batas_dp = $batas_dp_dt->format('Y-m-d H:i:s');

        $data = [
            //data package
            'data_package' => $package,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day'=> $getday,
            'daypack'=> $daypack,
            'activity' => $combinedData,

            //data homestay
            'title' => 'Reservation Homestay',
            'data' => $contents,
            'detail' => $datareservation,
            'list_unit' => $list_unit,
            'date'=>$date,
            'check_out'=>$check_out,
            'batas_dp'=>$batas_dp,
            'dayhome'=> $dayhome,
            'data_unit'=>$booking_unit,
            'booking'=>$data_unit_booking,
            'price_home'=>$tph
        ];
        // dd($data);
        return view('dashboard/detail-reservation-confirm', $data);
    }

    public function saveconfirm($id = null)
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d H:i');

        $requestData = [
            'status' => $request['status'],
            'confirmation_date'=>$date,
            'feedback' => $request['feedback'],
        ];

        // dd($requestData);
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateDR = $this->reservationModel->update_reservation($id, $requestData);

        if ($updateDR) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function savecancel($id = null)
    {
        $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();

        if($booking_unit!=null){
            foreach ($booking_unit as $data_unit){
                $date=$data_unit['date'];
                $unit_number=$data_unit['unit_number'];
                $homestay_id=$data_unit['homestay_id'];
                $unit_type=$data_unit['unit_type'];
                $reservation_id=$data_unit['reservation_id'];
                $array = array('date' => $date,'unit_number' => $unit_number,'homestay_id' => $homestay_id, 'unit_type' => $unit_type, 'reservation_id'=>$reservation_id);

                $addBDE= $this->backupDetailReservationModel->add_backup($array);
                $deletefromDE = $this->detailReservationModel->where($array)->delete();
            }
        } 

        $requestData = [
            'cancel' =>  '1',
        ];
        $updateDR = $this->reservationModel->update_cancel($id, $requestData);

        if ($updateDR) {
            session()->setFlashdata('success', 'Reservasi telah di cancel');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }

    }

    public function saverefund($id = null)
    {
        $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();

        if($booking_unit!=null){
            foreach ($booking_unit as $data_unit){
                $date=$data_unit['date'];
                $unit_number=$data_unit['unit_number'];
                $homestay_id=$data_unit['homestay_id'];
                $unit_type=$data_unit['unit_type'];
                $reservation_id=$data_unit['reservation_id'];
                $array = array('date' => $date,'unit_number' => $unit_number,'homestay_id' => $homestay_id, 'unit_type' => $unit_type, 'reservation_id'=>$reservation_id);

                $addBDE= $this->backupDetailReservationModel->add_backup($array);
                $deletefromDE = $this->detailReservationModel->where($array)->delete();
            }
        } 
        $request = $this->request->getPost();

        $requestData = [
            'cancel' =>  $request['cancel'],
            'account_refund' => $request['account_refund'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
        $updateR = $this->reservationModel->update_cancel($id, $requestData);

        if ($updateR) {
            session()->setFlashdata('success', 'Reservasi telah di cancel dan refund diajukan');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }


    public function create()
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d');

        $reservation_id = $request['reservation_id'];
        $pk_unit = $request['pk_unit'];
        $array = explode("-", $pk_unit);
        
        $check_in = date('Y-m-d', strtotime($request['check_in_timestamp'])); 
        $check_out = date('Y-m-d', strtotime($request['check_out_timestamp'])); 

        $date_booking = array(); // Array untuk menyimpan tanggal-tanggal booking
        $current_date = $check_in;
        while (strtotime($current_date) < strtotime($check_out)) {
            $date_booking[] = date('Y-m-d', strtotime($current_date)); // Menambahkan tanggal ke dalam array
            $current_date = date('Y-m-d', strtotime($current_date . " +1 day")); // Menambah 1 hari ke tanggal saat ini
        }

        foreach ($date_booking as $db){
            $requestData = [
                'date' => $db,
                'homestay_id' => $array[0],
                'unit_type' => $array[1],
                'unit_number' => $array[2],
                'reservation_id' => $reservation_id
            ];

            foreach ($requestData as $key => $value) {
                if (empty($value)) {
                    unset($requestData[$key]);
                }
            }

            $checkExistingData = $this->detailReservationModel->checkIfDataExists($requestData);

            if(!$checkExistingData){
                $addDR = $this->detailReservationModel->add_new_detail_reservation($requestData);
            } else{
                $addDR = null;
            }
        }

        if ($addDR==null) {
            session()->setFlashdata('failed', 'Unit homestay tersebut sudah dibooking.');

            return redirect()->back()->withInput();
        }elseif ($addDR) {
            session()->setFlashdata('success', 'Unit homestay tersebut berhasil ditambahkan.');

            $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($requestData['unit_number'],$requestData['homestay_id'], $requestData['unit_type'])->getRowArray();
            $datareservation = $this->reservationModel->get_reservation_by_id($requestData['reservation_id'])->getRowArray();
            $getday = $this->packageDayModel->get_day_by_package($datareservation['package_id'])->getResultArray();


            $day=max($getday);
            $daypack=$day['day'];
            $dayhome=$day['day']-1;
            $tph=$dayhome*$data_unit['price'];

            $new_price = $datareservation['total_price']+$tph;
            $new_deposit= $new_price*0.2;

            $id=$requestData['reservation_id'];
            $requestData=[
                'total_price' => $new_price,
                'deposit' => $new_deposit,
            ];

            // dd($id, $requestData);
            $updateR = $this->reservationModel->update_reservation($id, $requestData);

            return redirect()->back();
        } 

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

        $updateDR = $this->detailReservationModel->add_new_detail_reservation($id, $requestData);

        if ($updateDR) {
            return redirect()->to(base_url('web/reservation') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

   
    public function deleteday($package_id=null, $day=null, $description=null)
    {
        $request = $this->request->getPost();

        $package_id=$request['package_id'];
        $day=$request['day'];
        $description=$request['description'];

        $array1 = array('package_id' => $package_id, 'day' => $day);
        $detailPackage = $this->detailPackageModel->where($array1)->find();
        $deleteDP= $this->detailPackageModel->where($array1)->delete();

        if ($deleteDP) {
            //jika success
            $array2 = array('package_id' => $package_id, 'day' => $day,'description'=>$description);
            $packageDay = $this->packageDayModel->where($array2)->find();
            // dd($packageDay);
            $deletePD= $this->packageDayModel->where($array2)->delete();

            if($deletePD){
                session()->setFlashdata('pesan', 'Activity "'.$description.'" Berhasil di Hapus.');

                $package = $this->packageModel->get_package_by_id($package_id)->getRowArray();
                $package_id=$package['id'];
                $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();
                $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id, $packageDay)->getResultArray();
                
                $data = [
                    'title' => 'New Detail Package',
                    'data' => $package,
                    'day' => $packageDay,
                    'activity' => $detailPackage
                ];  

                return redirect()->back();
            }
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


    public function delete($package_id=null, $day=null, $activity=null, $description=null)
    {
        $request = $this->request->getPost();

        $day=$request['day'];
        $activity=$request['activity'];
        $description=$request['description'];

        $array = array('package_id' => $package_id, 'day' => $day, 'activity' => $activity);
        $detailPackage = $this->detailPackageModel->where($array)->find();
        $deleteDP= $this->detailPackageModel->where($array)->delete();

        if ($deleteDP) {
            session()->setFlashdata('pesan', 'Activity "'.$description.'" Berhasil di Hapus.');
            //jika success
            $package = $this->packageModel->get_package_by_id($package_id)->getRowArray();

            // $package_id=$package['id'];
            
            $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();

            // dd($packageDay);
            // foreach ($packageDay as $item):
                // $dayp=$item['day'];
                $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id, $packageDay)->getResultArray();
            
                $data = [
                    'title' => 'New Detail Package',
                    'data' => $package,
                    'day' => $packageDay,
                    'activity' => $detailPackage
                ];  

            // endforeach;
            return redirect()->back();

            // return view('dashboard/detail-package-form', $data, $package, $packageDay, $detailPackage);

        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Package not found"
                ]
            ];
            return $this->failNotFound($response);
    }

        
        // return redirect()->to('/packageday/P0014');
    }


    public function deleteunit ($homestay_id=null, $unit_type=null, $unit_number=null, $reservation_id=null)
    {
        $request = $this->request->getPost();

        $date=$request['date'];
        $homestay_id=$request['homestay_id'];
        $unit_type=$request['unit_type'];
        $unit_number=$request['unit_number'];
        $reservation_id=$request['reservation_id'];
        $description=$request['description'];

        $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($date,$unit_number,$homestay_id, $unit_type)->getRowArray();

        $array = array('date' => $date,'unit_number' => $unit_number,'homestay_id' => $homestay_id, 'unit_type' => $unit_type);
        $bookingunit= $this->detailReservationModel->where($array)->find();
        $deleteBU= $this->detailReservationModel->where($array)->delete();

        if ($deleteBU) {
            session()->setFlashdata('pesan', 'Unit Berhasil di Hapus.');
            
            $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($unit_number, $homestay_id, $unit_type)->getRowArray();
            $datareservation = $this->reservationModel->get_reservation_by_id($reservation_id)->getRowArray();
            $getday = $this->packageDayModel->get_day_by_package($datareservation['package_id'])->getResultArray();


            $day=max($getday);
            $dayhome=$day['day']-1;
            $tph=$dayhome*$data_unit['price'];

            $new_price = $datareservation['total_price']-$tph;
            $new_deposit= $new_price*0.2;

            $id=$reservation_id;
            $requestData=[
                'total_price' => $new_price,
                'deposit' => $new_deposit,
            ];

            // dd($id, $requestData);
            $updateR = $this->reservationModel->update_reservation($id, $requestData);

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

    public function review($id=null)
    {
        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
        $package_reservation= $datareservation['package_id'];
        
        //detail package 
        $package = $this->packageModel->get_package_by_id($package_reservation)->getRowArray();        
        $getday = $this->packageDayModel->get_day_by_package($package_reservation)->getResultArray();

        //data homestay
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        $booking_unit = $this->detailReservationModel->get_unit_homestay_booking($id)->getResultArray();

        if(!empty($booking_unit)){
            foreach($booking_unit as $booking){
                $homestay_id=$booking['homestay_id'];
                $unit_type=$booking['unit_type'];
                $unit_number=$booking['unit_number'];
                $reservation_id=$booking['reservation_id'];

                $data_unit_booking = $this->detailReservationModel->get_unit_homestay_booking_data_reservation($homestay_id,$unit_type,$unit_number,$reservation_id)->getResultArray();
            }
        } else{
            $data_unit_booking=[];
        }

        // dd($booking_unit);
        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $check_in = $datareservation['check_in'];
        $totday=max($getday);
        $day=$totday['day']-1;
        // Ubah $check_in menjadi objek DateTime untuk mempermudah perhitungan
        $check_in_datetime = new DateTime($check_in);
        if($day=='0'){
            $check_out = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
        } else {
            // Tambahkan jumlah hari
            $check_in_datetime->modify('+' . $day . ' days');
            // Atur waktu selalu menjadi 12:00:00
            $check_out = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
        }

        $data = [
            'title' => 'Review Package and Homestay',
            'data_package' => $package,
            'detail' => $datareservation,
            'check_out' => $check_out,
            'data_unit'=>$booking_unit,
            'booking'=>$data_unit_booking,
        ];
        // dd($data);
        return view('web/review-reservation-form', $data);
    }

    public function savereview($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'rating' => $request['rating'],
            'review' => $request['review'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
                
        $updateR = $this->reservationModel->update_reservation($id, $requestData);

        if ($updateR) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function savereviewunit($reservation_id = null)
    {
        $request = $this->request->getPost();
        // $date=$request['date'];
        $reservation_id=$request['reservation_id'];
        $unit_number=$request['unit_number'];
        $homestay_id=$request['homestay_id'];
        $unit_type=$request['unit_type'];

        $requestData = [
            'rating' => $request['rating'],
            'review' => $request['review'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
        
        $updateDR = $this->detailReservationModel->update_detailreservation($reservation_id, $unit_number, $homestay_id, $unit_type, $requestData);

        if ($updateDR) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }
}

