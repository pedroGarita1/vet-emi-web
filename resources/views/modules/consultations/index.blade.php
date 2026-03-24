@extends('layouts.app')

@section('title', 'Consultas | Emi Veterinaria')

@section('content')
@php
    $selectedPatient = $selectedPatientPetId > 0 ? $petsCatalog->firstWhere('id', $selectedPatientPetId) : null;
    $tableRows = $selectedPatientPetId > 0 ? $patientHistory : $consultations;
@endphp

<style>
    .consult-shell {
        display: grid;
        gap: 1rem;
    }

    .consult-hero {
        background: radial-gradient(circle at 10% 10%, #34d399 0%, #059669 45%, #065f46 100%);
        border-radius: 18px;
        color: #fff;
        padding: 1.2rem;
        box-shadow: 0 14px 30px rgba(6, 95, 70, 0.35);
    }

    .consult-table-panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
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
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    }

    .consult-primary-action {
        padding: 1.1rem;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 55%, #ffffff 100%);
        border-color: #a7f3d0;
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
        background: linear-gradient(135deg, #10b981 0%, #059669 55%, #047857 100%);
        box-shadow: 0 18px 28px rgba(5, 150, 105, 0.25);
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
        border: 1px solid #dbeafe;
        background: #f8fafc;
        color: #0f172a;
    }

    .consult-secondary-trigger i {
        color: #0f766e;
        margin-right: 0.45rem;
    }

    .consult-secondary-trigger small {
        display: block;
        color: #64748b;
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
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 0.85rem;
        margin-bottom: 0.8rem;
    }

    .table-consult thead th {
        position: sticky;
        top: 0;
        background: #f8fafc;
        z-index: 1;
    }

    .med-row {
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 0.65rem;
        background: #f8fafc;
    }

    .med-chip {
        display: inline-block;
        border: 1px solid #d1d5db;
        border-radius: 999px;
        padding: 2px 10px;
        font-size: 0.72rem;
        margin: 2px 4px 2px 0;
        background: #f9fafb;
    }

    .breed-pill {
        display: inline-flex;
        align-items: center;
        border: 1px solid #a7f3d0;
        background: #ecfdf5;
        color: #065f46;
        font-size: 0.72rem;
        border-radius: 999px;
        padding: 2px 10px;
        font-weight: 700;
    }

    .size-pill {
        display: inline-flex;
        align-items: center;
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
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

    .consult-create-modal .modal-dialog {
        max-width: min(1320px, calc(100vw - 1.5rem));
    }

    .consult-create-modal .modal-content {
        border-radius: 24px;
        overflow: hidden;
    }

    .consult-create-modal .modal-header {
        padding: 1.15rem 1.25rem;
        background: linear-gradient(135deg, #e8fff5 0%, #d7f7ea 52%, #c3ecdb 100%);
        border-bottom: 1px solid #b7e4d2;
    }

    .consult-create-modal .modal-body {
        padding: 1.25rem;
        background:
            radial-gradient(circle at top right, rgba(16, 185, 129, 0.08), transparent 22%),
            linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
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
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 1rem;
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.06);
    }

    .consult-create-card-soft {
        background: linear-gradient(180deg, #ffffff 0%, #fbfffd 100%);
        border-color: #d1fae5;
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
        color: #064e3b;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        font-size: 0.78rem;
    }

    .consult-create-title i {
        color: #059669;
    }

    .consult-create-head p {
        margin: 0.2rem 0 0;
        color: #64748b;
        font-size: 0.88rem;
    }

    .consult-treatment-card {
        min-height: 100%;
        background: linear-gradient(180deg, #ffffff 0%, #f7fffb 100%);
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

    @media (max-width: 991.98px) {
        .consult-create-grid {
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
                <button type="button" class="consult-secondary-trigger" data-bs-toggle="modal" data-bs-target="#modalAddPricingRule">
                    <strong><i class="fa-solid fa-tags"></i> Tarifa</strong>
                    <small>Regla por diagnostico</small>
                </button>
            </div>
        </div>
    </section>

    <section class="consult-table-panel">
        <div class="consult-table-body">
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
                            <th>Productos</th>
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
                                    @if($consultation->consultationItems->isNotEmpty())
                                        @foreach($consultation->consultationItems as $item)
                                            <span class="med-chip">{{ $item->inventoryItem?->name ?: 'Producto' }} x{{ $item->quantity }}</span>
                                        @endforeach
                                    @else
                                        -
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
                                    <td colspan="9" class="small text-muted">
                                        <strong>Tratamiento:</strong> {!! $consultation->treatment ?: '<span class="text-muted">Sin detalle</span>' !!}
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr><td colspan="9" class="text-center text-muted">No hay consultas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@include('modules.consultations.modals.create_consultation_modal')
@include('modules.consultations.modals.add_species_modal')
@include('modules.consultations.modals.add_pet_modal')
@include('modules.consultations.modals.add_pricing_rule_modal')
@include('modules.consultations.modals.edit_consultation_modal')
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        (function () {
            const pets = @json($petsJson);
            const speciesCatalog = @json($speciesJson);
            const pricingMap = @json($pricingMap);
            const inventoryCatalog = @json($inventoryJson);
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

            function initializeSelect2ForElement(element) {
                if (!element || !window.jQuery || !window.jQuery.fn.select2) {
                    return;
                }

                const $element = window.jQuery(element);

                if ($element.hasClass('select2-hidden-accessible')) {
                    return;
                }

                $element.select2({
                    width: '100%',
                    dropdownParent: window.jQuery(consultationModal),
                    placeholder: element.getAttribute('data-placeholder') || 'Selecciona una opcion',
                    allowClear: true,
                });
            }

            function initializeConsultationSelect2(scope) {
                if (!scope) {
                    return;
                }

                scope.querySelectorAll('.consultation-select2').forEach(function (element) {
                    initializeSelect2ForElement(element);
                });
            }

            function refreshMedicationIndexes() {
                const rows = medicationsContainer.querySelectorAll('.med-row');
                rows.forEach(function (row, index) {
                    row.querySelectorAll('[data-field]').forEach(function (field) {
                        field.name = `medications[${index}][${field.getAttribute('data-field')}]`;
                    });
                });
            }

            function addMedicationRow() {
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

                medicationsContainer.appendChild(wrapper);
                refreshMedicationIndexes();

                if (consultationModal && consultationModal.classList.contains('show')) {
                    initializeConsultationSelect2(wrapper);
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
                    refreshMedicationIndexes();
                });
            }

            if (petSelect) {
                petSelect.addEventListener('change', function () {
                    const pet = findPet(this.value);
                    if (!pet) {
                        ownerInput.value = '';
                        breedInput.value = '';
                        if (sizeInput) {
                            sizeInput.value = '';
                        }
                        return;
                    }

                    if (pet.species_id) {
                        speciesSelect.value = String(pet.species_id);
                        if (window.jQuery && window.jQuery.fn.select2) {
                            window.jQuery(speciesSelect).trigger('change');
                        }
                    }

                    ownerInput.value = pet.owner_name || '';
                    breedInput.value = pet.breed || '';
                    if (sizeInput) {
                        sizeInput.value = pet.size_category ? pet.size_category.charAt(0).toUpperCase() + pet.size_category.slice(1) : '';
                    }
                    resolveCost();
                });
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
                addMedicationRowBtn.addEventListener('click', addMedicationRow);
                addMedicationRow();
            }

            if (consultationModal) {
                consultationModal.addEventListener('shown.bs.modal', function () {
                    initializeConsultationSelect2(consultationModal);
                });
            }

            if (window.ClassicEditor) {
                ClassicEditor.create(document.querySelector('#treatmentEditor')).catch(function () {
                    // Ignore editor init issues to avoid blocking form usage.
                });
            }
        })();
    </script>
@endpush
