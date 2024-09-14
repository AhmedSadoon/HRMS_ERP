<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OccasionsRequest extends FormRequest
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
            'name'=>'required',
            'from_date'=>'required',
            'days_counter'=>'required|numeric', //اذا ادخل عدد الايام العطلة يدوي
            'to_date'=>'required',
            'active'=>'required',
            
          
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'اسم المناسبة مطلوب',
            'from_date.required'=>'تاريخ بداية المناسبة مطلوب',
            'to_date.required'=>'تاريخ نهاية المناسبة مطلوب',
            //'to_date.gt'=>'تاريخ النهاية يجب ان يكون اكبر من تاريخ البداية',
            'active.required'=>'حالة التفعيل مطلوبة',
            'days_counter.required'=>'عدد ايام العطلة مطلوب', //اذا ادخل عدد الايام العطلة يدوي
            'days_counter.numeric'=>'عدد ايام يجب ان يكون رقم', //اذا ادخل عدد الايام العطلة يدوي
            
          
        ];
    }
    
}
