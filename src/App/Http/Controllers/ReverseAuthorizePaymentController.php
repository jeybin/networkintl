<?php

namespace Jeybin\Networkintl\App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Jeybin\Networkintl\Ngenius;
use Illuminate\Support\Facades\Validator;
use Jeybin\Networkintl\App\Services\ReverseAuthorizedPaymentService;

final class ReverseAuthorizePaymentController{

    private $reverseAuthorizedPaymentService;

    public function __construct(){
        $this->reverseAuthorizedPaymentService = new ReverseAuthorizedPaymentService();
    }

    public static function reverse(array $request){
        try {
            $object    = new self;
            $validated = $object->validated($request);
           return $object->reverseAuthorizedPaymentService->reverse($validated);
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


    public function validated(array $request){

        $validationRules = ['order_reference'   => 'required|string',
                            'payment_reference' => 'required|string'];

        $validator = Validator::make($request,$validationRules);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $error = (strpos($error,'field')) ? str_replace(' field ',' ',$error) : $error;
            throwNgeniusPackageResponse($error,[],422);
        }

        return $validator->validated();

    }




}
