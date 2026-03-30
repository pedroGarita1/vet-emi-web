@extends('layouts.app')

@section('title', 'Empleados | Emi Veterinaria')

@section('content')
<div class="container-fluid py-2 py-md-3">

    {{-- Hero --}}
    <div class="page-hero mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-users"></i> Gestión de Personal
                </span>
                <h1 class="h3 fw-bold mb-1">Empleados</h1>
                <p class="mb-0 opacity-75">Administra el personal de la clínica</p>
            </div>
            <div>
                <button class="emp-primary-trigger" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Nuevo Empleado</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="module-panel">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h2 class="h5 mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-id-card-clip" style="color:var(--emi-primary)"></i>
                Personal registrado
            </h2>
            <span class="badge rounded-pill" style="background:var(--emi-primary);color:#fff;font-size:.8rem;">
                {{ $employees->count() }} empleados
            </span>
        </div>

        @if($employees->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-users fs-1 mb-3 d-block" style="color:var(--emi-border)"></i>
                No hay empleados registrados.
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Sexo</th>
                        <th>Fecha nac.</th>
                        <th>Municipio / Estado</th>
                        <th>Documentos</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                    @php
                        $editPayload = [
                            'name' => $emp->user->name,
                            'email' => $emp->user->email,
                            'birthdate' => $emp->birthdate ? $emp->birthdate->format('Y-m-d') : '',
                            'sex' => $emp->sex ?? '',
                            'address' => $emp->address ?? '',
                            'postal_code' => $emp->postal_code ?? '',
                            'colonia' => $emp->colonia ?? '',
                            'municipio' => $emp->municipio ?? '',
                            'estado' => $emp->estado ?? '',
                        ];
                        $documentsPayload = [
                            'name' => $emp->user->name,
                            'has_ine' => (bool) $emp->ine_path,
                            'has_curp' => (bool) $emp->curp_path,
                            'has_acta' => (bool) $emp->acta_path,
                        ];
                        $documentsPreview = [
                            'ine' => route('employees-documento-ver', ['employee' => $emp->id, 'type' => 'ine']),
                            'curp' => route('employees-documento-ver', ['employee' => $emp->id, 'type' => 'curp']),
                            'acta' => route('employees-documento-ver', ['employee' => $emp->id, 'type' => 'acta']),
                        ];
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--emi-primary),var(--emi-primary-dark));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9rem;flex-shrink:0;">
                                    {{ strtoupper(substr($emp->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold" style="color:var(--emi-dark)">{{ $emp->user->name ?? '—' }}</div>
                                    <div class="small text-muted">Empleado</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small">{{ $emp->user->email ?? '—' }}</td>
                        <td>
                            @if($emp->sex === 'M')
                                <span class="badge" style="background:#dde9f9;color:#2563eb">Masculino</span>
                            @elseif($emp->sex === 'F')
                                <span class="badge" style="background:#fce7f3;color:#db2777">Femenino</span>
                            @elseif($emp->sex === 'otro')
                                <span class="badge" style="background:#f3f0f8;color:var(--emi-primary-dark)">Otro</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $emp->birthdate ? $emp->birthdate->format('d/m/Y') : '—' }}</td>
                        <td class="text-muted small">
                            @if($emp->municipio || $emp->estado)
                                {{ collect([$emp->municipio, $emp->estado])->filter()->implode(', ') }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                @if($emp->ine_path)
                                    <button type="button" class="badge border-0"
                                        style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;cursor:pointer"
                                        onclick="openDocumentPreview('{{ $documentsPreview['ine'] }}', 'INE')">
                                        <i class="fa-solid fa-check me-1"></i>INE
                                    </button>
                                @else
                                    <span class="badge" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca">INE</span>
                                @endif
                                @if($emp->curp_path)
                                    <button type="button" class="badge border-0"
                                        style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;cursor:pointer"
                                        onclick="openDocumentPreview('{{ $documentsPreview['curp'] }}', 'CURP')">
                                        <i class="fa-solid fa-check me-1"></i>CURP
                                    </button>
                                @else
                                    <span class="badge" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca">CURP</span>
                                @endif
                                @if($emp->acta_path)
                                    <button type="button" class="badge border-0"
                                        style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;cursor:pointer"
                                        onclick="openDocumentPreview('{{ $documentsPreview['acta'] }}', 'Acta de Nacimiento')">
                                        <i class="fa-solid fa-check me-1"></i>Acta
                                    </button>
                                @else
                                    <span class="badge" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca">Acta</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-sm btn-outline-primary" title="Documentos"
                                    onclick='openDocumentsModal({{ $emp->id }}, @json($documentsPayload))'>
                                    <i class="fa-solid fa-file-arrow-up"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" title="Editar"
                                    onclick='openEditModal({{ $emp->id }}, @json($editPayload))'>
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form method="POST" action="{{ route('employees-eliminar', $emp->id) }}"
                                    onsubmit="return confirm('¿Eliminar este empleado y su cuenta?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ===================== MODAL NUEVO EMPLEADO ===================== --}}
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px;overflow:hidden">
            <div class="modal-header border-0 pb-0 text-white"
                style="background:linear-gradient(135deg,var(--emi-primary),var(--emi-primary-dark));padding:1.4rem 1.6rem">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Nuevo Empleado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('employees-agregar') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 pt-4 pb-2">
                    <h6 class="fw-bold text-uppercase small mb-3" style="color:var(--emi-primary-dark);letter-spacing:.05em">
                        <i class="fa-solid fa-circle-user me-1"></i> Datos de acceso
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nombre completo <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Ej. María García López" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" required minlength="8">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Fecha de nacimiento</label>
                            <input type="date" name="birthdate" class="form-control">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Sexo</label>
                            <select name="sex" class="form-select">
                                <option value="">— Seleccionar —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="fw-bold text-uppercase small mb-3" style="color:var(--emi-primary-dark);letter-spacing:.05em">
                        <i class="fa-solid fa-location-dot me-1"></i> Dirección
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Código Postal</label>
                            <input type="text" name="postal_code" id="add_postal_code"
                                class="form-control" maxlength="5" placeholder="00000"
                                oninput="fetchAddressByCP(this.value,'add')">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Colonia</label>
                            <select name="colonia" id="add_colonia" class="form-select">
                                <option value="">— Primero ingresa el CP —</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Municipio</label>
                            <input type="text" name="municipio" id="add_municipio" class="form-control" placeholder="Autocompletado (editable)">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Estado</label>
                            <input type="text" name="estado" id="add_estado" class="form-control" readonly placeholder="Autocompletado">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Dirección (calle y número)</label>
                            <input type="text" name="address" class="form-control" placeholder="Ej. Calle Hidalgo 123 Int. 4">
                        </div>
                    </div>

                    <h6 class="fw-bold text-uppercase small mb-3" style="color:var(--emi-primary-dark);letter-spacing:.05em">
                        <i class="fa-solid fa-file-lines me-1"></i> Documentos (PDF, JPG o PNG, máx. 4MB)
                    </h6>
                    <div class="row g-3 mb-2">
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Copia INE</label>
                            <input type="file" name="ine_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Copia CURP</label>
                            <input type="file" name="curp_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Acta de Nacimiento</label>
                            <input type="file" name="acta_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn text-white fw-bold"
                        style="background:linear-gradient(135deg,var(--emi-primary),var(--emi-primary-dark));border:0;border-radius:10px;padding:.5rem 1.4rem">
                        <i class="fa-solid fa-user-plus me-1"></i> Registrar Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================== MINI MODAL VISTA PREVIA DOCUMENTO ===================== --}}
<div class="modal fade" id="previewDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0" style="border-radius:16px;overflow:hidden">
            <div class="modal-header border-0 pb-0 text-white"
                style="background:linear-gradient(135deg,var(--emi-primary),var(--emi-primary-dark));padding:1rem 1.2rem">
                <h6 class="modal-title fw-bold" id="previewDocumentTitle">Documento</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="background:#f8f7fb;min-height:65vh;">
                <iframe id="previewDocumentFrame" src="" style="width:100%;height:65vh;border:0;"></iframe>
            </div>
            <div class="modal-footer border-0">
                <a id="previewDocumentOpenNewTab" href="#" target="_blank" class="btn btn-sm btn-outline-primary">Abrir en pestaña nueva</a>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL DOCUMENTOS RÁPIDO ===================== --}}
