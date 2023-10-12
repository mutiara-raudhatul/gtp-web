<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'reservation';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'user_id', 'request_date','check_in', 'check_out', 'total_people', 'deposite', 'total_price', 'status_id', 'comment', 'rating'];

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
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`, `package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.check_out`, `reservation.status_id`,`status_reservation.status`")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->join('status_reservation', 'reservation.status_id = status_reservation.id')
            ->orderBy('reservation.id', 'ASC')
            ->get();

        return $query;
    }

    public function get_reservation_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `reservation.package_id`,`package.name`, `reservation.request_date`, `reservation.check_in`, `reservation.check_out`, `reservation.status_id`, `reservation.total_price`, `reservation.total_people`, `reservation.deposit`,`status_reservation.status`,`users.username` ")
            ->join('package', 'reservation.package_id = package.id')
            ->join('status_reservation', 'reservation.status_id = status_reservation.id')
            ->join('users', 'reservation.user_id = users.id')
            ->where('reservation.id', $id)
            ->get();
        return $query;
    }
    
    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        $count = (int)substr($lastId['id'], 3);
        $id = sprintf('R%07d', $count + 1);
        return $id;
    }

    public function add_new_reservation($reservation = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($reservation);
        return $insert;
    }

    // public function update_servicePackage($id = null, $servicePackage = null)
    // {
    //     foreach ($servicePackage as $key => $value) {
    //         if (empty($value)) {
    //             unset($servicePackage[$key]);
    //         }
    //     }
    //     $query = $this->db->table($this->table)
    //         ->where('id', $id)
    //         ->update($servicePackage);
    //     return $query;
    // }


}
