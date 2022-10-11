<?php

namespace Jeybin\Networkintl\App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jeybin\Networkintl\App\Http\Middleware\VerifyWebhookSignature;

class NgeniusWebhookController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(VerifyWebhookSignature::class);
    // }

    public function __invoke(Request $request){

        $payload = $request->input();
        debug(">> webhook network intl");
        debug($request->all());

        $model = config('ngenius-config.webhook-model');

        $ngeniusWebhookCall = $model::create([
            'type'    => $payload['event'] ?? '',
            'outlet' => $payload['outlet'] ?? '',
            'ref' => $payload['reference'] ?? '',
            'email' => $payload['event'] ?? '',
            'currency' => $payload['event'] ?? '',
            'amount' => $payload['event'] ?? '',
            'payload' => $payload ?? '',
        ]);

        try {
            $ngeniusWebhookCall->process();
        } catch (\Exception $e) {
            $ngeniusWebhookCall->saveException($e);
            throw $e;
        }
    }
}