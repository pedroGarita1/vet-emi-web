@extends('layouts.app')

@section('title', 'Consultas | Emi Veterinaria')

@section('content')
@php
    $selectedPatient = $selectedPatientPetId > 0 ? $petsCatalog->firstWhere('id', $selectedPatientPetId) : null;
    $tableRows = $selectedPatientPetId > 0 ? $patientHistory : $consultations;
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">

<style>
    .consult-shell {
        display: grid;
        gap: 1rem;
    }

    .consult-hero {
        background: radial-gradient(circle at 10% 10%, #a995cf 0%, #5d4a82 45%, #3d3456 100%);
        border-radius: 18px;
        color: #fff;
        padding: 1.2rem;
        box-shadow: 0 14px 30px rgba(93, 74, 130, 0.35);
    }

    .consult-table-panel {
        background: var(--emi-surface);
        border: 1px solid var(--emi-border);
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(37, 35, 50, 0.08);
        overflow: hidden;
    }

    .consult-table-body {
        padding: 1rem;
    }

    .consult-actions-panel {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.9fr);
        gap: 1rem;
        align-items: stretch;
    }

    .consult-primary-action,
    .consult-secondary-actions {
        background: var(--emi-surface);
        border: 1px solid var(--emi-border);
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(37, 35, 50, 0.08);
    }

    .consult-primary-action {
        padding: 1.1rem;
        background: linear-gradient(135deg, #f4f1fb 0%, #e8dff5 55%, var(--emi-surface) 100%);
        border-color: #d9cfe8;
    }

    .consult-primary-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        width: 100%;
        border: 0;
        border-radius: 16px;
        padding: 1rem 1.1rem;
        color: #fff;
        background: linear-gradient(135deg, #8b78b9 0%, #5d4a82 55%, #4a3d66 100%);
        box-shadow: 0 18px 28px rgba(93, 74, 130, 0.28);
    }

    .consult-primary-trigger strong {
        display: block;
        font-size: 1.05rem;
        text-align: left;
    }

    .consult-primary-trigger span {
        display: block;
        opacity: 0.86;
        font-size: 0.85rem;
        text-align: left;
    }

    .consult-primary-trigger i {
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .consult-section-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .consult-secondary-actions {
        padding: 1rem;
    }

    .consult-secondary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.65rem;
    }

    .consult-secondary-trigger {
        width: 100%;
        text-align: left;
        border-radius: 14px;
        padding: 0.85rem;
        border: 1px solid #d9cfe8;
        background: #f8f7fb;
        color: #252332;
    }

    .consult-secondary-trigger i {
        color: #5d4a82;
        margin-right: 0.45rem;
    }

    .consult-secondary-trigger small {
        display: block;
        color: #6f6a80;
        margin-top: 0.2rem;
    }

    @media (max-width: 991.98px) {
        .consult-actions-panel {
            grid-template-columns: 1fr;
        }

        .consult-secondary-grid {
            grid-template-columns: 1fr;
        }
    }

    .quick-tools {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .quick-tools .btn {
        white-space: nowrap;
    }

    .table-filter-wrap {
        background: #f8f7fb;
        border: 1px dashed #d9cfe8;
        border-radius: 12px;
        padding: 0.85rem;
        margin-bottom: 0.8rem;
    }

    .table-consult thead th {
        position: sticky;
        top: 0;
        background: #f8f7fb;
        z-index: 1;
    }

    .med-row {
        border: 1px dashed #d9cfe8;
        border-radius: 10px;
        padding: 0.65rem;
        background: #f8f7fb;
    }

    .med-chip {
        display: inline-block;
        border: 1px solid #d9cfe8;
        border-radius: 999px;
        padding: 2px 10px;
        font-size: 0.72rem;
        margin: 2px 4px 2px 0;
        background: #f4f1fb;
    }

    .breed-pill {
        display: inline-flex;
        align-items: center;
        border: 1px solid #d9cfe8;
        background: #f4f1fb;
        color: #3a334d;
        font-size: 0.72rem;
        border-radius: 999px;
        padding: 2px 10px;
        font-weight: 700;
    }

    .size-pill {
        display: inline-flex;
        align-items: center;
        border: 1px solid #d9cfe8;
        background: #f8f7fb;
        color: #5d4a82;
        font-size: 0.72rem;
        border-radius: 999px;
        padding: 2px 10px;
        font-weight: 700;
    }

    .history-toolbar-title {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-icon-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .care-alert-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.8rem;
    }

    .care-alert-card {
        border: 1px solid #d9cfe8;
        border-radius: 12px;
        background: #f8f7fb;
        padding: 0.75rem;
    }

    .alerts-collapsible {
        border: 1px solid #d9cfe8;
        border-radius: 12px;
        background: #f8f7fb;
        padding: 0.55rem 0.7rem;
    }

    .alerts-collapsible summary {
        cursor: pointer;
        list-style: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        font-weight: 700;
        color: #3f3a53;
    }

    .alerts-collapsible summary::-webkit-details-marker {
        display: none;
    }

    .alerts-collapsible-content {
        margin-top: 0.65rem;
    }

    .care-status-wrap {
        display: grid;
        gap: 0.35rem;
    }

    .care-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border: 1px solid #d9cfe8;
        border-radius: 999px;
        padding: 2px 9px;
        font-size: 0.74rem;
        font-weight: 700;
        width: fit-content;
        background: #f4f1fb;
        color: #3a334d;
    }

    .care-pill.overdue {
        border-color: #efb4b4;
        background: #fdf0f0;
        color: #962d2d;
    }

    .consult-create-modal .modal-dialog {
        max-width: min(1320px, calc(100vw - 1.5rem));
    }

    .consult-create-modal .modal-content {
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--emi-border);
    }

    .consult-create-modal .modal-header {
        padding: 1.15rem 1.25rem;
        background: linear-gradient(135deg, #f4f1fb 0%, #eae1f5 52%, #dfd4ed 100%);
        border-bottom: 1px solid #d9cfe8;
    }

    .consult-create-modal .modal-body {
        padding: 1.25rem;
        background:
            radial-gradient(circle at top right, rgba(139, 120, 185, 0.08), transparent 22%),
            linear-gradient(180deg, #f8f7fb 0%, var(--emi-surface) 100%);
    }

    .consult-create-shell {
        display: grid;
        gap: 1rem;
    }

    .consult-create-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
        gap: 1rem;
        align-items: start;
    }

    .consult-create-card {
        background: var(--emi-surface);
        border: 1px solid var(--emi-border);
        border-radius: 20px;
        padding: 1rem;
        box-shadow: 0 14px 32px rgba(37, 35, 50, 0.08);
    }

    .consult-create-card-soft {
        background: linear-gradient(180deg, var(--emi-surface) 0%, #fbfaf9 100%);
        border-color: #d9cfe8;
    }

    .consult-create-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.85rem;
    }

    .consult-create-title {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        font-weight: 800;
        color: #3a334d;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        font-size: 0.78rem;
    }

    .consult-create-title i {
        color: #5d4a82;
    }

    .consult-create-head p {
        margin: 0.2rem 0 0;
        color: #6f6a80;
        font-size: 0.88rem;
    }

    .consult-treatment-card {
        min-height: 100%;
        background: linear-gradient(180deg, var(--emi-surface) 0%, #faf9fc 100%);
    }

    .consult-treatment-card .form-label,
    .consult-products-card .form-label {
        font-weight: 700;
    }

    .consult-treatment-card .ck-editor__editable,
    .consult-treatment-card textarea {
        min-height: 300px;
    }

    .consult-products-card {
        padding-top: 1.05rem;
    }

    .consult-create-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding-top: 0.25rem;
    }

    .consult-tabs .nav-link {
        border: 1px solid #d9cfe8;
        color: #5d4a82;
        border-radius: 999px;
        padding: 0.45rem 0.9rem;
        font-weight: 700;
    }

    .consult-tabs .nav-link.active {
        background: #5d4a82;
        color: #fff;
        border-color: #5d4a82;
    }

    .calendar-panel {
        border: 1px solid var(--emi-border);
        border-radius: 14px;
        background: #fff;
        padding: 0.9rem;
    }

    .calendar-canvas {
        max-width: 1080px;
        margin: 0 auto;
    }

    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .calendar-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: 1px solid #d9cfe8;
        border-radius: 999px;
        padding: 3px 10px;
        font-size: 0.75rem;
        background: #f8f7fb;
    }

    .calendar-color-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        display: inline-block;
    }

    #dewormingCalendar {
        min-height: 520px;
        max-width: 1080px;
        margin: 0 auto;
    }

    .fc {
        --fc-small-font-size: 0.73em;
    }

    .fc .fc-toolbar.fc-header-toolbar {
        margin-bottom: 0.55rem;
    }

    .fc .fc-button {
        padding: 0.18rem 0.45rem;
        font-size: 0.78rem;
    }

    .fc .fc-multimonth-title {
        font-size: 1rem;
    }

    .fc .fc-multimonth-daygrid-table {
        font-size: 0.78rem;
    }

    .fc .fc-daygrid-day-frame {
        min-height: 1.95rem;
        padding: 1px;
    }

    .fc .fc-multimonth {
        max-width: 1080px;
        margin: 0 auto;
    }

    .fc .fc-event {
        border-radius: 4px;
        padding: 0 2px;
        font-size: 0.68rem;
        line-height: 1.15;
    }

    .fc .fc-toolbar-title {
        font-size: 1rem;
        font-weight: 700;
    }

    .fc .fc-button {
        text-transform: capitalize;
    }

    @media (max-width: 991.98px) {
        .consult-create-grid {
            grid-template-columns: 1fr;
        }

        .consult-create-modal .modal-body {
            padding: 1rem;
        }

        .care-alert-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid py-2 py-md-3 consult-shell">
    <section class="consult-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-notes-medical"></i> Area Clinica</span>
                <h1 class="h3 fw-bold mb-1">Consultas y Recetas</h1>
                <p class="mb-0 opacity-75">Historial clínico y registro de consultas.</p>
            </div>
            <div class="quick-tools">
                <a href="{{ route('vistas-inicio') }}" class="btn btn-outline-light btn-sm d-inline-flex align-items-center gap-2"><i class="fa-solid fa-house"></i><span>Panel</span></a>
            </div>
        </div>
    </section>

    <section class="consult-actions-panel">
        <div class="consult-primary-action">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
                <div>
                    <span class="consult-section-kicker text-success-emphasis mb-1"><i class="fa-solid fa-star"></i> Accion principal</span>
                    <h2 class="h4 mb-1">Nueva consulta</h2>
                    <p class="text-muted mb-0">Acceso principal del modulo.</p>
                </div>
            </div>
            <button type="button" class="consult-primary-trigger" data-bs-toggle="modal" data-bs-target="#modalCreateConsultation">
                <div>
                    <strong>Nueva consulta</strong>
                    <span>Diagnostico, tratamiento y productos.</span>
                </div>
                <i class="fa-solid fa-stethoscope"></i>
            </button>
            <button type="button" class="btn btn-outline-warning w-100 mt-2 d-inline-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalPreventiveControl">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Nuevo control preventivo</span>
            </button>
        </div>

        <div class="consult-secondary-actions">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-3 flex-wrap">
                <div>
                    <span class="consult-section-kicker text-secondary mb-1"><i class="fa-solid fa-layer-group"></i> Auxiliares</span>
                    <h3 class="h6 mb-0">Catalogos rapidos</h3>
                </div>
            </div>
            <div class="consult-secondary-grid">
                <button type="button" class="consult-secondary-trigger" data-bs-toggle="modal" data-bs-target="#modalAddSpecies">
                    <strong><i class="fa-solid fa-dna"></i> Especie</strong>
                    <small>Catalogo clinico</small>
                </button>
                <button type="button" class="consult-secondary-trigger" data-bs-toggle="modal" data-bs-target="#modalAddPet">
                    <strong><i class="fa-solid fa-paw"></i> Mascota</strong>
                    <small>Registro rapido</small>
                </button>
                <button type="button" class="consult-secondary-trigger" data-bs-toggle="modal" data-bs-target="#modalEditPet">
                    <strong><i class="fa-solid fa-pen"></i> Editar mascota</strong>
                    <small>Dueño y datos</small>
                </button>
                <button type="button" class="consult-secondary-trigger" data-bs-toggle="modal" data-bs-target="#modalAddPricingRule">
                    <strong><i class="fa-solid fa-tags"></i> Tarifa</strong>
                    <small>Regla por diagnostico</small>
                </button>
            </div>
        </div>
    </section>

    <section class="consult-table-panel">
        <div class="consult-table-body py-2">
            <details class="alerts-collapsible">
                <summary>
                    <span class="d-inline-flex align-items-center gap-2"><i class="fa-solid fa-bell text-warning"></i> Avisos preventivos (30 dias)</span>
                    <span class="small text-muted">{{ $upcomingCareAlerts->count() }} registro(s)</span>
                </summary>
                <div class="alerts-collapsible-content">
                    @if($upcomingCareAlerts->isNotEmpty())
                        <div class="care-alert-grid">
                            @foreach($upcomingCareAlerts as $alert)
                                <article class="care-alert-card">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                        <strong>{{ $alert['type'] }} - {{ $alert['pet_name'] }}</strong>
                                        <span class="care-pill {{ $alert['is_overdue'] ? 'overdue' : '' }}">
                                            <i class="fa-solid {{ $alert['is_overdue'] ? 'fa-triangle-exclamation' : 'fa-clock' }}"></i>
                                            {{ $alert['is_overdue'] ? 'Vencido' : 'Proximo' }}
                                        </span>
                                    </div>
                                    <div class="small text-muted">Propietario: {{ $alert['owner_name'] }}</div>
                                    <div class="small mt-1"><strong>Fecha objetivo:</strong> {{ $alert['due_date']->format('d/m/Y') }}</div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">No hay avisos de vacunacion o desparasitacion para los proximos 30 dias.</div>
                    @endif
                </div>
            </details>
        </div>
    </section>

    <section class="consult-table-panel">
        <div class="consult-table-body">
            <ul class="nav nav-pills consult-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-consult-calendar" type="button" role="tab">Calendario</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-consult-table" type="button" role="tab">Tabla</button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade" id="tab-consult-calendar" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h2 class="h5 mb-0 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-calendar-days text-primary"></i> Calendario anual de desparasitacion</h2>
                        <span class="small text-muted">Vista enero a diciembre, eventos aplicados y proximos por paciente.</span>
                    </div>

                    <div class="calendar-panel">
                        @if($dewormingCalendarLegend->isNotEmpty())
                            <div class="calendar-legend">
                                @foreach($dewormingCalendarLegend as $legend)
                                    <span class="calendar-legend-item">
                                        <span class="calendar-color-dot" style="background-color: {{ $legend['color'] }}"></span>
                                        <span>{{ $legend['pet_name'] }}{{ $legend['owner_name'] ? ' - '.$legend['owner_name'] : '' }}</span>
                                    </span>
                                @endforeach
                            </div>
                        @endif

                                <div class="calendar-canvas">
                                    <div id="dewormingCalendar"></div>
                                </div>
                    </div>
                </div>

                <div class="tab-pane fade show active" id="tab-consult-table" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                        <h2 class="h5 mb-0 history-toolbar-title">
                            <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                            {{ $selectedPatient ? 'Historico de '.$selectedPatient->name : 'Historico clinico' }}
                        </h2>
                        <span class="small text-muted d-inline-flex align-items-center gap-2"><i class="fa-solid fa-list"></i>{{ $tableRows->count() }} registros</span>
                    </div>

                    <form method="GET" action="{{ route('consultations-listar') }}" class="table-filter-wrap row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-filter"></i><span>Paciente</span></label>
                            <select class="form-select" name="patient_pet_id">
                                <option value="">Todos los pacientes</option>
                                @foreach($petsCatalog as $pet)
                                    <option value="{{ $pet->id }}" @selected($selectedPatientPetId === $pet->id)>{{ $pet->name }}{{ $pet->owner_name ? ' - '.$pet->owner_name : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"><button class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2"><i class="fa-solid fa-magnifying-glass"></i><span>Buscar</span></button></div>
                        <div class="col-md-2"><a class="btn btn-outline-secondary w-100 d-inline-flex align-items-center justify-content-center gap-2" href="{{ route('consultations-listar') }}"><i class="fa-solid fa-rotate-left"></i><span>Reset</span></a></div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-modern table-consult align-middle">
                            <thead>
                                <tr>
                                    <th>Mascota</th>
                                    <th>Especie</th>
                                    <th>Tipo</th>
                                    <th>Propietario</th>
                                    <th>Diagnostico</th>
                                    <th>Control sanitario</th>
                                    <th>Productos</th>
                                    <th>Imagenes</th>
                                    <th>Costo</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tableRows as $consultation)
                                    <tr>
                                        <td>{{ $consultation->pet_name }}</td>
                                        <td>{{ $consultation->species }}</td>
                                        <td>
                                            @if($consultation->petCatalog?->breed)
                                                <span class="breed-pill">{{ $consultation->petCatalog->breed }}</span>
                                                @if($consultation->petCatalog?->size_category)
                                                    <span class="size-pill">{{ ucfirst($consultation->petCatalog->size_category) }}</span>
                                                @endif
                                            @else
                                                {{ $consultation->petCatalog?->size_category ? ucfirst($consultation->petCatalog->size_category) : '-' }}
                                            @endif
                                        </td>
                                        <td>{{ $consultation->owner_name }}</td>
                                        <td>{{ $consultation->diagnosis }}</td>
                                        <td>
                                            <div class="care-status-wrap">
                                                <span class="care-pill {{ $consultation->next_vaccination_at && $consultation->next_vaccination_at->isPast() ? 'overdue' : '' }}">
                                                    <i class="fa-solid fa-syringe"></i>
                                                    {{ $consultation->next_vaccination_at ? 'Vacuna: '.$consultation->next_vaccination_at->format('d/m/Y') : 'Vacuna: sin fecha' }}
                                                </span>
                                                <span class="care-pill {{ $consultation->next_deworming_at && $consultation->next_deworming_at->isPast() ? 'overdue' : '' }}">
                                                    <i class="fa-solid fa-shield-halved"></i>
                                                    {{ $consultation->next_deworming_at ? 'Desparasitacion: '.$consultation->next_deworming_at->format('d/m/Y') : 'Desparasitacion: sin fecha' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($consultation->consultationItems->isNotEmpty())
                                                @foreach($consultation->consultationItems as $item)
                                                    <span class="med-chip">{{ $item->inventoryItem?->name ?: 'Producto' }} x{{ $item->quantity }}</span>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($consultation->images->isNotEmpty())
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($consultation->images->take(3) as $image)
                                                        <a href="{{ asset($image->image_path) }}" target="_blank" rel="noopener" class="d-inline-block">
                                                            <img src="{{ asset($image->image_path) }}" alt="Imagen consulta" style="width: 38px; height: 38px; object-fit: cover; border-radius: 8px; border: 1px solid #d9cfe8;">
                                                        </a>
                                                    @endforeach
                                                </div>
                                                @if($consultation->images->count() > 3)
                                                    <div class="small text-muted mt-1">+{{ $consultation->images->count() - 3 }} mas</div>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($consultation->cost, 2) }}</td>
                                        <td>{{ $consultation->consulted_at?->format('d/m/Y H:i') }}</td>
                                        <td class="d-flex flex-wrap gap-1">
                                            <a class="btn btn-sm btn-outline-secondary action-icon-btn" href="{{ route('consultations-receta-pdf', $consultation) }}" title="Descargar PDF" aria-label="Descargar PDF"><i class="fa-solid fa-file-pdf"></i></a>
                                            <button class="btn btn-sm btn-outline-primary action-icon-btn" type="button" data-bs-toggle="modal" data-bs-target="#modalEditConsultation-{{ $consultation->id }}" title="Editar consulta" aria-label="Editar consulta"><i class="fa-solid fa-pen"></i></button>
                                            <form class="d-inline" method="POST" action="{{ route('consultations-eliminar', $consultation) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger action-icon-btn" title="Eliminar consulta" aria-label="Eliminar consulta"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @if($selectedPatient)
                                        <tr>
                                            <td colspan="11" class="small text-muted">
                                                <strong>Tratamiento:</strong> {!! $consultation->treatment ?: '<span class="text-muted">Sin detalle</span>' !!}
                                                <span class="mx-2">|</span>
                                                <strong>Vacuna aplicada:</strong> {{ $consultation->vaccination_applied ? ($consultation->vaccination_note ?: 'Si') : 'No' }}
                                                <span class="mx-2">|</span>
                                                <strong>Desparasitacion aplicada:</strong> {{ $consultation->deworming_applied ? ($consultation->deworming_note ?: 'Si') : 'No' }}
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr><td colspan="11" class="text-center text-muted">No hay consultas registradas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalDewormingCalendarEvent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle preventivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="small text-muted mb-2" id="fcEventStatus"></div>
                <div class="mb-1"><strong>Paciente:</strong> <span id="fcEventPet"></span></div>
                <div class="mb-1"><strong>Propietario:</strong> <span id="fcEventOwner"></span></div>
                <div class="mb-1"><strong>Fecha del registro:</strong> <span id="fcEventDate"></span></div>
                <div class="mb-3"><strong>Nota:</strong> <span id="fcEventNote"></span></div>

                <form method="POST" id="fcRescheduleForm" data-action-base="{{ url('/consultations') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <label class="form-label" id="fcRescheduleDateLabel">Re-agendar proxima desparasitacion</label>
                        <input type="date" class="form-control" id="fcNextDewormingAt" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" id="fcRescheduleNoteLabel">Nota de desparasitacion (opcional)</label>
                        <input class="form-control" id="fcDewormingNote" maxlength="255" placeholder="Ej: Albendazol 10ml">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-calendar-check"></i>
                            <span>Guardar re-agenda</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('modules.consultations.modals.create_consultation_modal')
@include('modules.consultations.modals.add_species_modal')
@include('modules.consultations.modals.add_pet_modal')
@include('modules.consultations.modals.edit_pet_modal')
@include('modules.consultations.modals.add_pricing_rule_modal')
@include('modules.consultations.modals.create_preventive_control_modal')
@include('modules.consultations.modals.edit_consultation_modal')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/multimonth@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales-all.global.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        (function () {
            const pets = @json($petsJson);
            const speciesCatalog = @json($speciesJson);
            const pricingMap = @json($pricingMap);
            const inventoryCatalog = @json($inventoryJson);
            const dewormingCalendarEvents = @json($dewormingCalendarEvents);
            const consultationModal = document.getElementById('modalCreateConsultation');

            const petSelect = document.getElementById('new_pet_id');
            const speciesSelect = document.getElementById('new_species_id');
            const ownerInput = document.getElementById('new_owner_name');
            const breedInput = document.getElementById('new_pet_breed');
            const sizeInput = document.getElementById('new_pet_size');
            const diagnosisInput = document.getElementById('new_diagnosis');
            const costInput = document.getElementById('new_cost');
            const medicationsContainer = document.getElementById('medicationsContainer');
            const addMedicationRowBtn = document.getElementById('addMedicationRowBtn');
            const preventiveModal = document.getElementById('modalPreventiveControl');
            const preventivePetSelect = document.getElementById('preventive_pet_id');
            const preventiveSpeciesSelect = document.getElementById('preventive_species_id');
            const preventiveOwnerInput = document.getElementById('preventive_owner_name');
            const dewormingCalendarEl = document.getElementById('dewormingCalendar');
            const dewormingModalEl = document.getElementById('modalDewormingCalendarEvent');
            const fcEventStatus = document.getElementById('fcEventStatus');
            const fcEventPet = document.getElementById('fcEventPet');
            const fcEventOwner = document.getElementById('fcEventOwner');
            const fcEventDate = document.getElementById('fcEventDate');
            const fcEventNote = document.getElementById('fcEventNote');
            const fcNextDewormingAt = document.getElementById('fcNextDewormingAt');
            const fcDewormingNote = document.getElementById('fcDewormingNote');
            const fcRescheduleDateLabel = document.getElementById('fcRescheduleDateLabel');
            const fcRescheduleNoteLabel = document.getElementById('fcRescheduleNoteLabel');
            const fcRescheduleForm = document.getElementById('fcRescheduleForm');
            const calendarTabTrigger = document.querySelector('[data-bs-target="#tab-consult-calendar"]');
            let dewormingCalendarInstance = null;
            const editPetModal = document.getElementById('modalEditPet');
            const editPetForm = document.getElementById('editPetForm');
            const editPetSelector = document.getElementById('edit_pet_selector');
            const editPetName = document.getElementById('edit_pet_name');
            const editOwnerName = document.getElementById('edit_owner_name');
            const editOwnerEmail = document.getElementById('edit_owner_email');
            const editOwnerPhone = document.getElementById('edit_owner_phone');
            const editSpeciesId = document.getElementById('edit_species_id');
            const editBreed = document.getElementById('edit_breed');
            const editSizeCategory = document.getElementById('edit_size_category');

            function buildRescheduleUrl(consultationId) {
                if (!fcRescheduleForm) {
                    return '';
                }

                const base = fcRescheduleForm.dataset.actionBase || '';
                return `${base}/${consultationId}/deworming-reschedule`;
            }

            function buildVaccinationRescheduleUrl(consultationId) {
                if (!fcRescheduleForm) {
                    return '';
                }

                const base = fcRescheduleForm.dataset.actionBase || '';
                return `${base}/${consultationId}/vaccination-reschedule`;
            }

            function formatDateValue(value) {
                if (!value) {
                    return '-';
                }

                const date = new Date(`${value}T00:00:00`);
                if (Number.isNaN(date.getTime())) {
                    return value;
                }

                return date.toLocaleDateString('es-CR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                });
            }

            function initializeDewormingCalendar() {
                if (!dewormingCalendarEl || !window.FullCalendar) {
                    return;
                }

                if (dewormingCalendarInstance) {
                    dewormingCalendarInstance.updateSize();
                    return;
                }

                dewormingCalendarInstance = new window.FullCalendar.Calendar(dewormingCalendarEl, {
                    locale: 'es',
                    initialView: 'multiMonthYearGrid',
                    firstDay: 1,
                    height: 'auto',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'multiMonthYearGrid,multiMonthYearStack,multiMonthYearContinuous',
                    },
                    views: {
                        multiMonthYearGrid: {
                            type: 'multiMonth',
                            duration: { years: 1 },
                            multiMonthMaxColumns: 3,
                            multiMonthMinWidth: 250,
                            buttonText: 'Cuadricula',
                        },
                        multiMonthYearStack: {
                            type: 'multiMonth',
                            duration: { years: 1 },
                            multiMonthMaxColumns: 1,
                            multiMonthMinWidth: 420,
                            buttonText: 'Pila',
                        },
                        multiMonthYearContinuous: {
                            type: 'dayGrid',
                            duration: { years: 1 },
                            buttonText: 'Continuo',
                        },
                    },
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                    },
                    events: dewormingCalendarEvents,
                    eventClick: function (info) {
                        const props = info.event.extendedProps || {};
                        const consultationId = props.consultation_id;
                        const reminderType = props.reminder_type || 'desparasitacion';

                        if (!consultationId || !dewormingModalEl || !fcRescheduleForm) {
                            return;
                        }

                        fcEventStatus.textContent = `Estado: ${props.status_label || '-'}`;
                        fcEventPet.textContent = props.pet_name || '-';
                        fcEventOwner.textContent = props.owner_name || '-';
                        fcEventDate.textContent = formatDateValue(info.event.startStr);
                        fcEventNote.textContent = props.care_note || 'Sin nota';
                        fcNextDewormingAt.value = props.next_care_at || info.event.startStr;
                        fcDewormingNote.value = props.care_note || '';

                        if (reminderType === 'vacunacion') {
                            fcRescheduleDateLabel.textContent = 'Re-agendar proxima vacunacion';
                            fcRescheduleNoteLabel.textContent = 'Nota de vacunacion (opcional)';
                            fcNextDewormingAt.name = 'next_vaccination_at';
                            fcDewormingNote.name = 'vaccination_note';
                            fcRescheduleForm.action = buildVaccinationRescheduleUrl(consultationId);
                        } else {
                            fcRescheduleDateLabel.textContent = 'Re-agendar proxima desparasitacion';
                            fcRescheduleNoteLabel.textContent = 'Nota de desparasitacion (opcional)';
                            fcNextDewormingAt.name = 'next_deworming_at';
                            fcDewormingNote.name = 'deworming_note';
                            fcRescheduleForm.action = buildRescheduleUrl(consultationId);
                        }

                        if (window.bootstrap) {
                            window.bootstrap.Modal.getOrCreateInstance(dewormingModalEl).show();
                        }
                    },
                });

                dewormingCalendarInstance.render();
            }

            const petSpeciesModal = document.getElementById('pet_species_id_modal');
            const petBreedModal = document.getElementById('pet_breed_modal');
            const petBreedLabelModal = document.getElementById('pet_breed_label_text_modal');
            const petSizeModal = document.getElementById('pet_size_modal');

            function normalizeDiagnosis(value) {
                return (value || '').trim().toLowerCase();
            }

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function findPet(petId) {
                return pets.find((pet) => String(pet.id) === String(petId));
            }

            function speciesNameById(speciesId) {
                const species = speciesCatalog.find((item) => String(item.id) === String(speciesId));
                return species ? species.name.toLowerCase() : '';
            }

            function isBreedRequired(speciesName) {
                return speciesName.includes('canino') || speciesName.includes('perro') || speciesName.includes('ave');
            }

            function isSizeRequired(speciesName) {
                return speciesName.includes('canino') || speciesName.includes('perro') || speciesName.includes('felino') || speciesName.includes('gato');
            }

            function updatePetBreedModalBehavior() {
                if (!petSpeciesModal || !petBreedModal || !petBreedLabelModal || !petSizeModal) {
                    return;
                }

                const speciesName = speciesNameById(petSpeciesModal.value);
                if (speciesName.includes('ave')) {
                    petBreedLabelModal.textContent = 'Tipo de ave';
                    petBreedModal.placeholder = 'Ej: Agaporni, Periquito';
                } else if (speciesName.includes('canino') || speciesName.includes('perro')) {
                    petBreedLabelModal.textContent = 'Tipo de perro / raza';
                    petBreedModal.placeholder = 'Ej: Pastor Aleman, Husky';
                } else {
                    petBreedLabelModal.textContent = 'Tipo / Raza';
                    petBreedModal.placeholder = 'Opcional';
                }

                petBreedModal.required = isBreedRequired(speciesName);
                petSizeModal.required = isSizeRequired(speciesName);

                if (!petSizeModal.required) {
                    petSizeModal.value = '';
                }
            }

            function resolveCost() {
                const speciesId = speciesSelect.value;
                const diagnosis = normalizeDiagnosis(diagnosisInput.value);

                if (!speciesId || !diagnosis) {
                    return;
                }

                const bySpecies = pricingMap[String(speciesId)] || {};
                if (Object.prototype.hasOwnProperty.call(bySpecies, diagnosis)) {
                    costInput.value = Number(bySpecies[diagnosis]).toFixed(2);
                }
            }

            function medOptionsMarkup() {
                const grouped = inventoryCatalog.reduce(function (carry, item) {
                    const category = item.category || 'Sin categoria';

                    if (!carry[category]) {
                        carry[category] = [];
                    }

                    carry[category].push(item);
                    return carry;
                }, {});

                return Object.keys(grouped).map(function (category) {
                    const options = grouped[category].map(function (item) {
                        return `<option value="${item.id}" data-price="${item.unit_price}">${escapeHtml(item.name)}</option>`;
                    }).join('');

                    return `<optgroup label="${escapeHtml(category)}">${options}</optgroup>`;
                }).join('');
            }

            function initializeSelect2ForElement(element, dropdownParentElement) {
                if (!element || !window.jQuery || !window.jQuery.fn.select2) {
                    return;
                }

                const $element = window.jQuery(element);

                if ($element.hasClass('select2-hidden-accessible')) {
                    return;
                }

                $element.select2({
                    width: '100%',
                    dropdownParent: window.jQuery(dropdownParentElement || consultationModal || document.body),
                    placeholder: element.getAttribute('data-placeholder') || 'Selecciona una opcion',
                    allowClear: true,
                });
            }

            function initializeConsultationSelect2(scope, dropdownParentElement) {
                if (!scope) {
                    return;
                }

                scope.querySelectorAll('.consultation-select2').forEach(function (element) {
                    initializeSelect2ForElement(element, dropdownParentElement);
                });
            }

            function refreshMedicationIndexes(targetContainer) {
                if (!targetContainer) {
                    return;
                }

                const rows = targetContainer.querySelectorAll('.med-row');
                rows.forEach(function (row, index) {
                    row.querySelectorAll('[data-field]').forEach(function (field) {
                        field.name = `medications[${index}][${field.getAttribute('data-field')}]`;
                    });
                });
            }

            function getNextMedicationIndex(targetContainer) {
                let maxIndex = -1;

                if (!targetContainer) {
                    return 0;
                }

                targetContainer.querySelectorAll('[name^="medications["]').forEach(function (field) {
                    const match = String(field.name || '').match(/medications\[(\d+)\]/);
                    if (match) {
                        maxIndex = Math.max(maxIndex, Number(match[1]));
                    }
                });

                return maxIndex + 1;
            }

            function assignMedicationNames(row, index) {
                row.querySelectorAll('[data-field]').forEach(function (field) {
                    field.name = `medications[${index}][${field.getAttribute('data-field')}]`;
                });
            }

            function addMedicationRow(targetContainer, dropdownParentElement, reindexRows) {
                if (!targetContainer) {
                    return;
                }

                const shouldReindex = reindexRows !== false;
                const nextIndex = shouldReindex ? null : getNextMedicationIndex(targetContainer);

                const wrapper = document.createElement('div');
                wrapper.className = 'med-row';
                wrapper.innerHTML = `
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Producto</label>
                            <select class="form-select med-item-select consultation-select2" data-field="inventory_item_id" data-placeholder="Selecciona producto">
                                <option value="">Selecciona</option>
                                ${medOptionsMarkup()}
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Cant.</label>
                            <input type="number" min="1" value="1" class="form-control" data-field="quantity">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Precio</label>
                            <input type="number" step="0.01" min="0" class="form-control" data-field="unit_price">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Dosis</label>
                            <input class="form-control" placeholder="Ej: 5 ml" data-field="dosage">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Cada (h)</label>
                            <input type="number" min="1" class="form-control" data-field="frequency_hours">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Cada (dias)</label>
                            <input type="number" min="1" class="form-control" data-field="frequency_days">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Dias</label>
                            <input type="number" min="1" class="form-control" data-field="duration_days">
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-med-row d-inline-flex align-items-center gap-2"><i class="fa-solid fa-xmark"></i><span>Quitar</span></button>
                        </div>
                        <div class="col-12">
                            <input class="form-control form-control-sm" placeholder="Notas de aplicacion" data-field="administration_notes">
                        </div>
                    </div>
                `;

                targetContainer.appendChild(wrapper);
                if (shouldReindex) {
                    refreshMedicationIndexes(targetContainer);
                } else {
                    assignMedicationNames(wrapper, nextIndex);
                }

                if (dropdownParentElement) {
                    initializeConsultationSelect2(wrapper, dropdownParentElement);
                }

                const select = wrapper.querySelector('.med-item-select');
                const priceInput = wrapper.querySelector('[data-field="unit_price"]');

                select.addEventListener('change', function () {
                    const option = this.options[this.selectedIndex];
                    if (option && option.dataset.price) {
                        priceInput.value = Number(option.dataset.price).toFixed(2);
                    }
                });

                wrapper.querySelector('.remove-med-row').addEventListener('click', function () {
                    if (window.jQuery && window.jQuery.fn.select2 && window.jQuery(select).hasClass('select2-hidden-accessible')) {
                        window.jQuery(select).select2('destroy');
                    }

                    wrapper.remove();
                    if (shouldReindex) {
                        refreshMedicationIndexes(targetContainer);
                    }
                });
            }

            function applySelectedPetData() {
                if (!petSelect) {
                    return;
                }

                const pet = findPet(petSelect.value);
                if (!pet) {
                    ownerInput.value = '';
                    breedInput.value = '';
                    if (sizeInput) {
                        sizeInput.value = '';
                    }
                    return;
                }

                if (pet.species_id && speciesSelect) {
                    speciesSelect.value = String(pet.species_id);
                    if (window.jQuery && window.jQuery.fn.select2) {
                        window.jQuery(speciesSelect).trigger('change');
                    }
                }

                ownerInput.value = pet.owner_name || ownerInput.value || '';
                breedInput.value = pet.breed || breedInput.value || '';
                if (sizeInput) {
                    sizeInput.value = pet.size_category
                        ? pet.size_category.charAt(0).toUpperCase() + pet.size_category.slice(1)
                        : (sizeInput.value || '');
                }

                resolveCost();
            }

            function applyPreventivePetData() {
                if (!preventivePetSelect) {
                    return;
                }

                const pet = findPet(preventivePetSelect.value);
                if (!pet) {
                    if (preventiveOwnerInput) {
                        preventiveOwnerInput.value = '';
                    }
                    return;
                }

                if (preventiveSpeciesSelect && pet.species_id) {
                    preventiveSpeciesSelect.value = String(pet.species_id);
                    if (window.jQuery && window.jQuery.fn.select2) {
                        window.jQuery(preventiveSpeciesSelect).trigger('change');
                    }
                }

                if (preventiveOwnerInput) {
                    preventiveOwnerInput.value = pet.owner_name || preventiveOwnerInput.value || '';
                }
            }

            function applyEditPetData() {
                if (!editPetSelector || !editPetForm) {
                    return;
                }

                const pet = findPet(editPetSelector.value);
                const actionBase = editPetForm.dataset.actionBase || '';

                if (!pet) {
                    editPetForm.action = actionBase;
                    if (editPetName) editPetName.value = '';
                    if (editOwnerName) editOwnerName.value = '';
                    if (editOwnerEmail) editOwnerEmail.value = '';
                    if (editOwnerPhone) editOwnerPhone.value = '';
                    if (editSpeciesId) editSpeciesId.value = '';
                    if (editBreed) editBreed.value = '';
                    if (editSizeCategory) editSizeCategory.value = '';
                    return;
                }

                editPetForm.action = `${actionBase}/${pet.id}`;
                if (editPetName) editPetName.value = pet.name || '';
                if (editOwnerName) editOwnerName.value = pet.owner_name || '';
                if (editOwnerEmail) editOwnerEmail.value = pet.owner_email || '';
                if (editOwnerPhone) editOwnerPhone.value = pet.owner_phone || '';
                if (editSpeciesId) {
                    editSpeciesId.value = pet.species_id ? String(pet.species_id) : '';
                    if (window.jQuery && window.jQuery.fn.select2) {
                        window.jQuery(editSpeciesId).trigger('change');
                    }
                }
                if (editBreed) editBreed.value = pet.breed || '';
                if (editSizeCategory) editSizeCategory.value = pet.size_category || '';
            }

            if (petSelect) {
                petSelect.addEventListener('change', applySelectedPetData);

                if (window.jQuery && window.jQuery.fn.select2) {
                    window.jQuery(petSelect).on('select2:select select2:clear', applySelectedPetData);
                }
            }

            if (preventivePetSelect) {
                preventivePetSelect.addEventListener('change', applyPreventivePetData);

                if (window.jQuery && window.jQuery.fn.select2) {
                    window.jQuery(preventivePetSelect).on('select2:select select2:clear', applyPreventivePetData);
                }
            }

            if (editPetSelector) {
                editPetSelector.addEventListener('change', applyEditPetData);

                if (window.jQuery && window.jQuery.fn.select2) {
                    window.jQuery(editPetSelector).on('select2:select select2:clear', applyEditPetData);
                }
            }

            if (speciesSelect) {
                speciesSelect.addEventListener('change', resolveCost);
            }

            if (diagnosisInput) {
                diagnosisInput.addEventListener('input', resolveCost);
                diagnosisInput.addEventListener('blur', resolveCost);
            }

            if (petSpeciesModal) {
                petSpeciesModal.addEventListener('change', updatePetBreedModalBehavior);
                updatePetBreedModalBehavior();
            }

            if (addMedicationRowBtn) {
                addMedicationRowBtn.addEventListener('click', function () {
                    addMedicationRow(medicationsContainer, consultationModal, true);
                });
                addMedicationRow(medicationsContainer, consultationModal, true);
            }

            document.querySelectorAll('[id^="addMedicationRowBtnEdit-"]').forEach(function (button) {
                const consultationId = button.id.replace('addMedicationRowBtnEdit-', '');
                const editModal = document.getElementById(`modalEditConsultation-${consultationId}`);
                const editContainer = document.getElementById(`medicationsContainerEdit-${consultationId}`);

                if (!editContainer) {
                    return;
                }

                button.addEventListener('click', function () {
                    addMedicationRow(editContainer, editModal, false);
                });

                if (editModal) {
                    editModal.addEventListener('shown.bs.modal', function () {
                        initializeConsultationSelect2(editModal, editModal);
                    });
                }
            });

            if (consultationModal) {
                consultationModal.addEventListener('shown.bs.modal', function () {
                    initializeConsultationSelect2(consultationModal, consultationModal);
                    applySelectedPetData();
                });
            }

            if (preventiveModal) {
                preventiveModal.addEventListener('shown.bs.modal', function () {
                    initializeConsultationSelect2(preventiveModal, preventiveModal);
                    applyPreventivePetData();
                });
            }

            if (editPetModal) {
                editPetModal.addEventListener('shown.bs.modal', function () {
                    initializeConsultationSelect2(editPetModal, editPetModal);
                    applyEditPetData();
                });
            }

            if (window.ClassicEditor) {
                ClassicEditor.create(document.querySelector('#treatmentEditor')).catch(function () {
                    // Ignore editor init issues to avoid blocking form usage.
                });
            }

            (function openConsultationFromQuery() {
                if (!window.bootstrap) {
                    return;
                }

                const params = new URLSearchParams(window.location.search);
                const openConsultationId = params.get('open_consultation_id');

                if (!openConsultationId) {
                    return;
                }

                const tableTabButton = document.querySelector('[data-bs-target="#tab-consult-table"]');
                if (tableTabButton) {
                    window.bootstrap.Tab.getOrCreateInstance(tableTabButton).show();
                }

                const modal = document.getElementById(`modalEditConsultation-${openConsultationId}`);
                if (!modal) {
                    return;
                }

                setTimeout(function () {
                    window.bootstrap.Modal.getOrCreateInstance(modal).show();
                }, 180);
            })();

            if (calendarTabTrigger) {
                calendarTabTrigger.addEventListener('shown.bs.tab', function () {
                    initializeDewormingCalendar();
                });
            }
        })();
    </script>
@endpush
