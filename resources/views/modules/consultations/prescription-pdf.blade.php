<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta Medica</title>
    <style>
        @page {
            margin: 18px 18px 112px 18px;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 13px;
            background: #fff;
        }

        .sheet {
            border: 1px solid #d5d7dc;
            background: #fff;
        }

        .content {
            padding: 14px 22px 26px 22px;
        }

        .header {
            text-align: center;
            margin-bottom: 14px;
            position: relative;
        }

        .header-main {
            font-size: 17px;
            letter-spacing: 2px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header-sub {
            font-size: 16px;
            font-weight: bold;
        }

        .cedula {
            display: inline-block;
            margin-top: 5px;
            background: #cbc8e7;
            color: #fff;
            border-radius: 10px;
            padding: 2px 14px;
            font-size: 13px;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .logo-left,
        .logo-right {
            position: absolute;
            top: 4px;
            width: 64px;
            height: 64px;
            border: 2px solid #b8bbc4;
            border-radius: 50%;
            text-align: center;
            line-height: 64px;
            color: #8d92a2;
            font-size: 11px;
            font-weight: bold;
            background: #fff;
        }

        .logo-left {
            left: 2px;
        }

        .logo-right {
            right: 2px;
        }

        .patient-row {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        .patient-row td {
            padding: 0 0 8px 0;
            vertical-align: bottom;
        }

        .label {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #2f3640;
            font-weight: 700;
            width: 122px;
        }

        .line {
            border-bottom: 2px solid #8f8f8f;
            font-size: 18px;
            color: #374151;
            width: 37%;
            padding-left: 8px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .row-gap {
            width: 12px;
        }

        .products {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .products th,
        .products td {
            border-bottom: 1px solid #d9dde4;
            padding: 6px 4px;
            font-size: 13px;
        }

        .products th {
            text-transform: uppercase;
            font-size: 12px;
            color: #4b5563;
            text-align: left;
            background: #f8fafc;
        }

        .products td:last-child,
        .products th:last-child {
            text-align: right;
            width: 34%;
        }

        .products tr {
            page-break-inside: avoid;
        }

        .product-notes {
            display: block;
            margin-top: 3px;
            font-size: 11px;
            color: #6b7280;
        }

        .diagnosis-wrap {
            text-align: center;
            margin-top: 14px;
            margin-bottom: 8px;
        }

        .diagnosis-title {
            text-transform: uppercase;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .diagnosis-text {
            font-size: 17px;
            font-weight: 600;
            color: #111827;
        }

        .treatment {
            margin-top: 8px;
            font-size: 14px;
            line-height: 1.55;
            white-space: pre-wrap;
            min-height: 40px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .summary td {
            vertical-align: bottom;
        }

        .owner-block {
            width: 55%;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .owner-block .owner-label {
            text-transform: uppercase;
            color: #4b5563;
            font-size: 12px;
            display: block;
        }

        .total-block {
            width: 45%;
            text-align: right;
        }

        .total-label {
            text-transform: uppercase;
            color: #4b5563;
            font-size: 12px;
            display: block;
            margin-bottom: 2px;
        }

        .total-value {
            font-size: 32px;
            line-height: 1;
            font-weight: 800;
            color: #111827;
        }

        .footer {
            position: fixed;
            left: 18px;
            right: 18px;
            bottom: 18px;
            height: 86px;
            background: #c4c0df;
            color: #fff;
            padding: 10px 18px;
            font-size: 11px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .footer strong {
            font-size: 12px;
        }
    </style>
</head>
<body>
    @php
        $itemsTotal = (float) $consultation->consultationItems->sum('subtotal');
        $grandTotal = (float) $consultation->cost + $itemsTotal;
    @endphp

    <div class="sheet">
        <div class="content">
            <div class="header">
            <div class="logo-left">LOGO</div>
            <div class="logo-right">LOGO</div>

                <div class="header-main">CLINICA VETERINARIA "EMI"</div>
                <div class="header-sub">M.V.Z. Berenice Nallely Flores</div>
                <div class="cedula">CED. PROF. 7124583</div>
            </div>

            <table class="patient-row">
                <tr>
                    <td class="label">Paciente:</td>
                    <td class="line">{{ $consultation->pet_name }}</td>
                    <td class="row-gap"></td>
                    <td class="label">Fecha:</td>
                    <td class="line">{{ $consultation->consulted_at?->format('d/m/Y') }}</td>
                </tr>
            </table>

            <table class="products">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Horas / Dias</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultation->consultationItems as $item)
                        <tr>
                            <td>
                                {{ $item->inventoryItem?->name ?: 'Producto' }}
                                @if($item->quantity)
                                    ({{ $item->quantity }})
                                @endif
                                @if(!empty(trim((string) $item->administration_notes)))
                                    <span class="product-notes">Nota: {{ $item->administration_notes }}</span>
                                @endif
                            </td>
                            <td>
                                @if($item->frequency_hours)
                                    {{ $item->frequency_hours }} h
                                @endif
                                @if($item->frequency_hours && $item->duration_days)
                                    |
                                @endif
                                @if($item->duration_days)
                                    {{ $item->duration_days }} dias
                                @elseif($item->frequency_days)
                                    cada {{ $item->frequency_days }} dias
                                @else
                                    --
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No se registraron productos para esta consulta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="diagnosis-wrap">
                <div class="diagnosis-title">Diagnostico</div>
                <div class="diagnosis-text">{{ $consultation->diagnosis }}</div>
            </div>

            @if(!empty(trim((string) $consultation->treatment)))
                <div class="treatment">{!! $consultation->treatment !!}</div>
            @endif

            <table class="summary">
                <tr>
                    <td class="owner-block">
                        <span class="owner-label">Propietario</span>
                        {{ $consultation->owner_name }}
                    </td>
                    <td class="total-block">
                        <span class="total-label">Total</span>
                        <span class="total-value">${{ number_format($grandTotal, 2) }}</span>
                    </td>
                </tr>
            </table>

        </div>

    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>
                    <strong>55 8571 2928</strong><br>
                    Av. Avila Camacho esq. Isidro Fabela<br>
                    Col. Guadalupe 1 Secc. Valle de Chalco
                </td>
                <td>
                    Lunes, Martes, Jueves y Viernes 11:00 am a 7:00 pm<br>
                    Sabado, Domingo y dias festivos 10:00 am a 3:00 pm<br>
                    <strong>MIERCOLES NO HAY SERVICIO</strong>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