<div class="modal fade" id="documentsEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:18px;overflow:hidden">
            <div class="modal-header border-0 pb-0 text-white"
                style="background:linear-gradient(135deg,var(--emi-primary),var(--emi-primary-dark));padding:1.2rem 1.4rem">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    Actualizar Documentos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="documentsEmployeeForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 pt-4 pb-2">
                    <div class="small text-muted mb-3">
                        Empleado: <strong id="doc_employee_name" style="color:var(--emi-dark)"></strong>
                    </div>

                    <div class="row g-2 mb-3" id="doc_status_badges"></div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Copia INE</label>
                        <input type="file" name="ine_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Copia CURP</label>
                        <input type="file" name="curp_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Acta de Nacimiento</label>
                        <input type="file" name="acta_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <small class="text-muted d-block">Todos los campos son opcionales. Solo sube los que falten o quieras reemplazar.</small>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn text-white fw-bold"
                        style="background:linear-gradient(135deg,var(--emi-primary),var(--emi-primary-dark));border:0;border-radius:10px;padding:.5rem 1.3rem">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Guardar documentos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================== MODAL EDITAR EMPLEADO ===================== --}}
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px;overflow:hidden">
            <div class="modal-header border-0 pb-0 text-white"
                style="background:linear-gradient(135deg,var(--emi-primary),var(--emi-primary-dark));padding:1.4rem 1.6rem">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="fa-solid fa-user-pen"></i> Editar Empleado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editEmployeeForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body px-4 pt-4 pb-2">
                    <h6 class="fw-bold text-uppercase small mb-3" style="color:var(--emi-primary-dark);letter-spacing:.05em">
                        <i class="fa-solid fa-circle-user me-1"></i> Datos de acceso
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nombre completo <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nueva contraseña <small class="text-muted">(dejar vacío para no cambiar)</small></label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" minlength="8">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Fecha de nacimiento</label>
                            <input type="date" name="birthdate" id="edit_birthdate" class="form-control">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Sexo</label>
                            <select name="sex" id="edit_sex" class="form-select">
                                <option value="">— Seleccionar —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="fw-bold text-uppercase small mb-3" style="color:var(--emi-primary-dark);letter-spacing:.05em">
                        <i class="fa-solid fa-location-dot me-1"></i> Dirección
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Código Postal</label>
                            <input type="text" name="postal_code" id="edit_postal_code"
                                class="form-control" maxlength="5"
                                oninput="fetchAddressByCP(this.value,'edit')">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Colonia</label>
                            <select name="colonia" id="edit_colonia" class="form-select"></select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Municipio</label>
                            <input type="text" name="municipio" id="edit_municipio" class="form-control">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Estado</label>
                            <input type="text" name="estado" id="edit_estado" class="form-control" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Dirección (calle y número)</label>
                            <input type="text" name="address" id="edit_address" class="form-control">
                        </div>
                    </div>

                    <h6 class="fw-bold text-uppercase small mb-3" style="color:var(--emi-primary-dark);letter-spacing:.05em">
                        <i class="fa-solid fa-file-lines me-1"></i> Documentos (reemplaza solo si adjuntas nuevo archivo)
                    </h6>
                    <div class="row g-3 mb-2">
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Copia INE</label>
                            <input type="file" name="ine_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Copia CURP</label>
                            <input type="file" name="curp_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Acta de Nacimiento</label>
                            <input type="file" name="acta_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn text-white fw-bold"
                        style="background:linear-gradient(135deg,var(--emi-primary),var(--emi-primary-dark));border:0;border-radius:10px;padding:.5rem 1.4rem">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .emp-primary-trigger {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.4rem;
        border: 0;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        color: #fff;
        background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.12));
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        box-shadow: 0 4px 14px rgba(0,0,0,0.18);
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .emp-primary-trigger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0,0,0,0.28);
    }
