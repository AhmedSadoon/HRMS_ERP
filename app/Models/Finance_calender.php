<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance_calender extends Model
{
    use HasFactory;

    protected $table='finance_calenders';
    protected $fillable=[
        'finance_yr',
        'finance_yr_desc',
        'start_date',
        'end_date',
        'open_yr_flag',
        'com_code',
        'added_by',
        'updated_by',
    ];

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }
}
