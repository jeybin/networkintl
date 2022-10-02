<?php

namespace Jeybin\Networkintl\Services;


use Jeybin\Networkintl\Services\Client\NgeniusClient;

final class NgeniusCreateOrderService extends NgeniusClient{

    /**
     * To accept a payment from a customer, an order is always required so 
     * that we have something to interact with in all our API interactions with 
     * the gateway, and on the Portal user interface. 
     *
     */
    public $client;


    /**
     * ordertype : "AUTH", "SALE", "PURCHASE"
     * Orders created with the ‘PURCHASE’ action will, if successfully authorized, 
     * automatically and immediately attempt to capture/settle the full order amount, 
     * whereas orders created with the ‘AUTH’ action will await some further action from 
     * instructing N-Genius Online to capture/settle the funds. Unless you are ready to 
     * ship your goods/services immediately, or you are selling digital content, we 
     * recommend you use the ‘AUTH’ action and capture your customers’ successful 
     * authorizations when you are ready to ship the goods to your customer.
     */
    private $order_type = 'PURCHASE';



    /**
     * Order currency
     * the order currency must the same as configured
     * in the dashboard of network international
     */
    private $order_currency = 'AED';



    public function __construct(){
        $this->client = new NgeniusClient();
    }

    public function create($amount,$payer_emailId){
        try {
            $request  = $this->createOrderRequest($amount,$payer_emailId);
            $response = $this->client
                            ->setApi('transactions/outlets/{outlet-reference}/orders')
                            ->execute('post',$request);

            return $response;
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


    public function createOrderRequest(string $amount,string $payer_emailId){
        try {
            /**
             * The amount must be convert into minor units
             * Hence multiplying the amount with 100;
             */
            $amount         = $amount*100;

            /**
             * Action type is set as purchase 
             */
            $data['action'] = $this->order_type;

            /**
             * Amount value as minor value
             * multiplied by 100
             */
            $data['amount'] = ['currencyCode' => $this->order_currency,
                               'value'        => $amount];
            
            /**
             * Payer's email id
             */
            $data['emailAddress'] = $payer_emailId;

            return (array)$data;
        } catch (Exception $exception) {
            throwNgeniusPackageResponse($exception);
        }
    }


}
