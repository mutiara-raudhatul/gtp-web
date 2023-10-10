<?php

namespace App\Controllers\Web;

use App\Models\ReservationModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class ManageReservation extends ResourcePresenter
{
    protected $reservationModel;

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
            'title' => 'Manage Reservation',
            'data' => $contents,
        ];

        // dd($data);

        return view('dashboard/reservation', $data);
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

        // dd($data);
        // if (url_is('*dashboard*')) {
            return view('dashboard/detail_reservation', $data);
        // }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */

}
