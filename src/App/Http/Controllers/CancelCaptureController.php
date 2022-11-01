<?php
namespace Jeybin\Networkintl\App\Http\Controllers;

use Jeybin\Networkintl\App\Services\CancelCaptureService;


final class CancelCaptureController{
    
    
    private $CancelCaptureService;

    public function __construct(){
        $this->CancelCaptureService = new CancelCaptureService();
    }

    public static function cancel(array $request){
        try {
           $object = new self;

            if(empty($request['order_reference'])){
                throwNgeniusPackageResponse('order reference is required',null,422);
            }

            if(empty($request['payment_reference'])){
                throwNgeniusPackageResponse('payment reference is required',null,422);
            }

            if(empty($request['capture_reference'])){
                throwNgeniusPackageResponse('capture reference is required',null,422);
            }

            /**
             * Import variables from an array into the current symbol table. 
             */
            extract($request);

           return $object->CancelCaptureService->cancel($order_reference,$payment_reference,$capture_reference);
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


}


