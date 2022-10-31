<?php

namespace Jeybin\Networkintl\App\Services;


use Jeybin\Networkintl\App\Services\Client\NgeniusClient;

final class ReverseAuthorizedPaymentService extends NgeniusClient{

    /**
     * To accept a payment from a customer, an order is always required so 
     * that we have something to interact with in all our API interactions with 
     * the gateway, and on the Portal user interface. 
     *
     */
    public $client;

    public function __construct(){
        $this->client         = new NgeniusClient();
    }

    public function reverse(array $request){
        try {
            /**
             * Reversing an authorized payment
             * HTTP Request Method: PUT
             * https://docs.ngenius-payments.com/reference/reversing-an-authorized-payment
             * 
             * 
             * Reversing a successfully authorized payment will cancel the authorization 
             * that your customer’s issuing bank has provided as evidence of your transaction. 
             * Where supported by your customer’s issuing bank, this action will also release 
             * the funds that were previously held in readiness for a subsequent funds transfer (capture).
             */
            if(empty($request['order_reference']) || empty($request['payment_reference'])){
                throwNgeniusPackageResponse('Invalid reverse authorized payment request',null,422);
            }

            $orderRef   = $request['order_reference'];
            $paymentRef = $request['payment_reference'];

            $api = "transactions/outlets/{outlet-reference}/orders/$orderRef/payments/$paymentRef/cancel";
            /**
             * Sending request to the ngenius client
             */
            $response = $this->client->setApi($api)->execute('put');

            return $response;
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }




}
