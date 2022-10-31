<?php

namespace Jeybin\Networkintl\App\Services;


use Jeybin\Networkintl\App\Services\Client\NgeniusClient;

final class RefundOrderService extends NgeniusClient{

    /**
     * To accept a payment from a customer, an order is always required so 
     * that we have something to interact with in all our API interactions with 
     * the gateway, and on the Portal user interface. 
     *
     */
    public $client;

    /**
     * Order currency
     * the order currency must the same as configured
     * in the dashboard of network international
     */
    private $order_currency;

    public function __construct(){
        $this->client         = new NgeniusClient();
        $this->order_currency = config('ngenius-config.merchant-currency');
    }   


    public function refund(array $request){
        try{
            /**
             * Import variables into the current 
             * symbol table from an array
             */
            extract($request);

            /**
             * Api Url
             */
            $API = "transactions/outlets/{outlet-reference}/orders/$order_id/payments/$payment_id/purchases/$capture_id/refund";
            /**
             * Generating post request
             */
            $postRequest['currencyCode'] = $this->order_currency;
            $postRequest['value']        = $amount; 

            /**
             * Sending API request
             */
            return $this->client->setApi($API)->execute('post',['amount'=>$postRequest]);
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


}