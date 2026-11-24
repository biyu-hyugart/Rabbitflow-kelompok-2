<div class="modal fade rf-modal" id="growthModal" tabindex="-1" aria-labelledby="growthModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="growthForm" method="POST" action="{{ old('_action', route('growth.store')) }}">
                @csrf
                <input type="hidden" name="_method" value="{{ old('_method', 'POST') }}" id="growthMethod">
                <input type="hidden" name="_action" value="{{ old('_action') }}">
                <input type="hidden" name="_modal" value="growthModal">

                <div class="rf-modal-head">
                    <div>
                        <h3 class="rf-modal-title" id="growthModalTitle">Catat Pertumbuhan Kelinci</h3>
                        <p class="rf-modal-sub" id="growthModalSub">Isi hasil penimbangan dan pengukuran terbaru.</p>
                    </div>
                    <button type="button" class="rf-modal-close" data-bs-dismiss="modal" aria-label="Tutup">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">close</span>
                    </button>
                </div>

                <div class="rf-modal-body">
                    <div class="rf-form-grid">
                        <div class="rf-field">
                            <label for="growthRabbit" class="rf-label">Kelinci <span class="rf-required">*</span></label>
                            <select id="growthRabbit" name="rabbit_id" class="rf-select" required>
                                @foreach ($rabbits as $rabbit)
                                    <option value="{{ $rabbit->id }}">{{ $rabbit->kode }} - {{ $rabbit->nama_kelinci }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rf-field">
                            <label for="growthDate" class="rf-label">Tanggal Pencatatan <span class="rf-required">*</span></label>
                            <input type="date" id="growthDate" name="tgl_pencatatan" class="rf-input" required max="{{ now()->toDateString() }}">
                        </div>
                    </div>

                    <div class="rf-form-grid">
                        <div class="rf-field">
                            <label for="growthWeight" class="rf-label">Berat Badan (kg) <span class="rf-required">*</span></label>
                            <input type="number" step="0.01" min="0.1" max="99.99" id="growthWeight" name="berat_badan" class="rf-input" placeholder="Contoh: 3.85" required inputmode="decimal">
                        </div>

                        <div class="rf-field">
                            <label for="growthLength" class="rf-label">Panjang Badan (cm) <span class="rf-required">*</span></label>
                            <input type="number" step="0.1" min="1" max="200" id="growthLength" name="panjang_badan" class="rf-input" placeholder="Contoh: 42.0" required inputmode="decimal">
                        </div>
                    </div>

                    <div class="rf-form-grid">
                        <div class="rf-field">
                            <label for="growthStatus" class="rf-label">Status Kelinci <span class="rf-required">*</span></label>
                            <select id="growthStatus" name="status_kelinci" class="rf-select" required>
                                @foreach (\App\Models\Rabbit::STATUS as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rf-field">
                            <label for="growthHealth" class="rf-label">Kondisi Kesehatan <span class="rf-required">*</span></label>
                            <input type="text" id="growthHealth" name="kondisi_kesehatan" class="rf-input" placeholder="Contoh: Sangat Baik &amp; Aktif" required maxlength="100">
                        </div>
                    </div>

                    <div class="rf-field">
                        <label for="growthNotes" class="rf-label">Keterangan</label>
                        <textarea id="growthNotes" name="keterangan" class="rf-input" rows="2" maxlength="500" placeholder="Catatan tambahan mengenai nafsu makan atau bulu..."></textarea>
                    </div>
                </div>

                <div class="rf-modal-foot">
                    <button type="button" class="rf-btn rf-btn--ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="rf-btn rf-btn--primary" id="growthSubmit">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
