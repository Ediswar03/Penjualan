<?php

use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SaleController::class, 'index']);
