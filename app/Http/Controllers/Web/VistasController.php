<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationPricingRule;
use App\Models\InventoryItem;
use App\Models\Pet;
use App\Models\Sale;
use App\Models\Species;
use App\Support\WhatsappGateway;

use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class VistasController extends Controller
{
    public function register(): View
    {
        $roles = Role::query()->orderBy('name')->get();
        return view('auth.register', [
            'roles' => $roles,
        ]);
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->isAdmin();

        // Admin ve todos los datos; empleado solo ve sus propios registros del día
        $consultationsQuery = Consultation::query()->latest('consulted_at');
        $salesQuery         = Sale::query()->latest('sold_at');

        if (! $isAdmin) {
            if (Schema::hasColumn('consultations', 'user_id')) {
                $consultationsQuery->where('user_id', $user->id);
            } else {
                $consultationsQuery->whereDate('consulted_at', now()->toDateString());
            }

            if (Schema::hasColumn('sales', 'user_id')) {
                $salesQuery->where('user_id', $user->id);
            } else {
                $salesQuery->whereDate('sold_at', now()->toDateString());
            }
        }

        $consultations = $consultationsQuery->get();
        $sales         = $salesQuery->get();

        return view('dashboard', [
            'user'                 => $user,
            'isAdmin'              => $isAdmin,
            'selectedSede'         => session('selected_sede', 'Matriz'),
            'todayConsultations'   => $consultations->filter(fn ($c) => $c->consulted_at?->isToday())->count(),
            'monthConsultations'   => $consultations->filter(fn ($c) => $c->consulted_at?->isCurrentMonth())->count(),
            'monthRevenue'         => $isAdmin ? (float) $consultations->filter(fn ($c) => $c->consulted_at?->isCurrentMonth())->sum('cost') : null,
            'avgConsultationCost'  => $isAdmin ? (float) ($consultations->count() > 0 ? $consultations->avg('cost') : 0) : null,
            'todaySales'           => $sales->filter(fn ($s) => $s->sold_at?->isToday())->count(),
            'todaySalesRevenue'    => $isAdmin ? (float) $sales->filter(fn ($s) => $s->sold_at?->isToday())->sum('total') : null,
            'monthSalesRevenue'    => $isAdmin ? (float) $sales->filter(fn ($s) => $s->sold_at?->isCurrentMonth())->sum('total') : null,
        ]);
    }

    public function inventory(): View
    {
        return view('modules.inventory.index', [
            'items' => InventoryItem::query()->orderBy('category')->orderBy('name')->get(),
            'speciesCatalog' => Species::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function sales(): View
    {
        $items = InventoryItem::query()->where('is_active', true)->orderBy('category')->orderBy('name')->get();

        $itemsJson = $items->map(function ($item) {
            $speciesIds = collect(explode(',', (string) $item->target_species))
                ->map(fn ($value) => (int) trim($value))
                ->filter(fn ($value) => $value > 0)
                ->values();

            return [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category,
                'presentation' => $item->presentation,
                'sale_unit' => $item->sale_unit,
                'unit_price' => (float) $item->unit_price,
                'target_species_ids' => $speciesIds,
            ];
        })->values();

        return view('modules.sales.index', [
            'sales' => Sale::query()->with('items')->latest('sold_at')->get(),
            'items' => $items,
            'itemsJson' => $itemsJson,
            'speciesCatalog' => Species::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function consultations(Request $request): View
    {
        $species = Species::query()->where('is_active', true)->orderBy('name')->get();
        $pets = Pet::query()->where('is_active', true)->with('species')->orderBy('name')->get();
        $inventoryItems = InventoryItem::query()->where('is_active', true)->orderBy('category')->orderBy('name')->get();
        $pricingRules = ConsultationPricingRule::query()
            ->with('species')
            ->where('is_active', true)
            ->orderBy('diagnosis')
            ->get();

        $selectedPatientPetId = (int) $request->query('patient_pet_id', 0);
        $patientHistory = collect();

        if ($selectedPatientPetId > 0) {
            $patientHistory = Consultation::query()
                ->with(['petCatalog', 'consultationItems.inventoryItem', 'images'])
                ->where('pet_id', $selectedPatientPetId)
                ->latest('consulted_at')
                ->get();
        }

        $today = now()->startOfDay();
        $windowEnd = now()->addDays(30)->endOfDay();

        $latestByPet = Consultation::query()
            ->with('petCatalog')
            ->whereNotNull('pet_id')
            ->where(function ($query) {
                $query->whereNotNull('next_vaccination_at')
                    ->orWhereNotNull('next_deworming_at');
            })
            ->latest('consulted_at')
            ->get()
            ->groupBy('pet_id')
            ->map(fn ($rows) => $rows->first());

        $upcomingCareAlerts = collect();

        foreach ($latestByPet as $consultation) {
            if ($consultation->next_vaccination_at) {
                $dueDate = $consultation->next_vaccination_at->copy()->startOfDay();
                if ($dueDate->lessThanOrEqualTo($windowEnd)) {
                    $upcomingCareAlerts->push([
                        'type' => 'Vacunacion',
                        'pet_name' => $consultation->pet_name,
                        'owner_name' => $consultation->owner_name,
                        'due_date' => $consultation->next_vaccination_at,
                        'is_overdue' => $dueDate->lt($today),
                    ]);
                }
            }

            if ($consultation->next_deworming_at) {
                $dueDate = $consultation->next_deworming_at->copy()->startOfDay();
                if ($dueDate->lessThanOrEqualTo($windowEnd)) {
                    $upcomingCareAlerts->push([
                        'type' => 'Desparasitacion',
                        'pet_name' => $consultation->pet_name,
                        'owner_name' => $consultation->owner_name,
                        'due_date' => $consultation->next_deworming_at,
                        'is_overdue' => $dueDate->lt($today),
                    ]);
                }
            }
        }

        $upcomingCareAlerts = $upcomingCareAlerts
            ->sortBy('due_date')
            ->values();

        $pricingMap = $pricingRules
            ->groupBy('species_id')
            ->map(function ($rules) {
                return $rules->mapWithKeys(function ($rule) {
                    return [mb_strtolower(trim($rule->diagnosis)) => (float) $rule->default_cost];
                });
            });

        $petsJson = $pets->map(function ($pet) {
            return [
                'id' => $pet->id,
                'name' => $pet->name,
                'owner_name' => $pet->owner_name,
                'owner_email' => $pet->owner_email,
                'owner_phone' => $pet->owner_phone,
                'breed' => $pet->breed,
                'size_category' => $pet->size_category,
                'species_id' => $pet->species_id,
            ];
        })->values();

        $speciesJson = $species->map(function ($speciesItem) {
            return [
                'id' => $speciesItem->id,
                'name' => $speciesItem->name,
            ];
        })->values();

        $inventoryJson = $inventoryItems->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category,
                'unit_price' => (float) $item->unit_price,
            ];
        })->values();

        $consultations = Consultation::query()
            ->with(['petCatalog', 'consultationItems.inventoryItem', 'images'])
            ->latest('consulted_at')
            ->get();

        $palette = [
            '#3158d3', '#0f8f76', '#b55a1f', '#8d3ec9', '#0092c7', '#c2427f', '#5f8c1a', '#d8572a',
            '#2f728f', '#8a4d32', '#4f46b5', '#2d9b5f', '#c0671a', '#7a3bb4', '#1c8bb0', '#b83f5f',
        ];

        $patientColorMap = [];
        $petIds = $consultations->pluck('pet_id')->filter()->unique()->values();

        foreach ($petIds as $index => $petId) {
            $patientColorMap[(int) $petId] = $palette[$index % count($palette)];
        }

        $dewormingCalendarEvents = collect();

        foreach ($consultations as $consultation) {
            if (! $consultation->pet_id) {
                continue;
            }

            $color = $patientColorMap[(int) $consultation->pet_id] ?? '#3158d3';

            if ($consultation->vaccination_applied && $consultation->consulted_at) {
                $dewormingCalendarEvents->push([
                    'id' => 'vx-done-'.$consultation->id,
                    'title' => $consultation->pet_name.' - Vacunacion realizada',
                    'start' => $consultation->consulted_at->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'consultation_id' => $consultation->id,
                        'pet_name' => $consultation->pet_name,
                        'owner_name' => $consultation->owner_name,
                        'status_label' => 'Vacunacion realizada',
                        'reminder_type' => 'vacunacion',
                        'care_note' => $consultation->vaccination_note,
                        'next_care_at' => $consultation->next_vaccination_at?->toDateString(),
                    ],
                ]);
            }

            if ($consultation->next_vaccination_at) {
                $dewormingCalendarEvents->push([
                    'id' => 'vx-next-'.$consultation->id,
                    'title' => $consultation->pet_name.' - Proxima vacuna',
                    'start' => $consultation->next_vaccination_at->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'consultation_id' => $consultation->id,
                        'pet_name' => $consultation->pet_name,
                        'owner_name' => $consultation->owner_name,
                        'status_label' => 'Proxima vacunacion',
                        'reminder_type' => 'vacunacion',
                        'care_note' => $consultation->vaccination_note,
                        'next_care_at' => $consultation->next_vaccination_at->toDateString(),
                    ],
                ]);
            }

            if ($consultation->deworming_applied && $consultation->consulted_at) {
                $dewormingCalendarEvents->push([
                    'id' => 'dw-done-'.$consultation->id,
                    'title' => $consultation->pet_name.' - Desparasitacion realizada',
                    'start' => $consultation->consulted_at->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'consultation_id' => $consultation->id,
                        'pet_name' => $consultation->pet_name,
                        'owner_name' => $consultation->owner_name,
                        'status_label' => 'Desparasitacion realizada',
                        'reminder_type' => 'desparasitacion',
                        'care_note' => $consultation->deworming_note,
                        'next_care_at' => $consultation->next_deworming_at?->toDateString(),
                    ],
                ]);
            }

            if ($consultation->next_deworming_at) {
                $dewormingCalendarEvents->push([
                    'id' => 'dw-next-'.$consultation->id,
                    'title' => $consultation->pet_name.' - Proxima desparasitacion',
                    'start' => $consultation->next_deworming_at->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'consultation_id' => $consultation->id,
                        'pet_name' => $consultation->pet_name,
                        'owner_name' => $consultation->owner_name,
                        'status_label' => 'Proxima desparasitacion',
                        'reminder_type' => 'desparasitacion',
                        'care_note' => $consultation->deworming_note,
                        'next_care_at' => $consultation->next_deworming_at->toDateString(),
                    ],
                ]);
            }
        }

        $dewormingCalendarLegend = $consultations
            ->filter(fn ($consultation) => ! empty($consultation->pet_id))
            ->unique('pet_id')
            ->map(function ($consultation) use ($patientColorMap) {
                return [
                    'pet_name' => $consultation->pet_name,
                    'owner_name' => $consultation->owner_name,
                    'color' => $patientColorMap[(int) $consultation->pet_id] ?? '#3158d3',
                ];
            })
            ->values();

        return view('modules.consultations.index', [
            'consultations' => $consultations,
            'speciesCatalog' => $species,
            'petsCatalog' => $pets,
            'inventoryCatalog' => $inventoryItems,
            'pricingRules' => $pricingRules,
            'diagnosisCatalog' => $pricingRules->pluck('diagnosis')->unique()->values(),
            'pricingMap' => $pricingMap->toArray(),
            'petsJson' => $petsJson,
            'speciesJson' => $speciesJson,
            'inventoryJson' => $inventoryJson,
            'selectedPatientPetId' => $selectedPatientPetId,
            'patientHistory' => $patientHistory,
            'upcomingCareAlerts' => $upcomingCareAlerts,
            'dewormingCalendarEvents' => $dewormingCalendarEvents,
            'dewormingCalendarLegend' => $dewormingCalendarLegend,
        ]);
    }

    public function reminderNotifications(): View
    {
        $targetDate = now()->addDays(2)->toDateString();

        $vaccinationReminders = Consultation::query()
            ->with('petCatalog')
            ->whereDate('next_vaccination_at', $targetDate)
            ->latest('next_vaccination_at')
            ->get()
            ->map(function ($consultation) {
                return [
                    'type' => 'Vacunacion',
                    'reminder_key' => 'vacunacion',
                    'icon' => 'fa-syringe',
                    'pet_id' => $consultation->pet_id,
                    'pet_name' => $consultation->pet_name,
                    'owner_name' => $consultation->owner_name,
                    'owner_email' => $consultation->petCatalog?->owner_email,
                    'owner_phone' => $consultation->petCatalog?->owner_phone,
                    'due_date' => $consultation->next_vaccination_at,
                    'consultation_id' => $consultation->id,
                    'message' => 'Faltan 2 dias para la proxima vacunacion.',
                ];
            });

        $dewormingReminders = Consultation::query()
            ->with('petCatalog')
            ->whereDate('next_deworming_at', $targetDate)
            ->latest('next_deworming_at')
            ->get()
            ->map(function ($consultation) {
                return [
                    'type' => 'Desparasitacion',
                    'reminder_key' => 'desparasitacion',
                    'icon' => 'fa-shield-halved',
                    'pet_id' => $consultation->pet_id,
                    'pet_name' => $consultation->pet_name,
                    'owner_name' => $consultation->owner_name,
                    'owner_email' => $consultation->petCatalog?->owner_email,
                    'owner_phone' => $consultation->petCatalog?->owner_phone,
                    'due_date' => $consultation->next_deworming_at,
                    'consultation_id' => $consultation->id,
                    'message' => 'Faltan 2 dias para la proxima desparasitacion.',
                ];
            });

        $feedItems = $vaccinationReminders
            ->concat($dewormingReminders)
            ->sortBy('due_date')
            ->values();

        return view('modules.reminders.index', [
            'feedItems' => $feedItems,
        ]);
    }

    public function sendReminderToOwner(Consultation $consultation, string $type): RedirectResponse
    {
        $type = mb_strtolower(trim($type));

        if (! in_array($type, ['vacunacion', 'desparasitacion'], true)) {
            return back()->with('error', 'Tipo de recordatorio inválido.');
        }

        $pet = $consultation->petCatalog;
        if (! $pet) {
            return back()->with('error', 'No se encontró la mascota asociada a la consulta.');
        }

        $dueDate = $type === 'vacunacion'
            ? $consultation->next_vaccination_at
            : $consultation->next_deworming_at;

        if (! $dueDate) {
            return back()->with('error', 'No hay fecha programada para enviar el aviso.');
        }

        $label = $type === 'vacunacion' ? 'vacunación' : 'desparasitación';
        $dueDateText = $dueDate->format('d/m/Y');

        $message = "Hola, te recordamos que a {$consultation->pet_name} le corresponde {$label} el {$dueDateText}.\n\nEmi Veterinaria";

        $sentChannels = [];

        $email = trim((string) ($pet->owner_email ?? ''));
        if ($email !== '' && $this->mailCanDeliver()) {
            try {
                Mail::raw($message, function ($mail) use ($email, $label): void {
                    $mail->to($email)->subject('Recordatorio de '.$label.' - Emi Veterinaria');
                });
                $sentChannels[] = 'correo';
            } catch (\Throwable) {
                // Si falla SMTP, se intenta aún por WhatsApp.
            }
        }

        $phone = trim((string) ($pet->owner_phone ?? ''));

        if (WhatsappGateway::send($phone, $message)) {
            $sentChannels[] = 'whatsapp';
        }

        if (empty($sentChannels)) {
            return back()->with('error', 'No se pudo enviar: revisa configuración de correo (MAIL_MAILER/SMTP) o WhatsApp.');
        }

        return back()->with('success', 'Aviso enviado por '.implode(' y ', $sentChannels).'.');
    }

    private function mailCanDeliver(): bool
    {
        return !in_array((string) config('mail.default'), ['log', 'array'], true);
    }
}
