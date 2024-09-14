<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchesRequest extends FormRequest
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
            'address'=>'required',
            'phones'=>'required',
            'email'=>'required',
            'active'=>'required',
           
        ];
    }
    public function messages() {
       return [
        'name.required'=>'اسم الفرع مطلوب',
        'address.required'=>'عنوان الفرع مطلوب',
        'phones.required'=>'رقم الهاتف مطلوب',
        'email.required'=>'ايميل الفرع مطلوب',
        'active.required'=>'حالة الفرع مطلوبة',
       ];
    }
}
