<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main_salary_employee_p_loans extends Model
{
    use HasFactory;

    
    protected $table='main_salary_employee_p_loans';
    protected $guarded=[];

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }

  
    public function dismissail_by(){
        return $this->belongsTo(Admin::class,'dismissail_by');
    }

    public function aksat(){
        return $this->hasMany(Main_salary_employee_p_loans_aksat::class,'main_salary_p_loans_id');
    }
}
