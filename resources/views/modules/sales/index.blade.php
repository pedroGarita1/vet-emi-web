@extends('layouts.app')

@section('title', 'Punto de Venta | Emi Veterinaria')

@section('content')
<style>
/* POS Shell */
.pos-shell { display: grid; gap: 1.5rem; }
.pos-hero {
    background: radial-gradient(circle at 12% 15%, #a995cf 0%, #8b78b9 42%, #5d4a82 100%),
                linear-gradient(135deg, #3a334d 0%, #2a233d 100%);
    color: #fff; border-radius: 20px; padding: 1.5rem;
    box-shadow: 0 20px 40px rgba(93,74,130,.28);
}
.pos-hero-note { color: rgba(255,255,255,.85); }
.pos-primary-trigger {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; width: 100%; border: 0; border-radius: 16px; padding: 1.1rem; color: #fff;
    background: linear-gradient(135deg, #8b78b9 0%, #5d4a82 55%, #4a3d66 100%);
    box-shadow: 0 18px 28px rgba(93,74,130,.28); cursor: pointer;
    transition: transform .2s ease, box-shadow .2s ease;
}
.pos-primary-trigger:hover { transform: translateY(-2px); box-shadow: 0 22px 32px rgba(93,74,130,.35); }
.pos-primary-trigger strong { display: block; font-size: 1.05rem; text-align: left; }
.pos-primary-trigger span   { display: block; opacity: .88; font-size: .85rem; text-align: left; }
.pos-primary-trigger i      { font-size: 1.35rem; flex-shrink: 0; }
.pos-section-kicker {
    display: inline-flex; align-items: center; gap: .45rem;
    font-size: .78rem; font-weight: 800; letter-spacing: .04em;
    text-transform: uppercase; color: var(--emi-primary);
}
.pos-card {
    background: var(--emi-surface); border: 1px solid var(--emi-border);
    border-radius: 18px; box-shadow: 0 10px 26px rgba(37,35,50,.08); overflow: hidden;
}
.pos-card-head {
    padding: 1rem; border-bottom: 1px solid var(--emi-border);
    background: linear-gradient(90deg, #f8f7fb 0%, #f4f1fb 100%);
}
.pos-card-body { padding: 1.2rem; }
.pos-subtitle   { color: var(--emi-muted); font-size: .86rem; }
.pos-table thead th {
    background: #f8f7fb; border-bottom: 2px solid var(--emi-border);
    font-size: .78rem; text-transform: uppercase; letter-spacing: .03em;
    color: var(--emi-muted); font-weight: 800;
}
.pos-table tbody tr:hover { background: #f8f7fb; }
.pos-badge {
    display: inline-flex; align-items: center; border-radius: 999px;
    padding: 4px 12px; font-size: .75rem; font-weight: 700;
    background: #f4f1fb; color: #5d4a82; border: 1px solid #d9cfe8;
}
.pos-action-btn {
    background: linear-gradient(135deg, #8b78b9 0%, #5d4a82 100%);
    color: white; border: 0; border-radius: 8px; padding: .5rem 1rem; font-weight: 600;
}
.pos-action-btn:hover { filter: brightness(.95); }
.pos-action-btn:disabled { opacity: .5; cursor: not-allowed; }
.pos-modal-header { background: linear-gradient(135deg, #8b78b9 0%, #5d4a82 100%); color: white; border: 0; }
.pos-modal-body   { background: var(--emi-surface); }
/* POS Terminal */
.pos-terminal { display: grid; grid-template-columns: 1fr 1fr; gap: 0; min-height: 440px; }
@media (max-width: 767px) { .pos-terminal { grid-template-columns: 1fr; } }
.pos-search-panel { padding: 1.2rem; border-right: 2px solid #ede9f7; background: #faf8fd; }
.pos-search-panel .panel-title {
    font-size: .72rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase;
    color: #5d4a82; margin-bottom: 1rem; display: flex; align-items: center; gap: .4rem;
}
.pos-add-btn {
    width: 100%; padding: .65rem; border: 0; border-radius: 10px;
    background: linear-gradient(135deg, #8b78b9, #5d4a82); color: #fff;
    font-weight: 700; font-size: .95rem; cursor: pointer; transition: filter .15s;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
}
.pos-add-btn:hover:not(:disabled) { filter: brightness(.92); }
.pos-add-btn:disabled { opacity: .42; cursor: not-allowed; }
.pos-cart-panel { display: flex; flex-direction: column; }
.cart-header {
    padding: .85rem 1.2rem; background: linear-gradient(90deg, #5d4a82, #8b78b9); color: #fff;
    display: flex; align-items: center; justify-content: space-between; font-weight: 700; font-size: .85rem;
}
.cart-items-list { flex: 1; overflow-y: auto; max-height: 300px; list-style: none; margin: 0; padding: 0; }
.cart-item {
    display: flex; align-items: center; gap: .45rem; padding: .58rem 1rem;
    border-bottom: 1px solid #f0ecf8; font-size: .86rem; transition: background .1s;
}
.cart-item:hover { background: #f8f5ff; }
.cart-item-name  { flex: 1; font-weight: 600; color: #2a1f3d; line-height: 1.2; }
.cart-item-qty   { color: #5d4a82; font-weight: 700; white-space: nowrap; }
.cart-item-price { color: #999; font-size: .78rem; white-space: nowrap; }
.cart-item-sub   { font-weight: 800; color: #d67c1f; min-width: 68px; text-align: right; white-space: nowrap; }
.cart-item-del   {
    border: 0; background: none; color: #cc4444; cursor: pointer;
    padding: 2px 7px; border-radius: 6px; font-size: .82rem; transition: background .1s; line-height: 1;
}
.cart-item-del:hover { background: #fdecea; }
.cart-empty {
    flex: 1; display: flex; flex-direction: column; align-items: center;
    justify-content: center; color: #b0a4c8; padding: 2rem; text-align: center;
}
.cart-empty i { font-size: 2.6rem; margin-bottom: .6rem; opacity: .35; }
.cart-total-bar {
    border-top: 2px solid #ede9f7; padding: .7rem 1.2rem; background: #faf8fd;
    display: flex; align-items: center; justify-content: space-between;
}
.cart-total-label  { font-size: .78rem; font-weight: 800; text-transform: uppercase; color: #5d4a82; letter-spacing: .04em; }
.cart-total-amount { font-size: 1.4rem; font-weight: 900; color: #d67c1f; }
.pos-footer-fields { padding: 1rem 1.2rem; border-top: 2px solid #ede9f7; background: #fefaf8; }
.sale-items-mini { list-style: none; padding: 0; margin: 0; }
.sale-items-mini li {
    display: flex; gap: .4rem; align-items: baseline; font-size: .79rem;
    padding: 2px 0; border-bottom: 1px dotted #e8e4f0;
}
.sale-items-mini li:last-child { border: none; }
</style>

<div class="container-fluid py-2 py-md-3 pos-shell">

    <section class="pos-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="emi-badge mb-2 d-inline-flex align-items-center gap-2" style="background:rgba(255,255,255,.2);">
                    <i class="fa-solid fa-cash-register"></i> Módulo Tienda
                </span>
                <h1 class="h3 fw-bold mb-1">Punto de Venta</h1>
                <p class="mb-0 pos-hero-note">Agrega múltiples artículos al carrito y registra la venta al instante.</p>
            </div>
            <a href="{{ route('vistas-inicio') }}" class="btn btn-light btn-sm d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-house"></i><span>Panel</span>
            </a>
        </div>
    </section>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2" role="alert">
            <i class="fa-solid fa-circle-check fs-5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div>
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
            <div>
                <span class="pos-section-kicker mb-1"><i class="fa-solid fa-star"></i> Acción principal</span>
                <h2 class="h4 mb-1">Nueva venta</h2>
                <p class="text-muted mb-0">Selecciona múltiples productos, arma el carrito y registra la venta.</p>
            </div>
        </div>
        <button type="button" class="pos-primary-trigger" data-bs-toggle="modal" data-bs-target="#newSaleModal">
            <div>
                <strong>Nueva venta</strong>
                <span>Carrito multi-artículo — caja registradora digital.</span>
            </div>
            <i class="fa-solid fa-cart-shopping"></i>
        </button>
    </div>

    <div class="pos-card">
        <div class="pos-card-head">
            <h2 class="h5 mb-1 d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-receipt" style="color:#d67c1f;"></i><span>Historial de ventas</span>
            </h2>
            <p class="mb-0 pos-subtitle">{{ $sales->count() }} registros disponibles.</p>
        </div>
        <div class="pos-card-body">
            <div class="table-responsive">
                <table class="table table-modern pos-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Artículos vendidos</th>
                            <th>Total</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            @php $hasItems = $sale->items->count() > 0; @endphp
                            <tr>
                                <td><small class="text-muted">#{{ $sale->id }}</small></td>
                                <td>
                                    @if($hasItems)
                                        <span class="pos-badge mb-1">
                                            {{ $sale->items->count() }} {{ $sale->items->count() === 1 ? 'artículo' : 'artículos' }}
                                        </span>
                                        <ul class="sale-items-mini mt-1">
                                            @foreach($sale->items as $si)
                                                <li>
                                                    <span class="fw-semibold">{{ $si->product_name }}</span>
                                                    <span class="text-muted">x{{ $si->quantity }}</span>
                                                    <span class="ms-auto" style="color:#d67c1f;">${{ number_format($si->subtotal, 2) }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="fw-semibold">{{ $sale->product_name ?? '-' }}</span>
                                        @if($sale->quantity)
                                            <span class="text-muted ms-1">x{{ $sale->quantity }}</span>
                                        @endif
                                    @endif
                                </td>
                                <td><strong style="color:#d67c1f;">${{ number_format($sale->total, 2) }}</strong></td>
                                <td>{{ $sale->customer_name ?: 'Público general' }}</td>
                                <td><small>{{ $sale->sold_at?->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#edit-sale-{{ $sale->id }}">
                                        <i class="fa-solid fa-pen"></i><span>Editar</span>
                                    </button>
                                    <form class="d-inline" method="POST" action="{{ route('sales-eliminar', $sale) }}"
                                        onsubmit="return confirm('Eliminar esta venta y todos sus articulos?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" type="submit">
                                            <i class="fa-solid fa-trash"></i><span>Eliminar</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="collapse" id="edit-sale-{{ $sale->id }}">
                                <td colspan="6" class="bg-light p-3">
                                    <form method="POST" action="{{ route('sales-actualizar', $sale) }}" class="row g-2 align-items-end">
                                        @csrf @method('PUT')
                                        <div class="col-md-5">
                                            <label class="form-label small fw-semibold">Cliente</label>
                                            <input class="form-control form-control-sm" name="customer_name"
                                                value="{{ $sale->customer_name }}" placeholder="Público general">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label small fw-semibold">Fecha / Hora</label>
                                            <input type="datetime-local" class="form-control form-control-sm" name="sold_at"
                                                value="{{ $sale->sold_at?->format('Y-m-d\TH:i') }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-sm btn-primary w-100">Guardar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4"><strong>No hay ventas registradas.</strong></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="newSaleModal" tabindex="-1" aria-labelledby="newSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px; overflow:hidden;">

            <div class="modal-header pos-modal-header">
                <div>
                    <h5 class="modal-title mb-0 d-flex align-items-center gap-2" id="newSaleModalLabel">
                        <i class="fa-solid fa-cash-register"></i> Punto de Venta
                    </h5>
                    <p class="small mb-0 mt-1" style="opacity:.85;">Agrega artículos al carrito y luego registra la venta.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-0 pos-modal-body">
                <form method="POST" action="{{ route('sales-agregar') }}" id="saleForm">
                    @csrf
                    <input type="hidden" id="items_json" name="items_json" value="[]">

                    <div class="pos-terminal">

                        <div class="pos-search-panel">
                            <p class="panel-title"><i class="fa-solid fa-magnifying-glass"></i> Buscar artículo</p>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Filtrar por especie</label>
                                <select id="sale_species_filter" class="form-select form-select-sm select2">
                                    <option value="">Todas las especies</option>
                                    @foreach($speciesCatalog as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Producto del catálogo</label>
                                <select id="sale_inventory_item_id" class="form-select form-select-sm select2">
                                    <option value="">— Selecciona un producto —</option>
                                    @foreach ($items->groupBy('category') as $category => $group)
                                        <optgroup label="{{ $category }}">
                                            @foreach ($group as $item)
                                                <option value="{{ $item->id }}"
                                                    data-name="{{ $item->name }}"
                                                    data-price="{{ $item->unit_price }}"
                                                    data-unit="{{ $item->sale_unit }}"
                                                    data-presentation="{{ $item->presentation }}"
                                                    data-target-species="{{ $item->target_species }}">
                                                    {{ $item->name }}{{ $item->presentation ? ' - '.$item->presentation : '' }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Nombre del artículo</label>
                                <input type="text" id="sale_product_name" class="form-control form-control-sm"
                                    placeholder="Se llena al elegir producto o escribe manualmente">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-5">
                                    <label class="form-label small fw-semibold">Cantidad</label>
                                    <input type="number" min="1" step="1" id="sale_quantity"
                                        class="form-control form-control-sm" value="1">
                                </div>
                                <div class="col-7">
                                    <label class="form-label small fw-semibold">Precio unitario ($)</label>
                                    <input type="number" min="0" step="0.01" id="sale_unit_price"
                                        class="form-control form-control-sm" placeholder="0.00">
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                                <span class="small text-muted">Subtotal:</span>
                                <span id="sale_subtotal_preview" class="fw-bold fs-6" style="color:#d67c1f;">$0.00</span>
                            </div>

                            <button type="button" id="btnAddToCart" class="pos-add-btn" disabled>
                                <i class="fa-solid fa-circle-plus"></i> Agregar al carrito
                            </button>

                            <div id="addErrorMsg" class="text-danger small mt-2" style="display:none;"></div>
                        </div>

                        <div class="pos-cart-panel">
                            <div class="cart-header">
                                <span><i class="fa-solid fa-receipt me-1"></i> Carrito</span>
                                <span id="cartBadge" class="badge" style="background:rgba(255,255,255,.22); font-size:.78rem;">0 artículos</span>
                            </div>

                            <div id="cartEmpty" class="cart-empty">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <p class="fw-semibold mb-1">Carrito vacío</p>
                                <p class="small mb-0 opacity-75">Agrega artículos desde el panel izquierdo.</p>
                            </div>

                            <ul id="cartItemsList" class="cart-items-list" style="display:none;"></ul>

                            <div class="cart-total-bar">
                                <span class="cart-total-label"><i class="fa-solid fa-tag me-1"></i> Total</span>
                                <span class="cart-total-amount" id="cartTotal">$0.00</span>
                            </div>
                        </div>

                    </div>

                    <div class="pos-footer-fields">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">
                                    <i class="fa-solid fa-user me-1" style="color:#5d4a82;"></i> Cliente (opcional)
                                </label>
                                <input class="form-control form-control-sm" name="customer_name" placeholder="Público general">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">
                                    <i class="fa-solid fa-calendar me-1" style="color:#5d4a82;"></i> Fecha y hora
                                </label>
                                <input type="datetime-local" class="form-control form-control-sm" name="sold_at"
                                    value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <button type="submit" id="btnRegister"
                                    class="pos-action-btn flex-fill d-flex align-items-center justify-content-center gap-2"
                                    disabled style="padding:.55rem .8rem; font-size:.9rem; border-radius:10px;">
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span>Registrar venta</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var catalogItems = @json($itemsJson);
    var cart   = [];
    var nextId = 1;

    var $speciesFilter   = $('#sale_species_filter');
    var $productSelect   = $('#sale_inventory_item_id');
    var $productName     = $('#sale_product_name');
    var $quantity        = $('#sale_quantity');
    var $unitPrice       = $('#sale_unit_price');
    var $subtotalPreview = $('#sale_subtotal_preview');
    var $btnAdd          = $('#btnAddToCart');
    var $btnRegister     = $('#btnRegister');
    var $addErrorMsg     = $('#addErrorMsg');
    var $cartEmpty       = $('#cartEmpty');
    var $cartItemsList   = $('#cartItemsList');
    var $cartTotal       = $('#cartTotal');
    var $cartBadge       = $('#cartBadge');
    var $itemsJsonField  = $('#items_json');

    function money(n) {
        return '$' + Number(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function calcSubtotal() {
        var qty   = Math.max(1, parseInt($quantity.val()) || 1);
        var price = parseFloat($unitPrice.val()) || 0;
        return +(qty * price).toFixed(2);
    }

    function updateSubtotalPreview() {
        $subtotalPreview.text(money(calcSubtotal()));
    }

    function validateAddBtn() {
        var hasName  = ($productName.val() || '').trim().length > 0;
        var hasPrice = $unitPrice.val() !== '' && parseFloat($unitPrice.val()) >= 0;
        $btnAdd.prop('disabled', !hasName || !hasPrice);
    }

    function renderCart() {
        $cartItemsList.empty();
        var total = cart.reduce(function (s, i) { return s + i.subtotal; }, 0);
        $cartTotal.text(money(total));

        $itemsJsonField.val(JSON.stringify(cart.map(function (i) {
            return { inventory_item_id: i.inventory_item_id, product_name: i.product_name, quantity: i.quantity, unit_price: i.unit_price };
        })));

        if (cart.length === 0) {
            $cartEmpty.show(); $cartItemsList.hide();
            $cartBadge.text('0 artículos');
            $btnRegister.prop('disabled', true);
            return;
        }

        $cartEmpty.hide(); $cartItemsList.show();
        $cartBadge.text(cart.length + (cart.length === 1 ? ' artículo' : ' artículos'));
        $btnRegister.prop('disabled', false);

        cart.forEach(function (item) {
            var idLocal = item._id;
            var li = $('<li class="cart-item"></li>');
            li.append(
                $('<div class="cart-item-name"></div>').text(item.product_name),
                $('<span class="cart-item-qty"></span>').text('x' + item.quantity),
                $('<span class="cart-item-price"></span>').text(' @' + money(item.unit_price)),
                $('<span class="cart-item-sub"></span>').text(money(item.subtotal)),
                $('<button type="button" class="cart-item-del" title="Quitar"></button>')
                    .html('<i class="fa-solid fa-xmark"></i>')
                    .on('click', function () {
                        cart = cart.filter(function (c) { return c._id !== idLocal; });
                        renderCart();
                    })
            );
            $cartItemsList.append(li);
        });
    }

    $btnAdd.on('click', function () {
        var name   = ($productName.val() || '').trim();
        var qty    = Math.max(1, parseInt($quantity.val()) || 1);
        var price  = parseFloat($unitPrice.val());
        var itemId = $productSelect.val();

        if (!name) { $addErrorMsg.text('Escribe o selecciona el nombre del artículo.').show(); return; }
        if (isNaN(price) || price < 0) { $addErrorMsg.text('Ingresa un precio válido.').show(); return; }
        $addErrorMsg.hide();

        cart.push({ _id: nextId++, inventory_item_id: itemId ? parseInt(itemId) : null, product_name: name, quantity: qty, unit_price: price, subtotal: +(qty * price).toFixed(2) });

        $productSelect.val('').trigger('change.select2');
        $productName.val(''); $unitPrice.val(''); $quantity.val(1);
        updateSubtotalPreview(); validateAddBtn(); renderCart();
        setTimeout(function () { $productSelect.select2('open'); }, 80);
    });

    $quantity.add($unitPrice).on('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); if (!$btnAdd.prop('disabled')) $btnAdd.trigger('click'); }
    });

    function groupedOptionsMarkup(speciesId) {
        var grouped = {};
        catalogItems.forEach(function (item) {
            var target  = item.target_species_ids || [];
            var isMatch = !speciesId || target.length === 0 || target.indexOf(Number(speciesId)) !== -1;
            if (!isMatch) return;
            var cat = item.category || 'Sin categoría';
            if (!grouped[cat]) grouped[cat] = [];
            grouped[cat].push(item);
        });
        var cats = Object.keys(grouped).sort(function (a, b) { return a.localeCompare(b); });
        var groups = cats.map(function (cat) {
            var opts = grouped[cat]
                .sort(function (a, b) { return a.name.localeCompare(b.name); })
                .map(function (item) {
                    var label = item.presentation ? item.name + ' - ' + item.presentation : item.name;
                    return '<option value="' + item.id + '" data-name="' + item.name + '" data-price="' + item.unit_price + '" data-presentation="' + (item.presentation || '') + '" data-target-species="' + (item.target_species_ids || []).join(',') + '">' + label + '</option>';
                }).join('');
            return '<optgroup label="' + cat + '">' + opts + '</optgroup>';
        }).join('');
        return '<option value="">— Selecciona un producto —</option>' + groups;
    }

    function rebuildProductOptions() {
        var prev = $productSelect.val();
        $productSelect.html(groupedOptionsMarkup($speciesFilter.val()));
        if ($productSelect.find('option[value="' + prev + '"]').length) $productSelect.val(prev);
        else $productSelect.val('');
        $productSelect.trigger('change.select2');
    }

    $productSelect.on('change', function () {
        var opt = $productSelect.find('option:selected');
        if (!opt.val()) { updateSubtotalPreview(); validateAddBtn(); return; }
        var name  = opt.data('name')  || '';
        var pres  = opt.data('presentation') || '';
        var price = parseFloat(opt.data('price') || 0);
        $productName.val(pres ? name + ' - ' + pres : name);
        $unitPrice.val(price.toFixed(2));
        updateSubtotalPreview(); validateAddBtn();
    });

    $speciesFilter.on('change', rebuildProductOptions);
    $quantity.on('input change', function () { updateSubtotalPreview(); validateAddBtn(); });
    $unitPrice.on('input change', function () { updateSubtotalPreview(); validateAddBtn(); });
    $productName.on('input', validateAddBtn);

    $('#newSaleModal').on('hidden.bs.modal', function () {
        cart = []; nextId = 1;
        $productSelect.val('').trigger('change.select2');
        $productName.val(''); $unitPrice.val(''); $quantity.val(1);
        $addErrorMsg.hide(); renderCart();
    });

    $(function () {
        // Select2 dentro de modal requiere dropdownParent para que el menú sea visible y clickeable.
        if ($.fn.select2) {
            $('.select2').select2({
                width: '100%',
                dropdownParent: $('#newSaleModal')
            });
        }

        rebuildProductOptions();
        renderCart();
        updateSubtotalPreview();
        validateAddBtn();
    });
}());
</script>
@endpush
