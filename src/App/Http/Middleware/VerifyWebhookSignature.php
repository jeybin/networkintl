<?php

namespace Jeybin\Networkintl\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jeybin\Networkintl\App\Exceptions\NgeniusWebhookExceptions;

class VerifyWebhookSignature
{
    public function handle($request, Closure $next){
        $signature = $request->header(config('ngenius-config.webhook-header'));

        if (! $signature) {
            throw NgeniusWebhookExceptions::missingSignature();
        }

        if (! $this->isValid($signature, $request->getContent())) {
            throw NgeniusWebhookExceptions::invalidSignature($signature);
        }

        return $next($request);
    }

    protected function isValid(string $signature, string $payload): bool
    {
        $secret = config('ngenius-config.webhook-secret');

        if (empty($secret)) {
            throw NgeniusWebhookExceptions::sharedSecretNotSet();
        }

        $computedSignature = hash_hmac('sha256', $payload, $secret);

        return hash_equals($signature, $computedSignature);
    }
}