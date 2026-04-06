<div class="modal fade" id="modalEditPet" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-inline-flex align-items-center gap-2"><i class="fa-solid fa-pen-to-square text-primary"></i><span>Editar mascota y dueño</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="editPetForm" data-action-base="{{ url('/consultations/pets') }}">
                @csrf
                @method('PUT')
                <div class="modal-body row g-2">
                    <div class="col-12">
                        <label class="form-label">Selecciona mascota</label>
                        <select class="form-select consultation-select2" id="edit_pet_selector" data-placeholder="Selecciona mascota" required>
                            <option value="">Selecciona mascota</option>
                            @foreach($petsCatalog as $pet)
                                <option value="{{ $pet->id }}">{{ $pet->name }}{{ $pet->owner_name ? ' - '.$pet->owner_name : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Nombre mascota</label>
                        <input class="form-control" id="edit_pet_name" name="name" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Propietario</label>
                        <input class="form-control" id="edit_owner_name" name="owner_name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo del dueño</label>
                        <input type="email" class="form-control" id="edit_owner_email" name="owner_email">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono del dueño</label>
                        <input class="form-control" id="edit_owner_phone" name="owner_phone">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Especie</label>
                        <select class="form-select consultation-select2" id="edit_species_id" name="species_id" data-placeholder="Selecciona especie" required>
                            <option value="">Selecciona especie</option>
                            @foreach($speciesCatalog as $species)
                                <option value="{{ $species->id }}">{{ $species->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tipo / Raza</label>
                        <input class="form-control" id="edit_breed" name="breed" placeholder="Ej: Pastor Aleman">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Talla</label>
                        <select class="form-select" id="edit_size_category" name="size_category">
                            <option value="">No aplica</option>
                            <option value="pequena">Pequena</option>
                            <option value="mediana">Mediana</option>
                            <option value="grande">Grande</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
