<?php

namespace Jeybin\Networkintl\App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jeybin\Networkintl\App\Http\Middleware\VerifyWebhookSignature;

class NgeniusWebhookController extends Controller
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

        $payload = $request->all();

        if($payload){
            throwNgeniusPackageResponse('Empty event data',null,422);
        }

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
                $currency  = (string)$orderDetails['currencyCode'] ?? config('ngenius-config.merchant-currency');
                $amount    = (integer)$orderDetails['amount']['value'] ?? 0;
                if($amount > 0){
                    $amount = $amount/100;
                }
            }
            

        }


        $insertData = ['event_id'=>$payload['eventId'] ?? null,
                       'event_name'=>$payload['eventName'] ?? null,
                       'order_data'=>json_encode($payload['order']) ?? null,
                       'order_reference'=>$reference,
                       'merchant_order_reference'=>$merchantOrderReference,
                       'email'    => $email,
                       'currency' => $currency,
                       'amount'   => $amount,
                       'payload'  => $payload];


        $ngeniusWebhookCall = $model::create($insertData);

        try {
            $ngeniusWebhookCall->process();
        } catch (\Exception $e) {
            $ngeniusWebhookCall->saveException($e);
            throw $e;
        }
    }
}