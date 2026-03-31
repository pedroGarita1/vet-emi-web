<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; }
        .wrapper { background: #f5f7fa; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { font-size: 28px; margin-bottom: 5px; }
        .header p { font-size: 14px; opacity: 0.9; }
        .body { padding: 40px 20px; }
        .body h2 { color: #1f2937; font-size: 22px; margin-bottom: 15px; }
        .body p { margin-bottom: 15px; color: #4b5563; }
        .tipo-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .tipo-promocion { background: #dbeafe; color: #0369a1; }
        .tipo-cierre { background: #fee2e2; color: #991b1b; }
        .tipo-aviso { background: #fef3c7; color: #92400e; }
        .tipo-otro { background: #e5e7eb; color: #1f2937; }
        .contenido { background: #f9fafb; padding: 20px; border-left: 4px solid #2563eb; border-radius: 4px; margin: 20px 0; }
        .vigencia { background: #f0f4ff; padding: 15px; border-radius: 4px; margin: 20px 0; font-size: 13px; }
        .vigencia table { width: 100%; }
        .vigencia td { padding: 5px 0; }
        .vigencia td:first-child { font-weight: bold; color: #1f2937; width: 100px; }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .footer { background: #f9fafb; padding: 30px 20px; text-align: center; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; }
        .footer-link { color: #2563eb; text-decoration: none; }
        .footer-link:hover { text-decoration: underline; }
        .divider { height: 1px; background: #e5e7eb; margin: 20px 0; }
    </style>
</head>
<body>
    @php
        $tipoNombre = match($notificacion->tipo) {
            'promocion' => 'Promoción',
            'cierre' => 'Aviso de Cierre',
            'aviso' => 'Aviso Importante',
            default => 'Notificación',
        };

        $tipoIcono = match($notificacion->tipo) {
            'promocion' => '🎉',
            'cierre' => '⏰',
            'aviso' => '📢',
            default => '📬',
        };
    @endphp

    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>🐾 Emi Veterinaria</h1>
                <p>{{ $tipoNombre }}</p>
            </div>

            <!-- Contenido -->
            <div class="body">
                <p>¡Hola <strong>{{ $nombreCliente }}</strong>!</p>

                <div class="tipo-badge @switch($notificacion->tipo)
                    @case('promocion') tipo-promocion @break
                    @case('cierre') tipo-cierre @break
                    @case('aviso') tipo-aviso @break
                    @default tipo-otro
                @endswitch">
                    {{ $tipoIcono }} {{ $tipoNombre }}
                </div>

                <h2>{{ $notificacion->titulo }}</h2>

                <div class="contenido">
                    {!! nl2br(e($notificacion->descripcion)) !!}
                </div>

                <!-- Información de vigencia -->
                @if($notificacion->fecha_inicio || $notificacion->fecha_fin)
                    <div class="vigencia">
                        <table>
                            <tr>
                                <td>📅 Vigente desde:</td>
                                <td>{{ $notificacion->fecha_inicio->format('d \d\e F \d\e Y \a \l\a\s H:i') }}</td>
                            </tr>
                            @if($notificacion->fecha_fin)
                                <tr>
                                    <td>📅 Válido hasta:</td>
                                    <td>{{ $notificacion->fecha_fin->format('d \d\e F \d\e Y \a \l\a\s H:i') }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                @endif

                <!-- Call to Action según tipo -->
                @if($notificacion->tipo === 'promocion')
                    <p><strong>¡No te pierdas esta oportunidad!</strong> Visítanos pronto para aprovechar esta promoción.</p>
                @elseif($notificacion->tipo === 'cierre')
                    <p><strong>Recuerda que no estaremos abiertos en esta fecha.</strong> Planifica tu visita en otros horarios.</p>
                @elseif($notificacion->tipo === 'aviso')
                    <p><strong>Esta es información importante que debes conocer.</strong> Si tienes preguntas, no dudes en contactarnos.</p>
                @endif

                <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 13px;">
                    ¿Preguntas? Contáctanos en <a href="mailto:info@veterinariaemi.com" class="footer-link">info@veterinariaemi.com</a>
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin-bottom: 10px;">
                    © {{ now()->year }} Emi Veterinaria. Todos los derechos reservados.
                </p>
                <p>
                    <a href="#" class="footer-link">Actualizar preferencias</a> • 
                    <a href="#" class="footer-link">Desuscribirse</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
