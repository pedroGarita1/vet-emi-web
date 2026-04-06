<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\EsteticaServiceController;
use App\Http\Controllers\Api\InventoryItemController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\SaleController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Rutas públicas para notificaciones (sin autenticación necesaria)
Route::get('/notificaciones', [NotificacionController::class, 'obtenerNotificacionesActivas'])->name('api.notificaciones');
Route::get('/notificaciones/{notificacion}', [NotificacionController::class, 'obtenerNotificacion'])->name('api.notificacion');

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::get('/catalogs/consultations-data', [CatalogController::class, 'consultationsData'])->name('api.catalogs.consultations-data');

    Route::apiResource('inventory-items', InventoryItemController::class);
    Route::apiResource('sales', SaleController::class);
    Route::apiResource('consultations', ConsultationController::class);
    Route::apiResource('estetica-services', EsteticaServiceController::class)->only(['index', 'store']);
});
