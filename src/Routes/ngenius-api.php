<?php

use Jeybin\Networkintl\App\Http\Controllers\NgeniusWebhookController;

Route::group(['prefix' => 'api/ngenius','middleware'=>'api'], function() {
    Route::post('webhook',NgeniusWebhookController::class)->name('ngenius-webhook');
    Route::post('webhook2',NgeniusWebhookController::class)->name('ngenius-webhook2');
});