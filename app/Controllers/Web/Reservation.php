<?php

namespace App\Controllers\Web;

use App\Models\ReservationModel;
use App\Models\UnitHomestayModel;
use App\Models\PackageModel;
use App\Models\PackageDayModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Reservation extends ResourcePresenter
{
    protected $reservationModel;
    protected $unitHomestayModel;
    protected $packageModel;
    protected $packageDayModel;


    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->packageModel = new PackageModel();
        $this->packageDayModel = new PackageDayModel();

    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->reservationModel->get_list_reservation()->getResultArray();
        $data = [
            'title' => 'Reservation',
            'data' => $contents,
        ];

        // dd($data);

        return view('web/reservation', $data);
    }

    public function show($id = null)
    {
        $detail_reservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();

        if (empty($detail_reservation)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $detail_reservation['username'],
            'data' => $detail_reservation,
        ];

        // if (url_is('*dashboard*')) {
            return view('web/detailreservation', $data);
        // }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        $contents = $this->packageModel->get_list_package_distinct()->getResultArray();

        // dd($contents);
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

// dd($list_unit);
        // // Periksa apakah ada hasil
        // if ($list_unit > 0) {
        //     // Siapkan opsi untuk pilihan unit homestay
        //     $options = '<option value="">Select Unit Homestay</option>';
        //     foreach($list_unit as $item){
        //         $homestay_id = $item['homestay_id'];
        //         $unit_type = $item['unit_type'];
        //         $unit_number = $item['unit_number'];
        //         $nama_homestay = $item['name'];
        //         $nama_unit = $item['nama_unit'];
        //         $name_type = $item['name_type'];
        //         $price = $item['price'];
        //         $capacity = $item['capacity'];

        //         // Tambahkan opsi ke variabel $options
        //         $options .= "<option value='$homestay_id' data-price='$price'>Homestay $nama_homestay - $name_type - $unit_number - $nama_unit(Capacity: $capacity)</option>";
        //     }
        //     echo $options;

        // } else {
        //     echo '<option value="">No Units Available</option>';
        // }

        $data = [
            'title' => 'New Reservation',
            'data' => $contents,
            'list_unit' => $list_unit,
            // 'options' => $options,
        ];

        // dd($data);
        return view('web/reservation-form', $data);
    }

    public function dataunithomestay()
    {

            $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

            // Periksa apakah ada hasil
            if ($list_unit > 0) {
                // Siapkan opsi untuk pilihan unit homestay
                $options = '<option value="">Select Unit Homestay</option>';
                while ($row = $list_unit->fetch_assoc()) {
                    $homestay_id = $row['homestay_id'];
                    $unit_type = $row['unit_type'];
                    $unit_number = $row['unit_number'];
                    $price = $row['price'];
                    $capacity = $row['capacity'];

                    // Tambahkan opsi ke variabel $options
                    $options .= "<option value='$homestay_id' data-price='$price'>$unit_type - $unit_number (Capacity: $capacity)</option>";
                }
                echo $options;
            } else {
                echo '<option value="">No Units Available</option>';
            }

        // $data = [
        //     'title' => 'Homestay',
        //     'data' => $list_unit,
        // ];

        // // dd($data);
        // return view('dashboard/reservation-form', $data);
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

        $id = $this->reservationModel->get_new_id();

        $requestData = [
            'id' => $id,
            'user_id' => user()->id,
            'package_id' => $request['package'],
            'total_people' => $request['total_people'],
            'check_in' => $request['check_in'].' '.$request['time_check_in'],
            'check_out' => $request['check_out'].' '.$request['time_check_out'],
            'total_price' => $request['total_price'],
            'deposit' => $request['deposit'],
            'status_id' => '1',
        ];
// dd($requestData);
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addRe = $this->reservationModel->add_new_reservation($requestData);

        if ($addRe) {
            return redirect()->to(base_url('web/detailreservation/edit/'.$id));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $contents = $this->packageModel->get_list_package()->getResultArray();

        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();

        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        
        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $data = [
            'title' => 'Reservation Homestay',
            'data' => $contents,
            'detail' => $datareservation,
            'list_unit' => $list_unit,
            'date'=>$date
        ];
        
        return view('web/reservation-form', $data);
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
