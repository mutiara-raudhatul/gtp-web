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

    public function get_unit_homestay_booking_data($homestay_id=null, $unit_type=null, $unit_number=null, $reservation_id=null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->join('unit_homestay', 'detail_reservation.homestay_id=unit_homestay.homestay_id')
            ->join('homestay', 'detail_reservation.homestay_id=homestay.id')
            ->join('homestay_unit_type', 'detail_reservation.unit_type=homestay_unit_type.id')
            ->where('detail_reservation.unit_type', $unit_type)
            ->where('detail_reservation.unit_number', $unit_number)
            ->where('detail_reservation.reservation_id', $reservation_id)
            ->get();
        return $query;
    }

    public function get_price_homestay_booking($homestay_id=null, $unit_type=null, $unit_number=null, $reservation_id=null)
    {
        $query = $this->db->table($this->table)
            ->selectSum('price','tot_price_hom')
            ->join('unit_homestay', 'detail_reservation.homestay_id=unit_homestay.homestay_id')
            ->join('homestay', 'detail_reservation.homestay_id=homestay.id')
            ->join('homestay_unit_type', 'detail_reservation.unit_type=homestay_unit_type.id')
            ->where('detail_reservation.unit_type', $unit_type)
            ->where('detail_reservation.unit_number', $unit_number)
            ->where('detail_reservation.reservation_id', $reservation_id)
            ->get();
            
        return $query;
    }


}
