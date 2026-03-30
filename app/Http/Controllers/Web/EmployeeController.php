<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function buscarCP(string $cp): JsonResponse
    {
        $response = Http::get("https://api.zippopotam.us/MX/{$cp}");

        if (! $response->successful()) {
            return response()->json([
                'zip_codes' => [],
            ]);
        }

        $data = $response->json();
        $resultado = [];

        foreach (($data['places'] ?? []) as $place) {
            $resultado[] = [
                'd_estado' => $place['state'] ?? '',
                'd_mnpio' => $place['place name'] ?? '',
                'd_ciudad' => $place['place name'] ?? '',
                'd_asenta' => $place['place name'] ?? '',
            ];
        }

        return response()->json([
            'zip_codes' => $resultado,
        ]);
    }

    public function index(): View
    {
        $employees = Employee::query()
            ->with('user.role')
            ->orderByDesc('created_at')
            ->get();

        return view('modules.employees.index', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8'],
            'birthdate'   => ['nullable', 'date'],
            'sex'         => ['nullable', 'in:M,F,otro'],
            'address'     => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:5'],
            'colonia'     => ['nullable', 'string', 'max:255'],
            'municipio'   => ['nullable', 'string', 'max:255'],
            'estado'      => ['nullable', 'string', 'max:255'],
            'ine_file'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'curp_file'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'acta_file'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
        ]);

        $employeeRole = Role::where('name', 'employee')->firstOrFail();

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'  => $employeeRole->id,
        ]);

        $inePath  = $request->hasFile('ine_file')  ? $request->file('ine_file')->store('employees/ine', 'local')  : null;
        $curpPath = $request->hasFile('curp_file') ? $request->file('curp_file')->store('employees/curp', 'local') : null;
        $actaPath = $request->hasFile('acta_file') ? $request->file('acta_file')->store('employees/acta', 'local') : null;

        Employee::create([
            'user_id'     => $user->id,
            'birthdate'   => $data['birthdate'] ?? null,
            'sex'         => $data['sex'] ?? null,
            'ine_path'    => $inePath,
            'curp_path'   => $curpPath,
            'acta_path'   => $actaPath,
            'address'     => $data['address'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'colonia'     => $data['colonia'] ?? null,
            'municipio'   => $data['municipio'] ?? null,
            'estado'      => $data['estado'] ?? null,
        ]);

        return redirect()->route('employees-listar')->with('success', 'Empleado registrado correctamente.');
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', "unique:users,email,{$employee->user_id}"],
            'password'    => ['nullable', 'string', 'min:8'],
            'birthdate'   => ['nullable', 'date'],
            'sex'         => ['nullable', 'in:M,F,otro'],
            'address'     => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:5'],
            'colonia'     => ['nullable', 'string', 'max:255'],
            'municipio'   => ['nullable', 'string', 'max:255'],
            'estado'      => ['nullable', 'string', 'max:255'],
            'ine_file'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'curp_file'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'acta_file'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
        ]);

        $userUpdate = ['name' => $data['name'], 'email' => $data['email']];
        if (! empty($data['password'])) {
            $userUpdate['password'] = Hash::make($data['password']);
        }
        $employee->user->update($userUpdate);

        $employeeUpdate = [
            'birthdate'   => $data['birthdate'] ?? null,
            'sex'         => $data['sex'] ?? null,
            'address'     => $data['address'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'colonia'     => $data['colonia'] ?? null,
            'municipio'   => $data['municipio'] ?? null,
            'estado'      => $data['estado'] ?? null,
        ];

        if ($request->hasFile('ine_file')) {
            if ($employee->ine_path) Storage::disk('local')->delete($employee->ine_path);
            $employeeUpdate['ine_path'] = $request->file('ine_file')->store('employees/ine', 'local');
        }
        if ($request->hasFile('curp_file')) {
            if ($employee->curp_path) Storage::disk('local')->delete($employee->curp_path);
            $employeeUpdate['curp_path'] = $request->file('curp_file')->store('employees/curp', 'local');
        }
        if ($request->hasFile('acta_file')) {
            if ($employee->acta_path) Storage::disk('local')->delete($employee->acta_path);
            $employeeUpdate['acta_path'] = $request->file('acta_file')->store('employees/acta', 'local');
        }

        $employee->update($employeeUpdate);

        return redirect()->route('employees-listar')->with('success', 'Empleado actualizado correctamente.');
    }

    public function updateDocuments(Request $request, Employee $employee): RedirectResponse
    {
        $request->validate([
            'ine_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'curp_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
            'acta_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
        ]);

        $updated = false;

        if ($request->hasFile('ine_file')) {
            if ($employee->ine_path) {
                Storage::disk('local')->delete($employee->ine_path);
            }
            $employee->ine_path = $request->file('ine_file')->store('employees/ine', 'local');
            $updated = true;
        }

        if ($request->hasFile('curp_file')) {
            if ($employee->curp_path) {
                Storage::disk('local')->delete($employee->curp_path);
            }
            $employee->curp_path = $request->file('curp_file')->store('employees/curp', 'local');
            $updated = true;
        }

        if ($request->hasFile('acta_file')) {
            if ($employee->acta_path) {
                Storage::disk('local')->delete($employee->acta_path);
            }
            $employee->acta_path = $request->file('acta_file')->store('employees/acta', 'local');
            $updated = true;
        }

        if ($updated) {
            $employee->save();
            return redirect()->route('employees-listar')->with('success', 'Documentos actualizados correctamente.');
        }

        return redirect()->route('employees-listar')->with('success', 'No se adjuntaron documentos nuevos.');
    }

    public function viewDocument(Employee $employee, string $type)
    {
        $fieldMap = [
            'ine' => 'ine_path',
            'curp' => 'curp_path',
            'acta' => 'acta_path',
        ];

        if (! array_key_exists($type, $fieldMap)) {
            abort(404);
        }

        $path = $employee->{$fieldMap[$type]};
        if (! $path || ! Storage::disk('local')->exists($path)) {
            abort(404, 'Documento no disponible.');
        }

        return response()->file(Storage::disk('local')->path($path));
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        // Eliminar archivos del disco
        foreach (['ine_path', 'curp_path', 'acta_path'] as $field) {
            if ($employee->$field) {
                Storage::disk('local')->delete($employee->$field);
            }
        }

        $user = $employee->user;
        $employee->delete();
        $user->delete();

        return redirect()->route('employees-listar')->with('success', 'Empleado eliminado correctamente.');
    }
}
