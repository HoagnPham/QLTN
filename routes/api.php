<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\HostelController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\MaintenanceRequestController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\TenantController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth.jwt')->group(function (): void {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
        });
    });

    Route::middleware(['auth.jwt', 'role:admin'])->group(function (): void {
        Route::apiResource('hostels', HostelController::class)->only(['index', 'store']);
        Route::get('hostels/{hostelId}/rooms', [RoomController::class, 'index']);
        Route::post('hostels/{hostelId}/rooms', [RoomController::class, 'store']);
        Route::get('rooms/{id}', [RoomController::class, 'show']);
        Route::put('rooms/{id}', [RoomController::class, 'update']);
        Route::delete('rooms/{id}', [RoomController::class, 'destroy']);
        Route::patch('rooms/{id}/status', [RoomController::class, 'updateStatus']);

        Route::get('tenants', [TenantController::class, 'index']);
        Route::post('tenants', [TenantController::class, 'store']);
        Route::get('tenants/{id}', [TenantController::class, 'show']);
        Route::put('tenants/{id}', [TenantController::class, 'update']);

        Route::get('contracts', [ContractController::class, 'index']);
        Route::post('contracts', [ContractController::class, 'store']);
        Route::get('contracts/{id}', [ContractController::class, 'show']);
        Route::patch('contracts/{id}/renew', [ContractController::class, 'renew']);
        Route::patch('contracts/{id}/terminate', [ContractController::class, 'terminate']);

        Route::get('invoices', [InvoiceController::class, 'index']);
        Route::post('invoices', [InvoiceController::class, 'store']);
        Route::get('invoices/{id}', [InvoiceController::class, 'show']);

        Route::patch('payments/{id}/confirm', [PaymentController::class, 'confirm']);
        Route::get('payments/history', [PaymentController::class, 'history']);

        Route::get('maintenance-requests', [MaintenanceRequestController::class, 'index']);
        Route::patch('maintenance-requests/{id}/status', [MaintenanceRequestController::class, 'updateStatus']);

        Route::post('notifications/send', [NotificationController::class, 'send']);
        Route::get('reports/dashboard', [ReportController::class, 'dashboard']);
    });

    Route::middleware(['auth.jwt', 'role:tenant'])->group(function (): void {
        Route::post('payments/upload-proof', [PaymentController::class, 'uploadProof']);
        Route::post('maintenance-requests', [MaintenanceRequestController::class, 'store']);
        Route::get('notifications/me', [NotificationController::class, 'myNotifications']);
    });
});
