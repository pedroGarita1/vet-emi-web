<div class="modal fade" id="modalAddPet" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-inline-flex align-items-center gap-2"><i class="fa-solid fa-paw text-success"></i><span>Mascota</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('consultations-mascota-agregar') }}">
                @csrf
                <div class="modal-body row g-2">
                    <div class="col-12">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-signature text-muted"></i><span>Nombre</span></label>
                        <input class="form-control" name="name" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-user text-muted"></i><span>Propietario</span></label>
                        <input class="form-control" name="owner_name">
                    </div>
                    <div class="col-12">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-dna text-muted"></i><span>Especie</span></label>
                        <select class="form-select" id="pet_species_id_modal" name="species_id" required>
                            <option value="">Selecciona especie</option>
                            @foreach($speciesCatalog as $species)
                                <option value="{{ $species->id }}">{{ $species->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12" id="pet_breed_group_modal">
                        <label class="form-label d-inline-flex align-items-center gap-2" id="pet_breed_label_modal"><i class="fa-solid fa-shield-dog text-muted"></i><span id="pet_breed_label_text_modal">Tipo / Raza</span></label>
                        <input class="form-control" id="pet_breed_modal" name="breed" placeholder="Ej: Pastor Aleman">
                        <div class="form-text">Requerido en perro o ave.</div>
                    </div>
                    <div class="col-12" id="pet_size_group_modal">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-ruler-combined text-muted"></i><span>Talla</span></label>
                        <select class="form-select" id="pet_size_modal" name="size_category">
                            <option value="">No aplica</option>
                            <option value="pequena">Pequena</option>
                            <option value="mediana">Mediana</option>
                            <option value="grande">Grande</option>
                        </select>
                        <div class="form-text">Usar en perro o gato para ajustar dosis y manejo.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i><span>Cerrar</span></button>
                    <button class="btn btn-success d-inline-flex align-items-center gap-2"><i class="fa-solid fa-floppy-disk"></i><span>Guardar</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
