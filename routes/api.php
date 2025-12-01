<?php

use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'imports'], function () {
    Route::post('', [ImportController::class, 'store']);
    Route::get('', [ImportController::class, 'index']);
    Route::get('{import}', [ImportController::class, 'show']);
});
