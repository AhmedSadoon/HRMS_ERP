<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employee_fixed_suits extends Model
{
    use HasFactory;
    protected $table='employee_fixed_suits';
    protected $guarded=[];

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }

    public function allowances(){
        return $this->belongsTo(Allowance::class,'allowance_id');
    }
}
