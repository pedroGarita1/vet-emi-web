<?php

namespace App\Console\Commands;

use App\Models\Consultation;
use App\Support\WhatsappGateway;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendPreventiveCareReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-preventive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios preventivos de vacunación y desparasitación 2 días antes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetDate = now()->addDays(2)->toDateString();

        $vaccinationCount = $this->dispatchForType(
            dateColumn: 'next_vaccination_at',
            reminderType: 'vacunacion',
            label: 'vacunación',
            targetDate: $targetDate,
        );

        $dewormingCount = $this->dispatchForType(
            dateColumn: 'next_deworming_at',
            reminderType: 'desparasitacion',
            label: 'desparasitación',
            targetDate: $targetDate,
        );

        $this->info("Recordatorios enviados. Vacunación: {$vaccinationCount}. Desparasitación: {$dewormingCount}.");

        return self::SUCCESS;
    }

    private function dispatchForType(string $dateColumn, string $reminderType, string $label, string $targetDate): int
    {
        $count = 0;

        $consultations = Consultation::query()
            ->with('petCatalog')
            ->whereDate($dateColumn, $targetDate)
            ->get();

        foreach ($consultations as $consultation) {
            $pet = $consultation->petCatalog;

            if (! $pet) {
                continue;
            }

            $email = trim((string) ($pet->owner_email ?? ''));
            $phone = trim((string) ($pet->owner_phone ?? ''));
            $dueDate = Carbon::parse($consultation->{$dateColumn})->toDateString();

            if ($email !== '' && ! $this->alreadySent($consultation->id, $reminderType, $dueDate, 'email')) {
                $subject = 'Recordatorio de '.$label.' - Emi Veterinaria';
                $message = $this->buildMessage($consultation->pet_name, $label, $dueDate);

                Mail::raw($message, function ($mail) use ($email, $subject): void {
                    $mail->to($email)->subject($subject);
                });

                $this->registerLog($consultation->id, $reminderType, $dueDate, 'email');
                $count++;
            }

            if ($phone !== '' && ! $this->alreadySent($consultation->id, $reminderType, $dueDate, 'whatsapp')) {
                $this->sendWhatsapp($phone, $this->buildMessage($consultation->pet_name, $label, $dueDate));
                $this->registerLog($consultation->id, $reminderType, $dueDate, 'whatsapp');
                $count++;
            }
        }

        return $count;
    }

    private function buildMessage(string $petName, string $label, string $dueDate): string
    {
        $formattedDate = Carbon::parse($dueDate)->format('d/m/Y');

        return "Hola, te recordamos que a {$petName} le corresponde {$label} el {$formattedDate}.\n\n"
            . "Emi Veterinaria";
    }

    private function sendWhatsapp(string $phone, string $message): void
    {
        if (!WhatsappGateway::send($phone, $message)) {
            $this->warn('WhatsApp no configurado o falló el envío. Revisa variables de entorno y credenciales.');
            return;
        }
    }

    private function alreadySent(int $consultationId, string $reminderType, string $dueDate, string $channel): bool
    {
        return DB::table('preventive_reminder_logs')
            ->where('consultation_id', $consultationId)
            ->where('reminder_type', $reminderType)
            ->where('due_date', $dueDate)
            ->where('channel', $channel)
            ->exists();
    }

    private function registerLog(int $consultationId, string $reminderType, string $dueDate, string $channel): void
    {
        DB::table('preventive_reminder_logs')->insert([
            'consultation_id' => $consultationId,
            'reminder_type' => $reminderType,
            'due_date' => $dueDate,
            'channel' => $channel,
            'sent_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
