@extends('layouts.app')

@section('title', 'Notificaciones | Emi Veterinaria')

@section('content')
<style>
    .reminder-shell {
        display: grid;
        gap: 1rem;
    }

    .reminder-hero {
        background: linear-gradient(135deg, #2d3658 0%, #1f2946 100%);
        border-radius: 16px;
        padding: 1rem 1.1rem;
        color: #fff;
    }

    .feed-wrap {
        display: grid;
        gap: 0.75rem;
    }

    .feed-card {
        background: #fff;
        border: 1px solid #dce1eb;
        border-radius: 12px;
        padding: 0.9rem;
        box-shadow: 0 4px 12px rgba(33, 38, 58, 0.08);
    }

    .feed-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #e6efff 0%, #d4e2ff 100%);
        color: #1847a8;
        font-weight: 800;
    }

    .feed-meta {
        font-size: 0.85rem;
        color: #616782;
    }

    .feed-headline {
        font-size: 0.95rem;
    }

    .feed-actions {
        border-top: 1px solid #edf0f5;
        margin-top: 0.7rem;
        padding-top: 0.55rem;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .feed-action-btn {
        border: 0;
        background: #f1f3f7;
        color: #334168;
        border-radius: 8px;
        padding: 0.32rem 0.62rem;
        font-size: 0.8rem;
        font-weight: 700;
    }
</style>

<div class="container-fluid py-2 py-md-3 reminder-shell">
    <section class="reminder-hero">
        <h1 class="h4 mb-1">Notificaciones preventivas</h1>
        <p class="mb-0 opacity-75">Avisos internos estilo feed para pacientes con control en 2 dias.</p>
    </section>

    <section class="feed-wrap">
        @forelse($feedItems as $item)
            <article class="feed-card">
                <div class="d-flex gap-3 align-items-start">
                    <div class="feed-avatar">{{ strtoupper(substr($item['pet_name'], 0, 1)) }}</div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                            <div>
                                <div class="fw-bold d-inline-flex align-items-center gap-2">
                                    <i class="fa-solid {{ $item['icon'] }} text-primary"></i>
                                    <span class="feed-headline">{{ $item['pet_name'] }}</span>
                                </div>
                                <div class="feed-meta">Dueño: {{ $item['owner_name'] ?: 'No registrado' }} · hace un momento</div>
                            </div>
                            <span class="badge text-bg-light">{{ $item['type'] }} - {{ $item['due_date']?->format('d/m/Y') }}</span>
                        </div>

                        <p class="mb-2 mt-2">{{ $item['message'] }}</p>
                        <div class="feed-meta">
                            Correo: {{ $item['owner_email'] ?: 'No registrado' }}
                            <span class="mx-2">|</span>
                            WhatsApp: {{ $item['owner_phone'] ?: 'No registrado' }}
                        </div>
                        <div class="feed-actions">
                            <a href="{{ route('consultations-listar', ['patient_pet_id' => $item['pet_id'], 'open_consultation_id' => $item['consultation_id'], 'tab' => 'tabla']) }}" class="feed-action-btn text-decoration-none"><i class="fa-solid fa-stethoscope me-1"></i> Ver consulta</a>
                            <form method="POST" action="{{ route('reminders-notify-owner', ['consultation' => $item['consultation_id'], 'type' => $item['reminder_key']]) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="feed-action-btn"><i class="fa-regular fa-paper-plane me-1"></i> Enviar aviso al dueño</button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <article class="feed-card">
                <div class="text-muted">No hay notificaciones preventivas para los próximos 2 días.</div>
            </article>
        @endforelse
    </section>
</div>
@endsection
