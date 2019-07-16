<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'=>'required|string|max:255',
            'email'=>'required|email|string|unique:customer,email',
            'password'=>'required|string|min:8'
        ];
    }
    public function messages()
    {
        return [
            'username.required'=>'Bạn không được để trống trường này',
            'username.string'=>'Bạn phải nhập vào một chuỗi',
            'username.max'=>'Vượt ký tự tối đa',
            'email.required'=>'bạn không được để trống trường này',
            'email.email'=>'Email không đúng định dạng',
            'email.string'=>'email phải là một chuỗi',
            'email.unique'=>'Email đã tồn tại, vui lòng chọn email khác',
            'password.required' =>'Bạn không được để trống trường này',
            'password.string'=>'Password phải là một chuỗi',
            'password.min'=>'Độ dài mật khẩu tối đa phải là 8 ký tự',
        ];
    }
}
