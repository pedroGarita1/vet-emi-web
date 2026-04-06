<div class="modal fade consult-create-modal" id="modalCreateEsteticaService" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-scissors text-primary"></i><span>Nuevo servicio de estetica</span></h5>
                    <p class="small text-muted mb-0">Registro de mascota, contacto del dueño y detalle del servicio.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('estetica-agregar') }}" class="consult-create-shell" id="esteticaForm" enctype="multipart/form-data">
                    @csrf

                    <section class="consult-create-card consult-create-card-soft">
                        <div class="consult-create-head">
                            <div>
                                <span class="consult-create-title"><i class="fa-solid fa-paw"></i> Datos del servicio</span>
                                <p>Completa la mascota, responsable y tipo de atencion.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Mascota (catalogo)</label>
                                <select class="form-select consultation-select2" id="est_pet_id" name="pet_id" data-placeholder="Selecciona mascota">
                                    <option value="">Selecciona mascota</option>
                                    @foreach($petsCatalog as $pet)
                                        <option value="{{ $pet->id }}">{{ $pet->name }}{{ $pet->owner_name ? ' - '.$pet->owner_name : '' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nombre mascota</label>
                                <input class="form-control" name="pet_name" id="est_pet_name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Dueño</label>
                                <input class="form-control" name="owner_name" id="est_owner_name">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" name="owner_email" id="est_owner_email">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Telefono</label>
                                <input class="form-control" name="owner_phone" id="est_owner_phone">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Servicio</label>
                                <input class="form-control" name="service_type" placeholder="Bano, corte, limpieza" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fecha y hora</label>
                                <input type="datetime-local" class="form-control" name="requested_at" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notas</label>
                                <textarea class="form-control" name="notes" rows="4"></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Imagenes del servicio</label>
                                <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
                                <div class="form-text">Adjunta fotos del proceso o resultado (maximo 5MB por archivo).</div>
                            </div>
                        </div>
                    </section>

                    <div class="consult-create-actions">
                        <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i><span>Cerrar</span></button>
                        <button class="btn btn-emi-purple px-4 d-inline-flex align-items-center gap-2"><i class="fa-solid fa-floppy-disk"></i><span>Guardar servicio</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
