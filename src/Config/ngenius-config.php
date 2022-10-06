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
    'webhook-queue-name'  => null,


    /**
     * Webhook model, the db model 
     * where the event data from webhook will be stored
     */
    'webhook-model' => \Jeybin\Networkintl\App\Models\NgeniusGatewayWehooks::class,


    /**
     * Event jobs the listener classes 
     * need to accessed while triggering an event,
     * comment out the jobs that are not required
     * Here you can add more events as you like 
     * please refer for the list of events : 
     * https://docs.ngenius-payments.com/reference/consuming-web-hooks
     */

    'webhook-jobs' => [
        /**
         * Triggered when a payment has been authorized
         */
        'AUTHORISED' => \App\Jobs\NgeniusWebhoks\Auth\HandleNgeniusAuthorized::class,

        /**
         * Triggered when the authorization for a payment 
         * has been declined by the card-holder's issuing bank
         */
        'DECLINED'   => \App\Jobs\NgeniusWebhoks\Auth\HandleNgeniusDeclined::class,

        /**
         * Triggered when an authorization has been reversed, either automatically 
         * following a post-authorization fraud screening rejection, or manually 
         * following an API or portal-based request to reverse the authorization.
         */
        'FULL_AUTH_REVERSED'   => \App\Jobs\NgeniusWebhoks\Auth\HandleNgeniusFullAuthReversed::class,

        /**
         * Triggered when a request to reverse an authorization has failed. 
         * Note that, in this circumstance, the payment will remain in an AUTHORISED state.
         */
        'FULL_AUTH_REVERSAL_FAILED'   => \App\Jobs\NgeniusWebhoks\Auth\HandleNgeniusFullAuthReversed::class,

        /**
         * Triggered when the PURCHASE process has succeeded.
         */
        'PURCHASED'   => \App\Jobs\NgeniusWebhoks\Purchase\HandleNgeniusPurchaseSuccess::class,

        /**
         * Triggered when the PURCHASE process has been declined.
         */
        'PURCHASE_DECLINED'   => \App\Jobs\NgeniusWebhoks\Purchase\HandleNgeniusPurchaseDeclined::class,

        /**
         * Triggered when the PURCHASE process has been failed.
         */
        'PURCHASE_FAILED'   => \App\Jobs\NgeniusWebhoks\Purchase\HandleNgeniusPurchaseFailed::class,

        /**
         * Triggered when a previous PURCHASE has been reversed either 
         * through the N-Genius Online portal, or using the APIs.
         */
        'PURCHASE_REVERSED'   => \App\Jobs\NgeniusWebhoks\Purchase\HandleNgeniusPurchaseReversed::class,

        /**
         * Triggered when the request above to reverse a PURCHASE request has failed.
         */
        'PURCHASE_REVERSAL_FAILED'   => \App\Jobs\NgeniusWebhoks\Purchase\HandleNgeniusPurchaseReverseFailed::class,



    ],


];