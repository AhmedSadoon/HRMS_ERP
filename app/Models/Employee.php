<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table='employees';
    protected $guarded=[];

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }
    
    public function Branch(){
        return $this->belongsTo(Branche::class,'branch_id');
    }

    public function Department(){
        return $this->belongsTo(Department::class,'emp_department_id');
    }

    public function Job(){
        return $this->belongsTo(jobs_category::class,'emp_jobs_id');
    }
}
