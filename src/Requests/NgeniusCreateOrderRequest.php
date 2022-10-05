<?php

namespace Jeybin\Networkintl\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class NgeniusCreateOrderRequest extends FormRequest{


    protected $stopOnFirstFailure = false;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        $mainRule = [
            'id'   => 'required',
            'page' => 'required|string|max:100',
        ];
        
        return $rules;
    }

    /**
     * Validation failure custom messages
     *
     * @return array
     */
    public function messages(){
        return [
            'id.required' => 'Tour id is required!',
            'id.exists'   => 'Invalid tour id',
            'page.required'     => 'Page name is required!',
            'services.*.meal_type.required_with' => 'Meal type is required in services',
            'services.*.meal_type.integer'       => 'Meal type must be an integer',
            'services.*.meal_type.exists'        => 'Invalid meal type id'
        ];
    }
    
  

    /**
     * If validation fails it will 
     * change the response as like
     * -> JSON response if api
     * -> Redirect with error response if web
     *
     * @param $validator
     * @return void
     */
    public function withValidator($validator){
        if($validator->fails()){
            throwResponse($validator->errors()->first(),'',422);
        }
    }

    

}
