<?php
use Illuminate\Support\Facades\Route;
use Cego\ServiceHealthChecking\Controllers\ServiceHealthCheckingController;

Route::get('/vendor/service-health-checking', [ServiceHealthCheckingController::class, 'index'])->name('vendor.service-health-checking.index');
