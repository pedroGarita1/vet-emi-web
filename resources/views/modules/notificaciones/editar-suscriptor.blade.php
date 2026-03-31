@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="mb-4">
        <a href="{{ route('suscriptores-listar') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-chevron-left me-1"></i>Volver a suscriptores
        </a>
        <h2 class="h2 fw-bold text-dark mt-2">
            <i class="fa-solid fa-edit me-2"></i>Editar Suscriptor
        </h2>
    </div>

    <!-- Formulario -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('suscriptores-actualizar', $suscriptor) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Información del Suscriptor -->
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-user-check me-2"></i>Información del Suscriptor
                        </h5>

                        <div class="mb-4">
                            <label for="correo" class="form-label fw-semibold">
                                Correo Electrónico <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror" 
                                   id="correo" name="correo" placeholder="cliente@ejemplo.com"
                                   value="{{ old('correo', $suscriptor->correo) }}" required>
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tipo_avisos" class="form-label fw-semibold">
                                Tipo de Avisos a Recibir <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('tipo_avisos') is-invalid @enderror" 
                                    id="tipo_avisos" name="tipo_avisos" required>
                                <option value="">Selecciona el tipo...</option>
                                @foreach($tipos as $valor => $etiqueta)
                                    <option value="{{ $valor }}" @selected(old('tipo_avisos', $suscriptor->tipo_avisos) === $valor)>
                                        {{ $etiqueta }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_avisos')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Estado de suscripción -->
                        <h5 class="fw-bold text-dark mb-3 mt-5">
                            <i class="fa-solid fa-toggle-on me-2"></i>Estado
                        </h5>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="suscrito" name="suscrito" value="1" 
                                       @checked($suscriptor->suscrito)>
                                <label class="form-check-label" for="suscrito">
                                    <strong>Suscriptor activo</strong>
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Si está desactivado, no recibirá avisos hasta que se active nuevamente.
                            </small>
                        </div>

                        <!-- Usuario Asociado -->
                        @if($suscriptor->usuario)
                            <div class="alert alert-info mt-4">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                <strong>Usuario asociado:</strong> {{ $suscriptor->usuario->name }} ({{ $suscriptor->usuario->email }})
                            </div>
                        @endif

                        <!-- Información del registro -->
                        <div class="alert alert-secondary mt-4" role="alert">
                            <i class="fa-solid fa-history me-2"></i>
                            <strong>Historial:</strong>
                            <br>
                            <small>Creado: {{ $suscriptor->created_at->format('d/m/Y H:i') }}</small>
                            <br>
                            <small>Actualizado: {{ $suscriptor->updated_at->format('d/m/Y H:i') }}</small>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 mt-5">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-2"></i>Guardar Cambios
                            </button>
                            <a href="{{ route('suscriptores-listar') }}" class="btn btn-outline-secondary">
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
                        <i class="fa-solid fa-envelope me-2"></i>Preferencias de Avisos
                    </h5>
                    <p class="small mb-3">El suscriptor recibirá:</p>
                    @if($suscriptor->tipo_avisos === 'todos')
                        <ul class="list-unstyled small">
                            <li class="mb-1">📬 Todos los avisos</li>
                            <li class="mb-1">🎉 Promociones</li>
                            <li>⏰ Avisos de cierre</li>
                        </ul>
                    @elseif($suscriptor->tipo_avisos === 'promociones')
                        <ul class="list-unstyled small">
                            <li class="mb-1">🎉 Solo promociones</li>
                        </ul>
                    @elseif($suscriptor->tipo_avisos === 'cierres')
                        <ul class="list-unstyled small">
                            <li class="mb-1">⏰ Solo avisos de cierre</li>
                        </ul>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-check-circle me-2"></i>Estado Actual
                    </h5>
                    @if($suscriptor->suscrito)
                        <div class="alert alert-success mb-0">
                            <i class="fa-solid fa-check me-1"></i>
                            <strong>Activo</strong> - Recibirá avisos
                        </div>
                    @else
                        <div class="alert alert-danger mb-0">
                            <i class="fa-solid fa-ban me-1"></i>
                            <strong>Inactivo</strong> - No recibe avisos
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
