<?php

use App\Http\Controllers\Api\{
    CastMemberController,
    CategoryController,
    GenreController,
    VideoController,
};
use Illuminate\Support\Facades\Route;

Route::apiResource('/videos', VideoController::class);

Route::apiResource('/categories', CategoryController::class);
Route::apiResource(
    name: '/genres',
    controller: GenreController::class
);

Route::apiResource(
    name: '/cast-members',
    controller: CastMemberController::class
);

Route::get('/', function () {
    return response()->json(['message' => 'success']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', function () {
        return true;
    });
});
