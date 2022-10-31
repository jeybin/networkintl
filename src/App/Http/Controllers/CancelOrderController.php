<?php

namespace Jeybin\Networkintl\App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Jeybin\Networkintl\Ngenius;
use Illuminate\Support\Facades\Validator;
use Jeybin\Networkintl\App\Services\CancelOrderService;

final class CancelOrderController{

    private $CancelOrderService;

    public function __construct(){
        $this->CancelOrderService = new CancelOrderService();
    }

    public static function cancel(string $orderReference){
        try {
            $object = new self;
           return $object->CancelOrderService->cancel($orderReference);
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }




}
