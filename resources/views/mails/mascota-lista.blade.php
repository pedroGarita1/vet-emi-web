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
            background: #dbeafe;
            color: #0369a1;
        }
        .contenido { background: #f9fafb; padding: 20px; border-left: 4px solid #2563eb; border-radius: 4px; margin: 20px 0; }
        .detalle { background: #f0f4ff; padding: 15px; border-radius: 4px; margin: 20px 0; font-size: 13px; }
        .detalle table { width: 100%; }
        .detalle td { padding: 5px 0; vertical-align: top; }
        .detalle td:first-child { font-weight: bold; color: #1f2937; width: 120px; }
        .footer { background: #f9fafb; padding: 30px 20px; text-align: center; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; }
        .footer-link { color: #2563eb; text-decoration: none; }
        .footer-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    @php
        $ownerName = $service->owner_name ?: 'Cliente';
        $readyAt = $service->ready_at ?: now();
    @endphp

    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>🐾 Emi Veterinaria</h1>
                <p>Servicio de Estetica</p>
            </div>

            <div class="body">
                <p>¡Hola <strong>{{ $ownerName }}</strong>!</p>

                <div class="tipo-badge">✨ Mascota lista para entrega</div>

                <h2>{{ $service->pet_name }} ya esta lista</h2>

                <div class="contenido">
                    Tu mascota ya finalizo su servicio de estetica y puede ser retirada en Emi Veterinaria.
                </div>

                <div class="detalle">
                    <table>
                        <tr>
                            <td>🐶 Mascota:</td>
                            <td>{{ $service->pet_name }}</td>
                        </tr>
                        <tr>
                            <td>🧴 Servicio:</td>
                            <td>{{ $service->service_type }}</td>
                        </tr>
                        <tr>
                            <td>🕒 Lista desde:</td>
                            <td>{{ $readyAt->format('d/m/Y H:i') }}</td>
                        </tr>
                        @if(!empty($service->notes))
                            <tr>
                                <td>📝 Nota:</td>
                                <td>{{ $service->notes }}</td>
                            </tr>
                        @endif
                    </table>
                </div>

                <p><strong>Gracias por confiar en nosotros.</strong> Te esperamos para la entrega.</p>

                <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 13px;">
                    ¿Preguntas? Contáctanos en <a href="mailto:info@veterinariaemi.com" class="footer-link">info@veterinariaemi.com</a>
                </p>
            </div>

            <div class="footer">
                <p style="margin-bottom: 10px;">
                    © {{ now()->year }} Emi Veterinaria. Todos los derechos reservados.
                </p>
                <p>
                    Este correo fue generado automaticamente por el sistema de estetica.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
