<?php

namespace Jeybin\Networkintl\App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jeybin\Networkintl\App\Services\NgeniusOrderStatusService;

final class OrderStatusController{


    public function CheckStatus(string $orderReference){
        try {
            if(empty($orderReference)){
                throwNgeniusPackageResponse('Order reference id is required');
            }

            $paymentStatusService = new NgeniusOrderStatusService();

            return $paymentStatusService->check($orderReference);



        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }

}
