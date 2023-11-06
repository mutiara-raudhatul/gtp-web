<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'reservation';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'user_id', 'request_date','check_in', 'total_people', 'deposite', 'total_price', 'status', 'note', 'feedback', 'rating'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function get_list_reservation() {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`, `package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.proof_of_deposit`,`reservation.proof_of_payment`,`reservation.status`,`reservation.confirmation_date`,`reservation.review`,`reservation.rating`,`reservation.note`,`reservation.feedback`,`reservation.account_refund`,`reservation.proof_refund`,`users.username` ")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->orderBy('reservation.request_date', 'DESC')
            ->get();
        return $query;
    }

    public function get_list_reservation_report() {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`, `package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.deposit`,`reservation.total_price`,`reservation.proof_of_deposit`,`reservation.proof_of_payment`,`reservation.status`,`reservation.confirmation_date`,`reservation.review`,`reservation.rating`,`reservation.note`,`reservation.feedback`,`reservation.account_refund`,`reservation.proof_refund`,`users.username` ")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->orderBy('reservation.request_date', 'DESC')
            ->where('reservation.total_price <>', '0')
            ->where('reservation.status', '1')
            ->get();

        return $query;
    }

    public function sum_done_deposit() {
        $query = $this->db->table($this->table)
            ->selectSUM('deposit', 'deposit')
            ->where('proof_of_deposit <>', null)
            ->where('proof_refund', null)
            ->where('proof_of_payment', null)
            ->where('total_price <>', '0')
            ->where('status', '1')
            ->get();
        return $query;
    }

    public function sum_done_refund() {
        $query = $this->db->table($this->table)
            ->selectSUM('deposit', 'refund')
            ->where('proof_of_deposit <>', null)
            ->where('proof_refund <>', null)
            ->where('total_price <>', '0')
            ->where('status', '1')
            ->get();
        return $query;
    }

    public function sum_done_total() {
        $query = $this->db->table($this->table)
            ->selectSUM('total_price', 'total_price')
            ->where('proof_of_payment <>', null)
            ->where('total_price <>', '0')
            ->where('status', '1')
            ->get();

        return $query;
    }

    public function get_list_reservation_by_user($username) {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`, `package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.proof_of_deposit`,`reservation.proof_of_payment`,`reservation.status`,`reservation.confirmation_date`,`reservation.review`,`reservation.rating`,`reservation.note`,`reservation.feedback`,`reservation.account_refund`,`reservation.proof_refund`,`users.username` ")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->orderBy('reservation.request_date', 'DESC')
            ->where('users.username', $username)
            ->get();

        return $query;
    }

    public function get_reservation_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `reservation.package_id`,`package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.status`, `reservation.total_price`, `reservation.total_people`, `reservation.deposit`, `reservation.proof_of_deposit`, `reservation.deposit_date`,`reservation.proof_of_payment`,`reservation.payment_date`,`reservation.confirmation_date`,`reservation.note`, `reservation.feedback`,`reservation.cancel`,`reservation.account_refund`,`reservation.proof_refund`,`reservation.review`,`reservation.rating`,`users.username` ")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->where('reservation.id', $id)
            ->get();
        return $query;
    }

    public function getReview($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `reservation.package_id`,`package.name`,`reservation.review`,`reservation.rating`,`users.username` ")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->where('reservation.package_id', $id)
            ->where('reservation.rating <>', 0) 
            ->get();
        return $query;
    }
    
    public function getRating($id = null)
    {
        $query = $this->db->table($this->table)
            ->selectAVG('reservation.rating', 'rating')
            ->where('reservation.package_id', $id)
            ->where('reservation.rating <>', 0) 
            ->get();
        return $query;
    }

    public function get_reservation_package_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `reservation.package_id`,`package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.status`, `reservation.total_price`, `reservation.total_people`, `reservation.deposit`, `reservation.proof_of_deposit`, `reservation.deposit_date`,`reservation.proof_of_payment`,`reservation.payment_date`,`reservation.confirmation_date`,`reservation.note`, `reservation.feedback`, `reservation.account_refund`,`reservation.proof_refund`,`users.username` ")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->where('reservation.id', $id)
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if(empty($lastId)){
            $id='R0001';
        }else{
        $count = (int)substr($lastId['id'], 3);
        $id = sprintf('R%04d', $count + 1);
        }
        return $id;
    }

    public function add_new_reservation($reservation = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($reservation);
        return $insert;
    }

    public function update_reservation($id = null, $data = null) {
        $query = $this->db->table('reservation')
            ->update($data, ['id' => $id]);
        return $query;
    }

    public function update_cancel($id = null, $data = null) {

        $query = $this->db->table('reservation')
                        ->set($data)
                        ->where('id', $id)
                        ->update();
    
        return $query;
    }

    public function upload_deposit($id = null, $data = null) {
        $query = $this->db->table('reservation')
            ->update($data, ['id' => $id]);
        return $query;
    }

    public function upload_fullpayment ($id = null, $data = null) {
        $query = $this->db->table('reservation')
            ->update($data, ['id' => $id]);
        return $query;
    }


    // public function upload_deposit($id = null, $deposit = null)
    // {
    //     foreach ($deposit as $key => $value) {
    //         if (empty($value)) {
    //             unset($deposit[$key]);
    //         }
    //     }
    //     $queryIns = $this->add_new_gallery($id, $deposit);
    //     return $queryIns;
    // }

}
