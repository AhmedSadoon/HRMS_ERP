<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_file extends Model
{
    use HasFactory;
    protected $table='employees_files';
    protected $guarded=[];

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
