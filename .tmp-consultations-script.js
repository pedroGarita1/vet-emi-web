(function () {
            const pets = [];
            const speciesCatalog = [];
            const pricingMap = [];
            const inventoryCatalog = [];
            const consultationModal = document.getElementById('modalCreateConsultation');

            const petSelect = document.getElementById('new_pet_id');
            const speciesSelect = document.getElementById('new_species_id');
            const ownerInput = document.getElementById('new_owner_name');
            const breedInput = document.getElementById('new_pet_breed');
            const sizeInput = document.getElementById('new_pet_size');
            const diagnosisInput = document.getElementById('new_diagnosis');
            const costInput = document.getElementById('new_cost');
            const medicationsContainer = document.getElementById('medicationsContainer');
            const addMedicationRowBtn = document.getElementById('addMedicationRowBtn');

            const petSpeciesModal = document.getElementById('pet_species_id_modal');
            const petBreedModal = document.getElementById('pet_breed_modal');
            const petBreedLabelModal = document.getElementById('pet_breed_label_text_modal');
            const petSizeModal = document.getElementById('pet_size_modal');

            function normalizeDiagnosis(value) {
                return (value || '').trim().toLowerCase();
            }

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function findPet(petId) {
                return pets.find((pet) => String(pet.id) === String(petId));
            }

            function speciesNameById(speciesId) {
                const species = speciesCatalog.find((item) => String(item.id) === String(speciesId));
                return species ? species.name.toLowerCase() : '';
            }

            function isBreedRequired(speciesName) {
                return speciesName.includes('canino') || speciesName.includes('perro') || speciesName.includes('ave');
            }

            function isSizeRequired(speciesName) {
                return speciesName.includes('canino') || speciesName.includes('perro') || speciesName.includes('felino') || speciesName.includes('gato');
            }

            function updatePetBreedModalBehavior() {
                if (!petSpeciesModal || !petBreedModal || !petBreedLabelModal || !petSizeModal) {
                    return;
                }

                const speciesName = speciesNameById(petSpeciesModal.value);
                if (speciesName.includes('ave')) {
                    petBreedLabelModal.textContent = 'Tipo de ave';
                    petBreedModal.placeholder = 'Ej: Agaporni, Periquito';
                } else if (speciesName.includes('canino') || speciesName.includes('perro')) {
                    petBreedLabelModal.textContent = 'Tipo de perro / raza';
                    petBreedModal.placeholder = 'Ej: Pastor Aleman, Husky';
                } else {
                    petBreedLabelModal.textContent = 'Tipo / Raza';
                    petBreedModal.placeholder = 'Opcional';
                }

                petBreedModal.required = isBreedRequired(speciesName);
                petSizeModal.required = isSizeRequired(speciesName);

                if (!petSizeModal.required) {
                    petSizeModal.value = '';
                }
            }

            function resolveCost() {
                const speciesId = speciesSelect.value;
                const diagnosis = normalizeDiagnosis(diagnosisInput.value);

                if (!speciesId || !diagnosis) {
                    return;
                }

                const bySpecies = pricingMap[String(speciesId)] || {};
                if (Object.prototype.hasOwnProperty.call(bySpecies, diagnosis)) {
                    costInput.value = Number(bySpecies[diagnosis]).toFixed(2);
                }
            }

            function medOptionsMarkup() {
                const grouped = inventoryCatalog.reduce(function (carry, item) {
                    const category = item.category || 'Sin categoria';

                    if (!carry[category]) {
                        carry[category] = [];
                    }

                    carry[category].push(item);
                    return carry;
                }, {});

                return Object.keys(grouped).map(function (category) {
                    const options = grouped[category].map(function (item) {
                        return `<option value="${item.id}" data-price="${item.unit_price}">${escapeHtml(item.name)}</option>`;
                    }).join('');

                    return `<optgroup label="${escapeHtml(category)}">${options}</optgroup>`;
                }).join('');
            }

            function initializeSelect2ForElement(element, dropdownParentElement) {
                if (!element || !window.jQuery || !window.jQuery.fn.select2) {
                    return;
                }

                const $element = window.jQuery(element);

                if ($element.hasClass('select2-hidden-accessible')) {
                    return;
                }

                $element.select2({
                    width: '100%',
                    dropdownParent: window.jQuery(dropdownParentElement || consultationModal || document.body),
                    placeholder: element.getAttribute('data-placeholder') || 'Selecciona una opcion',
                    allowClear: true,
                });
            }

            function initializeConsultationSelect2(scope, dropdownParentElement) {
                if (!scope) {
                    return;
                }

                scope.querySelectorAll('.consultation-select2').forEach(function (element) {
                    initializeSelect2ForElement(element, dropdownParentElement);
                });
            }

            function refreshMedicationIndexes(targetContainer) {
                if (!targetContainer) {
                    return;
                }

                const rows = targetContainer.querySelectorAll('.med-row');
                rows.forEach(function (row, index) {
                    row.querySelectorAll('[data-field]').forEach(function (field) {
                        field.name = `medications[${index}][${field.getAttribute('data-field')}]`;
                    });
                });
            }

            function getNextMedicationIndex(targetContainer) {
                let maxIndex = -1;

                if (!targetContainer) {
                    return 0;
                }

                targetContainer.querySelectorAll('[name^="medications["]').forEach(function (field) {
                    const match = String(field.name || '').match(/medications\[(\d+)\]/);
                    if (match) {
                        maxIndex = Math.max(maxIndex, Number(match[1]));
                    }
                });

                return maxIndex + 1;
            }

            function assignMedicationNames(row, index) {
                row.querySelectorAll('[data-field]').forEach(function (field) {
                    field.name = `medications[${index}][${field.getAttribute('data-field')}]`;
                });
            }

            function addMedicationRow(targetContainer, dropdownParentElement, reindexRows) {
                if (!targetContainer) {
                    return;
                }

                const shouldReindex = reindexRows !== false;
                const nextIndex = shouldReindex ? null : getNextMedicationIndex(targetContainer);

                const wrapper = document.createElement('div');
                wrapper.className = 'med-row';
                wrapper.innerHTML = `
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Producto</label>
                            <select class="form-select med-item-select consultation-select2" data-field="inventory_item_id" data-placeholder="Selecciona producto">
                                <option value="">Selecciona</option>
                                ${medOptionsMarkup()}
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Cant.</label>
                            <input type="number" min="1" value="1" class="form-control" data-field="quantity">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Precio</label>
                            <input type="number" step="0.01" min="0" class="form-control" data-field="unit_price">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Dosis</label>
                            <input class="form-control" placeholder="Ej: 5 ml" data-field="dosage">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Cada (h)</label>
                            <input type="number" min="1" class="form-control" data-field="frequency_hours">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Cada (dias)</label>
                            <input type="number" min="1" class="form-control" data-field="frequency_days">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Dias</label>
                            <input type="number" min="1" class="form-control" data-field="duration_days">
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-med-row d-inline-flex align-items-center gap-2"><i class="fa-solid fa-xmark"></i><span>Quitar</span></button>
                        </div>
                        <div class="col-12">
                            <input class="form-control form-control-sm" placeholder="Notas de aplicacion" data-field="administration_notes">
                        </div>
                    </div>
                `;

                targetContainer.appendChild(wrapper);
                if (shouldReindex) {
                    refreshMedicationIndexes(targetContainer);
                } else {
                    assignMedicationNames(wrapper, nextIndex);
                }

                if (dropdownParentElement) {
                    initializeConsultationSelect2(wrapper, dropdownParentElement);
                }

                const select = wrapper.querySelector('.med-item-select');
                const priceInput = wrapper.querySelector('[data-field="unit_price"]');

                select.addEventListener('change', function () {
                    const option = this.options[this.selectedIndex];
                    if (option && option.dataset.price) {
                        priceInput.value = Number(option.dataset.price).toFixed(2);
                    }
                });

                wrapper.querySelector('.remove-med-row').addEventListener('click', function () {
                    if (window.jQuery && window.jQuery.fn.select2 && window.jQuery(select).hasClass('select2-hidden-accessible')) {
                        window.jQuery(select).select2('destroy');
                    }

                    wrapper.remove();
                    if (shouldReindex) {
                        refreshMedicationIndexes(targetContainer);
                    }
                });
            }

            function applySelectedPetData() {
                if (!petSelect) {
                    return;
                }

                const pet = findPet(petSelect.value);
                if (!pet) {
                    ownerInput.value = '';
                    breedInput.value = '';
                    if (sizeInput) {
                        sizeInput.value = '';
                    }
                    return;
                }

                if (pet.species_id && speciesSelect) {
                    speciesSelect.value = String(pet.species_id);
                    if (window.jQuery && window.jQuery.fn.select2) {
                        window.jQuery(speciesSelect).trigger('change');
                    }
                }

                ownerInput.value = pet.owner_name || ownerInput.value || '';
                breedInput.value = pet.breed || breedInput.value || '';
                if (sizeInput) {
                    sizeInput.value = pet.size_category
                        ? pet.size_category.charAt(0).toUpperCase() + pet.size_category.slice(1)
                        : (sizeInput.value || '');
                }

                resolveCost();
            }

            if (petSelect) {
                petSelect.addEventListener('change', applySelectedPetData);

                if (window.jQuery && window.jQuery.fn.select2) {
                    window.jQuery(petSelect).on('select2:select select2:clear', applySelectedPetData);
                }
            }

            if (speciesSelect) {
                speciesSelect.addEventListener('change', resolveCost);
            }

            if (diagnosisInput) {
                diagnosisInput.addEventListener('input', resolveCost);
                diagnosisInput.addEventListener('blur', resolveCost);
            }

            if (petSpeciesModal) {
                petSpeciesModal.addEventListener('change', updatePetBreedModalBehavior);
                updatePetBreedModalBehavior();
            }

            if (addMedicationRowBtn) {
                addMedicationRowBtn.addEventListener('click', function () {
                    addMedicationRow(medicationsContainer, consultationModal, true);
                });
                addMedicationRow(medicationsContainer, consultationModal, true);
            }

            document.querySelectorAll('[id^="addMedicationRowBtnEdit-"]').forEach(function (button) {
                const consultationId = button.id.replace('addMedicationRowBtnEdit-', '');
                const editModal = document.getElementById(`modalEditConsultation-${consultationId}`);
                const editContainer = document.getElementById(`medicationsContainerEdit-${consultationId}`);

                if (!editContainer) {
                    return;
                }

                button.addEventListener('click', function () {
                    addMedicationRow(editContainer, editModal, false);
                });

                if (editModal) {
                    editModal.addEventListener('shown.bs.modal', function () {
                        initializeConsultationSelect2(editModal, editModal);
                    });
                }
            });

            if (consultationModal) {
                consultationModal.addEventListener('shown.bs.modal', function () {
                    initializeConsultationSelect2(consultationModal, consultationModal);
                    applySelectedPetData();
                });
            }

            if (window.ClassicEditor) {
                ClassicEditor.create(document.querySelector('#treatmentEditor')).catch(function () {
                    // Ignore editor init issues to avoid blocking form usage.
                });
            }
        })();
