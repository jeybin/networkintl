<?php

namespace App\Jobs\NgeniusWebhooks\Capture;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Jeybin\Networkintl\App\Models\NgeniusGatewayWehooks;


class HandleCapturePaymentSuccess implements ShouldQueue{

    /**
     * Event name : CAPTURE_VOID_FAILED
     * 
     * Triggered when the request above to cancel/void a 
     * CAPTURE request has failed.
     */

    use InteractsWithQueue, Queueable, SerializesModels;
    
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