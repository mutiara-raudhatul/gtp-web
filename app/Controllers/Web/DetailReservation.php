<?php

namespace App\Controllers\Web;

use App\Models\DetailReservationModel;
use App\Models\ReservationModel;
use App\Models\UnitHomestayModel;
use App\Models\PackageModel;
use App\Models\DetailPackageModel;
use App\Models\detailServicePackageModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class DetailReservation extends ResourcePresenter
{
    protected $detailReservationModel;
    protected $reservationModel;
    protected $unitHomestayModel;
    protected $packageModel;
    protected $detailPackageModel;
    protected $detailServicePackageModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->detailReservationModel = new DetailReservationModel();
        $this->reservationModel = new ReservationModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->packageModel = new PackageModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();
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
        $package_reservation= $datareservation['package_id'];
        
        //detail package 
        $package = $this->packageModel->get_package_by_id($package_reservation)->getRowArray();        
        $serviceinclude= $this->detailServicePackageModel->get_service_include_by_id($package_reservation)->getResultArray();
        $serviceexclude= $this->detailServicePackageModel->get_service_exclude_by_id($package_reservation)->getResultArray();
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_reservation)->getResultArray();
        $getday = $this->detailPackageModel->get_day_by_package($package_reservation)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData();

        //data homestay
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        $booking_unit = $this->detailReservationModel->get_unit_homestay_booking($id)->getResultArray();

        if(!empty($booking_unit)){
            foreach($booking_unit as $booking){
                $homestay_id=$booking['homestay_id'];
                $unit_type=$booking['unit_type'];
                $unit_number=$booking['unit_number'];
                $reservation_id=$booking['reservation_id'];

                $data_unit_booking = $this->detailReservationModel->get_unit_homestay_booking_data($homestay_id,$unit_type,$unit_number,$reservation_id)->getResultArray();

                $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id,$unit_type,$unit_number,$reservation_id)->getRow();
                $tph = $total_price_homestay->tot_price_hom;
            }
        } else{
            $data_unit_booking=[];
            $tph = '0';
        }

        // dd($booking_unit);
        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $data = [
            //data package
            'data_package' => $package,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day'=> $getday,
            'activity' => $combinedData,
            'detail' => $datareservation,

            //data homestay
            'title' => 'Reservation Homestay',
            'data' => $contents,
            'list_unit' => $list_unit,
            'date'=>$date,
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
        $getday = $this->detailPackageModel->get_day_by_package($package_reservation)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData();

        //data homestay
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        $booking_unit = $this->detailReservationModel->get_unit_homestay_booking($id)->getResultArray();

        if(!empty($booking_unit)){
            foreach($booking_unit as $booking){
                $homestay_id=$booking['homestay_id'];
                $unit_type=$booking['unit_type'];
                $unit_number=$booking['unit_number'];
                $reservation_id=$booking['reservation_id'];

                $data_unit_booking = $this->detailReservationModel->get_unit_homestay_booking_data($homestay_id,$unit_type,$unit_number,$reservation_id)->getResultArray();

                $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id,$unit_type,$unit_number,$reservation_id)->getRow();
                $tph = $total_price_homestay->tot_price_hom;
            }
        } else{
            $data_unit_booking=[];
            $tph = '0';
        }

        // dd($booking_unit);
        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $data = [
            //data package
            'data_package' => $package,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day'=> $getday,
            'activity' => $combinedData,

            //data homestay
            'title' => 'Reservation Homestay',
            'data' => $contents,
            'detail' => $datareservation,
            'list_unit' => $list_unit,
            'date'=>$date,
            'data_unit'=>$booking_unit,
            'booking'=>$data_unit_booking,
            'price_home'=>$tph
        ];
        // dd($data);
        return view('web/detail-reservation-form', $data);
    }

    public function create()
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d');

        $reservation_id = $request['reservation_id'];
        $pk_unit = $request['pk_unit'];
        $array = explode("-", $pk_unit);

        $requestData = [
            'date' => $date,
            'reservation_id' => $reservation_id,
            'homestay_id' => $array[0],
            'unit_type' => $array[1],
            'unit_number' => $array[2],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addDR = $this->detailReservationModel->add_new_detail_reservation($requestData);

        if ($addDR) {
            $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($requestData['homestay_id'], $requestData['unit_type'], $requestData['unit_number'])->getRowArray();
            $datareservation = $this->reservationModel->get_reservation_by_id($requestData['reservation_id'])->getRowArray();

            // dd($datareservation['deposit']);

            $new_price = $datareservation['total_price']+$data_unit['price'];
            $new_deposit= $new_price/2;

            $id=$requestData['reservation_id'];
            $requestData=[
                'total_price' => $new_price,
                'deposit' => $new_deposit,
            ];

            // dd($id, $requestData);
            $updateR = $this->reservationModel->update_reservation($id, $requestData);

            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
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

    public function delete ($date=null, $homestay_id=null, $unit_type=null, $unit_number=null, $reservation_id=null)
    {
        $request = $this->request->getPost();

        $date=$request['date'];
        $homestay_id=$request['homestay_id'];
        $unit_type=$request['unit_type'];
        $unit_number=$request['unit_number'];
        $reservation_id=$request['reservation_id'];
        $description=$request['description'];

        $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($homestay_id, $unit_type, $unit_number)->getRowArray();

        $array = array('date' => $date,'homestay_id' => $homestay_id, 'unit_type' => $unit_type,'unit_number' => $unit_number);
        $bookingunit= $this->detailReservationModel->where($array)->find();
        $deleteBU= $this->detailReservationModel->where($array)->delete();

        if ($deleteBU) {
            session()->setFlashdata('pesan', 'Unit Berhasil di Hapus.');
            
            $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($homestay_id, $unit_type, $unit_number)->getRowArray();
            $datareservation = $this->reservationModel->get_reservation_by_id($reservation_id)->getRowArray();
            // dd($datareservation);

            // dd($datareservation['deposit']);

            $new_price = $datareservation['total_price']-$data_unit['price'];
            $new_deposit= $new_price/2;

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

    // public function delete($package_id,$day,$activity)
    // {
    //     dd($package_id,$day,$activity);
    //     // cari gambar berdasarkan id
    //     // $komik = $this->komikModel->find($id); 

    //     // // cek jika file gambarnya default.jpg
    //     // if ($komik['sampul'] != 'default.png') {
    //     //     // hapus gambar
    //     //     unlink('img/' . $komik['sampul']);
    //     // }

    //     $this->detailPackageModel->delete($package_id,$day,$activity);
    //     session()->setFlashdata('pesan', 'Data Berhasil di Hapus.');
    //     return redirect()->to('/packageday/P0014');
    // }
}
