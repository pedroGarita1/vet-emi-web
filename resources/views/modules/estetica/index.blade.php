@extends('layouts.app')

@section('title', 'Estetica | Emi Veterinaria')

@section('content')
@php
    $pendingCount = $services->where('status', 'pendiente')->count();
    $inProgressCount = $services->where('status', 'en_proceso')->count();
    $readyCount = $services->where('status', 'lista')->count();
@endphp

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

    .consult-create-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding-top: 0.25rem;
    }

    .btn-emi-purple {
        background: #5d4a82;
        border-color: #5d4a82;
        color: #fff;
    }

    .btn-emi-purple:hover,
    .btn-emi-purple:focus {
        background: #4d3c6e;
        border-color: #4d3c6e;
        color: #fff;
    }

    .btn-outline-emi-purple {
        border-color: #5d4a82;
        color: #5d4a82;
        background: #fff;
    }

    .btn-outline-emi-purple:hover,
    .btn-outline-emi-purple:focus {
        background: #5d4a82;
        border-color: #5d4a82;
        color: #fff;
    }

    .consult-create-modal .select2-container--default .select2-selection--single {
        border-color: #cfc2e4;
        min-height: 38px;
    }

    .consult-create-modal .select2-container--default .select2-selection--single:focus,
    .consult-create-modal .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #7a63a8;
        box-shadow: 0 0 0 0.2rem rgba(93, 74, 130, 0.16);
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #5d4a82;
        color: #fff;
    }

    #modalAddPet .modal-title i,
    #modalEditPet .modal-title i {
        color: #5d4a82 !important;
    }

    #modalAddPet .btn-success,
    #modalEditPet .btn-primary {
        background: #5d4a82;
        border-color: #5d4a82;
        color: #fff;
    }

    #modalAddPet .btn-success:hover,
    #modalAddPet .btn-success:focus,
    #modalEditPet .btn-primary:hover,
    #modalEditPet .btn-primary:focus {
        background: #4d3c6e;
        border-color: #4d3c6e;
        color: #fff;
    }

    @media (max-width: 991.98px) {
        .consult-actions-panel {
            grid-template-columns: 1fr;
        }

        .consult-secondary-grid {
            grid-template-columns: 1fr;
        }

        .consult-create-modal .modal-body {
            padding: 1rem;
        }
    }
</style>

