<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\EnviarAvisoPorCorreo;
use App\Models\Notificacion;
use App\Models\SuscriptorCorreo;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    /**
     * Mostrar listado de notificaciones/avisos
     */
    public function index(Request $request): View
    {
        $notificaciones = Notificacion::query()
            ->with('creadaPor')
            ->when($request->filled('tipo'), function ($query) use ($request) {
                return $query->where('tipo', $request->get('tipo'));
            })
            ->when($request->filled('estado'), function ($query) use ($request) {
                $estado = $request->get('estado');
                if ($estado === 'activa') {
                    return $query->where('activa', true);
                } elseif ($estado === 'inactiva') {
                    return $query->where('activa', false);
                }
            })
            ->latest('created_at')
            ->paginate(15);

        return view('modules.notificaciones.index', [
            'notificaciones' => $notificaciones,
            'totalSuscriptores' => SuscriptorCorreo::suscritosActivos()->count(),
        ]);
    }

    /**
     * Mostrar formulario para crear nueva notificación
     */
    public function create(): View
    {
        return view('modules.notificaciones.crear', [
            'tipos' => ['promocion' => 'Promoción', 'cierre' => 'Cierre', 'aviso' => 'Aviso', 'otro' => 'Otro'],
        ]);
    }

    /**
     * Guardar nueva notificación
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string', 'min:10'],
            'tipo' => ['required', 'in:promocion,cierre,aviso,otro'],
            'fecha_inicio' => ['required', 'date', 'after_or_equal:now'],
            'fecha_fin' => ['nullable', 'date', 'after:fecha_inicio'],
            'activa' => ['boolean'],
            'enviar_ahora' => ['boolean'],
        ]);

        $notificacion = Notificacion::create([
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'tipo' => $datos['tipo'],
            'fecha_inicio' => $datos['fecha_inicio'],
            'fecha_fin' => $datos['fecha_fin'],
            'activa' => $request->boolean('activa', true),
            'creada_por' => auth()->id(),
        ]);

        if ($request->boolean('enviar_ahora')) {
            $this->enviarNotificacionAClientes($notificacion);
        }

        return redirect()
            ->route('notificaciones-listar')
            ->with('success', 'Aviso creado exitosamente' . ($request->boolean('enviar_ahora') ? ' y enviado por correo.' : '.'));
    }

    /**
     * Mostrar formulario para editar notificación
     */
    public function edit(Notificacion $notificacion): View
    {
        return view('modules.notificaciones.editar', [
            'notificacion' => $notificacion,
            'tipos' => ['promocion' => 'Promoción', 'cierre' => 'Cierre', 'aviso' => 'Aviso', 'otro' => 'Otro'],
        ]);
    }

    /**
     * Actualizar notificación existente
     */
    public function update(Request $request, Notificacion $notificacion): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string', 'min:10'],
            'tipo' => ['required', 'in:promocion,cierre,aviso,otro'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after:fecha_inicio'],
            'activa' => ['boolean'],
            'enviar_ahora' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('notificaciones-listar')
                ->withErrors($validator)
                ->withInput()
                ->with('editar_notificacion_id', $notificacion->id);
        }

        $datos = $validator->validated();

        $notificacion->update([
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'tipo' => $datos['tipo'],
            'fecha_inicio' => $datos['fecha_inicio'],
            'fecha_fin' => $datos['fecha_fin'],
            'activa' => $request->boolean('activa', true),
        ]);

        if ($request->boolean('enviar_ahora')) {
            $this->enviarNotificacionAClientes($notificacion);
        }

        return redirect()
            ->route('notificaciones-listar')
            ->with('success', 'Aviso actualizado exitosamente' . ($request->boolean('enviar_ahora') ? ' y enviado por correo.' : '.'));
    }

    /**
     * Eliminar notificación
     */
    public function destroy(Notificacion $notificacion): RedirectResponse
    {
        $titulo = $notificacion->titulo;
        $notificacion->delete();

        return redirect()
            ->route('notificaciones-listar')
            ->with('success', "Aviso '{$titulo}' eliminado exitosamente.");
    }

    /**
     * Enviar notificación a clientes por correo
     */
    public function enviar(Notificacion $notificacion): RedirectResponse
    {
        $enviados = $this->enviarNotificacionAClientes($notificacion);

        return redirect()
            ->route('notificaciones-listar')
            ->with('success', "Aviso enviado a {$enviados} cliente(s) por correo.");
    }

    /**
     * Función para enviar los correos a clientes
     */
    private function enviarNotificacionAClientes(Notificacion $notificacion): int
    {
        $suscriptores = SuscriptorCorreo::suscritosActivos($notificacion->tipo)->get();
        $enviados = 0;

        foreach ($suscriptores as $suscriptor) {
            try {
                $nombreCliente = $suscriptor->usuario?->name
                    ?? trim((string) strstr($suscriptor->correo, '@', true))
                    ?: 'Cliente';

                Mail::to($suscriptor->correo)
                    ->send(new EnviarAvisoPorCorreo($notificacion, $nombreCliente));
                $enviados++;
            } catch (\Exception $e) {
                \Log::error("Error enviando correo a {$suscriptor->correo}: " . $e->getMessage());
            }
        }

        $notificacion->marcarEnviada($enviados);

        return $enviados;
    }

    /**
     * Cambiar estado activo/inactivo de una notificación
     */
    public function cambiarEstado(Notificacion $notificacion): RedirectResponse
    {
        $notificacion->update(['activa' => !$notificacion->activa]);

        $estado = $notificacion->activa ? 'activado' : 'desactivado';

        return redirect()
            ->route('notificaciones-listar')
            ->with('success', "Aviso {$estado} exitosamente.");
    }
}
