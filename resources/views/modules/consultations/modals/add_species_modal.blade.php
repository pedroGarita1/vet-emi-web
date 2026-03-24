<div class="modal fade" id="modalAddSpecies" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-inline-flex align-items-center gap-2"><i class="fa-solid fa-dna text-success"></i><span>Especie</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('consultations-especie-agregar') }}">
                @csrf
                <div class="modal-body">
                    <label class="form-label d-inline-flex align-items-center gap-2"><i class="fa-solid fa-tag text-muted"></i><span>Nombre</span></label>
                    <input class="form-control" name="name" placeholder="Ej: Canino" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i><span>Cerrar</span></button>
                    <button class="btn btn-success d-inline-flex align-items-center gap-2"><i class="fa-solid fa-floppy-disk"></i><span>Guardar</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
