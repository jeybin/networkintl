<?php

namespace Jeybin\Networkintl\App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Jeybin\Networkintl\Ngenius;
use Illuminate\Support\Facades\Validator;
use Jeybin\Networkintl\App\Requests\CreateOrderRequest;
use Jeybin\Networkintl\App\Services\CreateOrderService;

final class CreateOrderController{

    private $CreateOrderService;

    public function __construct(){
        $this->CreateOrderService = new CreateOrderService();
    }

    public function CreateOrder(array $request){
        try {
            $object    = new self;
            $validated = $object->validated($request);
           return $object->CreateOrderService->create($validated);
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


    public function validated(array $request){

        $validationRules = ['amount'             => 'required|numeric|gt:0',
                            'payer_email'        => 'required|email',
                            'order_reference'    => 'required|string|max:200|min:3',
                            'redirect_url'       => 'required|string',
                            'cancel_url'         => 'required',
                            'billing.first_name' => 'required|string',
                            'billing.last_name'  => 'required|string',
                            'billing.address'    => 'required|string',
                            'billing.city'       => 'required|string',
                            'billing.country'    => 'required|string',
                            'cancel_text'        => 'sometimes|string',
                            'merchant_defined'   => 'sometimes|array',
                            'language'           => 'sometimes|in:en,ar,fr',
                            'skip_confirmation_page' => 'sometimes|boolean',
                            'skip3DS'                => 'sometimes|boolean'
                ];

        
        $validationMessages = [
            'amount.*'=>'Amount is required in '.config('ngenius-config.merchant-currency').' and amount must be a number and greater than zero',
            'payer_email.required'=>'Payer email id is required',
            'payer_email.email'=>'Invalid email format in payer email',
            'redirect_url.*'=>'Redirect url required to redirect once payment is success',
            'cancel_url.*'=>'Cancel url required to redirect once payment is cancelled',
            'merchant_defined.*'=>'Merchant defined data must be key value pair',
            'billing.country.*'=>'Billing address country code is required',
        ];



        $validator = Validator::make($request,$validationRules);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $error = (strpos($error,'field')) ? str_replace(' field ',' ',$error) : $error;
            throwNgeniusPackageResponse($error,[],422);
        }

        $validated = $validator->validated();
        $validated['skip3DS']                = (!empty($validated['skip3DS'])) ? $validated['skip3DS'] : false;
        $validated['language']               = (!empty($validated['language'])) ? $validated['language'] : 'en';
        $validated['cancel_text']            = (!empty($validated['cancel_text'])) ? $validated['cancel_text'] : '';
        $validated['merchant_defined']       = (!empty($validated['merchant_defined'])) ? $validated['merchant_defined'] : [];
        $validated['skip_confirmation_page'] = (!empty($validated['skip_confirmation_page'])) ? $validated['skip_confirmation_page'] : false;

        return $validated;
    }



}
