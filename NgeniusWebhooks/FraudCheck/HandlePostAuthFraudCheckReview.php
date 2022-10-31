<?php

namespace App\Jobs\NgeniusWebhooks\FraudCheck;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Jeybin\Networkintl\App\Models\NgeniusGatewayWehooks;


class HandlePostAuthFraudCheckReview implements ShouldQueue{

    use InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * Event name : POST_AUTH_FRAUD_CHECK_REVIEW
     * 
     * Triggered when a payment has been rejected by 
     * a 3rd party post-authorization fraud screening service.
     */


    /** @var WebhookModel */
    public $webhookCall;

    public function __construct(NgeniusGatewayWehooks $webhookCall)
    {
        /**
         * Uncomment if the event has specific name
         * add the name in the config file or 
         * in the env file
         */
        $this->onQueue(config('ngenius-config.webhook-queue-name'));


        /**
         * Setting the webhook data received into
         * public variable
         */
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        /**
         * you can access the payload of the webhook call with `$this->webhookCall`
         * Contains object of ngenius_gateway_webhooks data
         * Passing the payload/response from the webhook to 
         * the api or controller or to service to do the next steps
         */

        Http::get(route('ngenius-transaction-finalize',['ref'=>$this->webhookCall->order_reference]));;

    }
}