<div class="container-fluid py-2 py-md-3 consult-shell">
    <section class="consult-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-scissors"></i> Area Estetica</span>
                <h1 class="h3 fw-bold mb-1">Servicios de Estetica</h1>
                <p class="mb-0 opacity-75">Registro operativo, estados y aviso al dueño.</p>
            </div>
            <div>
                <a href="{{ route('vistas-inicio') }}" class="btn btn-outline-light btn-sm d-inline-flex align-items-center gap-2"><i class="fa-solid fa-house"></i><span>Panel</span></a>
            </div>
        </div>
    </section>

    <section class="consult-actions-panel">
        <div class="consult-primary-action">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
                <div>
                    <span class="consult-section-kicker text-success-emphasis mb-1"><i class="fa-solid fa-star"></i> Accion principal</span>
                    <h2 class="h4 mb-1">Nuevo servicio</h2>
                    <p class="text-muted mb-0">Misma experiencia visual del modulo de consultas.</p>
                </div>
            </div>
            <button type="button" class="consult-primary-trigger" data-bs-toggle="modal" data-bs-target="#modalCreateEsteticaService">
                <div>
                    <strong>Registrar servicio</strong>
                    <span>Mascota, contacto y fecha de atencion.</span>
                </div>
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <div class="consult-secondary-actions">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-3 flex-wrap">
                <div>
                    <span class="consult-section-kicker text-secondary mb-1"><i class="fa-solid fa-layer-group"></i> Auxiliares</span>
                    <h3 class="h6 mb-0">Estado rapido</h3>
                </div>
            </div>
            <div class="consult-secondary-grid">
                <button type="button" class="consult-secondary-trigger" data-bs-toggle="modal" data-bs-target="#modalAddPet">
                    <strong><i class="fa-solid fa-paw"></i> Mascota</strong>
                    <small>Agregar mascota y dueño</small>
                </button>
                <button type="button" class="consult-secondary-trigger" data-bs-toggle="modal" data-bs-target="#modalEditPet">
                    <strong><i class="fa-solid fa-pen"></i> Editar mascota</strong>
                    <small>Actualizar dueño y datos</small>
                </button>
                <div class="consult-secondary-trigger">
                    <strong><i class="fa-solid fa-bell"></i> Lista para aviso</strong>
                    <small>{{ $readyCount }} servicio(s)</small>
                </div>
            </div>
        </div>
    </section>

    <section class="consult-table-panel">
        <div class="consult-table-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h5 mb-0 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-table-list text-primary"></i> Servicios registrados</h2>
                <span class="small text-muted d-inline-flex align-items-center gap-2"><i class="fa-solid fa-list"></i>{{ $services->count() }} registros</span>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th>Mascota</th>
                            <th>Dueño</th>
                            <th>Contacto</th>
                            <th>Servicio</th>
                            <th>Imagenes</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td>{{ $service->pet_name }}</td>
                                <td>{{ $service->owner_name ?: '-' }}</td>
                                <td>
                                    <div class="small">{{ $service->owner_phone ?: '-' }}</div>
                                    <div class="small text-muted">{{ $service->owner_email ?: '-' }}</div>
                                </td>
                                <td>{{ $service->service_type }}</td>
                                <td>
                                    @if($service->images->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($service->images->take(3) as $image)
                                                <a href="{{ asset($image->image_path) }}" target="_blank" rel="noopener" class="d-inline-block">
                                                    <img src="{{ asset($image->image_path) }}" alt="Imagen servicio" style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid #d9cfe8;">
                                                </a>
                                            @endforeach
                                        </div>
                                        @if($service->images->count() > 3)
                                            <div class="small text-muted mt-1">+{{ $service->images->count() - 3 }} mas</div>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td><span class="badge text-bg-light">{{ ucfirst(str_replace('_', ' ', $service->status)) }}</span></td>
                                <td>{{ $service->requested_at?->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <form method="POST" action="{{ route('estetica-estado-actualizar', $service) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="en_proceso">
                                            <button class="btn btn-sm btn-outline-emi-purple">En proceso</button>
                                        </form>
                                        <form method="POST" action="{{ route('estetica-estado-actualizar', $service) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="lista">
                                            <button class="btn btn-sm btn-outline-emi-purple">Lista</button>
                                        </form>
                                        <form method="POST" action="{{ route('estetica-estado-actualizar', $service) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="entregada">
                                            <button class="btn btn-sm btn-outline-emi-purple">Entregada</button>
                                        </form>
                                        <form method="POST" action="{{ route('estetica-notificar-dueno', $service) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-emi-purple">Avisar dueño</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted">No hay servicios de estetica.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@include('modules.estetica.modals.create_service_modal')
@include('modules.consultations.modals.add_pet_modal')
@include('modules.consultations.modals.edit_pet_modal')

@endsection

@push('scripts')
<script>
    (function () {
        const pets = @json($petsJson);
        const speciesCatalog = @json($speciesJson);
        const createModal = document.getElementById('modalCreateEsteticaService');
        const selector = document.getElementById('est_pet_id');
        const petName = document.getElementById('est_pet_name');
        const ownerName = document.getElementById('est_owner_name');
        const ownerEmail = document.getElementById('est_owner_email');
        const ownerPhone = document.getElementById('est_owner_phone');
        const addPetModal = document.getElementById('modalAddPet');
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
        const petSpeciesModal = document.getElementById('pet_species_id_modal');
        const petBreedModal = document.getElementById('pet_breed_modal');
        const petBreedLabelModal = document.getElementById('pet_breed_label_text_modal');
        const petSizeModal = document.getElementById('pet_size_modal');

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
                dropdownParent: window.jQuery(dropdownParentElement || createModal || document.body),
                placeholder: element.getAttribute('data-placeholder') || 'Selecciona una opcion',
                allowClear: true,
            });
        }

        function initializeScopedSelect2(scope, dropdownParentElement) {
            if (!scope) {
                return;
            }

            scope.querySelectorAll('.consultation-select2').forEach(function (element) {
                initializeSelect2ForElement(element, dropdownParentElement);
            });
        }

        function findPet(petId) {
            return pets.find(function (pet) {
                return String(pet.id) === String(petId);
            });
        }

        function speciesNameById(speciesId) {
            const species = speciesCatalog.find(function (item) {
                return String(item.id) === String(speciesId);
            });

            return species ? String(species.name || '').toLowerCase() : '';
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

        function applySelectedPetData() {
            if (!selector) {
                return;
            }

            const pet = findPet(selector.value);
            if (!pet) {
                if (petName) petName.value = '';
                if (ownerName) ownerName.value = '';
                if (ownerEmail) ownerEmail.value = '';
                if (ownerPhone) ownerPhone.value = '';
                return;
            }

            if (petName) petName.value = pet.name || '';
            if (ownerName) ownerName.value = pet.owner_name || '';
            if (ownerEmail) ownerEmail.value = pet.owner_email || '';
            if (ownerPhone) ownerPhone.value = pet.owner_phone || '';
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

        if (!selector) {
            return;
        }

        selector.addEventListener('change', applySelectedPetData);

        if (window.jQuery && window.jQuery.fn.select2) {
            window.jQuery(selector).on('select2:select select2:clear', applySelectedPetData);
        }

        if (editPetSelector) {
            editPetSelector.addEventListener('change', applyEditPetData);

            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery(editPetSelector).on('select2:select select2:clear', applyEditPetData);
            }
        }

        if (petSpeciesModal) {
            petSpeciesModal.addEventListener('change', updatePetBreedModalBehavior);
            updatePetBreedModalBehavior();
        }

        if (createModal) {
            createModal.addEventListener('shown.bs.modal', function () {
                initializeScopedSelect2(createModal, createModal);
                applySelectedPetData();
            });
        }

        if (addPetModal) {
            addPetModal.addEventListener('shown.bs.modal', function () {
                if (petSpeciesModal) {
                    initializeSelect2ForElement(petSpeciesModal, addPetModal);
                }
                updatePetBreedModalBehavior();
            });
        }

        if (editPetModal) {
            editPetModal.addEventListener('shown.bs.modal', function () {
                initializeScopedSelect2(editPetModal, editPetModal);
                applyEditPetData();
            });
        }

        applySelectedPetData();

        @if($errors->any())
            const modalEl = document.getElementById('modalCreateEsteticaService');
            if (modalEl && window.bootstrap?.Modal) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        @endif
    })();
</script>
@endpush
