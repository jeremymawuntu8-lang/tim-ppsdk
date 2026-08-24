{{--
    Partial widget tanda tangan (dipakai berulang di tab Pengesahan).
    Variabel yang diperlukan:
    - $name    : nama field untuk input hidden, contoh "ketua_tim_tanda_tangan" atau "pengawas[0][tanda_tangan]"
    - $existing: path file tersimpan saat ini (untuk pratinjau via asset('storage/...')), atau null
    - $value   : nilai untuk value awal input hidden (biasanya sama dengan $existing, atau hasil old())
--}}
@php
    $existingUrl = $existing ? asset('storage/' . $existing) : '';
@endphp
<div class="signature-pad-wrap" data-existing="{{ $existingUrl }}">
    <div class="btn-group btn-group-sm ttd-mode-tabs mb-2" role="group">
        <button type="button" class="btn btn-outline-primary active ttd-mode-btn" data-mode="draw"><i class="fas fa-pen me-1"></i>Gambar</button>
        <button type="button" class="btn btn-outline-primary ttd-mode-btn" data-mode="upload"><i class="fas fa-camera me-1"></i>Upload Foto</button>
    </div>
    <div class="ttd-mode-draw">
        <canvas class="ttd-canvas"></canvas>
        <button type="button" class="btn btn-sm btn-outline-secondary btn-clear-ttd mt-2"><i class="fas fa-eraser me-1"></i>Kosongkan</button>
    </div>
    <div class="ttd-mode-upload" style="display:none;">
        <input type="file" class="form-control form-control-sm ttd-file-input" accept="image/*">
        <div class="form-text">Foto tanda tangan di kertas putih, hasil scan, atau stempel digital.</div>
        <div class="ttd-upload-preview mt-2"></div>
    </div>
    <input type="hidden" name="{{ $name }}" class="ttd-hidden-input" value="{{ $value }}">
</div>
