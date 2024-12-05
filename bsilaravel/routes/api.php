<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/halo', [UserController::class, 'halo'])->name('halo');
Route::post('/registrasi', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/listBuku', [BukuController::class, 'listBuku'])->name('listBuku');
Route::get('/detailBuku/{id}', [BukuController::class, 'detailBuku'])->name('detailBuku');

Route::post('/game', [GameController::class, 'addGame'])->name('addGame');
Route::get('/game', [GameController::class, 'listGame'])->name('getGame');
Route::get('/game-detail/{id}', [GameController::class, 'detailGame'])->name('detailGame');
Route::delete('/game-delete/{id}', [GameController::class, 'deleteGame'])->name('deleteGame');
Route::post('/game-update/{id}', [GameController::class, 'updateGame'])->name('updateGame');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/kategori', [KategoriController::class, 'kategori'])->name('kategori');
    Route::post('/tambahBuku', [BukuController::class, 'tambahBuku'])->name('tambahBuku');
    Route::delete('/hapusBuku/{id}', [BukuController::class, 'hapusBuku'])->name('detailBuku');
    Route::post('/ubahBuku/{id}', [BukuController::class, 'ubahBuku'])->name('ubahBuku');
});

Route::get("", function () {
    $response = [];
    $response['kode'] = 401;
    $response['message'] = "Anda belum login";
    return json_encode($response);
})->name('login');
