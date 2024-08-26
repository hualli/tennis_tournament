<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/prueba', function () {
    return response()->json([
        'mensaje' => 'La API está funcionando correctamente'
    ]);
});

Route::post('/playTournament', [TournamentController::class, 'playTournament']);
Route::get('/getTournament', [TournamentController::class, 'getTournament']);