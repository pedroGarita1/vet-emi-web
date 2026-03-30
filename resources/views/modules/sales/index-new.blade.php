@extends('layouts.app')

@section('title', 'Punto de Venta | Emi Veterinaria')

@section('content')
<style>
    .pos-shell {
        display: grid;
        gap: 1.5rem;
    }

    .pos-hero {
        background:
            radial-gradient(circle at 12% 15%, #f4b860 0%, #e89a3a 42%, #d67c1f 100%),
            linear-gradient(135deg, #8b5a2b 0%, #6b4426 100%);
        color: #fff;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 20px 40px rgba(139, 90, 43, 0.28);
    }

    .pos-hero-note {
        color: rgba(255, 255, 255, 0.85);
    }

    .pos-primary-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        width: 100%;
        border: 0;
        border-radius: 16px;
        padding: 1.1rem;
        color: #fff;
        background: linear-gradient(135deg, #e89a3a 0%, #d67c1f 55%, #b85c13 100%);
        box-shadow: 0 18px 28px rgba(186, 124, 31, 0.32);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .pos-primary-trigger:hover {
        transform: translateY(-2px);
        box-shadow: 0 22px 32px rgba(186, 124, 31, 0.4);
    }

    .pos-primary-trigger strong {
        display: block;
        font-size: 1.05rem;
        text-align: left;
    }

    .pos-primary-trigger span {
        display: block;
        opacity: 0.88;
        font-size: 0.85rem;
        text-align: left;
    }

    .pos-primary-trigger i {
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .pos-section-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #d67c1f;
    }

    .pos-card {
        background: var(--emi-surface);
        border: 1px solid var(--emi-border);
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(37, 35, 50, 0.08);
        overflow: hidden;
    }

    .pos-card-head {
        padding: 1rem;
        border-bottom: 1px solid var(--emi-border);
        background: linear-gradient(90deg, #fff9f4 0%, #fef5f0 100%);
    }

    .pos-card-body {
        padding: 1.2rem;
    }

    .pos-subtitle {
        color: var(--emi-muted);
        font-size: 0.86rem;
    }

    .pos-table thead th {
        background: #fff9f4;
        border-bottom: 2px solid #e5ddd0;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #8b5a2b;
        font-weight: 800;
    }

    .pos-table tbody tr:hover {
        background: #fef8f3;
    }

    .pos-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 700;
        background: #fff4e6;
        color: #d67c1f;
        border: 1px solid #e5b8a0;
    }

    .pos-action-btn {
        background: linear-gradient(135deg, #e89a3a 0%, #d67c1f 100%);
        color: white;
        border: 0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
    }

    .pos-action-btn:hover {
        filter: brightness(0.95);
    }

    /* Modal styling */
    .pos-modal-header {
        background: linear-gradient(135deg, #f4b860 0%, #e89a3a 100%);
        color: white;
        border: 0;
    }

    .pos-modal-body {
        background: #fefaf6;
    }
</style>

<div class="container-fluid py-2 py-md-3 pos-shell">
    <!-- Hero Section -->
    <section class="pos-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2" style="background: rgba(255,255,255,0.2);"><i class="fa-solid fa-cash-register"></i> Módulo Tienda</span>
                <h1 class="h3 fw-bold mb-1">Punto de Venta</h1>
                <p class="mb-0 pos-hero-note">Venta rápida estilo tienda. Medicamentos, accesorios y alimentos organizados por categoría.</p>
            </div>
            <a href="{{ route('vistas-inicio') }}" class="btn btn-light btn-sm d-inline-flex align-items-center gap-2"><i class="fa-solid fa-house"></i><span>Panel</span></a>
        </div>
    </section>

    <!-- Acción Principal -->
    <div>
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
            <div>
                <span class="pos-section-kicker mb-1"><i class="fa-solid fa-star"></i> Acción principal</span>
                <h2 class="h4 mb-1">Nueva venta</h2>
                <p class="text-muted mb-0">Selecciona productos, establece cantidad y precio, registra la venta.</p>
            </div>
        </div>
        <button type="button" class="pos-primary-trigger" data-bs-toggle="modal" data-bs-target="#newSaleModal">
            <div>
                <strong>Nueva venta</strong>
                <span>Carrito de compra rápida y eficiente.</span>
            </div>
            <i class="fa-solid fa-cart-shopping"></i>
        </button>
    </div>

    <!-- Historial de Ventas -->
    <div class="pos-card">
        <div class="pos-card-head">
            <div>
                <h2 class="h5 mb-1 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-receipt" style="color: #d67c1f;"></i><span>Historial de ventas</span></h2>
                <p class="mb-0 pos-subtitle">{{ $sales->count() }} registros disponibles en el sistema.</p>
            </div>
        </div>
        <div class="pos-card-body">
            <div class="table-responsive">
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
                                <td><strong>{{ $sale->product_name }}</strong></td>
                                <td><span class="pos-badge">{{ $sale->quantity }}</span></td>
                                <td>${{ number_format($sale->unit_price, 2) }}</td>
                                <td><strong style="color: #d67c1f;">${{ number_format($sale->total, 2) }}</strong></td>
                                <td>{{ $sale->customer_name ?: 'Publico general' }}</td>
                                <td><small>{{ $sale->sold_at?->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" type="button" data-bs-toggle="collapse" data-bs-target="#edit-sale-{{ $sale->id }}"><i class="fa-solid fa-pen"></i><span>Editar</span></button>
                                    <form class="d-inline" method="POST" action="{{ route('sales-eliminar', $sale) }}" onsubmit="return confirm('¿Eliminar venta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" type="submit"><i class="fa-solid fa-trash"></i><span>Eliminar</span></button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="collapse" id="edit-sale-{{ $sale->id }}">
                                <td colspan="7" class="bg-light p-3">
                                    <form method="POST" action="{{ route('sales-actualizar', $sale) }}" class="row g-2">
                                        @csrf
                                        @method('PUT')
                                        <div class="col-md-2">
                                            <label class="form-label small">Producto</label>
                                            <select name="inventory_item_id" class="form-select form-select-sm">
                                                <option value="">Sin relacion</option>
                                                @foreach ($items as $item)
                                                    <option value="{{ $item->id }}" {{ (int) $sale->inventory_item_id === (int) $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Nombre</label>
                                            <input class="form-control form-control-sm" name="product_name" value="{{ $sale->product_name }}" required>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Cant.</label>
                                            <input type="number" min="1" class="form-control form-control-sm" name="quantity" value="{{ $sale->quantity }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Precio</label>
                                            <input type="number" step="0.01" min="0" class="form-control form-control-sm" name="unit_price" value="{{ $sale->unit_price }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Cliente</label>
                                            <input class="form-control form-control-sm" name="customer_name" value="{{ $sale->customer_name }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Fecha/Hora</label>
                                            <input type="datetime-local" class="form-control form-control-sm" name="sold_at" value="{{ $sale->sold_at?->format('Y-m-d\\TH:i') }}" required>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">&nbsp;</label>
                                            <button class="btn btn-sm btn-primary w-100">Guardar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4"><strong>No hay ventas registradas.</strong></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Nueva Venta -->
<div class="modal fade" id="newSaleModal" tabindex="-1" aria-labelledby="newSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header pos-modal-header">
                <div>
                    <h5 class="modal-title mb-1" id="newSaleModalLabel" style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-cart-shopping"></i> Nueva Venta</h5>
                    <p class="small mb-0" style="opacity: 0.9;">Crea una venta rápida. Los datos se rellenan automáticamente.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pos-modal-body">
                <form method="POST" action="{{ route('sales-agregar') }}" id="saleForm">
                    @csrf

                    <!-- Sección 1: Filtro de Especie -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="fa-solid fa-filter" style="color: #d67c1f;"></i> Filtrar por especie</label>
                        <select id="sale_species_filter" class="form-select select2">
                            <option value="">Todas las especies</option>
                            @foreach($speciesCatalog as $species)
                                <option value="{{ $species->id }}">{{ $species->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Filtra los productos disponibles según la especie (opcional).</div>
                    </div>

                    <!-- Sección 2: Selección de Producto -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="fa-solid fa-boxes-stacked" style="color: #d67c1f;"></i> Selecciona producto</label>
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

                    <!-- Sección 3: Detalles de Venta -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nombre del producto</label>
                            <input class="form-control" id="sale_product_name" name="product_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cantidad</label>
                            <input type="number" min="1" step="1" class="form-control" id="sale_quantity" name="quantity" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Precio unitario</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="sale_unit_price" name="unit_price" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Total</label>
                            <input type="text" class="form-control" id="sale_total_preview" readonly style="background: #fff4e6; font-weight: 800; color: #d67c1f; border: 2px solid #e5b8a0;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fecha y hora</label>
                            <input type="datetime-local" class="form-control" name="sold_at" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cliente (opcional)</label>
                            <input class="form-control" name="customer_name" placeholder="Ej: Juan Pérez">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top p-3" style="background: #fefaf6;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="saleForm" class="pos-action-btn d-inline-flex align-items-center gap-2" style="padding: 0.6rem 1.2rem; font-size: 1rem;">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Registrar venta</span>
                </button>
            </div>
        </div>
    </div>
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
