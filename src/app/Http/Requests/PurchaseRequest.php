<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'payment_method'   => ['required'],
            'shipping_address' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'payment_method.required'   => '支払い方法を選択してください',
            'shipping_address.required' => '配送先を選択してください',
        ];
    }
}