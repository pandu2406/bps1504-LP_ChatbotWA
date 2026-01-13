<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// WhatsApp Webhook
Route::post('/whatsapp/webhook', [App\Http\Controllers\WhatsAppController::class, 'handleWebhook']);
Route::get('/whatsapp/webhook', function () {
    return response()->json(['status' => 'Webhook Ready', 'info' => 'Use POST to send messages']);
});
