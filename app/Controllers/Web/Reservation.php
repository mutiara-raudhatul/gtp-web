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
        $user=user()->username;
        $contents = $this->reservationModel->get_list_reservation_by_user($user)->getResultArray();
        $data = [
            'title' => 'Reservation',
            'data' => $contents,
        ];

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
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        $id = $this->packageModel->get_new_id();
        $data = [
            'title' => 'New Reservation',
            'data' => $contents,
            'list_unit' => $list_unit,
            'custom_id' =>$id
        ];

        return view('web/reservation-form', $data);
    }

    public function custombooking($id)
    {
        $contents = $this->packageModel->get_package_by_id_custom($id)->getResultArray();
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

        foreach ($contents as $con){
            if($con['days']==null) {
                $session = session();
                $session->setFlashdata('warning', 'Belum ada aktivitas package.');

                return redirect()->back();
            } else {
                $data = [
                    'title' => 'Reservation of Package',
                    'data' => $contents,
                    'list_unit' => $list_unit,
                ];
                
                return view('web/reservation-custom-form', $data);
            }
        }

    }

    public function packagecustom()
    {
        $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
dd($contents);
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

        $data = [
            'title' => 'Custom Package',
            'data' => $contents,
            'list_unit' => $list_unit,
        ];

        return view('web/custom-package-form', $data);
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
        $date = date('Y-m-d H:i');
        $requestData = [
            'id' => $id,
            'user_id' => user()->id,
            'package_id' => $request['package'],
            'request_date' => $date,
            'total_people' => $request['total_people'],
            'check_in' => $request['check_in'].' '.$request['time_check_in'],
            'check_out' => $request['check_out'].' '.$request['time_check_out'],
            'total_price' => $request['total_price'],
            'deposit' => $request['deposit']
        ];
// dd($requestData);
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addRe = $this->reservationModel->add_new_reservation($requestData);

        if ($addRe) {
            return redirect()->to(base_url('web/detailreservation/addhome/'.$id));
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


    public function uploaddeposit($id = null)
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d H:i');

        $requestData = [
            'deposit_date' => $date,
        ];
        foreach ($requestData as $key => $value) {
            if(empty($value)) {
                unset($requestData[$key]);
            }
        }
        $img = $this->request->getFile('proof_of_deposit');

        if (empty($_FILES['proof_of_deposit']['name'])) {
            $query = $this->reservationModel->upload_deposit($id, $requestData);
            if ($query) {
                $response = [
                    'status' => 200,
                    'message' => [
                        "Success upload deposit"
                    ]
                ];
                return redirect()->back();
            }
            $response = [
                'status' => 400,
                'message' => [
                    "Fail upload deposit"
                ]
            ];
            return $this->respond($response, 400);
        } else {

            $validationRule = [
                'proof_of_deposit' => [
                    'label' => 'proof_of_deposit File',
                    'rules' => 'uploaded[proof_of_deposit]'
                        . '|is_image[proof_of_deposit]'
                        . '|mime_in[proof_of_deposit,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                ],
            ];
            if (!$this->validate($validationRule) && !empty($_FILES['proof_of_deposit']['name'])) {
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload deposit "
                    ]
                ];
                return $this->respond($response, 400);
            }
    
            if ($img->isValid() && !$img->hasMoved()) {
                $filepath = WRITEPATH . 'uploads/' . $img->store();
                $user_image = new File($filepath);
                $user_image->move(FCPATH . 'media/photos/deposit');
                $requestData['proof_of_deposit'] = $user_image->getFilename();
        
                $query = $this->reservationModel->upload_deposit($id, $requestData);
                if ($query) {
                    $response = [
                        'status' => 200,
                        'message' => [
                            "Success upload deposit image"
                        ]
                    ];
                    return redirect()->back();
                    
                }
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload deposit"
                    ]
                ];
                return $this->respond($response, 400);
        
            }
        }
        $response = [
            'status' => 400,
            'message' => [
                "Fail upload deposit."
            ]
        ];
        return $this->respond($response, 400);
    }

    public function uploadfullpayment($id = null)
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d H:i');

        $requestData = [
            'payment_date' => $date,
        ];
        foreach ($requestData as $key => $value) {
            if(empty($value)) {
                unset($requestData[$key]);
            }
        }
        $img = $this->request->getFile('proof_of_payment');

        if (empty($_FILES['proof_of_payment']['name'])) {
            $query = $this->reservationModel->upload_fullpayment($id, $requestData);
            if ($query) {
                $response = [
                    'status' => 200,
                    'message' => [
                        "Success upload full payment"
                    ]
                ];
                return redirect()->back();
            }
            $response = [
                'status' => 400,
                'message' => [
                    "Fail upload full payment"
                ]
            ];
            return $this->respond($response, 400);
        } else {

            $validationRule = [
                'proof_of_payment' => [
                    'label' => 'proof_of_payment File',
                    'rules' => 'uploaded[proof_of_payment]'
                        . '|is_image[proof_of_payment]'
                        . '|mime_in[proof_of_payment,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                ],
            ];
            if (!$this->validate($validationRule) && !empty($_FILES['proof_of_payment']['name'])) {
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload full payment "
                    ]
                ];
                return $this->respond($response, 400);
            }
    
            if ($img->isValid() && !$img->hasMoved()) {
                $filepath = WRITEPATH . 'uploads/' . $img->store();
                $user_image = new File($filepath);
                $user_image->move(FCPATH . 'media/photos/fullpayment');
                $requestData['proof_of_payment'] = $user_image->getFilename();
        
                $query = $this->reservationModel->upload_fullpayment($id, $requestData);
                if ($query) {
                    $response = [
                        'status' => 200,
                        'message' => [
                            "Success upload full payment image"
                        ]
                    ];
                    return redirect()->back();
                    
                }
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload full payment"
                    ]
                ];
                return $this->respond($response, 400);
        
            }
        }
        $response = [
            'status' => 400,
            'message' => [
                "Fail upload fullpayment."
            ]
        ];
        return $this->respond($response, 400);
    }

}
