@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="mb-4">
        <a href="{{ route('suscriptores-listar') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-chevron-left me-1"></i>Volver a suscriptores
        </a>
        <h2 class="h2 fw-bold text-dark mt-2">
            <i class="fa-solid fa-plus me-2"></i>Agregar Nuevo Suscriptor
        </h2>
    </div>

    <!-- Formulario -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('suscriptores-guardar') }}" novalidate>
                        @csrf

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
                                   value="{{ old('correo') }}" required>
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
                                    <option value="{{ $valor }}" @selected(old('tipo_avisos') === $valor)>
                                        {{ $etiqueta }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-2">
                                Puedes cambiar esto después.
                            </small>
                            @error('tipo_avisos')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="usuario_id" class="form-label fw-semibold">
                                Usuario Asociado <span class="text-muted">(opcional)</span>
                            </label>
                            <input type="hidden" id="usuario_id" name="usuario_id" value="{{ auth()->id() }}">
                            <input type="text" class="form-control" value="{{ auth()->user()->name }} ({{ auth()->user()->email }})" disabled>
                            <small class="text-muted d-block mt-2">Este suscriptor está asociado a tu usuario.</small>
                        </div>

                        <!-- Información adicional -->
                        <div class="alert alert-info mt-4">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <strong>Nota:</strong> El suscriptor recibirá avisos solo si está activo y si el tipo de aviso coincide con sus preferencias.
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 mt-5">
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-save me-2"></i>Agregar Suscriptor
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
                        <i class="fa-solid fa-lightbulb me-2"></i>¿Qué es un suscriptor?
                    </h5>
                    <p class="small mb-2">
                        Un suscriptor es un correo electrónico que recibe notificaciones y avisos de la clínica veterinaria:
                    </p>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <strong>📬 Todos los avisos:</strong> Recibirá todas las notificaciones.
                        </li>
                        <li class="mb-2">
                            <strong>🎉 Solo promociones:</strong> Recibirá solo ofertas y descuentos.
                        </li>
                        <li>
                            <strong>⏰ Solo cierres:</strong> Recibirá solo avisos de cierre.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-check-circle me-2"></i>Consideraciones
                    </h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2">✅ El correo debe ser válido y único.</li>
                        <li class="mb-2">✅ Puedes agregar múltiples suscriptores.</li>
                        <li class="mb-2">✅ Los avisos se envían automáticamente.</li>
                        <li>✅ Los suscriptores puede cambiar sus preferencias.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
