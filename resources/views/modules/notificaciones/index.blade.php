@extends('layouts.app')

@section('title', 'Avisos | Emi Veterinaria')

@section('content')
<style>
    .avisos-shell {
        display: grid;
        gap: 1rem;
    }

    .avisos-hero {
        background: radial-gradient(circle at 10% 10%, #a995cf 0%, #5d4a82 45%, #3d3456 100%);
        border-radius: 18px;
        color: #fff;
        padding: 1.2rem;
        box-shadow: 0 14px 30px rgba(93, 74, 130, 0.35);
    }

    .avisos-actions-panel {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.9fr);
        gap: 1rem;
        align-items: stretch;
    }

    .avisos-primary-action,
    .avisos-secondary-actions {
        background: var(--emi-surface);
        border: 1px solid var(--emi-border);
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(37, 35, 50, 0.08);
    }

    .avisos-primary-action {
        padding: 1.1rem;
        background: linear-gradient(135deg, #f4f1fb 0%, #e8dff5 55%, var(--emi-surface) 100%);
        border-color: #d9cfe8;
    }

    .avisos-primary-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        width: 100%;
        border: 0;
        border-radius: 16px;
        padding: 1rem 1.1rem;
        color: #fff;
        text-decoration: none;
        background: linear-gradient(135deg, #8b78b9 0%, #5d4a82 55%, #4a3d66 100%);
        box-shadow: 0 18px 28px rgba(93, 74, 130, 0.28);
    }

    .avisos-primary-trigger:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    .avisos-primary-trigger strong {
        display: block;
        font-size: 1.05rem;
        text-align: left;
    }

    .avisos-primary-trigger span {
        display: block;
        opacity: 0.86;
        font-size: 0.85rem;
        text-align: left;
    }

    .avisos-primary-trigger i {
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .avisos-section-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .avisos-secondary-actions {
        padding: 1rem;
    }

    .avisos-secondary-trigger {
        width: 100%;
        text-align: left;
        border-radius: 14px;
        padding: 0.85rem;
        border: 1px solid #d9cfe8;
        background: #f8f7fb;
        color: #252332;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
    }

    .avisos-secondary-trigger:hover {
        border-color: #b9abc9;
        color: #252332;
    }

    .avisos-secondary-trigger i {
        color: #5d4a82;
        margin-right: 0.45rem;
    }

    .avisos-secondary-trigger small {
        display: block;
        color: #6f6a80;
        margin-top: 0.2rem;
    }

    .avisos-table-panel {
        background: var(--emi-surface);
        border: 1px solid var(--emi-border);
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(37, 35, 50, 0.08);
        overflow: hidden;
    }

    .avisos-table-body {
        padding: 1rem;
    }

    .table-filter-wrap {
        background: #f8f7fb;
        border: 1px dashed #d9cfe8;
        border-radius: 12px;
        padding: 0.85rem;
        margin-bottom: 0.8rem;
    }

    .table-avisos thead th {
        position: sticky;
        top: 0;
        background: #f8f7fb;
        z-index: 1;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #6b7280;
    }

    .aviso-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 999px;
        padding: 0.25rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .aviso-badge-promocion {
        background: #dbeafe;
        color: #0369a1;
        border-color: #bfdbfe;
    }

    .aviso-badge-cierre {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .aviso-badge-aviso {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }

    .aviso-badge-otro {
        background: #ece9f4;
        color: #4a3d66;
        border-color: #d9cfe8;
    }

    .action-icon-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    @media (max-width: 991.98px) {
        .avisos-actions-panel {
            grid-template-columns: 1fr;
        }
    }

    .modal-aviso .modal-header {
        background: linear-gradient(135deg, #8b78b9 0%, #5d4a82 55%, #4a3d66 100%);
        color: #fff;
    }
</style>

<div class="container-fluid py-2 py-md-3 avisos-shell">
    <section class="avisos-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-bell"></i> Centro de comunicacion</span>
                <h1 class="h3 fw-bold mb-1">Gestionar Avisos</h1>
                <p class="mb-0 opacity-75">Promociones, cierres programados y comunicados para clientes.</p>
            </div>
            <div class="small d-inline-flex align-items-center gap-2 opacity-75">
                <i class="fa-solid fa-users"></i>
                <span>Suscriptores activos: {{ $totalSuscriptores }}</span>
            </div>
        </div>
    </section>

    <section class="avisos-actions-panel">
        <div class="avisos-primary-action">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
                <div>
                    <span class="avisos-section-kicker text-success-emphasis mb-1"><i class="fa-solid fa-star"></i> Accion principal</span>
                    <h2 class="h4 mb-1">Nuevo aviso</h2>
                    <p class="text-muted mb-0">Publica promociones o avisos de cierre en pocos pasos.</p>
                </div>
            </div>
            <button type="button" class="avisos-primary-trigger" data-bs-toggle="modal" data-bs-target="#modalCrearAviso">
                <div>
                    <strong>Crear aviso</strong>
                    <span>Define titulo, vigencia y tipo de comunicado.</span>
                </div>
                <i class="fa-solid fa-bullhorn"></i>
            </button>
        </div>

        <div class="avisos-secondary-actions">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-3 flex-wrap">
                <div>
                    <span class="avisos-section-kicker text-secondary mb-1"><i class="fa-solid fa-layer-group"></i> Auxiliares</span>
                    <h3 class="h6 mb-0">Gestion rapida</h3>
                </div>
            </div>
            <a href="{{ route('suscriptores-listar') }}" class="avisos-secondary-trigger">
                <div>
                    <strong><i class="fa-solid fa-envelope-open-text"></i> Suscriptores</strong>
                    <small>Administra correos de clientes</small>
                </div>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
    </section>

    <section class="avisos-table-panel">
        <div class="avisos-table-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <h2 class="h5 mb-0 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                    Historico de avisos
                </h2>
                <span class="small text-muted d-inline-flex align-items-center gap-2"><i class="fa-solid fa-list"></i>{{ $notificaciones->total() }} registros</span>
            </div>

            <form method="GET" action="{{ route('notificaciones-listar') }}" class="table-filter-wrap row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-filter"></i><span>Tipo de aviso</span></label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos los tipos</option>
                        <option value="promocion" @selected(request('tipo') === 'promocion')>Promocion</option>
                        <option value="cierre" @selected(request('tipo') === 'cierre')>Cierre</option>
                        <option value="aviso" @selected(request('tipo') === 'aviso')>Aviso</option>
                        <option value="otro" @selected(request('tipo') === 'otro')>Otro</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="activa" @selected(request('estado') === 'activa')>Activo</option>
                        <option value="inactiva" @selected(request('estado') === 'inactiva')>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Buscar</span>
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('notificaciones-listar') }}" class="btn btn-outline-secondary w-100 d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-modern table-avisos align-middle">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Titulo</th>
                            <th>Descripcion</th>
                            <th>Vigencia</th>
                            <th>Enviados</th>
                            <th>Estado</th>
                            <th>Creado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($notificaciones as $notificacion)
                            <tr>
                                <td>
                                    @if($notificacion->tipo === 'promocion')
                                        <span class="aviso-badge aviso-badge-promocion"><i class="fa-solid fa-gift"></i> Promocion</span>
                                    @elseif($notificacion->tipo === 'cierre')
                                        <span class="aviso-badge aviso-badge-cierre"><i class="fa-solid fa-clock"></i> Cierre</span>
                                    @elseif($notificacion->tipo === 'aviso')
                                        <span class="aviso-badge aviso-badge-aviso"><i class="fa-solid fa-bullhorn"></i> Aviso</span>
                                    @else
                                        <span class="aviso-badge aviso-badge-otro"><i class="fa-solid fa-envelope"></i> Otro</span>
                                    @endif
                                </td>
                                <td class="fw-semibold text-dark">{{ Str::limit($notificacion->titulo, 28) }}</td>
                                <td class="small text-muted">{{ Str::limit($notificacion->descripcion, 42) }}</td>
                                <td class="small">
                                    <div><strong>Inicio:</strong> {{ $notificacion->fecha_inicio->format('d/m/Y H:i') }}</div>
                                    @if($notificacion->fecha_fin)
                                        <div><strong>Fin:</strong> {{ $notificacion->fecha_fin->format('d/m/Y H:i') }}</div>
                                    @else
                                        <div class="text-muted">Sin fecha de vencimiento</div>
                                    @endif
                                </td>
                                <td>{{ $notificacion->cantidad_enviadas }}</td>
                                <td>
                                    @if($notificacion->activa)
                                        <span class="badge text-bg-success">Activo</span>
                                    @else
                                        <span class="badge text-bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td class="small">{{ $notificacion->creadaPor->name }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary action-icon-btn" data-bs-toggle="modal" data-bs-target="#modalEditarAviso{{ $notificacion->id }}" title="Editar aviso" aria-label="Editar aviso">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="{{ route('notificaciones-enviar', $notificacion) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-info action-icon-btn" title="Enviar correo" aria-label="Enviar correo">
                                                <i class="fa-solid fa-paper-plane"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('notificaciones-cambiar-estado', $notificacion) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary action-icon-btn" title="{{ $notificacion->activa ? 'Desactivar' : 'Activar' }}" aria-label="Cambiar estado">
                                                <i class="fa-solid {{ $notificacion->activa ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-outline-danger action-icon-btn" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ $notificacion->id }}" title="Eliminar aviso" aria-label="Eliminar aviso">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        <div class="modal fade" id="modalEditarAviso{{ $notificacion->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content modal-aviso border-0 shadow">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>Editar aviso</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('notificaciones-actualizar', $notificacion) }}" novalidate>
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="titulo_editar_{{ $notificacion->id }}" class="form-label fw-semibold">Titulo <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control @if(session('editar_notificacion_id') == $notificacion->id && $errors->has('titulo')) is-invalid @endif" id="titulo_editar_{{ $notificacion->id }}" name="titulo" value="{{ session('editar_notificacion_id') == $notificacion->id ? old('titulo', $notificacion->titulo) : $notificacion->titulo }}" required>
                                                                @if(session('editar_notificacion_id') == $notificacion->id)
                                                                    @error('titulo')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                @endif
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="descripcion_editar_{{ $notificacion->id }}" class="form-label fw-semibold">Descripcion <span class="text-danger">*</span></label>
                                                                <textarea class="form-control @if(session('editar_notificacion_id') == $notificacion->id && $errors->has('descripcion')) is-invalid @endif" id="descripcion_editar_{{ $notificacion->id }}" name="descripcion" rows="4" required>{{ session('editar_notificacion_id') == $notificacion->id ? old('descripcion', $notificacion->descripcion) : $notificacion->descripcion }}</textarea>
                                                                @if(session('editar_notificacion_id') == $notificacion->id)
                                                                    @error('descripcion')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                @endif
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="tipo_editar_{{ $notificacion->id }}" class="form-label fw-semibold">Tipo de aviso <span class="text-danger">*</span></label>
                                                                    @php
                                                                        $tipoSeleccionado = session('editar_notificacion_id') == $notificacion->id
                                                                            ? old('tipo', $notificacion->tipo)
                                                                            : $notificacion->tipo;
                                                                    @endphp
                                                                    <select class="form-select @if(session('editar_notificacion_id') == $notificacion->id && $errors->has('tipo')) is-invalid @endif" id="tipo_editar_{{ $notificacion->id }}" name="tipo" required>
                                                                        <option value="promocion" @selected($tipoSeleccionado === 'promocion')>Promocion</option>
                                                                        <option value="cierre" @selected($tipoSeleccionado === 'cierre')>Cierre (No abriremos)</option>
                                                                        <option value="aviso" @selected($tipoSeleccionado === 'aviso')>Aviso importante</option>
                                                                        <option value="otro" @selected($tipoSeleccionado === 'otro')>Otro</option>
                                                                    </select>
                                                                    @if(session('editar_notificacion_id') == $notificacion->id)
                                                                        @error('tipo')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="fecha_inicio_editar_{{ $notificacion->id }}" class="form-label fw-semibold">Fecha y hora de inicio <span class="text-danger">*</span></label>
                                                                    <input type="datetime-local" class="form-control @if(session('editar_notificacion_id') == $notificacion->id && $errors->has('fecha_inicio')) is-invalid @endif" id="fecha_inicio_editar_{{ $notificacion->id }}" name="fecha_inicio" value="{{ session('editar_notificacion_id') == $notificacion->id ? old('fecha_inicio', $notificacion->fecha_inicio?->format('Y-m-d\\TH:i')) : $notificacion->fecha_inicio?->format('Y-m-d\\TH:i') }}" required>
                                                                    @if(session('editar_notificacion_id') == $notificacion->id)
                                                                        @error('fecha_inicio')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="fecha_fin_editar_{{ $notificacion->id }}" class="form-label fw-semibold">Fecha y hora de fin <span class="text-muted">(opcional)</span></label>
                                                                <input type="datetime-local" class="form-control @if(session('editar_notificacion_id') == $notificacion->id && $errors->has('fecha_fin')) is-invalid @endif" id="fecha_fin_editar_{{ $notificacion->id }}" name="fecha_fin" value="{{ session('editar_notificacion_id') == $notificacion->id ? old('fecha_fin', $notificacion->fecha_fin?->format('Y-m-d\\TH:i')) : $notificacion->fecha_fin?->format('Y-m-d\\TH:i') }}">
                                                                @if(session('editar_notificacion_id') == $notificacion->id)
                                                                    @error('fecha_fin')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                @endif
                                                            </div>

                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox" id="activa_editar_{{ $notificacion->id }}" name="activa" value="1" @checked(session('editar_notificacion_id') == $notificacion->id ? old('activa', $notificacion->activa) : $notificacion->activa)>
                                                                <label class="form-check-label" for="activa_editar_{{ $notificacion->id }}">Aviso activo</label>
                                                            </div>
                                                            <div class="form-check mb-1">
                                                                <input class="form-check-input" type="checkbox" id="enviar_ahora_editar_{{ $notificacion->id }}" name="enviar_ahora" value="1" @checked(session('editar_notificacion_id') == $notificacion->id ? old('enviar_ahora') : false)>
                                                                <label class="form-check-label" for="enviar_ahora_editar_{{ $notificacion->id }}">Enviar por correo a los suscriptores ahora</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Guardar cambios</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="modalEliminar{{ $notificacion->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h6 class="modal-title">Eliminar aviso</h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-0">Se eliminara el aviso <strong>{{ $notificacion->titulo }}</strong>. Esta accion no se puede deshacer.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                                        <form action="{{ route('notificaciones-eliminar', $notificacion) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox fa-2x mb-3 d-block"></i>
                                    <strong>No hay avisos registrados</strong>
                                    <p class="small mb-0">Crea uno nuevo para comenzar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($notificaciones->hasPages())
                <div class="mt-3">
                    {{ $notificaciones->links() }}
                </div>
            @endif
        </div>
    </section>

    <div class="modal fade" id="modalCrearAviso" tabindex="-1" aria-labelledby="modalCrearAvisoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content modal-aviso border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearAvisoLabel"><i class="fa-solid fa-bullhorn me-2"></i>Crear aviso</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form method="POST" action="{{ route('notificaciones-guardar') }}" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titulo" class="form-label fw-semibold">Titulo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Ej: 20% de descuento en consultas" required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-semibold">Descripcion <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="4" placeholder="Ingresa la descripcion detallada del aviso..." required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label fw-semibold">Tipo de aviso <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                                    <option value="">Selecciona un tipo...</option>
                                    <option value="promocion" @selected(old('tipo') === 'promocion')>Promocion</option>
                                    <option value="cierre" @selected(old('tipo') === 'cierre')>Cierre (No abriremos)</option>
                                    <option value="aviso" @selected(old('tipo') === 'aviso')>Aviso importante</option>
                                    <option value="otro" @selected(old('tipo') === 'otro')>Otro</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_inicio" class="form-label fw-semibold">Fecha y hora de inicio <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label fw-semibold">Fecha y hora de fin <span class="text-muted">(opcional)</span></label>
                            <input type="datetime-local" class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}">
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="activa" name="activa" value="1" @checked(old('activa', '1') == '1')>
                            <label class="form-check-label" for="activa">Activar aviso inmediatamente</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="enviar_ahora" name="enviar_ahora" value="1" @checked(old('enviar_ahora'))>
                            <label class="form-check-label" for="enviar_ahora">Enviar por correo a los suscriptores ahora</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Crear aviso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if((!session('editar_notificacion_id')) && ($errors->has('titulo') || $errors->has('descripcion') || $errors->has('tipo') || $errors->has('fecha_inicio') || $errors->has('fecha_fin')))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalElement = document.getElementById('modalCrearAviso');
            if (!modalElement || typeof bootstrap === 'undefined') return;
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        });
    </script>
@endif

@if(session('editar_notificacion_id'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalElement = document.getElementById('modalEditarAviso{{ session('editar_notificacion_id') }}');
            if (!modalElement || typeof bootstrap === 'undefined') return;
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        });
    </script>
@endif
@endsection
