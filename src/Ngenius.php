<?php

namespace Jeybin\Networkintl;

use Illuminate\Http\Request;
use Jeybin\Networkintl\App\Http\Middleware\NgeniusJsonHeader;
use Jeybin\Networkintl\App\Http\Controllers\OrderStatusController;
use Jeybin\Networkintl\App\Http\Controllers\NgeniusCreateOrderController;

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
            return NgeniusCreateOrderController::CreateOrder($this->request);
        }

        if($requestType == 'order-status'){
            return OrderStatusController::CheckStatus($this->request);
        }

    }


}