@extends('layouts.app')

@section('title', 'Inventario | Emi Veterinaria')

@section('content')
@php
    $unitOptions = ['unidad', 'pieza', 'paquete', 'bulto', 'sobre', 'frasco', 'caja', 'tableta', 'kg', 'g', 'litro', 'ml'];
@endphp
<div class="container-fluid py-2 py-md-3">
    <div class="page-hero mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-warehouse"></i> Módulo</span>
            <h1 class="h3 fw-bold mb-1">Inventario</h1>
            <p class="mb-0">Gestión de productos, stock y costos de la veterinaria.</p>
        </div>
        <a href="{{ route('vistas-inicio') }}" class="btn btn-light btn-sm">Volver al panel</a>
    </div>

    <div class="module-panel mb-4">
        <h2 class="h5 mb-3">Nuevo producto</h2>
        <form method="POST" action="{{ route('inventario-agregar') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Categoria</label>
                <input type="text" name="category" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Presentacion</label>
                <input type="text" name="presentation" class="form-control" placeholder="Ej: Bolsa 1 kg, Frasco 350 ml">
            </div>
            <div class="col-md-2">
                <label class="form-label">Unidad de venta</label>
                <select name="sale_unit" class="form-select">
                    @foreach($unitOptions as $unit)
                        <option value="{{ $unit }}">{{ ucfirst($unit) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Especies objetivo</label>
                <select name="target_species[]" class="form-select select2" multiple>
                    @foreach($speciesCatalog as $species)
                        <option value="{{ $species->id }}">{{ $species->name }}</option>
                    @endforeach
                </select>
                <div class="form-text">Si no seleccionas ninguna, aplica para todas.</div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" min="0" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Precio unitario</label>
                <input type="number" step="0.01" min="0" name="unit_price" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Stock minimo</label>
                <input type="number" min="0" name="min_stock" class="form-control" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                    <label class="form-check-label">Activo</label>
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-success">Guardar producto</button>
            </div>
        </form>
    </div>

    <div class="module-panel">
        <h2 class="h5 mb-3">Productos registrados</h2>
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
@endsection

@push('scripts')
<script>
    $(function () {
        $('.select2').select2({ width: '100%' });
    });
</script>
@endpush
