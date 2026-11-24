@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('growthModal');
            const form = document.getElementById('growthForm');
            const method = document.getElementById('growthMethod');
            const title = document.getElementById('growthModalTitle');
            const sub = document.getElementById('growthModalSub');
            const rabbit = document.getElementById('growthRabbit');
            const date = document.getElementById('growthDate');
            const weight = document.getElementById('growthWeight');
            const length = document.getElementById('growthLength');
            const status = document.getElementById('growthStatus');
            const health = document.getElementById('growthHealth');
            const notes = document.getElementById('growthNotes');
            const submit = document.getElementById('growthSubmit');

            const storeUrl = @json(route('growth.store'));
            const updateTemplate = @json(route('growth.update', ['growth' => '__ID__']));

            modal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                if (!trigger) {
                    return;
                }

                const mode = trigger.getAttribute('data-growth-mode') || 'create';
                const id = trigger.getAttribute('data-growth-id');
                const today = new Date().toISOString().slice(0, 10);

                form.reset();
                rabbit.value = trigger.getAttribute('data-growth-rabbit') || (rabbit.options[0] ? rabbit.options[0].value : '');
                date.value = trigger.getAttribute('data-growth-date') || today;
                weight.value = trigger.getAttribute('data-growth-weight') || '';
                length.value = trigger.getAttribute('data-growth-length') || '';
                status.value = trigger.getAttribute('data-growth-status') || 'Sehat';
                health.value = trigger.getAttribute('data-growth-health') || '';
                notes.value = trigger.getAttribute('data-growth-notes') || '';

                if (mode === 'edit') {
                    title.textContent = 'Edit Catatan Pertumbuhan';
                    sub.textContent = 'Perbarui hasil pencatatan.';
                    method.value = 'PUT';
                    form.action = updateTemplate.replace('__ID__', id);
                    submit.textContent = 'Simpan Perubahan';
                } else {
                    title.textContent = 'Catat Pertumbuhan Kelinci';
                    sub.textContent = 'Isi hasil penimbangan dan pengukuran terbaru.';
                    method.value = 'POST';
                    form.action = storeUrl;
                    submit.textContent = 'Simpan Catatan';
                }
            });
        });
    </script>
@endpush
