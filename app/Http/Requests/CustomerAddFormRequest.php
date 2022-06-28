<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddFormRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

   
    public function rules()
    {
        return [
              
            
            'name' => 'required|regex:/^[(a-zA-Z\s)\p{L}]+$/u|max:50',
            'mail' => 'required|email|unique:customers,mail,'.$this->id,
            'phone' => 'required|min:9|max:9|digits:9|regex:/^[- +()]*[0-9][- +()0-9]*$/|unique:customers,phone,'.$this->id,
            'address' => 'required',        

        ];
    }


    public function messages()
    {
        return [
            'name.required' => trans('app.customer name is required.'),
            'name.regex'  => trans('app.customer name is only alphabets and space.'),
            'name.max' => trans('app.customer name should not more than 50 character.'),

    
            'mail.required' => trans('app.Email is required.'),
            'mail.email'  => trans('app.Please enter a valid email address. Like : sales@dasinfomedia.com'),
            'mail.unique' => trans('app.Email you entered is already registered.'),

            'phone.required' => trans('app.Contact number is required.'),
            'phone.min' => trans('Contact number must be 9 digits.'),
            'phone.max' => trans('Contact number must be 9 digits.'),
            'phone.regex' => trans('Contact number must be number'),
            'phone.unique' => trans('Phone you entered is already registered.'),
            'phone.digits' => trans('Contact number must be 9 digits.'),

            'address.required'  => trans('app.Address field is required.'), 

        ];

    }
    
}
