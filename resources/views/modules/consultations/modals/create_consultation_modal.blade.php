<div class="modal fade consult-create-modal" id="modalCreateConsultation" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-stethoscope text-success"></i><span>Nueva consulta</span></h5>
                    <p class="small text-muted mb-0">Captura completa de consulta, tratamiento y productos.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('consultations-agregar') }}" class="consult-create-shell" id="consultationForm" enctype="multipart/form-data">
                    @csrf
                    <div class="consult-create-grid">
                        <section class="consult-create-card consult-create-card-soft">
                            <div class="consult-create-head">
                                <div>
                                    <span class="consult-create-title"><i class="fa-solid fa-circle-info"></i> Datos base</span>
                                    <p>Mascota, responsable, fecha, diagnostico y costo.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mascota</label>
                                    <select class="form-select consultation-select2" name="pet_id" id="new_pet_id" data-placeholder="Selecciona mascota" required>
                                        <option value="">Selecciona mascota</option>
                                        @foreach($petsCatalog as $pet)
                                            <option value="{{ $pet->id }}">{{ $pet->name }}{{ $pet->owner_name ? ' - '.$pet->owner_name : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Especie</label>
                                    <select class="form-select consultation-select2" name="species_id" id="new_species_id" data-placeholder="Selecciona especie" required>
                                        <option value="">Selecciona especie</option>
                                        @foreach($speciesCatalog as $species)
                                            <option value="{{ $species->id }}">{{ $species->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tipo</label>
                                    <input class="form-control" id="new_pet_breed" placeholder="Se autocompleta o captura manualmente">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Propietario</label>
                                    <input class="form-control" name="owner_name" id="new_owner_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Talla</label>
                                    <input class="form-control" id="new_pet_size" placeholder="Pequena / Mediana / Grande">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fecha y hora</label>
                                    <input type="datetime-local" class="form-control" name="consulted_at" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Costo</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="cost" id="new_cost" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Diagnostico</label>
                                    <input class="form-control" name="diagnosis" id="new_diagnosis" list="diagnosis-list" required>
                                    <datalist id="diagnosis-list">
                                        @foreach($diagnosisCatalog as $diagnosis)
                                            <option value="{{ $diagnosis }}"></option>
                                        @endforeach
                                    </datalist>
                                </div>

                                <div class="col-12">
                                    <hr class="my-1">
                                    <label class="form-label fw-semibold mb-2">Control preventivo</label>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" value="1" id="new_vaccination_applied" name="vaccination_applied">
                                                <label class="form-check-label" for="new_vaccination_applied">Se aplico vacuna en esta consulta</label>
                                            </div>
                                            <input class="form-control" name="vaccination_note" placeholder="Vacuna aplicada (ej: Sextuple)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Proxima vacuna</label>
                                            <input type="date" class="form-control" name="next_vaccination_at">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" value="1" id="new_deworming_applied" name="deworming_applied">
                                                <label class="form-check-label" for="new_deworming_applied">Se aplico desparasitacion en esta consulta</label>
                                            </div>
                                            <input class="form-control" name="deworming_note" placeholder="Producto aplicado (ej: Albendazol)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Proxima desparasitacion</label>
                                            <input type="date" class="form-control" name="next_deworming_at">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Imagenes de la consulta</label>
                                    <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
                                    <div class="form-text">Puedes seleccionar varias imagenes (maximo 5MB por archivo).</div>
                                </div>
                            </div>
                        </section>

                        <section class="consult-create-card consult-treatment-card">
                            <div class="consult-create-head">
                                <div>
                                    <span class="consult-create-title"><i class="fa-solid fa-notes-medical"></i> Tratamiento</span>
                                    <p>Espacio clinico exclusivo para indicaciones, evolucion y observaciones.</p>
                                </div>
                            </div>

                            <label class="form-label">Detalle clinico</label>
                            <textarea class="form-control" name="treatment" id="treatmentEditor" rows="10"></textarea>
                        </section>
                    </div>

                    <section class="consult-create-card consult-products-card">
                        <div class="consult-create-head">
                            <div>
                                <span class="consult-create-title"><i class="fa-solid fa-capsules"></i> Productos</span>
                                <p>Medicacion, alimento, accesorios u otros articulos vinculados a la consulta.</p>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-2" id="addMedicationRowBtn"><i class="fa-solid fa-plus"></i><span>Producto</span></button>
                        </div>

                        <label class="form-label mb-2">Medicacion / productos aplicados o vendidos</label>
                        <div id="medicationsContainer" class="d-grid gap-2"></div>
                        <div class="small text-muted mt-2 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-receipt"></i><span>Se agrega a inventario, venta y receta.</span></div>
                    </section>

                    <div class="consult-create-actions">
                        <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i><span>Cerrar</span></button>
                        <button class="btn btn-success px-4 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-floppy-disk"></i><span>Guardar</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>