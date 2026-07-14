<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ComplaintController;

use Illuminate\Support\Facades\Route;

Route::resource('admin/category', CategoryController::class)->middleware('role:admin')->names('admin.category');
Route::resource('admin/complaint', ComplaintController::class)->middleware('role:admin')->names('admin.complaint');