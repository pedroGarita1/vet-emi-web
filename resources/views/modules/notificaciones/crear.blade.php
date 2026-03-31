@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="mb-4">
        <a href="{{ route('notificaciones-listar') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-chevron-left me-1"></i>Volver a avisos
        </a>
        <h2 class="h2 fw-bold text-dark mt-2">
            <i class="fa-solid fa-plus me-2"></i>Crear Nuevo Aviso
        </h2>
    </div>

    <!-- Formulario -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('notificaciones-guardar') }}" novalidate>
                        @csrf

                        <!-- Información General -->
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-circle-info me-2"></i>Información General
                        </h5>

                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold">
                                Título <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" name="titulo" placeholder="Ej: 20% de descuento en consultas"
                                   value="{{ old('titulo') }}" required>
                            @error('titulo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label fw-semibold">
                                Descripción <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="6"
                                      placeholder="Ingresa la descripción detallada del aviso..."
                                      required>{{ old('descripcion') }}</textarea>
                            <small class="text-muted d-block mt-1">Mínimo 10 caracteres</small>
                            @error('descripcion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tipo" class="form-label fw-semibold">
                                Tipo de Aviso <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('tipo') is-invalid @enderror" 
                                    id="tipo" name="tipo" required>
                                <option value="">Selecciona un tipo...</option>
                                <option value="promocion" @selected(old('tipo') === 'promocion')>
                                    🎉 Promoción
                                </option>
                                <option value="cierre" @selected(old('tipo') === 'cierre')>
                                    ⏰ Cierre (No abriremos)
                                </option>
                                <option value="aviso" @selected(old('tipo') === 'aviso')>
                                    📢 Aviso Importante
                                </option>
                                <option value="otro" @selected(old('tipo') === 'otro')>
                                    Otro
                                </option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Vigencia del Aviso -->
                        <h5 class="fw-bold text-dark mb-3 mt-5">
                            <i class="fa-solid fa-calendar me-2"></i>Vigencia del Aviso
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="fecha_inicio" class="form-label fw-semibold">
                                    Fecha y hora de inicio <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                       id="fecha_inicio" name="fecha_inicio"
                                       value="{{ old('fecha_inicio') }}" required>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="fecha_fin" class="form-label fw-semibold">
                                    Fecha y hora de fin <span class="text-muted">(opcional)</span>
                                </label>
                                <input type="datetime-local" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                       id="fecha_fin" name="fecha_fin"
                                       value="{{ old('fecha_fin') }}">
                                <small class="text-muted d-block mt-1">Dejar vacío para sin fecha de vencimiento</small>
                                @error('fecha_fin')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Opciones de envío -->
                        <h5 class="fw-bold text-dark mb-3 mt-5">
                            <i class="fa-solid fa-envelope me-2"></i>Envío y Activación
                        </h5>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="activa" name="activa" value="1" checked>
                                <label class="form-check-label" for="activa">
                                    Activar aviso inmediatamente
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">Si está desactivada, se puede activar después desde el listado.</small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="enviar_ahora" name="enviar_ahora" value="1">
                                <label class="form-check-label" for="enviar_ahora">
                                    <strong>Enviar por correo a los suscriptores ahora</strong>
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">El aviso se enviará a todos los suscriptores de correo correspondientes.</small>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 mt-5">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-2"></i>Crear Aviso
                            </button>
                            <a href="{{ route('notificaciones-listar') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de información -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-lightbulb me-2"></i>Consejos
                    </h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <strong>📝 Título claro:</strong> Usa un título conciso que capture la atención.
                        </li>
                        <li class="mb-2">
                            <strong>🎯 Descripciones completas:</strong> Proporciona todos los detalles necesarios.
                        </li>
                        <li class="mb-2">
                            <strong>⏰ Fechas precisas:</strong> Define bien cuándo será visible el aviso.
                        </li>
                        <li class="mb-2">
                            <strong>📧 Envío gradual:</strong> Puedes crear varios avisos y enviarlos en horarios diferentes.
                        </li>
                        <li>
                            <strong>🔄 Reutilizable:</strong> Los avisos se mostrarán en la app móvil solo si hay internet.
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Información de tipos -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-tag me-2"></i>Tipos de Avisos
                    </h5>
                    <div class="mb-3">
                        <div class="badge" style="background: #dbeafe; color: #0369a1; font-size: 0.85rem;">
                            🎉 Promoción
                        </div>
                        <p class="small mt-2 mb-0">Ofertas, descuentos y promociones especiales.</p>
                    </div>
                    <div class="mb-3">
                        <div class="badge" style="background: #fee2e2; color: #991b1b; font-size: 0.85rem;">
                            ⏰ Cierre
                        </div>
                        <p class="small mt-2 mb-0">Aviso que no abriremos en determinada fecha.</p>
                    </div>
                    <div class="mb-3">
                        <div class="badge" style="background: #fef3c7; color: #92400e; font-size: 0.85rem;">
                            📢 Aviso
                        </div>
                        <p class="small mt-2 mb-0">Información importante y actualizaciones.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
