<?php

namespace Jeybin\Networkintl\Controllers;


use Exception;
use Jeybin\Networkintl\Services\NgeniusCreateOrderService;

final class NgeniusCreateOrderController{

    public function CreateOrder($amount,$payer_email){
        try {
           $orderService = new NgeniusCreateOrderService();
           return $orderService->create($amount,$payer_email);
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


}
