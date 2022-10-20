<?php

namespace Jeybin\Networkintl\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jeybin\Networkintl\App\Exceptions\NgeniusWebhookExceptions;

class VerifyWebhookSignature
{
    /**
     * Custom middleware to verify the 
     * webhook signture to confirm the
     * webhook response is trusted
     * 
     * The webhook secret and the webhook 
     * custom header name must be set in
     * the ngenius dashboard as well as'
     * in the ngenius config file
     *
     * @param [type] $request
     * @param Closure $next
     *
     *  @return void
     */
    public function handle($request, Closure $next){

        /**
         * Secret webhook header value
         * Set in the the ngenius config file.
         * If empty throw exception
         */
        $secret    = config('ngenius-config.webhook-secret');
        if (empty($secret)) {
            throw NgeniusWebhookExceptions::sharedSecretNotSet();
        }

        /**
         * Getting the webhook header with 
         * the custom header name that set 
         * in the ngenius config file 
         * If empty throw exception
         */
        $signature = $request->header(config('ngenius-config.webhook-header'));
        if (empty($signature)) {
            throw NgeniusWebhookExceptions::missingSignature();
        }

        /**
         * Checking if the secret 
         * and the signture got from the
         * webhook header is equals 
         * if not throws exception else continues
         */
        if ($signature !== $secret) {
            throw NgeniusWebhookExceptions::invalidSignature($signature);
        }

        return $next($request);
    }


}