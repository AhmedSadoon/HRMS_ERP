<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin_panel_setting extends Model
{
    use HasFactory;
    protected $table='admin_panel_settings';

    protected $fillable=[
        'company_name', 
        'system_status', 
        'image', 
        'phone',
        'address',
        'email',
        'added_by',
        'updated_by',
        'com_code', 
        'after_miniute_calculate_delay',
        'after_miniute_calculate_early_departure',
        'after_miniute_quarterday',
        'after_time_half_dayCut',
        'after_time_allday_daycut',
        'monthly_vaction_balance',
        'after_days_begins_vacation',
        'first_balance_begin_vacation',
        'sanctions_value_first_abcence',
        'sanctions_value_second_abcence',
         'sanctions_value_thaird_abcence',
         'sanctions_value_forth_abcence',
         'created_at', 
         'updated_at'
    ];
}
