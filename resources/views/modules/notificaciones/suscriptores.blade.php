@extends('layouts.app')

@section('title', 'Suscriptores | Emi Veterinaria')

@section('content')
<style>
    .suscriptores-shell {
        display: grid;
        gap: 1rem;
    }

    .suscriptores-hero {
        background: radial-gradient(circle at 10% 10%, #a995cf 0%, #5d4a82 45%, #3d3456 100%);
        border-radius: 18px;
        color: #fff;
        padding: 1.2rem;
        box-shadow: 0 14px 30px rgba(93, 74, 130, 0.35);
    }

    .suscriptores-actions-panel {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.9fr);
        gap: 1rem;
        align-items: stretch;
    }

    .suscriptores-primary-action,
    .suscriptores-secondary-actions {
        background: var(--emi-surface);
        border: 1px solid var(--emi-border);
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(37, 35, 50, 0.08);
    }

    .suscriptores-primary-action {
        padding: 1.1rem;
        background: linear-gradient(135deg, #f4f1fb 0%, #e8dff5 55%, var(--emi-surface) 100%);
        border-color: #d9cfe8;
    }

    .suscriptores-primary-trigger {
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

    .suscriptores-primary-trigger:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    .suscriptores-primary-trigger strong {
        display: block;
        font-size: 1.05rem;
        text-align: left;
    }

    .suscriptores-primary-trigger span {
        display: block;
        opacity: 0.86;
        font-size: 0.85rem;
        text-align: left;
    }

    .suscriptores-primary-trigger i {
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .suscriptores-section-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .suscriptores-secondary-actions {
        padding: 1rem;
    }

    .suscriptores-secondary-trigger {
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

    .suscriptores-secondary-trigger:hover {
        border-color: #b9abc9;
        color: #252332;
    }

    .suscriptores-secondary-trigger i {
        color: #5d4a82;
        margin-right: 0.45rem;
    }

    .suscriptores-secondary-trigger small {
        display: block;
        color: #6f6a80;
        margin-top: 0.2rem;
    }

    .suscriptores-table-panel {
        background: var(--emi-surface);
        border: 1px solid var(--emi-border);
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(37, 35, 50, 0.08);
        overflow: hidden;
    }

    .suscriptores-table-body {
        padding: 1rem;
    }

    .table-filter-wrap {
        background: #f8f7fb;
        border: 1px dashed #d9cfe8;
        border-radius: 12px;
        padding: 0.85rem;
        margin-bottom: 0.8rem;
    }

    .table-suscriptores thead th {
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

    .suscriptor-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 999px;
        padding: 0.25rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .suscriptor-badge-todos {
        background: #dbeafe;
        color: #0369a1;
        border-color: #bfdbfe;
    }

    .suscriptor-badge-promociones {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }

    .suscriptor-badge-cierres {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .correo-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #8b78b9 0%, #5d4a82 70%, #4a3d66 100%);
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
        .suscriptores-actions-panel {
            grid-template-columns: 1fr;
        }
    }

    .modal-suscriptor .modal-header {
        background: linear-gradient(135deg, #8b78b9 0%, #5d4a82 55%, #4a3d66 100%);
        color: #fff;
    }
</style>

<div class="container-fluid py-2 py-md-3 suscriptores-shell">
    <section class="suscriptores-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-envelope"></i> Centro de comunicacion</span>
                <h1 class="h3 fw-bold mb-1">Gestionar Suscriptores</h1>
                <p class="mb-0 opacity-75">Controla correos de clientes para promociones, cierres y comunicados.</p>
            </div>
            <a href="{{ route('notificaciones-listar') }}" class="btn btn-outline-light d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-house"></i>
                <span>Avisos</span>
            </a>
        </div>
    </section>

    <section class="suscriptores-actions-panel">
        <div class="suscriptores-primary-action">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
                <div>
                    <span class="suscriptores-section-kicker text-success-emphasis mb-1"><i class="fa-solid fa-star"></i> Accion principal</span>
                    <h2 class="h4 mb-1">Nuevo suscriptor</h2>
                    <p class="text-muted mb-0">Registra correos de clientes para envio de avisos.</p>
                </div>
            </div>
            <button type="button" class="suscriptores-primary-trigger" data-bs-toggle="modal" data-bs-target="#modalCrearSuscriptor">
                <div>
                    <strong>Agregar suscriptor</strong>
                    <span>Asigna tipo de avisos y estado de suscripcion.</span>
                </div>
                <i class="fa-solid fa-user-plus"></i>
            </button>
        </div>

        <div class="suscriptores-secondary-actions">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-3 flex-wrap">
                <div>
                    <span class="suscriptores-section-kicker text-secondary mb-1"><i class="fa-solid fa-layer-group"></i> Auxiliares</span>
                    <h3 class="h6 mb-0">Gestion rapida</h3>
                </div>
            </div>
            <a href="{{ route('notificaciones-listar') }}" class="suscriptores-secondary-trigger">
                <div>
                    <strong><i class="fa-solid fa-bell"></i> Modulo de avisos</strong>
                    <small>Volver a promociones y comunicados</small>
                </div>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
    </section>

    <section class="suscriptores-table-panel">
        <div class="suscriptores-table-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <h2 class="h5 mb-0 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                    Historico de suscriptores
                </h2>
                <span class="small text-muted d-inline-flex align-items-center gap-2"><i class="fa-solid fa-list"></i>{{ $suscriptores->total() }} registros</span>
            </div>

            <form method="GET" action="{{ route('suscriptores-listar') }}" class="table-filter-wrap row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-filter"></i><span>Estado de suscripcion</span></label>
                    <select name="suscrito" class="form-select">
                        <option value="">Todos</option>
                        <option value="true" @selected(request('suscrito') === 'true')>Suscrito</option>
                        <option value="false" @selected(request('suscrito') === 'false')>Desuscrito</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo de avisos</label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos</option>
                        <option value="todos" @selected(request('tipo') === 'todos')>Todos los avisos</option>
                        <option value="promociones" @selected(request('tipo') === 'promociones')>Solo promociones</option>
                        <option value="cierres" @selected(request('tipo') === 'cierres')>Solo cierres</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Buscar</span>
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('suscriptores-listar') }}" class="btn btn-outline-secondary w-100 d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-modern table-suscriptores align-middle">
                    <thead>
                        <tr>
                            <th>Correo</th>
                            <th>Tipo de avisos</th>
                            <th>Estado</th>
                            <th>Usuario asociado</th>
                            <th>Suscrito desde</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suscriptores as $suscriptor)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="correo-avatar">{{ strtoupper(substr($suscriptor->correo, 0, 1)) }}</span>
                                        <div>
                                            <span class="d-block fw-semibold text-dark">{{ $suscriptor->correo }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($suscriptor->tipo_avisos === 'todos')
                                        <span class="suscriptor-badge suscriptor-badge-todos"><i class="fa-solid fa-envelope-open-text"></i> Todos</span>
                                    @elseif($suscriptor->tipo_avisos === 'promociones')
                                        <span class="suscriptor-badge suscriptor-badge-promociones"><i class="fa-solid fa-gift"></i> Promociones</span>
                                    @else
                                        <span class="suscriptor-badge suscriptor-badge-cierres"><i class="fa-solid fa-clock"></i> Cierres</span>
                                    @endif
                                </td>
                                <td>
                                    @if($suscriptor->suscrito)
                                        <span class="badge text-bg-success">Suscrito</span>
                                    @else
                                        <span class="badge text-bg-secondary">Desuscrito</span>
                                    @endif
                                </td>
                                <td>
                                    @if($suscriptor->usuario)
                                        <div class="small">
                                            <strong class="d-block text-dark">{{ $suscriptor->usuario->name }}</strong>
                                            <span class="text-muted">{{ $suscriptor->usuario->email }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">Sin usuario</span>
                                    @endif
                                </td>
                                <td class="small">{{ $suscriptor->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary action-icon-btn" data-bs-toggle="modal" data-bs-target="#modalEditarSuscriptor{{ $suscriptor->id }}" title="Editar suscriptor" aria-label="Editar suscriptor">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="{{ route('suscriptores-cambiar-estado', $suscriptor) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary action-icon-btn" title="{{ $suscriptor->suscrito ? 'Desuscribir' : 'Suscribir' }}" aria-label="Cambiar estado de suscripcion">
                                                <i class="fa-solid {{ $suscriptor->suscrito ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-outline-danger action-icon-btn" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ $suscriptor->id }}" title="Eliminar suscriptor" aria-label="Eliminar suscriptor">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        <div class="modal fade" id="modalEditarSuscriptor{{ $suscriptor->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content modal-suscriptor border-0 shadow">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa-solid fa-user-pen me-2"></i>Editar suscriptor</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('suscriptores-actualizar', $suscriptor) }}" novalidate>
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="correo_editar_{{ $suscriptor->id }}" class="form-label fw-semibold">Correo electronico <span class="text-danger">*</span></label>
                                                                <input type="email" class="form-control @if(session('editar_suscriptor_id') == $suscriptor->id && $errors->has('correo')) is-invalid @endif" id="correo_editar_{{ $suscriptor->id }}" name="correo" value="{{ session('editar_suscriptor_id') == $suscriptor->id ? old('correo', $suscriptor->correo) : $suscriptor->correo }}" required>
                                                                @if(session('editar_suscriptor_id') == $suscriptor->id)
                                                                    @error('correo')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                @endif
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="tipo_avisos_editar_{{ $suscriptor->id }}" class="form-label fw-semibold">Tipo de avisos <span class="text-danger">*</span></label>
                                                                @php
                                                                    $tipoAvisoSuscriptor = session('editar_suscriptor_id') == $suscriptor->id
                                                                        ? old('tipo_avisos', $suscriptor->tipo_avisos)
                                                                        : $suscriptor->tipo_avisos;
                                                                @endphp
                                                                <select class="form-select @if(session('editar_suscriptor_id') == $suscriptor->id && $errors->has('tipo_avisos')) is-invalid @endif" id="tipo_avisos_editar_{{ $suscriptor->id }}" name="tipo_avisos" required>
                                                                    <option value="todos" @selected($tipoAvisoSuscriptor === 'todos')>Todos los avisos</option>
                                                                    <option value="promociones" @selected($tipoAvisoSuscriptor === 'promociones')>Solo promociones</option>
                                                                    <option value="cierres" @selected($tipoAvisoSuscriptor === 'cierres')>Solo cierres</option>
                                                                </select>
                                                                @if(session('editar_suscriptor_id') == $suscriptor->id)
                                                                    @error('tipo_avisos')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                @endif
                                                            </div>

                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="suscrito_editar_{{ $suscriptor->id }}" name="suscrito" value="1" @checked(session('editar_suscriptor_id') == $suscriptor->id ? old('suscrito', $suscriptor->suscrito) : $suscriptor->suscrito)>
                                                                <label class="form-check-label" for="suscrito_editar_{{ $suscriptor->id }}">Suscriptor activo</label>
                                                            </div>

                                                            @if($suscriptor->usuario)
                                                                <div class="alert alert-info mt-3 mb-0 small">
                                                                    <strong>Usuario asociado:</strong> {{ $suscriptor->usuario->name }} ({{ $suscriptor->usuario->email }})
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Guardar cambios</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="modalEliminar{{ $suscriptor->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h6 class="modal-title">Eliminar suscriptor</h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-0">Confirma que deseas eliminar a <strong>{{ $suscriptor->correo }}</strong>.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <form action="{{ route('suscriptores-eliminar', $suscriptor) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox fa-2x mb-3 d-block"></i>
                                    <strong>No hay suscriptores registrados</strong>
                                    <p class="small mb-0">Agrega uno nuevo para comenzar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @if($suscriptores->hasPages())
        <div class="mt-2">
            {{ $suscriptores->links() }}
        </div>
    @endif

    <div class="modal fade" id="modalCrearSuscriptor" tabindex="-1" aria-labelledby="modalCrearSuscriptorLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-suscriptor border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearSuscriptorLabel"><i class="fa-solid fa-user-plus me-2"></i>Agregar suscriptor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form method="POST" action="{{ route('suscriptores-guardar') }}" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="correo" class="form-label fw-semibold">Correo electronico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo') }}" placeholder="cliente@ejemplo.com" required>
                            @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tipo_avisos" class="form-label fw-semibold">Tipo de avisos <span class="text-danger">*</span></label>
                            <select class="form-select @error('tipo_avisos') is-invalid @enderror" id="tipo_avisos" name="tipo_avisos" required>
                                <option value="">Selecciona el tipo...</option>
                                <option value="todos" @selected(old('tipo_avisos') === 'todos')>Todos los avisos</option>
                                <option value="promociones" @selected(old('tipo_avisos') === 'promociones')>Solo promociones</option>
                                <option value="cierres" @selected(old('tipo_avisos') === 'cierres')>Solo cierres</option>
                            </select>
                            @error('tipo_avisos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-semibold">Usuario asociado</label>
                            <input type="hidden" id="usuario_id" name="usuario_id" value="{{ old('usuario_id', auth()->id()) }}">
                            <input type="text" class="form-control" value="{{ auth()->user()->name }} ({{ auth()->user()->email }})" disabled>
                            <small class="text-muted">El suscriptor se asociara al usuario actual.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Agregar suscriptor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if((!session('editar_suscriptor_id')) && ($errors->has('correo') || $errors->has('tipo_avisos') || $errors->has('usuario_id')))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalElement = document.getElementById('modalCrearSuscriptor');
            if (!modalElement || typeof bootstrap === 'undefined') return;
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        });
    </script>
@endif

@if(session('editar_suscriptor_id'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalElement = document.getElementById('modalEditarSuscriptor{{ session('editar_suscriptor_id') }}');
            if (!modalElement || typeof bootstrap === 'undefined') return;
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        });
    </script>
@endif
@endsection
