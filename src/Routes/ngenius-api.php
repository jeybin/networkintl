<?php

use Jeybin\Networkintl\App\Http\Controllers\WebhookController;

Route::group(['prefix' => 'api/ngenius','middleware'=>'api'], function() {
    Route::post('webhook',WebhookController::class)->name('ngenius-webhook');
    Route::post('webhook2',WebhookController::class)->name('ngenius-webhook2');
});