<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta Medica</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 13px; }
        .header { border-bottom: 2px solid #10b981; margin-bottom: 14px; padding-bottom: 8px; }
        .title { font-size: 20px; font-weight: bold; color: #047857; }
        .subtitle { font-size: 12px; color: #4b5563; }
        .grid { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .grid td { border: 1px solid #d1d5db; padding: 8px; vertical-align: top; }
        .label { font-weight: bold; width: 30%; background: #f9fafb; }
        .box { border: 1px solid #d1d5db; padding: 10px; min-height: 90px; margin-top: 8px; }
        .med-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .med-table th, .med-table td { border: 1px solid #d1d5db; padding: 6px; font-size: 12px; }
        .med-table th { background: #f3f4f6; }
        .footer { margin-top: 38px; }
        .sign { margin-top: 40px; border-top: 1px solid #9ca3af; width: 240px; padding-top: 6px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Receta Medica Veterinaria</div>
        <div class="subtitle">Documento generado automaticamente por Emi Veterinaria</div>
    </div>

    <table class="grid">
        <tr>
            <td class="label">Fecha de consulta</td>
            <td>{{ $consultation->consulted_at?->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Paciente</td>
            <td>{{ $consultation->pet_name }}</td>
        </tr>
        <tr>
            <td class="label">Especie</td>
            <td>{{ $consultation->species }}</td>
        </tr>
        <tr>
            <td class="label">Tipo / Raza</td>
            <td>{{ $consultation->petCatalog?->breed ?: 'No especificado' }}</td>
        </tr>
        <tr>
            <td class="label">Talla</td>
            <td>{{ $consultation->petCatalog?->size_category ? ucfirst($consultation->petCatalog->size_category) : 'No especificada' }}</td>
        </tr>
        <tr>
            <td class="label">Propietario</td>
            <td>{{ $consultation->owner_name }}</td>
        </tr>
        <tr>
            <td class="label">Diagnostico</td>
            <td>{{ $consultation->diagnosis }}</td>
        </tr>
        <tr>
            <td class="label">Costo de consulta</td>
            <td>${{ number_format((float) $consultation->cost, 2) }}</td>
        </tr>
    </table>

    <div class="box">
        <strong>Tratamiento / Indicaciones medicas:</strong>
        <div style="margin-top: 6px;">{!! $consultation->treatment ?: 'Sin indicaciones registradas.' !!}</div>
    </div>

    <div style="margin-top: 10px;">
        <strong>Medicacion / productos indicados:</strong>
        @if($consultation->consultationItems->isNotEmpty())
            <table class="med-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>Dosis</th>
                        <th>Frecuencia</th>
                        <th>Duracion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consultation->consultationItems as $item)
                        <tr>
                            <td>{{ $item->inventoryItem?->name ?: 'Producto' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->dosage ?: 'N/A' }}</td>
                            <td>
                                @if($item->frequency_hours)
                                    Cada {{ $item->frequency_hours }} h
                                @elseif($item->frequency_days)
                                    Cada {{ $item->frequency_days }} dias
                                @else
                                    Segun indicacion
                                @endif
                            </td>
                            <td>{{ $item->duration_days ? $item->duration_days.' dias' : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="margin-top:6px;">No se registraron medicamentos o productos.</div>
        @endif
    </div>

    <div class="footer">
        <div class="sign">Firma y sello del medico veterinario</div>
    </div>
</body>
</html>
