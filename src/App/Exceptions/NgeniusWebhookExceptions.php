<?php

namespace  Jeybin\Networkintl\App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Jeybin\Networkintl\App\Models\NgeniusGatewayWehooks;

class NgeniusWebhookExceptions extends Exception
{
    public static function missingSignature()
    {
        return new static('The request did not contain a header named `X-NGENIUS-Webhook-Signature`.');
    }

    public static function invalidSignature($signature)
    {
        return new static("The signature `{$signature}` found in the header named `X-NGENIUS-Webhook-Signature` is invalid. Make sure that the `ngenius.webhookSecret` config key is set to the value you found on the ngenius dashboard. If you are caching your config try running `php artisan clear:cache` to resolve the problem.");
    }

    public static function sharedSecretNotSet()
    {
        return new static('The ngenius Commerce webhook shared secret is not set. Make sure that the `ngenius.webhookSecret` config key is set to the value you found on the ngenius dashboard.');
    }
    
    public static function jobClassDoesNotExist(string $jobClass, NgeniusGatewayWehooks $webhookCall)
    {
        return new static("Could not process webhook id `{$webhookCall->id}` of type `{$webhookCall->type} because the configured jobclass `$jobClass` does not exist.");
    }

    public static function missingType()
    {
        return new static('The webhook call did not contain a type. Valid ngenius Commerce webhook calls should always contain a type.');
    }

    public function render($request)
    {
        return response(['code'=>400,
                         'error' => true,
                         'message'=>$this->getMessage()], 400);
    }
}