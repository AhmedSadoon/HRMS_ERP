<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance_departure extends Model
{
    use HasFactory;
    protected $table='attendance_departure';
    protected $guarded=[];

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }

    public function updatedByforAction(){
        return $this->belongsTo(Admin::class,'is_updated_active_action_by');
    }
}
