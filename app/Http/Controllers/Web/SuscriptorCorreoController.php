<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SuscriptorCorreo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SuscriptorCorreoController extends Controller
{
    /**
     * Mostrar listado de suscriptores de correo
     */
    public function index(Request $request): View
    {
        $suscriptores = SuscriptorCorreo::query()
            ->with('usuario')
            ->when($request->filled('suscrito'), function ($query) use ($request) {
                return $query->where('suscrito', $request->get('suscrito') === 'true');
            })
            ->when($request->filled('tipo'), function ($query) use ($request) {
                return $query->where('tipo_avisos', $request->get('tipo'));
            })
            ->latest('created_at')
            ->paginate(20);

        return view('modules.notificaciones.suscriptores', [
            'suscriptores' => $suscriptores,
        ]);
    }

    /**
     * Mostrar formulario para agregar suscriptor
     */
    public function create(): View
    {
        return view('modules.notificaciones.crear-suscriptor', [
            'tipos' => ['todos' => 'Todos los avisos', 'promociones' => 'Solo promociones', 'cierres' => 'Solo cierres'],
        ]);
    }

    /**
     * Guardar nuevo suscriptor de correo
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'correo' => ['required', 'email', 'unique:suscriptores_correo,correo'],
            'tipo_avisos' => ['required', 'in:todos,promociones,cierres'],
            'usuario_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        SuscriptorCorreo::create([
            'correo' => $datos['correo'],
            'tipo_avisos' => $datos['tipo_avisos'],
            'usuario_id' => $datos['usuario_id'] ?? auth()->id(),
            'suscrito' => true,
        ]);

        return redirect()
            ->route('suscriptores-listar')
            ->with('success', 'Suscriptor agregado exitosamente.');
    }

    /**
     * Mostrar formulario para editar suscriptor
     */
    public function edit(SuscriptorCorreo $suscriptor): View
    {
        return view('modules.notificaciones.editar-suscriptor', [
            'suscriptor' => $suscriptor,
            'tipos' => ['todos' => 'Todos los avisos', 'promociones' => 'Solo promociones', 'cierres' => 'Solo cierres'],
        ]);
    }

    /**
     * Actualizar suscriptor existente
     */
    public function update(Request $request, SuscriptorCorreo $suscriptor): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'correo' => ['required', 'email', 'unique:suscriptores_correo,correo,' . $suscriptor->id],
            'tipo_avisos' => ['required', 'in:todos,promociones,cierres'],
            'suscrito' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('suscriptores-listar')
                ->withErrors($validator)
                ->withInput()
                ->with('editar_suscriptor_id', $suscriptor->id);
        }

        $datos = $validator->validated();

        $suscriptor->update([
            'correo' => $datos['correo'],
            'tipo_avisos' => $datos['tipo_avisos'],
            'suscrito' => $request->boolean('suscrito', true),
        ]);

        return redirect()
            ->route('suscriptores-listar')
            ->with('success', 'Suscriptor actualizado exitosamente.');
    }

    /**
     * Eliminar suscriptor
     */
    public function destroy(SuscriptorCorreo $suscriptor): RedirectResponse
    {
        $correo = $suscriptor->correo;
        $suscriptor->delete();

        return redirect()
            ->route('suscriptores-listar')
            ->with('success', "Suscriptor '{$correo}' eliminado exitosamente.");
    }

    /**
     * Cambiar estado de suscripción
     */
    public function cambiarEstado(SuscriptorCorreo $suscriptor): RedirectResponse
    {
        $suscriptor->update(['suscrito' => !$suscriptor->suscrito]);

        $estado = $suscriptor->suscrito ? 'suscrito' : 'desuscrito';

        return redirect()
            ->route('suscriptores-listar')
            ->with('success', "Suscriptor {$estado} exitosamente.");
    }
}
