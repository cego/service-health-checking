<?php
use Illuminate\Support\Facades\Route;
use Cego\ServiceHealthChecking\Controllers\ServiceHealthController;

Route::get('/vendor/service-health-checking', [ServiceHealthController::class, 'index'])->name('vendor.service-health-checking.index');
