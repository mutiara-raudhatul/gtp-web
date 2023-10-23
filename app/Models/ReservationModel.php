<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'reservation';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'user_id', 'request_date','check_in', 'check_out', 'total_people', 'deposite', 'total_price', 'status', 'comment', 'rating'];

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
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`, `package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.check_out`, `reservation.proof_of_deposit`,`reservation.proof_of_payment`,`reservation.status`,`reservation.confirmation_date`,`reservation.review`,`reservation.rating`,`reservation.comment`,`users.username` ")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->orderBy('reservation.request_date', 'DESC')
            ->get();

        return $query;
    }

    public function get_list_reservation_by_user($username) {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`, `package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.check_out`, `reservation.proof_of_deposit`,`reservation.proof_of_payment`,`reservation.status`,`reservation.confirmation_date`,`reservation.review`,`reservation.rating`,`reservation.comment`,`users.username` ")
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
            ->select("`reservation.id`, `reservation.user_id`, `reservation.package_id`,`package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.check_out`, `reservation.status`, `reservation.total_price`, `reservation.total_people`, `reservation.deposit`, `reservation.proof_of_deposit`, `reservation.deposit_date`,`reservation.proof_of_payment`,`reservation.payment_date`,`reservation.confirmation_date`,`reservation.comment`,`reservation.review`,`reservation.rating`,`users.username` ")
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
            ->select("`reservation.id`, `reservation.user_id`, `reservation.package_id`,`package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.check_out`, `reservation.status`, `reservation.total_price`, `reservation.total_people`, `reservation.deposit`, `reservation.proof_of_deposit`, `reservation.deposit_date`,`reservation.proof_of_payment`,`reservation.payment_date`,`reservation.confirmation_date`,`reservation.comment`,`users.username` ")
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
            $id='R0000001';
        }else{
        $count = (int)substr($lastId['id'], 3);
        $id = sprintf('R%07d', $count + 1);
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
