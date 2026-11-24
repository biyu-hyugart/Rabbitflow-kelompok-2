<div class="modal fade rf-modal" id="breedingModal" tabindex="-1" aria-labelledby="breedingModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="breedingForm" method="POST" action="{{ old('_action', route('breeding.store')) }}">
                @csrf
                <input type="hidden" name="_method" value="{{ old('_method', 'POST') }}" id="breedingMethod">
                <input type="hidden" name="_action" value="{{ old('_action') }}">
                <input type="hidden" name="_modal" value="breedingModal">

                <div class="rf-modal-head">
                    <div>
                        <h3 class="rf-modal-title" id="breedingModalTitle">Tambah Jadwal Perkawinan</h3>
                        <p class="rf-modal-sub">Tabel <code class="rf-code">breeding_records</code> (pejantan, betina, tanggal rencana, tanggal realisasi)</p>
                    </div>
                    <button type="button" class="rf-modal-close" data-bs-dismiss="modal" aria-label="Tutup">
                        <span class="material-symbols-outlined rf-icon" aria-hidden="true">close</span>
                    </button>
                </div>

                <div class="rf-modal-body">
                    <div class="rf-form-grid">
                        <div class="rf-field">
                            <label for="breedingMale" class="rf-label">Kelinci Jantan <span class="rf-required">*</span></label>
                            <select id="breedingMale" name="male_rabbit_id" class="rf-select" required>
                                @foreach ($maleRabbits as $rabbit)
                                    <option value="{{ $rabbit->id }}">{{ $rabbit->kode }} - {{ $rabbit->nama_kelinci }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rf-field">
                            <label for="breedingFemale" class="rf-label">Kelinci Betina <span class="rf-required">*</span></label>
                            <select id="breedingFemale" name="female_rabbit_id" class="rf-select" required>
                                @foreach ($femaleRabbits as $rabbit)
                                    <option value="{{ $rabbit->id }}">{{ $rabbit->kode }} - {{ $rabbit->nama_kelinci }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="rf-form-grid">
                        <div class="rf-field">
                            <label for="breedingPlan" class="rf-label">Tanggal Rencana Kawin <span class="rf-required">*</span></label>
                            <input type="date" id="breedingPlan" name="tgl_rencana_kawin" class="rf-input" required>
                        </div>

                        <div class="rf-field">
                            <label for="breedingActual" class="rf-label">Tanggal Realisasi Kawin</label>
                            <input type="date" id="breedingActual" name="tgl_realisasi_kawin" class="rf-input">
                            <span class="rf-help">Kosongkan jika belum terlaksana.</span>
                        </div>
                    </div>

                    <div class="rf-form-grid">
                        <div class="rf-field">
                            <label for="breedingStatus" class="rf-label">Status Perkawinan <span class="rf-required">*</span></label>
                            <select id="breedingStatus" name="status_perkawinan" class="rf-select" required>
                                @foreach (\App\Models\BreedingRecord::STATUS as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rf-field">
                            <label for="breedingOffspring" class="rf-label">Jumlah Anakan</label>
                            <input type="number" id="breedingOffspring" name="jumlah_anakan" class="rf-input" min="0" max="20" placeholder="Contoh: 7" inputmode="numeric">
                            <span class="rf-help">Isi setelah kelahiran terjadi.</span>
                        </div>
                    </div>

                    <div class="rf-field">
                        <label for="breedingNotes" class="rf-label">Keterangan</label>
                        <textarea id="breedingNotes" name="keterangan" class="rf-input" rows="2" maxlength="500" placeholder="Catatan kesesuaian pejantan, respon indukan, atau estimasi tanggal partus..."></textarea>
                    </div>
                </div>

                <div class="rf-modal-foot">
                    <button type="button" class="rf-btn rf-btn--ghost" data-bs-dismiss="modal">Batal</button>
                    @if ($isOwner)
                        <button type="submit" class="rf-btn rf-btn--primary" id="breedingSubmit">Simpan Jadwal</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
