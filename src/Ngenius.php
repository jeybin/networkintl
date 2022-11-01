<?php

namespace Jeybin\Networkintl;

use Illuminate\Http\Request;
use Jeybin\Networkintl\App\Enums\StatusCodes;
use Jeybin\Networkintl\App\Http\Middleware\AcceptJsonHeader;
use Jeybin\Networkintl\App\Http\Controllers\CancelOrderController;
use Jeybin\Networkintl\App\Http\Controllers\CreateOrderController;
use Jeybin\Networkintl\App\Http\Controllers\OrderStatusController;
use Jeybin\Networkintl\App\Http\Controllers\RefundOrderController;
use Jeybin\Networkintl\App\Http\Controllers\CancelCaptureController;
use Jeybin\Networkintl\App\Http\Controllers\ReverseAuthorizePaymentController;

class Ngenius {

    private $request_for;

    private $request;

    /**
     * Function to set public variables for the class
     * since the function is working as FACADE
     * it returning error 
     * Uncaught Error: Using $this when not in object context when using 
     *
     * @param [type] $n
     * @return this
     */
    public static function type($requestType){
        $object = new self;
        $object->request_for = $requestType;
        return $object;
    }

    public function request($request){
        $this->request = $request;
        return $this;
    }

    public function execute(){
        $requestType  = $this->request_for;

        if($requestType == 'create-order'){
            return CreateOrderController::CreateOrder($this->request);
        }

        if($requestType == 'order-status'){
            return OrderStatusController::CheckStatus($this->request);
        }

        if($requestType == 'refund-order'){
            return RefundOrderController::initate($this->request);
        }

        if($requestType == 'cancel-order'){
            return CancelOrderController::cancel($this->request);
        }

        if($requestType == 'reverse-auth'){
            return ReverseAuthorizePaymentController::reverse($this->request);
        }

        if($requestType == 'cancel-capture'){
            return CancelCaptureController::cancel($this->request);
        }

    }

    public function Status($result_code){
        return StatusCodes::find($result_code);
    }


}