<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance_cin_periods extends Model
{
    use HasFactory;
    protected $table='finance_cin_periods';
    protected $fillable=[
    'finance_calenders_id',
    'number_of_dats', 
    'year_and_month', 
    'finance_yr', 
    'month_id', 
    'start_date_m', 
    'end_date_m', 
    'is_open', 
    'start_date_for_pasma', 
    'end_date_for_pasma', 
    'com_code', 
    'added_by', 
    'updated_by', 
    'created_at', 
    'updated_at'
    ];

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }

    public function Month(){
        return $this->belongsTo(Monthes::class,'month_id');
    }


}
