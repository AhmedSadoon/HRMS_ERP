<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateRequest extends FormRequest
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
            'emp_name'=>'required',
            'emp_gender'=>'required',
            'branch_id'=>'required',
            'qualifications_id'=>'required',
            'qualifications_year'=>'required',
            'graduation_estimate'=>'required',
            'graduation_specialization'=>'required',
            'brith_date'=>'required',
            'emp_home_tel'=>'required',
            'emp_start_date'=>'required',
            'function_status'=>'required',
            'emp_jobs_id'=>'required',
            'emp_national_identity'=>'required',
            'emp_endDate_identityID'=>'required',
            'emp_idenity_place'=>'required',
            'emp_nationalitie_id'=>'required',
            'religion_id'=>'required',
            'country_id'=>'required',
            'states_address'=>'required',
            'emp_work_tel'=>'required',
            'emp_military_status_id'=>'required',
            'emp_lang_id'=>'required',
            'emp_military_date_from'=>'required_if:emp_military_status_id,1',
            'emp_military_date_to'=>'required_if:emp_military_status_id,1',
            'emp_military_wepon'=>'required_if:emp_military_status_id,1',
            'exemption_date'=>'required_if:emp_military_status_id,2',
            'exemption_reason'=>'required_if:emp_military_status_id,2',
            'postponement_reason'=>'required_if:emp_military_status_id,3',
            'does_has_driving_license'=>'required',
            'driving_license_degree'=>'required_if:does_has_driving_license,1',
            'driving_license_types_id'=>'required_if:does_has_driving_license,1',
            'has_relatives'=>'required',
            'relatives_details'=>'required_if:has_relatives,1',
            'is_disabilities_processes'=>'required',
            'disabilities_processes'=>'required_if:is_disabilities_processes,1',
            'emp_department_id'=>'required',
            'does_has_ateendance'=>'required',
            'emp_salary'=>'required',
            'sal_cach_or_visa'=>'required',
            'is_active_for_vaccation'=>'required',
            'urgent_person_details'=>'required',
            'is_has_fixced_shift'=>'required',
            'shift_type_id'=>'required_if:is_has_fixced_shift,1',
            'daily_work_hour'=>'required_if:is_has_fixced_shift,0',
            'motivation_type'=>'required',
            'motivation'=>'required_if:motivation_type,1',
            'is_social_nsurance'=>'required',
            'social_nsurance_cutMonthely'=>'required_if:is_social_nsurance,1',
            'social_nsurance_number'=>'required_if:is_social_nsurance,1',
            'is_medical_nsurance'=>'required',
            'medical_nsurance_cutMonthely'=>'required_if:is_medical_nsurance,1',
            'medical_nsurance_number'=>'required_if:is_medical_nsurance,1',
            'does_have_fixed_allowances'=>'required',
            
        ];
    }

    public function messages(){
        return [
            'emp_name.required'=>'هذا الحقل مطلوب',
            'emp_gender.required'=>'هذا الحقل مطلوب',
            'branch_id.required'=>'هذا الحقل مطلوب',
            'qualifications_id.required'=>'هذا الحقل مطلوب',
            'qualifications_year.required'=>'هذا الحقل مطلوب',
            'graduation_estimate.required'=>'هذا الحقل مطلوب',
            'graduation_specialization.required'=>'هذا الحقل مطلوب',
            'brith_date.required'=>'هذا الحقل مطلوب',
            'emp_home_tel.required'=>'هذا الحقل مطلوب',
            'emp_start_date.required'=>'هذا الحقل مطلوب',
            'function_status.required'=>'هذا الحقل مطلوب',
            'emp_jobs_id.required'=>'هذا الحقل مطلوب',
            'emp_national_identity.required'=>'هذا الحقل مطلوب',
            'emp_endDate_identityID.required'=>'هذا الحقل مطلوب',
            'emp_idenity_place.required'=>'هذا الحقل مطلوب',
            'emp_nationalitie_id.required'=>'هذا الحقل مطلوب',
            'religion_id.required'=>'هذا الحقل مطلوب',
            'country_id.required'=>'هذا الحقل مطلوب',
            'states_address.required'=>'هذا الحقل مطلوب',
            'emp_work_tel.required'=>'هذا الحقل مطلوب',
            'emp_military_status_id.required'=>'هذا الحقل مطلوب',
            'emp_lang_id.required'=>'هذا الحقل مطلوب',
            'emp_military_date_from.required_if'=>'هذا الحقل مطلوب',
            'emp_military_date_to.required_if'=>'هذا الحقل مطلوب',
            'emp_military_wepon.required_if'=>'هذا الحقل مطلوب',
            'exemption_date.required_if'=>'هذا الحقل مطلوب',
            'exemption_reason.required_if'=>'هذا الحقل مطلوب',
            'postponement_reason.required_if'=>'هذا الحقل مطلوب',
            'does_has_driving_license.required'=>'هذا الحقل مطلوب',
            'driving_license_degree.required_if'=>'هذا الحقل مطلوب',
            'driving_license_types_id.required_if'=>'هذا الحقل مطلوب',
            'has_relatives.required'=>'هذا الحقل مطلوب',
            'relatives_details.required_if'=>'هذا الحقل مطلوب',
            'is_disabilities_processes.required'=>'هذا الحقل مطلوب',
            'disabilities_processes.required_if'=>'هذا الحقل مطلوب',
            'emp_department_id.required'=>'هذا الحقل مطلوب',
            'does_has_ateendance.required'=>'هذا الحقل مطلوب',
            'emp_salary.required'=>'هذا الحقل مطلوب',
            'sal_cach_or_visa.required'=>'هذا الحقل مطلوب',
            'is_active_for_vaccation.required'=>'هذا الحقل مطلوب',
            'urgent_person_details.required'=>'هذا الحقل مطلوب',
            'is_has_fixced_shift.required'=>'هذا الحقل مطلوب',
            'shift_type_id.required_if'=>'هذا الحقل مطلوب',
            'daily_work_hour.required_if'=>'هذا الحقل مطلوب',
            'motivation_type.required'=>'هذا الحقل مطلوب',
            'motivation.required_if'=>'هذا الحقل مطلوب',
            'is_social_nsurance.required'=>'هذا الحقل مطلوب',
            'social_nsurance_cutMonthely.required_if'=>'هذا الحقل مطلوب',
            'social_nsurance_number.required_if'=>'هذا الحقل مطلوب',
            'is_medical_nsurance.required'=>'هذا الحقل مطلوب',
            'medical_nsurance_cutMonthely.required_if'=>'هذا الحقل مطلوب',
            'medical_nsurance_number.required_if'=>'هذا الحقل مطلوب',
            'does_have_fixed_allowances.required'=>'هذا الحقل مطلوب',
            
         
            
        ];
    }
}
