@extends('layouts.app')

@section('title', 'Punto de Venta | Emi Veterinaria')

@section('content')
<style>
    .pos-shell {
        display: grid;
        gap: 1rem;
    }

    .pos-hero {
        background:
            radial-gradient(circle at 12% 15%, #34d399 0%, #10b981 42%, #047857 100%),
            linear-gradient(135deg, #065f46 0%, #064e3b 100%);
        color: #fff;
        border-radius: 20px;
        padding: 1.2rem;
        box-shadow: 0 20px 40px rgba(4, 120, 87, 0.25);
    }

    .pos-hero-note {
        color: rgba(255, 255, 255, 0.82);
    }

    .pos-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 1rem;
    }

    .pos-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .pos-card-head {
        padding: 0.95rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .pos-card-body {
        padding: 1rem;
    }

    .pos-subtitle {
        color: #64748b;
        font-size: 0.86rem;
    }

    .pos-total-preview {
        border: 2px solid #86efac;
        background: #f0fdf4;
        color: #166534;
        font-weight: 800;
        text-align: center;
    }

    .pos-table-wrap {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
    }

    .pos-table thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #475569;
    }

    .pos-table tbody tr:hover {
        background: #f8fafc;
    }

    .pos-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 2px 10px;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .pos-pill-total {
        color: #065f46;
        background: #dcfce7;
        border: 1px solid #86efac;
    }

</style>

<div class="container-fluid py-2 py-md-3 pos-shell">
    <section class="pos-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-cash-register"></i> Módulo</span>
                <h1 class="h3 fw-bold mb-1">Punto de Venta</h1>
                <p class="mb-0 pos-hero-note">Venta ágil de medicamentos, accesorios, higiene, ropa y alimentos por presentación.</p>
            </div>
            <a href="{{ route('vistas-inicio') }}" class="btn btn-light btn-sm d-inline-flex align-items-center gap-2"><i class="fa-solid fa-house"></i><span>Panel</span></a>
        </div>
    </section>

    <section class="pos-grid">
        <div class="pos-card">
            <div class="pos-card-head d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <div>
                    <h2 class="h5 mb-1 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-receipt text-success"></i><span>Nueva venta</span></h2>
                    <p class="mb-0 pos-subtitle">Filtra por especie, elige producto por categoría y completa datos automáticamente.</p>
                </div>
            </div>
            <div class="pos-card-body">
                <form method="POST" action="{{ route('sales-agregar') }}" class="row g-3" id="saleForm">
                    @csrf
                    <div class="col-md-3">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-filter text-secondary"></i><span>Especie</span></label>
                        <select id="sale_species_filter" class="form-select select2">
                            <option value="">Todas las especies</option>
                            @foreach($speciesCatalog as $species)
                                <option value="{{ $species->id }}">{{ $species->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-boxes-stacked text-secondary"></i><span>Producto de inventario</span></label>
                        <select id="sale_inventory_item_id" name="inventory_item_id" class="form-select select2">
                            <option value="">Sin relacion</option>
                            @foreach ($items->groupBy('category') as $category => $group)
                                <optgroup label="{{ $category }}">
                                    @foreach ($group as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            data-name="{{ $item->name }}"
                                            data-price="{{ $item->unit_price }}"
                                            data-unit="{{ $item->sale_unit }}"
                                            data-presentation="{{ $item->presentation }}"
                                            data-target-species="{{ $item->target_species }}"
                                        >
                                            {{ $item->name }}{{ $item->presentation ? ' - '.$item->presentation : '' }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-tag text-secondary"></i><span>Nombre producto</span></label>
                        <input class="form-control" id="sale_product_name" name="product_name" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-hashtag text-secondary"></i><span>Cantidad</span></label>
                        <input type="number" min="1" step="1" class="form-control" id="sale_quantity" name="quantity" value="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-dollar-sign text-secondary"></i><span>Precio unitario</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="sale_unit_price" name="unit_price" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-calculator text-secondary"></i><span>Total</span></label>
                        <input type="text" class="form-control pos-total-preview" id="sale_total_preview" readonly value="$0.00">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-calendar text-secondary"></i><span>Fecha y hora</span></label>
                        <input type="datetime-local" class="form-control" name="sold_at" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-user text-secondary"></i><span>Cliente</span></label>
                        <input class="form-control" name="customer_name">
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-success d-inline-flex align-items-center gap-2"><i class="fa-solid fa-floppy-disk"></i><span>Registrar venta</span></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="pos-card">
            <div class="pos-card-head d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <div>
                    <h2 class="h5 mb-1 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-clock-rotate-left text-primary"></i><span>Historial de ventas</span></h2>
                    <p class="mb-0 pos-subtitle">{{ $sales->count() }} registros disponibles.</p>
                </div>
            </div>
            <div class="pos-card-body">
                <div class="table-responsive pos-table-wrap">
                    <table class="table table-modern pos-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $sale)
                                <tr>
                                    <td>{{ $sale->product_name }}</td>
                                    <td>{{ $sale->quantity }}</td>
                                    <td>${{ number_format($sale->unit_price, 2) }}</td>
                                    <td><span class="pos-pill pos-pill-total">${{ number_format($sale->total, 2) }}</span></td>
                                    <td>{{ $sale->customer_name ?: 'Publico general' }}</td>
                                    <td>{{ $sale->sold_at?->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" type="button" data-bs-toggle="collapse" data-bs-target="#edit-sale-{{ $sale->id }}"><i class="fa-solid fa-pen"></i><span>Editar</span></button>
                                        <form class="d-inline" method="POST" action="{{ route('sales-eliminar', $sale) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"><i class="fa-solid fa-trash"></i><span>Eliminar</span></button>
                                        </form>
                                    </td>
                                </tr>
                                <tr class="collapse" id="edit-sale-{{ $sale->id }}">
                                    <td colspan="7" class="bg-light">
                                        <form method="POST" action="{{ route('sales-actualizar', $sale) }}" class="row g-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="col-md-2">
                                                <select name="inventory_item_id" class="form-select">
                                                    <option value="">Sin relacion</option>
                                                    @foreach ($items as $item)
                                                        <option value="{{ $item->id }}" {{ (int) $sale->inventory_item_id === (int) $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2"><input class="form-control" name="product_name" value="{{ $sale->product_name }}" required></div>
                                            <div class="col-md-1"><input type="number" min="1" class="form-control" name="quantity" value="{{ $sale->quantity }}" required></div>
                                            <div class="col-md-2"><input type="number" step="0.01" min="0" class="form-control" name="unit_price" value="{{ $sale->unit_price }}" required></div>
                                            <div class="col-md-2"><input class="form-control" name="customer_name" value="{{ $sale->customer_name }}"></div>
                                            <div class="col-md-2"><input type="datetime-local" class="form-control" name="sold_at" value="{{ $sale->sold_at?->format('Y-m-d\\TH:i') }}" required></div>
                                            <div class="col-md-1"><button class="btn btn-sm btn-primary w-100">OK</button></div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No hay ventas registradas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        const items = @json($itemsJson);
        const $speciesFilter = $('#sale_species_filter');
        const $productSelect = $('#sale_inventory_item_id');
        const $productName = $('#sale_product_name');
        const $quantity = $('#sale_quantity');
        const $unitPrice = $('#sale_unit_price');
        const $totalPreview = $('#sale_total_preview');

        function groupedOptionsMarkup(speciesId) {
            const grouped = {};

            items.forEach(function (item) {
                const target = item.target_species_ids || [];
                const isMatch = !speciesId || target.length === 0 || target.includes(Number(speciesId));

                if (!isMatch) {
                    return;
                }

                const category = item.category || 'Sin categoria';
                if (!grouped[category]) {
                    grouped[category] = [];
                }

                grouped[category].push(item);
            });

            const categories = Object.keys(grouped).sort((a, b) => a.localeCompare(b));

            const groupsMarkup = categories.map(function (category) {
                const optionsMarkup = grouped[category]
                    .sort((a, b) => a.name.localeCompare(b.name))
                    .map(function (item) {
                        const label = item.presentation ? `${item.name} - ${item.presentation}` : item.name;
                        const targetSpecies = (item.target_species_ids || []).join(',');

                        return `<option value="${item.id}" data-name="${item.name}" data-price="${item.unit_price}" data-unit="${item.sale_unit || 'unidad'}" data-presentation="${item.presentation || ''}" data-target-species="${targetSpecies}">${label}</option>`;
                    }).join('');

                return `<optgroup label="${category}">${optionsMarkup}</optgroup>`;
            }).join('');

            return `<option value="">Sin relacion</option>${groupsMarkup}`;
        }

        function rebuildProductOptions() {
            const selectedSpecies = $speciesFilter.val();
            const previousValue = $productSelect.val();

            $productSelect.html(groupedOptionsMarkup(selectedSpecies));

            if ($productSelect.find(`option[value="${previousValue}"]`).length > 0) {
                $productSelect.val(previousValue);
            } else {
                $productSelect.val('');
            }

            $productSelect.trigger('change.select2');
            applySelectedProduct();
        }

        function updateTotal() {
            const quantity = Number($quantity.val() || 0);
            const unitPrice = Number($unitPrice.val() || 0);
            const total = quantity * unitPrice;

            $totalPreview.val(`$${total.toFixed(2)}`);
        }

        function applySelectedProduct() {
            const option = $productSelect.find('option:selected');
            const itemId = option.val();

            if (!itemId) {
                updateTotal();
                return;
            }

            const name = option.data('name') || '';
            const presentation = option.data('presentation') || '';
            const price = Number(option.data('price') || 0);

            $productName.val(presentation ? `${name} - ${presentation}` : name);
            $unitPrice.val(price.toFixed(2));

            updateTotal();
        }

        $('.select2').select2({ width: '100%' });

        $speciesFilter.on('change', rebuildProductOptions);
        $productSelect.on('change', applySelectedProduct);
        $quantity.on('input change', updateTotal);
        $unitPrice.on('input change', updateTotal);

        rebuildProductOptions();
        updateTotal();
    });
</script>
@endpush
