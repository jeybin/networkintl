<?php

namespace Jeybin\Networkintl\App\Services;


use Jeybin\Networkintl\App\Services\Client\NgeniusClient;

/**
 * Cancel an abandoned order
 * HTTP Request Method: PUT
 * Resource (URI): https://api-gateway.sandbox.ngenius-payments.com/transactions/outlets/[outlet-reference]/orders/[order-reference]/cancel
 * 
 * Cancelling an abandoned order can be useful if you wish to directly control
 * how orders that are abandoned, either by you or your payer, are reported back 
 * to you in the gateway portal or reports. In such cases, you may choose to cancel 
 * (and close) an outstanding order instead of simply leaving the abandoned order open.
 */
final class CancelOrderService extends NgeniusClient{

    public function __construct(){
        $this->client         = new NgeniusClient();
    }   


    public function cancel(string $orderReference){
        try{

            /**
             * Api Url
             */
            $API = "transactions/outlets/{outlet-reference}/orders/$orderReference/cancel";
            
            /**
             * Sending API request
             */
            return $this->client->setApi($API)->execute('put');
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


}