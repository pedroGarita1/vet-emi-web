@extends('layouts.app')

@section('title', 'Acceso | Emi Veterinaria')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-12 col-md-11 col-lg-10">
            <div class="emi-card overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-5 text-white p-4 p-lg-5 d-flex flex-column justify-content-between position-relative" style="background:linear-gradient(135deg,#242033,#5d4a82)">
                        <div class="position-absolute top-0 start-0 rounded-circle" style="width:220px;height:220px;background:#9d8bc6;opacity:.24;transform:translate(-30%,-30%);"></div>
                        <div class="position-absolute bottom-0 end-0 rounded-circle" style="width:220px;height:220px;background:#7d69ae;opacity:.2;transform:translate(30%,30%);"></div>
                        <div class="position-relative">
                            <span class="emi-badge mb-3 d-inline-block">Emi Veterinaria</span>
                            <h1 class="h3 fw-bold mb-3">Gestión Integral</h1>
                            <p class="mb-0 text-white-50">Controla inventario, ventas en mostrador y consultas médicas desde un solo lugar.</p>
                        </div>
                        <div class="small text-white-50 mt-4 position-relative">
                            <i class="fa-solid fa-paw me-2"></i>Plataforma interna segura
                        </div>
                    </div>
                    <div class="col-lg-7 p-4 p-lg-5 bg-white">
                        <h2 class="h4 fw-bold mb-4 text-dark">Iniciar sesión</h2>
                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Correo</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="form-control"
                                    placeholder="ejemplo@veterinariaemi.com"
                                    value="{{ old('email', '') }}"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Contraseña</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="form-control"
                                    placeholder="********"
                                    required
                                >
                            </div>

                            <div class="mb-4">
                                <label for="sede" class="form-label fw-semibold">Sede</label>
                                <select id="sede" name="sede" class="form-select select2">
                                    <option value="Matriz" selected>Matriz</option>
                                    <option value="Sucursal Norte">Sucursal Norte</option>
                                    <option value="Sucursal Sur">Sucursal Sur</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Entrar al sistema
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('.select2').select2({
            width: '100%',
            minimumResultsForSearch: Infinity,
        });
    });
</script>
@endpush
