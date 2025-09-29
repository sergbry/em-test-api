<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/tasks', TaskController::class);

/**
 * Для другого тестового задания
 * Получение данных по API
 */
Route::get('/topbk', function () {
    $data = [];
    $data[] = [
        "id" => 1,
        "logo" => "assets/img/stavka.png",
        "verified" => true,
        "rating" => 4.7,
        "review_count" => 123,
        "bonus_amount" => 10000,
        "badge" => "exclusive",
        "internal_link" => "/bk/1",
        "external_link" => "https://partner1.com"
    ];
    $data[] = [
        "id" => 2,
        "logo" => "assets/img/fonbet.png",
        "verified" => false,
        "rating" => 4.2,
        "review_count" => 87,
        "bonus_amount" => 4500,
        "badge" => "no-deposit",
        "internal_link" => "/bk/2",
        "external_link" => "https://partner2.com"
    ];
    $data[] = [
        "id" => 3,
        "logo" => "assets/img/leon.png",
        "verified" => true,
        "rating" => 3.9,
        "review_count" => 45,
        "bonus_amount" => 0,
        "badge" => "no-deposit",
        "internal_link" => "/bk/3",
        "external_link" => "https://partner3.com"
    ];
    $data[] = [
        "id" => 4,
        "logo" => "assets/img/melbet.png",
        "verified" => false,
        "rating" => 2.3,
        "review_count" => 405,
        "bonus_amount" => 0,
        "badge" => "no-bonus",
        "internal_link" => "/bk/4",
        "external_link" => "https://partner3.com"
    ];
    sleep(1);
    $data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return response()->json($data);
});
