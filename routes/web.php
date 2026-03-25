<?php

use App\Http\Controllers\Web\ValidateController;
use App\Http\Controllers\Web\VistasController;
use App\Http\Controllers\Web\ConsultasController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');


Route::middleware('guest')->group(function () {
    Route::get('/login', [VistasController::class, 'login'])->name('login');
    Route::post('/login', [ValidateController::class, 'login'])->name('login.submit');

    Route::get('/register', [VistasController::class, 'register'])->name('register');
    Route::post('/register', [ValidateController::class, 'register'])->name('register.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [VistasController::class, 'dashboard'])->name('vistas-inicio');
    Route::get('/inventory-items', [VistasController::class, 'inventory'])->name('inventario-listar');
    Route::get('/sales', [VistasController::class, 'sales'])->name('sales-listar');
    Route::get('/consultations', [VistasController::class, 'consultations'])->name('consultations-listar');

    Route::post('/logout', [ValidateController::class, 'logout'])->name('logout');

    Route::post('/inventory-items', [ValidateController::class, 'storeInventory'])->name('inventario-agregar');
    Route::put('/inventory-items/{inventoryItem}', [ValidateController::class, 'updateInventory'])->name('inventario-actualizar');
    Route::delete('/inventory-items/{inventoryItem}', [ValidateController::class, 'destroyInventory'])->name('inventario-eliminar');

    Route::post('/sales', [ValidateController::class, 'storeSale'])->name('sales-agregar');
    Route::put('/sales/{sale}', [ValidateController::class, 'updateSale'])->name('sales-actualizar');
    Route::delete('/sales/{sale}', [ValidateController::class, 'destroySale'])->name('sales-eliminar');


    // Modularización: lógica de consultas a ConsultasController
    Route::post('/consultations', [ConsultasController::class, 'store'])->name('consultations-agregar');
    Route::put('/consultations/{consultation}', [ConsultasController::class, 'update'])->name('consultations-actualizar');
    Route::delete('/consultations/{consultation}', [ConsultasController::class, 'destroy'])->name('consultations-eliminar');
    Route::post('/consultations/{consultation}/images', [ConsultasController::class, 'addImages'])->name('consultations-add-images');
    Route::get('/consultations/{consultation}/edit', [ConsultasController::class, 'edit'])->name('consultations-editar');
    Route::get('/consultations/{consultation}/prescription-pdf', [ValidateController::class, 'downloadConsultationPrescriptionPdf'])->name('consultations-receta-pdf');

    Route::post('/consultations/species', [ValidateController::class, 'storeSpecies'])->name('consultations-especie-agregar');
    Route::post('/consultations/pets', [ValidateController::class, 'storePet'])->name('consultations-mascota-agregar');
    Route::post('/consultations/pricing-rules', [ValidateController::class, 'storeConsultationPricingRule'])->name('consultations-tarifa-agregar');
});
