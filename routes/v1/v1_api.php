<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Object\GetObjectController;
use App\Http\Controllers\Api\V1\Object\PostObjectKeyController;
use App\Http\Controllers\Api\V1\Object\ShowObjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/object-keys', [GetObjectController::class, '__invoke']);
    Route::post('/object-keys', [PostObjectKeyController::class, '__invoke']);
});
