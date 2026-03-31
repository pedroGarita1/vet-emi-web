<?php

use App\Http\Controllers\Web\ValidateController;
use App\Http\Controllers\Web\VistasController;
use App\Http\Controllers\Web\ConsultasController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\NotificacionController;
use App\Http\Controllers\Web\SuscriptorCorreoController;
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
    Route::get('/sales', [VistasController::class, 'sales'])->name('sales-listar');
    Route::get('/consultations', [VistasController::class, 'consultations'])->name('consultations-listar');

    Route::post('/logout', [ValidateController::class, 'logout'])->name('logout');

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

    // Rutas exclusivas para administradores
    Route::middleware('role:admin')->group(function () {
        Route::get('/inventory-items', [VistasController::class, 'inventory'])->name('inventario-listar');
        Route::post('/inventory-items', [ValidateController::class, 'storeInventory'])->name('inventario-agregar');
        Route::put('/inventory-items/{inventoryItem}', [ValidateController::class, 'updateInventory'])->name('inventario-actualizar');
        Route::delete('/inventory-items/{inventoryItem}', [ValidateController::class, 'destroyInventory'])->name('inventario-eliminar');

        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees-listar');
        Route::get('/employees/buscar-cp/{cp}', [EmployeeController::class, 'buscarCP'])->name('employees-buscar-cp');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees-agregar');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees-actualizar');
        Route::post('/employees/{employee}/documents', [EmployeeController::class, 'updateDocuments'])->name('employees-documentos');
        Route::get('/employees/{employee}/documents/{type}', [EmployeeController::class, 'viewDocument'])->name('employees-documento-ver');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees-eliminar');

        // Rutas para gestión de notificaciones y avisos
        Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones-listar');
        Route::get('/notificaciones/crear', [NotificacionController::class, 'create'])->name('notificaciones-crear');
        Route::post('/notificaciones', [NotificacionController::class, 'store'])->name('notificaciones-guardar');
        Route::get('/notificaciones/{notificacion}/editar', [NotificacionController::class, 'edit'])->name('notificaciones-editar');
        Route::put('/notificaciones/{notificacion}', [NotificacionController::class, 'update'])->name('notificaciones-actualizar');
        Route::delete('/notificaciones/{notificacion}', [NotificacionController::class, 'destroy'])->name('notificaciones-eliminar');
        Route::post('/notificaciones/{notificacion}/enviar', [NotificacionController::class, 'enviar'])->name('notificaciones-enviar');
        Route::post('/notificaciones/{notificacion}/cambiar-estado', [NotificacionController::class, 'cambiarEstado'])->name('notificaciones-cambiar-estado');

        // Rutas para gestión de suscriptores de correo
        Route::get('/suscriptores', [SuscriptorCorreoController::class, 'index'])->name('suscriptores-listar');
        Route::get('/suscriptores/crear', [SuscriptorCorreoController::class, 'create'])->name('suscriptores-crear');
        Route::post('/suscriptores', [SuscriptorCorreoController::class, 'store'])->name('suscriptores-guardar');
        Route::get('/suscriptores/{suscriptor}/editar', [SuscriptorCorreoController::class, 'edit'])->name('suscriptores-editar');
        Route::put('/suscriptores/{suscriptor}', [SuscriptorCorreoController::class, 'update'])->name('suscriptores-actualizar');
        Route::delete('/suscriptores/{suscriptor}', [SuscriptorCorreoController::class, 'destroy'])->name('suscriptores-eliminar');
        Route::post('/suscriptores/{suscriptor}/cambiar-estado', [SuscriptorCorreoController::class, 'cambiarEstado'])->name('suscriptores-cambiar-estado');
    });
});
