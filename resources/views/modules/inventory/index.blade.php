@extends('layouts.app')

@section('title', 'Inventario | Emi Veterinaria')

@section('content')
@php
    $unitOptions = ['unidad', 'pieza', 'paquete', 'bulto', 'sobre', 'frasco', 'caja', 'tableta', 'kg', 'g', 'litro', 'ml'];
@endphp

<style>
    .inventory-primary-trigger {
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
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .inventory-primary-trigger:hover {
        transform: translateY(-2px);
        box-shadow: 0 22px 32px rgba(93, 74, 130, 0.35);
    }

    .inventory-primary-trigger strong {
        display: block;
        font-size: 1.05rem;
        text-align: left;
    }

    .inventory-primary-trigger span {
        display: block;
        opacity: 0.86;
        font-size: 0.85rem;
        text-align: left;
    }

    .inventory-primary-trigger i {
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .inventory-section-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--emi-primary);
    }
</style>

<div class="container-fluid py-2 py-md-3">
    <div class="page-hero mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-warehouse"></i> Módulo</span>
            <h1 class="h3 fw-bold mb-1">Inventario</h1>
            <p class="mb-0">Gestión de productos, stock y costos de la veterinaria.</p>
        </div>
        <a href="{{ route('vistas-inicio') }}" class="btn btn-light btn-sm">Volver al panel</a>
    </div>

    <!-- Acción Principal -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
            <div>
                <span class="inventory-section-kicker mb-1"><i class="fa-solid fa-star"></i> Acción principal</span>
                <h2 class="h4 mb-1">Nuevo producto</h2>
                <p class="text-muted mb-0">Agrega productos a tu inventario de medicamentos y accesorios.</p>
            </div>
        </div>
        <button type="button" class="inventory-primary-trigger" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <div>
                <strong>Nuevo producto</strong>
                <span>Nombre, stock, precio y unidad de venta.</span>
            </div>
            <i class="fa-solid fa-cube"></i>
        </button>
    </div>
    <!-- Listado de Productos -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
            <div>
                <span class="inventory-section-kicker mb-1"><i class="fa-solid fa-list"></i> Gestión</span>
                <h2 class="h4 mb-1">Productos registrados</h2>
                <p class="text-muted mb-0">Visualiza, edita y elimina productos del inventario.</p>
            </div>
        </div>

        <div class="module-panel">
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Categoria</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Minimo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->stock }}</td>
                                <td>${{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->min_stock }}</td>
                                <td>
                                    <span class="badge {{ $item->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $item->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#edit-item-{{ $item->id }}">Editar</button>
                                    <form class="d-inline" method="POST" action="{{ route('inventario-eliminar', $item) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="collapse" id="edit-item-{{ $item->id }}">
                                <td colspan="7">
                                    @php
                                        $selectedSpecies = collect(explode(',', (string) $item->target_species))
                                            ->map(fn ($value) => (int) trim($value))
                                            ->filter(fn ($value) => $value > 0)
                                            ->values();
                                    @endphp
                                    <form method="POST" action="{{ route('inventario-actualizar', $item) }}" class="row g-2">
                                        @csrf
                                        @method('PUT')
                                        <div class="col-md-3"><input class="form-control" name="name" value="{{ $item->name }}" required></div>
                                        <div class="col-md-2"><input class="form-control" name="category" value="{{ $item->category }}" required></div>
                                        <div class="col-md-2"><input class="form-control" name="presentation" value="{{ $item->presentation }}" placeholder="Presentacion"></div>
                                        <div class="col-md-2">
                                            <select name="sale_unit" class="form-select">
                                                @foreach($unitOptions as $unit)
                                                    <option value="{{ $unit }}" @selected($item->sale_unit === $unit)>{{ ucfirst($unit) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="target_species[]" class="form-select select2" multiple>
                                                @foreach($speciesCatalog as $species)
                                                    <option value="{{ $species->id }}" @selected($selectedSpecies->contains((int) $species->id))>{{ $species->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1"><input type="number" min="0" class="form-control" name="stock" value="{{ $item->stock }}" required></div>
                                        <div class="col-md-2"><input type="number" step="0.01" min="0" class="form-control" name="unit_price" value="{{ $item->unit_price }}" required></div>
                                        <div class="col-md-2"><input type="number" min="0" class="form-control" name="min_stock" value="{{ $item->min_stock }}" required></div>
                                        <div class="col-md-3 form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label">Activo</label>
                                        </div>
                                        <div class="col-md-1"><button class="btn btn-sm btn-primary w-100">OK</button></div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No hay productos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Nuevo Producto -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-3" style="background: var(--emi-surface); border: 1px solid var(--emi-border);">
            <div class="modal-header border-bottom p-4" style="background: linear-gradient(135deg, rgba(139, 120, 185, 0.08), rgba(93, 74, 130, 0.06));">
                <h1 class="modal-title fs-5 fw-bold" id="addProductModalLabel" style="color: var(--emi-dark);">
                    <i class="fa-solid fa-box me-2" style="color: var(--emi-primary)"></i>Nuevo Producto
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('inventario-agregar') }}" id="addProductForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nombre *</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej: Amoxicilina" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Categoria *</label>
                            <input type="text" name="category" class="form-control" placeholder="Ej: Antibiótico" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Presentación</label>
                            <input type="text" name="presentation" class="form-control" placeholder="Ej: Frasco 350 ml">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Unidad de venta *</label>
                            <select name="sale_unit" class="form-select" required>
                                @foreach($unitOptions as $unit)
                                    <option value="{{ $unit }}">{{ ucfirst($unit) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Especies objetivo</label>
                            <select name="target_species[]" class="form-select select2-modal" multiple>
                                @foreach($speciesCatalog as $species)
                                    <option value="{{ $species->id }}">{{ $species->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text mt-1">Si no seleccionas ninguna, aplica para todas las especies.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stock *</label>
                            <input type="number" name="stock" min="0" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Precio unitario *</label>
                            <input type="number" step="0.01" min="0" name="unit_price" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stock mínimo *</label>
                            <input type="number" min="0" name="min_stock" class="form-control" value="0" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveCheck" checked>
                                <label class="form-check-label fw-semibold" for="isActiveCheck">
                                    Producto activo
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top p-4" style="background: rgba(139, 120, 185, 0.02);">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="addProductForm" class="btn btn-success fw-bold">
                    <i class="fa-solid fa-save me-2"></i>Guardar producto
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(function () {
        $('.select2').select2({ width: '100%' });
        
        // Inicializar select2 en la modal cuando se abre
        $('#addProductModal').on('shown.bs.modal', function () {
            $('.select2-modal').select2({ width: '100%' });
        });
    });
</script>
@endpush
