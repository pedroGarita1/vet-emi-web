@extends('layouts.app')

@section('title', 'Registro | Emi Veterinaria')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="emi-card overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-5 text-white p-4 p-lg-5 d-flex flex-column justify-content-between position-relative" style="background:linear-gradient(135deg,#27c27c,#1a9e63)">
                        <div class="position-absolute top-0 start-0 rounded-circle" style="width:220px;height:220px;background:#6ee7b7;opacity:.25;transform:translate(-30%,-30%);"></div>
                        <div class="position-absolute bottom-0 end-0 rounded-circle" style="width:220px;height:220px;background:#34d399;opacity:.2;transform:translate(30%,30%);"></div>
                        <div class="position-relative">
                            <span class="emi-badge mb-3 d-inline-block">Emi Veterinaria</span>
                            <h1 class="h3 fw-bold mb-3">Registro de usuario</h1>
                            <p class="mb-0 text-white-50">Solicita tu acceso como cliente o empleado. Un administrador aprobará tu cuenta.</p>
                        </div>
                        <div class="small text-white-50 mt-4 position-relative">
                            <i class="fa-solid fa-paw me-2"></i>Registro seguro
                        </div>
                    </div>
                    <div class="col-lg-7 p-4 p-lg-5 bg-white">
                        <h2 class="h4 fw-bold mb-4 text-dark">Crear cuenta</h2>
                        <form method="POST" action="{{ route('register.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Nombre completo</label>
                                <input id="name" name="name" type="text" class="form-control" required value="{{ old('name') }}">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Correo</label>
                                <input id="email" name="email" type="email" class="form-control" required value="{{ old('email') }}">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Contraseña</label>
                                <input id="password" name="password" type="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="role_id" class="form-label fw-semibold">Tipo de usuario</label>
                                <select id="role_id" name="role_id" class="form-select" required>
                                    <option value="">Selecciona tipo</option>
                                    @foreach($roles as $role)
                                        @if($role->name !== 'administrador')
                                            <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ ucfirst($role->name) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-primary w-100">Solicitar registro</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
