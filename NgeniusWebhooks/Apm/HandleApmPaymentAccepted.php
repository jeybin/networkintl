<?php

namespace App\Jobs\NgeniusWebhooks\Apm;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
/**
 * Replace model if the default model 
 * is changed
 */
use Jeybin\Networkintl\App\Models\NgeniusGatewayWehooks;


class HandleApmPaymentAccepted implements ShouldQueue{

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

    }
}