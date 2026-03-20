<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class AddressRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }
    public function rules()
    {
        return [
            'name'          => 'required',
            'post_code'     => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address'       => 'required',
            'building_name' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required'          => 'お名前を入力してください',
            'post_code.required'     => '郵便番号を入力してください',
            'post_code.regex'        => '郵便番号はハイフンを含めて入力してください',
            'address.required'       => '住所を入力してください',
            'building_name.required' => '建物名を入力してください',
        ];
    }
}