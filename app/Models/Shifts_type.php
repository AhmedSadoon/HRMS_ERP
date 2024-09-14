<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shifts_type extends Model
{
    use HasFactory;

    protected $table='shifts_types';
    protected $fillable = [
        'type',
        'form_time',
        'to_time',
        'total_huor',
        'added_by',
        'updated_by',
        'com_code',
    ];

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }
}
