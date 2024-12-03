<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Admin_panel_settingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'after_miniute_calculate_delay' => 'required',
            'after_miniute_calculate_early_departure' => 'required',
            'after_miniute_quarterday' => 'required',
            'after_time_half_dayCut' => 'required',
            'after_time_allday_daycut' => 'required',
            'monthly_vaction_balance' => 'required',
            'after_days_begins_vacation' => 'required',
            'first_balance_begin_vacation' => 'required',
            'sanctions_value_first_abcence' => 'required',
            'sanctions_value_second_abcence' => 'required',
            'sanctions_value_thaird_abcence' => 'required',
            'sanctions_value_forth_abcence' => 'required',
            'is_transfer_vacction' => 'required',
            'is_pull_manull_days_from_passma' => 'required',
        ];
    }
    public function messages() {
    
        return [
          'company_name.required'=>'هذا الحقل مطلوب',
          'phone.required'=>'هذا الحقل مطلوب',
          'address.required'=>'هذا الحقل مطلوب',
          'after_miniute_calculate_delay.required'=>'هذا الحقل مطلوب',
          'after_miniute_calculate_early_departure.required'=>'هذا الحقل مطلوب',
          'after_miniute_quarterday.required'=>'هذا الحقل مطلوب',
          'after_time_half_dayCut.required'=>'هذا الحقل مطلوب',
          'after_time_allday_daycut.required'=>'هذا الحقل مطلوب',
          'monthly_vaction_balance.required'=>'هذا الحقل مطلوب',
          'after_days_begins_vacation.required'=>'هذا الحقل مطلوب',
          'first_balance_begin_vacation.required'=>'هذا الحقل مطلوب',
          'sanctions_value_first_abcence.required'=>'هذا الحقل مطلوب',
          'sanctions_value_second_abcence.required'=>'هذا الحقل مطلوب',
          'sanctions_value_thaird_abcence.required'=>'هذا الحقل مطلوب',
          'sanctions_value_forth_abcence.required'=>'هذا الحقل مطلوب',
          'is_transfer_vacction.required'=>'هذا الحقل مطلوب',
          'is_pull_manull_days_from_passma.required'=>'هذا الحقل مطلوب',
        ];
      }

   
}
