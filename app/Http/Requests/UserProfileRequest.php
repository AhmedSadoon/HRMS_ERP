<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
            'username'=>'required',
            'email'=>'required',
            'password'=>'required_if:checkForUpdatePassword,1',
        ];
    }
    public function messages(){
        
        return [
            'username.required'=>'اسم المستخدم مطلوب',
            'email.required'=>'البريد الالكتروني مطلوب',
            'password.required_if'=>'كلمة المرور مطلوبة',
        ];
       
    }
}
