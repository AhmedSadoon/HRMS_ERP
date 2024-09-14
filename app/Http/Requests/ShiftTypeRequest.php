<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftTypeRequest extends FormRequest
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
            'type'=>'required',
            'form_time'=>'required',
            'to_time'=>'required',
            'total_huor'=>'required',
            'active'=>'required',
        
        ];
    }

    public function messages() {
        return [
            'type.required'=>'نوع الشفت مطلوب',
            'form_time.required'=>'وقت بداية الشفت مطلوب',
            'to_time.required'=>'وقت نهاية الشفت مطلوب',
            'total_huor.required'=>'مجموع ساعات الشفت مطلوب',
            'active.required'=>'حالة التفعيل مطلوب',
        ];
    }
}
