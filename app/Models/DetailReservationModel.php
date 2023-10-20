<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailReservationModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detail_reservation';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['data','package_id','unit_type','unit_number','reservation_id','comment','rating'];

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

    public function add_new_detail_reservation($detailreservation = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($detailreservation);
        return $insert;
    }
    
    public function get_unit_homestay_booking($reservation_id =  null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->where('reservation_id', $reservation_id)
            ->get();
        return $query;
    }

    public function get_unit_homestay_dtbooking($reservation_id =  null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            // ->join('homestay', 'homestay.id = detail_reservation.homestay_id')
            // ->join('homestay_unit_type', 'homestay_unit_type.id = detail_reservation.unit_type')
            ->join('unit_homestay', 'detail_reservation.homestay_id = unit_homestay.homestay_id', 'detail_reservation.unit_number = unit_homestay.unit_number', 'detail_reservation.unit_type = unit_homestay.unit_type')
            ->where('detail_reservation.reservation_id', $reservation_id)
            ->get();

        return $query;
    }

    public function get_unit_homestay_booking_data($homestay_id=null, $unit_type=null, $unit_number=null,$reservation_id=null)
    {
        $query = $this->db->table($this->table)
        ->select('*')
        ->join('unit_homestay', 'detail_reservation.homestay_id = unit_homestay.homestay_id', 'detail_reservation.unit_number = unit_homestay.unit_number', 'detail_reservation.unit_type = unit_homestay.unit_type')
        ->join('homestay', 'homestay.id = detail_reservation.homestay_id', 'inner')
        ->join('homestay_unit_type', 'homestay_unit_type.id = detail_reservation.unit_type', 'inner')
        ->where('unit_homestay.unit_number', $unit_number)
        ->where('unit_homestay.unit_type', $unit_type)
        ->where('unit_homestay.homestay_id', $homestay_id)
        ->where('detail_reservation.reservation_id', $reservation_id)
        ->get();
        return $query;
    }

    public function get_price_homestay_booking($homestay_id=null, $unit_type=null, $unit_number=null,$reservation_id=null)
    {
        $query = $this->db->table($this->table)
            ->select('price')
            ->join('unit_homestay', 'detail_reservation.homestay_id = unit_homestay.homestay_id', 'detail_reservation.unit_number = unit_homestay.unit_number', 'detail_reservation.unit_type = unit_homestay.unit_type')
            ->join('homestay', 'homestay.id = detail_reservation.homestay_id', 'inner')
            ->join('homestay_unit_type', 'homestay_unit_type.id = detail_reservation.unit_type', 'inner')
            ->where('unit_homestay.unit_number', $unit_number)
            ->where('unit_homestay.unit_type', $unit_type)
            ->where('unit_homestay.homestay_id', $homestay_id)
            ->where('detail_reservation.reservation_id', $reservation_id)
            ->get();
            
        return $query;
    }

    public function update_detailreservation($date = null, $unit_number = null, $homestay_id = null, $unit_type = null, $data = null) {
        $query = $this->db->table('detail_reservation')
            ->update($data, ['date' => $date, 'unit_number' => $unit_number, 'homestay_id' => $homestay_id, 'unit_type' => $unit_type,]);
        return $query;
    }

    
    public function getReview($id = null, $unit_number = null, $unit_type = null)
    {
        $query = $this->db->table($this->table)
            ->select("`detail_reservation.homestay_id`,`detail_reservation.unit_number`,`detail_reservation.unit_type`,`detail_reservation.review`, `detail_reservation.rating`, `users.username`")
            ->join('reservation', 'reservation.id = detail_reservation.reservation_id')
            ->join('users', 'reservation.user_id = users.id')
            ->where('detail_reservation.unit_number', $unit_number)
            ->where('detail_reservation.homestay_id', $id)
            ->where('detail_reservation.unit_type', $unit_type)
            ->where('reservation.rating <>', 0) 
            ->get();
        return $query;
    }
    
    public function getRating($id = null, $unit_number = null, $unit_type = null)
    {
        $query = $this->db->table($this->table)
            ->selectAVG('detail_reservation.rating', 'rating')
            ->select("`detail_reservation.homestay_id`,`detail_reservation.unit_number`,`detail_reservation.unit_type`")
            ->where('detail_reservation.unit_number', $unit_number)
            ->where('detail_reservation.homestay_id', $id)
            ->where('detail_reservation.unit_type', $unit_type)
            ->where('detail_reservation.rating <>', 0) 
            ->get();
        return $query;
    }

}
