<?php

namespace Jeybin\Networkintl\App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jeybin\Networkintl\App\Exceptions\WebhookExceptions;
use Jeybin\Networkintl\App\Http\Middleware\VerifyWebhookSignature;

class WebhookController extends Controller
{
    public function __construct()
    {
        $this->middleware(VerifyWebhookSignature::class);
    }

    public function __invoke(Request $request){

        /**
         * The webhook model where the webhook 
         * datas to be inserted
         */


         $model = config('ngenius-config.webhook-model');

        if(empty($model)){
            throw WebhookExceptions::missingModel();
        }

        $payload = $request->all();

        if(empty($payload)){
            throw WebhookExceptions::emptyPayload();
        }

        /**
         * Converting payload to array 
         */
        $payload = (!is_string($payload)) ?  $payload : json_decode(json_encode($payload),true);
        
        $currency  = config('ngenius-config.merchant-currency'); 
        $reference = $email = $orderDetails = $merchantOrderReference = null;
        $amount    = 0;

        if(!empty($payload['order'])){

            $orderDetails = $payload['order'];

            if(!empty($orderDetails['reference'])){
                $reference = $orderDetails['reference'] ?? null;
                $email     = $orderDetails['emailAddress'] ?? null;
                $merchantOrderReference = $orderDetails['merchantOrderReference'] ?? null;
            }

            if(!empty($orderDetails['amount'])){
                $currency  = (string)(!empty($orderDetails['currencyCode'])) ? $orderDetails['currencyCode'] : config('ngenius-config.merchant-currency');
                $amount    = (integer)(!empty($orderDetails['amount']['value'])) ? $orderDetails['amount']['value'] : 0;
                if($amount > 0){
                    $amount = $amount/100;
                }
            }
        }

        /**
         * Event id
         */
        $eventId    = !empty($payload['eventId'])   ? $payload['eventId']   : null;

        /**
         * Event name from the order
         */
        $eventName  = !empty($payload['eventName']) ? $payload['eventName'] : null;

        $insertData = ['event_id'=>$eventId,
                       'event_name'=>$eventName,
                       'order_reference'=>$reference,
                       'merchant_order_reference'=>$merchantOrderReference,
                       'email'    => $email,
                       'currency' => $currency,
                       'amount'   => $amount,
                       'payload'  => json_encode($payload)];

        $ngeniusWebhookCall = $model::create($insertData);

        try {
            $ngeniusWebhookCall->process();
        } catch (\Exception $e) {
            $ngeniusWebhookCall->saveException($e);
            throw $e;
        }
    }
}