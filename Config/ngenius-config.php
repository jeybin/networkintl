<?php


return [

    /**
     * Merchant curreny from the network international dashboard.
     * The currency which is defined in the network international 
     * can only be used
     */
    'merchant-currency' => env('NGENIUS_MERCHANT_CURRENCY','AED'),


    /**
     * Webhook header which is added inside the dashboard.
     * You can define the custom webhook header that added in 
     * the network international or you can add the header as
     * X-NGENIUS-Webhook-Signature to the dashboard
     */
    'webhook-header'  => env('NGENIUS_WEBHOOK_HEADER','X-NGENIUS-Webhook-Signature'),

    /**
     * Webhook secret which generated in the dashboard
     */
    'webhook-secret'    => env('NGENIUS_WEBHOOK_SECRET',null),

    /**
     * Webhook secret which generated in the dashboard
     */
    'webhook-queue-name'  => env('NGENIUS_WEBHOOK_QUEUE','ngenius-webhook-queue'),


    /**
     * Webhook model, the db model 
     * where the event data from webhook will be stored
     */
    'webhook-model' => \Jeybin\Networkintl\App\Models\NgeniusGatewayWehooks::class,


    /**
     * Event jobs the listener classes 
     * need to accessed while triggering an event,
     * uncomment the jobs needed and add the jobs
     * in the project App\Jobs\NgeniusWebhooks
     * Here you can add more events as you like 
     * please refer for the list of events : 
     * https://docs.ngenius-payments.com/reference/consuming-web-hooks
     */

    'webhook-jobs' => [

        /**
         * Triggered when the payment is success
         * fro a PURCHASE order
         */
        'PURCHASED'   => \App\Jobs\NgeniusWebhooks\Purchase\HandlePurchaseSuccess::class,

        /**
         * Triggered when the payment is declined 
         * for a PURCHASE order
         */
        'PURCHASE_DECLINED'   => \App\Jobs\NgeniusWebhooks\Purchase\HandlePurchaseDeclined::class,

        /**
         * Triggered when the payment is failed for a
         * PURCHASE order.
         */
        'PURCHASE_FAILED'   => \App\Jobs\NgeniusWebhooks\Purchase\HandlePurchaseFailed::class,

        /**
         * Triggered when a previous PURCHASE has been reversed either 
         * through the N-Genius Online portal, or using the APIs.
         */
        'PURCHASE_REVERSED'   => \App\Jobs\NgeniusWebhooks\Purchase\HandlePurchaseReversed::class,

        /**
         * Triggered when the request above to reverse a PURCHASE request has failed.
         */
        'PURCHASE_REVERSAL_FAILED'   => \App\Jobs\NgeniusWebhooks\Purchase\HandlePurchaseReverseFailed::class,


        /**
         * Triggered when a payment has been authorized
         */
        // 'AUTHORISED' => \App\Jobs\NgeniusWebhooks\Auth\HandleAuthAuthorized::class,

        /**
         * Triggered when the authorization for a payment 
         * has been declined by the card-holder's issuing bank
         */
        // 'DECLINED'   => \App\Jobs\NgeniusWebhooks\Auth\HandleAuthDeclined::class,

        // /**
        //  * Triggered when an authorization has been reversed, either automatically 
        //  * following a post-authorization fraud screening rejection, or manually 
        //  * following an API or portal-based request to reverse the authorization.
        //  */
        // 'FULL_AUTH_REVERSED'   => \App\Jobs\NgeniusWebhooks\Auth\HandleNgeniusFullAuthReversed::class,

        // /**
        //  * Triggered when a request to reverse an authorization has failed. 
        //  * Note that, in this circumstance, the payment will remain in an AUTHORISED state.
        //  */
        // 'FULL_AUTH_REVERSAL_FAILED'   => \App\Jobs\NgeniusWebhooks\Auth\HandleNgeniusFullAuthReversed::class,



    ],


];