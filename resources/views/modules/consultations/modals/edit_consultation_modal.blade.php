@foreach ($consultations as $consultation)
<div class="modal fade" id="modalEditConsultation-{{ $consultation->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-inline-flex align-items-center gap-2"><i class="fa-solid fa-pen-to-square text-primary"></i><span>Consulta #{{ $consultation->id }}</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('consultations-actualizar', $consultation) }}">
                @csrf
                @method('PUT')
                <div class="modal-body row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Mascota</label>
                        <select class="form-select" name="pet_id" required>
                            @foreach($petsCatalog as $pet)
                                <option value="{{ $pet->id }}" @selected($consultation->pet_id === $pet->id)>{{ $pet->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Especie</label>
                        <select class="form-select" name="species_id" required>
                            @foreach($speciesCatalog as $species)
                                <option value="{{ $species->id }}" @selected($consultation->species_id === $species->id)>{{ $species->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Propietario</label>
                        <input class="form-control" name="owner_name" value="{{ $consultation->owner_name }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Diagnostico</label>
                        <input class="form-control" name="diagnosis" value="{{ $consultation->diagnosis }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Costo</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="cost" value="{{ $consultation->cost }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha y hora</label>
                        <input type="datetime-local" class="form-control" name="consulted_at" value="{{ $consultation->consulted_at?->format('Y-m-d\\TH:i') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tratamiento</label>
                        <textarea class="form-control" name="treatment" rows="4">{{ $consultation->treatment }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i><span>Cerrar</span></button>
                    <button class="btn btn-primary d-inline-flex align-items-center gap-2"><i class="fa-solid fa-floppy-disk"></i><span>Actualizar</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
