<?php

namespace Jeybin\Networkintl\App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Jeybin\Networkintl\Ngenius;
use Illuminate\Support\Facades\Validator;
use Jeybin\Networkintl\App\Services\RefundOrderService;

final class RefundOrderController{

    private $RefundOrderService;

    public function __construct(){
        $this->RefundOrderService = new RefundOrderService();
    }

    public static function initate(array $request){
        try {
            $object    = new self;
            $validated = $object->validated($request);
           return $object->RefundOrderService->refund($validated);
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


    public function validated(array $request){

        $validationRules = ['order_id'    => 'required|string',
                            'payment_id'  => 'required|string',
                            'capture_id'  => 'required|string',
                            'amount'      => 'required|numeric'];

        
        $validationMessages = [
            'order_id*'=>'Order id is required',
            'payment_id.*'=>'Payment id is required',
            'capture_id.*'=>'Purchase id is required',
        ];



        $validator = Validator::make($request,$validationRules,$validationMessages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $error = (strpos($error,'field')) ? str_replace(' field ',' ',$error) : $error;
            throwNgeniusPackageResponse($error,[],422);
        }

        $validated = $validator->validated();

        /**
         * Converting into minor values
         */
        $validated['amount'] = $validated['amount']*100;

        return $validated;

    }




}