</style>

<script>
    function openDocumentPreview(url, label) {
        const frame = document.getElementById('previewDocumentFrame');
        const title = document.getElementById('previewDocumentTitle');
        const openNewTab = document.getElementById('previewDocumentOpenNewTab');

        title.textContent = 'Vista previa: ' + label;
        frame.src = url;
        openNewTab.href = url;

        new bootstrap.Modal(document.getElementById('previewDocumentModal')).show();
    }

    function openDocumentsModal(employeeId, data) {
        document.getElementById('documentsEmployeeForm').action = '/employees/' + employeeId + '/documents';
        document.getElementById('doc_employee_name').textContent = data.name || 'Empleado';

        const badge = (ok, label) => ok
            ? `<div class="col-4"><span class="badge w-100" style="background:#f0fdf4;color:#166534;border:1px solid #86efac">${label}: Cargado</span></div>`
            : `<div class="col-4"><span class="badge w-100" style="background:#fef2f2;color:#991b1b;border:1px solid #fca5a5">${label}: Pendiente</span></div>`;

        document.getElementById('doc_status_badges').innerHTML =
            badge(!!data.has_ine, 'INE') +
            badge(!!data.has_curp, 'CURP') +
            badge(!!data.has_acta, 'Acta');

        new bootstrap.Modal(document.getElementById('documentsEmployeeModal')).show();
    }

    function openEditModal(employeeId, data) {
        document.getElementById('editEmployeeForm').action = '/employees/' + employeeId;
        document.getElementById('edit_name').value      = data.name || '';
        document.getElementById('edit_email').value     = data.email || '';
        document.getElementById('edit_birthdate').value = data.birthdate || '';
        document.getElementById('edit_sex').value       = data.sex || '';
        document.getElementById('edit_address').value   = data.address || '';
        document.getElementById('edit_postal_code').value = data.postal_code || '';
        document.getElementById('edit_municipio').value = data.municipio || '';
        document.getElementById('edit_estado').value    = data.estado || '';

        // Setear colonia guardada
        const coloniaSelect = document.getElementById('edit_colonia');
        coloniaSelect.innerHTML = data.colonia
            ? `<option value="${data.colonia}" selected>${data.colonia}</option>`
            : '<option value="">—</option>';

        new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
    }

    function applyAddressData(prefix, data) {
        document.getElementById(prefix + '_municipio').value = data.municipio || '';
        document.getElementById(prefix + '_estado').value = data.estado || '';

        const coloniaSelect = document.getElementById(prefix + '_colonia');
        const colonias = Array.isArray(data.colonias) ? data.colonias : [];
        coloniaSelect.innerHTML = colonias.length
            ? colonias.map(col => `<option value="${col}">${col}</option>`).join('')
            : '<option value="">— Sin colonias disponibles —</option>';
    }

    // Autocomplete por CP consumiendo endpoint backend (formato zip_codes)
    async function fetchAddressByCP(cp, prefix) {
        if (cp.length !== 5) return;

        try {
            const resp = await fetch(`/employees/buscar-cp/${cp}`);
            if (!resp.ok) return;
            const json = await resp.json();
            const places = Array.isArray(json.zip_codes) ? json.zip_codes : [];
            if (!places.length) return;

            const first = places[0];
            applyAddressData(prefix, {
                municipio: first.d_mnpio || '',
                estado: first.d_estado || '',
                colonias: places.map(p => p.d_asenta).filter(Boolean),
            });
        } catch (e) {
            // CP no encontrado, ignorar silenciosamente
        }
    }
</script>
@endsection
