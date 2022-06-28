<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarAddEditFormRequest extends FormRequest
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
              
            'manufacturing' => 'required|regex:/^[A-Za-z]+$/',            
            'registration' => 'required',            
            'manufacturing_date' => 'required',            
            'chassis' => 'required|min:17|max:17',            
            'model' => 'required',            
            'customer_id',    
            'reg_chars'=>'required|regex:/^[A-Za-z]+$/'        

        ];
    }


    public function messages()
    {
        return [
            
            'manufacturing.required' => trans('manufacturing field is required.'),
            'registration.required' => trans('registration field is required.'),
            'manufacturing_date.required' => trans('app.manufacturing_date field is required.'),
            'chassis.required' => trans('app.chassis field is required.'),
            'model.required' => trans('app.model field is required.'),
            'chassis.min' => trans('chassis number must be 17 digits.'),
            'chassis.max' => trans('chassis number must be 17 digits.'),
            'reg_chars.required' => trans('this field is required.'),
            'reg_chars.regex' => trans('this field must be only alphabets '),
            'manufacturing name.regex' => trans('manufacturing name must be only alphabets '),

        ];

    }
    
}
