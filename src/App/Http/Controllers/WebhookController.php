<?php

namespace Jeybin\Networkintl\App\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jeybin\Networkintl\App\Http\Middleware\VerifyWebhookSignature;

class WebhookController extends Controller
{
    public function __construct()
    {
        $this->middleware(VerifyWebhookSignature::class);
    }

    public function __invoke(Request $request)
    {
        $payload = $request->input();

        $model = config('ngenius.webhookModel');

        $ngeniusWebhookCall = $model::create([
            'type' =>  $payload['event']['type'] ?? '',
            'payload' => $payload,
        ]);

        try {
            $ngeniusWebhookCall->process();
        } catch (\Exception $e) {
            $ngeniusWebhookCall->saveException($e);

            throw $e;
        }
    }
}