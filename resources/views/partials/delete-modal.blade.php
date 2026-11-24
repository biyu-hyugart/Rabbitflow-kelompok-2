<div class="modal fade rf-modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form id="deleteForm" method="POST" action="#">
                @csrf
                @method('DELETE')

                <div class="rf-modal-body" style="padding-bottom: 0;">
                    <div class="rf-confirm-head">
                        <span class="rf-danger-icon">
                            <span class="material-symbols-outlined rf-icon" aria-hidden="true">delete</span>
                        </span>
                        <div>
                            <h3 class="rf-modal-title" id="deleteModalTitle">Hapus Data?</h3>
                            <p class="rf-confirm-text" style="margin-top: 6px;">
                                Anda akan menghapus <strong id="deleteTarget">data ini</strong>.
                                <span id="deleteMessage">Data yang dihapus tidak dapat dikembalikan.</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rf-modal-foot" style="padding: 16px 24px 24px; border-top: 0;">
                    <button type="button" class="rf-btn rf-btn--ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="rf-btn rf-btn--danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
