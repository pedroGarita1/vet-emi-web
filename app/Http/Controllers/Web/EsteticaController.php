<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\MascotaListaCorreo;
use App\Models\EsteticaService;
use App\Models\EsteticaServiceImage;
use App\Models\Pet;
use App\Models\Species;
use App\Support\WhatsappGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EsteticaController extends Controller
{
    public function index(): View
    {
        $pets = Pet::query()->where('is_active', true)->orderBy('name')->get();
        $species = Species::query()->orderBy('name')->get();

        return view('modules.estetica.index', [
            'services' => EsteticaService::query()->with('images')->latest('requested_at')->get(),
            'petsCatalog' => $pets,
            'speciesCatalog' => $species,
            'speciesJson' => $species->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
            ])->values(),
            'petsJson' => $pets->map(fn ($pet) => [
                'id' => $pet->id,
                'name' => $pet->name,
                'species_id' => $pet->species_id,
                'breed' => $pet->breed,
                'size_category' => $pet->size_category,
                'owner_name' => $pet->owner_name,
                'owner_email' => $pet->owner_email,
                'owner_phone' => $pet->owner_phone,
            ])->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pet_id' => ['nullable', 'integer', 'exists:pets,id'],
            'pet_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'owner_phone' => ['nullable', 'string', 'max:30'],
            'owner_email' => ['nullable', 'email', 'max:255'],
            'service_type' => ['required', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
            'requested_at' => ['required', 'date'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
        ]);

        DB::transaction(function () use ($data, $request): void {
            $service = EsteticaService::query()->create([
                'pet_id' => $data['pet_id'] ?? null,
                'pet_name' => $data['pet_name'],
                'owner_name' => $data['owner_name'] ?? null,
                'owner_phone' => $data['owner_phone'] ?? null,
                'owner_email' => $data['owner_email'] ?? null,
                'service_type' => $data['service_type'],
                'status' => 'pendiente',
                'notes' => $data['notes'] ?? null,
                'requested_at' => $data['requested_at'],
            ]);

            foreach ($request->file('images', []) as $image) {
                $path = $image->store('public/estetica');
                $relativePath = str_replace('public/', 'storage/', $path);

                EsteticaServiceImage::query()->create([
                    'estetica_service_id' => $service->id,
                    'image_path' => $relativePath,
                ]);
            }
        });

        return redirect()->route('estetica-listar')->with('success', 'Servicio de estetica registrado.');
    }

    public function updateStatus(Request $request, EsteticaService $esteticaService): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pendiente,en_proceso,lista,entregada'],
        ]);

        $esteticaService->update([
            'status' => $data['status'],
            'ready_at' => $data['status'] === 'lista' ? now() : $esteticaService->ready_at,
        ]);

        return redirect()->route('estetica-listar')->with('success', 'Estado actualizado.');
    }

    public function notifyOwner(EsteticaService $esteticaService): RedirectResponse
    {
        $message = "Hola, te avisamos que {$esteticaService->pet_name} ya esta lista en Emi Veterinaria.";
        $sentChannels = [];

        $email = trim((string) ($esteticaService->owner_email ?? ''));
        if ($email !== '' && $this->mailCanDeliver()) {
            try {
                Mail::to($email)->send(new MascotaListaCorreo($esteticaService));
                $sentChannels[] = 'correo';
            } catch (\Throwable $e) {
                Log::error('Error enviando correo de estetica.', [
                    'service_id' => $esteticaService->id,
                    'email' => $email,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $phone = trim((string) ($esteticaService->owner_phone ?? ''));

        if (WhatsappGateway::send($phone, $message)) {
            $sentChannels[] = 'whatsapp';
        }

        if (empty($sentChannels)) {
            return redirect()->route('estetica-listar')->with('error', 'No se pudo enviar aviso: revisa configuración de correo (MAIL_MAILER/SMTP) o WhatsApp.');
        }

        $esteticaService->update([
            'status' => 'lista',
            'ready_at' => $esteticaService->ready_at ?: now(),
            'notified_at' => now(),
        ]);

        return redirect()->route('estetica-listar')->with('success', 'Aviso enviado por '.implode(' y ', $sentChannels).'.');
    }

    public function showImage(EsteticaServiceImage $image)
    {
        $path = str_replace('storage/', '', (string) $image->image_path);

        if ($path === '' || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($path));
    }

    private function mailCanDeliver(): bool
    {
        return !in_array((string) config('mail.default'), ['log', 'array'], true);
    }
}
