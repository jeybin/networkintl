<?php

namespace Jeybin\Networkintl\App\Services;


use Jeybin\Networkintl\App\Services\Client\NgeniusClient;

/**
 * Cancel a capture request
 * HTTP Request Method: DELETE
 * REF : https://docs.ngenius-payments.com/reference/cancel-a-capture-request
 * Resource (URI): https://api-gateway.sandbox.ngenius-payments.com/transactions/outlets/[outlet-reference]/orders/[order-reference]/payments/[payment-reference]/captures/[capture-reference]
 * 
 * Capture requests that have been made prior to the overnight settlement (midnight UAE/Dubai time) 
 * may be cancelled by using the operation specified below. Cancelling a capture request will return all 
 * or part of the payment to a state in which it has been authorized only, and thereby will not – without 
 * a further capture – settle the funds to your account or permanently remove the funds from your customer.
 * This operation may be useful in instances where you wish to control the settlement of funds from your 
 * customer to you, on a per-basket-item basis, or for individual goods/services represented by a 
 * single authorization amount.
 */
final class CancelCaptureService extends NgeniusClient{

    public function __construct(){
        $this->client         = new NgeniusClient();
    }   


    public function cancel(string $orderReference,string $paymentReference,string $captureReference){
        try{
           /**
             * Api Url
             */
            $API = "transactions/outlets/{outlet-reference}/orders/$orderReference/payments/$paymentReference/captures/$captureReference";
            
            /**
             * Sending API request
             */
            return $this->client->setApi($API)->execute('delete');
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


}