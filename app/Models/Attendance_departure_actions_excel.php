<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance_departure_actions_excel extends Model
{
    use HasFactory;
    protected $table='attendance_departure_actions_excel';
    protected $guarded=[];
    public $timestamps=false;

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

}
