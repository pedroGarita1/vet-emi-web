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
                @if($isAdmin)
                    <span class="emi-badge"><i class="fa-solid fa-boxes-stacked me-1"></i> Inventario</span>
                @endif
                <span class="emi-badge"><i class="fa-solid fa-cash-register me-1"></i> POS</span>
                <span class="emi-badge"><i class="fa-solid fa-stethoscope me-1"></i> Consultas</span>
                @if($isAdmin)
                    <span class="emi-badge"><i class="fa-solid fa-users me-1"></i> Empleados</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @if($isAdmin)
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
                <div class="kpi-card kpi-warm h-100">
                    <div class="fw-bold text-uppercase small text-muted">Cobertura</div>
                    <div class="h4 fw-bold mb-1">4 módulos</div>
                    <div class="small text-muted">Inventario, POS, Consultas y Empleados</div>
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="kpi-card kpi-blue" style="background:linear-gradient(135deg,#f0edf9,#e4dff0)">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,var(--emi-primary),var(--emi-primary-dark));color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
                            <i class="fa-solid fa-user-nurse"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="color:var(--emi-dark)">Bienvenido, {{ $user->name }}</div>
                            <div class="small text-muted">Tienes acceso a Consultas y Punto de Venta</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="module-panel mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h2 class="h5 mb-0">Indicadores clínicos</h2>
            <a href="{{ route('consultations-listar') }}" class="btn btn-sm btn-outline-success">Ir a consultas</a>
        </div>
        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="kpi-card kpi-blue h-100">
                    <div class="fw-bold text-uppercase small text-muted">{{ $isAdmin ? 'Atenciones hoy' : 'Mis consultas hoy' }}</div>
                    <div class="h4 fw-bold mb-0">{{ $todayConsultations }}</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="kpi-card kpi-green h-100">
                    <div class="fw-bold text-uppercase small text-muted">{{ $isAdmin ? 'Atenciones mes' : 'Mis consultas del mes' }}</div>
                    <div class="h4 fw-bold mb-0">{{ $monthConsultations }}</div>
                </div>
            </div>
            @if($isAdmin)
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="kpi-card kpi-warm h-100">
                        <div class="fw-bold text-uppercase small text-muted">Ingreso mes</div>
                        <div class="h4 fw-bold mb-0">${{ number_format($monthRevenue, 2) }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="kpi-card kpi-soft h-100">
                        <div class="fw-bold text-uppercase small text-muted">Promedio consulta</div>
                        <div class="h4 fw-bold mb-0">${{ number_format($avgConsultationCost, 2) }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="module-panel mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h2 class="h5 mb-0">Indicadores de punto de venta</h2>
            <a href="{{ route('sales-listar') }}" class="btn btn-sm btn-outline-warning">Ir a POS</a>
        </div>
        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-4">
                <div class="kpi-card kpi-blue h-100">
                    <div class="fw-bold text-uppercase small text-muted">{{ $isAdmin ? 'Ventas hoy' : 'Mis ventas hoy' }}</div>
                    <div class="h4 fw-bold mb-0">{{ $todaySales }}</div>
                </div>
            </div>
            @if($isAdmin)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="kpi-card kpi-green h-100">
                        <div class="fw-bold text-uppercase small text-muted">Ingreso hoy</div>
                        <div class="h4 fw-bold mb-0">${{ number_format($todaySalesRevenue, 2) }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="kpi-card kpi-warm h-100">
                        <div class="fw-bold text-uppercase small text-muted">Ingreso mensual</div>
                        <div class="h4 fw-bold mb-0">${{ number_format($monthSalesRevenue, 2) }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-3 g-md-4">
        @if($isAdmin)
            <div class="col-12 col-md-6 col-xl-3">
                <div class="emi-card bg-white p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">Inventario</h2>
                        <i class="fa-solid fa-boxes-stacked text-success fs-4"></i>
                    </div>
                    <p class="text-muted mb-0">Control de stock de medicamentos, alimento y accesorios.</p>
                    <a href="{{ route('inventario-listar') }}" class="btn btn-sm btn-outline-success mt-3">Abrir módulo</a>
                </div>
            </div>
        @endif
        <div class="{{ $isAdmin ? 'col-12 col-md-6 col-xl-3' : 'col-12 col-md-6' }}">
            <div class="emi-card bg-white p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Punto de Venta</h2>
                    <i class="fa-solid fa-cash-register text-warning fs-4"></i>
                </div>
                <p class="text-muted mb-0">Registro rápido de ventas, caja diaria y tickets de atención al cliente.</p>
                <a href="{{ route('sales-listar') }}" class="btn btn-sm btn-outline-warning mt-3">Abrir modulo</a>
            </div>
        </div>
        <div class="{{ $isAdmin ? 'col-12 col-md-6 col-xl-3' : 'col-12 col-md-6' }}">
            <div class="emi-card bg-white p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Consultas</h2>
                    <i class="fa-solid fa-stethoscope text-primary fs-4"></i>
                </div>
                <p class="text-muted mb-0">Historial de pacientes, agenda de consultas y seguimiento de tratamientos.</p>
                <a href="{{ route('consultations-listar') }}" class="btn btn-sm btn-outline-primary mt-3">Abrir modulo</a>
            </div>
        </div>
        @if($isAdmin)
            <div class="col-12 col-md-6 col-xl-3">
                <div class="emi-card bg-white p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">Empleados</h2>
                        <i class="fa-solid fa-users text-primary fs-4"></i>
                    </div>
                    <p class="text-muted mb-0">Gestiona el personal de la clínica, documentos y datos de contacto.</p>
                    <a href="{{ route('employees-listar') }}" class="btn btn-sm btn-outline-primary mt-3">Abrir módulo</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
