<div class="modal fade consult-create-modal" id="modalPreventiveControl" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-shield-halved text-warning"></i><span>Nuevo control preventivo</span></h5>
                    <p class="small text-muted mb-0">Registro independiente de vacunacion y desparasitacion. Se guarda como consulta.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('consultations-agregar') }}" class="consult-create-shell" id="preventiveControlForm">
                    @csrf
                    <input type="hidden" name="diagnosis" value="Control preventivo">
                    <input type="hidden" name="treatment" value="Registro de control preventivo.">

                    <div class="consult-create-grid">
                        <section class="consult-create-card consult-create-card-soft">
                            <div class="consult-create-head">
                                <div>
                                    <span class="consult-create-title"><i class="fa-solid fa-shield-halved"></i> Control sanitario</span>
                                    <p>Vacunacion y desparasitacion por paciente.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mascota</label>
                                    <select class="form-select consultation-select2" name="pet_id" id="preventive_pet_id" data-placeholder="Selecciona mascota" required>
                                        <option value="">Selecciona mascota</option>
                                        @foreach($petsCatalog as $pet)
                                            <option value="{{ $pet->id }}">{{ $pet->name }}{{ $pet->owner_name ? ' - '.$pet->owner_name : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Especie</label>
                                    <select class="form-select consultation-select2" name="species_id" id="preventive_species_id" data-placeholder="Selecciona especie" required>
                                        <option value="">Selecciona especie</option>
                                        @foreach($speciesCatalog as $species)
                                            <option value="{{ $species->id }}">{{ $species->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Propietario</label>
                                    <input class="form-control" name="owner_name" id="preventive_owner_name" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Fecha y hora</label>
                                    <input type="datetime-local" class="form-control" name="consulted_at" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Costo (opcional)</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="cost" placeholder="0.00">
                                </div>

                                <div class="col-12">
                                    <hr class="my-1">
                                    <label class="form-label fw-semibold mb-2">Vacunacion y desparasitacion</label>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" value="1" id="preventive_vaccination_applied" name="vaccination_applied">
                                                <label class="form-check-label" for="preventive_vaccination_applied">Se aplico vacuna en este control</label>
                                            </div>
                                            <input class="form-control" name="vaccination_note" placeholder="Vacuna aplicada (ej: Sextuple)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Proxima vacuna</label>
                                            <input type="date" class="form-control" name="next_vaccination_at">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" value="1" id="preventive_deworming_applied" name="deworming_applied">
                                                <label class="form-check-label" for="preventive_deworming_applied">Se aplico desparasitacion en este control</label>
                                            </div>
                                            <input class="form-control" name="deworming_note" placeholder="Producto aplicado (ej: Albendazol)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Proxima desparasitacion</label>
                                            <input type="date" class="form-control" name="next_deworming_at">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="consult-create-actions">
                        <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i><span>Cerrar</span></button>
                        <button class="btn btn-warning px-4 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-floppy-disk"></i><span>Guardar control</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
