<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main_salary_employee_allowances extends Model
{
    use HasFactory;
    protected $table='main_salary_employee_allowances';
    protected $guarded=[];

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }
}
