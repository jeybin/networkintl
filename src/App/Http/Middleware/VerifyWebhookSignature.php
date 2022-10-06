<?php

namespace Jeybin\Networkintl\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jeybin\Networkintl\App\Exceptions\WebhookExceptions;

class VerifyWebhookSignature
{
    public function handle($request, Closure $next){
        $signature = $request->header('X-NGENIUS-Webhook-Signature');

        if (! $signature) {
            throw WebhookExceptions::missingSignature();
        }

        if (! $this->isValid($signature, $request->getContent())) {
            throw WebhookExceptions::invalidSignature($signature);
        }

        return $next($request);
    }

    protected function isValid(string $signature, string $payload): bool
    {
        $secret = config('coinbase.webhookSecret');

        if (empty($secret)) {
            throw WebhookExceptions::sharedSecretNotSet();
        }

        $computedSignature = hash_hmac('sha256', $payload, $secret);

        return hash_equals($signature, $computedSignature);
    }
}