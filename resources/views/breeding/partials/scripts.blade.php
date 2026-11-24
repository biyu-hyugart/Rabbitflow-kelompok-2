@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('breedingModal');
            const form = document.getElementById('breedingForm');
            const method = document.getElementById('breedingMethod');
            const title = document.getElementById('breedingModalTitle');
            const male = document.getElementById('breedingMale');
            const female = document.getElementById('breedingFemale');
            const plan = document.getElementById('breedingPlan');
            const actual = document.getElementById('breedingActual');
            const status = document.getElementById('breedingStatus');
            const offspring = document.getElementById('breedingOffspring');
            const notes = document.getElementById('breedingNotes');
            const submit = document.getElementById('breedingSubmit');

            const storeUrl = @json(route('breeding.store'));
            const updateTemplate = @json(route('breeding.update', ['breeding' => '__ID__']));

            modal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                if (!trigger) {
                    return;
                }

                const mode = trigger.getAttribute('data-breeding-mode') || 'create';
                const id = trigger.getAttribute('data-breeding-id');
                const today = new Date().toISOString().slice(0, 10);

                form.reset();
                male.value = trigger.getAttribute('data-breeding-male') || (male.options[0] ? male.options[0].value : '');
                female.value = trigger.getAttribute('data-breeding-female') || (female.options[0] ? female.options[0].value : '');
                plan.value = trigger.getAttribute('data-breeding-plan') || today;
                actual.value = trigger.getAttribute('data-breeding-actual') || '';
                status.value = trigger.getAttribute('data-breeding-status') || 'Direncanakan';
                offspring.value = trigger.getAttribute('data-breeding-offspring') || '';
                notes.value = trigger.getAttribute('data-breeding-notes') || '';

                male.disabled = false;
                female.disabled = false;
                plan.readOnly = false;
                actual.readOnly = false;
                status.disabled = false;
                offspring.readOnly = false;
                notes.readOnly = false;

                if (submit) {
                    submit.style.display = '';
                }

                if (mode === 'edit') {
                    title.textContent = 'Edit Data Perkawinan';
                    method.value = 'PUT';
                    form.action = updateTemplate.replace('__ID__', id);
                    if (submit) {
                        submit.textContent = 'Simpan Perubahan';
                    }
                } else if (mode === 'view') {
                    title.textContent = 'Detail Perkawinan';
                    method.value = 'POST';
                    form.action = storeUrl;
                    male.disabled = true;
                    female.disabled = true;
                    plan.readOnly = true;
                    actual.readOnly = true;
                    status.disabled = true;
                    offspring.readOnly = true;
                    notes.readOnly = true;
                    if (submit) {
                        submit.style.display = 'none';
                    }
                } else {
                    title.textContent = 'Tambah Jadwal Perkawinan';
                    method.value = 'POST';
                    form.action = storeUrl;
                    if (submit) {
                        submit.textContent = 'Simpan Jadwal';
                    }
                }
            });
        });
    </script>
@endpush
