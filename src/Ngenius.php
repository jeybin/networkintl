<?php

namespace Jeybin\Networkintl;

use Illuminate\Http\Request;
use Jeybin\Networkintl\Middleware\NgeniusJsonHeader;
use Jeybin\Networkintl\Controllers\NgeniusCreateOrderController;

class Ngenius {

    public $request_for;

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

    public function execute($amount,$payer_email){
        $requestType  = $this->request_for;
        if($requestType == 'create-order'){
            return NgeniusCreateOrderController::CreateOrder($amount,$payer_email);
        }

    }


}