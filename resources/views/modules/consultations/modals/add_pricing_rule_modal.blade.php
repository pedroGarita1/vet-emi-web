<div class="modal fade" id="modalAddPricingRule" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-inline-flex align-items-center gap-2"><i class="fa-solid fa-tags text-success"></i><span>Tarifa</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('consultations-tarifa-agregar') }}">
                @csrf
                <div class="modal-body row g-2">
                    <div class="col-12">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-dna text-muted"></i><span>Especie</span></label>
                        <select class="form-select" name="species_id" required>
                            <option value="">Selecciona especie</option>
                            @foreach($speciesCatalog as $species)
                                <option value="{{ $species->id }}">{{ $species->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-7">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-notes-medical text-muted"></i><span>Diagnostico</span></label>
                        <input class="form-control" name="diagnosis" required>
                    </div>
                    <div class="col-5">
                        <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-dollar-sign text-muted"></i><span>Costo</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" name="default_cost" required>
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
