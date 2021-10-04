<?php
use Illuminate\Support\Facades\Route;
use Cego\ServiceHealthChecking\Controllers\ServiceHealthController;

Route::get('/vendor/service-health/check', [ServiceHealthController::class, 'index'])->name('vendor.service-health.index');
