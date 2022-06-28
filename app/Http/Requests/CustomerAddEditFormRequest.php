<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddEditFormRequest extends FormRequest
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
            'name' => 'required|regex:/^[(a-zA-Z\s)\p{L}]+$/u|max:50',
            'mail' => 'required|email|unique:customers,mail,'.$this->id,
            'phone' => 'required|min:9|max:9|digits:9|regex:/^[- +()]*[0-9][- +()0-9]*$/',
            'phone' => 'unique:customers,phone,'.$this->id,
            'address' => 'required',  
            //'manufacturing' => 'required',            
            //'registration' => 'required',            
            //'manufacturing_date' => 'required',            
            //'chassis' => 'required',            
            //'model' => 'required',            
            //'kilometers' => 'required',
            //'customer_id',            

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
            //'manufacturing.required' => trans('manufacturing field is required.'),
            //'registration.required' => trans('registration field is required.'),
            //'manufacturing_date.required' => trans('app.manufacturing_date field is required.'),
            //'chassis.required' => trans('app.chassis field is required.'),
            //'model.required' => trans('app.model field is required.'),
            //'kilometers.required' => trans('app.kilometers field is required.'),

        ];

    }
    
}
