<?php

use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UniqueLinkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [RegistrationController::class, 'showRegistrationForm'])->name('process-registration');
Route::get('/generate-link', [UniqueLinkController::class, 'generateLinkPage'])->name('generate-link');
Route::post('/generate-link', [UniqueLinkController::class, 'generateUniqueLink']);
Route::get('/generate-link/{uniqueLink}', [UniqueLinkController::class, 'showGeneratedLinkPage']);
Route::post('/process-registration', [RegistrationController::class, 'processRegistration']);
Route::get('/deactivate-link/{uniqueLink}', [UniqueLinkController::class, 'deactivateLink'])->name('deactivate-link');
Route::post('/save-game-result', [GameController::class, 'saveGameResult']);
Route::get('/get-game-history', [GameController::class, 'getGameHistory']);


Route::get('/admin-panel', [AdminPanelController::class, 'index'])->name('admin-panel.index');
Route::get('/admin-panel/create', [AdminPanelController::class, 'create'])->name('admin-panel.create');
Route::post('/admin-panel/store', [AdminPanelController::class, 'store'])->name('admin-panel.store');
Route::get('/admin-panel/edit/{id}', [AdminPanelController::class, 'edit'])->name('admin-panel.edit');
Route::put('/admin-panel/update/{id}', [AdminPanelController::class, 'update'])->name('admin-panel.update');
Route::delete('/admin-panel/destroy/{id}', [AdminPanelController::class, 'destroy'])->name('admin-panel.destroy');
