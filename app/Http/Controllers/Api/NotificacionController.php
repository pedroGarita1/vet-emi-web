<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Obtener notificaciones activas para la aplicación móvil
     */
    public function obtenerNotificacionesActivas(Request $request): JsonResponse
    {
        try {
            // Validación básica de que hay conexión a internet
            $notificaciones = Notificacion::activas()
                ->select('id', 'titulo', 'descripcion', 'tipo', 'fecha_inicio', 'fecha_fin')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'mensaje' => 'Notificaciones obtenidas correctamente.',
                'datos' => $notificaciones->map(fn ($n) => [
                    'id' => $n->id,
                    'titulo' => $n->titulo,
                    'descripcion' => $n->descripcion,
                    'tipo' => $n->tipo,
                    'tipoFormato' => $this->obtenerTipoFormato($n->tipo),
                    'fecha_inicio' => $n->fecha_inicio->toIso8601String(),
                    'fecha_fin' => $n->fecha_fin?->toIso8601String(),
                ]),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al obtener notificaciones. Verifica tu conexión a internet.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Obtener una notificación específica por ID
     */
    public function obtenerNotificacion(Notificacion $notificacion): JsonResponse
    {
        try {
            // Verificar que la notificación esté activa y vigente
            if (!$notificacion->activa || $notificacion->fecha_inicio > now() || 
                ($notificacion->fecha_fin && $notificacion->fecha_fin < now())) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'La notificación no está disponible.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'datos' => [
                    'id' => $notificacion->id,
                    'titulo' => $notificacion->titulo,
                    'descripcion' => $notificacion->descripcion,
                    'tipo' => $notificacion->tipo,
                    'tipoFormato' => $this->obtenerTipoFormato($notificacion->tipo),
                    'fecha_inicio' => $notificacion->fecha_inicio->toIso8601String(),
                    'fecha_fin' => $notificacion->fecha_fin?->toIso8601String(),
                    'creada_en' => $notificacion->created_at->toIso8601String(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al obtener la notificación.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Obtener formato legible del tipo de notificación
     */
    private function obtenerTipoFormato(string $tipo): array
    {
        return match($tipo) {
            'promocion' => [
                'icono' => '🎉',
                'nombre' => 'Promoción',
                'color' => '#10b981',
            ],
            'cierre' => [
                'icono' => '⏰',
                'nombre' => 'Aviso de Cierre',
                'color' => '#ef4444',
            ],
            'aviso' => [
                'icono' => '📢',
                'nombre' => 'Aviso Importante',
                'color' => '#f59e0b',
            ],
            default => [
                'icono' => '📬',
                'nombre' => 'Notificación',
                'color' => '#6366f1',
            ],
        };
    }
}
