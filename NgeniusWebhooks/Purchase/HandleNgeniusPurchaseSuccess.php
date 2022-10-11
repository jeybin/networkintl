<?php

namespace App\Jobs\NgeniusWebhooks\Purchase;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
/**
 * Replace model if the default model 
 * is changed
 */
use Jeybin\Networkintl\App\Models\NgeniusGatewayWehooks;


class HandleNgeniusPurchaseSuccess implements ShouldQueue{

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
         * you can access the payload of the webhook call with `$this->webhookCall->payload`
         * 
         * Passing the payload/response from the webhook to the 
         * CreatedHandler function in CreateChargeController
         */
    }
}