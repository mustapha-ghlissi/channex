<?php
 
use App\Http\Controllers\Api\PropertyApiController;
use Illuminate\Support\Facades\Route;
 
Route::middleware(['api'])->prefix('api/v1')->group(function () {
    // Property endpoints
    Route::get('/properties', [PropertyApiController::class, 'index']);
    Route::get('/properties/{property}', [PropertyApiController::class, 'show']);
    
    // Availability endpoints
    Route::get('/properties/{property}/availability', [PropertyApiController::class, 'availability']);
    Route::put('/properties/{property}/availability', [PropertyApiController::class, 'updateAvailability']);
    
    // Rates endpoints
    Route::get('/properties/{property}/rates', [PropertyApiController::class, 'rates']);
    Route::put('/properties/{property}/rates', [PropertyApiController::class, 'updateRates']);
    
    // Channels endpoints
    Route::get('/properties/{property}/channels', [PropertyApiController::class, 'channels']);
});
