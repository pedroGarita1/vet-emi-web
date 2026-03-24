@extends('layouts.app')

@section('title', 'Dashboard | Emi Veterinaria')

@section('content')
<div class="container-fluid py-2 py-md-3">
    <div class="page-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-shield-dog"></i> Emi Veterinaria
                </span>
                <h1 class="h3 fw-bold mb-1">Hola, {{ $user->name }}</h1>
                <p class="mb-0">Sede activa: <strong>{{ $selectedSede }}</strong></p>
            </div>
            <div class="d-flex gap-2">
                <span class="emi-badge"><i class="fa-solid fa-boxes-stacked me-1"></i> Inventario</span>
                <span class="emi-badge"><i class="fa-solid fa-cash-register me-1"></i> POS</span>
                <span class="emi-badge"><i class="fa-solid fa-stethoscope me-1"></i> Consultas</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="kpi-card kpi-blue h-100">
                <div class="fw-bold text-uppercase small text-muted">Sede</div>
                <div class="h4 fw-bold mb-1">{{ $selectedSede }}</div>
                <div class="small text-muted">Operación activa</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="kpi-card kpi-green h-100">
                <div class="fw-bold text-uppercase small text-muted">Sesión</div>
                <div class="h4 fw-bold mb-1">Autenticada</div>
                <div class="small text-muted">Con Sanctum y API</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="kpi-card h-100" style="background:linear-gradient(135deg,#fff7ed,#ffedd5)">
                <div class="fw-bold text-uppercase small text-muted">Cobertura</div>
                <div class="h4 fw-bold mb-1">3 módulos</div>
                <div class="small text-muted">Inventario, POS y Consultas</div>
            </div>
        </div>
    </div>

    <div class="module-panel mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h2 class="h5 mb-0">Indicadores clínicos</h2>
            <a href="{{ route('consultations-listar') }}" class="btn btn-sm btn-outline-success">Ir a consultas</a>
        </div>
        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="kpi-card h-100" style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">
                    <div class="fw-bold text-uppercase small text-muted">Atenciones hoy</div>
                    <div class="h4 fw-bold mb-0">{{ $todayConsultations }}</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="kpi-card h-100" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7)">
                    <div class="fw-bold text-uppercase small text-muted">Atenciones mes</div>
                    <div class="h4 fw-bold mb-0">{{ $monthConsultations }}</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="kpi-card h-100" style="background:linear-gradient(135deg,#fff7ed,#ffedd5)">
                    <div class="fw-bold text-uppercase small text-muted">Ingreso mes</div>
                    <div class="h4 fw-bold mb-0">${{ number_format($monthRevenue, 2) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="kpi-card h-100" style="background:linear-gradient(135deg,#fdf2f8,#fce7f3)">
                    <div class="fw-bold text-uppercase small text-muted">Promedio consulta</div>
                    <div class="h4 fw-bold mb-0">${{ number_format($avgConsultationCost, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="module-panel mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h2 class="h5 mb-0">Indicadores de punto de venta</h2>
            <a href="{{ route('sales-listar') }}" class="btn btn-sm btn-outline-warning">Ir a POS</a>
        </div>
        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-4">
                <div class="kpi-card h-100" style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">
                    <div class="fw-bold text-uppercase small text-muted">Ventas hoy</div>
                    <div class="h4 fw-bold mb-0">{{ $todaySales }}</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="kpi-card h-100" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7)">
                    <div class="fw-bold text-uppercase small text-muted">Ingreso hoy</div>
                    <div class="h4 fw-bold mb-0">${{ number_format($todaySalesRevenue, 2) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="kpi-card h-100" style="background:linear-gradient(135deg,#fff7ed,#ffedd5)">
                    <div class="fw-bold text-uppercase small text-muted">Ingreso mensual</div>
                    <div class="h4 fw-bold mb-0">${{ number_format($monthSalesRevenue, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-md-4">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="emi-card bg-white p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Inventario</h2>
                    <i class="fa-solid fa-boxes-stacked text-success fs-4"></i>
                </div>
                <p class="text-muted mb-0">Control de stock de medicamentos, alimento y accesorios para mascotas.</p>
                <a href="{{ route('inventario-listar') }}" class="btn btn-sm btn-outline-success mt-3">Abrir modulo</a>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="emi-card bg-white p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Punto de Venta</h2>
                    <i class="fa-solid fa-cash-register text-warning fs-4"></i>
                </div>
                <p class="text-muted mb-0">Registro rápido de ventas, caja diaria y tickets de atención al cliente.</p>
                <a href="{{ route('sales-listar') }}" class="btn btn-sm btn-outline-warning mt-3">Abrir modulo</a>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="emi-card bg-white p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Consultas</h2>
                    <i class="fa-solid fa-stethoscope text-primary fs-4"></i>
                </div>
                <p class="text-muted mb-0">Historial de pacientes, agenda de consultas y seguimiento de tratamientos.</p>
                <a href="{{ route('consultations-listar') }}" class="btn btn-sm btn-outline-primary mt-3">Abrir modulo</a>
            </div>
        </div>
    </div>
</div>
@endsection
