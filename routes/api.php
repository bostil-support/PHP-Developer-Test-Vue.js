<?php

use App\Http\Controllers\ApartmentController;
use Illuminate\Support\Facades\Route;

Route::get('apartments', [ApartmentController::class, 'index']);
