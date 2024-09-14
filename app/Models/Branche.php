<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branche extends Model
{
    use HasFactory;
    protected $table='branches';
    protected $fillable=[
        'name', 
        'address', 
        'phones', 
        'email', 
        'active', 
        'added_by', 
        'updated_by', 
        'com_code', 
        'created_at', 
        'updated_at'
    ];

    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }

}
