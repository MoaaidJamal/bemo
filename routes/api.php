<?php

use App\Http\Controllers\API\CardController;
use App\Http\Controllers\API\ColumnController;
use Illuminate\Support\Facades\Route;

Route::get('list-cards', [ColumnController::class, 'index']);
Route::controller(ColumnController::class)->prefix('columns')->group(function () {
    Route::post('store', 'store');
    Route::put('', 'update');
    Route::delete('{column}', 'delete');
});
Route::get('export-sql', [ColumnController::class, 'exportSql']);

Route::controller(CardController::class)->prefix('cards')->group(function () {
    Route::post('store', 'store');
    Route::put('{card}', 'update');
    Route::post('delete-restore/{card_id}', 'deleteRestore');
});
