
@foreach ($consultations as $consultation)
<div class="modal fade consult-create-modal" id="modalEditConsultation-{{ $consultation->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-pen-to-square text-primary"></i><span>Editar consulta #{{ $consultation->id }}</span></h5>
                    <p class="small text-muted mb-0">Edición completa de consulta, tratamiento y productos.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('consultations-actualizar', $consultation) }}" class="consult-create-shell" id="consultationEditForm-{{ $consultation->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                                    <select class="form-select consultation-select2" name="pet_id" data-placeholder="Selecciona mascota" required>
                                        <option value="">Selecciona mascota</option>
                                        @foreach($petsCatalog as $pet)
                                            <option value="{{ $pet->id }}" @selected($consultation->pet_id === $pet->id)>{{ $pet->name }}{{ $pet->owner_name ? ' - '.$pet->owner_name : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Especie</label>
                                    <select class="form-select consultation-select2" name="species_id" data-placeholder="Selecciona especie" required>
                                        <option value="">Selecciona especie</option>
                                        @foreach($speciesCatalog as $species)
                                            <option value="{{ $species->id }}" @selected($consultation->species_id === $species->id)>{{ $species->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tipo</label>
                                    <input class="form-control" value="{{ $consultation->petCatalog?->breed }}" readonly placeholder="Se autocompleta">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Propietario</label>
                                    <input class="form-control" name="owner_name" value="{{ $consultation->owner_name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Talla</label>
                                    <input class="form-control" value="{{ $consultation->petCatalog?->size_category }}" readonly placeholder="Pequena / Mediana / Grande">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fecha y hora</label>
                                    <input type="datetime-local" class="form-control" name="consulted_at" value="{{ $consultation->consulted_at?->format('Y-m-d\TH:i') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Costo</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="cost" value="{{ $consultation->cost }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Diagnostico</label>
                                    <input class="form-control" name="diagnosis" value="{{ $consultation->diagnosis }}" list="diagnosis-list" required>
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
                                                <input class="form-check-input" type="checkbox" value="1" id="edit_vaccination_applied_{{ $consultation->id }}" name="vaccination_applied" @checked($consultation->vaccination_applied)>
                                                <label class="form-check-label" for="edit_vaccination_applied_{{ $consultation->id }}">Se aplico vacuna en esta consulta</label>
                                            </div>
                                            <input class="form-control" name="vaccination_note" value="{{ $consultation->vaccination_note }}" placeholder="Vacuna aplicada (ej: Sextuple)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Proxima vacuna</label>
                                            <input type="date" class="form-control" name="next_vaccination_at" value="{{ $consultation->next_vaccination_at?->format('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" value="1" id="edit_deworming_applied_{{ $consultation->id }}" name="deworming_applied" @checked($consultation->deworming_applied)>
                                                <label class="form-check-label" for="edit_deworming_applied_{{ $consultation->id }}">Se aplico desparasitacion en esta consulta</label>
                                            </div>
                                            <input class="form-control" name="deworming_note" value="{{ $consultation->deworming_note }}" placeholder="Producto aplicado (ej: Albendazol)">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Proxima desparasitacion</label>
                                            <input type="date" class="form-control" name="next_deworming_at" value="{{ $consultation->next_deworming_at?->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Agregar imagenes de la consulta</label>
                                    <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
                                    <div class="form-text">Las nuevas imagenes se agregan al historial existente.</div>
                                </div>

                                @if($consultation->images->isNotEmpty())
                                    <div class="col-12">
                                        <label class="form-label mb-2">Imagenes guardadas</label>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($consultation->images as $image)
                                                <a href="{{ asset($image->image_path) }}" target="_blank" rel="noopener" class="d-inline-block">
                                                    <img src="{{ asset($image->image_path) }}" alt="Imagen consulta" style="width: 72px; height: 72px; object-fit: cover; border-radius: 8px; border: 1px solid #d9cfe8;">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
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
                            <textarea class="form-control" name="treatment" rows="10">{{ $consultation->treatment }}</textarea>
                        </section>
                    </div>
                    <section class="consult-create-card consult-products-card">
                        <div class="consult-create-head">
                            <div>
                                <span class="consult-create-title"><i class="fa-solid fa-capsules"></i> Productos</span>
                                <p>Medicacion, alimento, accesorios u otros articulos vinculados a la consulta.</p>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-2" id="addMedicationRowBtnEdit-{{ $consultation->id }}"><i class="fa-solid fa-plus"></i><span>Producto</span></button>
                        </div>
                        <label class="form-label mb-2">Medicacion / productos aplicados o vendidos</label>
                        <div id="medicationsContainerEdit-{{ $consultation->id }}" class="d-grid gap-2">
                            @foreach($consultation->consultationItems as $item)
                                <div class="med-row">
                                    <select class="form-select d-inline-block w-auto" name="medications[{{ $loop->index }}][inventory_item_id]" required>
                                        @foreach($inventoryCatalog as $inv)
                                            <option value="{{ $inv->id }}" @selected($item->inventory_item_id === $inv->id)>{{ $inv->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" class="form-control d-inline-block w-auto" name="medications[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}" min="1" required placeholder="Cantidad">
                                    <input type="number" class="form-control d-inline-block w-auto" name="medications[{{ $loop->index }}][unit_price]" value="{{ $item->unit_price }}" min="0" step="0.01" required placeholder="Precio unitario">
                                    <input type="text" class="form-control d-inline-block w-auto" name="medications[{{ $loop->index }}][dosage]" value="{{ $item->dosage }}" placeholder="Dosis">
                                    <input type="number" class="form-control d-inline-block w-auto" name="medications[{{ $loop->index }}][frequency_hours]" value="{{ $item->frequency_hours }}" min="1" placeholder="Cada (hrs)">
                                    <input type="number" class="form-control d-inline-block w-auto" name="medications[{{ $loop->index }}][frequency_days]" value="{{ $item->frequency_days }}" min="1" placeholder="Cada (días)">
                                    <input type="number" class="form-control d-inline-block w-auto" name="medications[{{ $loop->index }}][duration_days]" value="{{ $item->duration_days }}" min="1" placeholder="Duración (días)">
                                    <input type="text" class="form-control d-inline-block w-auto" name="medications[{{ $loop->index }}][administration_notes]" value="{{ $item->administration_notes }}" placeholder="Notas">
                                </div>
                            @endforeach
                        </div>
                        <div class="small text-muted mt-2 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-receipt"></i><span>Se agrega a inventario, venta y receta.</span></div>
                    </section>
                    <div class="consult-create-actions">
                        <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i><span>Cerrar</span></button>
                        <button class="btn btn-primary px-4 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-floppy-disk"></i><span>Actualizar</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
