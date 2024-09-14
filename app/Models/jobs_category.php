<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jobs_category extends Model
{
    use HasFactory;
    protected $table='jobs_categories';

    protected $fillable=[
        'name',
        'active',
        'added_by',
        'com_code',
        'updated_by',
    ];
    
    public function added(){
        return $this->belongsTo(Admin::class,'added_by');
    }

    public function updatedBy(){
        return $this->belongsTo(Admin::class,'updated_by');
    }
}
