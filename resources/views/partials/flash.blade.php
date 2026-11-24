@if (session('status'))
    <div class="rf-flash rf-flash--success" role="status">
        <span class="material-symbols-outlined rf-icon" aria-hidden="true">check_circle</span>
        <span>{{ session('status') }}</span>
        <button type="button" class="rf-flash-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi">
            <span class="material-symbols-outlined rf-icon" aria-hidden="true">close</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="rf-flash rf-flash--danger" role="alert">
        <span class="material-symbols-outlined rf-icon" aria-hidden="true">error</span>
        <span>{{ session('error') }}</span>
        <button type="button" class="rf-flash-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi">
            <span class="material-symbols-outlined rf-icon" aria-hidden="true">close</span>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="rf-flash rf-flash--danger" role="alert">
        <span class="material-symbols-outlined rf-icon" aria-hidden="true">error</span>
        <div>
            <strong>{{ $errors->count() === 1 ? $errors->first() : 'Periksa kembali data yang Anda masukkan.' }}</strong>
            @if ($errors->count() > 1)
                <ul class="rf-flash-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        <button type="button" class="rf-flash-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi">
            <span class="material-symbols-outlined rf-icon" aria-hidden="true">close</span>
        </button>
    </div>
@endif
