<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\ComplaintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/complaint/modal-delete/{id}', [ComplaintController::class, 'modal_delete'])->middleware('role:masyarakat')->name('user.complaint.modal-delete');

Route::resource('complaint', ComplaintController::class)->middleware('role:masyarakat')->names('user.complaint');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
