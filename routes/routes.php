<?php


use Rooberthh\Faktura\Http\Controllers\StripeCallbackController;

Route::prefix('faktura')->name('faktura::')->group(function () {
    Route::prefix('callback')->name('callback:')->group(function () {
        Route::post('stripe', StripeCallbackController::class)->name('stripe');
    });
});
